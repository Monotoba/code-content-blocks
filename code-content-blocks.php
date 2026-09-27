<?php
/**
 * Plugin Name: Code Content Blocks
 * Description: Adds a syntax-highlighted Code block with light, dark, and system themes plus a broad language registry from legacy languages to current stacks.
 * Version: 1.0.0
 * Requires at least: 6.3
 * Requires PHP: 7.4
 * Author: Randall Morgan / ChatGPT
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: code-content-blocks
 *
 * @package CodeContentBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CCB_VERSION', '1.0.0' );
define( 'CCB_HIGHLIGHTJS_VERSION', '11.11.1' );
define( 'CCB_HIGHLIGHTJS_CDN_BASE', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/' . CCB_HIGHLIGHTJS_VERSION . '/' );
define( 'CCB_PLUGIN_FILE', __FILE__ );
define( 'CCB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once CCB_PLUGIN_DIR . 'includes/class-code-languages.php';

/**
 * Normalizes the code theme.
 *
 * @param mixed $theme Raw theme.
 * @return string
 */
function ccb_normalize_code_theme( $theme ) {
	$theme   = is_string( $theme ) ? sanitize_key( $theme ) : 'system';
	$allowed = array( 'system', 'light', 'dark' );
	return in_array( $theme, $allowed, true ) ? $theme : 'system';
}

/**
 * Normalizes a string boolean attribute.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string              $key Attribute name.
 * @param bool                $default Default value.
 * @return bool
 */
function ccb_bool_attr( $attributes, $key, $default = false ) {
	if ( ! array_key_exists( $key, $attributes ) ) {
		return $default;
	}
	return (bool) $attributes[ $key ];
}

/**
 * Sanitizes highlight line expressions such as "1,3-5".
 *
 * @param mixed $lines Raw line expression.
 * @return string
 */
function ccb_sanitize_highlight_lines( $lines ) {
	if ( ! is_string( $lines ) ) {
		return '';
	}
	$lines = preg_replace( '/[^0-9,\-\s]/', '', $lines );
	$lines = preg_replace( '/\s+/', '', $lines );
	return trim( (string) $lines, ',' );
}

/**
 * Registers scripts, styles, and the block type.
 *
 * @return void
 */
function ccb_register_block() {
	$registry_json = wp_json_encode( CCB_Code_Languages::public_registry() );
	$config_json   = wp_json_encode(
		array(
			'highlightVersion' => CCB_HIGHLIGHTJS_VERSION,
			'cdnBase'          => CCB_HIGHLIGHTJS_CDN_BASE,
		)
	);

	wp_register_script(
		'ccb-highlightjs-lib',
		CCB_HIGHLIGHTJS_CDN_BASE . 'highlight.min.js',
		array(),
		CCB_HIGHLIGHTJS_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_register_script(
		'ccb-code-renderer',
		CCB_PLUGIN_URL . 'assets/code-renderer.js',
		array( 'ccb-highlightjs-lib' ),
		CCB_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_add_inline_script(
		'ccb-code-renderer',
		'window.CCBCodeLanguages = ' . $registry_json . '; window.CCBCodeBlocksConfig = ' . $config_json . ';',
		'before'
	);

	wp_register_script(
		'ccb-code-editor',
		CCB_PLUGIN_URL . 'blocks/code/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-components', 'wp-block-editor', 'ccb-code-renderer' ),
		CCB_VERSION,
		true
	);

	wp_add_inline_script(
		'ccb-code-editor',
		'window.CCBCodeLanguages = ' . $registry_json . '; window.CCBCodeBlocksConfig = ' . $config_json . ';',
		'before'
	);

	wp_register_style(
		'ccb-code-style',
		CCB_PLUGIN_URL . 'blocks/code/style.css',
		array(),
		CCB_VERSION
	);

	wp_register_style(
		'ccb-code-editor-style',
		CCB_PLUGIN_URL . 'blocks/code/editor.css',
		array( 'ccb-code-style' ),
		CCB_VERSION
	);

	register_block_type(
		CCB_PLUGIN_DIR . 'blocks/code',
		array(
			'render_callback' => 'ccb_render_code_block',
		)
	);
}
add_action( 'init', 'ccb_register_block' );

/**
 * Renders the dynamic code block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @return string
 */
function ccb_render_code_block( $attributes ) {
	$source = isset( $attributes['source'] ) && is_string( $attributes['source'] ) ? $attributes['source'] : '';

	if ( '' === trim( $source ) ) {
		return '';
	}

	$language        = CCB_Code_Languages::normalize_language( isset( $attributes['language'] ) ? $attributes['language'] : 'plaintext' );
	$language_label  = CCB_Code_Languages::label_for( $language );
	$theme           = ccb_normalize_code_theme( isset( $attributes['theme'] ) ? $attributes['theme'] : 'system' );
	$caption         = isset( $attributes['caption'] ) && is_string( $attributes['caption'] ) ? trim( wp_strip_all_tags( $attributes['caption'] ) ) : '';
	$show_language   = ccb_bool_attr( $attributes, 'showLanguage', true );
	$show_lines      = ccb_bool_attr( $attributes, 'showLineNumbers', false );
	$show_copy       = ccb_bool_attr( $attributes, 'showCopyButton', true );
	$wrap_lines      = ccb_bool_attr( $attributes, 'wrapLines', false );
	$highlight_lines = ccb_sanitize_highlight_lines( isset( $attributes['highlightLines'] ) ? $attributes['highlightLines'] : '' );

	wp_enqueue_style( 'ccb-code-style' );
	wp_enqueue_script( 'ccb-highlightjs-lib' );
	wp_enqueue_script( 'ccb-code-renderer' );

	$classes = array(
		'mcb-code-block',
		'ccb-code-block',
		'ccb-theme-' . $theme,
		'ccb-language-' . sanitize_html_class( $language ),
	);

	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class'                    => implode( ' ', $classes ),
			'data-mcb-code-block'      => '1',
			'data-ccb-code-block'      => '1',
			'data-mcb-language'        => $language,
			'data-mcb-theme'           => $theme,
			'data-mcb-line-numbers'    => $show_lines ? 'true' : 'false',
			'data-mcb-wrap-lines'      => $wrap_lines ? 'true' : 'false',
			'data-mcb-highlight-lines' => $highlight_lines,
		)
	);

	$output  = '<figure ' . $wrapper_attributes . '>';
	$output .= '<div class="ccb-code-toolbar">';
	if ( $show_language ) {
		$output .= '<span class="ccb-code-language-label">' . esc_html( $language_label ) . '</span>';
	}
	if ( $show_copy ) {
		$output .= '<button type="button" class="ccb-code-copy-button" data-ccb-copy="1">' . esc_html__( 'Copy', 'code-content-blocks' ) . '</button>';
	}
	$output .= '</div>';
	$output .= '<pre class="ccb-code-pre"><code class="mcb-code-source ccb-code-source language-' . esc_attr( $language ) . '">' . esc_html( $source ) . '</code></pre>';
	$output .= '<div class="ccb-code-error" role="alert" hidden></div>';

	if ( '' !== $caption ) {
		$output .= '<figcaption class="ccb-code-caption">' . esc_html( $caption ) . '</figcaption>';
	}

	$output .= '</figure>';

	return $output;
}
