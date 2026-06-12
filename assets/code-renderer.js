(function () {
	'use strict';

	var registry = window.CCBCodeLanguages || { languages: {}, aliases: {} };
	var config = window.CCBCodeBlocksConfig || {};
	var cdnBase = config.cdnBase || 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/';
	var loadedLanguages = {};
	var loadingLanguages = {};
	var copyButtonsBound = false;

	function escapeHtml(value) {
		return String(value || '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function slug(value) {
		return String(value || '')
			.toLowerCase()
			.trim()
			.replace(/\+/g, 'plus')
			.replace(/#/g, 'sharp')
			.replace(/[\/_\s]+/g, '-')
			.replace(/[^a-z0-9-]+/g, '-')
			.replace(/-+/g, '-')
			.replace(/^-|-$/g, '');
	}

	function normalizeLanguage(value) {
		var key = slug(value || 'plaintext');
		if (!key) {
			return 'plaintext';
		}
		if (registry.aliases && registry.aliases[key]) {
			return registry.aliases[key];
		}
		if (registry.languages && registry.languages[key]) {
			return key;
		}
		var compact = key.replace(/-/g, '');
		if (registry.aliases && registry.aliases[compact]) {
			return registry.aliases[compact];
		}
		return 'plaintext';
	}

	function getDefinition(language) {
		language = normalizeLanguage(language);
		return (registry.languages && registry.languages[language]) || registry.languages.plaintext || { key: 'plaintext', label: 'Plain text', hljs: 'plaintext' };
	}

	function setError(block, message) {
		var error = block.querySelector('.ccb-code-error');
		if (!error) {
			return;
		}
		error.textContent = message;
		error.hidden = false;
	}

	function clearError(block) {
		var error = block.querySelector('.ccb-code-error');
		if (!error) {
			return;
		}
		error.textContent = '';
		error.hidden = true;
	}

	function simpleCaseInsensitiveLanguage(name, keywords, options) {
		options = options || {};
		return function (hljs) {
			var contains = [
				hljs.QUOTE_STRING_MODE,
				hljs.APOS_STRING_MODE,
				hljs.C_NUMBER_MODE
			];

			if (options.semicolonComment) {
				contains.push(hljs.COMMENT(';', '$'));
			}
			if (options.hashComment) {
				contains.push(hljs.COMMENT('#', '$'));
			}
			if (options.slashComment) {
				contains.push(hljs.C_LINE_COMMENT_MODE, hljs.C_BLOCK_COMMENT_MODE);
			}
			if (options.remComment) {
				contains.push({ scope: 'comment', begin: /\bREM\b/i, end: /$/ });
			}
			if (options.apostropheComment) {
				contains.push(hljs.COMMENT("'", '$'));
			}

			return {
				name: name,
				case_insensitive: options.caseInsensitive !== false,
				keywords: {
					keyword: keywords.join(' '),
					literal: options.literals || 'true false null nil yes no on off'
				},
				contains: contains
			};
		};
	}

	function registerCustomLanguages() {
		if (!window.hljs || typeof window.hljs.registerLanguage !== 'function') {
			return;
		}

		var hljs = window.hljs;
		var custom = {
			'algol': simpleCaseInsensitiveLanguage('ALGOL', 'begin end integer real boolean array procedure value if then else for step until do while switch goto own comment string bits long short true false'.split(' '), { semicolonComment: false }),
			'b': simpleCaseInsensitiveLanguage('B', 'auto extrn if else while switch case default goto return break next sizeof'.split(' '), { slashComment: true, caseInsensitive: false }),
			'bcpl': simpleCaseInsensitiveLanguage('BCPL', 'let manifest static external global get put resultis if then else unless while until repeat for to by do switchon into case default endcase return break loop finish true false'.split(' '), { slashComment: true }),
			'pl1': simpleCaseInsensitiveLanguage('PL/I', 'declare dcl procedure proc end call return if then else do while until to by repeat select when otherwise on goto put get file fixed float char character bit binary decimal based pointer init like entry returns'.split(' '), { slashComment: true }),
			'basic': simpleCaseInsensitiveLanguage('BASIC', 'print input let if then else elseif endif for to step next goto gosub return dim rem data read restore poke peek def fn on error resume open close get put line circle draw play sound cls clear run list load save new end stop while wend do loop until select case as integer string single double long randomize locate color screen width lprint'.split(' '), { remComment: true, apostropheComment: true }),
			'quickbasic': simpleCaseInsensitiveLanguage('QuickBASIC', 'declare sub function shared static dim redim preserve type end type as integer long single double string byte if then else elseif end if for to step next do loop while wend until select case end select gosub return goto on error resume open close input output append binary random get put line input print lprint locate color screen cls pset preset line circle paint view window shell system'.split(' '), { remComment: true, apostropheComment: true }),
			'color-basic': simpleCaseInsensitiveLanguage('TRS-80 Color BASIC', 'print input let if then else for to step next goto gosub return dim rem data read restore poke peek usr exec clear cls set reset point line pset preset circle paint draw play sound audio motor csave cload run list new end stop string mid left right val str chr asc len rnd timer'.split(' '), { remComment: true, apostropheComment: true }),
			'commodore-basic': simpleCaseInsensitiveLanguage('Commodore BASIC', 'print input let if then else for to step next goto gosub return dim rem data read restore poke peek sys wait load save verify open close get cmd new run list end stop clr cont fre pos spc tab usr rnd ti ti$ str val chr asc len mid left right'.split(' '), { remComment: true, apostropheComment: true }),
			'comal': simpleCaseInsensitiveLanguage('COMAL', 'proc endproc func endfunc if then elif else endif for to step next while endwhile repeat until case when otherwise endcase print input dim let import return true false loop exit'.split(' '), { remComment: true, apostropheComment: true }),
			'oberon': simpleCaseInsensitiveLanguage('Oberon', 'module import const type var procedure begin end if then elsif else case of while do repeat until for to by loop with return array record pointer to true false nil div mod or in is'.split(' '), { caseInsensitive: false }),
			'modula2': simpleCaseInsensitiveLanguage('Modula-2', 'module from import export definition implementation const type var procedure begin end if then elsif else case of while do repeat until for to by loop return array record pointer set div mod or in and not true false nil'.split(' '), { caseInsensitive: false }),
			'forth': simpleCaseInsensitiveLanguage('Forth', ': ; variable constant begin until again while repeat if else then do loop +loop leave create does immediate compile only literal emit cr . .s words bye key dup drop swap over rot pick roll @ ! c@ c! here allot cells cell+ true false'.split(' '), { slashComment: false }),
			'csh': simpleCaseInsensitiveLanguage('C shell', 'if then else endif switch case breaksw default endsw foreach end while set setenv unset unsetenv alias unalias source cd pushd popd dirs echo exit glob goto onintr repeat'.split(' '), { hashComment: true, caseInsensitive: false }),
			'dos': simpleCaseInsensitiveLanguage('DOS batch', 'echo off on set setlocal endlocal if exist errorlevel not defined else for in do call goto shift pause rem choice copy xcopy move del erase dir md mkdir rd rmdir cd chdir pushd popd type find findstr start exit color mode title attrib'.split(' '), { remComment: true }),
			'z80asm': simpleCaseInsensitiveLanguage('Z80 assembly', 'adc add and bit call ccf cp cpd cpdr cpi cpir cpl daa dec di djnz ei ex exx halt im in inc ind indr ini inir jp jr ld ldd lddr ldi ldir neg nop or otdr otir out outd outi pop push res ret reti retn rl rla rlc rlca rld rr rra rrc rrca rrd rst sbc scf set sla sra srl sub xor org equ db dw ds'.split(' '), { semicolonComment: true }),
			'm68kasm': simpleCaseInsensitiveLanguage('Motorola 68000 assembly', 'abcd add adda addi addq addx and andi asl asr bcc bchg bclr bra bset bsr btst chk clr cmp cmpa cmpi cmpm dbcc divs divu eor eori exg ext illegal jmp jsr lea link lsl lsr move movea movem movep moveq muls mulu nbcd neg negx nop not or ori pea reset rol ror roxl roxr rte rtr rts sbcd scc stop sub suba subi subq subx swap tas trap trapv tst unlink dc ds org equ'.split(' '), { semicolonComment: true }),
			'asm': simpleCaseInsensitiveLanguage('Generic assembly', 'mov move ld lda sta stx sty add adc sub sbc inc dec cmp cpx cpy and or xor eor not shl shr asl asr rol ror push pop call jsr ret rts jmp jp bra brk nop halt org equ db dw ds section segment global extern public'.split(' '), { semicolonComment: true }),
			'csv': function (hljs) { return { name: 'CSV', contains: [ { scope: 'string', begin: /"/, end: /"/, contains: [ { begin: /""/ } ] }, { scope: 'punctuation', begin: /,/ } ] }; },
			'zig': simpleCaseInsensitiveLanguage('Zig', 'const var fn pub export extern comptime inline noinline threadlocal align allowzero volatile if else switch while for break continue return defer errdefer async await suspend resume try catch unreachable struct enum union opaque error anytype undefined null true false'.split(' '), { slashComment: true, caseInsensitive: false })
		};

		Object.keys(custom).forEach(function (name) {
			if (!hljs.getLanguage(name)) {
				try {
					hljs.registerLanguage(name, custom[name]);
				} catch (error) {
					// Keep the renderer resilient: unsupported custom grammars fall back to plaintext.
				}
			}
		});
	}

	function scriptUrlForLanguage(component) {
		return cdnBase.replace(/\/$/, '') + '/languages/' + encodeURIComponent(component) + '.min.js';
	}

	function loadLanguage(definition) {
		if (!window.hljs) {
			return Promise.reject(new Error('Highlight.js is not available.'));
		}

		registerCustomLanguages();

		var component = definition && definition.hljs ? definition.hljs : definition.key;
		if (!component || component === 'plaintext' || component === 'auto') {
			return Promise.resolve('plaintext');
		}

		if (window.hljs.getLanguage(component)) {
			return Promise.resolve(component);
		}

		if (definition && definition.custom && window.hljs.getLanguage(definition.key)) {
			return Promise.resolve(definition.key);
		}

		if (loadedLanguages[component]) {
			return Promise.resolve(component);
		}

		if (loadingLanguages[component]) {
			return loadingLanguages[component];
		}

		loadingLanguages[component] = new Promise(function (resolve) {
			var script = document.createElement('script');
			script.src = scriptUrlForLanguage(component);
			script.async = true;
			script.onload = function () {
				loadedLanguages[component] = true;
				registerCustomLanguages();
				if (window.hljs.getLanguage(component)) {
					resolve(component);
					return;
				}
				if (definition && definition.custom && window.hljs.getLanguage(definition.key)) {
					resolve(definition.key);
					return;
				}
				if (definition && definition.fallback) {
					resolve(definition.fallback);
					return;
				}
				resolve('plaintext');
			};
			script.onerror = function () {
				registerCustomLanguages();
				if (definition && definition.custom && window.hljs.getLanguage(definition.key)) {
					resolve(definition.key);
					return;
				}
				if (definition && definition.fallback && window.hljs.getLanguage(definition.fallback)) {
					resolve(definition.fallback);
					return;
				}
				resolve('plaintext');
			};
			document.head.appendChild(script);
		});

		return loadingLanguages[component];
	}

	function parseHighlightLines(value) {
		var lines = {};
		String(value || '').split(',').forEach(function (part) {
			var range = part.split('-').map(function (item) { return parseInt(item, 10); });
			var start = range[0];
			var end = range.length > 1 ? range[1] : start;
			if (!isFinite(start) || start < 1) {
				return;
			}
			if (!isFinite(end) || end < start) {
				end = start;
			}
			for (var line = start; line <= end; line++) {
				lines[line] = true;
			}
		});
		return lines;
	}

	function updateSpanStack(lineHtml, stack) {
		var tagRegex = /<\/?span\b[^>]*>/g;
		var match;
		while ((match = tagRegex.exec(lineHtml)) !== null) {
			if (match[0].indexOf('</') === 0) {
				if (stack.length) {
					stack.pop();
				}
			} else {
				stack.push(match[0]);
			}
		}
	}

	function closeOpenSpans(stack) {
		return new Array(stack.length + 1).join('</span>');
	}

	function wrapLines(html, highlightedLines) {
		var lines = String(html).split('\n');
		var lastLineIsEmpty = lines.length > 1 && lines[lines.length - 1] === '';
		var stack = [];
		if (lastLineIsEmpty) {
			lines.pop();
		}
		return lines.map(function (rawLineHtml, index) {
			var lineNumber = index + 1;
			var classes = 'ccb-code-line';
			var prefix = stack.join('');
			updateSpanStack(rawLineHtml, stack);
			if (highlightedLines[lineNumber]) {
				classes += ' ccb-code-line-highlighted';
			}
			return '<span class="' + classes + '" data-line="' + lineNumber + '">' + prefix + (rawLineHtml || '\u200b') + closeOpenSpans(stack) + '</span>';
		}).join('\n');
	}

	function applyHighlight(codeElement, language, options) {
		options = options || {};
		var source = codeElement.textContent || '';
		var definition = getDefinition(language);

		return loadLanguage(definition).then(function (engineLanguage) {
			var highlighted = '';
			if (!window.hljs) {
				highlighted = escapeHtml(source);
			} else if (definition.key === 'auto') {
				highlighted = window.hljs.highlightAuto(source).value;
			} else if (engineLanguage && engineLanguage !== 'plaintext' && window.hljs.getLanguage(engineLanguage)) {
				highlighted = window.hljs.highlight(source, { language: engineLanguage, ignoreIllegals: true }).value;
			} else {
				highlighted = escapeHtml(source);
			}

			codeElement.innerHTML = wrapLines(highlighted, parseHighlightLines(options.highlightLines));
			codeElement.classList.add('hljs');
			codeElement.classList.add('language-' + definition.key);
			codeElement.setAttribute('data-mcb-highlighted-language', definition.key);
		});
	}

	function renderBlock(block) {
		if (!block || block.nodeType !== 1) {
			return Promise.resolve();
		}

		if (block.getAttribute('data-mcb-rendered') === 'true') {
			return Promise.resolve();
		}

		var code = block.querySelector('code.mcb-code-source, code.ccb-code-source');
		if (!code) {
			return Promise.resolve();
		}

		var language = block.getAttribute('data-mcb-language') || code.getAttribute('data-language') || code.className.replace(/^.*language-([a-zA-Z0-9_-]+).*$/, '$1');
		var definition = getDefinition(language);
		var highlightLines = block.getAttribute('data-mcb-highlight-lines') || '';
		var lineNumbers = block.getAttribute('data-mcb-line-numbers') === 'true';
		var wrap = block.getAttribute('data-mcb-wrap-lines') === 'true';

		clearError(block);
		block.setAttribute('data-mcb-rendered', 'pending');
		block.setAttribute('data-mcb-normalized-language', definition.key);
		block.classList.toggle('ccb-has-line-numbers', lineNumbers);
		block.classList.toggle('ccb-wrap-lines', wrap);

		return applyHighlight(code, definition.key, { highlightLines: highlightLines })
			.then(function () {
				block.setAttribute('data-mcb-rendered', 'true');
			})
			.catch(function (error) {
				code.textContent = code.textContent || '';
				block.setAttribute('data-mcb-rendered', 'error');
				setError(block, 'Code highlighting failed: ' + error.message);
			});
	}

	function renderLegacyPre(pre) {
		if (!pre || pre.getAttribute('data-mcb-rendered') === 'true') {
			return Promise.resolve();
		}
		var code = pre.querySelector('code');
		if (!code) {
			return Promise.resolve();
		}
		var match = String(code.className || '').match(/language-([a-zA-Z0-9_-]+)/);
		var language = match ? match[1] : 'auto';
		pre.classList.add('ccb-code-pre-standalone');
		pre.setAttribute('data-mcb-rendered', 'pending');
		return applyHighlight(code, language, { highlightLines: '' }).then(function () {
			pre.setAttribute('data-mcb-rendered', 'true');
		});
	}

	function renderAll(root) {
		var scope = root || document;
		var blocks = Array.prototype.slice.call(scope.querySelectorAll('.mcb-code-block[data-mcb-code-block="1"], .ccb-code-block[data-ccb-code-block="1"]'));
		var legacy = Array.prototype.slice.call(scope.querySelectorAll('pre.mib-code-block'));
		var chain = Promise.resolve();

		blocks.forEach(function (block) {
			chain = chain.then(function () { return renderBlock(block); });
		});
		legacy.forEach(function (pre) {
			chain = chain.then(function () { return renderLegacyPre(pre); });
		});
		return chain;
	}

	function bindCopyButtons() {
		if (copyButtonsBound) {
			return;
		}
		copyButtonsBound = true;
		document.addEventListener('click', function (event) {
			var button = event.target.closest ? event.target.closest('[data-ccb-copy="1"]') : null;
			if (!button) {
				return;
			}
			var block = button.closest('.mcb-code-block, .ccb-code-block');
			var code = block ? block.querySelector('code.mcb-code-source, code.ccb-code-source') : null;
			var text = code ? code.textContent : '';
			if (!text) {
				return;
			}
			var original = button.textContent;
			var copied = function () {
				button.textContent = 'Copied';
				setTimeout(function () { button.textContent = original || 'Copy'; }, 1400);
			};
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text).then(copied).catch(function () {});
			}
		});
	}

	function ready(callback) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', callback);
			return;
		}
		callback();
	}

	ready(function () {
		registerCustomLanguages();
		bindCopyButtons();
		renderAll(document);

		if ('MutationObserver' in window) {
			var observer = new MutationObserver(function (mutations) {
				mutations.forEach(function (mutation) {
					mutation.addedNodes.forEach(function (node) {
						if (node.nodeType === 1) {
							renderAll(node);
						}
					});
				});
			});
			observer.observe(document.body, { childList: true, subtree: true });
		}
	});

	window.MCBCodeBlocks = window.MCBCodeBlocks || {};
	window.MCBCodeBlocks.renderAll = renderAll;
	window.MCBCodeBlocks.renderBlock = renderBlock;
	window.MCBCodeBlocks.normalizeLanguage = normalizeLanguage;
	window.MCBCodeBlocks.getDefinition = getDefinition;
})();
