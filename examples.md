# Code Content Blocks examples

## Python

```python
from dataclasses import dataclass

@dataclass
class Device:
    name: str
    pins: int

print(Device("27C256", 28))
```

## QuickBASIC

```quickbasic
DECLARE SUB FlashByte (addr AS LONG, value AS INTEGER)
FOR i = 0 TO 255
    POKE baseAddress + i, i
NEXT i
PRINT "Done"
```

## TRS-80 Color BASIC

```color-basic
10 CLS
20 PRINT "HELLO FROM COLOR BASIC"
30 FOR I=1 TO 10
40 SOUND 100+I*10,1
50 NEXT I
```

## Mermaid/Markdown cooperation note

This plugin intentionally owns `.mcb-code-block` markup and also highlights the Markdown Importer plugin's older `pre.mib-code-block` markup. The Markdown plugin can later convert code fences directly to `mcb/code` blocks or emit this shared frontend markup:

```html
<figure class="mcb-code-block ccb-code-block ccb-theme-system" data-mcb-code-block="1" data-mcb-language="python">
  <pre class="ccb-code-pre"><code class="mcb-code-source language-python">print("hello")</code></pre>
</figure>
```
