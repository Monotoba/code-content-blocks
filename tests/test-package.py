#!/usr/bin/env python3
"""Check that the release ZIP is installable, complete, and reproducible."""

import hashlib
import importlib.util
import tempfile
import unittest
import zipfile
from pathlib import Path
from unittest import mock

ROOT = Path(__file__).resolve().parent.parent
spec = importlib.util.spec_from_file_location("build_release", ROOT / "tools/build-release.py")
builder = importlib.util.module_from_spec(spec)
spec.loader.exec_module(builder)


class ReleasePackageTests(unittest.TestCase):
    def test_zip_contents_and_reproducibility(self):
        with tempfile.TemporaryDirectory() as directory:
            first, second = (Path(directory) / name for name in ("first.zip", "second.zip"))
            version = builder.build(first)
            self.assertEqual(version, builder.release_version())
            builder.build(second)
            self.assertEqual(hashlib.sha256(first.read_bytes()).digest(), hashlib.sha256(second.read_bytes()).digest())
            with zipfile.ZipFile(first) as archive:
                expected = {f"code-content-blocks/{path}" for path in builder.FILES}
                self.assertEqual(set(archive.namelist()), expected)
                self.assertEqual(archive.testzip(), None)
                self.assertIn(b"Plugin Name: Code Content Blocks", archive.read("code-content-blocks/code-content-blocks.php"))
                self.assertIn(b"BSD 2-Clause License", archive.read("code-content-blocks/LICENSE"))
                for item in archive.infolist():
                    self.assertEqual(item.date_time, (2020, 1, 1, 0, 0, 0))
                    self.assertEqual((item.external_attr >> 16) & 0o777, 0o644)

    def test_versions_agree(self):
        self.assertRegex(builder.release_version(), r"^\d+\.\d+\.\d+$")
        self.assertEqual(builder.check_tag(f"v{builder.release_version()}"), builder.release_version())
        with self.assertRaisesRegex(ValueError, "does not match"):
            builder.check_tag("v9.9.9")

    def test_mismatched_version_is_rejected(self):
        with tempfile.TemporaryDirectory() as directory:
            root = Path(directory)
            for path in ("code-content-blocks.php", "readme.txt", "blocks/code/block.json"):
                target = root / path
                target.parent.mkdir(parents=True, exist_ok=True)
                target.write_bytes((ROOT / path).read_bytes())
            readme = root / "readme.txt"
            current = builder.release_version()
            readme.write_text(readme.read_text(encoding="utf-8").replace(f"Stable tag: {current}", "Stable tag: 9.9.9"), encoding="utf-8")
            with mock.patch.object(builder, "ROOT", root):
                with self.assertRaisesRegex(ValueError, "Version mismatch"):
                    builder.release_version()


if __name__ == "__main__":
    unittest.main()
