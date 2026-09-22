# Adoption guide

## Distribution (Teams plan)

- One team marketplace per Teams plan.
- Import this repository; add the plugin; choose installation mode:
  - **Default Off** — good for pilot
  - **Default On** — after pilot confidence
  - **Required** — only when CoE mandates the pack
- Fallback for repos not using the marketplace: `./install.sh /path/to/project`

## Pilot (recommended)

1. Pick two real Adobe Commerce projects (ideally one Cloud/PaaS, one varied frontend).
2. Install pack (marketplace or `install.sh`).
3. Fill `AGENTS.md` and keep `docs/ai/project-facts.md` human-reviewed.
4. Give engineers `docs/TEAM_USER_GUIDE.md` as the primary instruction doc.
5. Run manual evals from `evals/run.md` on fixtures, then smoke real tickets for 2–4 weeks.
6. File wrong outputs as new `evals/*/cases.yaml` entries.

## One-command project entry points

In each Commerce repo, expose stable Composer or Make targets so skills call the same commands:

- `composer lint` / `make lint`
- `composer test:unit`
- `composer test:integration` (when DB available)
- `composer test:static`

Document the exact commands in that project's `AGENTS.md`.

## Ignore / vendor readability

Copy `templates/project/.cursorignore` so `composer.lock` and `vendor/magento/**` can be re-included. `.cursorignore` is best-effort and does **not** block terminal or MCP.

Run the vendor probe in `evals/run.md` once per Cursor version upgrade; record results in `docs/VERIFICATION.md`.

## Azure DevOps

Tickets live in Azure DevOps. If an Azure DevOps MCP is installed, skills may read work items through it. Otherwise paste the work item text. Do not require Jira.

## Hooks

Guardrail hooks ship with the pack (v0.4.0+):

- Block writes to `vendor/`, `generated/`, `pub/static/`, `app/etc/env.php`, `auth.json`
- Ask before force-push or DROP/TRUNCATE-style shell commands
- Reading `vendor/` remains allowed (needed for version/API verification)

Installed via team marketplace plugin hooks, or via `./install.sh` into `.cursor/hooks.json`. Details: `hooks/README.md`.

Upgrade and deploy skills ship in 0.5.0 as slash-only (`disable-model-invocation: true`).

## Owners

- Pack owner: Brainvire Adobe Commerce CoE
- Versioning: semver in `.cursor-plugin/plugin.json` + `CHANGELOG.md`
