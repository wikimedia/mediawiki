<?php

abstract class MediaWikiTestCase extends PHPUnit_Framework_TestCase {
	public $suite;
	public $regex = '';
	public $runDisabled = false;
	
	protected $db;
	protected $dbClone;
	protected $oldTablePrefix;
	protected $useTemporaryTables = true;
	
	protected $supportedDBs = array(
		'mysql',
		'sqlite'
	);

	function  __construct( $name = null, array $data = array(), $dataName = '' ) {
		if ($name !== null) {
			$this->setName($name);
		}

		$this->data = $data;
		$this->dataName = $dataName;
		
		$this->backupGlobals = false;
        $this->backupStaticAttributes = false;
	}
	
	function run( PHPUnit_Framework_TestResult $result = NULL ) {
		
		if( $this->needsDB() ) {
		
			global $wgDBprefix;
			
			$this->db = wfGetDB( DB_MASTER );
			
			$this->checkDbIsSupported();
			
			$this->oldTablePrefix = $wgDBprefix;
			
			$this->destroyDB();
			
			$this->initDB();
			$this->addCoreDBData();
			$this->addDBData();
			
			parent::run( $result );
		
			$this->destroyDB();
		}
		else {
			parent::run( $result );
		
		}
		
	}
	
	function __destruct() {
		if( $this->needsDB() ) {
			$this->destroyDB();
		}
	}
	
	function needsDB() {
		$rc = new ReflectionClass( $this );
		return strpos( $rc->getDocComment(), '@group Database' ) !== false;
	}
	
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

		$dbType = $this->db->getType();
		
		if ( $wgDBprefix === 'unittest_' || ( $dbType == 'oracle' && $wgDBprefix === 'ut_' ) ) {
			throw new MWException( 'Cannot run unit tests, the database prefix is already "unittest_"' );
		}

		$tables = $this->listTables();
		
		$prefix = $dbType != 'oracle' ? 'unittest_' : 'ut_';
		
		$this->dbClone = new CloneDatabase( $this->db, $tables, $prefix );
		$this->dbClone->useTemporaryTables( false ); //reported problems with temp tables, disabling until fixed
		$this->dbClone->cloneTableStructure();
		
		if ( $dbType == 'oracle' )
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;' );

		if ( $dbType == 'oracle' ) {
			# Insert 0 user to prevent FK violations
			
			# Anonymous user
			$this->db->insert( 'user', array(
				'user_id' 		=> 0,
				'user_name'   	=> 'Anonymous' ) );
		}
		
	}
	
	protected function destroyDB() {
		global $wgDBprefix;
		
		if ( $this->useTemporaryTables ) {
			# Don't need to do anything
			//return;
			//Temporary tables seem to be broken ATM, delete anyway
		}
		
		if( is_null( $this->db ) ) {
			return;
		}
		
		if( $this->db->getType() == 'oracle' ) {
			$tables = $this->db->listTables( 'ut_', __METHOD__ );
		}
		else {
			$tables = $this->db->listTables( 'unittest_', __METHOD__ );
		}
		
		foreach ( $tables as $table ) {
			try {
				$sql = $this->db->getType() == 'oracle' ? "DROP TABLE $table DROP CONSTRAINTS" : "DROP TABLE `$table`";
				$this->db->query( $sql, __METHOD__ );
			} catch( Exception $e ) {
			}
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
	
	static private function unprefixTable( $tableName ) {
		global $wgDBprefix;
		return substr( $tableName, strlen( $wgDBprefix ) );
	}

	protected function listTables() {
		global $wgDBprefix;
		
		$tables = $this->db->listTables( $wgDBprefix, __METHOD__ );
		$tables = array_map( array( __CLASS__, 'unprefixTable' ), $tables );
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
}

