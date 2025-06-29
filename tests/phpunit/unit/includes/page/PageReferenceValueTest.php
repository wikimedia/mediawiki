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
 * @author Daniel Kinzler
 */

use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use Wikimedia\Assert\ParameterAssertionException;

/**
 * @covers \MediaWiki\Page\PageReferenceValue
 *
 * @group Title
 */
class PageReferenceValueTest extends MediaWikiUnitTestCase {

	public static function goodConstructorProvider() {
		return [
			[ NS_MAIN, 'Test', false ],
			[ NS_MAIN, 'Test', false ],
			[ NS_USER, 'Test', 'h2g2' ],
			[ NS_MEDIA, 'Test', false ],
			[ NS_SPECIAL, 'Test', false ],
		];
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testConstruction( $namespace, $dbKey, $wikiId ) {
		$pageReference = new PageReferenceValue( $namespace, $dbKey, $wikiId );

		$this->assertSame( $wikiId, $pageReference->getWikiId() );
		$this->assertSame( $namespace, $pageReference->getNamespace() );
		$this->assertSame( $dbKey, $pageReference->getDBkey() );
	}

	public static function badConstructorProvider() {
		return [
			[ NS_MAIN, 'Test', 2.3 ],
		];
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $namespace, $dbKey, $wikiId ) {
		$this->expectException( ParameterAssertionException::class );
		new PageReferenceValue( $namespace, $dbKey, $wikiId );
	}

	public static function provideToString() {
		yield [
			PageReferenceValue::localReference( 0, 'Foo' ),
			'[0:Foo]'
		];
		yield [
			PageReferenceValue::localReference( 1, 'Bar_Baz' ),
			'[1:Bar_Baz]'
		];
		yield [
			new PageReferenceValue( 200, 'tea', 'codewiki' ),
			'[200:tea]@codewiki'
		];
	}

	/**
	 * @dataProvider provideToString
	 */
	public function testToString( PageReferenceValue $value, $expected ) {
		$this->assertSame(
			$expected,
			$value->__toString()
		);
	}

	public static function provideIsSamePageAs() {
		yield [
			PageReferenceValue::localReference( 0, 'Foo' ),
			PageReferenceValue::localReference( 0, 'Foo' ),
			true
		];
		yield [
			PageReferenceValue::localReference( 1, 'Bar_Baz' ),
			PageReferenceValue::localReference( 1, 'Bar_Baz' ),
			true
		];
		yield [
			PageReferenceValue::localReference( 0, 'Foo' ),
			PageReferenceValue::localReference( 0, 'Foozz' ),
			false
		];
		yield [
			PageReferenceValue::localReference( 0, 'Foo' ),
			PageReferenceValue::localReference( 1, 'Foo' ),
			false
		];
		yield [
			new PageReferenceValue( 0, 'Foo', '' ),
			new PageReferenceValue( 0, 'Foo', 'bar' ),
			false
		];
		yield [
			new PageReferenceValue( 0, 'Foo', '' ),
			new PageReferenceValue( 0, 'Foo', 'bar' ),
			false
		];
		yield [
			new PageReferenceValue( 0, 'Foo', 'bar' ),
			new PageReferenceValue( 0, 'Foo', 'bar' ),
			true
		];
	}

	/**
	 * @dataProvider provideIsSamePageAs
	 */
	public function testIsSamePageAs( PageReferenceValue $a, PageReferenceValue $b, $expected ) {
		$this->assertSame( $expected, $a->isSamePageAs( $b ) );
		$this->assertSame( $expected, $b->isSamePageAs( $a ) );
	}

	/**
	 * @covers \MediaWiki\Page\PageReferenceValue::localReference
	 */
	public function testLocalIdentity() {
		$page = PageReferenceValue::localReference( NS_MAIN, __METHOD__ );
		$this->assertSame( NS_MAIN, $page->getNamespace() );
		$this->assertSame( __METHOD__, $page->getDBkey() );
		$this->assertSame( PageReference::LOCAL, $page->getWikiId() );
	}
}
