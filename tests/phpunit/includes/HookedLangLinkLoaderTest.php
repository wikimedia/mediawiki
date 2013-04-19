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
 *
 * @author Daniel Kinzler
 *
 * @group LangLink
 * @group Database
 *           ^--- needed, because HookedLangLinkLoader uses a Title object
 */
class HookedLangLinkLoaderTest extends MediaWikiTestCase {

	protected static $links = array(
		array( 'll_from' => '1', 'll_lang' => 'de', 'll_title' => 'FooDe' ),
		array( 'll_from' => '1', 'll_lang' => 'en', 'll_title' => 'FooEn' ),
		array( 'll_from' => '1', 'll_lang' => 'fr', 'll_title' => 'FooFr' ),
		array( 'll_from' => '2', 'll_lang' => 'de', 'll_title' => 'BarDe' ),
		array( 'll_from' => '2', 'll_lang' => 'en', 'll_title' => 'BarEn' ),
		array( 'll_from' => '3', 'll_lang' => 'de', 'll_title' => 'MehDe' ),
	);

	public static function provideLoadLanguageLinksNoHook() {
		return array(
			array( // #0
				self::$links,
				null,
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
				self::$links,
				null,
				array( 3, 1, 2 ), // $fromPageIds
				'ascending', // $dir
				10, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				self::$links, // $expectedLinks
			),
			array( // #2
				self::$links,
				null,
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array_reverse( self::$links ), // $expectedLinks
			),
			array( // #3
				self::$links,
				null,
				array( 3, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe' ),
					array( 'll_from' =>  '2', 'll_lang' => 'en', 'll_title' => 'BarEn' ),
					array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe' ),
				),
			),
			array( // #4
				self::$links,
				null,
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				2, // $limit
				null, // $forLang
				null, // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe' ),
					array( 'll_from' =>  '2', 'll_lang' => 'en', 'll_title' => 'BarEn' ),
				),
			),
			array( // #5
				self::$links,
				null,
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				2, // $limit
				null, // $forLang
				null, // $forTitle
				2, // $continueFrom
				'de', // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe' ),
					array( 'll_from' =>  '1', 'll_lang' => 'fr', 'll_title' => 'FooFr' ),
				),
			),
			array( // #6
				self::$links,
				null,
				array( 3, 1, 2 ), // $fromPageIds
				'ascending', // $dir
				2, // $limit
				null, // $forLang
				null, // $forTitle
				3, // $continueFrom
				'de', // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe' ),
				),
			),
			array( // #7
				self::$links,
				null,
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				'de', // $forLang
				null, // $forTitle
				1, // $continueFrom
				'de', // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '1', 'll_lang' => 'de', 'll_title' => 'FooDe' ),
				),
			),
			array( // #8
				self::$links,
				null,
				array( 3, 1, 2 ), // $fromPageIds
				'descending', // $dir
				10, // $limit
				'de', // $forLang
				'BarDe', // $forTitle
				null, // $continueFrom
				null, // $continueLang
				array( // $expectedLinks
					array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe' ),
				),
			),
		);
	}

	public static function doNothing( $title, &$links, &$flags ) {
		return true;
	}

	public static function doSomething( $title, &$links, &$flags ) {
		if ( !$links ) {
			$links['xx'] = "xx:Special";
			$flags["xx:Special"] = array( 'special' );
			return true;
		}

		$first = reset($links);
		list( $from, $title ) = explode( ':', $first, 2 );
		$base = substr( $title, 0, 3 );

		$links = array_filter(
			$links,
			function ( $link ) {
				return !preg_match( '/^en:/', $link );
			}
		);

		$zzLink = 'zz:' . $base . 'Zz';
		$aaLink = 'aa:' . $base . 'Aa';

		$links['zz'] = $zzLink;
		$links['aa'] = $aaLink;

		$flags[$aaLink] = array( 'aaFlag' );
		return true;
	}

	public static function provideLoadLanguageLinksEmptyHook() {
		$cases = self::provideLoadLanguageLinksNoHook();
		$casesWithEmptyHook = array();

		foreach ( $cases as $case ) {
			$case[1] = 'HookedLangLinkLoaderTest::doNothing';

			foreach( $case[9] as $i => $link ) {
				$case[9][$i]['ll_flags'] = array();
			}

			$casesWithEmptyHook[] = $case;
		}

		return $casesWithEmptyHook;
	}

	public static function provideLoadLanguageLinksWithHook() {
		$casesWithNoHook = self::provideLoadLanguageLinksEmptyHook();
		$casesWithHook = array();

		foreach ( $casesWithNoHook as $case ) {
			$case[1] = 'HookedLangLinkLoaderTest::doSomething';
			$casesWithHook[] = $case;
		}

		$casesWithHook[1][9] = array(
			array( 'll_from' =>  '1', 'll_lang' => 'aa', 'll_title' => 'FooAa', 'll_flags' => array( 'aaFlag' ) ),
			array( 'll_from' =>  '1', 'll_lang' => 'de', 'll_title' => 'FooDe', 'll_flags' => array() ),
			array( 'll_from' =>  '1', 'll_lang' => 'fr', 'll_title' => 'FooFr', 'll_flags' => array() ),
			array( 'll_from' =>  '1', 'll_lang' => 'zz', 'll_title' => 'FooZz', 'll_flags' => array() ),
			array( 'll_from' =>  '2', 'll_lang' => 'aa', 'll_title' => 'BarAa', 'll_flags' => array( 'aaFlag' ) ),
			array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe', 'll_flags' => array() ),
			array( 'll_from' =>  '2', 'll_lang' => 'zz', 'll_title' => 'BarZz', 'll_flags' => array() ),
			array( 'll_from' =>  '3', 'll_lang' => 'aa', 'll_title' => 'MehAa', 'll_flags' => array( 'aaFlag' ) ),
			array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe', 'll_flags' => array() ),
			array( 'll_from' =>  '3', 'll_lang' => 'zz', 'll_title' => 'MehZz', 'll_flags' => array() ),
		);

		$casesWithHook[2][9] = array_reverse( $casesWithHook[1][9] );

		$casesWithHook[3][9] = array(
			array( 'll_from' =>  '3', 'll_lang' => 'zz', 'll_title' => 'MehZz', 'll_flags' => array() ),
			array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe', 'll_flags' => array() ),
			array( 'll_from' =>  '3', 'll_lang' => 'aa', 'll_title' => 'MehAa', 'll_flags' => array( 'aaFlag' ) ),
			array( 'll_from' =>  '2', 'll_lang' => 'zz', 'll_title' => 'BarZz', 'll_flags' => array() ),
			array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe', 'll_flags' => array() ),
			array( 'll_from' =>  '2', 'll_lang' => 'aa', 'll_title' => 'BarAa', 'll_flags' => array( 'aaFlag' ) ),
		);

		$casesWithHook[4][9] = array(
			array( 'll_from' =>  '3', 'll_lang' => 'zz', 'll_title' => 'MehZz', 'll_flags' => array() ),
			array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe', 'll_flags' => array() ),
		);

		$casesWithHook[5][9] = array(
			array( 'll_from' =>  '2', 'll_lang' => 'de', 'll_title' => 'BarDe', 'll_flags' => array() ),
			array( 'll_from' =>  '2', 'll_lang' => 'aa', 'll_title' => 'BarAa', 'll_flags' => array( 'aaFlag' ) ),
		);

		$casesWithHook[6][9] = array(
			array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe', 'll_flags' => array() ),
			array( 'll_from' =>  '3', 'll_lang' => 'zz', 'll_title' => 'MehZz', 'll_flags' => array() ),
		);

		$casesWithHook[9] = array(
			self::$links,
			'HookedLangLinkLoaderTest::doSomething',
			array( 3, 1, 2 ), // $fromPageIds
			'descending', // $dir
			10, // $limit
			'zz', // $forLang
			'BarZz', // $forTitle
			null, // $continueFrom
			null, // $continueLang
			array( // $expectedLinks
				array( 'll_from' =>  '2', 'll_lang' => 'zz', 'll_title' => 'BarZz', 'll_flags' => array() ),
			),
		);

		$casesWithHook[10] = array(
			self::$links,
			'HookedLangLinkLoaderTest::doSomething',
			array( 8 ), // $fromPageIds
			'ascending', // $dir
			10, // $limit
			null, // $forLang
			null, // $forTitle
			null, // $continueFrom
			null, // $continueLang
			array( // $expectedLinks
				array( 'll_from' =>  '8', 'll_lang' => 'xx', 'll_title' => 'Special', 'll_flags' => array( 'special' ) ),
			),
		);

		$casesWithHook[11] = array(
			self::$links,
			'HookedLangLinkLoaderTest::doSomething',
			array( 3, 1, 2, 8 ), // $fromPageIds
			'ascending', // $dir
			10, // $limit
			null, // $forLang
			null, // $forTitle
			2, // $continueFrom
			'zz', // $continueLang
			array( // $expectedLinks
				array( 'll_from' =>  '2', 'll_lang' => 'zz', 'll_title' => 'BarZz', 'll_flags' => array() ),
				array( 'll_from' =>  '3', 'll_lang' => 'aa', 'll_title' => 'MehAa', 'll_flags' => array( 'aaFlag' ) ),
				array( 'll_from' =>  '3', 'll_lang' => 'de', 'll_title' => 'MehDe', 'll_flags' => array() ),
				array( 'll_from' =>  '3', 'll_lang' => 'zz', 'll_title' => 'MehZz', 'll_flags' => array() ),
				array( 'll_from' =>  '8', 'll_lang' => 'xx', 'll_title' => 'Special', 'll_flags' => array( 'special' ) ),
			),
		);

		return $casesWithHook;
	}

	/**
	 * @dataProvider provideLoadLanguageLinksNoHook
	 */
	public function testLoadLanguageLinksMock(
		$baseLinks,
		$hookFunction,
		$fromPageIds,
		$dir,
		$limit,
		$forLang,
		$forTitle,
		$continueFrom,
		$continueLang,
		$expectedLinks
	) {
		if ( $hookFunction !== null ) {
			// ignore this
			$this->assertTrue( true );
			return;
		}

		$loader = new HookedLangLinkLoaderTestMockLoader( $baseLinks );

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

	/**
	 * @dataProvider provideLoadLanguageLinksNoHook
	 */
	public function testLoadLanguageLinksNoHook(
		$baseLinks,
		$hookFunction,
		$fromPageIds,
		$dir,
		$limit,
		$forLang,
		$forTitle,
		$continueFrom,
		$continueLang,
		$expectedLinks
	) {
		$this->callLoadLanguageLinks(
			$baseLinks,
			$hookFunction,
			$fromPageIds,
			$dir,
			$limit,
			$forLang,
			$forTitle,
			$continueFrom,
			$continueLang,
			$expectedLinks
		);
	}

	/**
	 * @dataProvider provideLoadLanguageLinksEmptyHook
	 */
	public function testLoadLanguageLinksEmptyHook(
		$baseLinks,
		$hookFunction,
		$fromPageIds,
		$dir,
		$limit,
		$forLang,
		$forTitle,
		$continueFrom,
		$continueLang,
		$expectedLinks
	) {
		$this->callLoadLanguageLinks(
			$baseLinks,
			$hookFunction,
			$fromPageIds,
			$dir,
			$limit,
			$forLang,
			$forTitle,
			$continueFrom,
			$continueLang,
			$expectedLinks
		);
	}

	/**
	 * @dataProvider provideLoadLanguageLinksWithHook
	 */
	public function testLoadLanguageLinksWithHook(
		$baseLinks,
		$hookFunction,
		$fromPageIds,
		$dir,
		$limit,
		$forLang,
		$forTitle,
		$continueFrom,
		$continueLang,
		$expectedLinks
	) {
		$this->callLoadLanguageLinks(
			$baseLinks,
			$hookFunction,
			$fromPageIds,
			$dir,
			$limit,
			$forLang,
			$forTitle,
			$continueFrom,
			$continueLang,
			$expectedLinks
		);
	}

	protected function callLoadLanguageLinks(
		$baseLinks,
		$hookFunction,
		$fromPageIds,
		$dir,
		$limit,
		$forLang,
		$forTitle,
		$continueFrom,
		$continueLang,
		$expectedLinks
	) {
		$loader = new HookedLangLinkLoader(
			new HookedLangLinkLoaderTestMockLoader( $baseLinks ),
			'HookedLangLinkLoaderTest'
		);

		if ( $hookFunction !== null ) {
			Hooks::register( 'HookedLangLinkLoaderTest', $hookFunction );
		}

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

		Hooks::clear( 'HookedLangLinkLoaderTest' );
	}

	protected function rowsToArrays( $links ) {
		$arrays = array_map(
			'get_object_vars',
			$links
		);

		return $arrays;
	}
}

class HookedLangLinkLoaderTestMockLoader implements LangLinkLoader {

	protected $links;

	/**
	 * @param array[]|object[] $links A list if links given as row objects or arrays
	 */
	public function __construct( array $links ) {
		foreach ( $links as $i => $link ) {
			if ( is_array( $link ) ) {
				$obj = new stdClass();
				$obj->ll_from = $link['ll_from'];
				$obj->ll_lang = $link['ll_lang'];
				$obj->ll_title = $link['ll_title'];

				$links[$i] = $obj;
			}
		}

		$this->links = $links;
	}

	/**
	 * Returns links that match the given parameters.
	 *
	 * @return object[] a list of stdClass objects.
	 */
	public function loadLanguageLinks(
		$fromPageIds,
		$dir,
		$limit = null,
		$forLang = null,
		$forTitle = null,
		$continueFrom = null,
		$continueLang = null
	) {
		$filteredLinks = array();

		$links = $this->links;

		usort(
			$links,
			function ( $a, $b ) use ( $dir ) {
				if ( $dir === 'descending' ) {
					$x = $a;
					$a = $b;
					$b = $x;
				}

				if ( $a->ll_from < $b->ll_from ) return -1;
				elseif ( $a->ll_from > $b->ll_from ) return +1;
				elseif ( $a->ll_lang < $b->ll_lang ) return -1;
				elseif ( $a->ll_lang > $b->ll_lang ) return +1;
				else return 0;
			}
		);

		foreach ( $links as $row ) {
			if ( $limit && count( $filteredLinks ) >= $limit ) {
				break;
			}

			if ( !in_array( $row->ll_from, $fromPageIds ) ) {
				continue;
			}

			if ( $continueFrom !== null ) {
				if ( ( $dir == 'ascending' && $row->ll_from < $continueFrom )
					|| ( $dir == 'descending' && $row->ll_from > $continueFrom ) ) {
					// not at continuation point yet
					continue;
				}
			}

			if ( $continueFrom !== null
				&& intval( $row->ll_from ) === intval( $continueFrom )
				&& $continueLang !== null
			) {
				if ( ( $dir == 'ascending' && $row->ll_lang < $continueLang )
					|| ( $dir == 'descending' && $row->ll_lang > $continueLang ) ) {
					// not at continuation point yet
					continue;
				} else {
					// stop checking
					$continueFrom = null;
					$continueLang = null;
				}
			}

			if ( $forLang !== null ) {
				if ( $row->ll_lang !== $forLang ) {
					// wrong language
					continue;
				}

				if ( $forTitle !== null && $row->ll_title !== $forTitle ) {
					// wrong title
					continue;
				}
			}

			$filteredLinks[] = $row;
		}

		return $filteredLinks;
	}
}