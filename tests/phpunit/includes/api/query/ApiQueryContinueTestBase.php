<?php
/**
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
 */
abstract class ApiQueryContinueTestBase extends ApiQueryTestBase {

	/**
	 * Enable to print in-depth debugging info during the test run
	 */
	protected $mVerbose = false;

	/**
	 * Run query() and compare against expected values
	 * @param array $expected
	 * @param array $params Api parameters
	 * @param int $expectedCount Max number of iterations
	 * @param string $id Unit test id
	 * @param bool $continue True to use smart continue
	 * @return array Merged results data array
	 */
	protected function checkC( $expected, $params, $expectedCount, $id, $continue = true ) {
		$result = $this->query( $params, $expectedCount, $id, $continue );
		$this->assertResult( $expected, $result, $id );
	}

	/**
	 * Run query in a loop until no more values are available
	 * @param array $params Api parameters
	 * @param int $expectedCount Max number of iterations
	 * @param string $id Unit test id
	 * @param bool $useContinue True to use smart continue
	 * @return array Merged results data array
	 * @throws Exception
	 */
	protected function query( $params, $expectedCount, $id, $useContinue = true ) {
		if ( isset( $params['action'] ) ) {
			$this->assertEquals( 'query', $params['action'], 'Invalid query action' );
		} else {
			$params['action'] = 'query';
		}
		$count = 0;
		$result = [];
		$continue = [];
		do {
			$request = array_merge( $params, $continue );
			uksort( $request, function ( $a, $b ) {
				// put 'continue' params at the end - lazy method
				$a = strpos( $a, 'continue' ) !== false ? 'zzz ' . $a : $a;
				$b = strpos( $b, 'continue' ) !== false ? 'zzz ' . $b : $b;

				return strcmp( $a, $b );
			} );
			$reqStr = http_build_query( $request );
			// $reqStr = str_replace( '&', ' & ', $reqStr );
			$this->assertLessThan( $expectedCount, $count, "$id more data: $reqStr" );
			if ( $this->mVerbose ) {
				print "$id (#$count): $reqStr\n";
			}
			try {
				$data = $this->doApiRequest( $request );
			} catch ( Exception $e ) {
				throw new Exception( "$id on $count", 0, $e );
			}
			$data = $data[0];
			if ( isset( $data['warnings'] ) ) {
				$warnings = json_encode( $data['warnings'] );
				$this->fail( "$id Warnings on #$count in $reqStr\n$warnings" );
			}
			$this->assertArrayHasKey( 'query', $data, "$id no 'query' on #$count in $reqStr" );
			if ( isset( $data['continue'] ) ) {
				$continue = $data['continue'];
				unset( $data['continue'] );
			} else {
				$continue = [];
			}
			if ( $this->mVerbose ) {
				$this->printResult( $data );
			}
			$this->mergeResult( $result, $data );
			$count++;
			if ( empty( $continue ) ) {
				$this->assertEquals( $expectedCount, $count, "$id finished early" );

				return $result;
			} elseif ( !$useContinue ) {
				$this->assertFalse( 'Non-smart query must be requested all at once' );
			}
		} while ( true );
	}

	/**
	 * @param array $data
	 */
	private function printResult( $data ) {
		$q = $data['query'];
		$print = [];
		if ( isset( $q['pages'] ) ) {
			foreach ( $q['pages'] as $p ) {
				$m = $p['title'];
				if ( isset( $p['links'] ) ) {
					$m .= '/[' . implode( ',', array_map(
						function ( $v ) {
							return $v['title'];
						},
						$p['links'] ) ) . ']';
				}
				if ( isset( $p['categories'] ) ) {
					$m .= '/(' . implode( ',', array_map(
						function ( $v ) {
							return str_replace( 'Category:', '', $v['title'] );
						},
						$p['categories'] ) ) . ')';
				}
				$print[] = $m;
			}
		}
		if ( isset( $q['allcategories'] ) ) {
			$print[] = '*Cats/(' . implode( ',', array_map(
				function ( $v ) {
					return $v['*'];
				},
				$q['allcategories'] ) ) . ')';
		}
		self::GetItems( $q, 'allpages', 'Pages', $print );
		self::GetItems( $q, 'alllinks', 'Links', $print );
		self::GetItems( $q, 'alltransclusions', 'Trnscl', $print );
		print ' ' . implode( '  ', $print ) . "\n";
	}

	private static function GetItems( $q, $moduleName, $name, &$print ) {
		if ( isset( $q[$moduleName] ) ) {
			$print[] = "*$name/[" . implode( ',',
				array_map(
					function ( $v ) {
						return $v['title'];
					},
					$q[$moduleName] ) ) . ']';
		}
	}

	/**
	 * Recursively merge the new result returned from the query to the previous results.
	 * @param mixed $results
	 * @param mixed $newResult
	 * @param bool $numericIds If true, treat keys as ids to be merged instead of appending
	 */
	protected function mergeResult( &$results, $newResult, $numericIds = false ) {
		$this->assertEquals(
			is_array( $results ),
			is_array( $newResult ),
			'Type of result and data do not match'
		);
		if ( !is_array( $results ) ) {
			$this->assertEquals( $results, $newResult, 'Repeated result must be the same as before' );
		} else {
			$sort = null;
			foreach ( $newResult as $key => $value ) {
				if ( !$numericIds && $sort === null ) {
					if ( !is_array( $value ) ) {
						$sort = false;
					} elseif ( array_key_exists( 'title', $value ) ) {
						$sort = function ( $a, $b ) {
							return strcmp( $a['title'], $b['title'] );
						};
					} else {
						$sort = false;
					}
				}
				$keyExists = array_key_exists( $key, $results );
				if ( is_numeric( $key ) ) {
					if ( $numericIds ) {
						if ( !$keyExists ) {
							$results[$key] = $value;
						} else {
							$this->mergeResult( $results[$key], $value );
						}
					} else {
						$results[] = $value;
					}
				} elseif ( !$keyExists ) {
					$results[$key] = $value;
				} else {
					$this->mergeResult( $results[$key], $value, $key === 'pages' );
				}
			}
			if ( $numericIds ) {
				ksort( $results, SORT_NUMERIC );
			} elseif ( $sort !== null && $sort !== false ) {
				usort( $results, $sort );
			}
		}
	}
}
