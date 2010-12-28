<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 * This file is included by phpunit and is NOT in the global scope.
 *
 * @file
 */

if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	echo <<<EOF
You are running these tests directly from phpunit. You may not have all globals correctly set.
Running phpunit.php instead is recommended.
EOF;
	require_once ( dirname( __FILE__ ) . "/phpunit.php" );
}

// Output a notice when running with older versions of PHPUnit
if ( !version_compare( PHPUnit_Runner_Version::id(), "3.4.1", ">" ) ) {
  echo <<<EOF
********************************************************************************

These tests run best with version PHPUnit 3.4.2 or better. Earlier versions may
show failures because earlier versions of PHPUnit do not properly implement
dependencies.

********************************************************************************

EOF;
}

global $wgLocalisationCacheConf, $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
global $wgMessageCache, $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry;
$wgLocalisationCacheConf['storeClass'] =  'LCStore_Null';
$wgMainCacheType = CACHE_NONE;
$wgMessageCacheType = CACHE_NONE;
$wgParserCacheType = CACHE_NONE;
$wgUseDatabaseMessages = false; # Set for future resets

# The message cache was already created in Setup.php
$wgMessageCache = new StubObject( 'wgMessageCache', 'MessageCache',
	array( $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry ) );

/* Classes */

abstract class MediaWikiTestSetup extends PHPUnit_Framework_TestCase {
	protected $suite;
	public $regex = '';
	public $runDisabled = false;
	
	protected static $databaseSetupDone = false;
	protected $db;
	protected $dbClone;
	protected $oldTablePrefix;
	protected $useTemporaryTables = true;

	function __construct( PHPUnit_Framework_TestSuite $suite = null ) {
		if ( null !== $suite ) {
			$this->suite = $suite;
		}
		parent::__construct();
		
		if( $this->needsDB() && !is_object( $this->dbClone ) ) {
			$this->initDB();
			$this->addCoreDBData();
			$this->addDBData();
		}
	}
	
	function __destruct() {
		if( $this->needsDB() && is_object( $this->dbClone ) && $this->dbClone instanceof CloneDatabase ) {
			$this->destroyDB();
		}
	}
	
	function needsDB() { return false; }
	
	function addDBData() {}
	
	private function addCoreDBData() {
		
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

		if ( self::$databaseSetupDone ) {
			return;
		}

		$this->db = wfGetDB( DB_MASTER );
		$dbType = $this->db->getType();
		
		if ( $wgDBprefix === 'unittest_' || ( $dbType == 'oracle' && $wgDBprefix === 'ut_' ) ) {
			throw new MWException( 'Cannot run unit tests, the database prefix is already "unittest_"' );
		}

		self::$databaseSetupDone = true;
		$this->oldTablePrefix = $wgDBprefix;

		# SqlBagOStuff broke when using temporary tables on r40209 (bug 15892).
		# It seems to have been fixed since (r55079?).
		# If it fails, $wgCaches[CACHE_DB] = new HashBagOStuff(); should work around it.

		# CREATE TEMPORARY TABLE breaks if there is more than one server
		if ( wfGetLB()->getServerCount() != 1 ) {
			$this->useTemporaryTables = false;
		}

		$temporary = $this->useTemporaryTables || $dbType == 'postgres';
		
		$tables = $this->listTables();
		
		$prefix = $dbType != 'oracle' ? 'unittest_' : 'ut_';

		$this->dbClone = new CloneDatabase( $this->db, $tables, $prefix );
		$this->dbClone->useTemporaryTables( $temporary );
		$this->dbClone->cloneTableStructure();

		if ( $dbType == 'oracle' )
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;' );

		if ( $dbType == 'oracle' ) {
			# Insert 0 user to prevent FK violations

			# Anonymous user
			$this->db->insert( 'user', array(
				'user_id'         => 0,
				'user_name'       => 'Anonymous' ) );
		}
		
	}
	
	private function destroyDB() {
		if ( !self::$databaseSetupDone ) {
			return;
		}
		
		$this->dbClone->destroy();
		self::$databaseSetupDone = false;

		if ( $this->useTemporaryTables ) {
			# Don't need to do anything
			//return;
			//Temporary tables seem to be broken ATM, delete anyway
		}

		if( $this->db->getType() == 'oracle' ) {
			$tables = $this->db->listTables( 'ut_', __METHOD__ );
		}
		else {
			$tables = $this->db->listTables( 'unittest_', __METHOD__ );
		}
		
		foreach ( $tables as $table ) {
			$sql = $this->db->getType() == 'oracle' ? "DROP TABLE $table DROP CONSTRAINTS" : "DROP TABLE `$table`";
			$this->db->query( $sql );
		}

		if ( $this->db->getType() == 'oracle' )
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;' );
		
		
	}

	function __call( $func, $args ) {
		if ( method_exists( $this->suite, $func ) ) {
			return call_user_func_array( array( $this->suite, $func ), $args);
		} else {
			throw new MWException( "Called non-existant $func method on "
				. get_class( $this ) );
		}
	}
	
	protected function listTables() {
		global $wgDBprefix;
		
		$tables = $this->db->listTables( $wgDBprefix, __METHOD__ );
		$tables = array_map( create_function( '$table', 'global $wgDBprefix; return substr( $table, strlen( $wgDBprefix ) );' ), $tables );
		return $tables;
		
	}
}

