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
 * @group Database
 */

class DBLangLinkLoaderTest extends MediaWikiTestCase {

	public function __construct( $name = null, $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed[] = 'langlinks';
	}

	public function setUp() {
		parent::setUp();

		$dbw = wfGetDB( DB_MASTER );

		$links = array(
			array( 'll_from' =>  1, 'll_lang' => 'de', 'll_title' => 'FooDe'  ),
			array( 'll_from' =>  1, 'll_lang' => 'en', 'll_title' => 'FooEn'  ),
			array( 'll_from' =>  1, 'll_lang' => 'fr', 'll_title' => 'FooFr'  ),
			array( 'll_from' =>  2, 'll_lang' => 'de', 'll_title' => 'BarDe'  ),
			array( 'll_from' =>  2, 'll_lang' => 'en', 'll_title' => 'BarEn'  ),
			array( 'll_from' =>  3, 'll_lang' => 'de', 'll_title' => 'MehDe'  ),
		);

		foreach ( $links as $row ) {
			$dbw->insert( 'langlinks', $row, __METHOD__ );
		}
	}

	public static function provideLoadLanguageLinks() {
		return array(
			array( // #0
				array(), // $fromPageIds
				'ascending', // $dir
				10, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array(), // $expectedLinks
			),
			array( // #1
				array( 3, 1, 2 ), // $fromPageIds
				'ascending', // $dir
				10, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '1', 'll_lang' => 'de', 'll_title' => 'FooDe'  ),
					array( 'll_from' =>  '1', 'll_lang' => 'en', 'll_title' => 'FooEn'  ),
					array( 'll_from' =>  '1', 'll_lang' => 'fr', 'll_title' => 'FooFr'  ),
					array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe'  ),
					array( 'll_from' =>  '2', 'll_lang' => 'en', 'll_title' => 'BarEn'  ),
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe'  ),
				),
			),
			array( // #2
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe'  ),
					array( 'll_from' =>  '2', 'll_lang' => 'en', 'll_title' => 'BarEn'  ),
					array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe'  ),
					array( 'll_from' =>  '1', 'll_lang' => 'fr', 'll_title' => 'FooFr'  ),
					array( 'll_from' =>  '1', 'll_lang' => 'en', 'll_title' => 'FooEn'  ),
					array( 'll_from' =>  '1', 'll_lang' => 'de', 'll_title' => 'FooDe'  ),
				),
			),
			array( // #3
				array( 3, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe'  ),
					array( 'll_from' =>  '2', 'll_lang' => 'en', 'll_title' => 'BarEn'  ),
					array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe'  ),
				),
			),
			array( // #4
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				2, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe'  ),
					array( 'll_from' =>  '2', 'll_lang' => 'en', 'll_title' => 'BarEn'  ),
				),
			),
			array( // #5
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				2, // $limit
				null, // $forLang
				null, // $forTitle
				2, // $continueFrom
				'de', // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe'  ),
					array( 'll_from' =>  '1', 'll_lang' => 'fr', 'll_title' => 'FooFr'  ),
				),
			),
			array( // #6
				array( 3, 1, 2 ), // $fromPageIds
				'ascending', // $dir
				2, // $limit
				null, // $forLang
				null, // $forTitle
				3, // $continueFrom
				'de', // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe'  ),
				),
			),
			array( // #7
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				'en', // $forLang
				null, // $forTitle
				1, // $continueFrom
				'en', // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '1', 'll_lang' => 'en', 'll_title' => 'FooEn'  ),
				),
			),
			array( // #8
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				'en', // $forLang
				'BarEn', // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '2', 'll_lang' => 'en', 'll_title' => 'BarEn'  ),
				),
			),
		);
	}

	/**
	 * @dataProvider provideLoadLanguageLinks
	 */
	public function testLoadLanguageLinks(
		$fromPageIds,
		$dir,
		$limit,
		$forLang,
		$forTitle,
		$continueFrom,
		$continueLang,
		$expectedLinks
	) {
		$loader = new DBLangLinkLoader();

		$links = $loader->loadLanguageLinks(
			$fromPageIds,
			$dir,
			$limit,
			$forLang,
			$forTitle,
			$continueFrom,
			$continueLang
		);

		$links = $this->rowsToArrays( $links );
		$this->assertArrayEquals( $expectedLinks, $links, true );
	}

	protected function rowsToArrays( $links ) {
		$arrays = array_map(
			'get_object_vars',
			$links
		);

		return $arrays;
	}
}