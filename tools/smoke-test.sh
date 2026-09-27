#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
php tests/smoke.php
php tests/behavior.php
for js in assets/code-renderer.js blocks/code/editor.js; do
  node --check "$js"
  echo "JS OK: $js"
done
node tests/renderer.test.js
