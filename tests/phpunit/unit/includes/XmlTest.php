<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Tests\Unit;

use MediaWikiUnitTestCase;
use MWException;
use Xml;
use XmlJsCode;

/**
 * Split from \XmlTest integration tests
 *
 * @group Xml
 */
class XmlTest extends MediaWikiUnitTestCase {

	/**
	 * @covers Xml::expandAttributes
	 */
	public function testExpandAttributes() {
		$this->assertNull(
			Xml::expandAttributes( null ),
			'Converting a null list of attributes'
		);
		$this->assertSame(
			'',
			Xml::expandAttributes( [] ),
			'Converting an empty list of attributes'
		);
	}

	/**
	 * @covers Xml::expandAttributes
	 */
	public function testExpandAttributesException() {
		$this->expectException( MWException::class );
		Xml::expandAttributes( 'string' );
	}

	/**
	 * @covers Xml::escapeTagsOnly
	 */
	public function testEscapeTagsOnly() {
		$this->assertEquals( '&quot;&gt;&lt;', Xml::escapeTagsOnly( '"><' ),
			'replace " > and < with their HTML entitites'
		);
	}

	public function provideEncodeJsVar() {
		// $expected, $input
		yield 'boolean' => [ 'true', true ];
		yield 'null' => [ 'null', null ];
		yield 'array' => [ '["a",1]', [ 'a', 1 ] ];
		yield 'associative arary' => [ '{"a":"a","b":1}', [ 'a' => 'a', 'b' => 1 ] ];
		yield 'object' => [ '{"a":"a","b":1}', (object)[ 'a' => 'a', 'b' => 1 ] ];
		yield 'int' => [ '123456', 123456 ];
		yield 'float' => [ '1.5', 1.5 ];
		yield 'int-like string' => [ '"123456"', '123456' ];

		$code = 'function () { foo( 42 ); }';
		yield 'code' => [ $code, new XmlJsCode( $code ) ];
	}

	/**
	 * @covers Xml::encodeJsVar
	 * @dataProvider provideEncodeJsVar
	 */
	public function testEncodeJsVar( string $expect, $input ) {
		$this->assertEquals(
			$expect,
			Xml::encodeJsVar( $input )
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 * @covers XmlJsCode::encodeObject
	 */
	public function testEncodeObject() {
		$codeA = 'function () { foo( 42 ); }';
		$codeB = 'function ( jQuery ) { bar( 142857 ); }';
		$obj = XmlJsCode::encodeObject( [
			'a' => new XmlJsCode( $codeA ),
			'b' => new XmlJsCode( $codeB )
		] );
		$this->assertEquals(
			"{\"a\":$codeA,\"b\":$codeB}",
			Xml::encodeJsVar( $obj )
		);
	}

}
