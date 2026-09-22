# adobe-commerce-ai-skills

Cursor plugin for the Brainvire Adobe Commerce CoE. It makes Cursor behave like a senior engineer on an unfamiliar Commerce project: it copies the patterns already in the repo, sizes effort to risk, verifies claims against installed code, and reports honestly what it ran.

**Version 0.5.1** — 18 skills, always-on core rule, guardrail hooks, project templates, eval fixtures and cases.

## What a developer gets

| Daily task | Skill that handles it |
|------------|----------------------|
| "Add a plugin / observer / patch / CLI command / resolver" | `adobe-commerce-module-scaffold` |
| "This is broken, find out why" | `adobe-commerce-debugging` |
| "What tests do I need, and run them" | `adobe-commerce-testing` |
| "Review this PR" | `adobe-commerce-code-review` |
| "Is this change safe?" | `adobe-commerce-security-review` |
| "Will this hurt checkout/catalog performance?" | `adobe-commerce-performance-review` |
| Templates, styles, storefront JS, GA4/GTM | `adobe-commerce-frontend-and-tracking` |
| ERP/CRM/OMS sync work and failures | `adobe-commerce-integration-work` |
| B2B company / shared catalog / quotes | `adobe-commerce-b2b` |
| MSI / reservations / salable qty | `adobe-commerce-inventory-msi` |
| Checkout steps / quote totals / place-order | `adobe-commerce-checkout` |
| "How does this feature actually work?" | `adobe-commerce-feature-analysis` |
| "Turn this work item into a plan" | `adobe-commerce-requirement-planning` |
| Conventions while editing existing module code | `adobe-commerce-coding-standards` |
| "What is this project?" | `adobe-commerce-project-understanding` |
| Record an ADR / project fact (explicit only) | `/adobe-commerce-project-documentation` |
| Version upgrade / UCT / patches (explicit only) | `/adobe-commerce-upgrade-and-patching` |
| Cloud / App Builder deploy (explicit only) | `/adobe-commerce-deploy-and-environments` |

Always-on: `rules/adobe-commerce-core.mdc`.

**Guardrails (hooks):** block writes to `vendor/`, `generated/`, `pub/static/`, `app/etc/env.php`, `auth.json`. Reading `vendor/` via the **terminal** is allowed (prefer that over `@vendor/...`). See `hooks/README.md` and below.

## Scope

Covers in-process PHP work on Adobe Commerce **PaaS and on-premises**, plus team process and cross-cutting review.

Defers to Adobe's official tooling (`aio commerce extensibility tools-setup`) for App Builder, starter kits, Edge Delivery drop-ins, and Wayfinder when those skills own the task. Our slash-only deploy skill covers App Builder *deploy* hygiene; implementation still follows Adobe's skills.

## Install

### Team marketplace (Teams plan, preferred)

1. Push this pack to a private Git repo (**GitHub** works for Default marketplace Plugin Repository; Azure DevOps only if Cursor accepts the URL after auth).
2. Dashboard → **Plugins & MCPs** → Team Marketplaces → **Default** → paste repo URL → **Refresh** → add **adobe-commerce-ai-skills**.
3. Set install mode: **Default Off** (pilot) → **Default On** / **Required** later.
4. Not an MCP: do not add this pack under Team MCP Servers / `npx`.

### Per project

```bash
./install.sh /path/to/commerce-project
```

### Local plugin test

Copy under `~/.cursor/plugins/local/adobe-commerce-ai-skills` and reload Cursor.

## Required per-project step

Fill in `AGENTS.md` (commands, DDEV prefix, exemplars). Skills use those lines verbatim.

### Reading `vendor/` and `composer.lock`

Typical Magento repos **gitignore `vendor/`**. Cursor also **default-ignores `composer.lock`**. File tools and `@` mentions of those paths often fail when the Magento project is the workspace root.

**Do this instead:** use the terminal (`cat`, `rg`, or the pack’s `detect-stack.sh`). Ignore files do not block the shell. Do not burn turns retrying `@vendor/magento/...` before using the terminal.

Authoritative probe fixture: open `evals/fixtures/vendor-probe-git/` alone as the workspace and follow its README.

## Clarifying questions

Prefer the host clarifying-question tool (Cursor docs: “Ask questions”; often exposed as `AskQuestion` when attached). If missing or renamed, ask one focused numbered question in chat. Never hard-fail on the tool name. Never guess when the answer changes the implementation.

## Versioning

Pack version and every skill’s `metadata.version` track together (**0.5.1**). Each pack release bumps all skill metadata versions to match.

## Evals

See `evals/run.md` and `evals/results-0.5.1.md` (inventory). Historical blocked live-run sheet: `evals/results-0.5.0.md`.

## Docs

- `docs/TEAM_USER_GUIDE.md` — **start here for engineers**
- `docs/ADOPTION.md` — CoE rollout / Teams marketplace
- `docs/VERIFICATION.md` — claims and decisions
- `docs/SOURCES.md` — source index
- `hooks/README.md` — guardrail behaviour
