(function (wp) {
	'use strict';

	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var __ = wp.i18n.__;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var TextareaControl = wp.components.TextareaControl;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var ToggleControl = wp.components.ToggleControl;
	var PanelBody = wp.components.PanelBody;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useEffect = wp.element.useEffect;
	var useRef = wp.element.useRef;

	var registry = window.CCBCodeLanguages || { languages: {} };

	function languageOptions() {
		var languages = registry.languages || {};
		return Object.keys(languages).map(function (key) {
			return {
				label: (languages[key].label || key) + (languages[key].category ? ' — ' + languages[key].category : ''),
				value: key
			};
		});
	}

	function renderPreview(ref) {
		if (!ref.current || !window.MCBCodeBlocks) {
			return;
		}
		ref.current.removeAttribute('data-mcb-rendered');
		var code = ref.current.querySelector('code');
		if (code && code.getAttribute('data-original-source') !== null) {
			code.textContent = code.getAttribute('data-original-source');
		}
		window.MCBCodeBlocks.renderBlock(ref.current);
	}

	registerBlockType('mcb/code', {
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var source = attributes.source || '';
			var language = attributes.language || 'plaintext';
			var theme = attributes.theme || 'system';
			var caption = attributes.caption || '';
			var showLanguage = attributes.showLanguage !== false;
			var showLineNumbers = attributes.showLineNumbers === true;
			var showCopyButton = attributes.showCopyButton !== false;
			var wrapLines = attributes.wrapLines === true;
			var highlightLines = attributes.highlightLines || '';
			var previewRef = useRef(null);
			var blockProps = useBlockProps({ className: 'ccb-code-editor-shell' });

			useEffect(function () {
				renderPreview(previewRef);
			}, [source, language, theme, showLineNumbers, wrapLines, highlightLines]);

			return el(
				'div',
				blockProps,
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Code settings', 'code-content-blocks'), initialOpen: true },
						el(SelectControl, {
							label: __('Language', 'code-content-blocks'),
							value: language,
							options: languageOptions(),
							onChange: function (value) { setAttributes({ language: value }); }
						}),
						el(SelectControl, {
							label: __('Theme', 'code-content-blocks'),
							value: theme,
							options: [
								{ label: __('System', 'code-content-blocks'), value: 'system' },
								{ label: __('Light', 'code-content-blocks'), value: 'light' },
								{ label: __('Dark', 'code-content-blocks'), value: 'dark' }
							],
							onChange: function (value) { setAttributes({ theme: value }); }
						}),
						el(ToggleControl, {
							label: __('Show language label', 'code-content-blocks'),
							checked: showLanguage,
							onChange: function (value) { setAttributes({ showLanguage: value }); }
						}),
						el(ToggleControl, {
							label: __('Show line numbers', 'code-content-blocks'),
							checked: showLineNumbers,
							onChange: function (value) { setAttributes({ showLineNumbers: value }); }
						}),
						el(ToggleControl, {
							label: __('Show copy button', 'code-content-blocks'),
							checked: showCopyButton,
							onChange: function (value) { setAttributes({ showCopyButton: value }); }
						}),
						el(ToggleControl, {
							label: __('Wrap long lines', 'code-content-blocks'),
							checked: wrapLines,
							onChange: function (value) { setAttributes({ wrapLines: value }); }
						}),
						el(TextControl, {
							label: __('Highlighted lines', 'code-content-blocks'),
							help: __('Use comma/range notation, for example: 1,3-5', 'code-content-blocks'),
							value: highlightLines,
							onChange: function (value) { setAttributes({ highlightLines: value }); }
						})
						)
					),
				el(SelectControl, {
					label: __('Language', 'code-content-blocks'),
					value: language,
					options: languageOptions(),
					onChange: function (value) { setAttributes({ language: value }); }
				}),
				el(TextareaControl, {
					label: __('Code', 'code-content-blocks'),
					value: source,
					rows: 12,
					onChange: function (value) { setAttributes({ source: value }); },
					placeholder: __('Paste or type code here…', 'code-content-blocks')
				}),
				el(TextControl, {
					label: __('Caption', 'code-content-blocks'),
					value: caption,
					onChange: function (value) { setAttributes({ caption: value }); }
				}),
				el('p', { className: 'ccb-code-editor-preview-label' }, __('Preview', 'code-content-blocks')),
				el(
					'figure',
					{
						ref: previewRef,
						className: 'mcb-code-block ccb-code-block ccb-theme-' + theme,
						'data-mcb-code-block': '1',
						'data-ccb-code-block': '1',
						'data-mcb-language': language,
						'data-mcb-theme': theme,
						'data-mcb-line-numbers': showLineNumbers ? 'true' : 'false',
						'data-mcb-wrap-lines': wrapLines ? 'true' : 'false',
						'data-mcb-highlight-lines': highlightLines
					},
					el('div', { className: 'ccb-code-toolbar' },
						showLanguage ? el('span', { className: 'ccb-code-language-label' }, language) : null,
						showCopyButton ? el('button', { type: 'button', className: 'ccb-code-copy-button', disabled: true }, __('Copy', 'code-content-blocks')) : null
					),
					el('pre', { className: 'ccb-code-pre' },
						el('code', { className: 'mcb-code-source ccb-code-source language-' + language, 'data-original-source': source }, source || __('No code yet.', 'code-content-blocks'))
					),
					el('div', { className: 'ccb-code-error', role: 'alert', hidden: true }),
					caption ? el('figcaption', { className: 'ccb-code-caption' }, caption) : null
				)
			);
		},
		save: function () {
			return null;
		}
	});
})(window.wp);
