# Code Content Blocks

[![Tests](https://github.com/Monotoba/code-content-blocks/actions/workflows/test.yml/badge.svg)](https://github.com/Monotoba/code-content-blocks/actions/workflows/test.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![WordPress Plugin: Compatible with 6.3+](https://img.shields.io/badge/WordPress-6.3%2B-blue.svg)](https://wordpress.org)
[![PHP: 7.4+](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net)

A WordPress plugin that adds a syntax-highlighted Code block for displaying source code with comprehensive language support.

**Works standalone or as part of the cohesive trio:** [Markdown Importer](https://github.com/Monotoba/markdown-importer-blocks) + **Code Content Blocks** + [Math Content Blocks](https://github.com/Monotoba/math-content-blocks). See [Mermaid Content Blocks](https://github.com/Monotoba/Mermaid-WP-Block) for diagram support.

## Features

- **Syntax-Highlighted Code Block** – Display source code with language-specific highlighting
- **80+ Languages** – Comprehensive language registry from legacy to modern stacks
- **Multiple Themes** – Light, dark, and system color modes
- **Line Numbers** – Optional line numbering for easy reference
- **Line Highlighting** – Highlight specific line ranges (e.g., `1,3-5`)
- **Long-Line Wrapping** – Control how long lines are handled
- **Copy Button** – One-click copy-to-clipboard functionality
- **Caption Support** – Add descriptive text above or below code blocks
- **Live Editor Preview** – See changes in real-time while editing
- **Shared Renderer API** – `window.MCBCodeBlocks.renderAll()` and `window.MCBCodeBlocks.renderBlock()`
- **Backward Compatible** – Highlights Markdown Importer's legacy code fence markup
- **Graceful Fallback** – Code remains readable as plaintext if JavaScript fails

## Installation

### Via WordPress Plugin Directory (When Available)
1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "Code Content Blocks"
3. Click **Install Now** and then **Activate**

### Manual Installation
1. Download the plugin from the [releases page](https://github.com/Monotoba/code-content-blocks/releases)
2. Upload the `code-content-blocks` folder to `/wp-content/plugins/`
3. Go to **Plugins** in WordPress admin and activate "Code Content Blocks"
4. You're ready to use the block!

## Quick Start

1. Edit or create a post/page in WordPress
2. Click the **+** button to add a block
3. Search for "Code" and insert the **Code Content Block**
4. Select a language from the dropdown
5. Paste or type your source code
6. (Optional) Add a caption, enable line numbers, highlight specific lines
7. Publish!

## Examples

### Python Function
```python {1,3-4}
def fibonacci(n: int) -> list:
    """Generate fibonacci sequence up to n."""
    if n <= 0:
        return []
    return [1, 1] + [fib(i) for i in range(2, n)]
```

### JavaScript Promise
```javascript {5}
const fetchData = (url) => {
  return fetch(url)
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error(error));
}
```

### SQL Query
```sql {2}
SELECT users.id, users.name, COUNT(posts.id) as post_count
FROM users
LEFT JOIN posts ON users.id = posts.user_id
GROUP BY users.id
ORDER BY post_count DESC;
```

See [examples.md](examples.md) for more language examples.

## Supported Languages

**Scripting & Data:** Python, Ruby, JavaScript, TypeScript, PHP, Perl, Bash, Shell, Lua, Groovy, R, MATLAB

**Web:** HTML, CSS, SCSS, Less, XML, JSON, YAML, TOML

**Systems:** C, C++, C#, Java, Go, Rust, Kotlin, Swift

**Markup & Templates:** Markdown, LaTeX, XAML, Haml, Pug, Handlebars, Jinja, ERB, Liquid

**Database & Query:** SQL, PostgreSQL, MySQL, T-SQL, PL/SQL, Cypher, HCL

**Functional:** Lisp, Clojure, Scheme, Haskell, Elm, F#, Scala

**Legacy & Domain-Specific:** COBOL, FORTRAN, Pascal, Ada, Verilog, VHDL, Cython, Assembly, x86, and 40+ more

## Adding Custom Languages

Edit `includes/class-code-languages.php` to add new language definitions. If Highlight.js provides a component, reference it via `hljs`. For missing languages, add a compact grammar in `assets/code-renderer.js` with `custom: true`.

## Features When Used with Markdown Importer

When activated alongside [Markdown Importer Blocks](https://github.com/Monotoba/markdown-importer-blocks):

- Fenced code blocks in Markdown automatically render with syntax highlighting
- Global code theme setting applies to all code blocks in the Markdown
- Line number and copy button settings sync across imported content
- Users can configure variable color rules for better readability

## Usage with Other Plugins

- **Markdown Importer Blocks** – Converts Markdown fenced code into highlighted code blocks
- **Math Content Blocks** – No direct interaction, but works well in multi-format content
- **Mermaid Content Blocks** – Complementary for diagram-heavy documentation

## Content Security Policy

The plugin loads Highlight.js from cdnjs:

```
https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js
```

### CSP Configuration

If your WordPress site has a restrictive Content Security Policy (CSP), allow this script source:

```
script-src 'self' https://cdnjs.cloudflare.com
```

Alternatively, bundle Highlight.js locally and modify the `ccb_register_block()` function in `code-content-blocks.php`.

## Security

### Security Posture

- **Output Escaping** – All code is properly escaped before rendering
- **Language Validation** – Only known languages are processed
- **No Code Execution** – Code is displayed only, never executed
- **KSES Filtering** – Output passes through WordPress KSES allowlist
- **Pinned Library Version** – Uses Highlight.js 11.11.1, not a floating tag

### Important Notes

- Authors who can edit posts can add code blocks
- Do not grant post editing privileges to untrusted users
- Code is stored as-is in post content
- Always keep WordPress and Highlight.js updated

## Testing

### Running Tests Locally

From the plugin directory, run:

```bash
bash tools/smoke-test.sh
```

This validates:
- PHP syntax via `php -l`
- JavaScript syntax via `node --check`
- Requires `php` and `node` to be installed

### Manual Testing

See [tests/manual-test-plan.md](tests/manual-test-plan.md) for comprehensive testing procedures.

## Requirements

- **WordPress:** 6.3 or later
- **PHP:** 7.4 or later
- **Browser:** Modern browser with ES6+ support

## Versioning

This project follows [Semantic Versioning](https://semver.org/):
- **MAJOR** – Breaking changes or significant new features
- **MINOR** – Backward-compatible new features
- **PATCH** – Bug fixes and security patches

## License

This project is licensed under the [MIT License](LICENSE) – see the LICENSE file for details.

## Support

For issues, feature requests, or questions:
- Open an [issue on GitHub](https://github.com/Monotoba/code-content-blocks/issues)
- Check [existing discussions](https://github.com/Monotoba/code-content-blocks/discussions)

## Credits

- Plugin author: Monotoba
- Uses [Highlight.js](https://highlightjs.org/) for syntax highlighting
- Distributed via cdnjs CDN

## Related Plugins

- **[Markdown Importer Blocks](https://github.com/Monotoba/markdown-importer-blocks)** – Import Markdown and delegate code/math/diagrams to specialized plugins
- **[Math Content Blocks](https://github.com/Monotoba/math-content-blocks)** – Render mathematical formulas (TeX, AsciiMath, MathML)
- **[Mermaid Content Blocks](https://github.com/Monotoba/Mermaid-WP-Block)** – Create diagrams and flowcharts

Use all four plugins together for a complete content creation system, or use Code Content Blocks standalone.

## Changelog

### 1.0.0 (Initial Release)
- Syntax-highlighted code block for WordPress block editor
- 80+ language registry
- Light, dark, and system theme modes
- Line numbers and line highlighting
- Copy button and caption support
- Shared renderer API for integration
