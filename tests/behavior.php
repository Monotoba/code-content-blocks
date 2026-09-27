<?php
/**
 * Behavioral tests for the plugin's PHP helpers and dynamic block renderer.
 */

$root = dirname( __DIR__ );

define( 'ABSPATH', $root . '/' );

$GLOBALS['ccb_test_enqueued_scripts'] = array();
$GLOBALS['ccb_test_enqueued_styles']  = array();

function plugin_dir_path( $file ) {
	return trailingslashit( dirname( $file ) );
}

function plugin_dir_url( $file ) {
	return 'https://example.test/wp-content/plugins/' . basename( dirname( $file ) ) . '/';
}

function trailingslashit( $value ) {
	return rtrim( $value, '/\\' ) . '/';
}

function add_action( $hook, $callback ) {}

function sanitize_key( $key ) {
	return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $key ) );
}

function sanitize_html_class( $class ) {
	return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $class );
}

function wp_strip_all_tags( $text ) {
	return strip_tags( (string) $text );
}

function wp_enqueue_style( $handle ) {
	$GLOBALS['ccb_test_enqueued_styles'][] = $handle;
}

function wp_enqueue_script( $handle ) {
	$GLOBALS['ccb_test_enqueued_scripts'][] = $handle;
}

function esc_html( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
}

function esc_attr( $text ) {
	return esc_html( $text );
}

function esc_html__( $text, $domain ) {
	return esc_html( $text );
}

function get_block_wrapper_attributes( $attributes ) {
	$pairs = array();
	foreach ( $attributes as $name => $value ) {
		$pairs[] = $name . '="' . esc_attr( $value ) . '"';
	}
	return implode( ' ', $pairs );
}

require_once $root . '/code-content-blocks.php';

$assertions = 0;

function ccb_test_assert_same( $expected, $actual, $message ) {
	global $assertions;
	++$assertions;
	if ( $expected !== $actual ) {
		fwrite( STDERR, $message . "\nExpected: " . var_export( $expected, true ) . "\nActual: " . var_export( $actual, true ) . "\n" );
		exit( 1 );
	}
}

function ccb_test_assert_contains( $needle, $haystack, $message ) {
	global $assertions;
	++$assertions;
	if ( false === strpos( $haystack, $needle ) ) {
		fwrite( STDERR, $message . "\nMissing: " . $needle . "\n" );
		exit( 1 );
	}
}

function ccb_test_assert_not_contains( $needle, $haystack, $message ) {
	global $assertions;
	++$assertions;
	if ( false !== strpos( $haystack, $needle ) ) {
		fwrite( STDERR, $message . "\nUnexpected: " . $needle . "\n" );
		exit( 1 );
	}
}

ccb_test_assert_same( 'dark', ccb_normalize_code_theme( 'DARK' ), 'Theme names should be normalized.' );
ccb_test_assert_same( 'system', ccb_normalize_code_theme( 'sepia' ), 'Unknown themes should use the system theme.' );
ccb_test_assert_same( 'system', ccb_normalize_code_theme( array() ), 'Non-string themes should use the system theme.' );

ccb_test_assert_same( true, ccb_bool_attr( array(), 'enabled', true ), 'Missing boolean attributes should use their default.' );
ccb_test_assert_same( false, ccb_bool_attr( array( 'enabled' => false ), 'enabled', true ), 'Explicit false should override the default.' );

ccb_test_assert_same( '1,3-5', ccb_sanitize_highlight_lines( ' 1, 3-5 ' ), 'Whitespace should be removed from line ranges.' );
ccb_test_assert_same( '1,3-5', ccb_sanitize_highlight_lines( '1,<script>3-5</script>' ), 'Non-range characters should be removed.' );
ccb_test_assert_same( '', ccb_sanitize_highlight_lines( array( 1, 2 ) ), 'Non-string line ranges should be rejected.' );

ccb_test_assert_same( '', ccb_render_code_block( array( 'source' => " \n\t" ) ), 'Whitespace-only source should render nothing.' );
ccb_test_assert_same( array(), $GLOBALS['ccb_test_enqueued_scripts'], 'Empty blocks should not enqueue scripts.' );
ccb_test_assert_same( array(), $GLOBALS['ccb_test_enqueued_styles'], 'Empty blocks should not enqueue styles.' );

$html = ccb_render_code_block(
	array(
		'source'             => '<script>alert("x")</script>&',
		'language'           => 'c++',
		'theme'              => 'not-a-theme',
		'caption'            => '<b>Safe title</b><script>bad()</script>',
		'showLanguage'       => true,
		'showLineNumbers'    => true,
		'showCopyButton'     => false,
		'wrapLines'          => true,
		'highlightLines'     => '1, 3-5"><script>',
	)
);

ccb_test_assert_contains( 'ccb-theme-system', $html, 'Invalid themes should render with the system class.' );
ccb_test_assert_contains( 'ccb-language-cpp', $html, 'Language aliases should render with their canonical class.' );
ccb_test_assert_contains( 'data-mcb-language="cpp"', $html, 'The canonical language should be exposed to the renderer.' );
ccb_test_assert_contains( 'data-mcb-line-numbers="true"', $html, 'Line-number state should be rendered.' );
ccb_test_assert_contains( 'data-mcb-wrap-lines="true"', $html, 'Line-wrapping state should be rendered.' );
ccb_test_assert_contains( 'data-mcb-highlight-lines="1,3-5"', $html, 'Highlight ranges should be sanitized before rendering.' );
ccb_test_assert_contains( '>C++</span>', $html, 'The canonical language label should be rendered.' );
ccb_test_assert_contains( '&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;&amp;', $html, 'Source code should be HTML-escaped.' );
ccb_test_assert_contains( '<figcaption class="ccb-code-caption">Safe titlebad()</figcaption>', $html, 'Caption markup should be stripped before rendering.' );
ccb_test_assert_not_contains( '<script>', $html, 'User-provided script elements must never be rendered.' );
ccb_test_assert_not_contains( 'ccb-code-copy-button', $html, 'The copy button should honor an explicit false setting.' );
ccb_test_assert_same( array( 'ccb-highlightjs-lib', 'ccb-code-renderer' ), $GLOBALS['ccb_test_enqueued_scripts'], 'A rendered block should enqueue both frontend scripts.' );
ccb_test_assert_same( array( 'ccb-code-style' ), $GLOBALS['ccb_test_enqueued_styles'], 'A rendered block should enqueue its stylesheet.' );

echo 'PHP behavioral assertions: ' . $assertions . "\n";
