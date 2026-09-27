'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

class FakeClassList {
	constructor(initial) {
		this.values = new Set(initial || []);
	}
	add(...names) {
		names.forEach((name) => this.values.add(name));
	}
	toggle(name, force) {
		if (force) this.values.add(name);
		else this.values.delete(name);
	}
	contains(name) {
		return this.values.has(name);
	}
}

class FakeElement {
	constructor(attributes) {
		this.attributes = Object.assign({}, attributes);
		this.classList = new FakeClassList();
		this.className = '';
		this.hidden = true;
		this.innerHTML = '';
		this.nodeType = 1;
		this.textContent = '';
	}
	getAttribute(name) {
		return Object.prototype.hasOwnProperty.call(this.attributes, name) ? this.attributes[name] : null;
	}
	setAttribute(name, value) {
		this.attributes[name] = String(value);
	}
}

function createBlock(attributes, source) {
	const block = new FakeElement(attributes);
	const code = new FakeElement();
	const error = new FakeElement();
	code.className = 'mcb-code-source';
	code.textContent = source;
	block.querySelector = (selector) => selector === '.ccb-code-error' ? error : code;
	return { block, code, error };
}

async function main() {
	const listeners = {};
	const timers = [];
	const document = {
		readyState: 'loading',
		body: {},
		head: { appendChild() {} },
		addEventListener(name, callback) { listeners[name] = callback; },
		createElement() { return new FakeElement(); },
		querySelectorAll() { return []; }
	};

	let highlightCalls = 0;
	const languages = { plaintext: true, python: true };
	const hljs = {
		getLanguage(name) { return Boolean(languages[name]); },
		registerLanguage(name) { languages[name] = true; },
		highlight(source) {
			highlightCalls += 1;
			return { value: '<span class="hljs-keyword">print</span>\n' + source.split('\n')[1] };
		},
		highlightAuto() { return { value: 'automatic' }; }
	};

	const clipboardWrites = [];
	const window = {
		CCBCodeLanguages: {
			aliases: { py: 'python' },
			languages: {
				plaintext: { key: 'plaintext', label: 'Plain text', hljs: 'plaintext' },
				python: { key: 'python', label: 'Python', hljs: 'python' }
			}
		},
		CCBCodeBlocksConfig: {},
		hljs
	};
	const context = {
		console,
		document,
		navigator: { clipboard: { writeText(text) { clipboardWrites.push(text); return Promise.resolve(); } } },
		Promise,
		setTimeout(callback) { timers.push(callback); },
		window
	};

	vm.runInNewContext(fs.readFileSync('assets/code-renderer.js', 'utf8'), context, { filename: 'assets/code-renderer.js' });
	const api = window.MCBCodeBlocks;

	assert.equal(api.normalizeLanguage(' PY '), 'python');
	assert.equal(api.normalizeLanguage('unknown<script>'), 'plaintext');
	assert.equal(api.getDefinition('py').label, 'Python');

	const rendered = createBlock({
		'data-mcb-language': 'py',
		'data-mcb-highlight-lines': '2',
		'data-mcb-line-numbers': 'true',
		'data-mcb-wrap-lines': 'true'
	}, 'print("safe")\nvalue');

	await api.renderBlock(rendered.block);
	assert.equal(rendered.block.getAttribute('data-mcb-rendered'), 'true');
	assert.equal(rendered.block.getAttribute('data-mcb-normalized-language'), 'python');
	assert.ok(rendered.block.classList.contains('ccb-has-line-numbers'));
	assert.ok(rendered.block.classList.contains('ccb-wrap-lines'));
	assert.match(rendered.code.innerHTML, /data-line="1"/);
	assert.match(rendered.code.innerHTML, /data-line="2"/);
	assert.match(rendered.code.innerHTML, /ccb-code-line-highlighted/);
	assert.ok(rendered.code.classList.contains('hljs'));
	assert.ok(rendered.code.classList.contains('language-python'));
	assert.equal(highlightCalls, 1);

	await api.renderBlock(rendered.block);
	assert.equal(highlightCalls, 1, 'already-rendered blocks must not be highlighted twice');

	window.hljs = undefined;
	const fallback = createBlock({ 'data-mcb-language': 'python' }, '<b>still readable</b>');
	await api.renderBlock(fallback.block);
	assert.equal(fallback.block.getAttribute('data-mcb-rendered'), 'error');
	assert.equal(fallback.code.textContent, '<b>still readable</b>');
	assert.equal(fallback.error.hidden, false);
	assert.match(fallback.error.textContent, /Highlight\.js is not available/);

	window.hljs = hljs;
	listeners.DOMContentLoaded();
	const copyBlock = createBlock({}, 'copy this exactly');
	const button = new FakeElement({ 'data-ccb-copy': '1' });
	button.textContent = 'Copy';
	button.closest = (selector) => selector === '[data-ccb-copy="1"]' ? button : copyBlock.block;
	listeners.click({ target: button });
	await Promise.resolve();
	assert.deepEqual(clipboardWrites, ['copy this exactly']);
	assert.equal(button.textContent, 'Copied');
	timers.shift()();
	assert.equal(button.textContent, 'Copy');

	context.navigator.clipboard.writeText = () => Promise.reject(new Error('denied'));
	button.textContent = 'Copy';
	listeners.click({ target: button });
	await Promise.resolve();
	await Promise.resolve();
	assert.equal(button.textContent, 'Copy', 'failed clipboard writes must not claim success');

	console.log('JavaScript renderer assertions: 27');
}

main().catch((error) => {
	console.error(error);
	process.exitCode = 1;
});
