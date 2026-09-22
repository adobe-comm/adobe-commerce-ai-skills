---
name: adobe-commerce-module-scaffold
description: >
  Generates Adobe Commerce module and extension-point code that matches this
  project's existing patterns: new modules, plugins, observers, DI wiring,
  declarative schema and data/schema patches, repositories, CLI commands, cron
  jobs, queue consumers, GraphQL resolvers, admin config with ACL, and their
  unit tests. Use when asked to create, add, scaffold, generate, or wire up any
  of these in a Magento or Adobe Commerce PaaS/on-premises codebase. Do not use
  for ACCS/SaaS out-of-process work, App Builder actions, or Edge Delivery
  drop-ins, which belong to Adobe's official skills.
paths:
  - "**/*.php"
  - "**/etc/**/*.xml"
  - "**/registration.php"
  - "**/composer.json"
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.0"
  verified-against: "Magento 2.4.x module structure; Adobe PHP developer docs 2026-09-22"
---

# Module scaffold

Fastest path from "add X" to working, convention-matching code.

## When to use / skip

Use: create module, add plugin/observer/patch/command/cron/consumer/resolver/admin config.
Skip: ACCS/SaaS customization (out-of-process), App Builder actions, EDS drop-ins.

## Procedure

1. Confirm the platform supports in-process PHP (PaaS/on-prem). On ACCS/ACO, stop and redirect to out-of-process options.
2. Find the nearest exemplar module in `app/code` and mirror it: namespace style, `declare(strict_types=1)` usage, constructor DI style, license headers, test layout. Exemplar wins over generic Magento style.
3. Determine the correct extension point before writing code:
   - New behaviour around existing public method: **plugin** (before/after preferred over around)
   - Reaction to a dispatched event: **observer**
   - Replace an implementation entirely: **preference** (justify why a plugin is insufficient)
   - Structural DB change: `db_schema.xml` + whitelist
   - Data change/backfill: `Setup/Patch/Data`
4. Generate the minimum file set (see [references/file-sets.md](references/file-sets.md)) — no speculative files, no unused interfaces.
5. Add ACL and config defaults when introducing admin config or admin routes.
6. Write a unit test for new logic if the project has a unit test harness; targeted, not mock-everything.
7. State the commands the developer must run locally (do not run against shared environments):
   `bin/magento module:enable`, `setup:upgrade`, `setup:di:compile`, `cache:flush` — only those actually needed for the change. Prefix with `ddev exec` when `.ddev/` exists.

## Must not

- Create a new module when an existing project module is the right home.
- Add `around` plugins, `ObjectManager` calls, or preferences without justification.
- Generate README/docs files unless asked.
- Invent version-specific APIs; check installed `vendor/magento` when unsure.

## Output

The files created or changed, the extension point chosen with a one-line reason, and the local commands required.
