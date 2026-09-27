# Security Policy

## Supported versions

Security fixes are applied to the current `main` branch and, after releases begin, to the latest published release when practical. Older versions may not receive fixes; users should update to the newest available version.

## Reporting a vulnerability

Please do not disclose suspected vulnerabilities or working exploits in a public issue.

Use GitHub's private vulnerability-reporting form:

https://github.com/Monotoba/code-content-blocks/security/advisories/new

Include:

- The affected plugin version or commit
- WordPress, PHP, and browser versions where relevant
- Reproduction steps or a minimal proof of concept
- The likely impact and required attacker permissions
- Any suggested mitigation, if known

You should receive an acknowledgement within seven days. Confirmed reports will be investigated, fixed, tested, and disclosed through a GitHub Security Advisory when appropriate. Please allow reasonable time for a fix before public disclosure.

## Scope

Security issues include unauthorized script or markup injection, insufficient escaping, unintended code execution, privilege-boundary violations, and unsafe handling of block attributes. General bugs, feature requests, and problems caused solely by unsupported WordPress or PHP versions belong in the public [issue tracker](https://github.com/Monotoba/code-content-blocks/issues).

The plugin loads the pinned Highlight.js version documented in the README from cdnjs. Reports involving CDN availability alone are operational rather than security issues, but integrity or supply-chain concerns are in scope.

