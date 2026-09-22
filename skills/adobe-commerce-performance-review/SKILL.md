---
name: adobe-commerce-performance-review
description: >
  Checks Adobe Commerce changes for performance risk: N+1 and unbounded
  collection loads, heavy work on high-traffic paths (category, product, cart,
  checkout), full-page-cache and cache-invalidation impact, indexers, queue and
  cron load, external-call fan-out, and front-end payload size. Use for hot-path
  changes, loops over catalog or customer or order data, new external calls, or
  caching changes. Do not use for low-traffic admin edits with bounded data,
  CSS/copy-only edits, or full security audits (security-review).
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.1"
  verified-against: "Adobe Commerce FPC/indexer patterns; CoE depth ladder 2026-09-22"
---

# Performance review

Measure before optimizing. Findings only where this change can hurt.

## When to use / skip

Use: hot paths, collection loops, new HTTP/ERP calls in request cycle, cache/indexer changes, checkout/cart/category/product listing.
Skip: low-traffic admin CRUD with bounded data; pure docs/CSS; production profiling without approval.

## Procedure

1. **Platform / local:** on ACCS/ACO, focus on API/App Builder latency and SaaS constraints rather than Magento FPC/indexer internals that do not apply. Prefix local Magento CLI with `ddev exec` when `.ddev/` exists. Never profile or load-test production.
2. Identify whether the changed path is **hot** (storefront catalog, product, cart, checkout, search, customer account listing) or **cold** (admin config, one-off CLI). Depth of review follows that.
3. Check collections and queries:
   - Unbounded `getCollection()->load()` / repository `getList` without page size
   - N+1 (load inside a loop; attribute/extension attributes fetched per item)
   - Missing filters that the project's exemplar uses for the same entity
4. Request-cycle external calls: any sync HTTP/SDK call on a hot path needs a timeout, a failure mode that does not block checkout unless explicitly required, and a justification. Prefer async (queue/cron) when the project already does.
5. Cache / FPC:
   - Does the change vary output by customer, store, or currency without a correct cache key / identity?
   - Does it invalidate overly broad tags (full catalog flush for one SKU)?
6. Indexers: new searchable/filterable attributes or columns that need indexer coverage — say so; do not invent indexer config that the project does not use.
7. Cron/queue: new jobs must be bounded (batch size). Check they will not pile up under peak order volume if volumes are known; otherwise mark UNKNOWN.
8. Front-end: avoid shipping large unminified assets or duplicate RequireJS/Alpine modules when the project already has a pattern. Defer deep EDS payload work to Adobe storefront skills when present.
9. Prefer a cheap measurement (profiler, New Relic/AAPM if the project has it, or a timed local request) over speculative micro-optimizations. If you cannot measure, label the risk INFERRED.

## Must not

- Demand a rewrite of unrelated hot paths.
- Recommend disabling FPC or flattening the catalog as a "fix".
- Claim a performance improvement without before/after evidence from this session.

## Output

```
Path heat: hot|cold — <why>
Findings:
- <SEVERITY> <file:line> — why it hurts under load — minimal fix
Measurement: Ran: <cmd or "none"> -> <result>
Out of scope: ...
```
