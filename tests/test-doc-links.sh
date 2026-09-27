#!/usr/bin/env bash
set -euo pipefail

root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
readme="$root/README.md"

grep -Fq 'https://github.com/Monotoba/markdown-importer-blocks' "$readme"
grep -Fq 'https://github.com/Monotoba/math-content-blocks' "$readme"
grep -Fq 'https://github.com/Monotoba/Mermaid-WP-Block' "$readme"
grep -Fq '[CONTRIBUTING.md](CONTRIBUTING.md)' "$readme"
grep -Fq '[SECURITY.md](SECURITY.md)' "$readme"

test -f "$root/CONTRIBUTING.md"
test -f "$root/SECURITY.md"
grep -Fq 'tests/manual-test-plan.md' "$root/CONTRIBUTING.md"
grep -Fq 'https://github.com/Monotoba/code-content-blocks/security/advisories/new' "$root/SECURITY.md"

if grep -Eq '\.\./(markdown-importer-blocks|math-content-blocks|mermaid-content-blocks)' "$readme"; then
	echo 'Stale relative sibling-repository link found in README.md' >&2
	exit 1
fi

echo 'Documentation repository links validated.'
