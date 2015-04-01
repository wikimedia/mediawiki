<?php

/**
 * @since 1.18
 */
abstract class MediaWikiTestCase extends PHPUnit_Framework_TestCase {
	/**
	 * $called tracks whether the setUp and tearDown method has been called.
	 * class extending MediaWikiTestCase usually override setUp and tearDown
	 * but forget to call the parent.
	 *
	 * The array format takes a method name as key and anything as a value.
	 * By asserting the key exist, we know the child class has called the
	 * parent.
	 *
	 * This property must be private, we do not want child to override it,
	 * they should call the appropriate parent method instead.
	 */
	private $called = array();

	/**
	 * @var TestUser[]
	 * @since 1.20
	 */
	public static $users;

	/**
	 * @var DatabaseBase
	 * @since 1.18
	 */
	protected $db;

	/**
	 * @var array
	 * @since 1.19
	 */
	protected $tablesUsed = array(); // tables with data

	private static $useTemporaryTables = true;
	private static $reuseDB = false;
	private static $dbSetup = false;
	private static $oldTablePrefix = false;

	/**
	 * Original value of PHP's error_reporting setting.
	 *
	 * @var int
	 */
	private $phpErrorLevel;

	/**
	 * Holds the paths of temporary files/directories created through getNewTempFile,
	 * and getNewTempDirectory
	 *
	 * @var array
	 */
	private $tmpFiles = array();

	/**
	 * Holds original values of MediaWiki configuration settings
	 * to be restored in tearDown().
	 * See also setMwGlobals().
	 * @var array
	 */
	private $mwGlobals = array();

	/**
	 * Table name prefixes. Oracle likes it shorter.
	 */
	const DB_PREFIX = 'unittest_';
	const ORA_DB_PREFIX = 'ut_';

	/**
	 * @var array
	 * @since 1.18
	 */
	protected $supportedDBs = array(
		'mysql',
		'sqlite',
		'postgres',
		'oracle'
	);

	public function __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->backupGlobals = false;
		$this->backupStaticAttributes = false;
	}

	public function __destruct() {
		// Complain if self::setUp() was called, but not self::tearDown()
		// $this->called['setUp'] will be checked by self::testMediaWikiTestCaseParentSetupCalled()
		if ( isset( $this->called['setUp'] ) && !isset( $this->called['tearDown'] ) ) {
			throw new MWException( get_called_class() . "::tearDown() must call parent::tearDown()" );
		}
	}

	public function run( PHPUnit_Framework_TestResult $result = null ) {
		/* Some functions require some kind of caching, and will end up using the db,
		 * which we can't allow, as that would open a new connection for mysql.
		 * Replace with a HashBag. They would not be going to persist anyway.
		 */
		ObjectCache::$instances[CACHE_DB] = new HashBagOStuff;

		$needsResetDB = false;

		if ( $this->needsDB() ) {
			// set up a DB connection for this test to use

			self::$useTemporaryTables = !$this->getCliArg( 'use-normal-tables' );
			self::$reuseDB = $this->getCliArg( 'reuse-db' );

			$this->db = wfGetDB( DB_MASTER );

			$this->checkDbIsSupported();

			if ( !self::$dbSetup ) {
				// switch to a temporary clone of the database
				self::setupTestDB( $this->db, $this->dbPrefix() );

				if ( ( $this->db->getType() == 'oracle' || !self::$useTemporaryTables ) && self::$reuseDB ) {
					$this->resetDB();
				}
			}
			$this->addCoreDBData();
			$this->addDBData();
			$needsResetDB = true;
		}

		parent::run( $result );

		if ( $needsResetDB ) {
			$this->resetDB();
		}
	}

	/**
	 * @since 1.21
	 *
	 * @return bool
	 */
	public function usesTemporaryTables() {
		return self::$useTemporaryTables;
	}

	/**
	 * Obtains a new temporary file name
	 *
	 * The obtained filename is enlisted to be removed upon tearDown
	 *
	 * @since 1.20
	 *
	 * @return string Absolute name of the temporary file
	 */
	protected function getNewTempFile() {
		$fileName = tempnam( wfTempDir(), 'MW_PHPUnit_' . get_class( $this ) . '_' );
		$this->tmpFiles[] = $fileName;

		return $fileName;
	}

	/**
	 * obtains a new temporary directory
	 *
	 * The obtained directory is enlisted to be removed (recursively with all its contained
	 * files) upon tearDown.
	 *
	 * @since 1.20
	 *
	 * @return string Absolute name of the temporary directory
	 */
	protected function getNewTempDirectory() {
		// Starting of with a temporary /file/.
		$fileName = $this->getNewTempFile();

		// Converting the temporary /file/ to a /directory/
		//
		// The following is not atomic, but at least we now have a single place,
		// where temporary directory creation is bundled and can be improved
		unlink( $fileName );
		$this->assertTrue( wfMkdirParents( $fileName ) );

		return $fileName;
	}

	protected function setUp() {
		parent::setUp();
		$this->called['setUp'] = true;

		$this->phpErrorLevel = intval( ini_get( 'error_reporting' ) );

		// Cleaning up temporary files
		foreach ( $this->tmpFiles as $fileName ) {
			if ( is_file( $fileName ) || ( is_link( $fileName ) ) ) {
				unlink( $fileName );
			} elseif ( is_dir( $fileName ) ) {
				wfRecursiveRemoveDir( $fileName );
			}
		}

		if ( $this->needsDB() && $this->db ) {
			// Clean up open transactions
			while ( $this->db->trxLevel() > 0 ) {
				$this->db->rollback();
			}

			// don't ignore DB errors
			$this->db->ignoreErrors( false );
		}

		DeferredUpdates::clearPendingUpdates();

	}

	protected function addTmpFiles( $files ) {
		$this->tmpFiles = array_merge( $this->tmpFiles, (array)$files );
	}

	protected function tearDown() {
		$this->called['tearDown'] = true;
		// Cleaning up temporary files
		foreach ( $this->tmpFiles as $fileName ) {
			if ( is_file( $fileName ) || ( is_link( $fileName ) ) ) {
				unlink( $fileName );
			} elseif ( is_dir( $fileName ) ) {
				wfRecursiveRemoveDir( $fileName );
			}
		}

		if ( $this->needsDB() && $this->db ) {
			// Clean up open transactions
			while ( $this->db->trxLevel() > 0 ) {
				$this->db->rollback();
			}

			// don't ignore DB errors
			$this->db->ignoreErrors( false );
		}

		// Restore mw globals
		foreach ( $this->mwGlobals as $key => $value ) {
			$GLOBALS[$key] = $value;
		}
		$this->mwGlobals = array();
		RequestContext::resetMain();
		MediaHandler::resetCache();

		$phpErrorLevel = intval( ini_get( 'error_reporting' ) );

		if ( $phpErrorLevel !== $this->phpErrorLevel ) {
			ini_set( 'error_reporting', $this->phpErrorLevel );

			$oldHex = strtoupper( dechex( $this->phpErrorLevel ) );
			$newHex = strtoupper( dechex( $phpErrorLevel ) );
			$message = "PHP error_reporting setting was left dirty: "
				. "was 0x$oldHex before test, 0x$newHex after test!";

			$this->fail( $message );
		}

		parent::tearDown();
	}

	/**
	 * Make sure MediaWikiTestCase extending classes have called their
	 * parent setUp method
	 */
	final public function testMediaWikiTestCaseParentSetupCalled() {
		$this->assertArrayHasKey( 'setUp', $this->called,
			get_called_class() . "::setUp() must call parent::setUp()"
		);
	}

	/**
	 * Sets a global, maintaining a stashed version of the previous global to be
	 * restored in tearDown
	 *
	 * The key is added to the array of globals that will be reset afterwards
	 * in the tearDown().
	 *
	 * @example
	 * <code>
	 *     protected function setUp() {
	 *         $this->setMwGlobals( 'wgRestrictStuff', true );
	 *     }
	 *
	 *     function testFoo() {}
	 *
	 *     function testBar() {}
	 *         $this->assertTrue( self::getX()->doStuff() );
	 *
	 *         $this->setMwGlobals( 'wgRestrictStuff', false );
	 *         $this->assertTrue( self::getX()->doStuff() );
	 *     }
	 *
	 *     function testQuux() {}
	 * </code>
	 *
	 * @param array|string $pairs Key to the global variable, or an array
	 *  of key/value pairs.
	 * @param mixed $value Value to set the global to (ignored
	 *  if an array is given as first argument).
	 *
	 * @since 1.21
	 */
	protected function setMwGlobals( $pairs, $value = null ) {
		if ( is_string( $pairs ) ) {
			$pairs = array( $pairs => $value );
		}

		$this->stashMwGlobals( array_keys( $pairs ) );

		foreach ( $pairs as $key => $value ) {
			$GLOBALS[$key] = $value;
		}
	}

	/**
	 * Stashes the global, will be restored in tearDown()
	 *
	 * Individual test functions may override globals through the setMwGlobals() function
	 * or directly. When directly overriding globals their keys should first be passed to this
	 * method in setUp to avoid breaking global state for other tests
	 *
	 * That way all other tests are executed with the same settings (instead of using the
	 * unreliable local settings for most tests and fix it only for some tests).
	 *
	 * @param array|string $globalKeys Key to the global variable, or an array of keys.
	 *
	 * @throws Exception When trying to stash an unset global
	 * @since 1.23
	 */
	protected function stashMwGlobals( $globalKeys ) {
		if ( is_string( $globalKeys ) ) {
			$globalKeys = array( $globalKeys );
		}

		foreach ( $globalKeys as $globalKey ) {
			// NOTE: make sure we only save the global once or a second call to
			// setMwGlobals() on the same global would override the original
			// value.
			if ( !array_key_exists( $globalKey, $this->mwGlobals ) ) {
				if ( !array_key_exists( $globalKey, $GLOBALS ) ) {
					throw new Exception( "Global with key {$globalKey} doesn't exist and cant be stashed" );
				}
				// NOTE: we serialize then unserialize the value in case it is an object
				// this stops any objects being passed by reference. We could use clone
				// and if is_object but this does account for objects within objects!
				try {
					$this->mwGlobals[$globalKey] = unserialize( serialize( $GLOBALS[$globalKey] ) );
				}
					// NOTE; some things such as Closures are not serializable
					// in this case just set the value!
				catch ( Exception $e ) {
					$this->mwGlobals[$globalKey] = $GLOBALS[$globalKey];
				}
			}
		}
	}

	/**
	 * Merges the given values into a MW global array variable.
	 * Useful for setting some entries in a configuration array, instead of
	 * setting the entire array.
	 *
	 * @param string $name The name of the global, as in wgFooBar
	 * @param array $values The array containing the entries to set in that global
	 *
	 * @throws MWException If the designated global is not an array.
	 *
	 * @since 1.21
	 */
	protected function mergeMwGlobalArrayValue( $name, $values ) {
		if ( !isset( $GLOBALS[$name] ) ) {
			$merged = $values;
		} else {
			if ( !is_array( $GLOBALS[$name] ) ) {
				throw new MWException( "MW global $name is not an array." );
			}

			// NOTE: do not use array_merge, it screws up for numeric keys.
			$merged = $GLOBALS[$name];
			foreach ( $values as $k => $v ) {
				$merged[$k] = $v;
			}
		}

		$this->setMwGlobals( $name, $merged );
	}

	/**
	 * @return string
	 * @since 1.18
	 */
	public function dbPrefix() {
		return $this->db->getType() == 'oracle' ? self::ORA_DB_PREFIX : self::DB_PREFIX;
	}

	/**
	 * @return bool
	 * @since 1.18
	 */
	public function needsDB() {
		# if the test says it uses database tables, it needs the database
		if ( $this->tablesUsed ) {
			return true;
		}

		# if the test says it belongs to the Database group, it needs the database
		$rc = new ReflectionClass( $this );
		if ( preg_match( '/@group +Database/im', $rc->getDocComment() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Insert a new page.
	 *
	 * Should be called from addDBData().
	 *
	 * @since 1.25
	 * @param string $pageName Page name
	 * @param string $text Page's content
	 * @return array Title object and page id
	 */
	protected function insertPage( $pageName, $text = 'Sample page for unit test.' ) {
		$title = Title::newFromText( $pageName, 0 );

		$user = User::newFromName( 'UTSysop' );
		$comment = __METHOD__ . ': Sample page for unit test.';

		// Avoid memory leak...?
		// LinkCache::singleton()->clear();
		// Maybe.  But doing this absolutely breaks $title->isRedirect() when called during unit tests....

		$page = WikiPage::factory( $title );
		$page->doEditContent( ContentHandler::makeContent( $text, $title ), $comment, 0, false, $user );

		return array(
			'title' => $title,
			'id' => $page->getId(),
		);
	}

	/**
	 * Stub. If a test needs to add additional data to the database, it should
	 * implement this method and do so
	 *
	 * @since 1.18
	 */
	public function addDBData() {
	}

	private function addCoreDBData() {
		if ( $this->db->getType() == 'oracle' ) {

			# Insert 0 user to prevent FK violations
			# Anonymous user
			$this->db->insert( 'user', array(
				'user_id' => 0,
				'user_name' => 'Anonymous' ), __METHOD__, array( 'IGNORE' ) );

			# Insert 0 page to prevent FK violations
			# Blank page
			$this->db->insert( 'page', array(
				'page_id' => 0,
				'page_namespace' => 0,
				'page_title' => ' ',
				'page_restrictions' => null,
				'page_is_redirect' => 0,
				'page_is_new' => 0,
				'page_random' => 0,
				'page_touched' => $this->db->timestamp(),
				'page_latest' => 0,
				'page_len' => 0 ), __METHOD__, array( 'IGNORE' ) );
		}

		User::resetIdByNameCache();

		// Make sysop user
		$user = User::newFromName( 'UTSysop' );

		if ( $user->idForName() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTSysopPassword' );

			$user->addGroup( 'sysop' );
			$user->addGroup( 'bureaucrat' );
			$user->saveSettings();
		}

		// Make 1 page with 1 revision
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		if ( $page->getId() == 0 ) {
			$page->doEditContent(
				new WikitextContent( 'UTContent' ),
				'UTPageSummary',
				EDIT_NEW,
				false,
				$user
			);
		}
	}

	/**
	 * Restores MediaWiki to using the table set (table prefix) it was using before
	 * setupTestDB() was called. Useful if we need to perform database operations
	 * after the test run has finished (such as saving logs or profiling info).
	 *
	 * @since 1.21
	 */
	public static function teardownTestDB() {
		if ( !self::$dbSetup ) {
			return;
		}

		CloneDatabase::changePrefix( self::$oldTablePrefix );

		self::$oldTablePrefix = false;
		self::$dbSetup = false;
	}

	/**
	 * Creates an empty skeleton of the wiki database by cloning its structure
	 * to equivalent tables using the given $prefix. Then sets MediaWiki to
	 * use the new set of tables (aka schema) instead of the original set.
	 *
	 * This is used to generate a dummy table set, typically consisting of temporary
	 * tables, that will be used by tests instead of the original wiki database tables.
	 *
	 * @since 1.21
	 *
	 * @note the original table prefix is stored in self::$oldTablePrefix. This is used
	 * by teardownTestDB() to return the wiki to using the original table set.
	 *
	 * @note this method only works when first called. Subsequent calls have no effect,
	 * even if using different parameters.
	 *
	 * @param DatabaseBase $db The database connection
	 * @param string $prefix The prefix to use for the new table set (aka schema).
	 *
	 * @throws MWException If the database table prefix is already $prefix
	 */
	public static function setupTestDB( DatabaseBase $db, $prefix ) {
		global $wgDBprefix;
		if ( $wgDBprefix === $prefix ) {
			throw new MWException(
				'Cannot run unit tests, the database prefix is already "' . $prefix . '"' );
		}

		if ( self::$dbSetup ) {
			return;
		}

		$tablesCloned = self::listTables( $db );
		$dbClone = new CloneDatabase( $db, $tablesCloned, $prefix );
		$dbClone->useTemporaryTables( self::$useTemporaryTables );

		self::$dbSetup = true;
		self::$oldTablePrefix = $wgDBprefix;

		if ( ( $db->getType() == 'oracle' || !self::$useTemporaryTables ) && self::$reuseDB ) {
			CloneDatabase::changePrefix( $prefix );

			return;
		} else {
			$dbClone->cloneTableStructure();
		}

		if ( $db->getType() == 'oracle' ) {
			$db->query( 'BEGIN FILL_WIKI_INFO; END;' );
		}
	}

	/**
	 * Empty all tables so they can be repopulated for tests
	 */
	private function resetDB() {
		if ( $this->db ) {
			if ( $this->db->getType() == 'oracle' ) {
				if ( self::$useTemporaryTables ) {
					wfGetLB()->closeAll();
					$this->db = wfGetDB( DB_MASTER );
				} else {
					foreach ( $this->tablesUsed as $tbl ) {
						if ( $tbl == 'interwiki' ) {
							continue;
						}
						$this->db->query( 'TRUNCATE TABLE ' . $this->db->tableName( $tbl ), __METHOD__ );
					}
				}
			} else {
				foreach ( $this->tablesUsed as $tbl ) {
					if ( $tbl == 'interwiki' || $tbl == 'user' ) {
						continue;
					}
					$this->db->delete( $tbl, '*', __METHOD__ );
				}
			}
		}
	}

	/**
	 * @since 1.18
	 *
	 * @param string $func
	 * @param array $args
	 *
	 * @return mixed
	 * @throws MWException
	 */
	public function __call( $func, $args ) {
		static $compatibility = array(
			'assertEmpty' => 'assertEmpty2', // assertEmpty was added in phpunit 3.7.32
		);

		if ( isset( $compatibility[$func] ) ) {
			return call_user_func_array( array( $this, $compatibility[$func] ), $args );
		} else {
			throw new MWException( "Called non-existent $func method on "
				. get_class( $this ) );
		}
	}

	/**
	 * Used as a compatibility method for phpunit < 3.7.32
	 * @param string $value
	 * @param string $msg
	 */
	private function assertEmpty2( $value, $msg ) {
		$this->assertTrue( $value == '', $msg );
	}

	private static function unprefixTable( $tableName ) {
		global $wgDBprefix;

		return substr( $tableName, strlen( $wgDBprefix ) );
	}

	private static function isNotUnittest( $table ) {
		return strpos( $table, 'unittest_' ) !== 0;
	}

	/**
	 * @since 1.18
	 *
	 * @param DatabaseBase $db
	 *
	 * @return array
	 */
	public static function listTables( $db ) {
		global $wgDBprefix;

		$tables = $db->listTables( $wgDBprefix, __METHOD__ );

		if ( $db->getType() === 'mysql' ) {
			# bug 43571: cannot clone VIEWs under MySQL
			$views = $db->listViews( $wgDBprefix, __METHOD__ );
			$tables = array_diff( $tables, $views );
		}
		$tables = array_map( array( __CLASS__, 'unprefixTable' ), $tables );

		// Don't duplicate test tables from the previous fataled run
		$tables = array_filter( $tables, array( __CLASS__, 'isNotUnittest' ) );

		if ( $db->getType() == 'sqlite' ) {
			$tables = array_flip( $tables );
			// these are subtables of searchindex and don't need to be duped/dropped separately
			unset( $tables['searchindex_content'] );
			unset( $tables['searchindex_segdir'] );
			unset( $tables['searchindex_segments'] );
			$tables = array_flip( $tables );
		}

		return $tables;
	}

	/**
	 * @throws MWException
	 * @since 1.18
	 */
	protected function checkDbIsSupported() {
		if ( !in_array( $this->db->getType(), $this->supportedDBs ) ) {
			throw new MWException( $this->db->getType() . " is not currently supported for unit testing." );
		}
	}

	/**
	 * @since 1.18
	 * @param string $offset
	 * @return mixed
	 */
	public function getCliArg( $offset ) {
		if ( isset( PHPUnitMaintClass::$additionalOptions[$offset] ) ) {
			return PHPUnitMaintClass::$additionalOptions[$offset];
		}
	}

	/**
	 * @since 1.18
	 * @param string $offset
	 * @param mixed $value
	 */
	public function setCliArg( $offset, $value ) {
		PHPUnitMaintClass::$additionalOptions[$offset] = $value;
	}

	/**
	 * Don't throw a warning if $function is deprecated and called later
	 *
	 * @since 1.19
	 *
	 * @param string $function
	 */
	public function hideDeprecated( $function ) {
		wfSuppressWarnings();
		wfDeprecated( $function );
		wfRestoreWarnings();
	}

	/**
	 * Asserts that the given database query yields the rows given by $expectedRows.
	 * The expected rows should be given as indexed (not associative) arrays, with
	 * the values given in the order of the columns in the $fields parameter.
	 * Note that the rows are sorted by the columns given in $fields.
	 *
	 * @since 1.20
	 *
	 * @param string|array $table The table(s) to query
	 * @param string|array $fields The columns to include in the result (and to sort by)
	 * @param string|array $condition "where" condition(s)
	 * @param array $expectedRows An array of arrays giving the expected rows.
	 *
	 * @throws MWException If this test cases's needsDB() method doesn't return true.
	 *         Test cases can use "@group Database" to enable database test support,
	 *         or list the tables under testing in $this->tablesUsed, or override the
	 *         needsDB() method.
	 */
	protected function assertSelect( $table, $fields, $condition, array $expectedRows ) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing database state, the test cases\'s needDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.' );
		}

		$db = wfGetDB( DB_SLAVE );

		$res = $db->select( $table, $fields, $condition, wfGetCaller(), array( 'ORDER BY' => $fields ) );
		$this->assertNotEmpty( $res, "query failed: " . $db->lastError() );

		$i = 0;

		foreach ( $expectedRows as $expected ) {
			$r = $res->fetchRow();
			self::stripStringKeys( $r );

			$i += 1;
			$this->assertNotEmpty( $r, "row #$i missing" );

			$this->assertEquals( $expected, $r, "row #$i mismatches" );
		}

		$r = $res->fetchRow();
		self::stripStringKeys( $r );

		$this->assertFalse( $r, "found extra row (after #$i)" );
	}

	/**
	 * Utility method taking an array of elements and wrapping
	 * each element in its own array. Useful for data providers
	 * that only return a single argument.
	 *
	 * @since 1.20
	 *
	 * @param array $elements
	 *
	 * @return array
	 */
	protected function arrayWrap( array $elements ) {
		return array_map(
			function ( $element ) {
				return array( $element );
			},
			$elements
		);
	}

	/**
	 * Assert that two arrays are equal. By default this means that both arrays need to hold
	 * the same set of values. Using additional arguments, order and associated key can also
	 * be set as relevant.
	 *
	 * @since 1.20
	 *
	 * @param array $expected
	 * @param array $actual
	 * @param bool $ordered If the order of the values should match
	 * @param bool $named If the keys should match
	 */
	protected function assertArrayEquals( array $expected, array $actual,
		$ordered = false, $named = false
	) {
		if ( !$ordered ) {
			$this->objectAssociativeSort( $expected );
			$this->objectAssociativeSort( $actual );
		}

		if ( !$named ) {
			$expected = array_values( $expected );
			$actual = array_values( $actual );
		}

		call_user_func_array(
			array( $this, 'assertEquals' ),
			array_merge( array( $expected, $actual ), array_slice( func_get_args(), 4 ) )
		);
	}

	/**
	 * Put each HTML element on its own line and then equals() the results
	 *
	 * Use for nicely formatting of PHPUnit diff output when comparing very
	 * simple HTML
	 *
	 * @since 1.20
	 *
	 * @param string $expected HTML on oneline
	 * @param string $actual HTML on oneline
	 * @param string $msg Optional message
	 */
	protected function assertHTMLEquals( $expected, $actual, $msg = '' ) {
		$expected = str_replace( '>', ">\n", $expected );
		$actual = str_replace( '>', ">\n", $actual );

		$this->assertEquals( $expected, $actual, $msg );
	}

	/**
	 * Does an associative sort that works for objects.
	 *
	 * @since 1.20
	 *
	 * @param array $array
	 */
	protected function objectAssociativeSort( array &$array ) {
		uasort(
			$array,
			function ( $a, $b ) {
				return serialize( $a ) > serialize( $b ) ? 1 : -1;
			}
		);
	}

	/**
	 * Utility function for eliminating all string keys from an array.
	 * Useful to turn a database result row as returned by fetchRow() into
	 * a pure indexed array.
	 *
	 * @since 1.20
	 *
	 * @param mixed $r The array to remove string keys from.
	 */
	protected static function stripStringKeys( &$r ) {
		if ( !is_array( $r ) ) {
			return;
		}

		foreach ( $r as $k => $v ) {
			if ( is_string( $k ) ) {
				unset( $r[$k] );
			}
		}
	}

	/**
	 * Asserts that the provided variable is of the specified
	 * internal type or equals the $value argument. This is useful
	 * for testing return types of functions that return a certain
	 * type or *value* when not set or on error.
	 *
	 * @since 1.20
	 *
	 * @param string $type
	 * @param mixed $actual
	 * @param mixed $value
	 * @param string $message
	 */
	protected function assertTypeOrValue( $type, $actual, $value = false, $message = '' ) {
		if ( $actual === $value ) {
			$this->assertTrue( true, $message );
		} else {
			$this->assertType( $type, $actual, $message );
		}
	}

	/**
	 * Asserts the type of the provided value. This can be either
	 * in internal type such as boolean or integer, or a class or
	 * interface the value extends or implements.
	 *
	 * @since 1.20
	 *
	 * @param string $type
	 * @param mixed $actual
	 * @param string $message
	 */
	protected function assertType( $type, $actual, $message = '' ) {
		if ( class_exists( $type ) || interface_exists( $type ) ) {
			$this->assertInstanceOf( $type, $actual, $message );
		} else {
			$this->assertInternalType( $type, $actual, $message );
		}
	}

	/**
	 * Returns true if the given namespace defaults to Wikitext
	 * according to $wgNamespaceContentModels
	 *
	 * @param int $ns The namespace ID to check
	 *
	 * @return bool
	 * @since 1.21
	 */
	protected function isWikitextNS( $ns ) {
		global $wgNamespaceContentModels;

		if ( isset( $wgNamespaceContentModels[$ns] ) ) {
			return $wgNamespaceContentModels[$ns] === CONTENT_MODEL_WIKITEXT;
		}

		return true;
	}

	/**
	 * Returns the ID of a namespace that defaults to Wikitext.
	 *
	 * @throws MWException If there is none.
	 * @return int The ID of the wikitext Namespace
	 * @since 1.21
	 */
	protected function getDefaultWikitextNS() {
		global $wgNamespaceContentModels;

		static $wikitextNS = null; // this is not going to change
		if ( $wikitextNS !== null ) {
			return $wikitextNS;
		}

		// quickly short out on most common case:
		if ( !isset( $wgNamespaceContentModels[NS_MAIN] ) ) {
			return NS_MAIN;
		}

		// NOTE: prefer content namespaces
		$namespaces = array_unique( array_merge(
			MWNamespace::getContentNamespaces(),
			array( NS_MAIN, NS_HELP, NS_PROJECT ), // prefer these
			MWNamespace::getValidNamespaces()
		) );

		$namespaces = array_diff( $namespaces, array(
			NS_FILE, NS_CATEGORY, NS_MEDIAWIKI, NS_USER // don't mess with magic namespaces
		) );

		$talk = array_filter( $namespaces, function ( $ns ) {
			return MWNamespace::isTalk( $ns );
		} );

		// prefer non-talk pages
		$namespaces = array_diff( $namespaces, $talk );
		$namespaces = array_merge( $namespaces, $talk );

		// check default content model of each namespace
		foreach ( $namespaces as $ns ) {
			if ( !isset( $wgNamespaceContentModels[$ns] ) ||
				$wgNamespaceContentModels[$ns] === CONTENT_MODEL_WIKITEXT
			) {

				$wikitextNS = $ns;

				return $wikitextNS;
			}
		}

		// give up
		// @todo Inside a test, we could skip the test as incomplete.
		//        But frequently, this is used in fixture setup.
		throw new MWException( "No namespace defaults to wikitext!" );
	}

	/**
	 * Check, if $wgDiff3 is set and ready to merge
	 * Will mark the calling test as skipped, if not ready
	 *
	 * @since 1.21
	 */
	protected function checkHasDiff3() {
		global $wgDiff3;

		# This check may also protect against code injection in
		# case of broken installations.
		wfSuppressWarnings();
		$haveDiff3 = $wgDiff3 && file_exists( $wgDiff3 );
		wfRestoreWarnings();

		if ( !$haveDiff3 ) {
			$this->markTestSkipped( "Skip test, since diff3 is not configured" );
		}
	}

	/**
	 * Check whether we have the 'gzip' commandline utility, will skip
	 * the test whenever "gzip -V" fails.
	 *
	 * Result is cached at the process level.
	 *
	 * @return bool
	 *
	 * @since 1.21
	 */
	protected function checkHasGzip() {
		static $haveGzip;

		if ( $haveGzip === null ) {
			$retval = null;
			wfShellExec( 'gzip -V', $retval );
			$haveGzip = ( $retval === 0 );
		}

		if ( !$haveGzip ) {
			$this->markTestSkipped( "Skip test, requires the gzip utility in PATH" );
		}

		return $haveGzip;
	}

	/**
	 * Check if $extName is a loaded PHP extension, will skip the
	 * test whenever it is not loaded.
	 *
	 * @since 1.21
	 * @param string $extName
	 * @return bool
	 */
	protected function checkPHPExtension( $extName ) {
		$loaded = extension_loaded( $extName );
		if ( !$loaded ) {
			$this->markTestSkipped( "PHP extension '$extName' is not loaded, skipping." );
		}

		return $loaded;
	}

	/**
	 * Asserts that an exception of the specified type occurs when running
	 * the provided code.
	 *
	 * @since 1.21
	 * @deprecated since 1.22 Use setExpectedException
	 *
	 * @param callable $code
	 * @param string $expected
	 * @param string $message
	 */
	protected function assertException( $code, $expected = 'Exception', $message = '' ) {
		$pokemons = null;

		try {
			call_user_func( $code );
		} catch ( Exception $pokemons ) {
			// Gotta Catch 'Em All!
		}

		if ( $message === '' ) {
			$message = 'An exception of type "' . $expected . '" should have been thrown';
		}

		$this->assertInstanceOf( $expected, $pokemons, $message );
	}

	/**
	 * Asserts that the given string is a valid HTML snippet.
	 * Wraps the given string in the required top level tags and
	 * then calls assertValidHtmlDocument().
	 * The snippet is expected to be HTML 5.
	 *
	 * @since 1.23
	 *
	 * @note Will mark the test as skipped if the "tidy" module is not installed.
	 * @note This ignores $wgUseTidy, so we can check for valid HTML even (and especially)
	 *        when automatic tidying is disabled.
	 *
	 * @param string $html An HTML snippet (treated as the contents of the body tag).
	 */
	protected function assertValidHtmlSnippet( $html ) {
		$html = '<!DOCTYPE html><html><head><title>test</title></head><body>' . $html . '</body></html>';
		$this->assertValidHtmlDocument( $html );
	}

	/**
	 * Asserts that the given string is valid HTML document.
	 *
	 * @since 1.23
	 *
	 * @note Will mark the test as skipped if the "tidy" module is not installed.
	 * @note This ignores $wgUseTidy, so we can check for valid HTML even (and especially)
	 *        when automatic tidying is disabled.
	 *
	 * @param string $html A complete HTML document
	 */
	protected function assertValidHtmlDocument( $html ) {
		// Note: we only validate if the tidy PHP extension is available.
		// In case wgTidyInternal is false, MWTidy would fall back to the command line version
		// of tidy. In that case however, we can not reliably detect whether a failing validation
		// is due to malformed HTML, or caused by tidy not being installed as a command line tool.
		// That would cause all HTML assertions to fail on a system that has no tidy installed.
		if ( !$GLOBALS['wgTidyInternal'] ) {
			$this->markTestSkipped( 'Tidy extension not installed' );
		}

		$errorBuffer = '';
		MWTidy::checkErrors( $html, $errorBuffer );
		$allErrors = preg_split( '/[\r\n]+/', $errorBuffer );

		// Filter Tidy warnings which aren't useful for us.
		// Tidy eg. often cries about parameters missing which have actually
		// been deprecated since HTML4, thus we should not care about them.
		$errors = preg_grep(
			'/^(.*Warning: (trimming empty|.* lacks ".*?" attribute).*|\s*)$/m',
			$allErrors, PREG_GREP_INVERT
		);

		$this->assertEmpty( $errors, implode( "\n", $errors ) );
	}

	/**
	 * @param array $matcher
	 * @param string $actual
	 * @param bool $isHtml
	 *
	 * @return bool
	 */
	private static function tagMatch( $matcher, $actual, $isHtml = true ) {
		$dom = PHPUnit_Util_XML::load( $actual, $isHtml );
		$tags = PHPUnit_Util_XML::findNodes( $dom, $matcher, $isHtml );
		return count( $tags ) > 0 && $tags[0] instanceof DOMNode;
	}

	/**
	 * Note: we are overriding this method to remove the deprecated error
	 * @see https://bugzilla.wikimedia.org/show_bug.cgi?id=69505
	 * @see https://github.com/sebastianbergmann/phpunit/issues/1292
	 * @deprecated
	 *
	 * @param array $matcher
	 * @param string $actual
	 * @param string $message
	 * @param bool $isHtml
	 */
	public static function assertTag( $matcher, $actual, $message = '', $isHtml = true ) {
		//trigger_error(__METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		self::assertTrue( self::tagMatch( $matcher, $actual, $isHtml ), $message );
	}

	/**
	 * @see MediaWikiTestCase::assertTag
	 * @deprecated
	 *
	 * @param array $matcher
	 * @param string $actual
	 * @param string $message
	 * @param bool $isHtml
	 */
	public static function assertNotTag( $matcher, $actual, $message = '', $isHtml = true ) {
		//trigger_error(__METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		self::assertFalse( self::tagMatch( $matcher, $actual, $isHtml ), $message );
	}
}
