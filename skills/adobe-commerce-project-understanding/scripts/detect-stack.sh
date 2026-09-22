#!/usr/bin/env bash
# Read-only stack detector. Reads composer.json / composer.lock through the
# shell, which is not blocked by .cursorignore, so version facts stay available
# even when vendor/ and composer.lock are gitignored.
# Prints "key: value" lines. Exits 0 even when nothing is found.
set -euo pipefail

ROOT="${1:-.}"
if [[ ! -d "$ROOT" ]]; then
  echo "error: not a directory: $ROOT" >&2
  exit 1
fi
ROOT="$(cd "$ROOT" && pwd)"

if ! command -v python3 >/dev/null 2>&1; then
  echo "error: python3 required for lock parsing" >&2
  exit 1
fi

python3 - "$ROOT" << 'PY'
import json, os, sys, glob

root = sys.argv[1]

def load(path):
    try:
        with open(os.path.join(root, path), encoding="utf-8") as fh:
            return json.load(fh)
    except Exception:
        return None

cj = load("composer.json")
cl = load("composer.lock")

# Installed packages from the lock (authoritative), else declared constraints.
installed = {}
if cl:
    for section in ("packages", "packages-dev"):
        for pkg in cl.get(section) or []:
            name = pkg.get("name")
            if name:
                installed[name] = pkg.get("version", "unknown")

declared = {}
if cj:
    for section in ("require", "require-dev"):
        declared.update(cj.get(section) or {})

if not cj and not cl:
    print("no-composer-metadata: " + root)

COMMERCE = [
    "magento/product-enterprise-edition",
    "magento/product-community-edition",
    "magento/magento-cloud-metapackage",
    "magento/magento2-base",
    "adobe-commerce/commerce-cloud-metapackage",
]
for name in COMMERCE:
    if name in installed:
        print(f"commerce-package: {name}@{installed[name]} (composer.lock)")
    elif name in declared:
        print(f"commerce-constraint: {name}@{declared[name]} (composer.json)")

if "magento/framework" in installed:
    print(f"framework-version: magento/framework@{installed['magento/framework']} (composer.lock)")

if "php" in declared:
    print(f"php-constraint: {declared['php']} (composer.json)")

def report(label, name):
    if name in installed:
        print(f"{label}: {name}@{installed[name]} (installed)")
    elif name in declared:
        print(f"{label}: {name}@{declared[name]} (declared, not in lock)")
    else:
        print(f"{label}: absent")

report("phpunit", "phpunit/phpunit")
report("coding-standard", "magento/magento-coding-standard")
report("phpcs", "squizlabs/php_codesniffer")
report("phpstan", "phpstan/phpstan")
report("psalm", "vimeo/psalm")
report("rector", "rector/rector")

if "phpunit/phpunit" in installed:
    major = installed["phpunit/phpunit"].lstrip("v").split(".")[0]
    print(f"phpunit-major: {major}")
    if major.isdigit() and int(major) >= 12:
        print("phpunit-metadata: attributes required (doc-comment annotations removed in PHPUnit 12)")
    elif major.isdigit() and int(major) == 11:
        print("phpunit-metadata: annotations deprecated, prefer attributes")

scripts = (cj or {}).get("scripts") or {}
if scripts:
    print("composer-scripts: " + ", ".join(sorted(scripts.keys())))
else:
    print("composer-scripts: none")

# Test harness and tooling config presence
checks = {
    "unit-phpunit-config": ["dev/tests/unit/phpunit.xml", "dev/tests/unit/phpunit.xml.dist"],
    "integration-phpunit-config": ["dev/tests/integration/phpunit.xml", "dev/tests/integration/phpunit.xml.dist"],
    "phpcs-config": ["phpcs.xml", "phpcs.xml.dist", ".phpcs.xml.dist"],
    "phpstan-config": ["phpstan.neon", "phpstan.neon.dist"],
    "ddev": [".ddev"],
    "vendor-installed": ["vendor/magento"],
    "makefile": ["Makefile"],
}
for label, paths in checks.items():
    hit = next((p for p in paths if os.path.exists(os.path.join(root, p))), None)
    print(f"{label}: {hit if hit else 'absent'}")

module_tests = glob.glob(os.path.join(root, "app/code/*/*/Test/Unit"))
print(f"module-unit-test-dirs: {len(module_tests)}")

if os.path.isdir(os.path.join(root, ".ddev")):
    print("command-prefix: ddev exec (DDEV project detected)")
else:
    print("command-prefix: none (run natively)")
PY
exit 0
