# Adoption guide

**Current pack:** `adobe-commerce-ai-skills` **0.5.2** (18 skills). Live eval sheet: `evals/results-0.5.2.md`.

## Distribution (Teams plan)

- One team marketplace per Teams plan.
- Preferred: **Dashboard → Plugins & MCPs → Team Marketplaces → Default** → set **Plugin Repository** to the GitHub URL of this pack → **Refresh** → **Add to Marketplace**.
- Install mode per plugin:
  - **Default Off** — good for pilot
  - **Default On** — after pilot confidence
  - **Required** — only when CoE mandates the pack
- Do **not** register this pack as a Team MCP / `npx` server — it is a Cursor plugin (skills + rules + hooks), not an MCP.

### Marketplace error: `could not read Username for 'https://github.com'`

Cursor installs marketplace plugins by **git fetch** from the Plugin Repository. If the repo is **private** and GitHub is not connected to Cursor, install fails with:

`fatal: could not read Username for 'https://github.com': terminal prompts disabled`

**Fix (pick one):**

1. **Connect GitHub to Cursor (recommended for private repos)**  
   Team admin: Cursor Dashboard → connect the GitHub org that owns `adobe-comm/adobe-commerce-ai-skills` (Cursor GitHub App / marketplace repo access). Every teammate who installs may also need access to that private repo.
2. **Make the marketplace repo readable by Cursor**  
   Either grant the Cursor GitHub App access to the repo, or use a **public** repo / internal mirror if company policy allows.
3. **Skip marketplace for now (works today)**  
   Engineers use Option B/C in `TEAM_USER_GUIDE.md`: `./install.sh` or `~/.cursor/plugins/local/adobe-commerce-ai-skills`.

This is **not** a bug in the skills pack — the commit Cursor tries to fetch (e.g. `a367a35…`) is valid; GitHub auth is missing.

## What’s in 0.5.1 (for rollout talk-tracks)

| Area | Skills / notes |
|------|----------------|
| Daily PHP | scaffold, coding-standards, debugging, testing, reviews, frontend, integration |
| Domain (thin) | `adobe-commerce-b2b`, `adobe-commerce-inventory-msi`, `adobe-commerce-checkout` |
| Slash-only (high risk) | `/adobe-commerce-project-documentation`, `/adobe-commerce-upgrade-and-patching`, `/adobe-commerce-deploy-and-environments` |
| Always-on | `rules/adobe-commerce-core.mdc` |
| Guardrails | hooks block writes to `vendor/`, `generated/`, `pub/static/`, `env.php`, `auth.json` |
| Defer to Adobe | App Builder implementation, EDS drop-ins, Wayfinder (use team Adobe MCPs/skills) |

## Pilot (recommended)

1. Pick two real Adobe Commerce projects (ideally one Cloud/PaaS, one varied frontend).
2. Install pack (marketplace or `install.sh`).
3. Fill `AGENTS.md` and keep `docs/ai/project-facts.md` human-reviewed.
4. Give engineers `docs/TEAM_USER_GUIDE.md` as the primary instruction doc.
5. Run manual evals from `evals/run.md` on fixtures (`evals/results-0.5.2.md` for live scores), then smoke real tickets for 2–4 weeks.
6. File wrong outputs as new `evals/*/cases.yaml` entries.

## One-command project entry points

In each Commerce repo, expose stable Composer or Make targets so skills call the same commands:

- `composer lint` / `make lint`
- `composer test:unit`
- `composer test:integration` (when DB available)
- `composer test:static`

Document the exact commands in that project's `AGENTS.md`. Use `ddev exec` as the command prefix when the project uses DDEV.

## Ignore / vendor readability

Copy `templates/project/.cursorignore` so `composer.lock` and `vendor/magento/**` can be re-included. `.cursorignore` is best-effort and does **not** block terminal or MCP.

**Prefer the terminal** (`cat`, `rg`, `detect-stack.sh`) for `vendor/` and `composer.lock` — file `@`-mentions often fail. Probe fixture: `evals/fixtures/vendor-probe-git/`.

## Azure DevOps

Tickets live in Azure DevOps. If an Azure DevOps MCP is installed, skills may read work items through it. Otherwise paste the work item text. Do not require Jira.

## Hooks

Guardrail hooks ship with the pack (v0.4.0+; still current in 0.5.1):

- Block writes to `vendor/`, `generated/`, `pub/static/`, `app/etc/env.php`, `auth.json`
- Ask before force-push or DROP/TRUNCATE-style shell commands
- Reading `vendor/` remains allowed (needed for version/API verification)
- Shell regex detection is a safety net, not CI — see `TEAM_USER_GUIDE.md` / `hooks/README.md`

Installed via team marketplace plugin hooks, or via `./install.sh` into `.cursor/hooks.json`.

## Slash-only / high-risk skills

Upgrade, deploy, and project-documentation are **slash-only** (`disable-model-invocation: true`) since 0.5.0. Domain B2B/MSI/checkout skills auto-trigger on matching prompts (0.5.1).

## Owners

- Pack owner: Brainvire Adobe Commerce CoE
- Versioning: pack + every skill `metadata.version` track together (semver in `.cursor-plugin/plugin.json` + `CHANGELOG.md`)
