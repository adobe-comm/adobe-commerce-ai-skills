---
name: adobe-commerce-checkout
description: >
  Guides Adobe Commerce checkout customization on PaaS/on-premises: checkout
  layout and steps, quote totals collectors, shipping/payment method renderers,
  place-order path, and related Knockout/UI-component or Hyva checkout behaviour.
  Use when changing checkout, cart-to-order, payment/shipping display on checkout,
  or quote collectors. Do not use for payment gateway PCI/token vault security
  audits (security-review), for GTM purchase tags alone (frontend-and-tracking),
  for L0 CSS/spacing-only tweaks on checkout buttons (frontend-and-tracking),
  for MSI salable qty root cause (inventory-msi), or for ACCS headless checkout
  owned by Adobe drop-in skills.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.2"
  verified-against: "Magento Checkout / Quote APIs; CoE PaaS scope 2026-09-22"
---

# Checkout

## When to use / skip

Use: checkout steps/layout, quote totals, shipping/payment renderers, place-order plugins.
Skip: ACCS drop-in checkout (Adobe skills); CSP bypass approval (security-review); analytics-only (frontend-and-tracking).

## Procedure

1. **Platform:** PaaS/on-prem Magento checkout vs ACCS/EDS drop-ins — if SaaS/drop-in, stop and use Adobe storefront/checkout skills.
2. Trace the active path: Onepage vs custom, Hyva checkout vs Luma, GraphQL cart mutations if headless. Exemplar in this repo wins.
3. Prefer plugins on Quote/Totals/Payment Method List over preferences. Keep collectors bounded and ordered explicitly.
4. Hot path: no sync ERP/HTTP on place-order without timeout and non-blocking failure mode (else integration-work + performance-review).
5. Payment: tokenized methods only; never log PAN/CVV. New scripts on payment pages → implement via frontend-and-tracking, security sign-off via security-review.
6. Prefix CLI with `ddev exec` when `.ddev/` exists. Validate guest + logged-in, multi-shipping if enabled. Never production.
7. See [references/checkout-checklist.md](references/checkout-checklist.md).

## Must not

- Disable form keys, CSP, or SRI to "make checkout work".
- Add unbounded collection loads on quote collect totals.
- Deploy checkout experiments to production without explicit confirmation.
