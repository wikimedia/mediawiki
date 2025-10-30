<?php
/**
 * @license GPL-2.0-or-later
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
