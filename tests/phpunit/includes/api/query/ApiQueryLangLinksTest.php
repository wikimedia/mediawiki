<?php

/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryLangLinksTest extends ApiTestCase {

	protected function initIniterwiki() {
		// ugly hack to provide interwiki prefixes

		$row = array(
			'iw_prefix' => 'de',
			'iw_url' => 'http://acme.test/$1',
			'iw_api' => '',
			'iw_wikiid' => '',
			'iw_local' => 1,
			'iw_trans' => 0,
		);

		$db = wfGetDB( DB_MASTER );

		$db->insert( 'interwiki', $row, __FUNCTION__, array( 'IGNORE' ) );

		$row['iw_prefix'] = 'en';
		$db->insert( 'interwiki', $row, __FUNCTION__, array( 'IGNORE' ) );

		$row['iw_prefix'] = 'fr';
		$db->insert( 'interwiki', $row, __FUNCTION__, array( 'IGNORE' ) );

		$db->commit( __METHOD__, 'flush' );
	}

	/**
	 * @return TestPageSet
	 * @throws MWException
	 */
	protected function getTestPages() {
		static $pages = null;

		if ( $pages === null ) {
			$this->initIniterwiki();

			$pages = new TestPageSet( $this->getDefaultWikitextNS(), basename( __CLASS__ ) );

			$pages->createTestPage( 'Foo', new WikitextContent(
				'[[de:FooDe]][[en:FooEn]][[fr:FooFr]]'
			) );

			$pages->createTestPage( 'Bar', new WikitextContent(
				'[[de:BarDe]][[en:BarEn]]'
			) );

			$pages->createTestPage( 'Meh', new WikitextContent(
				'[[de:MehDe]]'
			) );
		}

		return $pages;
	}

	/**
	 * Extracts language links from an API result, grouped by page.
	 *
	 * @param array $apiResult
	 *
	 * @return array[][] an array associating page ids with lists of language links,
	 *         each of which is again an array.
	 */
	protected function extractLangLinks( $apiResult ) {
		$links = array();

		foreach ( $apiResult['query']['pages'] as $id => $page ) {
			$links[$id] = array();

			if ( isset( $page['langlinks'] ) ) {
				foreach ( $page['langlinks'] as $link ) {
					$links[$id][] = $link;
				}
			}
		}

		return $links;
	}

	/**
	 * Merges two array structures that represent language links, grouped by page.
	 *
	 * @param array[][] $base an array associating page ids with lists of language links,
	 *         each of which is again an array.
	 * @param array[][] $add an array associating page ids with lists of language links,
	 *         each of which is again an array.
	 *
	 * @return array[][] an array associating page ids with lists of language links,
	 *         each of which is again an array.
	 */
	protected function mergeLangLinks( $base, $add ) {
		foreach ( $add as $pageId => $links ) {
			if ( empty( $base[$pageId] ) ) {
				$base[$pageId] = $links;
			} else {
				$base[$pageId] = array_merge( $base[$pageId], $links );
			}
		}

		return $base;
	}

	public static function provideQuery() {
		// things to test:
		// - simple get, with limit
		// - multi-title query
		// - multi-id query
		// - filtering by target language and title
		// - direction
		// - paging (with direction)
		// - with/without url
		// - unknown page

		return array(
			array( // #0: get all links from a single page
				array( // params
					'titles' => 'Foo',
					//'pageids' => '',
					//'llcontinue' => ''
					'lllimit' => '5',
					//'lltitle' => '',
					//'lllang' => '',
					//'lldir' => '',
					//'llurl' => '',
				),
				array( // expected links
					'Foo' => array(
						array( 'lang' => 'de', '*' => 'FooDe' ),
						array( 'lang' => 'en', '*' => 'FooEn' ),
						array( 'lang' => 'fr', '*' => 'FooFr' ),
					),
				),
				'get all lang links from one page by title'
			),
			array( // #1: request links from a missing page
				array( // params
					'titles' => 'Quux', // will be expanded to full titles automatically
					//'pageids' => '',
					//'llcontinue' => ''
					//'lllimit' => '5',
					//'lltitle' => '',
					//'lllang' => '',
					//'lldir' => '',
					//'llurl' => '',
				),
				array( // expected links
					'Quux' => array(),
				),
				'request links from a non-existing page'
			),
			array( // #2: get links from multiple pages using IDs, with paging
				array( // params
					//'titles' => '',
					'pageids' => 'Foo|Bar|Meh', // will be translated to IDs automatically
					//'llcontinue' => ''
					'lllimit' => '2',
					//'lltitle' => '',
					//'lllang' => '',
					'lldir' => 'ascending',
					//'llurl' => '',
				),
				array( // expected links
					'Foo' => array(
						array( 'lang' => 'de', '*' => 'FooDe' ),
						array( 'lang' => 'en', '*' => 'FooEn' ),
						array( 'lang' => 'fr', '*' => 'FooFr' ),
					),
					'Bar' => array(
						array( 'lang' => 'de', '*' => 'BarDe' ),
						array( 'lang' => 'en', '*' => 'BarEn' ),
					),
					'Meh' => array(
						array( 'lang' => 'de', '*' => 'MehDe' ),
					),
				),
				'multi-id query with paging'
			),
			array( // #3: get links from multiple pages using IDs, with paging
				array( // params
					'titles' => 'Foo|Bar|Meh', // will be translated to full titles automatically
					//'pageids' => ''
					//'llcontinue' => ''
					'lllimit' => '2',
					//'lltitle' => '',
					//'lllang' => '',
					'lldir' => 'descending',
					//'llurl' => '',
				),
				array( // expected links
					'Meh' => array(
						array( 'lang' => 'de', '*' => 'MehDe' ),
					),
					'Bar' => array(
						array( 'lang' => 'en', '*' => 'BarEn' ),
						array( 'lang' => 'de', '*' => 'BarDe' ),
					),
					'Foo' => array(
						array( 'lang' => 'fr', '*' => 'FooFr' ),
						array( 'lang' => 'en', '*' => 'FooEn' ),
						array( 'lang' => 'de', '*' => 'FooDe' ),
					),
				),
				'multi-title query with reverse paging'
			),
			array( // #4: filter links by language
				array( // params
					'titles' => 'Foo|Bar|Meh', // will be translated to full titles automatically
					//'pageids' => ''
					//'llcontinue' => ''
					'lllimit' => '2',
					//'lltitle' => '',
					'lllang' => 'en',
					//'lldir' => '',
					//'llurl' => '',
				),
				array( // expected links
					'Foo' => array(
						array( 'lang' => 'en', '*' => 'FooEn' ),
					),
					'Bar' => array(
						array( 'lang' => 'en', '*' => 'BarEn' ),
					),
					'Meh' => array(),
				),
				'filter links by language'
			),
			array( // #5: filter links by language and title
				array( // params
					'titles' => 'Foo|Bar|Meh', // will be translated to full titles automatically
					//'pageids' => ''
					//'llcontinue' => ''
					'lllimit' => '2',
					'lltitle' => 'BarEn',
					'lllang' => 'en',
					'lldir' => 'descending',
					'llurl' => '',
				),
				array( // expected links
					'Foo' => array(),
					'Bar' => array(
						array( 'lang' => 'en', '*' => 'BarEn', 'url' => 'http://acme.test/BarEn' ),
					),
					'Meh' => array(),
				),
				'filter links by language and title'
			),
		);
	}

	/**
	 * @group medium
	 * @dataProvider provideQuery
	 */
	function testQuery( $params, $expected, $info ) {
		$pages = $this->getTestPages();

		$result = array();
		$continue = null;

		$params['action'] = 'query';
		$params['prop'] = 'langlinks';

		if ( isset($params['titles']) ) {
			// titles are given as handles, convert
			$params['titles'] = implode( '|', $pages->handlesToTitles( explode( '|', $params['titles'] ) ) );
		}

		if ( isset($params['pageids']) ) {
			// ids are given as handles, convert
			$params['pageids'] = implode( '|', $pages->handlesToIds( explode( '|', $params['pageids'] ) ) );
		}

		do { // continuation loop
			// perform request
			list( $response, , ) = $this->doApiRequest( $params );

			$this->assertArrayHasKey( 'query', $response );
			$this->assertArrayHasKey( 'pages', $response['query'] );

			// check continuation
			if ( isset( $response['query-continue']['langlinks']['llcontinue'] ) ) {
				$params['llcontinue'] = $response['query-continue']['langlinks']['llcontinue'];
			} else {
				$params['llcontinue'] = null;
			}

			// collect links
			$langlinks = $this->extractLangLinks( $response );
			$result = $this->mergeLangLinks( $result, $langlinks );
		} while ( $params['llcontinue'] !== null );

		$missingId = 0;
		foreach ( $expected as $page => $links ) {
			$pageId = $pages->getTitle( $page )->getArticleID();

			if ( $pageId === 0 ) {
				// if the page is missing, the API will count down negative IDs.
				$pageId = --$missingId;
			}

			$this->assertArrayHasKey( $pageId, $result, "missing result for page `$page` (ID $pageId)" );

			$this->assertLinkListsEqual( $links, $result[$pageId], "$info\nPage `$page`"  );
		}

		$this->assertSameSize( $expected, $result, "Result must have the expected number of entries" );
	}

	protected function assertLinkListsEqual( $expected, $actual, $info ) {
		$acount = count( $actual );
		$ecount = count( $expected );

		// loop over result and compare with expected
		$i = 0;
		while ( true ) {
			if ( $i >= $acount && $i >= $ecount ) {
				break; // done
			}

			if ( $i >= $ecount ) {
				$this->fail( "$info\nUnexpected link in result at position $i: "
					. self::link2string( $actual[$i] ) );
			}

			$expectedLink = $expected[$i];

			if ( $i >= $acount ) {
				$this->fail( "$info\nNo more links in the result at position $i, expected "
					. self::link2string( $expectedLink ) );
			}

			$this->assertLinkEquals( $expectedLink, $actual[$i], "$info\nPosition $i" );

			$i++;
		}
	}

	protected static function link2string( $link ) {
		return $link['lang'] . ':' . $link['*'];
	}

	protected function assertLinkEquals( $expected, $actual, $info ) {
		$fields = array_merge( array_keys( (array)$expected ), array_keys( (array)$actual ) );

		foreach ( $fields as $field ) {
			$this->assertFieldEquals( $field, $expected, $actual, $info );
		}
	}

	protected function assertFieldEquals( $field, $expected, $actual, $info ) {
		if ( isset( $expected[$field] ) && !isset( $actual[$field] ) ) {
			$this->fail( "$info\nMissing field `$field`." );
		}

		if ( !isset( $expected[$field] ) && isset( $actual[$field] ) ) {
			$this->fail( "$info\nExtra field `$field`." );
		}

		if ( isset( $expected[$field] ) && isset( $actual[$field] ) ) {
			$this->assertEquals( $expected[$field], $actual[$field], "$info\nField `$field`" );
		}
	}
}
