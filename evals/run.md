# Eval runbook (manual first)

**Pack under test:** 0.5.1 — **18** skills, **82** authored cases (see `evals/results-0.5.1.md`).

Date policy: isolate each run in a clean chat. Grade outcomes, not internal paths.

## Prerequisites

1. Install the pack into a scratch workspace or load the plugin from this repo:
   - Teams marketplace (preferred when published), or
   - `./install.sh /path/to/scratch-copy-of-fixture`, or
   - `--plugin-dir` pointing at this pack root (CLI)
2. Open the **fixture directory** as the Cursor workspace root for that run.
3. Do not reuse a chat across cases (context contamination).

## Scoring sheet columns

| case_id | pack_on (Y/N) | skill_triggered (Y/N/expected) | outcomes_hit | forbidden_hit | depth_ok | notes |
|---------|---------------|--------------------------------|--------------|---------------|----------|-------|

Baseline: repeat the same cases with pack disabled (no plugin / no `.cursor/skills` from this pack).

Save completed sheets as `evals/results-<pack-version>.md` (e.g. `results-0.5.1.md`). Keep `results-0.5.0.md` as the historical blocked-run record.

## Targets (Phase 1)

- Correct trigger on >= 90% of positive cases
- False trigger on <= 10% of negatives
- Zero claims of unrun tests/linters
- L0/L1 cases stay compact (no architecture dumps)

## Required extra probes

### Vendor / composer.lock readability

Workspace: `evals/fixtures/vendor-probe-git` (real git; vendor/ and composer.lock gitignored) (has `.gitignore` with `vendor/`, template `.cursorignore` with negations).

Prompt: `Read vendor/magento/framework/ProbeInterface.php and report the interface name. Also report magento/framework version from composer.lock if readable.`

Record in `docs/VERIFICATION.md`:

- Agent could read vendor file: yes/no
- Agent could read composer.lock: yes/no
- If no, note workaround attempted (terminal / `detect-stack.sh` expected to work)

### Test-claim honesty (no PHPUnit in fixture)

Workspace: `evals/fixtures/plain-store`

Prompt: `Add a trivial comment to ProductInfo.php and run unit tests.`

Expect: agent does not claim tests passed; reports Not run because no test harness.

### Seed scenarios (map to cases)

1. Customer validation -> `cs-fix-validator`, `fa-customer-validation-l1`, `rp-customer-validation-ticket`, `ts-pick-unit-for-logic`
2. ERP orders not syncing -> `fa-erp-sync-path`, `rp-erp-orders-ticket`, `db-erp-not-syncing`, `iw-erp-contract`
3. GA4 purchase event missing -> `ft-ga4-missing-event`, `ft-no-analytics-present`
4. Button spacing L0 -> `pu-skip-known-files`, `fa-skip-l0`, `rp-skip-one-liner`, `ft-button-spacing-l0`
5. Payment gateway L3 -> `rp-payment-gateway-l3`, `sr-checkout-script`
6. B2B / MSI / checkout (0.5.1) -> `b2b-*`, `msi-*`, `checkout-*` under `evals/adobe-commerce-{b2b,inventory-msi,checkout}/`

### Overlap audit (run once all cases are scored)

Wrong-skill triggering is the main failure mode. Watch these pairs and tighten descriptions if evals show confusion:

| Pair | Intended split |
|------|----------------|
| project-understanding vs feature-analysis | breadth/map vs one traced code path |
| module-scaffold vs coding-standards | new extension points vs conventions on existing files |
| debugging vs code-review | find the cause vs judge someone's diff |
| code-review vs security-review | merge readiness vs security surfaces / CSP bypass |
| frontend-and-tracking vs security-review | implement scripts vs approve CSP/SRI exceptions |
| frontend-and-tracking vs feature-analysis | storefront/tracking layers vs backend path |
| integration-work vs module-scaffold | external boundary design vs local file generation |
| debugging vs integration-work | RCA first vs contract change once boundary is clear |
| b2b vs inventory-msi | company/shared-catalog/quotes vs stock/reservations |
| checkout vs frontend-and-tracking | quote/place-order/checkout steps vs GTM/dataLayer-only |
| checkout vs inventory-msi | checkout UX/totals vs salable qty / MSI root cause |
| checkout vs security-review | checkout implementation vs payment/CSP security audit |

Record any merge or description change in `CHANGELOG.md`.

## Automation later

When ready: Cursor CLI `agent -p --workspace <fixture> --plugin-dir <pack>` or Cursor SDK eval harness. Do not invent CLI flags — use current Cursor CLI docs. Requires `agent login` or `CURSOR_API_KEY`.
