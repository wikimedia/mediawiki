<?php

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
			throw new MWException( "No tests found.\n" );
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

		if ( ! $this->db->tableExists( 'testrun', __METHOD__ )
			|| ! $this->db->tableExists( 'testitem', __METHOD__ ) )
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
			|| ! $this->db->tableExists( 'testitem' ) )
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

class TestFileIterator implements Iterator {
	private $file;
	private $fh;
	private $parserTest; /* An instance of ParserTest (parserTests.php) or MediaWikiParserTest (phpunit) */
	private $index = 0;
	private $test;
	private $section = null; /** String|null: current test section being analyzed */
	private $sectionData = array();
	private $lineNum;
	private $eof;

	function __construct( $file, $parserTest ) {
		$this->file = $file;
		$this->fh = fopen( $this->file, "rt" );

		if ( !$this->fh ) {
			throw new MWException( "Couldn't open file '$file'\n" );
		}

		$this->parserTest = $parserTest;

		$this->lineNum = $this->index = 0;
	}

	function rewind() {
		if ( fseek( $this->fh, 0 ) ) {
			throw new MWException( "Couldn't fseek to the start of '$this->file'\n" );
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
		$this->clearSection();

		# Create a fake parser tests which never run anything unless
		# asked to do so. This will avoid running hooks for a disabled test
		$delayedParserTest = new DelayedParserTest();

		while ( false !== ( $line = fgets( $this->fh ) ) ) {
			$this->lineNum++;
			$matches = array();

			if ( preg_match( '/^!!\s*(\w+)/', $line, $matches ) ) {
				$this->section = strtolower( $matches[1] );

				if ( $this->section == 'endarticle' ) {
					$this->checkSection( 'text'    );
					$this->checkSection( 'article' );

					$this->parserTest->addArticle( ParserTest::chomp( $this->sectionData['article'] ), $this->sectionData['text'], $this->lineNum );

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'endhooks' ) {
					$this->checkSection( 'hooks' );

					foreach ( explode( "\n", $this->sectionData['hooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							$delayedParserTest->requireHook( $line );
						}
					}

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'endfunctionhooks' ) {
					$this->checkSection( 'functionhooks' );

					foreach ( explode( "\n", $this->sectionData['functionhooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							$delayedParserTest->requireFunctionHook( $line );
						}
					}

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'end' ) {
					$this->checkSection( 'test'   );
					$this->checkSection( 'input'  );
					$this->checkSection( 'result' );

					if ( !isset( $this->sectionData['options'] ) ) {
						$this->sectionData['options'] = '';
					}

					if ( !isset( $this->sectionData['config'] ) ) {
						$this->sectionData['config'] = '';
					}

					if ( ( ( preg_match( '/\\bdisabled\\b/i', $this->sectionData['options'] ) && !$this->parserTest->runDisabled )
							 || !preg_match( "/" . $this->parserTest->regex . "/i", $this->sectionData['test'] ) )  ) {
						# disabled test
						$this->clearSection();

						# Forget any pending hooks call since test is disabled
						$delayedParserTest->reset();

						continue;
					}

					# We are really going to run the test, run pending hooks and hooks function
					wfDebug( __METHOD__ . " unleashing delayed test for: {$this->sectionData['test']}" );
					$hooksResult = $delayedParserTest->unleash( $this->parserTest );
					if( !$hooksResult ) {
						# Some hook reported an issue. Abort.
						return false;
					}

					$this->test = array(
						'test'    => ParserTest::chomp( $this->sectionData['test']    ),
						'input'   => ParserTest::chomp( $this->sectionData['input']   ),
						'result'  => ParserTest::chomp( $this->sectionData['result']  ),
						'options' => ParserTest::chomp( $this->sectionData['options'] ),
						'config'  => ParserTest::chomp( $this->sectionData['config']  ),
					);

					return true;
				}

				if ( isset ( $this->sectionData[$this->section] ) ) {
					throw new MWException( "duplicate section '$section' at line {$this->lineNum} of $this->file\n" );
				}

				$this->sectionData[$this->section] = '';

				continue;
			}

			if ( $this->section ) {
				$this->sectionData[$this->section] .= $line;
			}
		}

		return false;
	}


	/**
	 * Clear section name and its data
	 */
	private function clearSection() {
		$this->sectionData = array();
		$this->section = null;
		
	}

	/**
	 * Verify the current section data has some value for the given token
	 * name (first parameter).
	 * Throw an exception if it is not set, referencing current section
	 * and adding the current file name and line number
	 *
	 * @param $token String: expected token that should have been mentionned before closing this section 
	 */
	private function checkSection( $token ) {
		if( is_null( $this->section ) ) {
			throw new MWException( __METHOD__ . " can not verify a null section!\n" );
		}

		if( !isset($this->sectionData[$token]) ) {
			throw new MWException( sprintf(
				"'%s' without '%s' at line %s of %s\n",
				$this->section,
				$token,
				$this->lineNum,
				$this->file
			));
		}
		return true;
	}
}

/**
 * A class to delay execution of a parser test hooks.
 */
class DelayedParserTest {

	/** Initialized on construction */
	private $hooks;
	private $fnHooks;

	public function __construct() {
		$this->reset();
	}

	/**
	 * Init/reset or forgot about the current delayed test.
	 * Call to this will erase any hooks function that were pending.
	 */
	public function reset() {
		$this->hooks   = array();
		$this->fnHooks = array();
	}

	/**
	 * Called whenever we actually want to run the hook.
	 * Should be the case if we found the parserTest is not disabled 
	 */
	public function unleash( &$parserTest ) {
		if( !($parserTest instanceof ParserTest || $parserTest instanceof NewParserTest
		) ) {
			throw new MWException( __METHOD__ . " must be passed an instance of ParserTest or NewParserTest classes\n" );
		}

		# Trigger delayed hooks. Any failure will make us abort
		foreach( $this->hooks as $hook ) {
			$ret = $parserTest->requireHook( $hook );
			if( !$ret ) {
				return false;
			}
		}

		# Trigger delayed function hooks. Any failure will make us abort
		foreach( $this->fnHooks as $fnHook ) {
			$ret = $parserTest->requireFunctionHook( $fnHook );
			if( !$ret ) {
				return false;
			}
		}

		# Delayed execution was successful.
		return true;
	}

	/**
	 * Similar to ParserTest object but does not run anything
	 * Use unleash() to really execute the hook
	 */
	public function requireHook( $hook ) {
		$this->hooks[] = $hook;
	}
	/**
	 * Similar to ParserTest object but does not run anything
	 * Use unleash() to really execute the hook function
	 */
	public function requireFunctionHook( $fnHook ) {
		$this->fnHooks[] = $fnHook;
	}

}