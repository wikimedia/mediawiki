<?php

/**
 * @ingroup Maintenance
 *
 * Set of classes to help with test output and such. Right now pretty specific
 * to the parser tests but could be more useful one day :)
 *
 * @todo Fixme: Make this more generic
 */

class AnsiTermColorer {
	function __construct() {
	}

	/**
	 * Return ANSI terminal escape code for changing text attribs/color
	 *
	 * @param $color String: semicolon-separated list of attribute/color codes
	 * @return String
	 */
	public function color( $color ) {
		global $wgCommandLineDarkBg;

		$light = $wgCommandLineDarkBg ? "1;" : "0;";

		return "\x1b[{$light}{$color}m";
	}

	/**
	 * Return ANSI terminal escape code for restoring default text attributes
	 *
	 * @return String
	 */
	public function reset() {
		return $this->color( 0 );
	}
}

/* A colour-less terminal */
class DummyTermColorer {
	public function color( $color ) {
		return '';
	}

	public function reset() {
		return '';
	}
}

class TestRecorder {
	var $parent;
	var $term;

	function __construct( $parent ) {
		$this->parent = $parent;
		$this->term = $parent->term;
	}

	function start() {
		$this->total = 0;
		$this->success = 0;
	}

	function record( $test, $result ) {
		$this->total++;
		$this->success += ( $result ? 1 : 0 );
	}

	function end() {
		// dummy
	}

	function report() {
		if ( $this->total > 0 ) {
			$this->reportPercentage( $this->success, $this->total );
		} else {
			wfDie( "No tests found.\n" );
		}
	}

	function reportPercentage( $success, $total ) {
		$ratio = wfPercent( 100 * $success / $total );
		print $this->term->color( 1 ) . "Passed $success of $total tests ($ratio)... ";

		if ( $success == $total ) {
			print $this->term->color( 32 ) . "ALL TESTS PASSED!";
		} else {
			$failed = $total - $success ;
			print $this->term->color( 31 ) . "$failed tests failed!";
		}

		print $this->term->reset() . "\n";

		return ( $success == $total );
	}
}

class DbTestPreviewer extends TestRecorder  {
	protected $lb;      // /< Database load balancer
	protected $db;      // /< Database connection to the main DB
	protected $curRun;  // /< run ID number for the current run
	protected $prevRun; // /< run ID number for the previous run, if any
	protected $results; // /< Result array

	/**
	 * This should be called before the table prefix is changed
	 */
	function __construct( $parent ) {
		parent::__construct( $parent );

		$this->lb = wfGetLBFactory()->newMainLB();
		// This connection will have the wiki's table prefix, not parsertest_
		$this->db = $this->lb->getConnection( DB_MASTER );
	}

	/**
	 * Set up result recording; insert a record for the run with the date
	 * and all that fun stuff
	 */
	function start() {
		parent::start();

		if ( ! $this->db->tableExists( 'testrun' )
			or ! $this->db->tableExists( 'testitem' ) )
		{
			print "WARNING> `testrun` table not found in database.\n";
			$this->prevRun = false;
		} else {
			// We'll make comparisons against the previous run later...
			$this->prevRun = $this->db->selectField( 'testrun', 'MAX(tr_id)' );
		}

		$this->results = array();
	}

	function record( $test, $result ) {
		parent::record( $test, $result );
		$this->results[$test] = $result;
	}

	function report() {
		if ( $this->prevRun ) {
			// f = fail, p = pass, n = nonexistent
			// codes show before then after
			$table = array(
				'fp' => 'previously failing test(s) now PASSING! :)',
				'pn' => 'previously PASSING test(s) removed o_O',
				'np' => 'new PASSING test(s) :)',

				'pf' => 'previously passing test(s) now FAILING! :(',
				'fn' => 'previously FAILING test(s) removed O_o',
				'nf' => 'new FAILING test(s) :(',
				'ff' => 'still FAILING test(s) :(',
			);

			$prevResults = array();

			$res = $this->db->select( 'testitem', array( 'ti_name', 'ti_success' ),
				array( 'ti_run' => $this->prevRun ), __METHOD__ );

			foreach ( $res as $row ) {
				if ( !$this->parent->regex
					|| preg_match( "/{$this->parent->regex}/i", $row->ti_name ) )
				{
					$prevResults[$row->ti_name] = $row->ti_success;
				}
			}

			$combined = array_keys( $this->results + $prevResults );

			# Determine breakdown by change type
			$breakdown = array();
			foreach ( $combined as $test ) {
				if ( !isset( $prevResults[$test] ) ) {
					$before = 'n';
				} elseif ( $prevResults[$test] == 1 ) {
					$before = 'p';
				} else /* if ( $prevResults[$test] == 0 )*/ {
					$before = 'f';
				}

				if ( !isset( $this->results[$test] ) ) {
					$after = 'n';
				} elseif ( $this->results[$test] == 1 ) {
					$after = 'p';
				} else /*if ( $this->results[$test] == 0 ) */ {
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
		parent::report();
	}

	/**
	 * Returns a string giving information about when a test last had a status change.
	 * Could help to track down when regressions were introduced, as distinct from tests
	 * which have never passed (which are more change requests than regressions).
	 */
	private function getTestStatusInfo( $testname, $after ) {
		// If we're looking at a test that has just been removed, then say when it first appeared.
		if ( $after == 'n' ) {
			$changedRun = $this->db->selectField ( 'testitem',
				'MIN(ti_run)',
				array( 'ti_name' => $testname ),
				__METHOD__ );
			$appear = $this->db->selectRow ( 'testrun',
				array( 'tr_date', 'tr_mw_version' ),
				array( 'tr_id' => $changedRun ),
				__METHOD__ );

			return "First recorded appearance: "
				   . date( "d-M-Y H:i:s",  strtotime ( $appear->tr_date ) )
				   .  ", " . $appear->tr_mw_version;
		}

		// Otherwise, this test has previous recorded results.
		// See when this test last had a different result to what we're seeing now.
		$conds = array(
			'ti_name'    => $testname,
			'ti_success' => ( $after == 'f' ? "1" : "0" ) );

		if ( $this->curRun ) {
			$conds[] = "ti_run != " . $this->db->addQuotes ( $this->curRun );
		}

		$changedRun = $this->db->selectField ( 'testitem', 'MAX(ti_run)', $conds, __METHOD__ );

		// If no record of ever having had a different result.
		if ( is_null ( $changedRun ) ) {
			if ( $after == "f" ) {
				return "Has never passed";
			} else {
				return "Has never failed";
			}
		}

		// Otherwise, we're looking at a test whose status has changed.
		// (i.e. it used to work, but now doesn't; or used to fail, but is now fixed.)
		// In this situation, give as much info as we can as to when it changed status.
		$pre  = $this->db->selectRow ( 'testrun',
			array( 'tr_date', 'tr_mw_version' ),
			array( 'tr_id' => $changedRun ),
			__METHOD__ );
		$post = $this->db->selectRow ( 'testrun',
			array( 'tr_date', 'tr_mw_version' ),
			array( "tr_id > " . $this->db->addQuotes ( $changedRun ) ),
			__METHOD__,
			array( "LIMIT" => 1, "ORDER BY" => 'tr_id' )
		);

		if ( $post ) {
			$postDate = date( "d-M-Y H:i:s",  strtotime ( $post->tr_date  ) ) . ", {$post->tr_mw_version}";
		} else {
			$postDate = 'now';
		}

		return ( $after == "f" ? "Introduced" : "Fixed" ) . " between "
				. date( "d-M-Y H:i:s",  strtotime ( $pre->tr_date ) ) .  ", " . $pre->tr_mw_version
				. " and $postDate";

	}

	/**
	 * Commit transaction and clean up for result recording
	 */
	function end() {
		$this->lb->commitMasterChanges();
		$this->lb->closeAll();
		parent::end();
	}

}

class DbTestRecorder extends DbTestPreviewer  {
	var $version;

	/**
	 * Set up result recording; insert a record for the run with the date
	 * and all that fun stuff
	 */
	function start() {
		$this->db->begin();

		if ( ! $this->db->tableExists( 'testrun' )
			or ! $this->db->tableExists( 'testitem' ) )
		{
			print "WARNING> `testrun` table not found in database. Trying to create table.\n";
			$this->db->sourceFile( $this->db->patchPath( 'patch-testrun.sql' ) );
			echo "OK, resuming.\n";
		}

		parent::start();

		$this->db->insert( 'testrun',
			array(
				'tr_date'        => $this->db->timestamp(),
				'tr_mw_version'  => $this->version,
				'tr_php_version' => phpversion(),
				'tr_db_version'  => $this->db->getServerVersion(),
				'tr_uname'       => php_uname()
			),
			__METHOD__ );
			if ( $this->db->getType() === 'postgres' ) {
				$this->curRun = $this->db->currentSequenceValue( 'testrun_id_seq' );
			} else {
				$this->curRun = $this->db->insertId();
			}
	}

	/**
	 * Record an individual test item's success or failure to the db
	 *
	 * @param $test String
	 * @param $result Boolean
	 */
	function record( $test, $result ) {
		parent::record( $test, $result );

		$this->db->insert( 'testitem',
			array(
				'ti_run'     => $this->curRun,
				'ti_name'    => $test,
				'ti_success' => $result ? 1 : 0,
			),
			__METHOD__ );
	}
}

class RemoteTestRecorder extends TestRecorder {
	function start() {
		parent::start();

		$this->results = array();
		$this->ping( 'running' );
	}

	function record( $test, $result ) {
		parent::record( $test, $result );
		$this->results[$test] = (bool)$result;
	}

	function end() {
		$this->ping( 'complete', $this->results );
		parent::end();
	}

	/**
	 * Inform a CodeReview instance that we've started or completed a test run...
	 *
	 * @param $status string: "running" - tell it we've started
	 *                        "complete" - provide test results array
	 *                        "abort" - something went horribly awry
	 * @param $results array of test name => true/false
	 */
	function ping( $status, $results = false ) {
		global $wgParserTestRemote, $IP;

		$remote = $wgParserTestRemote;
		$revId = SpecialVersion::getSvnRevision( $IP );
		$jsonResults = FormatJson::encode( $results );

		if ( !$remote ) {
			print "Can't do remote upload without configuring \$wgParserTestRemote!\n";
			exit( 1 );
		}

		// Generate a hash MAC to validate our credentials
		$message = array(
			$remote['repo'],
			$remote['suite'],
			$revId,
			$status,
		);

		if ( $status == "complete" ) {
			$message[] = $jsonResults;
		}
		$hmac = hash_hmac( "sha1", implode( "|", $message ), $remote['secret'] );

		$postData = array(
			'action' => 'codetestupload',
			'format' => 'json',
			'repo'   => $remote['repo'],
			'suite'  => $remote['suite'],
			'rev'    => $revId,
			'status' => $status,
			'hmac'   => $hmac,
		);

		if ( $status == "complete" ) {
			$postData['results'] = $jsonResults;
		}

		$response = $this->post( $remote['api-url'], $postData );

		if ( $response === false ) {
			print "CodeReview info upload failed to reach server.\n";
			exit( 1 );
		}

		$responseData = FormatJson::decode( $response, true );

		if ( !is_array( $responseData ) ) {
			print "CodeReview API response not recognized...\n";
			wfDebug( "Unrecognized CodeReview API response: $response\n" );
			exit( 1 );
		}

		if ( isset( $responseData['error'] ) ) {
			$code = $responseData['error']['code'];
			$info = $responseData['error']['info'];
			print "CodeReview info upload failed: $code $info\n";
			exit( 1 );
		}
	}

	function post( $url, $data ) {
		return Http::post( $url, array( 'postData' => $data ) );
	}
}

class TestFileIterator implements Iterator {
	private $file;
	private $fh;
	private $parserTest; /* An instance of ParserTest (parserTests.php) or MediaWikiParserTest (phpunit) */
	private $index = 0;
	private $test;
	private $lineNum;
	private $eof;

	function __construct( $file, $parserTest = null ) {
		global $IP;

		$this->file = $file;
		$this->fh = fopen( $this->file, "rt" );

		if ( !$this->fh ) {
			wfDie( "Couldn't open file '$file'\n" );
		}

		$this->parserTest = $parserTest;

		if ( $this->parserTest ) {
			$this->parserTest->showRunFile( wfRelativePath( $this->file, $IP ) );
		}

		$this->lineNum = $this->index = 0;
	}

	function rewind() {
		if ( fseek( $this->fh, 0 ) ) {
			wfDie( "Couldn't fseek to the start of '$this->file'\n" );
		}

		$this->index = -1;
		$this->lineNum = 0;
		$this->eof = false;
		$this->next();

		return true;
	}

	function current() {
		return $this->test;
	}

	function key() {
		return $this->index;
	}

	function next() {
		if ( $this->readNextTest() ) {
			$this->index++;
			return true;
		} else {
			$this->eof = true;
		}
	}

	function valid() {
		return $this->eof != true;
	}

	function readNextTest() {
		$data = array();
		$section = null;

		while ( false !== ( $line = fgets( $this->fh ) ) ) {
			$this->lineNum++;
			$matches = array();

			if ( preg_match( '/^!!\s*(\w+)/', $line, $matches ) ) {
				$section = strtolower( $matches[1] );

				if ( $section == 'endarticle' ) {
					if ( !isset( $data['text'] ) ) {
						wfDie( "'endarticle' without 'text' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['article'] ) ) {
						wfDie( "'endarticle' without 'article' at line {$this->lineNum} of $this->file\n" );
					}

					if ( $this->parserTest ) {
						$this->parserTest->addArticle( ParserTest::chomp( $data['article'] ), $data['text'], $this->lineNum );
					} else {wfDie("JAJA");
						ParserTest::addArticle( $data['article'], $data['text'], $this->lineNum );
					}
					$data = array();
					$section = null;

					continue;
				}

				if ( $section == 'endhooks' ) {
					if ( !isset( $data['hooks'] ) ) {
						wfDie( "'endhooks' without 'hooks' at line {$this->lineNum} of $this->file\n" );
					}

					foreach ( explode( "\n", $data['hooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							if ( $this->parserTest && !$this->parserTest->requireHook( $line ) ) {
								return false;
							}
						}
					}

					$data = array();
					$section = null;

					continue;
				}

				if ( $section == 'endfunctionhooks' ) {
					if ( !isset( $data['functionhooks'] ) ) {
						wfDie( "'endfunctionhooks' without 'functionhooks' at line {$this->lineNum} of $this->file\n" );
					}

					foreach ( explode( "\n", $data['functionhooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							if ( $this->parserTest && !$this->parserTest->requireFunctionHook( $line ) ) {
								return false;
							}
						}
					}

					$data = array();
					$section = null;

					continue;
				}

				if ( $section == 'end' ) {
					if ( !isset( $data['test'] ) ) {
						wfDie( "'end' without 'test' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['input'] ) ) {
						wfDie( "'end' without 'input' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['result'] ) ) {
						wfDie( "'end' without 'result' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['options'] ) ) {
						$data['options'] = '';
					}

					if ( !isset( $data['config'] ) )
						$data['config'] = '';

					if ( $this->parserTest
						 && ( ( preg_match( '/\\bdisabled\\b/i', $data['options'] ) && !$this->parserTest->runDisabled )
							 || !preg_match( "/" . $this->parserTest->regex . "/i", $data['test'] ) )  ) {
						# disabled test
						$data = array();
						$section = null;

						continue;
					}

					global $wgUseTeX;

					if ( $this->parserTest &&
						 preg_match( '/\\bmath\\b/i', $data['options'] ) && !$wgUseTeX ) {
						# don't run math tests if $wgUseTeX is set to false in LocalSettings
						$data = array();
						$section = null;

						continue;
					}

					if ( $this->parserTest ) {
						$this->test = array(
							'test' => ParserTest::chomp( $data['test'] ),
							'input' => ParserTest::chomp( $data['input'] ),
							'result' => ParserTest::chomp( $data['result'] ),
							'options' => ParserTest::chomp( $data['options'] ),
							'config' => ParserTest::chomp( $data['config'] ) );
					} else {
						$this->test['test'] = $data['test'];
					}

					return true;
				}

				if ( isset ( $data[$section] ) ) {
					wfDie( "duplicate section '$section' at line {$this->lineNum} of $this->file\n" );
				}

				$data[$section] = '';

				continue;
			}

			if ( $section ) {
				$data[$section] .= $line;
			}
		}

		return false;
	}
}
