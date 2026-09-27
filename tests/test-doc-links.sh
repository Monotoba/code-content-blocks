#!/usr/bin/env bash
set -euo pipefail

root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
readme="$root/README.md"

grep -Fq 'https://github.com/Monotoba/markdown-importer-blocks' "$readme"
grep -Fq 'https://github.com/Monotoba/math-content-blocks' "$readme"
grep -Fq 'https://github.com/Monotoba/Mermaid-WP-Block' "$readme"

if grep -Eq '\.\./(markdown-importer-blocks|math-content-blocks|mermaid-content-blocks)' "$readme"; then
	echo 'Stale relative sibling-repository link found in README.md' >&2
	exit 1
fi

echo 'Documentation repository links validated.'
