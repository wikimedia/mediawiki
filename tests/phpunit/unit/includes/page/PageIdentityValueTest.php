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

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\Page\PageIdentityValue
 *
 * @group Title
 */
class PageIdentityValueTest extends MediaWikiUnitTestCase {

	public static function goodConstructorProvider() {
		return [
			[ 0, NS_MAIN, 'Test', false ],
			[ 7, NS_MAIN, 'Test', false ],
			[ 3, NS_USER, 'Test', 'h2g2' ],
		];
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testConstruction( $pageId, $namespace, $dbKey, $wikiId ) {
		$pageIdentity = new PageIdentityValue( $pageId, $namespace, $dbKey, $wikiId );

		$this->assertSame( $wikiId, $pageIdentity->getWikiId() );
		$this->assertSame( $pageId, $pageIdentity->getId( $wikiId ) );
		$this->assertSame( $pageId > 0, $pageIdentity->exists() );
		$this->assertSame( $namespace, $pageIdentity->getNamespace() );
		$this->assertSame( $dbKey, $pageIdentity->getDBkey() );

		$this->assertTrue( $pageIdentity->canExist() );
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testTryNew( $pageId, $namespace, $dbKey, $wikiId ) {
		$pageIdentity = PageIdentityValue::tryNew( $pageId, $namespace, $dbKey, $wikiId );

		$this->assertSame( $wikiId, $pageIdentity->getWikiId() );
		$this->assertSame( $pageId, $pageIdentity->getId( $wikiId ) );
		$this->assertSame( $pageId > 0, $pageIdentity->exists() );
		$this->assertSame( $namespace, $pageIdentity->getNamespace() );
		$this->assertSame( $dbKey, $pageIdentity->getDBkey() );

		$this->assertTrue( $pageIdentity->canExist() );
	}

	public function testGetIdFailsForForeignWiki() {
		$pageIdentity = new PageIdentityValue( 7, NS_MAIN, 'Foo', 'h2g2' );

		$this->expectException( PreconditionException::class );
		$pageIdentity->getId();
	}

	public static function badConstructorProvider() {
		return [
			[ -1, NS_MAIN, 'Test', false ],
			[ 0, NS_MAIN, 'Test', 2.3 ],
			[ 0, NS_SPECIAL, 'Test', false ],
			[ 0, NS_USER, '#1234', false ],
			[ 0, NS_SPECIAL, 'foo|bar', false ],
		];
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $pageId, $namespace, $dbKey, $wikiId ) {
		$this->expectException( ParameterAssertionException::class );
		new PageIdentityValue( $pageId, $namespace, $dbKey, $wikiId );
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testTryNewWithBadValue( $pageId, $namespace, $dbKey, $wikiId ) {
		$this->assertNull( PageIdentityValue::tryNew( $pageId, $namespace, $dbKey, $wikiId ) );
	}

	public static function provideToString() {
		yield [
			PageIdentityValue::localIdentity( 5, 0, 'Foo' ),
			'[0:Foo]'
		];
		yield [
			PageIdentityValue::localIdentity( 0, 1, 'Bar_Baz' ),
			'[1:Bar_Baz]'
		];
		yield [
			new PageIdentityValue( 7, 200, 'tea', 'codewiki' ),
			'[200:tea]@codewiki'
		];
	}

	/**
	 * @dataProvider provideToString
	 */
	public function testToString( PageIdentityValue $value, $expected ) {
		$this->assertSame(
			$expected,
			$value->__toString()
		);
	}

	public static function provideIsSamePageAs() {
		yield [
			PageIdentityValue::localIdentity( 1, 0, 'Foo' ),
			PageIdentityValue::localIdentity( 1, 0, 'Foo' ),
			true
		];
		yield [
			PageIdentityValue::localIdentity( 0, 1, 'Bar_Baz' ),
			PageIdentityValue::localIdentity( 0, 1, 'Bar_Baz' ),
			true
		];
		yield [
			PageIdentityValue::localIdentity( 0, 0, 'Foo' ),
			PageIdentityValue::localIdentity( 0, 0, 'Foozz' ),
			false
		];
		yield [
			PageIdentityValue::localIdentity( 0, 0, 'Foo' ),
			PageIdentityValue::localIdentity( 0, 1, 'Foo' ),
			false
		];
		yield [
			new PageIdentityValue( 1, 0, 'Foo', '' ),
			new PageIdentityValue( 1, 0, 'Foo', 'bar' ),
			false
		];
		yield [
			new PageIdentityValue( 0, 0, 'Foo', '' ),
			new PageIdentityValue( 0, 0, 'Foo', 'bar' ),
			false
		];
		yield [
			new PageIdentityValue( 3, 0, 'Foo', 'bar' ),
			new PageIdentityValue( 3, 0, 'Foo', 'bar' ),
			true
		];
	}

	/**
	 * @dataProvider provideIsSamePageAs
	 */
	public function testIsSamePageAs( PageIdentityValue $a, PageIdentityValue $b, $expected ) {
		$this->assertSame( $expected, $a->isSamePageAs( $b ) );
		$this->assertSame( $expected, $b->isSamePageAs( $a ) );
	}

	/**
	 * @covers \MediaWiki\Page\PageIdentityValue::localIdentity
	 */
	public function testLocalIdentity() {
		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, __METHOD__ );
		$this->assertSame( 1, $page->getId( PageIdentity::LOCAL ) );
		$this->assertSame( NS_MAIN, $page->getNamespace() );
		$this->assertSame( __METHOD__, $page->getDBkey() );
		$this->assertSame( PageIdentity::LOCAL, $page->getWikiId() );
	}
}
