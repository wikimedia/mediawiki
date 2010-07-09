<?php

class MysqlInstaller extends InstallerDBType {
	protected $globalNames = array(
		'wgDBserver',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBprefix',
		'wgDBTableOptions',
		'wgDBmysql5',
	);

	protected $internalDefaults = array(
		'_MysqlEngine' => 'InnoDB',
		'_MysqlCharset' => 'binary',
	);

	var $supportedEngines = array( 'InnoDB', 'MyISAM' );

	var $minimumVersion = '4.0.14';

	var $webUserPrivs = array(
		'DELETE',
		'INSERT',
		'SELECT',
		'UPDATE',
		'CREATE TEMPORARY TABLES',
	);

	function getName() {
		return 'mysql';
	}

	function __construct( $parent ) {
		parent::__construct( $parent );
	}

	public function isCompiled() {
		return self::checkExtension( 'mysql' );
	}

	function getGlobalDefaults() {
		return array();
	}

	function getConnectForm() {
		return
			$this->getTextBox( 'wgDBserver', 'config-db-host' ) .
			$this->parent->getHelpBox( 'config-db-host-help' ) . 
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), wfMsg( 'config-db-wiki-settings' ) ) .
			$this->getTextBox( 'wgDBname', 'config-db-name' ) .
			$this->parent->getHelpBox( 'config-db-name-help' ) .
			$this->getTextBox( 'wgDBprefix', 'config-db-prefix' ) .
			$this->parent->getHelpBox( 'config-db-prefix-help' ) .
			Xml::closeElement( 'fieldset' ) .
			$this->getInstallUserBox();
	}

	function submitConnectForm() {
		// Get variables from the request
		$newValues = $this->setVarsFromRequest( array( 'wgDBserver', 'wgDBname', 'wgDBprefix' ) );

		// Validate them
		$status = Status::newGood();
		if ( !strlen( $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-missing-db-name' );
		} elseif ( !preg_match( '/^[a-zA-Z0-9_]+$/', $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-invalid-db-name', $newValues['wgDBname'] );
		}
		if ( !preg_match( '/^[a-zA-Z0-9_]*$/', $newValues['wgDBprefix'] ) ) {
			$status->fatal( 'config-invalid-db-prefix', $newValues['wgDBprefix'] );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Submit user box
		$status = $this->submitInstallUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		// Try to connect
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		// Check version
		$version = $conn->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-mysql-old', $this->minimumVersion, $version );
		}

		return $status;
	}

	function getConnection() {
		$status = Status::newGood();
		try {
			$this->db = new DatabaseMysql(
				$this->getVar( 'wgDBserver' ),
				$this->getVar( '_InstallUser' ),
				$this->getVar( '_InstallPassword' ),
				false,
				false,
				0, 
				$this->getVar( 'wgDBprefix' )
			);
			$status->value = $this->db;
			return $status;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}
		return $status;
	}

	function doUpgrade() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			$this->parent->showStatusError( $status );
			return;
		}
		$conn = $status->value;

		# Determine existing default character set
		if ( $conn->tableExists( "revision" ) ) {
			$revision = $conn->escapeLike( $this->getVar( 'wgDBprefix' ) . 'revision' );
			$res = $conn->query( "SHOW TABLE STATUS LIKE '$revision'" );
			$row = $conn->fetchObject( $res );
			if ( !$row ) {
				$this->parent->showMessage( 'config-show-table-status' );
				$existingSchema = false;
				$existingEngine = false;
			} else {
				if ( preg_match( '/^latin1/', $row->Collation ) ) {
					$existingSchema = 'mysql4';
				} elseif ( preg_match( '/^utf8/', $row->Collation ) ) {
					$existingSchema = 'mysql5';
				} elseif ( preg_match( '/^binary/', $row->Collation ) ) {
					$existingSchema = 'mysql5-binary';
				} else {
					$existingSchema = false;
					$this->parent->showMessage( 'config-unknown-collation' );
				}
				if ( isset( $row->Engine ) ) {
					$existingEngine = $row->Engine;
				} else {
					$existingEngine = $row->Type;
				}
			}
		}
		
		// TODO
	}

	/**
	 * Get a list of storage engines that are available and supported
	 */
	function getEngines() {
		$engines = array( 'InnoDB', 'MyISAM' );
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $engines;
		}
		$conn = $status->value;

		$version = $conn->getServerVersion();
		if ( version_compare( $version, "4.1.2", "<" ) ) {
			// No SHOW ENGINES in this version
			return $engines;
		}

		$engines = array();
		$res = $conn->query( 'SHOW ENGINES' );
		foreach ( $res as $row ) {
			if ( $row->Support == 'YES' || $row->Support == 'DEFAULT' ) {
				$engines[] = $row->Engine;
			}
		}
		$engines = array_intersect( $this->supportedEngines, $engines );
		return $engines;
	}

	/**
	 * Get a list of character sets that are available and supported
	 */
	function getCharsets() {
		$status = $this->getConnection();
		$mysql5 = array( 'binary', 'utf8' );
		$mysql4 = array( 'mysql4' );
		if ( !$status->isOK() ) {
			return $mysql5;
		}
		if ( version_compare( $status->value->getServerVersion(), '4.1.0', '>=' ) ) {
			return $mysql5;
		}
		return $mysql4;
	}

	/**
	 * Return true if the install user can create accounts
	 */
	function canCreateAccounts() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return false;
		}
		$conn = $status->value;

		// Check version, need INFORMATION_SCHEMA and CREATE USER
		if ( version_compare( $conn->getServerVersion(), '5.0.2', '<' ) ) {
			return false;
		}

		// Get current account name
		$currentName = $conn->selectField( '', 'CURRENT_USER()', '', __METHOD__ );
		$parts = explode( '@', $currentName );
		if ( count( $parts ) != 2 ) {
			return false;
		}
		$quotedUser = $conn->addQuotes( $parts[0] ) . 
			'@' . $conn->addQuotes( $parts[1] );

		// The user needs to have INSERT on mysql.* to be able to CREATE USER
		// The grantee will be double-quoted in this query, as required
		$res = $conn->select( 'INFORMATION_SCHEMA.USER_PRIVILEGES', '*', 
			array( 'GRANTEE' => $quotedUser ), __METHOD__ );
		$insertMysql = false;
		$grantOptions = array_flip( $this->webUserPrivs );
		foreach ( $res as $row ) {
			if ( $row->PRIVILEGE_TYPE == 'INSERT' ) {
				$insertMysql = true;
			}
			if ( $row->IS_GRANTABLE ) {
				unset( $grantOptions[$row->PRIVILEGE_TYPE] );
			}
		}

		// Check for DB-specific privs for mysql.*
		if ( !$insertMysql ) {
			$row = $conn->selectRow( 'INFORMATION_SCHEMA.SCHEMA_PRIVILEGES', '*',
				array( 
					'GRANTEE' => $quotedUser,
					'TABLE_SCHEMA' => 'mysql',
					'PRIVILEGE_TYPE' => 'INSERT',
				), __METHOD__ );
			if ( $row ) {
				$insertMysql = true;
			}
		}

		if ( !$insertMysql ) {
			return false;
		}

		// Check for DB-level grant options
		$res = $conn->select( 'INFORMATION_SCHEMA.SCHEMA_PRIVILEGES', '*', 
			array(
				'GRANTEE' => $quotedUser,
				'IS_GRANTABLE' => 1,
			), __METHOD__ );
		foreach ( $res as $row ) {
			$regex = $conn->likeToRegex( $row->TABLE_SCHEMA );
			if ( preg_match( $regex, $this->getVar( 'wgDBname' ) ) ) {
				unset( $grantOptions[$row->PRIVILEGE_TYPE] );
			}
		}
		if ( count( $grantOptions ) ) {
			// Can't grant everything
			return false;
		}
		return true;
	}

	function getSettingsForm() {
		if ( $this->canCreateAccounts() ) {
			$noCreateMsg = false;
		} else {
			$noCreateMsg = 'config-db-web-no-create-privs';
		}
		$s = $this->getWebUserBox( $noCreateMsg );

		// Do engine selector
		$engines = $this->getEngines();
		// If the current default engine is not supported, use an engine that is
		if ( !in_array( $this->getVar( '_MysqlEngine' ), $engines ) ) {
			$this->setVar( '_MysqlEngine', reset( $engines ) );
		}
		if ( count( $engines ) >= 2 ) {
			$s .= $this->getRadioSet( array(
				'var' => '_MysqlEngine', 
				'label' => 'config-mysql-engine', 
				'itemLabelPrefix' => 'config-mysql-', 
				'values' => $engines
			));
			$s .= $this->parent->getHelpBox( 'config-mysql-engine-help' );
		}

		// If the current default charset is not supported, use a charset that is
		$charsets = $this->getCharsets();
		if ( !in_array( $this->getVar( '_MysqlCharset' ), $charsets ) ) {
			$this->setVar( '_MysqlCharset', reset( $charsets ) );
		}

		// Do charset selector
		if ( count( $charsets ) >= 2 ) {
			$s .= $this->getRadioSet( array(
				'var' => '_MysqlCharset',
				'label' => 'config-mysql-charset',
				'itemLabelPrefix' => 'config-mysql-',
				'values' => $charsets
			));
			$s .= $this->parent->getHelpBox( 'config-mysql-charset-help' );
		}

		return $s;
	}

	function submitSettingsForm() {
		$newValues = $this->setVarsFromRequest( array( '_MysqlEngine', '_MysqlCharset' ) );
		$status = $this->submitWebUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		// Validate the create checkbox
		$canCreate = $this->canCreateAccounts();
		if ( !$canCreate ) {
			$this->setVar( '_CreateDBAccount', false );
			$create = false;
		} else {
			$create = $this->getVar( '_CreateDBAccount' );
		}

		if ( !$create ) {
			// Test the web account
			try {
				$webConn = new Database( 
					$this->getVar( 'wgDBserver' ),
					$this->getVar( 'wgDBuser' ),
					$this->getVar( 'wgDBpassword' ),
					false,
					false,
					0, 
					$this->getVar( 'wgDBprefix' )
				);
			} catch ( DBConnectionError $e ) {
				return Status::newFatal( 'config-connection-error', $e->getMessage() );
			}
		}

		// Validate engines and charsets
		// This is done pre-submit already so it's just for security
		$engines = $this->getEngines();
		if ( !in_array( $this->getVar( '_MysqlEngine' ), $engines ) ) {
			$this->setVar( '_MysqlEngine', reset( $engines ) );
		}
		$charsets = $this->getCharsets();
		if ( !in_array( $this->getVar( '_MysqlCharset' ), $charsets ) ) {
			$this->setVar( '_MysqlCharset', reset( $charsets ) );
		}
		return Status::newGood();
	}

	public function preInstall() {
		# Add our user callback to installSteps, right before the tables are created.
		$callback = array(
			array(
				'name' => 'user',
				'callback' => array( $this, 'setupUser' ),
			)
		);
		$this->parent->addInstallStepFollowing( "tables", $callback );
	}

	function setupDatabase() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;
		$dbName = $this->getVar( 'wgDBname' );
		if( !$conn->selectDB( $dbName ) ) {
			$conn->query( "CREATE DATABASE `$dbName`" );
			$conn->selectDB( $dbName );
		}
		return $status;
	}

	function setupUser() {
		global $IP;

		if ( !$this->getVar( '_CreateDBAccount' ) ) {
			return Status::newGood();
		}

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		$db = $this->getVar( 'wgDBname' );
		$this->db->selectDB( $db );
		$error = $this->db->sourceFile( "$IP/maintenance/users.sql" );
		if ( $error !== true ) {
			$status->fatal( 'config-install-user-failed', $this->getVar( 'wgDBuser' ), $error );
		}

		return $status;
	}

	function createTables() {
		global $IP;
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->db->selectDB( $this->getVar( 'wgDBname' ) );
		
		if( $this->db->tableExists( 'user' ) ) {
			$status->warning( 'config-install-tables-exist' );
			return $status;
		} 
		
		$error = $this->db->sourceFile( "$IP/maintenance/tables.sql" );
		if( $error !== true ) {
			$status->fatal( 'config-install-tables-failed', $error );
		}
		return $status;
	}

	function getTableOptions() {
		return array( 'engine' => $this->getVar( '_MysqlEngine' ),
			'default charset' => $this->getVar( '_MysqlCharset' ) );
	}

	function getLocalSettings() {
		$dbmysql5 = wfBoolToStr( $this->getVar( 'wgDBmysql5', true ) );
		$prefix = $this->getVar( 'wgDBprefix' );
		$opts = $this->getTableOptions();
		$tblOpts = "ENGINE=" . $opts['engine'] . ', DEFAULT CHARSET=' . $opts['default charset'];
		return
"# MySQL specific settings
\$wgDBprefix         = \"{$prefix}\";

# MySQL table options to use during installation or update
\$wgDBTableOptions   = \"{$tblOpts}\";

# Experimental charset support for MySQL 4.1/5.0.
\$wgDBmysql5 = {$dbmysql5};";
	}
}
