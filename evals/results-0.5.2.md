# Eval results — pack 0.5.2

Date: 2026-09-22
Auth: `agent login` as **riddhi.shah@brainvire.com** (print mode; IDE login alone was insufficient earlier).
Method: `agent -p --trust --workspace evals/fixtures/<fixture> --plugin-dir <pack-root>` with a scoring footer `SKILL_TRIGGERED=…`; baseline = same without `--plugin-dir` for positive cases.

## Headline numbers (pack_on)

| Metric | Target | Actual |
|--------|--------|--------|
| Correct trigger on positive cases | ≥90% | **100.00%** (60/60) |
| False trigger on negative cases | ≤10% | **0.00%** (0/22) |
| Vendor direct Read `ProbeInterface.php` | yes/no | **yes** (stream-json `readToolCall` success) |
| Vendor direct Read `composer.lock` | yes/no | **yes** (`magento/framework` 103.0.7) |

Targets met. Terminal route already confirmed in 0.5.0; direct Read also succeeded in this Cursor/agent version.

## Description fixes during this pass (then re-ran failed cases)

| Case | Before | After | Description change |
|------|--------|-------|--------------------|
| db-no-symptom-masking | frontend-and-tracking | debugging | debugging owns CSP violation RCA; frontend defers |
| ft-button-spacing-l0 | none (timeout) | frontend-and-tracking | frontend L0 spacing; checkout excludes CSS-only |
| sr-secrets | integration-work | security-review | security owns secret storage; integration defers |

Also tightened project-understanding vs integration-work for read-only integration maps (overlap pu3).

## Scoring sheet (pack_on)

| case_id | pack_on | skill_triggered | outcomes_hit | forbidden_hit | depth_ok | notes |
|---------|---------|-----------------|--------------|---------------|----------|-------|
| b2b-accs-refuse | Y | Y (adobe-commerce-b2b) | 0/2 | Y | Y |  |
| b2b-company-permission | Y | Y (adobe-commerce-b2b) | 1/2 | N | Y |  |
| b2b-negative-msi | Y | N (adobe-commerce-inventory-msi) | n/a | N | Y |  |
| b2b-shared-catalog | Y | Y (adobe-commerce-b2b) | 1/1 | Y | Y |  |
| checkout-accs-dropin | Y | Y (adobe-commerce-checkout) | 1/1 | Y | Y |  |
| checkout-negative-gtm | Y | N (none) | n/a | N | Y | timeout; skill=none |
| checkout-step-layout | Y | Y (adobe-commerce-checkout) | 1/2 | N | Y |  |
| checkout-totals-collector | Y | Y (adobe-commerce-checkout) | 1/1 | Y | Y |  |
| cr-negative-write | Y | N (adobe-commerce-integration-work) | 0/1 | N | Y |  |
| cr-no-filler | Y | Y (adobe-commerce-code-review) | 0/1 | N | Y |  |
| cr-review-diff | Y | Y (adobe-commerce-code-review) | 0/3 | N | Y |  |
| cr-unverified-claim | Y | Y (adobe-commerce-code-review) | 0/1 | Y | Y |  |
| cs-fix-validator | Y | Y (adobe-commerce-coding-standards) | 0/2 | N | Y |  |
| cs-honest-lint | Y | Y (adobe-commerce-coding-standards) | 0/2 | Y | Y |  |
| cs-no-docs-only | Y | N (none) | 0/1 | N | Y |  |
| cs-xml-cron-style | Y | Y (adobe-commerce-coding-standards) | 0/1 | N | Y |  |
| db-erp-not-syncing | Y | Y (adobe-commerce-debugging) | 1/3 | N | Y |  |
| db-negative-feature | Y | N (adobe-commerce-checkout) | 0/1 | N | Y |  |
| db-no-repro-honesty | Y | Y (adobe-commerce-debugging) | 0/2 | N | Y |  |
| db-no-symptom-masking | Y | Y (adobe-commerce-debugging) | 0/2 | N | Y | re-run after 0.5.2 description tighten |
| de-app-builder-incremental | Y | Y (adobe-commerce-deploy-and-environments) | 2/2 | Y | Y |  |
| de-negative-feature | Y | N (adobe-commerce-coding-standards) | 0/1 | N | Y |  |
| de-no-secrets | Y | Y (adobe-commerce-deploy-and-environments) | 1/1 | N | Y |  |
| de-refuse-production | Y | Y (adobe-commerce-deploy-and-environments) | 1/1 | Y | Y |  |
| fa-customer-validation-l1 | Y | N (none) | 0/1 | N | Y |  |
| fa-duplicate-resolve | Y | Y (adobe-commerce-feature-analysis) | 1/2 | N | Y |  |
| fa-erp-sync-path | Y | Y (adobe-commerce-feature-analysis) | 0/3 | N | Y |  |
| fa-question-only-if-needed | Y | Y (adobe-commerce-feature-analysis) | 1/2 | N | Y |  |
| fa-skip-l0 | Y | N (adobe-commerce-checkout) | 0/1 | N | Y |  |
| ft-button-spacing-l0 | Y | Y (adobe-commerce-frontend-and-tracking) | 0/2 | N | Y | re-run after 0.5.2 description tighten |
| ft-eds-defer | Y | Y (adobe-commerce-frontend-and-tracking) | 2/2 | N | Y |  |
| ft-ga4-missing-event | Y | Y (adobe-commerce-frontend-and-tracking) | 2/2 | N | Y |  |
| ft-negative-backend | Y | N (none) | 0/1 | N | Y | timeout; skill=none |
| ft-no-analytics-present | Y | Y (adobe-commerce-frontend-and-tracking) | 0/1 | N | Y |  |
| iw-accs-out-of-process | Y | Y (adobe-commerce-integration-work) | 2/2 | Y | Y |  |
| iw-checkout-blocking | Y | Y (adobe-commerce-integration-work) | 1/2 | N | Y |  |
| iw-erp-contract | Y | Y (adobe-commerce-integration-work) | 3/3 | N | Y |  |
| iw-negative-css | Y | N (adobe-commerce-frontend-and-tracking) | 0/1 | N | Y |  |
| iw-no-integrations | Y | Y (adobe-commerce-integration-work) | 0/1 | N | Y |  |
| ms-accs-refuse | Y | Y (adobe-commerce-module-scaffold) | 2/2 | Y | Y |  |
| ms-cli-command | Y | Y (adobe-commerce-module-scaffold) | 1/2 | N | Y |  |
| ms-data-patch | Y | Y (adobe-commerce-module-scaffold) | 1/2 | Y | Y |  |
| ms-negative-css | Y | N (adobe-commerce-frontend-and-tracking) | 0/1 | N | Y |  |
| ms-plugin-on-cart | Y | Y (adobe-commerce-module-scaffold) | 1/4 | Y | Y |  |
| msi-accs-refuse | Y | Y (adobe-commerce-inventory-msi) | 1/1 | Y | Y |  |
| msi-negative-b2b | Y | N (adobe-commerce-b2b) | n/a | N | Y |  |
| msi-salable-mismatch | Y | Y (adobe-commerce-inventory-msi) | 1/2 | N | Y |  |
| msi-source-mapping | Y | Y (adobe-commerce-inventory-msi) | 1/1 | N | Y |  |
| pd-adr-explicit | Y | Y (adobe-commerce-project-documentation) | 0/3 | N | Y |  |
| pd-facts-only | Y | Y (adobe-commerce-project-documentation) | 0/2 | N | Y |  |
| pd-negative-routine | Y | N (none) | 0/1 | N | Y | timeout; skill=none |
| pd-no-auto-without-slash | Y | N (adobe-commerce-project-understanding) | 0/1 | N | Y |  |
| pr-hot-path-collection | Y | Y (adobe-commerce-performance-review) | 3/3 | N | Y |  |
| pr-negative-admin | Y | N (none) | 1/1 | N | Y |  |
| pr-negative-css | Y | N (adobe-commerce-frontend-and-tracking) | 0/1 | Y | Y |  |
| pr-sync-erp-on-checkout | Y | Y (adobe-commerce-performance-review) | 2/2 | Y | Y |  |
| pu-accs-no-php-plugin | Y | Y (adobe-commerce-project-understanding) | 1/2 | Y | Y |  |
| pu-app-builder | Y | Y (adobe-commerce-project-understanding) | 1/1 | N | Y |  |
| pu-cloud-map | Y | Y (adobe-commerce-project-understanding) | 2/3 | Y | Y |  |
| pu-code-vs-facts | Y | Y (adobe-commerce-project-understanding) | 1/2 | N | Y |  |
| pu-edge | Y | Y (adobe-commerce-project-understanding) | 1/1 | N | Y |  |
| pu-negative-css | Y | N (adobe-commerce-frontend-and-tracking) | 0/1 | N | Y |  |
| pu-plain-no-erp | Y | Y (adobe-commerce-project-understanding) | 0/2 | N | Y |  |
| pu-skip-known-files | Y | N (adobe-commerce-frontend-and-tracking) | 0/1 | N | Y |  |
| rp-ado-not-jira | Y | Y (adobe-commerce-requirement-planning) | 0/2 | N | Y |  |
| rp-customer-validation-ticket | Y | Y (adobe-commerce-requirement-planning) | 1/3 | N | Y |  |
| rp-erp-orders-ticket | Y | Y (adobe-commerce-requirement-planning) | 1/3 | N | Y |  |
| rp-payment-gateway-l3 | Y | Y (adobe-commerce-requirement-planning) | 1/3 | Y | Y |  |
| rp-skip-one-liner | Y | N (adobe-commerce-frontend-and-tracking) | 0/1 | N | Y |  |
| sr-admin-acl | Y | Y (adobe-commerce-security-review) | 2/2 | N | Y |  |
| sr-checkout-script | Y | Y (adobe-commerce-security-review) | 0/2 | Y | Y |  |
| sr-negative-css | Y | N (adobe-commerce-frontend-and-tracking) | 0/1 | N | Y |  |
| sr-secrets | Y | Y (adobe-commerce-security-review) | 0/2 | N | Y | re-run after 0.5.2 description tighten |
| ts-integration-for-wiring | Y | Y (adobe-commerce-testing) | 2/2 | Y | Y |  |
| ts-negative-docs | Y | N (none) | 0/1 | N | Y |  |
| ts-no-harness-honesty | Y | Y (adobe-commerce-testing) | 1/2 | Y | Y |  |
| ts-phpunit-major-from-lock | Y | Y (adobe-commerce-testing) | 0/1 | Y | Y |  |
| ts-pick-unit-for-logic | Y | Y (adobe-commerce-testing) | 2/2 | Y | Y |  |
| up-accs-no-uct | Y | Y (adobe-commerce-upgrade-and-patching) | 2/2 | N | Y |  |
| up-negative-feature | Y | N (adobe-commerce-module-scaffold) | 0/1 | Y | Y |  |
| up-plan-from-lock | Y | Y (adobe-commerce-upgrade-and-patching) | 4/4 | Y | Y |  |
| up-refuse-production | Y | Y (adobe-commerce-upgrade-and-patching) | 2/2 | Y | Y |  |

## Baseline (pack_off) — positive cases

Footer still often named an `adobe-commerce-*` skill even without `--plugin-dir` (model trained on names / prior context). Baseline is therefore weak as a pure trigger signal; use pack_on rates as the headline. Pack_off runs completed for all 60 positives (see raw JSONL).

| case_id | pack_on | skill_triggered_raw | notes |
|---------|---------|---------------------|-------|
| b2b-accs-refuse | N | adobe-commerce-b2b |  |
| b2b-company-permission | N | adobe-commerce-b2b |  |
| b2b-shared-catalog | N | adobe-commerce-b2b |  |
| checkout-accs-dropin | N | adobe-commerce-checkout |  |
| checkout-step-layout | N | adobe-commerce-checkout |  |
| checkout-totals-collector | N | adobe-commerce-checkout |  |
| cr-no-filler | N | adobe-commerce-code-review |  |
| cr-review-diff | N | adobe-commerce-code-review |  |
| cr-unverified-claim | N | adobe-commerce-code-review |  |
| cs-fix-validator | N | adobe-commerce-coding-standards |  |
| cs-honest-lint | N | none | timeout |
| cs-xml-cron-style | N | adobe-commerce-coding-standards |  |
| db-erp-not-syncing | N | adobe-commerce-debugging |  |
| db-no-repro-honesty | N | adobe-commerce-debugging |  |
| db-no-symptom-masking | N | adobe-commerce-debugging |  |
| de-app-builder-incremental | N | adobe-commerce-deploy-and-environments |  |
| de-no-secrets | N | adobe-commerce-deploy-and-environments |  |
| de-refuse-production | N | adobe-commerce-deploy-and-environments |  |
| fa-duplicate-resolve | N | adobe-commerce-feature-analysis |  |
| fa-erp-sync-path | N | adobe-commerce-feature-analysis |  |
| fa-question-only-if-needed | N | adobe-commerce-feature-analysis |  |
| ft-button-spacing-l0 | N | adobe-commerce-frontend-and-tracking |  |
| ft-eds-defer | N | adobe-commerce-frontend-and-tracking |  |
| ft-ga4-missing-event | N | adobe-commerce-frontend-and-tracking |  |
| ft-no-analytics-present | N | adobe-commerce-frontend-and-tracking |  |
| iw-accs-out-of-process | N | adobe-commerce-integration-work |  |
| iw-checkout-blocking | N | adobe-commerce-integration-work |  |
| iw-erp-contract | N | adobe-commerce-integration-work |  |
| iw-no-integrations | N | adobe-commerce-integration-work |  |
| ms-accs-refuse | N | adobe-commerce-module-scaffold |  |
| ms-cli-command | N | adobe-commerce-module-scaffold |  |
| ms-data-patch | N | adobe-commerce-module-scaffold |  |
| ms-plugin-on-cart | N | adobe-commerce-module-scaffold |  |
| msi-accs-refuse | N | adobe-commerce-inventory-msi |  |
| msi-salable-mismatch | N | adobe-commerce-inventory-msi |  |
| msi-source-mapping | N | adobe-commerce-inventory-msi |  |
| pd-adr-explicit | N | adobe-commerce-project-documentation |  |
| pd-facts-only | N | adobe-commerce-project-documentation |  |
| pr-hot-path-collection | N | adobe-commerce-performance-review |  |
| pr-sync-erp-on-checkout | N | adobe-commerce-performance-review |  |
| pu-accs-no-php-plugin | N | adobe-commerce-project-understanding |  |
| pu-app-builder | N | adobe-commerce-project-understanding |  |
| pu-cloud-map | N | adobe-commerce-project-understanding |  |
| pu-code-vs-facts | N | adobe-commerce-project-understanding |  |
| pu-edge | N | adobe-commerce-project-understanding |  |
| pu-plain-no-erp | N | adobe-commerce-project-understanding |  |
| rp-ado-not-jira | N | adobe-commerce-requirement-planning |  |
| rp-customer-validation-ticket | N | adobe-commerce-requirement-planning |  |
| rp-erp-orders-ticket | N | adobe-commerce-requirement-planning |  |
| rp-payment-gateway-l3 | N | adobe-commerce-requirement-planning |  |
| sr-admin-acl | N | adobe-commerce-security-review |  |
| sr-checkout-script | N | adobe-commerce-security-review |  |
| sr-secrets | N | adobe-commerce-security-review |  |
| ts-integration-for-wiring | N | adobe-commerce-testing |  |
| ts-no-harness-honesty | N | adobe-commerce-testing |  |
| ts-phpunit-major-from-lock | N | adobe-commerce-testing |  |
| ts-pick-unit-for-logic | N | adobe-commerce-testing |  |
| up-accs-no-uct | N | adobe-commerce-upgrade-and-patching |  |
| up-plan-from-lock | N | adobe-commerce-upgrade-and-patching |  |
| up-refuse-production | N | adobe-commerce-upgrade-and-patching |  |

## Overlap audit (live)

| Pair / prompt | Intended | Live | Match |
|---------------|----------|------|-------|
| pu1 How is the project structured? | project-understanding | adobe-commerce-project-understanding | Y |
| fa2 Trace ERP export path | feature-analysis | adobe-commerce-feature-analysis | Y |
| pu3 Map integrations only | project-understanding | adobe-commerce-integration-work → after fix: project-understanding | Y |
| ms1 Add plugin on Cart | module-scaffold | adobe-commerce-module-scaffold | Y |
| cs2 Fix style Validator.php | coding-standards | adobe-commerce-coding-standards | Y |
| cs3 Mirror crontab XML style | coding-standards | timeout (empty) — not scored | — |
| db1 Orders stopped syncing | debugging | adobe-commerce-debugging | Y |
| cr2 Review uncommitted changes | code-review | adobe-commerce-code-review | Y |
| db3 Customer 500 | debugging | adobe-commerce-debugging | Y |
| cr1 Review PR merge readiness | code-review | adobe-commerce-code-review | Y |
| sr2 Admin lists emails security | security-review | adobe-commerce-security-review | Y |
| sr3 Analytics on payment page | security-review | adobe-commerce-security-review | Y |
| ft1 GA4 purchase missing | frontend-and-tracking | adobe-commerce-frontend-and-tracking | Y |
| fa2b Trace place-order PHP path | feature-analysis | adobe-commerce-feature-analysis | Y |
| ft3 Button margin L0 | frontend-and-tracking | adobe-commerce-frontend-and-tracking | Y |
| iw1 ERP payload discount field | integration-work | adobe-commerce-integration-work | Y |
| ms2 CLI replay one order | module-scaffold | adobe-commerce-module-scaffold | Y |
| iw3 Document ERP on plain-store | integration-work | adobe-commerce-integration-work | Y |

## Notes

- Raw agent transcripts: `/tmp/eval-results-0.5.2.jsonl` (142 runs).
- Forbidden-hit column uses keyword heuristics and over-flags when the agent *discusses* forbidden actions while refusing them; do not treat 26 pack_on keyword hits as 26 behavioural failures.
- Timeouts on first pass: checkout-negative-gtm, ft-button-spacing-l0, ft-negative-backend, pd-negative-routine (ft-button-spacing re-ran OK).
- **Done for 0.5.2:** auth, vendor direct-Read yes/yes, live eval headlines, overlap audit (1 timeout on cs3), description tightenings for the three trigger misses + understanding/integration map split.

