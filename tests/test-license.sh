#!/usr/bin/env bash
set -euo pipefail

root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

assert_contains() {
	local file="$1"
	local expected="$2"
	grep -Fq -- "$expected" "$file" || {
		echo "License check failed: $file must contain: $expected" >&2
		exit 1
	}
}

assert_contains "$root/LICENSE" 'BSD 2-Clause License'
assert_contains "$root/LICENSE" 'Copyright (c) 2026 Randall Morgan'
assert_contains "$root/LICENSE" 'Redistributions of source code must retain the above copyright notice'
assert_contains "$root/LICENSE" 'Redistributions in binary form must reproduce the above copyright notice'
assert_contains "$root/code-content-blocks.php" ' * License: BSD-2-Clause'
assert_contains "$root/code-content-blocks.php" ' * License URI: https://opensource.org/license/bsd-2-clause'
assert_contains "$root/readme.txt" 'License: BSD-2-Clause'
assert_contains "$root/readme.txt" 'License URI: https://opensource.org/license/bsd-2-clause'
assert_contains "$root/README.md" 'License-BSD_2--Clause-blue.svg'
assert_contains "$root/README.md" '[BSD 2-Clause License](LICENSE)'

if grep -Eq 'MIT License|License: MIT|License-MIT|licenses/MIT' \
	"$root/LICENSE" "$root/README.md" "$root/readme.txt" "$root/code-content-blocks.php"; then
	echo 'License check failed: obsolete MIT declaration remains.' >&2
	exit 1
fi

echo 'BSD 2-Clause license declarations validated.'
