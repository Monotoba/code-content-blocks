<?php
/**
 * Language registry for Code Content Blocks.
 *
 * @package CodeContentBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central language registry.
 *
 * The registry intentionally separates the public language key used by the
 * block from the Highlight.js component name. To add another language, add one
 * entry to language_definitions() and, if Highlight.js does not provide a
 * grammar, add a compact custom grammar in assets/code-renderer.js.
 */
final class CCB_Code_Languages {
	/**
	 * Returns the language definitions.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function language_definitions() {
		return array(
			array( 'key' => 'plaintext', 'label' => 'Plain text', 'category' => 'General', 'aliases' => array( 'text', 'txt', 'none' ), 'hljs' => 'plaintext' ),
			array( 'key' => 'auto', 'label' => 'Auto detect', 'category' => 'General', 'aliases' => array(), 'hljs' => '' ),

			array( 'key' => 'asm', 'label' => 'Assembly - generic', 'category' => 'Assembly / Machine', 'aliases' => array( 'assembly' ), 'custom' => true ),
			array( 'key' => 'x86asm', 'label' => 'Assembly - x86', 'category' => 'Assembly / Machine', 'aliases' => array( 'x86', 'i8086', '8086', 'i386', '80386', 'intelasm' ), 'hljs' => 'x86asm' ),
			array( 'key' => '6502asm', 'label' => 'Assembly - 6502', 'category' => 'Assembly / Machine', 'aliases' => array( '6502', 'mos6502', 'm6502' ), 'hljs' => '6502asm' ),
			array( 'key' => 'z80asm', 'label' => 'Assembly - Z80', 'category' => 'Assembly / Machine', 'aliases' => array( 'z80' ), 'custom' => true ),
			array( 'key' => 'm68kasm', 'label' => 'Assembly - Motorola 68000', 'category' => 'Assembly / Machine', 'aliases' => array( '68k', '68000', 'm68k' ), 'custom' => true ),
			array( 'key' => 'armasm', 'label' => 'Assembly - ARM', 'category' => 'Assembly / Machine', 'aliases' => array( 'arm' ), 'hljs' => 'armasm' ),
			array( 'key' => 'avrasm', 'label' => 'Assembly - AVR', 'category' => 'Assembly / Machine', 'aliases' => array( 'avr' ), 'hljs' => 'avrasm' ),
			array( 'key' => 'mipsasm', 'label' => 'Assembly - MIPS', 'category' => 'Assembly / Machine', 'aliases' => array( 'mips' ), 'hljs' => 'mipsasm' ),
			array( 'key' => 'llvm', 'label' => 'LLVM IR', 'category' => 'Assembly / Machine', 'aliases' => array( 'llvmir' ), 'hljs' => 'llvm' ),

			array( 'key' => 'fortran', 'label' => 'Fortran', 'category' => 'Historic / Scientific', 'aliases' => array( 'f77', 'f90', 'f95', 'f03', 'f08' ), 'hljs' => 'fortran' ),
			array( 'key' => 'algol', 'label' => 'ALGOL', 'category' => 'Historic / Scientific', 'aliases' => array( 'algol60', 'algol68' ), 'custom' => true ),
			array( 'key' => 'b', 'label' => 'B', 'category' => 'Historic / Scientific', 'aliases' => array( 'ken-b' ), 'custom' => true ),
			array( 'key' => 'bcpl', 'label' => 'BCPL', 'category' => 'Historic / Scientific', 'aliases' => array(), 'custom' => true ),
			array( 'key' => 'pl1', 'label' => 'PL/I', 'category' => 'Historic / Scientific', 'aliases' => array( 'pli', 'pl-i', 'pl/1' ), 'custom' => true ),
			array( 'key' => 'cobol', 'label' => 'COBOL', 'category' => 'Historic / Scientific', 'aliases' => array(), 'hljs' => 'cobol' ),
			array( 'key' => 'ada', 'label' => 'Ada', 'category' => 'Historic / Scientific', 'aliases' => array( 'ada83', 'ada95', 'ada2012' ), 'hljs' => 'ada' ),
			array( 'key' => 'lisp', 'label' => 'Lisp', 'category' => 'Historic / Scientific', 'aliases' => array( 'common-lisp', 'cl' ), 'hljs' => 'lisp' ),
			array( 'key' => 'scheme', 'label' => 'Scheme', 'category' => 'Historic / Scientific', 'aliases' => array(), 'hljs' => 'scheme' ),
			array( 'key' => 'prolog', 'label' => 'Prolog', 'category' => 'Historic / Scientific', 'aliases' => array(), 'hljs' => 'prolog' ),
			array( 'key' => 'smalltalk', 'label' => 'Smalltalk', 'category' => 'Historic / Scientific', 'aliases' => array(), 'hljs' => 'smalltalk' ),
			array( 'key' => 'forth', 'label' => 'Forth', 'category' => 'Historic / Scientific', 'aliases' => array(), 'custom' => true ),

			array( 'key' => 'basic', 'label' => 'BASIC - generic', 'category' => 'BASIC dialects', 'aliases' => array( 'gwbasic', 'basica', 'mbasic' ), 'custom' => true ),
			array( 'key' => 'quickbasic', 'label' => 'QuickBASIC / QBasic', 'category' => 'BASIC dialects', 'aliases' => array( 'qbasic', 'qb', 'quick-basic' ), 'custom' => true ),
			array( 'key' => 'color-basic', 'label' => 'TRS-80 Color BASIC', 'category' => 'BASIC dialects', 'aliases' => array( 'colorbasic', 'coco-basic', 'coco', 'extended-color-basic', 'ecb' ), 'custom' => true ),
			array( 'key' => 'commodore-basic', 'label' => 'Commodore BASIC', 'category' => 'BASIC dialects', 'aliases' => array( 'cbm-basic', 'c64-basic', 'vic20-basic', 'pet-basic' ), 'custom' => true ),
			array( 'key' => 'comal', 'label' => 'COMAL', 'category' => 'BASIC dialects', 'aliases' => array(), 'custom' => true ),
			array( 'key' => 'vbnet', 'label' => 'Visual Basic / VB.NET', 'category' => 'BASIC dialects', 'aliases' => array( 'vb', 'visualbasic', 'visual-basic', 'vba' ), 'hljs' => 'vbnet' ),

			array( 'key' => 'c', 'label' => 'C', 'category' => 'C family', 'aliases' => array( 'ansi-c', 'c89', 'c99', 'c11', 'c17' ), 'hljs' => 'c' ),
			array( 'key' => 'cpp', 'label' => 'C++', 'category' => 'C family', 'aliases' => array( 'c++', 'cplusplus', 'cc', 'cxx', 'cpp17', 'cpp20', 'cpp23' ), 'hljs' => 'cpp' ),
			array( 'key' => 'objectivec', 'label' => 'Objective-C', 'category' => 'C family', 'aliases' => array( 'objc', 'obj-c' ), 'hljs' => 'objectivec' ),
			array( 'key' => 'csharp', 'label' => 'C#', 'category' => 'C family', 'aliases' => array( 'cs', 'c#', 'dotnet-csharp' ), 'hljs' => 'csharp' ),
			array( 'key' => 'java', 'label' => 'Java', 'category' => 'C family', 'aliases' => array(), 'hljs' => 'java' ),
			array( 'key' => 'kotlin', 'label' => 'Kotlin', 'category' => 'C family', 'aliases' => array( 'kt', 'kts' ), 'hljs' => 'kotlin' ),
			array( 'key' => 'scala', 'label' => 'Scala', 'category' => 'C family', 'aliases' => array(), 'hljs' => 'scala' ),
			array( 'key' => 'swift', 'label' => 'Swift', 'category' => 'C family', 'aliases' => array(), 'hljs' => 'swift' ),
			array( 'key' => 'zig', 'label' => 'Zig', 'category' => 'C family', 'aliases' => array(), 'hljs' => 'zig', 'custom' => true ),
			array( 'key' => 'rust', 'label' => 'Rust', 'category' => 'C family', 'aliases' => array( 'rs' ), 'hljs' => 'rust' ),
			array( 'key' => 'go', 'label' => 'Go', 'category' => 'C family', 'aliases' => array( 'golang' ), 'hljs' => 'go' ),
			array( 'key' => 'arduino', 'label' => 'Arduino', 'category' => 'C family', 'aliases' => array( 'ino' ), 'hljs' => 'arduino', 'fallback' => 'cpp' ),

			array( 'key' => 'pascal', 'label' => 'Pascal', 'category' => 'Pascal / Wirth family', 'aliases' => array( 'turbo-pascal', 'tp' ), 'hljs' => 'delphi' ),
			array( 'key' => 'delphi', 'label' => 'Delphi / Object Pascal', 'category' => 'Pascal / Wirth family', 'aliases' => array( 'objectpascal', 'object-pascal' ), 'hljs' => 'delphi' ),
			array( 'key' => 'oberon', 'label' => 'Oberon', 'category' => 'Pascal / Wirth family', 'aliases' => array( 'oberon-2' ), 'custom' => true ),
			array( 'key' => 'modula2', 'label' => 'Modula-2', 'category' => 'Pascal / Wirth family', 'aliases' => array( 'modula-2' ), 'custom' => true ),

			array( 'key' => 'python', 'label' => 'Python', 'category' => 'Scripting / dynamic', 'aliases' => array( 'py', 'python3' ), 'hljs' => 'python' ),
			array( 'key' => 'javascript', 'label' => 'JavaScript', 'category' => 'Scripting / dynamic', 'aliases' => array( 'js', 'node', 'nodejs', 'ecmascript' ), 'hljs' => 'javascript' ),
			array( 'key' => 'typescript', 'label' => 'TypeScript', 'category' => 'Scripting / dynamic', 'aliases' => array( 'ts' ), 'hljs' => 'typescript' ),
			array( 'key' => 'php', 'label' => 'PHP', 'category' => 'Scripting / dynamic', 'aliases' => array(), 'hljs' => 'php' ),
			array( 'key' => 'perl', 'label' => 'Perl', 'category' => 'Scripting / dynamic', 'aliases' => array( 'pl' ), 'hljs' => 'perl' ),
			array( 'key' => 'ruby', 'label' => 'Ruby', 'category' => 'Scripting / dynamic', 'aliases' => array( 'rb' ), 'hljs' => 'ruby' ),
			array( 'key' => 'lua', 'label' => 'Lua', 'category' => 'Scripting / dynamic', 'aliases' => array(), 'hljs' => 'lua' ),
			array( 'key' => 'r', 'label' => 'R', 'category' => 'Scripting / dynamic', 'aliases' => array( 'rstats' ), 'hljs' => 'r' ),
			array( 'key' => 'julia', 'label' => 'Julia', 'category' => 'Scripting / dynamic', 'aliases' => array( 'jl' ), 'hljs' => 'julia' ),
			array( 'key' => 'matlab', 'label' => 'MATLAB / Octave', 'category' => 'Scripting / dynamic', 'aliases' => array( 'octave', 'm' ), 'hljs' => 'matlab' ),
			array( 'key' => 'groovy', 'label' => 'Groovy', 'category' => 'Scripting / dynamic', 'aliases' => array(), 'hljs' => 'groovy' ),

			array( 'key' => 'haskell', 'label' => 'Haskell', 'category' => 'Functional / ML family', 'aliases' => array( 'hs' ), 'hljs' => 'haskell' ),
			array( 'key' => 'elm', 'label' => 'Elm', 'category' => 'Functional / ML family', 'aliases' => array(), 'hljs' => 'elm' ),
			array( 'key' => 'ocaml', 'label' => 'OCaml', 'category' => 'Functional / ML family', 'aliases' => array( 'ml' ), 'hljs' => 'ocaml' ),
			array( 'key' => 'erlang', 'label' => 'Erlang', 'category' => 'Functional / ML family', 'aliases' => array( 'erl' ), 'hljs' => 'erlang' ),
			array( 'key' => 'elixir', 'label' => 'Elixir', 'category' => 'Functional / ML family', 'aliases' => array( 'ex', 'exs' ), 'hljs' => 'elixir' ),
			array( 'key' => 'clojure', 'label' => 'Clojure', 'category' => 'Functional / ML family', 'aliases' => array( 'clj', 'cljs' ), 'hljs' => 'clojure' ),

			array( 'key' => 'dart', 'label' => 'Dart', 'category' => 'Web / app', 'aliases' => array(), 'hljs' => 'dart' ),
			array( 'key' => 'flutter', 'label' => 'Flutter / Dart', 'category' => 'Web / app', 'aliases' => array(), 'hljs' => 'dart', 'fallback' => 'dart' ),
			array( 'key' => 'html', 'label' => 'HTML', 'category' => 'Web / app', 'aliases' => array( 'xhtml' ), 'hljs' => 'xml' ),
			array( 'key' => 'xml', 'label' => 'XML', 'category' => 'Web / app', 'aliases' => array( 'xsl', 'svg' ), 'hljs' => 'xml' ),
			array( 'key' => 'css', 'label' => 'CSS', 'category' => 'Web / app', 'aliases' => array(), 'hljs' => 'css' ),
			array( 'key' => 'scss', 'label' => 'SCSS', 'category' => 'Web / app', 'aliases' => array( 'sass' ), 'hljs' => 'scss' ),
			array( 'key' => 'less', 'label' => 'Less', 'category' => 'Web / app', 'aliases' => array(), 'hljs' => 'less' ),
			array( 'key' => 'jsx', 'label' => 'JSX', 'category' => 'Web / app', 'aliases' => array( 'react' ), 'hljs' => 'javascript' ),
			array( 'key' => 'tsx', 'label' => 'TSX', 'category' => 'Web / app', 'aliases' => array( 'react-ts' ), 'hljs' => 'typescript' ),
			array( 'key' => 'vue', 'label' => 'Vue', 'category' => 'Web / app', 'aliases' => array(), 'hljs' => 'xml' ),
			array( 'key' => 'svelte', 'label' => 'Svelte', 'category' => 'Web / app', 'aliases' => array(), 'hljs' => 'xml' ),
			array( 'key' => 'coffeescript', 'label' => 'CoffeeScript', 'category' => 'Web / app', 'aliases' => array( 'coffee' ), 'hljs' => 'coffeescript' ),

			array( 'key' => 'bash', 'label' => 'Bash / POSIX shell', 'category' => 'Shell / command', 'aliases' => array( 'sh', 'shell', 'zsh', 'ksh' ), 'hljs' => 'bash' ),
			array( 'key' => 'powershell', 'label' => 'PowerShell', 'category' => 'Shell / command', 'aliases' => array( 'pwsh', 'ps1' ), 'hljs' => 'powershell' ),
			array( 'key' => 'dos', 'label' => 'DOS / Windows batch', 'category' => 'Shell / command', 'aliases' => array( 'bat', 'batch', 'cmd', 'msdos' ), 'hljs' => 'dos', 'custom' => true ),
			array( 'key' => 'csh', 'label' => 'C shell', 'category' => 'Shell / command', 'aliases' => array( 'tcsh' ), 'custom' => true, 'fallback' => 'bash' ),
			array( 'key' => 'makefile', 'label' => 'Makefile', 'category' => 'Shell / command', 'aliases' => array( 'make' ), 'hljs' => 'makefile' ),
			array( 'key' => 'cmake', 'label' => 'CMake', 'category' => 'Shell / command', 'aliases' => array(), 'hljs' => 'cmake' ),
			array( 'key' => 'dockerfile', 'label' => 'Dockerfile', 'category' => 'Shell / command', 'aliases' => array( 'docker' ), 'hljs' => 'dockerfile' ),

			array( 'key' => 'sql', 'label' => 'SQL', 'category' => 'Data / config', 'aliases' => array( 'mysql', 'postgresql', 'postgres', 'sqlite' ), 'hljs' => 'sql' ),
			array( 'key' => 'json', 'label' => 'JSON', 'category' => 'Data / config', 'aliases' => array( 'jsonc' ), 'hljs' => 'json' ),
			array( 'key' => 'yaml', 'label' => 'YAML', 'category' => 'Data / config', 'aliases' => array( 'yml' ), 'hljs' => 'yaml' ),
			array( 'key' => 'toml', 'label' => 'TOML', 'category' => 'Data / config', 'aliases' => array(), 'hljs' => 'ini' ),
			array( 'key' => 'ini', 'label' => 'INI / config', 'category' => 'Data / config', 'aliases' => array( 'cfg', 'conf' ), 'hljs' => 'ini' ),
			array( 'key' => 'properties', 'label' => 'Java properties', 'category' => 'Data / config', 'aliases' => array(), 'hljs' => 'properties' ),
			array( 'key' => 'csv', 'label' => 'CSV', 'category' => 'Data / config', 'aliases' => array(), 'custom' => true ),
			array( 'key' => 'markdown', 'label' => 'Markdown', 'category' => 'Data / config', 'aliases' => array( 'md' ), 'hljs' => 'markdown' ),
			array( 'key' => 'diff', 'label' => 'Diff / patch', 'category' => 'Data / config', 'aliases' => array( 'patch' ), 'hljs' => 'diff' ),
			array( 'key' => 'nginx', 'label' => 'Nginx config', 'category' => 'Data / config', 'aliases' => array(), 'hljs' => 'nginx' ),
			array( 'key' => 'apache', 'label' => 'Apache config', 'category' => 'Data / config', 'aliases' => array( 'apacheconf' ), 'hljs' => 'apache' ),
		);
	}

	/**
	 * Returns a canonical language lookup map.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function language_map() {
		$map = array();
		foreach ( self::language_definitions() as $definition ) {
			$key         = self::slugify_language( $definition['key'] );
			$definition['key'] = $key;
			$map[ $key ] = $definition;
		}
		return $map;
	}

	/**
	 * Returns aliases to canonical language keys.
	 *
	 * @return array<string, string>
	 */
	public static function alias_map() {
		$aliases = array();
		foreach ( self::language_definitions() as $definition ) {
			$key              = self::slugify_language( $definition['key'] );
			$aliases[ $key ]  = $key;
			$aliases[ str_replace( '-', '', $key ) ] = $key;
			if ( ! empty( $definition['aliases'] ) && is_array( $definition['aliases'] ) ) {
				foreach ( $definition['aliases'] as $alias ) {
					$alias_key = self::slugify_language( $alias );
					if ( '' !== $alias_key ) {
						$aliases[ $alias_key ] = $key;
						$aliases[ str_replace( '-', '', $alias_key ) ] = $key;
					}
				}
			}
		}
		return $aliases;
	}

	/**
	 * Returns language option data for the editor and renderer.
	 *
	 * @return array<string, mixed>
	 */
	public static function public_registry() {
		return array(
			'languages' => self::language_map(),
			'aliases'   => self::alias_map(),
			'count'     => count( self::language_definitions() ),
		);
	}

	/**
	 * Normalizes a language key or alias.
	 *
	 * @param mixed $language Raw language.
	 * @return string
	 */
	public static function normalize_language( $language ) {
		$language = is_string( $language ) ? self::slugify_language( $language ) : 'plaintext';
		$aliases  = self::alias_map();

		return isset( $aliases[ $language ] ) ? $aliases[ $language ] : 'plaintext';
	}

	/**
	 * Returns the label for a language key.
	 *
	 * @param string $language Language key.
	 * @return string
	 */
	public static function label_for( $language ) {
		$language = self::normalize_language( $language );
		$map      = self::language_map();

		return isset( $map[ $language ]['label'] ) ? (string) $map[ $language ]['label'] : 'Plain text';
	}

	/**
	 * Sanitizes language identifiers while keeping useful separators.
	 *
	 * @param string $language Language identifier.
	 * @return string
	 */
	private static function slugify_language( $language ) {
		$language = strtolower( trim( (string) $language ) );
		$language = str_replace( array( '+', '#', '/', '_' ), array( 'plus', 'sharp', '-', '-' ), $language );
		$language = preg_replace( '/[^a-z0-9-]+/', '-', $language );
		$language = preg_replace( '/-+/', '-', $language );
		return trim( $language, '-' );
	}
}
