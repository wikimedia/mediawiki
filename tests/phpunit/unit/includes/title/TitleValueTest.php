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

/**
 * @covers TitleValue
 *
 * @group Title
 */
class TitleValueTest extends \MediaWikiUnitTestCase {

	public function goodConstructorProvider() {
		return [
			[ NS_MAIN, '', 'fragment', '', true, false ],
			[ NS_MAIN, '', '', 'interwiki', false, true ],
			[ NS_MAIN, '', 'fragment', 'interwiki', true, true ],
			[ NS_USER, 'TestThis', 'stuff', '', true, false ],
			[ NS_USER, 'TestThis', '', 'baz', false, true ],
			[ NS_MAIN, 'foo bar', '', '', false, false ],
			[ NS_MAIN, 'foo_bar', '', '', false, false ],
			[ NS_MAIN, '', '', '', false, false ],
		];
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testConstruction( $ns, $text, $fragment, $interwiki, $hasFragment,
		$hasInterwiki
	) {
		$title = new TitleValue( $ns, $text, $fragment, $interwiki );

		$this->assertEquals( $ns, $title->getNamespace() );
		$this->assertTrue( $title->inNamespace( $ns ) );
		$this->assertEquals( strtr( $text, ' ', '_' ), $title->getDBKey() );
		$this->assertEquals( strtr( $text, '_', ' ' ), $title->getText() );
		$this->assertEquals( $fragment, $title->getFragment() );
		$this->assertEquals( $hasFragment, $title->hasFragment() );
		$this->assertEquals( $interwiki, $title->getInterwiki() );
		$this->assertEquals( $hasInterwiki, $title->isExternal() );
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testTryNew( $ns, $text, $fragment, $interwiki, $hasFragment,
		$hasInterwiki
	) {
		$title = TitleValue::tryNew( $ns, $text, $fragment, $interwiki );

		$this->assertEquals( $ns, $title->getNamespace() );
		$this->assertTrue( $title->inNamespace( $ns ) );
		$this->assertEquals( strtr( $text, ' ', '_' ), $title->getDBKey() );
		$this->assertEquals( strtr( $text, '_', ' ' ), $title->getText() );
		$this->assertEquals( $fragment, $title->getFragment() );
		$this->assertEquals( $hasFragment, $title->hasFragment() );
		$this->assertEquals( $interwiki, $title->getInterwiki() );
		$this->assertEquals( $hasInterwiki, $title->isExternal() );
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testAssertValidSpec( $ns, $text, $fragment, $interwiki ) {
		TitleValue::assertValidSpec( $ns, $text, $fragment, $interwiki );
		$this->assertTrue( true ); // we are just checking that no exception is thrown
	}

	public function badConstructorNamespaceTypeProvider() {
		return [
			[ 'foo', 'title', 'fragment', '' ],
			[ null, 'title', 'fragment', '' ],
			[ 2.3, 'title', 'fragment', '' ],
		];
	}

	public function badConstructorProvider() {
		return [
			[ NS_MAIN, 5, 'fragment', '' ],
			[ NS_MAIN, null, 'fragment', '' ],
			[ NS_USER, '', 'fragment', '' ],
			[ NS_USER, '', '', 'interwiki' ],
			[ NS_MAIN, 'bar_', '', '' ],
			[ NS_MAIN, '_foo', '', '' ],
			[ NS_MAIN, ' eek ', '', '' ],

			[ NS_MAIN, 'title', 5, '' ],
			[ NS_MAIN, 'title', null, '' ],
			[ NS_MAIN, 'title', [], '' ],

			[ NS_MAIN, 'title', '', 5 ],
			[ NS_MAIN, 'title', null, 5 ],
			[ NS_MAIN, 'title', [], 5 ],
		];
	}

	/**
	 * @dataProvider badConstructorNamespaceTypeProvider
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $ns, $text, $fragment, $interwiki ) {
		$this->expectException( InvalidArgumentException::class );
		new TitleValue( $ns, $text, $fragment, $interwiki );
	}

	/**
	 * @dataProvider badConstructorNamespaceTypeProvider
	 */
	public function testTryNewErrors( $ns, $text, $fragment, $interwiki ) {
		$this->expectException( InvalidArgumentException::class );
		TitleValue::tryNew( $ns, $text, $fragment, $interwiki );
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testTryNewFailure( $ns, $text, $fragment, $interwiki ) {
		$this->assertNull( TitleValue::tryNew( $ns, $text, $fragment, $interwiki ) );
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testAssertValidSpecErrors( $ns, $text, $fragment, $interwiki ) {
		$this->expectException( InvalidArgumentException::class );
		TitleValue::assertValidSpec( $ns, $text, $fragment, $interwiki );
	}

	public function fragmentTitleProvider() {
		return [
			[ new TitleValue( NS_MAIN, 'Test' ), 'foo' ],
			[ new TitleValue( NS_TALK, 'Test', 'foo' ), '' ],
			[ new TitleValue( NS_CATEGORY, 'Test', 'foo' ), 'bar' ],
		];
	}

	/**
	 * @dataProvider fragmentTitleProvider
	 */
	public function testCreateFragmentTitle( TitleValue $title, $fragment ) {
		$fragmentTitle = $title->createFragmentTarget( $fragment );

		$this->assertEquals( $title->getNamespace(), $fragmentTitle->getNamespace() );
		$this->assertEquals( $title->getText(), $fragmentTitle->getText() );
		$this->assertEquals( $fragment, $fragmentTitle->getFragment() );
	}

	public function getTextProvider() {
		return [
			[ 'Foo', 'Foo' ],
			[ 'Foo_Bar', 'Foo Bar' ],
		];
	}

	/**
	 * @dataProvider getTextProvider
	 */
	public function testGetText( $dbkey, $text ) {
		$title = new TitleValue( NS_MAIN, $dbkey );

		$this->assertEquals( $text, $title->getText() );
	}

	public function provideTestToString() {
		yield [
			new TitleValue( 0, 'Foo' ),
			'0:Foo'
		];
		yield [
			new TitleValue( 1, 'Bar_Baz' ),
			'1:Bar_Baz'
		];
		yield [
			new TitleValue( 9, 'JoJo', 'Frag' ),
			'9:JoJo#Frag'
		];
		yield [
			new TitleValue( 200, 'tea', 'Fragment', 'wikicode' ),
			'wikicode:200:tea#Fragment'
		];
	}

	/**
	 * @dataProvider provideTestToString
	 */
	public function testToString( TitleValue $value, $expected ) {
		$this->assertSame(
			$expected,
			$value->__toString()
		);
	}
}
