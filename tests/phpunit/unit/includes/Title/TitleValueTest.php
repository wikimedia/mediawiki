<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Daniel Kinzler
 */

use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;

/**
 * @covers \MediaWiki\Title\TitleValue
 *
 * @group Title
 */
class TitleValueTest extends \MediaWikiUnitTestCase {

	public static function goodConstructorProvider() {
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
		$this->assertEquals( strtr( $text, ' ', '_' ), $title->getDBkey() );
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
		$this->assertEquals( strtr( $text, ' ', '_' ), $title->getDBkey() );
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

	public static function badConstructorNamespaceTypeProvider() {
		return [
			[ 'foo', 'title', 'fragment', '' ],
			[ null, 'title', 'fragment', '' ],
			[ 2.3, 'title', 'fragment', '' ],
		];
	}

	public static function badConstructorProvider() {
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

	public static function fragmentTitleProvider() {
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

	public static function provideNewFromPage() {
		yield [ PageReferenceValue::localReference( NS_USER, 'Test' ) ];
		yield [ new PageReferenceValue( NS_USER, 'Test', 'acme' ) ];
	}

	/**
	 * @dataProvider provideNewFromPage
	 *
	 * @param PageReference $page
	 */
	public function testNewFromPage( PageReference $page ) {
		$title = TitleValue::newFromPage( $page );

		$this->assertSame( $page->getNamespace(), $title->getNamespace() );
		$this->assertSame( $page->getDBkey(), $title->getDBkey() );
		$this->assertSame( $page->getDBkey(), $title->getText() );
		$this->assertSame( '', $title->getFragment() );
		$this->assertSame( '', $title->getInterwiki() );
		$this->assertFalse( $title->isExternal() );
		$this->assertFalse( $title->hasFragment() );
	}

	/**
	 * @dataProvider provideNewFromPage
	 *
	 * @param PageReference $page
	 */
	public function testCastPageToLinkTarget( PageReference $page ) {
		$title = TitleValue::castPageToLinkTarget( $page );

		$this->assertSame( $page->getNamespace(), $title->getNamespace() );
		$this->assertSame( $page->getDBkey(), $title->getDBkey() );
		$this->assertSame( $page->getDBkey(), $title->getText() );
		$this->assertSame( '', $title->getFragment() );
		$this->assertSame( '', $title->getInterwiki() );
		$this->assertFalse( $title->isExternal() );
		$this->assertFalse( $title->hasFragment() );
	}

	public function testCastTitleToLinkTarget() {
		$page = Title::makeTitle( NS_MAIN, 'Test' );
		$this->assertSame( $page, TitleValue::castPageToLinkTarget( $page ) );
	}

	public function testCastNullToLinkTarget() {
		$this->assertNull( TitleValue::castPageToLinkTarget( null ) );
	}

	public static function getTextProvider() {
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

	public static function provideTestToString() {
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

	public static function provideIsSameLinkAs() {
		yield [
			new TitleValue( 0, 'Foo' ),
			new TitleValue( 0, 'Foo' ),
			true
		];
		yield [
			new TitleValue( 1, 'Bar_Baz' ),
			new TitleValue( 1, 'Bar_Baz' ),
			true
		];
		yield [
			new TitleValue( 0, 'Foo' ),
			new TitleValue( 1, 'Foo' ),
			false
		];
		yield [
			new TitleValue( 0, 'Foo' ),
			new TitleValue( 0, 'Foozz' ),
			false
		];
		yield [
			new TitleValue( 0, 'Foo', '' ),
			new TitleValue( 0, 'Foo', 'Bar' ),
			false
		];
		yield [
			new TitleValue( 0, 'Foo', '', 'bar' ),
			new TitleValue( 0, 'Foo', '', '' ),
			false
		];
	}

	/**
	 * @dataProvider provideIsSameLinkAs
	 */
	public function testIsSameLinkAs( TitleValue $a, TitleValue $b, $expected ) {
		$this->assertSame( $expected, $a->isSameLinkAs( $b ) );
		$this->assertSame( $expected, $b->isSameLinkAs( $a ) );
	}
}
