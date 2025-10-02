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
