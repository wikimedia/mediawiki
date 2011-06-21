<?php

abstract class MediaWikiTestCase extends PHPUnit_Framework_TestCase {
	public $suite;
	public $regex = '';
	public $runDisabled = false;

	/**
	 * @var DatabaseBase
	 */
	protected $db;
	protected $oldTablePrefix;
	protected $useTemporaryTables = true;
	private static $dbSetup = false;

	/**
	 * Table name prefixes. Oracle likes it shorter.
	 */
	const DB_PREFIX = 'unittest_';
	const ORA_DB_PREFIX = 'ut_';

	protected $supportedDBs = array(
		'mysql',
		'sqlite',
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

	function dbPrefix() {
		return $this->db->getType() == 'oracle' ? self::ORA_DB_PREFIX : self::DB_PREFIX;
	}

	function needsDB() {
		$rc = new ReflectionClass( $this );
		return strpos( $rc->getDocComment(), '@group Database' ) !== false;
	}

	/**
	 * Stub. If a test needs to add additional data to the database, it should
	 * implement this method and do so
	 */
	function addDBData() {}

	private function addCoreDBData() {

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
		$article = new Article( Title::newFromText( 'UTPage' ) );
		$article->doEdit( 'UTContent',
							'UTPageSummary',
							EDIT_NEW,
							false,
							User::newFromName( 'UTSysop' ) );
	}

	private function initDB() {
		global $wgDBprefix;
		if ( $wgDBprefix === $this->dbPrefix() ) {
			throw new MWException( 'Cannot run unit tests, the database prefix is already "unittest_"' );
		}

		$dbClone = new CloneDatabase( $this->db, $this->listTables(), $this->dbPrefix() );
		$dbClone->useTemporaryTables( $this->useTemporaryTables );
		$dbClone->cloneTableStructure();

		if ( $this->db->getType() == 'oracle' ) {
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;' );

			# Insert 0 user to prevent FK violations
			# Anonymous user
			$this->db->insert( 'user', array(
				'user_id' 		=> 0,
				'user_name'   	=> 'Anonymous' ) );
		}
	}

	/**
	 * Empty all tables so they can be repopulated for tests
	 */
	private function resetDB() {
		if( $this->db ) {
			foreach( $this->listTables() as $tbl ) {
				if( $tbl == 'interwiki' || $tbl == 'user' ) continue;
				$this->db->delete( $tbl, '*', __METHOD__ );
			}
		}
	}

	protected function destroyDB() {
		if ( $this->useTemporaryTables || is_null( $this->db ) ) {
			# Don't need to do anything
			return;
		}

		$tables = $this->db->listTables( $this->dbPrefix(), __METHOD__ );

		foreach ( $tables as $table ) {
			try {
				$sql = $this->db->getType() == 'oracle' ? "DROP TABLE $table CASCADE CONSTRAINTS PURGE" : "DROP TABLE `$table`";
				$this->db->query( $sql, __METHOD__ );
			} catch( MWException $mwe ) {}
		}

		if ( $this->db->getType() == 'oracle' )
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;', __METHOD__ );

		CloneDatabase::changePrefix( $this->oldTablePrefix );
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

	public static function disableInterwikis( $prefix, &$data ) {
		return false;
	}
}

