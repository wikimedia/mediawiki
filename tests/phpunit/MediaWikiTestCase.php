<?php

abstract class MediaWikiTestCase extends PHPUnit_Framework_TestCase {
	public $suite;
	public $regex = '';
	public $runDisabled = false;

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
	 * @var Array of TestUser
	 */
	public static $users;

	/**
	 * @var DatabaseBase
	 */
	protected $db;
	protected $tablesUsed = array(); // tables with data

	private static $useTemporaryTables = true;
	private static $reuseDB = false;
	private static $dbSetup = false;
	private static $oldTablePrefix = false;

	/**
	 * Holds the paths of temporary files/directories created through getNewTempFile,
	 * and getNewTempDirectory
	 *
	 * @var array
	 */
	private $tmpfiles = array();

	/**
	 * Holds original values of MediaWiki configuration settings
	 * to be restored in tearDown().
	 * See also setMwGlobal().
	 * @var array
	 */
	private $mwGlobals = array();

	/**
	 * Table name prefixes. Oracle likes it shorter.
	 */
	const DB_PREFIX = 'unittest_';
	const ORA_DB_PREFIX = 'ut_';

	protected $supportedDBs = array(
		'mysql',
		'sqlite',
		'postgres',
		'oracle'
	);

	function __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->backupGlobals = false;
		$this->backupStaticAttributes = false;
	}

	function run( PHPUnit_Framework_TestResult $result = null ) {
		/* Some functions require some kind of caching, and will end up using the db,
		 * which we can't allow, as that would open a new connection for mysql.
		 * Replace with a HashBag. They would not be going to persist anyway.
		 */
		ObjectCache::$instances[CACHE_DB] = new HashBagOStuff;

		$needsResetDB = false;
		$logName = get_class( $this ) . '::' . $this->getName( false );

		if ( $this->needsDB() ) {
			// set up a DB connection for this test to use

			self::$useTemporaryTables = !$this->getCliArg( 'use-normal-tables' );
			self::$reuseDB = $this->getCliArg( 'reuse-db' );

			$this->db = wfGetDB( DB_MASTER );

			$this->checkDbIsSupported();

			if ( !self::$dbSetup ) {
				wfProfileIn( $logName . ' (clone-db)' );

				// switch to a temporary clone of the database
				self::setupTestDB( $this->db, $this->dbPrefix() );

				if ( ( $this->db->getType() == 'oracle' || !self::$useTemporaryTables ) && self::$reuseDB ) {
					$this->resetDB();
				}

				wfProfileOut( $logName . ' (clone-db)' );
			}

			wfProfileIn( $logName . ' (prepare-db)' );
			$this->addCoreDBData();
			$this->addDBData();
			wfProfileOut( $logName . ' (prepare-db)' );

			$needsResetDB = true;
		}

		wfProfileIn( $logName );
		parent::run( $result );
		wfProfileOut( $logName );

		if ( $needsResetDB ) {
			wfProfileIn( $logName . ' (reset-db)' );
			$this->resetDB();
			wfProfileOut( $logName . ' (reset-db)' );
		}
	}

	function usesTemporaryTables() {
		return self::$useTemporaryTables;
	}

	/**
	 * obtains a new temporary file name
	 *
	 * The obtained filename is enlisted to be removed upon tearDown
	 *
	 * @return string: absolute name of the temporary file
	 */
	protected function getNewTempFile() {
		$fname = tempnam( wfTempDir(), 'MW_PHPUnit_' . get_class( $this ) . '_' );
		$this->tmpfiles[] = $fname;

		return $fname;
	}

	/**
	 * obtains a new temporary directory
	 *
	 * The obtained directory is enlisted to be removed (recursively with all its contained
	 * files) upon tearDown.
	 *
	 * @return string: absolute name of the temporary directory
	 */
	protected function getNewTempDirectory() {
		// Starting of with a temporary /file/.
		$fname = $this->getNewTempFile();

		// Converting the temporary /file/ to a /directory/
		//
		// The following is not atomic, but at least we now have a single place,
		// where temporary directory creation is bundled and can be improved
		unlink( $fname );
		$this->assertTrue( wfMkdirParents( $fname ) );

		return $fname;
	}

	/**
	 * setUp and tearDown should (where significant)
	 * happen in reverse order.
	 */
	protected function setUp() {
		wfProfileIn( __METHOD__ );
		parent::setUp();
		$this->called['setUp'] = 1;

		/*
		// @todo global variables to restore for *every* test
		array(
			'wgLang',
			'wgContLang',
			'wgLanguageCode',
			'wgUser',
			'wgTitle',
		);
		*/

		// Cleaning up temporary files
		foreach ( $this->tmpfiles as $fname ) {
			if ( is_file( $fname ) || ( is_link( $fname ) ) ) {
				unlink( $fname );
			} elseif ( is_dir( $fname ) ) {
				wfRecursiveRemoveDir( $fname );
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

		wfProfileOut( __METHOD__ );
	}

	protected function tearDown() {
		wfProfileIn( __METHOD__ );

		// Cleaning up temporary files
		foreach ( $this->tmpfiles as $fname ) {
			if ( is_file( $fname ) || ( is_link( $fname ) ) ) {
				unlink( $fname );
			} elseif ( is_dir( $fname ) ) {
				wfRecursiveRemoveDir( $fname );
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

		parent::tearDown();
		wfProfileOut( __METHOD__ );
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
	 * Individual test functions may override globals (either directly or through this
	 * setMwGlobals() function), however one must call this method at least once for
	 * each key within the setUp().
	 * That way the key is added to the array of globals that will be reset afterwards
	 * in the tearDown(). And, equally important, that way all other tests are executed
	 * with the same settings (instead of using the unreliable local settings for most
	 * tests and fix it only for some tests).
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
	 */
	protected function setMwGlobals( $pairs, $value = null ) {

		// Normalize (string, value) to an array
		if ( is_string( $pairs ) ) {
			$pairs = array( $pairs => $value );
		}

		foreach ( $pairs as $key => $value ) {
			// NOTE: make sure we only save the global once or a second call to
			// setMwGlobals() on the same global would override the original
			// value.
			if ( !array_key_exists( $key, $this->mwGlobals ) ) {
				$this->mwGlobals[$key] = $GLOBALS[$key];
			}

			// Override the global
			$GLOBALS[$key] = $value;
		}
	}

	/**
	 * Merges the given values into a MW global array variable.
	 * Useful for setting some entries in a configuration array, instead of
	 * setting the entire array.
	 *
	 * @param String $name The name of the global, as in wgFooBar
	 * @param Array $values The array containing the entries to set in that global
	 *
	 * @throws MWException if the designated global is not an array.
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

	function dbPrefix() {
		return $this->db->getType() == 'oracle' ? self::ORA_DB_PREFIX : self::DB_PREFIX;
	}

	function needsDB() {
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
	 * Stub. If a test needs to add additional data to the database, it should
	 * implement this method and do so
	 */
	function addDBData() {
	}

	private function addCoreDBData() {
		# disabled for performance
		#$this->tablesUsed[] = 'page';
		#$this->tablesUsed[] = 'revision';

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
				'page_counter' => 0,
				'page_is_redirect' => 0,
				'page_is_new' => 0,
				'page_random' => 0,
				'page_touched' => $this->db->timestamp(),
				'page_latest' => 0,
				'page_len' => 0 ), __METHOD__, array( 'IGNORE' ) );
		}

		User::resetIdByNameCache();

		//Make sysop user
		$user = User::newFromName( 'UTSysop' );

		if ( $user->idForName() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTSysopPassword' );

			$user->addGroup( 'sysop' );
			$user->addGroup( 'bureaucrat' );
			$user->saveSettings();
		}

		//Make 1 page with 1 revision
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		if ( !$page->getId() == 0 ) {
			$page->doEditContent(
				new WikitextContent( 'UTContent' ),
				'UTPageSummary',
				EDIT_NEW,
				false,
				User::newFromName( 'UTSysop' ) );
		}
	}

	/**
	 * Restores MediaWiki to using the table set (table prefix) it was using before
	 * setupTestDB() was called. Useful if we need to perform database operations
	 * after the test run has finished (such as saving logs or profiling info).
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
	 * @note: the original table prefix is stored in self::$oldTablePrefix. This is used
	 * by teardownTestDB() to return the wiki to using the original table set.
	 *
	 * @note: this method only works when first called. Subsequent calls have no effect,
	 * even if using different parameters.
	 *
	 * @param DatabaseBase $db The database connection
	 * @param String $prefix The prefix to use for the new table set (aka schema).
	 *
	 * @throws MWException if the database table prefix is already $prefix
	 */
	public static function setupTestDB( DatabaseBase $db, $prefix ) {
		global $wgDBprefix;
		if ( $wgDBprefix === $prefix ) {
			throw new MWException( 'Cannot run unit tests, the database prefix is already "' . $prefix . '"' );
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

	function __call( $func, $args ) {
		static $compatibility = array(
			'assertInternalType' => 'assertType',
			'assertNotInternalType' => 'assertNotType',
			'assertInstanceOf' => 'assertType',
			'assertEmpty' => 'assertEmpty2',
		);

		if ( method_exists( $this->suite, $func ) ) {
			return call_user_func_array( array( $this->suite, $func ), $args );
		} elseif ( isset( $compatibility[$func] ) ) {
			return call_user_func_array( array( $this, $compatibility[$func] ), $args );
		} else {
			throw new MWException( "Called non-existant $func method on "
				. get_class( $this ) );
		}
	}

	private function assertEmpty2( $value, $msg ) {
		return $this->assertTrue( $value == '', $msg );
	}

	private static function unprefixTable( $tableName ) {
		global $wgDBprefix;

		return substr( $tableName, strlen( $wgDBprefix ) );
	}

	private static function isNotUnittest( $table ) {
		return strpos( $table, 'unittest_' ) !== 0;
	}

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

	protected function checkDbIsSupported() {
		if ( !in_array( $this->db->getType(), $this->supportedDBs ) ) {
			throw new MWException( $this->db->getType() . " is not currently supported for unit testing." );
		}
	}

	public function getCliArg( $offset ) {

		if ( isset( MediaWikiPHPUnitCommand::$additionalOptions[$offset] ) ) {
			return MediaWikiPHPUnitCommand::$additionalOptions[$offset];
		}
	}

	public function setCliArg( $offset, $value ) {

		MediaWikiPHPUnitCommand::$additionalOptions[$offset] = $value;
	}

	/**
	 * Don't throw a warning if $function is deprecated and called later
	 *
	 * @param $function String
	 * @return null
	 */
	function hideDeprecated( $function ) {
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
	 * @param $table String|Array the table(s) to query
	 * @param $fields String|Array the columns to include in the result (and to sort by)
	 * @param $condition String|Array "where" condition(s)
	 * @param $expectedRows Array - an array of arrays giving the expected rows.
	 *
	 * @throws MWException if this test cases's needsDB() method doesn't return true.
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
	 * each element in it's own array. Useful for data providers
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
	 * @param boolean $ordered If the order of the values should match
	 * @param boolean $named If the keys should match
	 */
	protected function assertArrayEquals( array $expected, array $actual, $ordered = false, $named = false ) {
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
	 * @param String $expected HTML on oneline
	 * @param String $actual HTML on oneline
	 * @param String $msg Optional message
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
	 * @param $r mixed the array to remove string keys from.
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
	 * Throws an MWException if there is none.
	 *
	 * @return int the ID of the wikitext Namespace
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
}
