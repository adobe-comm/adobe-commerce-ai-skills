# Acme Cloud-shaped Commerce (eval fixture)

Synthetic Adobe Commerce Cloud-shaped codebase for skill evals. Custom code lives under `app/code/`.

## Brainvire_ErpSync (ERP integration)

Module: `Brainvire_ErpSync` (`app/code/Brainvire/ErpSync/`). Integrates Magento sales orders with an external ERP over HTTP using two separate paths (see `docs/adr/001-erp-order-export-queue-cron-not-place-order-sync.md`):

1. **Stock reservation (synchronous on place order)** — Before the order is persisted, `OrderManagementPlacePlugin` calls `StockReservationService`, which POSTs a bounded reservation request via `ErpStockReservationClient`. Checkout may fail when reservation fails and `block_checkout_on_failure` is enabled. This path does **not** send the full order export payload.
2. **Order export (asynchronous)** — After the order exists in Magento, the full export payload is POSTed to the ERP via message queue, with in-consumer retries and cron-based recovery. Place order does **not** perform this HTTP export synchronously.

### Order export flow

1. **Enqueue** — `Cron/ExportOrders` runs every 15 minutes (`etc/crontab.xml`, job `brainvire_erp_order_export`) and is intended to publish pending orders; the cron class is currently a **fixture stub** (no selection/enqueue logic yet). Operators can manually enqueue with `bin/magento brainvire:erp:replay <order-id>` (`Console/Command/ReplayOrderExportCommand.php`).
2. **Queue** — AMQP topic/consumer `brainvire.erp.order.export` on queue `brainvire.erp.order.export` (`etc/queue_consumer.xml`, `communication.xml`, `queue_topology.xml`).
3. **Consumer** — `OrderExportConsumer::process` reads a JSON message containing `order_id`, then calls `OrderExportService::exportByOrderId`. If in-process retries are exhausted (`ExportRetryableException`), the order ID is recorded in `FailedExportRegistry` for the retry cron.
4. **Export** — `OrderExportService` loads the order, builds a JSON payload, and posts it through `ErpOrderExportClient` inside `RetryExecutor` (exponential backoff up to `max_attempts`).
5. **Retry cron** — `Cron/RetryFailedExports` runs hourly (`brainvire_erp_export_retry`) and re-attempts exports registered in `FailedExportRegistry`.

### HTTP export payload (current contract)

| Field | Description |
|-------|-------------|
| `idempotency_key` | `magento-order-{increment_id}` |
| `order_id` | Magento entity ID |
| `increment_id` | Order increment ID |
| `grand_total` | Order grand total |
| `discount_amount` | Order-level discount total in order currency |
| `currency` | Order currency code |
| `line_items` | Visible order lines (array) |
| `line_items[].sku` | Product SKU |
| `line_items[].qty` | Quantity ordered |
| `line_items[].discount_amount` | Line discount in order currency (excludes tax/shipping) |

### Configuration

Store-scoped paths under **Stores → Configuration → Brainvire → ERP Sync** (order export fields in `etc/adminhtml/system.xml`; defaults in `etc/config.xml`):

**Order export** (`brainvire_erp/order_export/`):

| Path | Purpose | Default |
|------|---------|---------|
| `sandbox_url` | ERP POST endpoint for order export | (empty — must be set) |
| `api_key` | API key (encrypted in admin) | (empty) |
| `max_attempts` | Max export attempts per message | `5` |
| `initial_delay_seconds` | First retry delay | `2` |
| `max_delay_seconds` | Retry delay cap (exponential backoff) | `60` |
| `connect_timeout_seconds` | cURL connect timeout | `5` |
| `read_timeout_seconds` | cURL read timeout | `30` |

If `sandbox_url` is empty, export fails permanently (`ExportPermanentFailureException`).

**Stock reservation** (`brainvire_erp/stock_reservation/` — defaults only in this fixture; configure via `config.php` / env if needed):

| Path | Purpose | Default |
|------|---------|---------|
| `enabled` | Run reservation on place order | `1` |
| `reservation_url` | ERP POST endpoint for reservation | (empty — must be set) |
| `connect_timeout_seconds` | cURL connect timeout | `2` |
| `read_timeout_seconds` | cURL read timeout | `8` |
| `block_checkout_on_failure` | Fail place order when reservation fails | `1` |

### Retries and errors (order export)

- **Retryable:** transport errors, HTTP `429`, and HTTP `5xx` (`ExportRetryableException`). `RetryExecutor` retries with exponential backoff up to `max_attempts`; exhausted retries are deferred to `RetryFailedExports` via `FailedExportRegistry`.
- **Permanent:** missing URL, client HTTP `4xx` (except `429`), invalid queue payload, or missing order (logged; consumer does not rethrow for not-found/permanent cases).

### Operations

- Run Magento message queue consumers for `brainvire.erp.order.export` (AMQP).
- Run cron so `ExportOrders` can enqueue pending work once implemented, and so `RetryFailedExports` can replay failed exports hourly.
- Use `bin/magento brainvire:erp:replay <order-id>` to re-enqueue a single order after fixing ERP or configuration issues.
- Monitor `var/log` for ERP export and stock reservation log lines from the consumer, clients, retry executor, and place-order plugin path.
