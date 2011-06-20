<?php
/**
 * IBM_DB2-specific installer.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for setting up the MediaWiki database using IBM_DB2.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class Ibm_db2Installer extends DatabaseInstaller {


	protected $globalNames = array(
		'wgDBserver',
		'wgDBport',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBmwschema',
	);

	protected $internalDefaults = array(
		'_InstallUser' => 'db2admin'
	);

	/**
	 * Get the DB2 database extension name
	 * @return string
	 */
	public function getName(){
		return 'ibm_db2';
	}

	/**
	 * Determine whether the DB2 database extension is currently available in PHP
	 * @return boolean
	 */
	public function isCompiled() {
		return self::checkExtension( 'ibm_db2' );
	}

	/**
	 * Generate a connection form for a DB2 database
	 * @return string
	 */
	public function getConnectForm() {
		return
			$this->getTextBox( 'wgDBserver', 'config-db-host', array(), $this->parent->getHelpBox( 'config-db-host-help' ) ) .
			$this->getTextBox( 'wgDBport', 'config-db-port', array(), $this->parent->getHelpBox( 'config-db-port' ) ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-wiki-settings' ) ) .
			$this->getTextBox( 'wgDBname', 'config-db-name', array(), $this->parent->getHelpBox( 'config-db-name-help' ) ) .
			$this->getTextBox( 'wgDBmwschema', 'config-db-schema', array(), $this->parent->getHelpBox( 'config-db-schema-help' ) ) .
			Html::closeElement( 'fieldset' ) .
			$this->getInstallUserBox();
	}

	/**
	 * Validate and then execute the connection form for a DB2 database
	 * @return Status
	 */
	public function submitConnectForm() {
		// Get variables from the request
		$newValues = $this->setVarsFromRequest(
			array( 'wgDBserver', 'wgDBport', 'wgDBname',
				'wgDBmwschema', 'wgDBuser', 'wgDBpassword' ) );

		// Validate them
		$status = Status::newGood();
		if ( !strlen( $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-missing-db-name' );
		} elseif ( !preg_match( '/^[a-zA-Z0-9_]+$/', $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-invalid-db-name', $newValues['wgDBname'] );
		}
		if ( !strlen( $newValues['wgDBmwschema'] ) ) {
			$status->fatal( 'config-invalid-schema' );
		}
		elseif ( !preg_match( '/^[a-zA-Z0-9_]*$/', $newValues['wgDBmwschema'] ) ) {
			$status->fatal( 'config-invalid-schema', $newValues['wgDBmwschema'] );
		}
		if ( !strlen( $newValues['wgDBport'] ) ) {
			$status->fatal( 'config-invalid-port' );
		}
		elseif ( !preg_match( '/^[0-9_]*$/', $newValues['wgDBport'] ) ) {
			$status->fatal( 'config-invalid-port', $newValues['wgDBport'] );
		}

		// Submit user box
		if ( $status->isOK() ) {
			$status->merge( $this->submitInstallUserBox() );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		global $wgDBport;
		$wgDBport = $newValues['wgDBport'];

		// Try to connect
		$status->merge( $this->getConnection() );
		if ( !$status->isOK() ) {
			return $status;
		}

		$this->parent->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
		$this->parent->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );

		return $status;
	}

	/**
	 * Open a DB2 database connection
	 * @return Status
	 */
	public function openConnection() {
		$status = Status::newGood();
		try {
			$db = new DatabaseIbm_db2(
				$this->getVar( 'wgDBserver' ),
				$this->getVar( '_InstallUser' ),
				$this->getVar( '_InstallPassword' ),
				$this->getVar( 'wgDBname' ),
				0,
				$this->getVar( 'wgDBmwschema' )
			);
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}
		return $status;
	}

	/**
	 * Create a DB2 database for MediaWiki
	 * @return Status
	 */
	public function setupDatabase() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;
		$dbName = $this->getVar( 'wgDBname' );
		if( !$conn->selectDB( $dbName ) ) {
			$conn->query( "CREATE DATABASE "
				. $conn->addIdentifierQuotes( $dbName )
				. " AUTOMATIC STORAGE YES"
				. " USING CODESET UTF-8 TERRITORY US COLLATE USING SYSTEM"
				. " PAGESIZE 32768", __METHOD__ );
			$conn->selectDB( $dbName );
		}
		$this->setupSchemaVars();
		return $status;
	}

	/**
	 * Create tables from scratch.
	 * First check if pagesize >= 32k.
	 *
	 * @return Status
	 */
	public function createTables() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->db->selectDB( $this->getVar( 'wgDBname' ) );

		if( $this->db->tableExists( 'user' ) ) {
			$status->warning( 'config-install-tables-exist' );
			return $status;
		}

		/* Check for pagesize */
		$status = $this->checkPageSize();
		if ( !$status->isOK() ) {
			return $status;
		}

		$this->db->setFlag( DBO_DDLMODE ); // For Oracle's handling of schema files
		$this->db->begin( __METHOD__ );

		$error = $this->db->sourceFile( $this->db->getSchemaPath() );
		if( $error !== true ) {
			$this->db->reportQueryError( $error, 0, '', __METHOD__ );
			$this->db->rollback( __METHOD__ );
			$status->fatal( 'config-install-tables-failed', $error );
		} else {
			$this->db->commit( __METHOD__ );
		}
		// Resume normal operations
		if( $status->isOk() ) {
			$this->enableLB();
		}
		return $status;
	}

	/**
	 * Check if database has a tablspace with pagesize >= 32k.
	 *
	 * @return Status
	 */
	public function checkPageSize() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->db->selectDB( $this->getVar( 'wgDBname' ) );

		try {
			$result = $this->db->query( 'SELECT PAGESIZE FROM SYSCAT.TABLESPACES' );
			if( $result == false ) {
				$status->fatal( 'config-connection-error', '' );
			}
			else {
				while ( $row = $this->db->fetchRow( $result ) ) {
					if( $row[0] >= 32768 ) {
						return $status;
					}
				}
				$status->fatal( 'config-ibm_db2-low-db-pagesize', '' );
			}
		} catch ( DBUnexpectedError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}

		return $status;
	}

	/**
	 * Generate the code to store the DB2-specific settings defined by the configuration form
	 * @return string
	 */
	public function getLocalSettings() {
		$schema = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgDBmwschema' ) );
		$port = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgDBport' ) );
		return
"# IBM_DB2 specific settings
\$wgDBmwschema         = \"{$schema}\";
\$wgDBport             = \"{$port}\";";
	}

	public function __construct($parent) {
		parent::__construct($parent);
	}
}
