---
name: adobe-commerce-inventory-msi
description: >
  Guides Multi-Source Inventory (MSI) and stock work on Adobe Commerce
  PaaS/on-premises: sources, stocks, reservations, salable quantity, store-source
  mapping, and related indexers. Use when the task involves MSI, reservations,
  salable qty mismatches, source selection, or Magento_Inventory* modules. Do
  not use for ACCS/ACO inventory SaaS services without PaaS MSI, for B2B company
  permissions (b2b), or for checkout payment UI (checkout).
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.2"
  verified-against: "Magento MSI / Inventory modules; CoE PaaS scope 2026-09-22"
---

# Inventory / MSI

## When to use / skip

Use: MSI sources/stocks, reservations, salable qty bugs, source selection algorithms.
Skip: ACCS without MSI; simple catalog attribute edits; ERP sync contracts (integration-work after stock truth is clear).

## Procedure

1. **Platform:** confirm MSI modules (`Magento_Inventory*`) via config/composer/module status (`ddev exec` when `.ddev/` exists). On ACCS/ACO, do not invent PaaS MSI plugins — use the SaaS inventory model / Adobe guidance.
2. Establish source of truth: which source/stock serves which website/store; reservations vs `quantity`; any ERP overwrite.
3. Trace: reservation placement on order, compensation on cancel/credit memo, indexer `inventory` / stock status.
4. Prefer existing Inventory APIs and plugins over direct table writes. Never edit reservation tables by hand as a "fix".
5. Validate with bounded SKUs on local/dev; state indexer commands the developer must run. Never production.
6. See [references/msi-checklist.md](references/msi-checklist.md).

## Must not

- Truncate reservation tables to clear a symptom.
- Assume single-stock legacy behaviour on an MSI project (or the reverse).
- Call production inventory APIs.
