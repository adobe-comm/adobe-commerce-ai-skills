---
name: adobe-commerce-project-understanding
description: >
  Builds a working understanding of an unfamiliar Adobe Commerce repository:
  detects the platform model (Cloud/PaaS, on-premises, ACCS, ACO, App Builder,
  Edge Delivery storefront), installed version signals, custom versus third-party
  modules, and integrations actually present. Use when starting in a repo, when
  asked how the project is structured, or before a task whose affected area is
  unknown. Do not use to trace one feature's code path end-to-end (use
  feature-analysis), when the affected files are already known, for CSS/copy-only
  edits, or when an Adobe App Builder/drop-in skill already owns the task.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.1"
  verified-against: "Cursor skills/plugins 2026-09-22; Adobe extensibility docs 2026-09-22"
---

# Project understanding

## When to use / skip

Use: new repo, "how is this structured", task area unknown.
Skip: L0/L1 with known files; App Builder/EDS work covered by Adobe official skills.

## Procedure

1. Run `scripts/detect-platform.sh <repo-root>` for platform signals and `scripts/detect-stack.sh <repo-root>` for versions and tooling. Both are read-only and exit 0 when nothing is found — report UNKNOWN rather than inventing. Run them through the terminal: the shell is not blocked by `.cursorignore`, so this works even when `composer.lock` and `vendor/` are gitignored.
2. Discovery order (stop when enough for the task):
   a. Platform model + version signals (composer metadata via the stack script, Cloud YAML, ACCS/App Builder markers, EDS markers).
   b. Major application areas present (custom `app/code`, themes, headless).
   c. Integrations present from evidence only (config, HTTP clients, queues, cron, webhooks, App Builder actions).
   d. Relevant business flow for the current task only.
   e. Deep dive only where the task needs it (hand off to `adobe-commerce-feature-analysis`).
3. Output levels — pick one:
   - Initial map: <=15 lines.
   - Feature-scoped notes: only the area touched.
   - Deep trace: defer to feature-analysis.
4. Evidence tags on non-trivial claims: CONFIRMED (path), INFERRED, UNKNOWN.
5. Project facts protocol: propose additions to `docs/ai/project-facts.md` only for facts code cannot show (authoritative-system decisions, duplicate/dead implementations, environment quirks, do-not-touch). Each fact needs evidence path + verified date/commit. Max 100 lines. Deliver as a diff for human review. Never store secrets.

## Platform signals (check; drop unsupported)

| Signal | Suggests | Source |
|--------|----------|--------|
| `magento/magento2-base` or `magento/product-enterprise-edition` in composer | Commerce/Magento PHP app | composer files |
| `.magento.app.yaml`, `.magento/` | Adobe Commerce on Cloud (PaaS) | Cloud project layout |
| `app/etc/config.php` | Classic Magento bootstrap | Magento root |
| `app.config.yaml` + `actions/` | App Builder extension | Adobe App Builder apps |
| `blocks/` + `scripts/initializers/` | Edge Delivery storefront | Adobe storefront boilerplate |
| `hyva-themes/` packages or Hyva theme path | Hyva frontend | composer / app/design |
| `.ddev/`, `docker-compose.yml`, `.warden/` | Local stack | common local tooling |

ACCS/ACO: if SaaS/ACCS markers or docs say ACCS, do not propose in-process PHP plugins; customization is out-of-process (App Builder, events, webhooks, APIs). Source: Adobe extension compatibility docs.

## Must not

- Scan the whole repo without a task reason.
- Restate the README as an architecture report.
- List integrations or modules that are not present.
- Edit vendor/generated/pub/static.

## Output format

```
Platform: <model> — CONFIRMED/INFERRED/UNKNOWN (<paths>)
Version signals: <package@version or UNKNOWN>
Custom modules (sample): <paths>
Integrations found: <only those with evidence>
Map (<=15 lines) or feature notes
Facts to propose: <diff or none>
```
