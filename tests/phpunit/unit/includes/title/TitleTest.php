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

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Title\Title
 *
 * @author DannyS712
 */
class TitleTest extends MediaWikiUnitTestCase {

	public function testGetters() {
		// The only way to create a title without needing any services is ::makeTitle
		// link to `w:Project:About Wikipedia#Introduction'
		$title = Title::makeTitle(
			NS_PROJECT,
			'About Wikipedia',
			'Introduction',
			'w'
		);
		$this->assertTrue( $title->isExternal() );
		$this->assertSame( 'w', $title->getInterwiki() );
		$this->assertSame( 'About Wikipedia', $title->getText() );
		$this->assertSame( wfUrlencode( 'About_Wikipedia' ), $title->getPartialURL() );
		$this->assertSame( 'About_Wikipedia', $title->getDBkey() );
		$this->assertSame( NS_PROJECT, $title->getNamespace() );
		$this->assertFalse( $title->isSpecialPage() );
		$this->assertFalse( $title->isConversionTable() );
		$this->assertSame( 'Introduction', $title->getFragment() );
		$this->assertTrue( $title->hasFragment() );
	}

	public function testIsSpecial() {
		// Already checked false above, try with true now
		$title = Title::makeTitle( NS_SPECIAL, 'SpecialPages' );
		$this->assertTrue( $title->isSpecialPage() );
	}

	public function testIsConversionTable() {
		// Already checked false above, try with true now
		$title = Title::makeTitle( NS_MEDIAWIKI, 'Conversiontable/foo' );
		$this->assertTrue( $title->isConversionTable() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::legalChars
	 */
	public function testLegalChars() {
		$titlechars = Title::legalChars();

		foreach ( range( 1, 255 ) as $num ) {
			$chr = chr( $num );
			if ( $num <= 0x1f || str_contains( "#[]{}<>|\x7f", $chr ) ) {
				$this->assertFalse(
					(bool)preg_match( "/[$titlechars]/", $chr ),
					"chr($num) = $chr is not a valid titlechar"
				);
			} else {
				$this->assertTrue(
					(bool)preg_match( "/[$titlechars]/", $chr ),
					"chr($num) = $chr is a valid titlechar"
				);
			}
		}
	}

	public static function provideConvertByteClassToUnicodeClass() {
		return [
			[
				' %!"$&\'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z~\\x80-\\xFF+',
				' %!"$&\'()*,\\-./0-9:;=?@A-Z\\\\\\^_`a-z~+\\u0080-\\uFFFF',
			],
			[
				'QWERTYf-\\xFF+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\x66-\\xFD+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'QWERTYf-y+',
				'QWERTYf-y+',
			],
			[
				'QWERTYf-\\x80+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\x66-\\x80+\\x23',
				'QWERTYf-\\x7F+#\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\x66-\\x80+\\xD3',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'\\\\\\x99',
				'\\\\\\u0080-\\uFFFF',
			],
			[
				'-\\x99',
				'\\-\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\-\\x99',
				'QWERTY\\-\\u0080-\\uFFFF',
			],
			[
				'\\\\x99',
				'\\\\x99',
			],
			[
				'A-\\x9F',
				'A-\\x7F\\u0080-\\uFFFF',
			],
			[
				'\\x66-\\x77QWERTY\\x88-\\x91FXZ',
				'f-wQWERTYFXZ\\u0080-\\uFFFF',
			],
			[
				'\\x66-\\x99QWERTY\\xAA-\\xEEFXZ',
				'f-\\x7FQWERTYFXZ\\u0080-\\uFFFF',
			],
		];
	}

	/**
	 * @dataProvider provideConvertByteClassToUnicodeClass
	 * @covers \MediaWiki\Title\Title::convertByteClassToUnicodeClass
	 */
	public function testConvertByteClassToUnicodeClass( $byteClass, $unicodeClass ) {
		$this->assertEquals( $unicodeClass, Title::convertByteClassToUnicodeClass( $byteClass ) );
	}

	public static function provideTitleValues() {
		return [
			[ new TitleValue( NS_MAIN, 'Foo' ) ],
			[ new TitleValue( NS_MAIN, 'Foo', 'bar' ) ],
			[ new TitleValue( NS_USER, 'Hansi_Maier' ) ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::newFromLinkTarget
	 * @dataProvider provideTitleValues
	 */
	public function testNewFromLinkTarget( LinkTarget $value ) {
		$title = Title::newFromLinkTarget( $value );

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $dbkey, $title->getDBkey() );
		$this->assertEquals( $value->getNamespace(), $title->getNamespace() );
		$this->assertEquals( $value->getFragment(), $title->getFragment() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::newFromLinkTarget
	 */
	public function testNewFromLinkTarget_clone() {
		$title = Title::makeTitle( NS_MAIN, 'Example' );
		$this->assertSame( $title, Title::newFromLinkTarget( $title ) );

		// The Title::NEW_CLONE flag should ensure that a fresh instance is returned.
		$clone = Title::newFromLinkTarget( $title, Title::NEW_CLONE );
		$this->assertNotSame( $title, $clone );
		$this->assertTrue( $clone->equals( $title ) );
	}

	public static function provideCastFromLinkTarget() {
		return [ [ null ], ...self::provideTitleValues() ];
	}

	/**
	 * @covers \MediaWiki\Title\Title::castFromLinkTarget
	 * @dataProvider provideCastFromLinkTarget
	 */
	public function testCastFromLinkTarget( $value ) {
		$title = Title::castFromLinkTarget( $value );

		if ( $value === null ) {
			$this->assertNull( $title );
		} else {
			$dbkey = str_replace( ' ', '_', $value->getText() );
			$this->assertSame( $dbkey, $title->getDBkey() );
			$this->assertSame( $value->getNamespace(), $title->getNamespace() );
			$this->assertSame( $value->getFragment(), $title->getFragment() );
		}
	}

	public static function provideDataForTestSetAndGetFragment() {
		return [
			[ '#fragment', 'fragment' ],
			[ '#fragment_frag', 'fragment frag' ],
			[ 'fragment', 'fragment' ],
			[ 'fragment_frag', 'fragment frag' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getFragment
	 * @covers \MediaWiki\Title\Title::setFragment
	 * @covers \MediaWiki\Title\Title::normalizeFragment
	 * @dataProvider provideDataForTestSetAndGetFragment
	 */
	public function testSetAndGetFragment( string $fragment, $expected ) {
		$title = Title::makeTitle( NS_MAIN, 'Title' );
		$title->setFragment( $fragment );
		$this->assertSame( $expected, $title->getFragment() );
	}

	public static function provideTitleWithOrWithoutFragments() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Title', 'fragment' ), true ],
			[ Title::makeTitle( NS_MAIN, 'Title' ), false ],
			[ Title::makeTitle( NS_HELP, '' ), false ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::hasFragment
	 * @dataProvider provideTitleWithOrWithoutFragments
	 */
	public function testHasFragment( Title $title, $expected ) {
		$this->assertSame( $expected, $title->hasFragment() );
	}

	public static function provideCompare() {
		yield 'Title == Title' => [
			Title::makeTitle( NS_MAIN, 'Aa' ),
			Title::makeTitle( NS_MAIN, 'Aa' ),
			0
		];
		yield 'Title > Title, name' => [
			Title::makeTitle( NS_MAIN, 'Ax' ),
			Title::makeTitle( NS_MAIN, 'Aa' ),
			1
		];
		yield 'Title < Title, name' => [
			Title::makeTitle( NS_MAIN, 'Aa' ),
			Title::makeTitle( NS_MAIN, 'Ax' ),
			-1
		];
		yield 'Title > Title, ns' => [
			Title::makeTitle( NS_TALK, 'Aa' ),
			Title::makeTitle( NS_MAIN, 'Aa' ),
			1
		];
		yield 'Title < Title, ns' => [
			Title::makeTitle( NS_SPECIAL, 'Aa' ),
			Title::makeTitle( NS_MAIN, 'Aa' ),
			-1
		];
		yield 'LinkTarget == PageReference' => [
			new TitleValue( NS_MAIN, 'Aa' ),
			new PageReferenceValue( NS_MAIN, 'Aa', PageReference::LOCAL ),
			0
		];
		yield 'Title > PageReference, name' => [
			Title::makeTitle( NS_TALK, 'Aa' ),
			new PageReferenceValue( NS_MAIN, 'Aa', PageReference::LOCAL ),
			1
		];
		yield 'LinkTarget < Title, ns' => [
			new TitleValue( NS_SPECIAL, 'Aa' ),
			Title::makeTitle( NS_MAIN, 'Aa' ),
			-2
		];
	}

	/**
	 * @dataProvider provideCompare
	 * @covers \MediaWiki\Title\Title::compare
	 */
	public function testCompare( $a, $b, $expected ) {
		if ( $expected > 0 ) {
			$this->assertGreaterThan( 0, Title::compare( $a, $b ) );
		} elseif ( $expected < 0 ) {
			$this->assertLessThan( 0, Title::compare( $a, $b ) );
		} else {
			$this->assertSame( 0, Title::compare( $a, $b ) );
		}
	}

	public static function provideCastFromPageIdentity() {
		yield [ null ];
		yield [ PageIdentity::class ];
		yield [ Title::class ];
	}

	/**
	 * @covers \MediaWiki\Title\Title::castFromPageIdentity
	 * @dataProvider provideCastFromPageIdentity
	 */
	public function testCastFromPageIdentity( $value ) {
		if ( $value !== null ) {
			$value = $this->createMock( $value );
			$value->method( 'getId' )->willReturn( 7 );
			$value->method( 'getNamespace' )->willReturn( NS_MAIN );
			$value->method( 'getDBkey' )->willReturn( 'Test' );
		}

		$title = Title::castFromPageIdentity( $value );

		if ( $value === null ) {
			$this->assertNull( $title );
		} elseif ( $value instanceof Title ) {
			$this->assertSame( $value, $title );
		} else {
			$this->assertSame( $value->getId(), $title->getArticleID() );
			$this->assertSame( $value->getNamespace(), $title->getNamespace() );
			$this->assertSame( $value->getDBkey(), $title->getDBkey() );
		}
	}

	public static function provideCastFromPageReference() {
		yield [ new PageReferenceValue( NS_MAIN, 'Test', PageReference::LOCAL ) ];
	}

	/**
	 * @covers \MediaWiki\Title\Title::castFromPageReference
	 * @dataProvider provideCastFromPageIdentity
	 * @dataProvider provideCastFromPageReference
	 */
	public function testCastFromPageReference( $value ) {
		if ( is_string( $value ) ) {
			$value = $this->createMock( $value );
			$value->method( 'getId' )->willReturn( 7 );
			$value->method( 'getNamespace' )->willReturn( NS_MAIN );
			$value->method( 'getDBkey' )->willReturn( 'Test' );
		}

		$title = Title::castFromPageReference( $value );

		if ( $value === null ) {
			$this->assertNull( $title );
		} elseif ( $value instanceof Title ) {
			$this->assertSame( $value, $title );
		} else {
			$this->assertSame( $value->getNamespace(), $title->getNamespace() );
			$this->assertSame( $value->getDBkey(), $title->getDBkey() );
		}
	}

	public static function provideCreateFragmentTitle() {
		return [
			[ NS_MAIN, 'Test', 'foo' ],
			[ NS_TALK, 'Test', 'foo', '' ],
			[ NS_CATEGORY, 'Test', 'foo', 'bar' ],
			[ NS_MAIN, 'Test1', '', 'interwiki', 'baz' ]
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::createFragmentTarget
	 * @dataProvider provideCreateFragmentTitle
	 */
	public function testCreateFragmentTitle( $namespace, $title, $fragment ) {
		$title = Title::makeTitle( $namespace, $title );
		$fragmentTitle = $title->createFragmentTarget( $fragment );

		$this->assertEquals( $title->getNamespace(), $fragmentTitle->getNamespace() );
		$this->assertEquals( $title->getText(), $fragmentTitle->getText() );
		$this->assertEquals( $title->getInterwiki(), $fragmentTitle->getInterwiki() );
		$this->assertEquals( $fragment, $fragmentTitle->getFragment() );
	}

	public static function provideEquals() {
		yield '(makeTitle) same text' => [
			Title::makeTitle( NS_MAIN, 'Main Page' ),
			Title::makeTitle( NS_MAIN, 'Main Page' ),
			true
		];
		yield '(makeTitle) different text' => [
			Title::makeTitle( NS_MAIN, 'Main Page' ),
			Title::makeTitle( NS_MAIN, 'Not The Main Page' ),
			false
		];
		yield '(makeTitle) different namespace, same text' => [
			Title::makeTitle( NS_MAIN, 'Main Page' ),
			Title::makeTitle( NS_PROJECT, 'Main Page' ),
			false
		];
		yield '(makeTitle) namespace alias' => [
			Title::makeTitle( NS_FILE, 'Example.png' ),
			Title::makeTitle( NS_FILE, 'Example.png' ),
			true
		];
		yield '(makeTitle) same special page' => [
			Title::makeTitle( NS_SPECIAL, 'Version' ),
			Title::makeTitle( NS_SPECIAL, 'Version' ),
			true
		];
		yield '(makeTitle) different special page' => [
			Title::makeTitle( NS_SPECIAL, 'Version' ),
			Title::makeTitle( NS_SPECIAL, 'Recentchanges' ),
			false
		];
		yield '(makeTitle) compare special and normal page' => [
			Title::makeTitle( NS_SPECIAL, 'Version' ),
			Title::makeTitle( NS_MAIN, 'Main Page' ),
			false
		];
		yield '(makeTitle) same fragment' => [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			true
		];
		yield '(makeTitle) different fragment (ignored)' => [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', 'Baz', '' ),
			true
		];
		yield '(makeTitle) fragment vs no fragment (ignored)' => [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			true
		];
		yield '(makeTitle) same interwiki' => [
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			true
		];
		yield '(makeTitle) different interwiki' => [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			false
		];

		// Wrong type
		yield '(makeTitle vs PageIdentityValue) name text' => [
			Title::makeTitle( NS_MAIN, 'Foo' ),
			new PageIdentityValue( 0, NS_MAIN, 'Foo', PageIdentity::LOCAL ),
			false
		];
		yield '(makeTitle vs TitleValue) name text' => [
			Title::makeTitle( NS_MAIN, 'Foo' ),
			new TitleValue( NS_MAIN, 'Foo' ),
			false
		];
		yield '(makeTitle vs UserIdentityValue) name text' => [
			Title::makeTitle( NS_MAIN, 'Foo' ),
			new UserIdentityValue( 7, 'Foo' ),
			false
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::equals
	 * @dataProvider provideEquals
	 */
	public function testEquals( Title $firstValue, $secondValue, $expectedSame ) {
		$this->assertSame(
			$expectedSame,
			$firstValue->equals( $secondValue )
		);
	}

	public static function provideIsSameLinkAs() {
		yield 'same text' => [
			Title::makeTitle( 0, 'Foo' ),
			new TitleValue( 0, 'Foo' ),
			true
		];
		yield 'same namespace' => [
			Title::makeTitle( 1, 'Bar_Baz' ),
			new TitleValue( 1, 'Bar_Baz' ),
			true
		];
		yield 'same text, different namespace' => [
			Title::makeTitle( 0, 'Foo' ),
			new TitleValue( 1, 'Foo' ),
			false
		];
		yield 'different text' => [
			Title::makeTitle( 0, 'Foo' ),
			new TitleValue( 0, 'Foozz' ),
			false
		];
		yield 'different fragment' => [
			Title::makeTitle( 0, 'Foo', '' ),
			new TitleValue( 0, 'Foo', 'Bar' ),
			false
		];
		yield 'different interwiki' => [
			Title::makeTitle( 0, 'Foo', '', 'bar' ),
			new TitleValue( 0, 'Foo', '', '' ),
			false
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isSameLinkAs
	 * @dataProvider provideIsSameLinkAs
	 */
	public function testIsSameLinkAs( Title $firstValue, $secondValue, $expectedSame ) {
		$this->assertSame(
			$expectedSame,
			$firstValue->isSameLinkAs( $secondValue )
		);
	}

}
