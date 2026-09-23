---
name: adobe-commerce-coding-standards
description: >
  Applies Adobe Commerce / Magento / PHP conventions when editing existing
  backend PHP, module XML, GraphQL schema, or CLI/cron/queue code so it matches
  this project's exemplars and lint rules. Use while changing existing module
  code for style, DI, plugins, or config consistency. Do not use to scaffold a
  brand-new module or extension point (module-scaffold), for storefront markup,
  LESS/CSS, RequireJS/Alpine, or analytics (frontend-and-tracking), for CSP/SRI
  security review (security-review), for docs-only/copy, or for App Builder JS
  under Adobe official skills.
paths:
  - "**/*.php"
  - "**/*.graphqls"
  - "**/etc/**/*.xml"
  - "**/view/**/*.xml"
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.2"
  verified-against: "magento/magento-coding-standard; Adobe PHP developer practices 2026-09-22"
---

# Coding standards

## When to use / skip

Use: conventions on **existing** PHP, module XML, GraphQL schema, CLI/cron/queue.
Skip: new extension-point scaffolding; template/JS/tracking; CSP security review; docs-only; ACCS/ACO in-process PHP (stop — out-of-process only).

## Procedure

1. **Platform:** if ACCS/ACO/SaaS markers, do not write in-process PHP plugins/observers; redirect to App Builder / events / webhooks / Adobe skills.
2. Precedence: (1) existing valid project convention, (2) Adobe/Magento standards, (3) PHP/PSR. On conflict, surface it in one line — do not silently choose.
3. Find the nearest exemplar (similar class or module in this repo) and mirror structure, naming, and DI style.
4. Load only the reference you need:
   - [references/backend.md](references/backend.md)
   - [references/xml-config.md](references/xml-config.md)
   - [references/graphql.md](references/graphql.md)
   - [references/cli-cron-queue.md](references/cli-cron-queue.md)
5. Tooling: detect the project's phpcs ruleset, static analysis, and lint config. Official Magento standard package is `magento/magento-coding-standard` (needs phpcs `installed_paths`). Source: https://github.com/magento/magento-coding-standard
   Many of our projects have no linter installed yet. When none exists, say so plainly, apply conventions by hand from the nearest exemplar, and offer the one-time setup as its own task — do not claim a standard was enforced.
6. Run linters on **changed files only**, prefixed with `ddev exec` when `.ddev/` exists. Report: `Ran: <cmd> -> <result>. Not run: <what> because <reason>.`
7. Verify version-specific APIs via terminal (`cat`/`rg`/`detect-stack.sh`) against installed `vendor/magento` — do not rely on `@vendor/...` reads. Never edit `vendor/`, `generated/`, `pub/static/`. Never run Magento CLI against production.

## Must not

- Reformat unrelated code.
- Apply rules for areas the change does not touch.
- Introduce preferences where a plugin suffices without justification.
- Use ObjectManager directly except in factories/tests where the project already does.
- Scaffold brand-new modules or extension points (use module-scaffold).

## Output format

Brief: exemplar used, standards applied, lint commands/results, any convention conflict surfaced.
