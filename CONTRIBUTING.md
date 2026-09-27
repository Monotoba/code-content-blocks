# Contributing to Code Content Blocks

Thank you for helping improve Code Content Blocks. Bug reports, compatibility findings, documentation corrections, language definitions, tests, and focused code changes are welcome.

## Before you begin

- Search the [existing issues](https://github.com/Monotoba/code-content-blocks/issues) before opening a new one.
- Use the security process in [SECURITY.md](SECURITY.md) for vulnerabilities; do not disclose exploitable details in a public issue.
- Keep changes focused. Unrelated refactoring makes reviews and regression testing harder.
- Preserve backward compatibility with the shared `mcb-*` markup and JavaScript API unless a breaking change has been discussed first.

## Development requirements

- PHP 7.4 or newer
- Node.js 18 or newer
- WordPress 6.3 or newer for integration testing

Fork and clone the repository, then run the automated checks from its root:

```bash
bash tools/smoke-test.sh
bash tests/test-doc-links.sh
bash tests/test-compatibility.sh
```

The smoke test performs PHP and JavaScript syntax checks and runs the PHP renderer and JavaScript frontend behavioral suites. For user-interface changes, also complete the relevant checks in [tests/manual-test-plan.md](tests/manual-test-plan.md).

## Making a change

1. Create a short-lived branch from `main`.
2. Add or update automated tests for every behavior change and bug fix.
3. Keep WordPress, PHP, and Block API compatibility metadata aligned.
4. Update README or WordPress `readme.txt` documentation when user-visible behavior changes.
5. Run all checks locally before opening a pull request.

Language additions belong in `includes/class-code-languages.php`. If Highlight.js does not provide the grammar, add the smallest practical custom grammar to `assets/code-renderer.js` and include representative tests.

## Coding expectations

- Follow the established WordPress-oriented PHP style and existing JavaScript style.
- Escape output at render time and sanitize values according to their context.
- Never execute displayed code.
- Avoid new runtime dependencies unless they provide a clear, documented benefit.
- Retain readable plaintext behavior when syntax highlighting is unavailable.

## Pull requests

Describe the problem, the chosen solution, and how it was tested. Include screenshots for visible editor or frontend changes. A pull request should be small enough to review, pass GitHub Actions, and avoid unrelated formatting changes.

