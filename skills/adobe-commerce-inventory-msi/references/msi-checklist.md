# MSI checklist (load on demand)

| Topic | Check |
|-------|--------|
| Modules | `Magento_Inventory`, `InventoryApi`, SalesMetada, ConfigurableProduct, etc. as installed |
| Mapping | stocks ↔ sources ↔ websites/sales channels |
| Salable qty | reservations outstanding; order/cancellation compensation |
| Source selection | algorithm preferences / plugins already in project |
| Indexers | inventory indexer status; avoid full reindex as first fix |
| ERP | whether stock is overwritten by integration — hand contract to integration-work |

Evidence: `bin/magento inventory:reservation:list-cli` (if present), module status, project inventory plugins.
