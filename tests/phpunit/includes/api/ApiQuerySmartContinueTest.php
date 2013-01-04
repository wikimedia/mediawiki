<?php
/**
 *
 *
 * Created on Jan 1, 2013
 *
 * Copyright © 2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * @file
 *
 * These tests validate SmartContinue functionality of the api query module by
 * doing multiple requests with varying parameters, merging the results, and checking
 * that the result matches the full data received in one no-limits call.
 *
 * @group API
 * @group Database
 */
class ApiQuerySmartContinueTest extends ApiTestCase {

	/**
	 * Enable to print in-depth debugging info during the test run
	 */
	private $mVerbose = false;

	/**
	 * Edits or creates a page
	 */
	function editPage( $pageName, $text ) {
		$title = Title::newFromText( $pageName, NS_MAIN );
		$page = WikiPage::factory( $title );
		$page->doEditContent( ContentHandler::makeContent( $text, $title ), '' );
	}

	/**
	 * Create a set of pages. These must not change, otherwise the tests might give wrong results.
	 * @see MediaWikiTestCase::addDBData()
	 */
	function addDBData() {
		try {
			$this->editPage( 'Page1', '**Page1** [[Category:Cat2]] [[Category:Cat3]] [[Category:Cat4]] [[Category:Cat5]]' );
			$this->editPage( 'Page2', '[[Page1]] **Page2** [[Category:Cat3]] [[Category:Cat4]] [[Category:Cat5]]' );
			$this->editPage( 'Page3', '[[Page1]] [[Page2]] **Page3** [[Category:Cat4]] [[Category:Cat5]]' );
			$this->editPage( 'Page4', '[[Page1]] [[Page2]] [[Page3]] **Page4** [[Category:Cat5]]' );
			$this->editPage( 'Page5', '[[Page1]] [[Page2]] [[Page3]] [[Page4]] **Page5**' );
		} catch ( Exception $e ) {
			$this->exceptionFromAddDBData = $e;
		}
	}

	/**
	 * Test smart continue - list=allpages
	 */
	public function testOneList() {
		$mk = function( $l )  { return array( 'list' => 'allpages', 'aplimit' => "$l" ); };
		$data = $this->query( $mk(99), 1, '1l', false );

		// 1 list, no continue
		$this->check( $data, $mk(1), 5, '1lNC-1', false );
		$this->check( $data, $mk(2), 3, '1lNC-2', false );
		$this->check( $data, $mk(3), 2, '1lNC-3', false );
		$this->check( $data, $mk(4), 2, '1lNC-4', false );
		$this->check( $data, $mk(5), 1, '1lNC-5', false );

		// 1 list
		$this->check( $data, $mk(1), 5, '1l-1', true );
		$this->check( $data, $mk(2), 3, '1l-2', true );
		$this->check( $data, $mk(3), 2, '1l-3', true );
		$this->check( $data, $mk(4), 2, '1l-4', true );
		$this->check( $data, $mk(5), 1, '1l-5', true );
	}

	/**
	 * Test smart continue - list=allpages|allcategories
	 */
	public function testTwoLists() {
		$mk = function( $l1, $l2 ) {
			return array(
					'list' => 'allpages|allcategories',
					'aplimit' => "$l1",
					'calimit' => "$l2",
			 );
		};
		// 2 lists
		$data = $this->query( $mk(99,99), 1, '2l', false );
		$this->check( $data, $mk(1,1), 5, '2l-11', true );
		$this->check( $data, $mk(2,2), 3, '2l-22', true );
		$this->check( $data, $mk(3,3), 2, '2l-33', true );
		$this->check( $data, $mk(4,4), 2, '2l-44', true );
		$this->check( $data, $mk(5,5), 1, '2l-55', true );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links
	 */
	public function testGen1Prop() {
		$mk = function( $g, $p ) {
			return array(
					'generator' => 'allpages',
					'gaplimit' => "$g",
					'prop' => 'links',
					'pllimit' => "$p",
			);
		};
		// generator + 1 prop
		$data = $this->query( $mk(99,99), 1, 'g1p', false );
		$this->check( $data, $mk(1,1), 11, 'g1p-11', true );
		$this->check( $data, $mk(2,2), 6, 'g1p-22', true );
		$this->check( $data, $mk(3,3), 4, 'g1p-33', true );
		$this->check( $data, $mk(4,4), 3, 'g1p-44', true );
		$this->check( $data, $mk(5,5), 2, 'g1p-55', true );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links|categories
	 */
	public function testGen2Prop() {
		$mk = function( $g, $p1, $p2 ) {
			return array(
					'generator' => 'allpages',
					'gaplimit' => "$g",
					'prop' => 'links|categories',
					'pllimit' => "$p1",
					'cllimit' => "$p2",
			);
		};
		// generator + 2 props
		$data = $this->query( $mk(99,99,99), 1, 'g2p', false );
		$this->check( $data, $mk(1,1,1), 16, 'g2p-111', true );
		$this->check( $data, $mk(2,2,2), 9, 'g2p-222', true );
		$this->check( $data, $mk(3,3,3), 6, 'g2p-333', true );
		$this->check( $data, $mk(4,4,4), 4, 'g2p-444', true );
		$this->check( $data, $mk(5,5,5), 2, 'g2p-555', true );
		$this->check( $data, $mk(5,1,1), 10, 'g2p-511', true );
		$this->check( $data, $mk(4,2,2), 7, 'g2pA-422', true );
		$this->check( $data, $mk(2,3,3), 7, 'g2pA-233', true );
		$this->check( $data, $mk(2,4,4), 5, 'g2pA-244', true );
		$this->check( $data, $mk(1,5,5), 5, 'g2pA-155', true );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links, list=allcategories
	 */
	public function testGen1Prop1List() {
		$mk = function( $g, $p, $l ) {
			return array(
					'generator' => 'allpages',
					'gaplimit' => "$g",
					'prop' => 'links',
					'pllimit' => "$p",
					'list' => 'allcategories',
					'limit' => "$l",
			);
		};
		// generator + 1 prop + 1 list
		$data = $this->query( $mk(99,99,99), 1, 'g1p1l', false );
		$this->check( $data, $mk(1,1,1), 11, 'g1p1l-111', true );
		$this->check( $data, $mk(2,2,2), 6, 'g1p1l-222', true );
		$this->check( $data, $mk(3,3,3), 4, 'g1p1l-333', true );
		$this->check( $data, $mk(4,4,4), 3, 'g1p1l-444', true );
		$this->check( $data, $mk(5,5,5), 2, 'g1p1l-555', true );
		$this->check( $data, $mk(5,5,1), 2, 'g1p1l-551', true );
		$this->check( $data, $mk(5,5,2), 2, 'g1p1l-552', true );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links|categories,
	 *                       list=alllinks|allcategories, meta=siteinfo
	 */
	public function testGen2Prop2List1Meta() {
		$mk = function( $g, $p1, $p2, $l1, $l2 ) {
			return array(
					'generator' => 'allpages',
					'gaplimit' => "$g",
					'prop' => 'links|categories',
					'pllimit' => "$p1",
					'calimit' => "$p2",
					'list' => 'alllinks|allcategories',
					'allimit' => "$l1",
					'aclimit' => "$l2",
					'meta' => 'siteinfo',
					'siprop' => 'namespaces',
			);
		};
		// generator + 1 prop + 1 list
		$data = $this->query( $mk(99,99,99,99,99), 1, 'g2p2lm', false );
		$this->check( $data, $mk(1,1,1,1,1), 11, 'g2p2lm-11111', true );
		$this->check( $data, $mk(2,2,2,2,2), 6, 'g2p2lm-22222', true );
		$this->check( $data, $mk(3,3,3,3,3), 4, 'g2p2lm-33333', true );
		$this->check( $data, $mk(4,4,4,4,4), 3, 'g2p2lm-44444', true );
		$this->check( $data, $mk(5,5,5,5,5), 2, 'g2p2lm-55555', true );
		$this->check( $data, $mk(5,5,5,1,1), 10, 'g2p2lm-55511', true );
		$this->check( $data, $mk(5,5,5,2,2), 5, 'g2p2lm-55522', true );
		$this->check( $data, $mk(5,1,1,5,5), 10, 'g2p2lm-51155', true );
		$this->check( $data, $mk(5,2,2,5,5), 5, 'g2p2lm-52255', true );
	}

	/**
	 * Run query() and compare against expected values
	 */
	private function check( $expected, $params, $expectedCount, $id, $continue = true  ) {
		$newData = $this->query( $params, $expectedCount, $id, $continue );
		if ( $expected != $newData ) {
			print_r( $expected );
			print_r( $newData );
			$this->assertEquals( $expected, $newData, $id );
		}
	}

	/**
	 * Run query in a loop until no more values are available
	 * @param array $params api parameters
	 * @param int $expectedCount max number of iterations
	 * @param string $id unit test id
	 * @param boolean $continue true to use smart continue
	 * @return mixed: merged results data array
	 */
	private function query( $params, $expectedCount, $id, $continue = true ) {
		if ( isset( $params['action'] ) ) {
			$this->assertEquals( 'query', $params['action'], 'Invalid query action');
		} else {
			$params['action'] = 'query';
		}
		if ( $continue && !isset( $params['continue'] ) ) {
			$params['continue'] = '';
		}
		$count = 0;
		$result = array();
		$req = http_build_query( $params );
		do {
			if ( $this->mVerbose ) {
				print ("$id: $req\n");
			}
			try {
				$data = $this->doApiRequest( $params );
			} catch ( Exception $e) {
				throw new Exception( "$id on $count", 0, $e );
			}
			$data = $data[0];
			$count++;
			if ( isset( $data['warnings'] ) ) {
				$this->assertFalse(
					isset( $data['warnings'] ),
					"$id Warnings on #$count in $req\n{$data['warnings']}" );
			}
			$this->assertArrayHasKey( 'query', $data, "$id no 'query' on #$count in $req" );
			if ( isset( $data['query-continue'] ) ) {
				$qc = $data['query-continue'];
				unset( $data['query-continue'] );
			} else {
				$qc = null;
			}
			if ( $this->mVerbose ) {
				$q = $data['query'];
				$print = array();
				if ( isset( $q['pages'] ) ) {
					foreach ( $q['pages'] as $p ) {
						$m = $p['title'];
						if ( isset( $p['links'] ) ) {
							$m .= '/[' . implode( ',', array_map(
									function( $v ) { return $v['title']; },
									$p['links'] ) ) . ']';
						}
						if ( isset( $p['categories'] ) ) {
							$m .= '/(' . implode( ',', array_map(
									function( $v ) {
										return str_replace( 'Category:','', $v['title'] );
									},
									$p['categories'] ) ) . ')';
						}
						$print[] = $m;
					}
				}
				if ( isset( $q['allcategories'] ) ) {
					$print[] = '*Cats/(' . implode( ',', array_map(
							function( $v ) { return $v['*']; },
							$q['allcategories'] ) ) . ')';
				}
				if ( isset( $q['alllinks'] ) ) {
					$print[] = '*Links/[' . implode( ',', array_map(
							function( $v ) { return $v['title']; },
							$q['alllinks'] ) ) . ']';
				}
				print( ' ' . implode( '  ', $print ) . "\n" );
			}
			$this->sortRecursive( $data );
			$result = array_merge_recursive( $result, $data );
			if ( is_null($qc) ) {
				// $this->assertEquals( $expectedCount, $count, "$id finished early" );
				if ( $expectedCount > $count ) {
					print "***** $id Finished early in $count turns. $expectedCount was expected\n";
				}
				$this->sortRecursive( $result );
				return $result;
			}
			if ( $continue ) {
				$params = array_merge( $params, $qc );
			} else {
				foreach ( $qc as $q ) {
					$params = array_merge( $params, $q );
				}
			}
			$req = http_build_query( $params );
			$this->assertLessThan( $expectedCount, $count, "$id more data: $req" );
		} while( true );
	}

	/**
	 * Sort and cleanup result array, removing any merge duplications
	 */
	function sortRecursive( &$array ) {
		if ( 0 === count( array_filter( array_keys($array), 'is_string' ) ) ) {
			if ( 0 === count( array_filter( $array, 'is_array' ) ) ) {
				// all array values are non arrays, check for duplicates and merge
				$unique = array_unique( $array );
				if ( 1 === count( $unique ) ) {
					$array = $unique[0];
					return;
				}
			} else {
				// Non-assoc array, replace keys with the sub strings like title
				$array = array_combine(
						array_map( array( 'ApiQuerySmartContinueTest', 'getSubValue' ), $array, array_keys( $array ) ),
						$array );
			}
		}
		uksort( $array,
			function ( $keyA, $keyB ) use ( $array ) {
				if ( is_int( $keyA ) && is_int( $keyB ) ) {
					return ApiQuerySmartContinueTest::cmp(
							ApiQuerySmartContinueTest::getSubValue( $array[$keyA] ),
							ApiQuerySmartContinueTest::getSubValue( $array[$keyB] ) );
				} else {
					return ApiQuerySmartContinueTest::cmp( $keyA, $keyB );
				}
			}
		);
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$this->sortRecursive( $value );
			}
		}
	}

	/**
	 * Helper to extract sub-element 'title' or '*' if available
	 */
	static function getSubValue( $val, $key = null ) {
		if ( is_array( $val ) ) {
			if ( isset( $val['title'] ) ) {
				return $val['title'];
			} elseif ( isset( $val['*'] ) ) {
				return $val['*'];
			}
		}
		return $key;
	}

	/**
	 * Helper to compare two values for sorting
	 */
	static function cmp( $valA, $valB ) {
		if ( is_null( $valA ) || is_null( $valB ) ) {
			if ( is_null( $valA ) && is_null( $valB ) )
				return 0;
			return is_null( $valA ) ? -1 : 1;
		}
		if ( $valA == $valB ) {
			return 0;
		} else {
			return $valA < $valB ? -1 : 1;
		}
	}
}
