<?php
/**
 * Smoke tests for Code Content Blocks.
 */

$root = dirname( __DIR__ );
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', $root . '/' );
}

$php_files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root ) );
foreach ( $php_files as $file_info ) {
	if ( ! $file_info->isFile() || 'php' !== $file_info->getExtension() ) {
		continue;
	}
	$file = $file_info->getPathname();
	$output = array();
	$status = 0;
	exec( 'php -l ' . escapeshellarg( $file ) . ' 2>&1', $output, $status );
	if ( 0 !== $status ) {
		echo implode( "\n", $output ) . "\n";
		exit( 1 );
	}
	echo 'PHP OK: ' . substr( $file, strlen( $root ) + 1 ) . "\n";
}

$json_files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root ) );
foreach ( $json_files as $file_info ) {
	if ( ! $file_info->isFile() || 'json' !== $file_info->getExtension() ) {
		continue;
	}
	$file = $file_info->getPathname();
	$json = json_decode( file_get_contents( $file ), true );
	if ( JSON_ERROR_NONE !== json_last_error() ) {
		echo 'JSON error in ' . $file . ': ' . json_last_error_msg() . "\n";
		exit( 1 );
	}
	echo 'JSON OK: ' . substr( $file, strlen( $root ) + 1 ) . "\n";
}

require_once $root . '/includes/class-code-languages.php';
$count = count( CCB_Code_Languages::language_definitions() );
if ( $count < 60 ) {
	echo 'Expected at least 60 language entries, got ' . $count . "\n";
	exit( 1 );
}

$aliases = CCB_Code_Languages::alias_map();
$checks  = array(
	'quick-basic' => 'quickbasic',
	'qbasic'      => 'quickbasic',
	'c++'         => 'cpp',
	'csharp'      => 'csharp',
	'pl-i'        => 'pl1',
	'coco-basic'  => 'color-basic',
	'flutter'     => 'flutter',
);
foreach ( $checks as $raw => $expected ) {
	$normalized = CCB_Code_Languages::normalize_language( $raw );
	if ( $expected !== $normalized ) {
		echo 'Alias failed: ' . $raw . ' => ' . $normalized . ', expected ' . $expected . "\n";
		exit( 1 );
	}
}

echo 'Language registry entries: ' . $count . "\n";
echo "Smoke tests completed.\n";
