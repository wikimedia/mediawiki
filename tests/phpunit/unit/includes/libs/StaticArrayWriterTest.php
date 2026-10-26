<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

namespace Wikimedia\Tests;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\StaticArrayWriter;

/**
 * @covers \Wikimedia\StaticArrayWriter
 */
class StaticArrayWriterTest extends TestCase {
	use MediaWikiCoversValidator;

	public function testCreate() {
		$data = [
			'foo' => 'bar',
			'baz' => 'rawr',
			"they're" => '"quoted properly"',
			'nested' => [ 'elements', 'work' ],
			'intlike' => [ '050' => true, '101' => true, '221B' => true ],
			'and' => [ 'these' => 'do too' ],
		];
		$writer = new StaticArrayWriter();
		$actual = $writer->create( $data, "Header\nWith\nNewlines" );
		$expected = <<<PHP
<?php
// Header
// With
// Newlines
return [
	'foo' => 'bar',
	'baz' => 'rawr',
	'they\'re' => '"quoted properly"',
	'nested' => [
		'elements',
		'work',
	],
	'intlike' => [
		'050' => true,
		101 => true,
		'221B' => true,
	],
	'and' => [
		'these' => 'do too',
	],
];

PHP;
		$this->assertSame( $expected, $actual );
	}

	public static function provideUnformatted() {
		yield 'scalars' => [
			[ 'foo' => 'bar', 'int' => 42, 'float' => 1.5, 'bool' => false, 'null' => null ],
			<<<PHP
<?php
// Header
return array (
  'foo' => 'bar',
  'int' => 42,
  'float' => 1.5,
  'bool' => false,
  'null' => NULL,
);
PHP
		];
		yield 'escaping' => [
			[ "they're" => "back\\slash 'quote'\n  newline", "nul\0byte" => "\0" ],
			<<<PHP
<?php
// Header
return array (
  'they\'re' => 'back\\\slash \'quote\'
  newline',
  'nul' . "\\0" . 'byte' => '' . "\\0" . '',
);
PHP
		];
		yield 'nested' => [
			[ 'list' => [ 'a', 'b' ], 'map' => [ '050' => true, 101 => [ [] ] ] ],
			<<<PHP
<?php
// Header
return array (
  'list' => 
  array (
    0 => 'a',
    1 => 'b',
  ),
  'map' => 
  array (
    '050' => true,
    101 => 
    array (
      0 => 
      array (
      ),
    ),
  ),
);
PHP
		];
	}

	/**
	 * @dataProvider provideUnformatted
	 */
	public function testWriteUnformatted( array $data, string $expected ) {
		$code = StaticArrayWriter::write( $data, 'Header', StaticArrayWriter::UNFORMATTED );
		$this->assertSame( "$expected\n", $code );
		$this->assertSame( $data, $this->requireCode( $code ) );
	}

	/**
	 * @dataProvider provideUnformatted
	 */
	public function testWriteClassUnformatted( array $data ) {
		$class = 'Bar' . md5( serialize( $data ) );
		$code = StaticArrayWriter::writeClass( $data, [
			'header' => 'Header',
			'namespace' => 'Wikimedia\\Tests\\StaticArrayWriterTest',
			'class' => $class,
			'const' => 'THING',
		], StaticArrayWriter::UNFORMATTED );
		$this->requireCode( $code );
		$this->assertSame( $data, constant( "Wikimedia\\Tests\\StaticArrayWriterTest\\$class::THING" ) );
	}

	private function requireCode( string $code ) {
		$file = tempnam( sys_get_temp_dir(), 'StaticArrayWriterTest' );
		try {
			file_put_contents( $file, $code );
			return require $file;
		} finally {
			unlink( $file );
		}
	}

	public function testWriteClass() {
		$data = [
			'foo' => 'bar',
			'baz' => 'rawr',
		];
		$writer = new StaticArrayWriter();
		$actual = $writer->writeClass( $data, [
			'header' => "Header\nWith\nNewlines",
			'namespace' => 'Example\\Foo',
			'class' => 'Bar',
			'const' => 'THING',
		] );
		$expected = <<<PHP
<?php
// Header
// With
// Newlines

namespace Example\Foo;

class Bar {
	public const THING = [
		'foo' => 'bar',
		'baz' => 'rawr',
	];
}

PHP;
		$this->assertSame( $expected, $actual );
	}
}
