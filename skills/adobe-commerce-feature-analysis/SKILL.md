---
name: adobe-commerce-feature-analysis
description: >
  Traces how an existing Adobe Commerce feature or behavior is actually
  implemented across the layers involved (PHP, XML configuration, database,
  API/GraphQL, frontend, queues/cron, integrations, tracking) before it is
  changed. Use for bugs and change requests at depth L2 or L3, or whenever the
  code path is unclear. Do not use for L0/L1 edits whose files are already known,
  for PR/diff review (use code-review), for whole-repo structure maps (use
  project-understanding), for B2B/MSI/checkout domain playbooks when those skills
  fit better, or for greenfield App Builder work owned by Adobe official skills.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.2"
  verified-against: "Adobe PHP DI/events patterns; Cursor skills 2026-09-22"
---

# Feature analysis

## When to use / skip

Use: L2/L3 change, unclear path, bug reproduction in code.
Skip: known single-file L0/L1; pure docs; ACCS/ACO in-process PHP customization (redirect out-of-process / Adobe skills).

## Procedure

1. **Platform / local:** if ACCS/ACO, stop in-process PHP plugin tracing as the fix path — map App Builder/events/webhooks instead. Prefix Magento CLI with `ddev exec` when `.ddev/` exists. Never against production.
2. State the entry point type: route, event, cron, queue topic, CLI, API/GraphQL, UI action.
3. Follow the real wiring — do not assume Magento defaults:
   - Routes / controllers or GraphQL resolvers
   - DI preferences, plugins, virtual types (per area: global, frontend, adminhtml, webapi, graphql)
   - Events / observers
   - Layout XML / UI components / templates
   - Declarative schema, data/schema patches
   - Cron, queue consumers, indexers if on the path
4. If several implementations exist, resolve which is active from config and DI **before** asking the developer. Prefer the host clarifying-question tool when available; else one A/B/C chat question only if the repo cannot decide and the answer changes the fix.
5. Runtime introspection: only if a local/dev environment is available. Prefer project-documented commands. Common Magento CLI examples (confirm they exist for this project before running): `bin/magento module:status`, `bin/magento dev:di:info <type>` — with `ddev exec` when `.ddev/` exists. Label as training knowledge - verify if unsure for the installed version.
6. Load recipes from [references/tracing-recipes.md](references/tracing-recipes.md) for the entry-point type.
7. Tag each step CONFIRMED / INFERRED / UNKNOWN. State impact of each UNKNOWN.

## Must not

- Assume a flow that was not traced.
- Expand beyond the relevant path.
- Ask what config/DI can answer.
- Edit code in this skill (analysis only unless the user also asked for a fix).

## Output format

Ordered flow list:

```
1. ENTRY: <type> — CONFIRMED <path>
2. ...
Active implementation: <why this one wins>
Unknowns: <item> — impact: <...>
Suggested depth: L2|L3 — <one-line justification>
```
