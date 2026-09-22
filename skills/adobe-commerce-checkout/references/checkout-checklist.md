# Checkout checklist (load on demand)

| Layer | Examples |
|-------|----------|
| Layout / UI | `checkout_index_index`, step configuration, Hyva checkout modules if present |
| Quote | address, shipping assignment, totals collectors, payment method availability |
| Place order | `PaymentManagement` / `CartManagement` plugins, order placement interceptors |
| Shipping/Payment | method renderers, availability plugins, offline vs gateway |
| Headless | GraphQL `setPaymentMethodOnCart`, `placeOrder` — match project schema |

Validation: empty cart, out-of-stock, payment decline, shipping estimate, tax, multi-website.
