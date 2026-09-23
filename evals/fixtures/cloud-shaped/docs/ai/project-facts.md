# Project facts (AI / onboarding)

verified: 2026-09-22

Facts here are durable context the codebase alone does not spell out. Each line must stay evidence-backed; do not treat as truth until a human accepts the diff.

- ERP is the source of truth for stock: Commerce must obtain a successful ERP stock reservation before an order is persisted; place-order can fail when the ERP rejects or cannot confirm stock (configurable via `brainvire_erp/stock_reservation/block_checkout_on_failure`). — evidence: `app/code/Brainvire/ErpSync/etc/di.xml`, `app/code/Brainvire/ErpSync/Plugin/Sales/Api/OrderManagementPlacePlugin.php`, `app/code/Brainvire/ErpSync/Model/StockReservationService.php`, `app/code/Brainvire/ErpSync/Model/Api/ErpStockReservationClient.php` — verified: 2026-09-22
