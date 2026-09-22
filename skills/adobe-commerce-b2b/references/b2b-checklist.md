# B2B checklist (load on demand)

Confirm against installed modules and project exemplars — do not treat as mandatory file list.

| Area | Look for |
|------|----------|
| Company | `Magento_Company`, company user roles, structure, credit limits if used |
| Shared catalog | `Magento_SharedCatalog`, category/product assignment, pricing vs standard catalog |
| Negotiable quote | `Magento_NegotiableQuote`, approval workflow, email templates, ACL |
| Requisition lists | `Magento_RequisitionList` |
| Permissions | Company role resources vs Magento customer ACL |
| Storefront | Company login, punchout if present — mirror existing theme patterns |

Validation ideas: company admin can approve; buyer cannot; shared-catalog SKU visibility; quote → order totals.
