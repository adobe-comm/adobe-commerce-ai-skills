#!/usr/bin/env python3
"""Block agent writes to Magento protected paths. Fail-closed for this hook.

Reads preToolUse JSON from stdin. Denies Write/StrReplace/Delete/EditNotebook
when the target path is under vendor/, generated/, pub/static/, or is
app/etc/env.php / auth.json.

Source: Cursor hooks reference (preToolUse permission allow|deny).
"""
from __future__ import annotations

import json
import sys
from typing import Any


def normalize(path: str) -> str:
    return path.replace("\\", "/").strip()


def is_protected(path: str) -> bool:
    if not path:
        return False
    p = normalize(path)
    # Strip leading ./
    while p.startswith("./"):
        p = p[2:]

    parts = [x for x in p.split("/") if x and x != "."]
    lower_parts = [x.lower() for x in parts]

    if "vendor" in lower_parts or "generated" in lower_parts:
        return True
    if "pub" in lower_parts:
        i = lower_parts.index("pub")
        if i + 1 < len(lower_parts) and lower_parts[i + 1] == "static":
            return True

    joined = "/".join(lower_parts)
    if joined.endswith("app/etc/env.php") or joined == "app/etc/env.php":
        return True
    if lower_parts and lower_parts[-1] == "auth.json":
        return True
    return False


def extract_paths(tool_input: Any) -> list[str]:
    paths: list[str] = []
    if not isinstance(tool_input, dict):
        return paths
    for key in ("path", "file_path", "filePath", "target_notebook"):
        val = tool_input.get(key)
        if isinstance(val, str):
            paths.append(val)
    for key in ("paths", "files"):
        val = tool_input.get(key)
        if isinstance(val, list):
            paths.extend(str(x) for x in val if x)
    return paths


def deny(path: str) -> dict:
    msg = (
        f"Blocked write to protected path: {path}. "
        "Do not edit vendor/, generated/, pub/static/, app/etc/env.php, or auth.json. "
        "Use Composer patches for vendor changes."
    )
    return {
        "permission": "deny",
        "user_message": msg,
        "agent_message": msg,
    }


def main() -> int:
    try:
        raw = sys.stdin.read()
        data = json.loads(raw) if raw.strip() else {}
    except Exception:
        print(json.dumps({
            "permission": "deny",
            "user_message": "Protected-path write hook received invalid JSON; blocking write.",
            "agent_message": "Write blocked: guardrail hook could not parse tool input.",
        }))
        return 0

    tool_input = data.get("tool_input") or data.get("arguments") or {}
    for path in extract_paths(tool_input):
        if is_protected(path):
            print(json.dumps(deny(path)))
            return 0

    print(json.dumps({"permission": "allow"}))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
