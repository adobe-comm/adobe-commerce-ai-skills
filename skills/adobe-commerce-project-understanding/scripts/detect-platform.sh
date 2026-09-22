#!/usr/bin/env bash
# Read-only platform signal detector for Adobe Commerce-shaped repos.
# Prints "label: path" lines. Exits 0 even when nothing is found.
set -euo pipefail

ROOT="${1:-.}"
if [[ ! -d "$ROOT" ]]; then
  echo "error: not a directory: $ROOT" >&2
  exit 1
fi
ROOT="$(cd "$ROOT" && pwd)"

found=0
emit() {
  local label="$1" path="$2"
  echo "${label}: ${path}"
  found=1
}

# Composer / Magento PHP app
if [[ -f "$ROOT/composer.json" ]]; then
  emit "composer.json" "$ROOT/composer.json"
fi
if [[ -f "$ROOT/composer.lock" ]]; then
  emit "composer.lock" "$ROOT/composer.lock"
fi
if [[ -f "$ROOT/app/etc/config.php" ]]; then
  emit "magento-config.php" "$ROOT/app/etc/config.php"
fi
if [[ -d "$ROOT/app/code" ]]; then
  emit "app-code" "$ROOT/app/code"
fi
if [[ -d "$ROOT/vendor/magento" ]]; then
  emit "vendor-magento" "$ROOT/vendor/magento"
fi

# Cloud PaaS
if [[ -f "$ROOT/.magento.app.yaml" ]]; then
  emit "cloud-magento-app-yaml" "$ROOT/.magento.app.yaml"
fi
if [[ -d "$ROOT/.magento" ]]; then
  emit "cloud-magento-dir" "$ROOT/.magento"
fi

# App Builder
if [[ -f "$ROOT/app.config.yaml" ]]; then
  emit "app-builder-config" "$ROOT/app.config.yaml"
fi
if [[ -d "$ROOT/actions" ]]; then
  emit "app-builder-actions" "$ROOT/actions"
fi

# Edge Delivery / boilerplate storefront signals
if [[ -d "$ROOT/blocks" ]]; then
  emit "eds-blocks" "$ROOT/blocks"
fi
if [[ -d "$ROOT/scripts/initializers" ]]; then
  emit "eds-initializers" "$ROOT/scripts/initializers"
fi

# Frontend / Hyva (presence only; no deep scan)
if [[ -d "$ROOT/app/design/frontend" ]]; then
  emit "design-frontend" "$ROOT/app/design/frontend"
fi
shopt -s nullglob
for p in "$ROOT"/vendor/hyva-themes "$ROOT"/vendor/hyva-themes/*; do
  [[ -e "$p" ]] && emit "hyva-vendor" "$p" && break
done
shopt -u nullglob

# Local stacks
if [[ -d "$ROOT/.ddev" ]]; then
  emit "ddev" "$ROOT/.ddev"
fi
if [[ -f "$ROOT/docker-compose.yml" ]] || [[ -f "$ROOT/docker-compose.yaml" ]]; then
  emit "docker-compose" "$ROOT/docker-compose.yml"
fi
if [[ -d "$ROOT/.warden" ]] || [[ -f "$ROOT/.warden" ]]; then
  emit "warden" "$ROOT/.warden"
fi

if [[ "$found" -eq 0 ]]; then
  echo "no-platform-signals: $ROOT"
  exit 0
fi

# Disambiguation hints. App Builder markers without a PHP application mean the
# customization surface is out-of-process, which is also true for ACCS/ACO.
has_php_app=0
[[ -d "$ROOT/app/code" || -f "$ROOT/app/etc/config.php" || -d "$ROOT/vendor/magento" ]] && has_php_app=1
has_appbuilder=0
[[ -f "$ROOT/app.config.yaml" || -d "$ROOT/actions" ]] && has_appbuilder=1

if [[ "$has_appbuilder" -eq 1 && "$has_php_app" -eq 0 ]]; then
  echo "hint: out-of-process only (App Builder or ACCS/ACO) - no in-process PHP surface found; confirm which before advising"
fi
if [[ "$has_php_app" -eq 1 && -f "$ROOT/.magento.app.yaml" ]]; then
  echo "hint: in-process PHP app on Cloud PaaS"
elif [[ "$has_php_app" -eq 1 ]]; then
  echo "hint: in-process PHP app (on-premises or Cloud - confirm from deploy config)"
fi
exit 0
