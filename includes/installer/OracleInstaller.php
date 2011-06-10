<?php
/**
 * Oracle-specific installer.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for setting up the MediaWiki database using Oracle.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class OracleInstaller extends DatabaseInstaller {

	protected $globalNames = array(
		'wgDBserver',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBprefix',
	);

	protected $internalDefaults = array(
		'_OracleDefTS' => 'USERS',
		'_OracleTempTS' => 'TEMP',
		'_InstallUser' => 'SYSDBA',
	);

	public $minimumVersion = '9.0.1'; // 9iR1

	protected $connError = null;

	public function getName() {
		return 'oracle';
	}

	public function isCompiled() {
		return self::checkExtension( 'oci8' );
	}

	public function getConnectForm() {
		if ( $this->getVar( 'wgDBserver' ) == 'localhost' ) {
			$this->parent->setVar( 'wgDBserver', '' );
		}
		return
			$this->getTextBox( 'wgDBserver', 'config-db-host-oracle', array(), $this->parent->getHelpBox( 'config-db-host-oracle-help' ) ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-wiki-settings' ) ) .
			$this->getTextBox( 'wgDBprefix', 'config-db-prefix' ) .
			$this->getTextBox( '_OracleDefTS', 'config-oracle-def-ts' ) .
			$this->getTextBox( '_OracleTempTS', 'config-oracle-temp-ts', array(), $this->parent->getHelpBox( 'config-db-oracle-help' ) ) .
			Html::closeElement( 'fieldset' ) .
			$this->parent->getWarningBox( wfMsg( 'config-db-account-oracle-warn' ) ).
			$this->getInstallUserBox().
			$this->getWebUserBox();
	}

	public function submitInstallUserBox() {
		parent::submitInstallUserBox();
		$this->parent->setVar( '_InstallDBname', $this->getVar( '_InstallUser' ) );
		return Status::newGood();
	}

	public function submitConnectForm() {
		// Get variables from the request
		$newValues = $this->setVarsFromRequest( array( 'wgDBserver', 'wgDBprefix', 'wgDBuser', 'wgDBpassword' ) );
		$this->parent->setVar( 'wgDBname', $this->getVar( 'wgDBuser' ) );

		// Validate them
		$status = Status::newGood();
		if ( !strlen( $newValues['wgDBserver'] ) ) {
			$status->fatal( 'config-missing-db-server-oracle' );
		} elseif ( !preg_match( '/^[a-zA-Z0-9_\.]+$/', $newValues['wgDBserver'] ) ) {
			$status->fatal( 'config-invalid-db-server-oracle', $newValues['wgDBserver'] );
		}
		if ( !preg_match( '/^[a-zA-Z0-9_]*$/', $newValues['wgDBprefix'] ) ) {
			$status->fatal( 'config-invalid-schema', $newValues['wgDBprefix'] );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Submit user box
		$status = $this->submitInstallUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		// Try to connect trough multiple scenarios
		// Scenario 1: Install with a manually created account
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			if ( $this->connError == 28009 ) {
				// _InstallUser seems to be a SYSDBA
				// Scenario 2: Create user with SYSDBA and install with new user
				$status = $this->submitWebUserBox();
				if ( !$status->isOK() ) {
					return $status;
				}
				$status = $this->openSYSDBAConnection();
				if ( !$status->isOK() ) {
					return $status;
				}
				if ( !$this->getVar( '_CreateDBAccount' ) ) {
					$status->fatal('config-db-sys-create-oracle');
				}
			} else {
				return $status;
			}
		} else {
			// check for web user credentials
			// Scenario 3: Install with a priviliged user but use a restricted user
			$statusIS3 = $this->submitWebUserBox();
			if ( !$statusIS3->isOK() ) {
				return $statusIS3;
			}
		}
		$conn = $status->value;

		// Check version
		$version = $conn->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-oracle-old', $this->minimumVersion, $version );
		}

		return $status;
	}

	public function openConnection() {
		$status = Status::newGood();
		try {
			$db = new DatabaseOracle(
				$this->getVar( 'wgDBserver' ),
				$this->getVar( '_InstallUser' ),
				$this->getVar( '_InstallPassword' ),
				$this->getVar( '_InstallDBname' ),
				0,
				$this->getVar( 'wgDBprefix' )
			);
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$this->connError = $e->db->lastErrno();
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}
		return $status;
	}

	public function openSYSDBAConnection() {
		$status = Status::newGood();
		try {
			$db = new DatabaseOracle(
				$this->getVar( 'wgDBserver' ),
				$this->getVar( '_InstallUser' ),
				$this->getVar( '_InstallPassword' ),
				$this->getVar( '_InstallDBname' ),
				DBO_SYSDBA,
				$this->getVar( 'wgDBprefix' )
			);
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$this->connError = $e->db->lastErrno();
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}
		return $status;
	}

	public function needsUpgrade() {
		$tempDBname = $this->getVar( 'wgDBname' );
		$this->parent->setVar( 'wgDBname', $this->getVar( 'wgDBuser' ) );
		$retVal = parent::needsUpgrade();
		$this->parent->setVar( 'wgDBname', $tempDBname );
		return $retVal;
	}

	public function preInstall() {
		# Add our user callback to installSteps, right before the tables are created.
		$callback = array(
			'name' => 'user',
			'callback' => array( $this, 'setupUser' )
		);
		$this->parent->addInstallStep( $callback, 'database' );
	}


	public function setupDatabase() {
		$status = Status::newGood();
		return $status;
	}

	public function setupUser() {
		global $IP;

		if ( !$this->getVar( '_CreateDBAccount' ) ) {
			return Status::newGood();
		}

		// normaly only SYSDBA users can create accounts
		$status = $this->openSYSDBAConnection();
		if ( !$status->isOK() ) {
			if ( $this->connError == 1031 ) {
				// insufficient  privileges (looks like a normal user)
				$status = $this->openConnection();
				if ( !$status->isOK() ) {
					return $status;
				}
			} else {
				return $status;
			}
		}
		$this->db = $status->value;
		$this->setupSchemaVars();

		if ( !$this->db->selectDB( $this->getVar( 'wgDBuser' ) ) ) {
			$this->db->setFlag( DBO_DDLMODE );
			$error = $this->db->sourceFile( "$IP/maintenance/oracle/user.sql" );
			if ( $error !== true || !$this->db->selectDB( $this->getVar( 'wgDBuser' ) ) ) {
				$status->fatal( 'config-install-user-failed', $this->getVar( 'wgDBuser' ), $error );
			}
		} elseif ( $this->db->getFlag( DBO_SYSDBA ) ) {
			$status->fatal( 'config-db-sys-user-exists-oracle', $this->getVar( 'wgDBuser' ) );
		}

		if ($status->isOK()) {
			// user created or already existing, switching back to a normal connection
			// as the new user has all needed privileges to setup the rest of the schema
			// i will be using that user as _InstallUser from this point on
			$this->parent->setVar( '_InstallUser', $this->getVar( 'wgDBuser' ) );
			$this->parent->setVar( '_InstallPassword', $this->getVar( 'wgDBpassword' ) );
			$this->parent->setVar( '_InstallDBname', $this->getVar( 'wgDBuser' ) );
			$status = $this->getConnection();
		}

		return $status;
	}

	/**
	 * Overload: after this action field info table has to be rebuilt
	 */
	public function createTables() {
		$this->setupSchemaVars();
		$this->db->selectDB( $this->getVar( 'wgDBuser' ) );
		$this->db->setFlag( DBO_DDLMODE );
		$status = parent::createTables();
		$this->db->clearFlag( DBO_DDLMODE );

		$this->db->query( 'BEGIN fill_wiki_info; END;' );

		return $status;
	}

	public function getSchemaVars() {
		$varNames = array(
			# These variables are used by maintenance/oracle/user.sql
			'_OracleDefTS',
			'_OracleTempTS',
			'wgDBuser',
			'wgDBpassword',

			# These are used by tables.sql
			'wgDBprefix',
		);
		$vars = array();
		foreach ( $varNames as $name ) {
			$vars[$name] = $this->getVar( $name );
		}
		return $vars;
	}

	public function getLocalSettings() {
		$prefix = $this->getVar( 'wgDBprefix' );
		return
"# Oracle specific settings
\$wgDBprefix         = \"{$prefix}\";
";
	}

}
