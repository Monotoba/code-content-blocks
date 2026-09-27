#!/usr/bin/env bash
set -euo pipefail

fail() {
  echo "Compatibility check failed: $1" >&2
  exit 1
}

assert_contains() {
  local file="$1"
  local expected="$2"
  grep -Fq -- "$expected" "$file" || fail "$file must contain: $expected"
}

assert_contains code-content-blocks.php " * Requires at least: 6.3"
assert_contains README.md "WordPress-6.3%2B"
assert_contains README.md "- **WordPress:** 6.3 or later"
assert_contains readme.txt "Requires at least: 6.3"
assert_contains blocks/code/block.json '"apiVersion": 3'
assert_contains code-content-blocks.php "'strategy'  => 'defer'"

if grep -Eq 'Requires at least: 7\.0|WordPress-7\.0|WordPress:\*\* 7\.0' code-content-blocks.php README.md readme.txt; then
  fail "obsolete WordPress 7.0 minimum remains in compatibility metadata"
fi

echo "Compatibility metadata is aligned with WordPress 6.3."
