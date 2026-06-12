=== Code Content Blocks ===
Contributors: rmorgan62, chatgpt
Tags: code, syntax highlighting, highlight.js, block, gutenberg
Requires at least: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Adds a syntax-highlighted Code block with light, dark, and system themes plus a broad language registry from legacy languages to current stacks.

== Description ==

Code Content Blocks provides a standalone WordPress block for highlighted source code. It is designed to work alongside Mermaid Content Blocks, Math Content Blocks, and the Markdown Importer plugin.

Features:

* Code block with live editor preview.
* Light, dark, and system color modes.
* Line numbers.
* Long-line wrapping.
* Highlight selected line ranges such as 1,3-5.
* Copy button.
* Caption support.
* Shared frontend API: window.MCBCodeBlocks.renderAll(root) and window.MCBCodeBlocks.renderBlock(element).
* Shared markup contract using .mcb-code-block and .mcb-code-source.
* Backward-compatible highlighting for Markdown Importer pre.mib-code-block code fences.
* 80+ language registry entries.
* Compact built-in grammars for legacy languages that Highlight.js does not commonly ship as first-class grammars.

The plugin uses Highlight.js 11.11.1 from cdnjs. If the CDN is blocked, code remains readable as escaped plaintext.

== Installation ==

1. Upload the code-content-blocks folder to /wp-content/plugins/ or install the ZIP through Plugins > Add New > Upload Plugin.
2. Activate Code Content Blocks.
3. Add the Code Block block to a post or page.

== Adding languages ==

Add a definition to includes/class-code-languages.php. If Highlight.js already provides a component, set the hljs field. If not, set custom => true and add a compact grammar in assets/code-renderer.js.

== Changelog ==

= 1.0.0 =
Initial release.
