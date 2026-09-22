---
name: adobe-commerce-integration-work
description: >
  Analyzes and changes integrations between Adobe Commerce and external systems
  (ERP, CRM, Odoo, PIM, OMS, WMS, payment, shipping, tax, marketing platforms,
  middleware, webhooks, message queues, cron-based sync, App Builder events).
  Use when a task touches data flowing to or from another system, a sync failure
  or backlog, payload or contract changes, or a new integration. Do not use for
  changes with no external boundary. Prefer debugging first when the symptom is
  an unexplained failure and the external contract is not yet implicated.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.1"
  verified-against: "Adobe PaaS/SaaS extension compatibility 2026-09-22"
---

# Integration work

## When to use / skip

Use: ERP/CRM/OMS/PIM sync, webhooks, queues, payload contracts, App Builder events.
Skip: pure storefront UI; Magento-only defects with no external boundary (debugging).

## Procedure

1. Build the inventory from evidence only: config files, HTTP client usage, queue topics and consumers, cron definitions, webhook config, environment variable names (never values), App Builder events. List only integrations that exist.
2. On ACCS/SaaS, custom logic is out-of-process — App Builder actions, Commerce events, webhooks, API Mesh. SaaS supports a predefined set of events and webhooks configured via Admin or REST, unlike PaaS XML registration. Defer to Adobe's official skills for App Builder implementation.
3. Prefix Magento CLI with `ddev exec` when `.ddev/` exists. Never call production endpoints or use production credentials.
4. For each integration the task touches, establish and state:

   | Aspect | Why it matters |
   |--------|----------------|
   | Direction and trigger | Who initiates, on what event or schedule |
   | Source of truth | Which system wins on conflict |
   | Payload contract | Fields, types, required vs optional, versioning |
   | Auth method | Mechanism only, never secret values |
   | Idempotency | Safe replay, duplicate suppression key |
   | Retry and timeout | Backoff, max attempts, connect/read timeouts |
   | Failure handling | Dead letter, alerting, manual replay procedure |
   | Ordering | Whether sequence matters and how it is preserved |
   | Observability | Log fields, correlation id, how to trace one record end to end |

5. Decide explicitly whether a failing non-critical sync may block a customer-facing flow such as checkout. Default: it must not. Make the decision visible.
6. Error handling: never swallow exceptions; fail visibly with enough context to trace the record. No silent `catch` blocks.
7. Test against mocks, sandboxes, or recorded fixtures.
8. Reference [references/integration-checklist.md](references/integration-checklist.md) when adding or changing a contract.

## Must not

- List integrations that are not present in the repo.
- Print, log, or echo credentials, tokens, or full payment payloads.
- Add a synchronous external call to a hot path without justification and a timeout.
- Assume a queue exists because the platform supports one.

## Output

Integrations touched, the aspects above for each, the change made, failure behaviour, and how to trace one record.
