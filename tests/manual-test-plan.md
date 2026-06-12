# Manual test plan

1. Install and activate Code Content Blocks on a staging WordPress 7+ site.
2. Create a post and insert the **Code Block** block.
3. Test at least these languages:
   - `python`
   - `c`
   - `cpp`
   - `zig`
   - `quickbasic`
   - `color-basic`
   - `commodore-basic`
   - `fortran`
   - `algol`
   - `bcpl`
   - `pl1`
   - `pascal`
   - `delphi`
   - `oberon`
   - `java`
   - `javascript`
   - `dart`
   - `flutter`
   - `bash`
   - `dos`
   - `powershell`
4. Confirm light, dark, and system modes.
5. Confirm line numbers, line wrapping, highlighted line ranges, captions, and copy button behavior.
6. Paste a Markdown Importer generated `<pre class="mib-code-block"><code class="language-python">...</code></pre>` block and confirm it receives highlighting when this plugin is active.
7. Disable network access or block the CDN and confirm code remains readable without highlighting.
