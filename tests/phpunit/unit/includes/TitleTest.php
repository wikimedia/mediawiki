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
use MediaWikiUnitTestCase;
use Title;
use TitleValue;

/**
 * @covers Title
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
	 * @covers Title::legalChars
	 */
	public function testLegalChars() {
		$titlechars = Title::legalChars();

		foreach ( range( 1, 255 ) as $num ) {
			$chr = chr( $num );
			if ( strpos( "#[]{}<>|", $chr ) !== false || preg_match( "/[\\x00-\\x1f\\x7f]/", $chr ) ) {
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

	public function provideConvertByteClassToUnicodeClass() {
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
	 * @covers Title::convertByteClassToUnicodeClass
	 */
	public function testConvertByteClassToUnicodeClass( $byteClass, $unicodeClass ) {
		$this->assertEquals( $unicodeClass, Title::convertByteClassToUnicodeClass( $byteClass ) );
	}

	public static function provideNewFromTitleValue() {
		return [
			[ new TitleValue( NS_MAIN, 'Foo' ) ],
			[ new TitleValue( NS_MAIN, 'Foo', 'bar' ) ],
			[ new TitleValue( NS_USER, 'Hansi_Maier' ) ],
		];
	}

	/**
	 * @covers Title::newFromTitleValue
	 * @dataProvider provideNewFromTitleValue
	 */
	public function testNewFromTitleValue( TitleValue $value ) {
		$title = Title::newFromTitleValue( $value );

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $dbkey, $title->getDBkey() );
		$this->assertEquals( $value->getNamespace(), $title->getNamespace() );
		$this->assertEquals( $value->getFragment(), $title->getFragment() );
	}

	/**
	 * @covers Title::newFromLinkTarget
	 * @dataProvider provideNewFromTitleValue
	 */
	public function testNewFromLinkTarget( LinkTarget $value ) {
		$title = Title::newFromLinkTarget( $value );

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $dbkey, $title->getDBkey() );
		$this->assertEquals( $value->getNamespace(), $title->getNamespace() );
		$this->assertEquals( $value->getFragment(), $title->getFragment() );
	}

	/**
	 * @covers Title::newFromLinkTarget
	 */
	public function testNewFromLinkTarget_clone() {
		$title = Title::makeTitle( NS_MAIN, 'Example' );
		$this->assertSame( $title, Title::newFromLinkTarget( $title ) );

		// The Title::NEW_CLONE flag should ensure that a fresh instance is returned.
		$clone = Title::newFromLinkTarget( $title, Title::NEW_CLONE );
		$this->assertNotSame( $title, $clone );
		$this->assertTrue( $clone->equals( $title ) );
	}

	public function provideCastFromLinkTarget() {
		return array_merge( [ [ null ] ], $this->provideNewFromTitleValue() );
	}

	/**
	 * @covers Title::castFromLinkTarget
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

	/**
	 * @covers Title::getFragment
	 * @covers Title::setFragment
	 * @covers Title::normalizeFragment
	 * @dataProvider provideDataForTestSetAndGetFragment
	 */
	public function testSetAndGetFragment( string $fragment, $expected ) {
		$title = Title::makeTitle( NS_MAIN, 'Title' );
		$title->setFragment( $fragment );
		$this->assertSame( $expected, $title->getFragment() );
	}

	public function provideDataForTestSetAndGetFragment() {
		return [
			[ '#fragment', 'fragment' ],
			[ '#fragment_frag', 'fragment frag' ],
			[ 'fragment', 'fragment' ],
			[ 'fragment_frag', 'fragment frag' ],
		];
	}

	/**
	 * @covers Title::hasFragment
	 * @dataProvider provideTitleWithOrWithoutFragments
	 */
	public function testHasFragment( Title $title, $expected ) {
		$this->assertSame( $expected, $title->hasFragment() );
	}

	public function provideTitleWithOrWithoutFragments() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Title', 'fragment' ), true ],
			[ Title::makeTitle( NS_MAIN, 'Title' ), false ],
			[ Title::makeTitle( NS_HELP, '' ), false ],
		];
	}

}
