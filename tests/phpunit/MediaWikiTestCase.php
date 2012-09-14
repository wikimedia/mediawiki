<?php

abstract class MediaWikiTestCase extends PHPUnit_Framework_TestCase {
	public $suite;
	public $regex = '';
	public $runDisabled = false;

	/**
	 * @var Array of TestUser
	 */
	public static $users;

	/**
	 * @var DatabaseBase
	 */
	protected $db;
	protected $oldTablePrefix;
	protected $useTemporaryTables = true;
	protected $reuseDB = false;
	protected $tablesUsed = array(); // tables with data

	private static $dbSetup = false;

	/**
	 * Holds the paths of temporary files/directories created through getNewTempFile,
	 * and getNewTempDirectory
	 *
	 * @var array
	 */
	private $tmpfiles = array();


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

	function  __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->backupGlobals = false;
		$this->backupStaticAttributes = false;
	}

	function run( PHPUnit_Framework_TestResult $result = NULL ) {
		/* Some functions require some kind of caching, and will end up using the db,
		 * which we can't allow, as that would open a new connection for mysql.
		 * Replace with a HashBag. They would not be going to persist anyway.
		 */
		ObjectCache::$instances[CACHE_DB] = new HashBagOStuff;

		if( $this->needsDB() ) {
			global $wgDBprefix;
			
			$this->useTemporaryTables = !$this->getCliArg( 'use-normal-tables' );
			$this->reuseDB = $this->getCliArg('reuse-db');

			$this->db = wfGetDB( DB_MASTER );

			$this->checkDbIsSupported();

			$this->oldTablePrefix = $wgDBprefix;

			if( !self::$dbSetup ) {
				$this->initDB();
				self::$dbSetup = true;
			}

			$this->addCoreDBData();
			$this->addDBData();

			parent::run( $result );

			$this->resetDB();
		} else {
			parent::run( $result );
		}
	}

	/**
	 * obtains a new temporary file name
	 *
	 * The obtained filename is enlisted to be removed upon tearDown
	 *
	 * @returns string: absolute name of the temporary file
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
	 * @returns string: absolute name of the temporary directory
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

	protected function tearDown() {
		// Cleaning up temporary files
		foreach ( $this->tmpfiles as $fname ) {
			if ( is_file( $fname ) || ( is_link( $fname ) ) ) {
				unlink( $fname );
			} elseif ( is_dir( $fname ) ) {
				wfRecursiveRemoveDir( $fname );
			}
		}

		// clean up open transactions
		if( $this->needsDB() && $this->db ) {
			while( $this->db->trxLevel() > 0 ) {
				$this->db->rollback();
			}
		}

		parent::tearDown();
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
	function addDBData() {}

	private function addCoreDBData() {
		# disabled for performance
		#$this->tablesUsed[] = 'page';
		#$this->tablesUsed[] = 'revision';

		if ( $this->db->getType() == 'oracle' ) {

			# Insert 0 user to prevent FK violations
			# Anonymous user
			$this->db->insert( 'user', array(
				'user_id' 		=> 0,
				'user_name'   	=> 'Anonymous' ), __METHOD__, array( 'IGNORE' ) );

			# Insert 0 page to prevent FK violations
			# Blank page
			$this->db->insert( 'page', array(
				'page_id' => 0,
				'page_namespace' => 0,
				'page_title' => ' ',
				'page_restrictions' => NULL,
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
			$page->doEdit( 'UTContent',
							'UTPageSummary',
							EDIT_NEW,
							false,
							User::newFromName( 'UTSysop' ) );
		}
	}

	private function initDB() {
		global $wgDBprefix;
		if ( $wgDBprefix === $this->dbPrefix() ) {
			throw new MWException( 'Cannot run unit tests, the database prefix is already "unittest_"' );
		}

		$tablesCloned = $this->listTables();
		$dbClone = new CloneDatabase( $this->db, $tablesCloned, $this->dbPrefix() );
		$dbClone->useTemporaryTables( $this->useTemporaryTables );

		if ( ( $this->db->getType() == 'oracle' || !$this->useTemporaryTables ) && $this->reuseDB ) {
			CloneDatabase::changePrefix( $this->dbPrefix() );
			$this->resetDB();
			return;
		} else {
			$dbClone->cloneTableStructure();
		}

		if ( $this->db->getType() == 'oracle' ) {
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;' );
		}
	}

	/**
	 * Empty all tables so they can be repopulated for tests
	 */
	private function resetDB() {
		if( $this->db ) {
			if ( $this->db->getType() == 'oracle' )  {
				if ( $this->useTemporaryTables ) {
					wfGetLB()->closeAll();
					$this->db = wfGetDB( DB_MASTER );
				} else {
					foreach( $this->tablesUsed as $tbl ) {
						if( $tbl == 'interwiki') continue;
						$this->db->query( 'TRUNCATE TABLE '.$this->db->tableName($tbl), __METHOD__ );
					}
				}
			} else {
				foreach( $this->tablesUsed as $tbl ) {
					if( $tbl == 'interwiki' || $tbl == 'user' ) continue;
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
			return call_user_func_array( array( $this->suite, $func ), $args);
		} elseif ( isset( $compatibility[$func] ) ) {
			return call_user_func_array( array( $this, $compatibility[$func] ), $args);
		} else {
			throw new MWException( "Called non-existant $func method on "
				. get_class( $this ) );
		}
	}

	private function assertEmpty2( $value, $msg ) {
		return $this->assertTrue( $value == '', $msg );
	}

	static private function unprefixTable( $tableName ) {
		global $wgDBprefix;
		return substr( $tableName, strlen( $wgDBprefix ) );
	}

	static private function isNotUnittest( $table ) {
		return strpos( $table, 'unittest_' ) !== 0;
	}

	protected function listTables() {
		global $wgDBprefix;

		$tables = $this->db->listTables( $wgDBprefix, __METHOD__ );
		$tables = array_map( array( __CLASS__, 'unprefixTable' ), $tables );

		// Don't duplicate test tables from the previous fataled run
		$tables = array_filter( $tables, array( __CLASS__, 'isNotUnittest' ) );

		if ( $this->db->getType() == 'sqlite' ) {
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
		if( !in_array( $this->db->getType(), $this->supportedDBs ) ) {
			throw new MWException( $this->db->getType() . " is not currently supported for unit testing." );
		}
	}

	public function getCliArg( $offset ) {

		if( isset( MediaWikiPHPUnitCommand::$additionalOptions[$offset] ) ) {
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
	protected function assertSelect( $table, $fields, $condition, Array $expectedRows ) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing database state, the test cases\'s needDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.');
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
			function( $element ) {
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
	protected function assertHTMLEquals( $expected, $actual, $msg='' ) {
		$expected = str_replace( '>', ">\n", $expected );
		$actual   = str_replace( '>', ">\n", $actual   );

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
			function( $a, $b ) {
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
		}
		else {
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
		if ( is_object( $actual ) ) {
			$this->assertInstanceOf( $type, $actual, $message );
		}
		else {
			$this->assertInternalType( $type, $actual, $message );
		}
	}

}
