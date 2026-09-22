#!/usr/bin/env bash
# Install adobe-commerce-ai-skills into a Commerce project for Teams without
# relying solely on the team marketplace. Copies rules, skills, and guardrail hooks.
set -euo pipefail

SRC_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DEST="${1:-.}"

if [[ ! -d "$DEST" ]]; then
  echo "error: destination directory does not exist: $DEST" >&2
  exit 1
fi

DEST="$(cd "$DEST" && pwd)"

mkdir -p "$DEST/.cursor/rules" "$DEST/.cursor/skills" "$DEST/.cursor/hooks" "$DEST/docs/ai"

# Always-on core rule
cp -f "$SRC_ROOT/rules/adobe-commerce-core.mdc" "$DEST/.cursor/rules/adobe-commerce-core.mdc"

# Skills (each folder with SKILL.md)
while IFS= read -r -d '' skill_dir; do
  name="$(basename "$skill_dir")"
  target="$DEST/.cursor/skills/$name"
  mkdir -p "$target"
  if command -v rsync >/dev/null 2>&1; then
    rsync -a --delete "$skill_dir/" "$target/"
  else
    rm -rf "$target"
    mkdir -p "$target"
    cp -a "$skill_dir"/. "$target"/
  fi
done < <(find "$SRC_ROOT/skills" -mindepth 1 -maxdepth 1 -type d -print0 2>/dev/null)

# Guardrail hooks (project-level paths; overwrite scripts, merge hooks.json carefully)
cp -f "$SRC_ROOT/scripts/block-protected-writes.py" "$DEST/.cursor/hooks/block-protected-writes.py"
cp -f "$SRC_ROOT/scripts/block-protected-shell.py" "$DEST/.cursor/hooks/block-protected-shell.py"
chmod +x "$DEST/.cursor/hooks/block-protected-writes.py" "$DEST/.cursor/hooks/block-protected-shell.py"

if [[ -f "$DEST/.cursor/hooks.json" ]]; then
  echo "skip (exists): $DEST/.cursor/hooks.json — merge adobe-commerce hooks manually if needed"
  echo "  template: $SRC_ROOT/templates/project/.cursor/hooks.json"
else
  cp -f "$SRC_ROOT/templates/project/.cursor/hooks.json" "$DEST/.cursor/hooks.json"
  echo "created: $DEST/.cursor/hooks.json"
fi

# Project overlay templates (do not overwrite existing project files)
copy_if_absent() {
  local src="$1" dest="$2"
  if [[ -e "$dest" ]]; then
    echo "skip (exists): $dest"
  else
    mkdir -p "$(dirname "$dest")"
    cp -f "$src" "$dest"
    echo "created: $dest"
  fi
}

copy_if_absent "$SRC_ROOT/templates/project/AGENTS.md" "$DEST/AGENTS.md"
copy_if_absent "$SRC_ROOT/templates/project/docs/ai/project-facts.md" "$DEST/docs/ai/project-facts.md"
copy_if_absent "$SRC_ROOT/templates/project/.cursorignore" "$DEST/.cursorignore"
copy_if_absent "$SRC_ROOT/templates/project/.cursorindexingignore" "$DEST/.cursorindexingignore"

echo "Installed adobe-commerce skills into $DEST/.cursor/skills"
echo "Core rule: $DEST/.cursor/rules/adobe-commerce-core.mdc"
echo "Hooks scripts: $DEST/.cursor/hooks/"
echo "Fill AGENTS.md and docs/ai/project-facts.md for this project."
echo "Prefer team marketplace install when available (Teams plan)."
