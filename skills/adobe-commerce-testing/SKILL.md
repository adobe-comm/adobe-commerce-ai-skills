---
name: adobe-commerce-testing
description: >
  Chooses, writes, and runs the right validation for an Adobe Commerce change
  (unit, integration, Web API functional, MFTF or other browser tests, JavaScript
  tests, static analysis, manual checks) in proportion to risk, and reports
  exactly what was and was not verified. Use while or after implementing a
  change, when asked to add or fix tests, or when asked whether a change is
  safe to merge. Do not use for documentation-only changes.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.0"
  verified-against: "Adobe Commerce testing guide 2026-09-22; 2.4.9 release notes (PHPUnit 12)"
---

# Testing

## When to use / skip

Use: any code change needing validation; writing or repairing tests.
Skip: docs/copy-only changes.

## Procedure

1. Discover the project's harness before writing anything: `composer.json` scripts, `dev/tests`, `phpunit.xml*`, `Test/Unit` folders, CI config, `AGENTS.md`. Do not assume MFTF — the project may use Playwright, Cypress, or nothing. Read these through the terminal, since `composer.lock` is ignored by file tools.
2. **If no harness exists** (common on projects that have never set one up), do not fabricate commands and do not silently skip validation. Say what is missing, do the manual validation the change needs, and offer the one-time setup in [references/bootstrap-quality-tooling.md](references/bootstrap-quality-tooling.md). Setting it up is a separate, explicitly agreed task — never bundle it into an unrelated ticket.
3. Read the PHPUnit major from `composer.lock` and match the metadata syntax: PHPUnit 12 removed doc-comment annotations, so `#[Test]`, `#[DataProvider]`, `#[Group]` attributes are required; PHPUnit 11 deprecates annotations; PHPUnit 10 supports both. Do not guess the major from memory.
4. Prefix every command with `ddev exec` when `.ddev/` exists; run natively otherwise.
5. Pick test types by change type:

   | Change | Minimum validation |
   |--------|-------------------|
   | Pure logic in one class | Unit |
   | DI/plugin/observer wiring, repository, DB behaviour | Integration |
   | REST/SOAP/GraphQL contract | Web API functional |
   | Storefront or admin flow | Existing browser suite, else documented manual steps |
   | Template/JS only | JS tests if present, else manual checklist |
   | Schema or data patch | Integration + rerun-safety check |
   | Config/ACL | Integration or manual admin verification |

6. Run targeted tests first (single file or directory). Full suite only at L3 or when the project requires it.
7. Adobe's contribution standard expects automated tests for code changes. If you skip them, state the reason and the manual validation performed.
8. Integration tests need a configured test database and are slow — say so rather than silently skipping.
9. Report honestly, always in this shape:
   `Ran: <command> -> <result>. Not run: <what> because <reason>.`

## Common commands (confirm they exist before relying on them)

```
vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist <path>
cd dev/tests/integration && ../../../vendor/bin/phpunit <path>
vendor/bin/phpcs --standard=Magento2 <changed files>
bin/magento dev:tests:run <type>
```

Under DDEV, prefix with `ddev exec`. Prefer the project's own `composer` script aliases when they exist.

## Must not

- Claim tests passed without running them in this session.
- Invent test commands or config paths.
- Write tests that mock everything just to reach coverage.
- Delete or skip failing tests to make a run green.

## Output

Test types chosen with a one-line reason, files added, and the honest run report.
