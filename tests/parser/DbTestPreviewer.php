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
 * @ingroup Testing
 */

class DbTestPreviewer extends TestRecorder {
	protected $filter; // /< Test name filter callback
	protected $lb; // /< Database load balancer
	protected $db; // /< Database connection to the main DB
	protected $curRun; // /< run ID number for the current run
	protected $prevRun; // /< run ID number for the previous run, if any
	protected $results; // /< Result array

	/**
	 * This should be called before the table prefix is changed
	 * @param IDatabase $db
	 * @param bool|string $filter
	 */
	function __construct( $db, $filter = false ) {
		$this->db = $db;
		$this->filter = $filter;
	}

	/**
	 * Set up result recording; insert a record for the run with the date
	 * and all that fun stuff
	 */
	function start() {
		if ( !$this->db->tableExists( 'testrun', __METHOD__ )
			|| !$this->db->tableExists( 'testitem', __METHOD__ )
		) {
			print "WARNING> `testrun` table not found in database.\n";
			$this->prevRun = false;
		} else {
			// We'll make comparisons against the previous run later...
			$this->prevRun = $this->db->selectField( 'testrun', 'MAX(tr_id)' );
		}

		$this->results = [];
	}

	function record( $test, ParserTestResult $result ) {
		$this->results[$test['desc']] = $result->isSuccess() ? 1 : 0;
	}

	function report() {
		if ( $this->prevRun ) {
			// f = fail, p = pass, n = nonexistent
			// codes show before then after
			$table = [
				'fp' => 'previously failing test(s) now PASSING! :)',
				'pn' => 'previously PASSING test(s) removed o_O',
				'np' => 'new PASSING test(s) :)',

				'pf' => 'previously passing test(s) now FAILING! :(',
				'fn' => 'previously FAILING test(s) removed O_o',
				'nf' => 'new FAILING test(s) :(',
				'ff' => 'still FAILING test(s) :(',
			];

			$prevResults = [];

			$res = $this->db->select( 'testitem', [ 'ti_name', 'ti_success' ],
				[ 'ti_run' => $this->prevRun ], __METHOD__ );
			$filter = $this->filter;

			foreach ( $res as $row ) {
				if ( !$filter || $filter( $row->ti_name ) ) {
					$prevResults[$row->ti_name] = $row->ti_success;
				}
			}

			$combined = array_keys( $this->results + $prevResults );

			# Determine breakdown by change type
			$breakdown = [];
			foreach ( $combined as $test ) {
				if ( !isset( $prevResults[$test] ) ) {
					$before = 'n';
				} elseif ( $prevResults[$test] == 1 ) {
					$before = 'p';
				} else /* if ( $prevResults[$test] == 0 ) */ {
					$before = 'f';
				}

				if ( !isset( $this->results[$test] ) ) {
					$after = 'n';
				} elseif ( $this->results[$test] == 1 ) {
					$after = 'p';
				} else /* if ( $this->results[$test] == 0 ) */ {
					$after = 'f';
				}

				$code = $before . $after;

				if ( isset( $table[$code] ) ) {
					$breakdown[$code][$test] = $this->getTestStatusInfo( $test, $after );
				}
			}

			# Write out results
			foreach ( $table as $code => $label ) {
				if ( !empty( $breakdown[$code] ) ) {
					$count = count( $breakdown[$code] );
					printf( "\n%4d %s\n", $count, $label );

					foreach ( $breakdown[$code] as $differing_test_name => $statusInfo ) {
						print "      * $differing_test_name  [$statusInfo]\n";
					}
				}
			}
		} else {
			print "No previous test runs to compare against.\n";
		}

		print "\n";
	}

	/**
	 * Returns a string giving information about when a test last had a status change.
	 * Could help to track down when regressions were introduced, as distinct from tests
	 * which have never passed (which are more change requests than regressions).
	 * @param string $testname
	 * @param string $after
	 * @return string
	 */
	private function getTestStatusInfo( $testname, $after ) {
		// If we're looking at a test that has just been removed, then say when it first appeared.
		if ( $after == 'n' ) {
			$changedRun = $this->db->selectField( 'testitem',
				'MIN(ti_run)',
				[ 'ti_name' => $testname ],
				__METHOD__ );
			$appear = $this->db->selectRow( 'testrun',
				[ 'tr_date', 'tr_mw_version' ],
				[ 'tr_id' => $changedRun ],
				__METHOD__ );

			return "First recorded appearance: "
				. date( "d-M-Y H:i:s", strtotime( $appear->tr_date ) )
				. ", " . $appear->tr_mw_version;
		}

		// Otherwise, this test has previous recorded results.
		// See when this test last had a different result to what we're seeing now.
		$conds = [
			'ti_name' => $testname,
			'ti_success' => ( $after == 'f' ? "1" : "0" ) ];

		if ( $this->curRun ) {
			$conds[] = "ti_run != " . $this->db->addQuotes( $this->curRun );
		}

		$changedRun = $this->db->selectField( 'testitem', 'MAX(ti_run)', $conds, __METHOD__ );

		// If no record of ever having had a different result.
		if ( is_null( $changedRun ) ) {
			if ( $after == "f" ) {
				return "Has never passed";
			} else {
				return "Has never failed";
			}
		}

		// Otherwise, we're looking at a test whose status has changed.
		// (i.e. it used to work, but now doesn't; or used to fail, but is now fixed.)
		// In this situation, give as much info as we can as to when it changed status.
		$pre = $this->db->selectRow( 'testrun',
			[ 'tr_date', 'tr_mw_version' ],
			[ 'tr_id' => $changedRun ],
			__METHOD__ );
		$post = $this->db->selectRow( 'testrun',
			[ 'tr_date', 'tr_mw_version' ],
			[ "tr_id > " . $this->db->addQuotes( $changedRun ) ],
			__METHOD__,
			[ "LIMIT" => 1, "ORDER BY" => 'tr_id' ]
		);

		if ( $post ) {
			$postDate = date( "d-M-Y H:i:s", strtotime( $post->tr_date ) ) . ", {$post->tr_mw_version}";
		} else {
			$postDate = 'now';
		}

		return ( $after == "f" ? "Introduced" : "Fixed" ) . " between "
			. date( "d-M-Y H:i:s", strtotime( $pre->tr_date ) ) . ", " . $pre->tr_mw_version
			. " and $postDate";
	}
}
