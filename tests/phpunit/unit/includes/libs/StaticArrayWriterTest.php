<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

use Wikimedia\StaticArrayWriter;

/**
 * @covers \Wikimedia\StaticArrayWriter
 */
class StaticArrayWriterTest extends PHPUnit\Framework\TestCase {
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
		0 => 'elements',
		1 => 'work',
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
}
