#!/usr/bin/env python3
"""Build a reproducible, installable WordPress plugin ZIP."""

import argparse
import json
import re
import zipfile
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
PLUGIN = "code-content-blocks"
FILES = (
    "LICENSE",
    "readme.txt",
    "code-content-blocks.php",
    "assets/code-renderer.js",
    "blocks/code/block.json",
    "blocks/code/editor.asset.php",
    "blocks/code/editor.css",
    "blocks/code/editor.js",
    "blocks/code/style.css",
    "includes/class-code-languages.php",
)


def release_version():
    main = (ROOT / "code-content-blocks.php").read_text(encoding="utf-8")
    readme = (ROOT / "readme.txt").read_text(encoding="utf-8")
    block = json.loads((ROOT / "blocks/code/block.json").read_text(encoding="utf-8"))

    def require(pattern, content, label):
        match = re.search(pattern, content, re.MULTILINE)
        if not match:
            raise ValueError(f"Missing {label} version")
        return match.group(1)

    versions = {
        "plugin header": require(r"^ \* Version: (\d+\.\d+\.\d+)$", main, "plugin header"),
        "PHP constant": require(r"^define\( 'CCB_VERSION', '(\d+\.\d+\.\d+)' \);$", main, "PHP constant"),
        "WordPress readme": require(r"^Stable tag: (\d+\.\d+\.\d+)$", readme, "WordPress readme"),
        "block metadata": block.get("version"),
    }
    if len(set(versions.values())) != 1:
        raise ValueError(f"Version mismatch: {versions}")
    return versions["plugin header"]


def build(output):
    version = release_version()
    missing = [path for path in FILES if not (ROOT / path).is_file()]
    if missing:
        raise FileNotFoundError(f"Missing release files: {', '.join(missing)}")
    output.parent.mkdir(parents=True, exist_ok=True)
    with zipfile.ZipFile(output, "w", compression=zipfile.ZIP_DEFLATED, compresslevel=9) as archive:
        for path in FILES:
            info = zipfile.ZipInfo(f"{PLUGIN}/{path}", date_time=(2020, 1, 1, 0, 0, 0))
            info.compress_type = zipfile.ZIP_DEFLATED
            info.external_attr = 0o644 << 16
            archive.writestr(info, (ROOT / path).read_bytes(), compress_type=zipfile.ZIP_DEFLATED, compresslevel=9)
    return version


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--output", type=Path, help="ZIP destination (default: dist/code-content-blocks-VERSION.zip)")
    args = parser.parse_args()
    output = args.output or ROOT / "dist" / f"{PLUGIN}-{release_version()}.zip"
    version = build(output)
    print(f"Built {output} (v{version})")
