# ADR-001: ERP order export via queue and cron (not synchronous HTTP on place-order)

verified: 2026-09-22

## Context

`Brainvire_ErpSync` must send Magento sales orders to an external ERP over HTTP. The place-order path is latency-sensitive and must stay reliable when the ERP is slow or unavailable. Full order export payloads are larger and more failure-prone than a bounded stock-reservation call, and exports benefit from retries and operational replay without blocking checkout.

Evidence: module overview in `README.md`; HTTP export in `app/code/Brainvire/ErpSync/Model/OrderExportService.php` and `Model/Api/ErpOrderExportClient.php`; place-order integration limited to stock reservation in `Plugin/Sales/Api/OrderManagementPlacePlugin.php` and `Model/StockReservationService.php`.

## Decision

**Order export** runs **asynchronously**: orders are enqueued for AMQP topic/queue `brainvire.erp.order.export`, processed by `Model/OrderExportConsumer`, with **cron** jobs to enqueue pending work (`Cron/ExportOrders`, schedule in `etc/crontab.xml`) and to retry failures (`Cron/RetryFailedExports`). Manual re-enqueue is supported via `Console/Command/ReplayOrderExportCommand.php`.

We do **not** POST the full order export to the ERP synchronously inside `OrderManagementInterface::place` (or equivalent checkout submit handlers).

**Stock reservation** on the ERP may still run **synchronously** on place-order with configured connect/read timeouts (`StockReservationService`), because inventory commitment is a separate, bounded contract from post-order export.

## Alternatives considered

- **Synchronous HTTP order export on place-order** — Rejected: couples checkout success and customer wait time to ERP availability and response time; harder to apply exponential backoff and hourly/dedicated retry without blocking or losing work; increases risk of duplicate or partial exports under timeout edge cases.

- **Fire-and-forget HTTP from place-order (no queue)** — Rejected: no durable handoff if the web process dies mid-request; weaker operational visibility and replay compared to Magento message queue plus cron-based retry registry (`Model/Export/FailedExportRegistry.php`).

## Consequences

- **Positive:** Checkout stays responsive; export retries (`Model/Export/RetryExecutor.php`) and cron retry can absorb ERP outages; operators can replay via CLI.
- **Negative:** Eventual consistency between Magento “order placed” and ERP “order received”; requires running AMQP consumers and cron (`etc/queue_consumer.xml`, `etc/crontab.xml`).
- **Follow-ups:** Implement pending-order selection/enqueue in `Cron/ExportOrders.php` (currently a fixture stub per `README.md`); monitor consumer and retry cron logs under `var/log`.
