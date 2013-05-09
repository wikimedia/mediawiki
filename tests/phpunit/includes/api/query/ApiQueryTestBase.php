<?php
/**
 *
 *
 * Created on Feb 10, 2013
 *
 * Copyright © 2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

/** This class has some common functionality for testing query module
 */
abstract class ApiQueryTestBase extends ApiTestCase {

	const PARAM_ASSERT = <<<STR
Each parameter must be an array of two elements,
first - an array of params to the API call,
and the second array - expected results as returned by the API
STR;

	/**
	 * Merges all requests parameter + expected values into one
	 * @param ... list of arrays, each of which contains exactly two
	 * @return array
	 */
	protected function merge( /*...*/ ) {
		$request = array();
		$expected = array();
		foreach ( func_get_args() as $v ) {
			list( $req, $exp ) = $this->validateRequestExpectedPair( $v );
			$request = array_merge_recursive( $request, $req );
			$this->mergeExpected( $expected, $exp );
		}

		return array( $request, $expected );
	}

	/**
	 * Check that the parameter is a valid two element array,
	 * with the first element being API request and the second - expected result
	 */
	private function validateRequestExpectedPair( $v ) {
		$this->assertType( 'array', $v, self::PARAM_ASSERT );
		$this->assertEquals( 2, count( $v ), self::PARAM_ASSERT );
		$this->assertArrayHasKey( 0, $v, self::PARAM_ASSERT );
		$this->assertArrayHasKey( 1, $v, self::PARAM_ASSERT );
		$this->assertType( 'array', $v[0], self::PARAM_ASSERT );
		$this->assertType( 'array', $v[1], self::PARAM_ASSERT );

		return $v;
	}

	/**
	 * Recursively merges the expected values in the $item into the $all
	 */
	private function mergeExpected( &$all, $item ) {
		foreach ( $item as $k => $v ) {
			if ( array_key_exists( $k, $all ) ) {
				if ( is_array( $all[$k] ) ) {
					$this->mergeExpected( $all[$k], $v );
				} else {
					$this->assertEquals( $all[$k], $v );
				}
			} else {
				$all[$k] = $v;
			}
		}
	}

	/**
	 * Checks that the request's result matches the expected results.
	 * @param $values array is a two element array( request, expected_results )
	 * @throws Exception
	 */
	protected function check( $values ) {
		list( $req, $exp ) = $this->validateRequestExpectedPair( $values );
		if ( !array_key_exists( 'action', $req ) ) {
			$req['action'] = 'query';
		}
		foreach ( $req as &$val ) {
			if ( is_array( $val ) ) {
				$val = implode( '|', array_unique( $val ) );
			}
		}
		$result = $this->doApiRequest( $req );
		$this->assertResult( array( 'query' => $exp ), $result[0], $req );
	}

	protected function assertResult( $exp, $result, $message = '' ) {
		try {
			$this->assertResultRecursive( $exp, $result );
		} catch ( Exception $e ) {
			if ( is_array( $message ) ) {
				$message = http_build_query( $message );
			}
			print "\nRequest: $message\n";
			print "\nExpected:\n";
			print_r( $exp );
			print "\nResult:\n";
			print_r( $result );
			throw $e; // rethrow it
		}
	}

	/**
	 * Recursively compare arrays, ignoring mismatches in numeric key and pageids.
	 * @param $expected array expected values
	 * @param $result array returned values
	 */
	private function assertResultRecursive( $expected, $result ) {
		reset( $expected );
		reset( $result );
		while ( true ) {
			$e = each( $expected );
			$r = each( $result );
			// If either of the arrays is shorter, abort. If both are done, success.
			$this->assertEquals( (bool)$e, (bool)$r );
			if ( !$e ) {
				break; // done
			}
			// continue only if keys are identical or both keys are numeric
			$this->assertTrue( $e['key'] === $r['key'] || ( is_numeric( $e['key'] ) && is_numeric( $r['key'] ) ) );
			// don't compare pageids
			if ( $e['key'] !== 'pageid' ) {
				// If values are arrays, compare recursively, otherwise compare with ===
				if ( is_array( $e['value'] ) && is_array( $r['value'] ) ) {
					$this->assertResultRecursive( $e['value'], $r['value'] );
				} else {
					$this->assertEquals( $e['value'], $r['value'] );
				}
			}
		}
	}
}
