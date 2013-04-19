<?php
 /**
 * Test for LangLinkConverter
 *
 * Copyright © 19.04.13 by the authors listed below.
 *
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
 * @license GPL 2+
 * @file
 *
 * @author Daniel Kinzler
 *
 * @group LangLink
 */

class LangLinkConverterTest extends MediaWikiTestCase {

	public static function makeRow( $from, $lang, $title, $flags = null ) {
		$row = new stdClass();
		$row->ll_from = strval( $from );
		$row->ll_lang = strval( $lang );
		$row->ll_title = strval( $title );

		if ( $flags !== null ) {
			$row->ll_flags = (array)$flags;
		}

		return $row;
	}

	public static function provideRowToLink() {
		return array(
			array( // #0
				self::makeRow( 11, 'de', 'Foo' ),
				'de:Foo'
			)
		);
	}

	/**
	 * @dataProvider provideRowToLink
	 */
	public function testRowToLink( $row, $link ) {
		$converter = new LangLinkConverter();

		$this->assertEquals( $link, $converter->rowToLink( $row ) );
	}


	public static function provideRowsToLinks() {
		return array(
			array( // #0
				array(),
				array()
			),
			array( // #1
				array(
					self::makeRow( 11, 'de', 'Foo' )
				),
				array(
					'de' => 'de:Foo'
				)
			),
			array( // #2
				array(
					self::makeRow( 11, 'en', 'Boo' ),
					self::makeRow( 11, 'de', 'Foo' ),
				),
				array(
					'en' => 'en:Boo',
					'de' => 'de:Foo',
				)
			)
		);
	}

	/**
	 * @dataProvider provideRowsToLinks
	 */
	public function testRowsToLinks( $row, $link ) {
		$converter = new LangLinkConverter();

		$this->assertArrayEquals( $link, $converter->rowsToLinks( $row ), false, true );
	}




	public static function provideLinkToRow() {
		return array(
			array( // #0
				11, 'de:Foo',
				self::makeRow( 11, 'de', 'Foo' ),
			),
			array( // #1
				12, ':Foo',
				self::makeRow( 12, '', 'Foo' ),
			),
			array( // #2
				13, 'Foo',
				self::makeRow( 13, '', 'Foo' ),
			),
			array( // #3
				14, 'de:Foo:Bar',
				self::makeRow( 14, 'de', 'Foo:Bar' ),
			),
		);
	}

	/**
	 * @dataProvider provideLinkToRow
	 */
	public function testLinkToRow( $from, $link, $row ) {
		$converter = new LangLinkConverter();

		$this->assertEquals( $row, $converter->linkToRow( $from, $link ) );
	}


	public static function provideLinksToRows() {
		return array(
			array( // #0
				11,
				array(),
				array()
			),
			array( // #1
				11,
				array(
					'de:Foo'
				),
				array(
					self::makeRow( 11, 'de', 'Foo' )
				),
			),
			array( // #2
				11,
				array(
					'en:Boo',
					'de:Foo',
				),
				array(
					self::makeRow( 11, 'en', 'Boo' ),
					self::makeRow( 11, 'de', 'Foo' ),
				),
			)
		);
	}

	/**
	 * @dataProvider provideLinksToRows
	 */
	public function testLinksToRows( $from, $links, $rows ) {
		$converter = new LangLinkConverter();

		$this->assertArrayEquals( $rows, $converter->linksToRows( $from, $links ), true );
	}



	public static function provideFlagRow() {
		return array(
			array( // #0
				self::makeRow( 11, 'de', 'Foo', null ),
				array( 'test' ),
				self::makeRow( 11, 'de', 'Foo', array( 'test' ) ),
			),
			array( // #1
				self::makeRow( 11, 'de', 'Foo', array() ),
				array( 'test' ),
				self::makeRow( 11, 'de', 'Foo', array( 'test' ) ),
			),
			array( // #2
				self::makeRow( 11, 'de', 'Foo', array( 'stuff' ) ),
				array( 'stuff', 'best', 'test' ),
				self::makeRow( 11, 'de', 'Foo', array( 'stuff', 'best', 'test' ) ),
			),
			array( // #3
				self::makeRow( 11, 'de', 'Foo', array( 'test' ) ),
				array(),
				self::makeRow( 11, 'de', 'Foo', array( 'test' ) ),
			),
		);
	}

	/**
	 * @dataProvider provideFlagRow
	 */
	public function testFlagRow( $row, $flags, $result ) {
		$converter = new LangLinkConverter();

		$actual = $converter->flagRow( $row, $flags );
		$this->assertArrayEquals( $result->ll_flags, $actual->ll_flags );
	}


	public static function provideFlagRows() {
		return array(
			array( // #0
				array(),
				array(),
				array()
			),
			array( // #1
				array(
					self::makeRow( 11, 'de', 'Foo' )
				),
				array(
					'de' => array( 'test' ),
					'en' => array( 'test' )
				),
				array(
					self::makeRow( 11, 'de', 'Foo', array( 'test' ) )
				),
			),
			array( // #2
				array(
					self::makeRow( 11, 'en', 'Boo' ),
					self::makeRow( 11, 'de', 'Foo' ),
				),
				array(
					'de' => array( 'test' ),
					'en' => array( 'best', 'zest' ),
					'xx' => array( 'yest' ),
				),
				array(
					self::makeRow( 11, 'en', 'Boo', array( 'best', 'zest' ) ),
					self::makeRow( 11, 'de', 'Foo', array( 'test' ) ),
				),
			),
			array( // #3
				array(
					self::makeRow( 11, 'de', 'Foo' )
				),
				array(),
				array(
					self::makeRow( 11, 'de', 'Foo', array() )
				),
			),
			array( // #4
				array(
					self::makeRow( 11, 'en', 'Boo' ),
					self::makeRow( 11, 'de', 'Foo' ),
				),
				array( 'de' => array( 'test' ) ),
				array(
					self::makeRow( 11, 'en', 'Boo', array() ),
					self::makeRow( 11, 'de', 'Foo', array( 'test' ) ),
				),
			),
		);
	}

	/**
	 * @dataProvider provideFlagRows
	 */
	public function testFlagRows( $rows, $flags, $result ) {
		$converter = new LangLinkConverter();

		$this->assertArrayEquals( $result, $converter->flagRows( $rows, $flags ), true );
	}

}