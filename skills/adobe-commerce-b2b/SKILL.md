---
name: adobe-commerce-b2b
description: >
  Guides Adobe Commerce B2B domain work on PaaS/on-premises: Company accounts,
  shared catalogs, negotiable quotes, requisition lists, company roles/permissions,
  and related quote-to-order flows. Use when the ticket or code touches Magento_B2b,
  Magento_Company, Magento_SharedCatalog, Magento_NegotiableQuote, Magento_RequisitionList,
  or B2B admin/storefront behaviour. Do not use for ACCS/SaaS B2B (out-of-process /
  Adobe skills), for generic module scaffolding without B2B context (module-scaffold),
  or for MSI-only stock issues (inventory-msi).
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.1"
  verified-against: "Adobe Commerce B2B module set; CoE PaaS scope 2026-09-22"
---

# B2B

## When to use / skip

Use: company, shared catalog, negotiable quote, requisition list, B2B permissions.
Skip: ACCS/ACO in-process customization; B2C-only catalog; pure MSI without company context.

## Procedure

1. **Platform:** confirm PaaS/on-prem B2B modules are present (`app/etc/config.php`, composer, `bin/magento module:status` via `ddev exec` when `.ddev/` exists). On ACCS/ACO, stop and redirect to Adobe/SaaS B2B extension paths.
2. Confirm which B2B features the project actually enables — do not assume the full B2B suite.
3. Find the nearest project exemplar (company plugin, shared-catalog price plugin, quote approval flow) and mirror it.
4. Blast radius: company scope vs website/store, shared-catalog price vs catalog rules, quote approval ACL, customer vs company user permissions.
5. Prefer plugins/observers on B2B APIs over preferences. Schema changes need declarative schema + upgrade safety.
6. Validate with the project's tests or a documented B2B persona checklist (company admin, buyer, guest must fail closed). Never against production.
7. See [references/b2b-checklist.md](references/b2b-checklist.md).

## Must not

- Invent B2B modules that are not installed.
- Bypass company permissions or quote approval for convenience.
- Mix B2C catalog assumptions into shared-catalog pricing without evidence.
