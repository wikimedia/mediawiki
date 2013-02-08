<?php
/**
 *
 *
 * Created on Feb 8, 2013
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
 * These tests checks for the Sqlite bug with allcategories query:
 *   after adding two pages with the same category, the api query results in two identical
 *   category entries. This bug does not occur with mysql.
 *
 * @group API
 * @group Database
 * @group medium
 */
class ApiQuerySqliteBugTest extends ApiTestCase {

	/**
	 * Test creates two pages with the Category:AQBT-Cat, and tests that a call to api
	 * api.php?action=query&list=allcategories&acprefix=AQBT-  returns only one value.
	 */
	public function testBug() {
		wfDebugLog( 'testBug', 'Start' );
		$this->editPage( 'AQBT-1', '[[Category:AQBT-Cat]]' );
		wfDebugLog( 'testBug', 'Finished AQBT-1' );
		$this->editPage( 'AQBT-2', '[[Category:AQBT-Cat]]' );
		wfDebugLog( 'testBug', 'Finished AQBT-2' );

		$this->check(
			array(
				// api.php?action=query&list=allcategories&acprefix=AQBT-
				array( 'list' => 'allcategories', 'acprefix' => 'AQBT-' ),
				// Expected: {'allcategories': [ {'*': 'AQBT-Cat'} ] }
				array( 'allcategories' => array( array( '*' => 'AQBT-Cat' ) ) )
			)
		);

		wfDebugLog( 'testBug', 'Success' );
	}

	protected function editPage( $pageName, $text ) {
		$title = Title::newFromText( $pageName );
		$page = WikiPage::factory( $title );
		return $page->doEditContent( ContentHandler::makeContent( $text, $title ), '' );
	}

	/**
	 * Checks that the request's result matches the expected results.
	 * @param $values array is a two element array( request, expected_results )
	 * @throws Exception
	 */
	private function check( $values ) {
		$request = $values[0];
		$expected = $values[1];
		if ( !array_key_exists( 'action', $request ) ) {
			$request['action'] = 'query';
		}
		foreach ( $request as &$val ) {
			if ( is_array( $val ) ) {
				$val = implode( '|', array_unique( $val ) );
			}
		}
		$result = $this->doApiRequest( $request );
		$result = $result[0];
		$expected = array( 'query' => $expected );
		try {
			$this->assertQueryResults( $expected, $result );
		} catch (Exception $e) {
			print("\nRequest:\n");
			print_r( $request );
			print("\nExpected:\n");
			print_r( $expected );
			print("\nResult:\n");
			print_r( $result );
			throw $e; // rethrow it
		}
	}

	/**
	 * Recursively compare arrays, ignoring mismatches in numeric key and pageids.
	 * @param $expected array expected values
	 * @param $result array returned values
	 */
	private function assertQueryResults( $expected, $result ) {
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
					$this->assertQueryResults( $e['value'], $r['value'] );
				} else {
					$this->assertEquals( $e['value'], $r['value'] );
				}
			}
		}
	}
}
