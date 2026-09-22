---
name: adobe-commerce-coding-standards
description: >
  Applies Adobe Commerce, Magento, PHP, and project conventions when writing or
  changing backend code, XML configuration, templates, GraphQL schema, or
  CLI/cron/queue code in a Commerce module or theme. Use for any code change in
  a module or theme. Do not use to scaffold a brand-new module or extension point
  from scratch (use module-scaffold), for documentation-only or copy changes, or
  for App Builder JavaScript actions owned by Adobe official skills.
paths:
  - "**/*.php"
  - "**/*.phtml"
  - "**/*.graphqls"
  - "**/etc/**/*.xml"
  - "**/view/**/*.xml"
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.0"
  verified-against: "magento/magento-coding-standard; Adobe PHP developer practices 2026-09-22"
---

# Coding standards

## When to use / skip

Use: changing PHP, module XML, phtml, GraphQL schema, CLI/cron/queue code.
Skip: docs-only, marketing copy, pure EDS/App Builder JS under Adobe skills.

## Procedure

1. Precedence: (1) existing valid project convention, (2) Adobe/Magento standards, (3) PHP/PSR. On conflict, surface it in one line — do not silently choose.
2. Find the nearest exemplar (similar class or module in this repo) and mirror structure, naming, and DI style.
3. Load only the reference you need:
   - [references/backend.md](references/backend.md)
   - [references/xml-config.md](references/xml-config.md)
   - [references/graphql.md](references/graphql.md)
   - [references/cli-cron-queue.md](references/cli-cron-queue.md)
4. Tooling: detect the project's phpcs ruleset, static analysis, and lint config. Official Magento standard package is `magento/magento-coding-standard` (needs phpcs `installed_paths`). Source: https://github.com/magento/magento-coding-standard
   Many of our projects have no linter installed yet. When none exists, say so plainly, apply conventions by hand from the nearest exemplar, and offer the one-time setup as its own task — do not claim a standard was enforced.
5. Run linters on **changed files only**, prefixed with `ddev exec` when `.ddev/` exists. Report: `Ran: <cmd> -> <result>. Not run: <what> because <reason>.`
6. Never edit `vendor/`, `generated/`, `pub/static/`.

## Must not

- Reformat unrelated code.
- Apply rules for areas the change does not touch.
- Introduce preferences where a plugin suffices without justification.
- Use ObjectManager directly except in factories/tests where the project already does.

## Output format

Brief: exemplar used, standards applied, lint commands/results, any convention conflict surfaced.
