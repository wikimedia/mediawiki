<?php
/**
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
	 * @param array $v,... List of arrays, each of which contains exactly two
	 * @return array
	 */
	protected function merge( /*...*/ ) {
		$request = [];
		$expected = [];
		foreach ( func_get_args() as $v ) {
			list( $req, $exp ) = $this->validateRequestExpectedPair( $v );
			$request = array_merge_recursive( $request, $req );
			$this->mergeExpected( $expected, $exp );
		}

		return [ $request, $expected ];
	}

	/**
	 * Check that the parameter is a valid two element array,
	 * with the first element being API request and the second - expected result
	 * @param array $v
	 * @return array
	 */
	private function validateRequestExpectedPair( $v ) {
		$this->assertInternalType( 'array', $v, self::PARAM_ASSERT );
		$this->assertEquals( 2, count( $v ), self::PARAM_ASSERT );
		$this->assertArrayHasKey( 0, $v, self::PARAM_ASSERT );
		$this->assertArrayHasKey( 1, $v, self::PARAM_ASSERT );
		$this->assertInternalType( 'array', $v[0], self::PARAM_ASSERT );
		$this->assertInternalType( 'array', $v[1], self::PARAM_ASSERT );

		return $v;
	}

	/**
	 * Recursively merges the expected values in the $item into the $all
	 * @param array &$all
	 * @param array $item
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
	 * Assumes no rawcontinue and a complete batch.
	 * @param array $values Array is a two element array( request, expected_results )
	 * @param array $session
	 * @param bool $appendModule
	 * @param User $user
	 */
	protected function check( $values, array $session = null,
		$appendModule = false, User $user = null
	) {
		list( $req, $exp ) = $this->validateRequestExpectedPair( $values );
		if ( !array_key_exists( 'action', $req ) ) {
			$req['action'] = 'query';
		}
		foreach ( $req as &$val ) {
			if ( is_array( $val ) ) {
				$val = implode( '|', array_unique( $val ) );
			}
		}
		$result = $this->doApiRequest( $req, $session, $appendModule, $user );
		$this->assertResult( [ 'batchcomplete' => true, 'query' => $exp ], $result[0], $req );
	}

	protected function assertResult( $exp, $result, $message = '' ) {
		try {
			$exp = self::sanitizeResultArray( $exp );
			$result = self::sanitizeResultArray( $result );
			$this->assertEquals( $exp, $result );
		} catch ( PHPUnit_Framework_ExpectationFailedException $e ) {
			if ( is_array( $message ) ) {
				$message = http_build_query( $message );
			}

			// FIXME: once we migrate to phpunit 4.1+, hardcode ComparisonFailure exception use
			$compEx = 'SebastianBergmann\Comparator\ComparisonFailure';
			if ( !class_exists( $compEx ) ) {
				$compEx = 'PHPUnit_Framework_ComparisonFailure';
			}

			throw new PHPUnit_Framework_ExpectationFailedException(
				$e->getMessage() . "\nRequest: $message",
				new $compEx(
					$exp,
					$result,
					print_r( $exp, true ),
					print_r( $result, true ),
					false,
					$e->getComparisonFailure()->getMessage() . "\nRequest: $message"
				)
			);
		}
	}

	/**
	 * Recursively ksorts a result array and removes any 'pageid' keys.
	 * @param array $result
	 * @return array
	 */
	private static function sanitizeResultArray( $result ) {
		unset( $result['pageid'] );
		foreach ( $result as $key => $value ) {
			if ( is_array( $value ) ) {
				$result[$key] = self::sanitizeResultArray( $value );
			}
		}

		// Sort the result by keys, then take advantage of how array_merge will
		// renumber numeric keys while leaving others alone.
		ksort( $result );
		return array_merge( $result );
	}
}
