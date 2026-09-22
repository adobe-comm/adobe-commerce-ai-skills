# Bootstrapping quality tooling

Use when a project has no lint or test setup. Propose this as its own task with the tech lead's agreement; never fold it into an unrelated ticket, because it touches root `composer.json` and will produce a large first wave of findings.

Under DDEV, prefix every command with `ddev exec`.

## 1. Coding standard

```bash
composer require --dev magento/magento-coding-standard
vendor/bin/phpcs --config-set installed_paths ../../magento/magento-coding-standard
vendor/bin/phpcs --standard=Magento2 app/code/<Vendor>/<Module>
```

Source: https://github.com/magento/magento-coding-standard

Start scoped to one module, not the whole `app/code`, or the output is unusable.

## 2. Baseline the existing debt

Run on changed files only in day-to-day work. For a first pass, record current findings per module so new code can be held to a clean bar without requiring a full cleanup first. Agree explicitly whether existing findings are fixed now or tracked.

## 3. Unit tests

Adobe Commerce ships PHPUnit configuration at `dev/tests/unit/phpunit.xml.dist`. Confirm it exists, then:

```bash
vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/<Vendor>/<Module>/Test/Unit
```

Match the metadata style to the installed PHPUnit major:

| PHPUnit major | Metadata style |
|---------------|----------------|
| 12 and later | Attributes only (`#[Test]`, `#[DataProvider]`, `#[Group]`) — doc-comment annotations removed |
| 11 | Attributes preferred; annotations deprecated |
| 10 | Attributes supported; annotations still work |

Data providers must be `public static` on PHPUnit 10 and later.

## 4. Integration tests (optional, later)

Requires a dedicated test database configured in `dev/tests/integration/etc/install-config-mysql.php`. Slow and environment-dependent — introduce only when DI wiring, repositories, or DB behaviour genuinely need coverage.

## 5. Composer script aliases

Add aliases so every developer and every skill invokes the same commands:

```json
{
  "scripts": {
    "lint": "phpcs --standard=Magento2",
    "test:unit": "phpunit -c dev/tests/unit/phpunit.xml.dist",
    "test:integration": "phpunit -c dev/tests/integration/phpunit.xml.dist"
  }
}
```

Then record the exact lines in the project's `AGENTS.md` so the agent uses them verbatim.

## 6. CI gate

Mirror the same commands in the pipeline (phpcs on changed files, unit tests, static analysis if adopted). Prompts and rules are not an enforcement mechanism — CI is.
