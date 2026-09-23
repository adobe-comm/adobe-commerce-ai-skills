# External integrations (ERP / CRM)

verified: 2026-09-22

Evidence-based inventory for the **acme/plain-store** fixture. Last reviewed against files in this repository only.

## Summary

| System type | Present in repo | Notes |
|-------------|-----------------|-------|
| ERP | No | No order/inventory export modules, HTTP clients, or ERP-related config |
| CRM | No | No customer sync, marketing connectors, or CRM API clients |

There are **no ERP or CRM integrations** to operate or trace in this codebase.

## How this was determined

- **`composer.json`** describes the project as a synthetic plain store with **no integrations**.
- **`app/etc/config.php`** enables only `Magento_Store` and `Acme_Catalog` — no custom integration modules.
- **Custom code** under `app/code/` is limited to `Acme\Catalog` (a stub `ProductInfo` model); no queue consumers, cron jobs, webhooks, or outbound HTTP clients.
- No `etc/queue*.xml`, `crontab.xml`, integration `config.xml`, or environment-variable names pointing at an ERP or CRM endpoint.

## Customer-facing impact

Not applicable: nothing syncs to an external ERP or CRM, so checkout and order placement are not blocked by external sync failures.

## If integrations are added later

For each new ERP or CRM boundary, document direction and trigger, source of truth, payload contract, auth mechanism (never secret values), idempotency, retry/timeouts, failure handling, ordering, and how to trace one record end to end. See the project’s Adobe Commerce integration checklist in the skills pack (`adobe-commerce-integration-work/references/integration-checklist.md`).
