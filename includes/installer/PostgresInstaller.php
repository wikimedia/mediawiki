<?php
/**
 * PostgreSQL-specific installer.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for setting up the MediaWiki database using Postgres.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class PostgresInstaller extends DatabaseInstaller {

	protected $globalNames = array(
		'wgDBserver',
		'wgDBport',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBmwschema',
	);

	var $minimumVersion = '8.3';
	private $useAdmin = false;

	function getName() {
		return 'postgres';
	}

	public function isCompiled() {
		return self::checkExtension( 'pgsql' );
	}

	function getConnectForm() {
		// If this is our first time here, switch the default user presented in the form
		if ( ! $this->getVar('_switchedInstallUser') ) {
			$this->setVar('_InstallUser', 'postgres');
			$this->setVar('_switchedInstallUser', true);
		}
		return
			$this->getTextBox( 'wgDBserver', 'config-db-host', array(), $this->parent->getHelpBox( 'config-db-host-help' ) ) .
			$this->getTextBox( 'wgDBport', 'config-db-port' ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-wiki-settings' ) ) .
			$this->getTextBox( 'wgDBname', 'config-db-name', array(), $this->parent->getHelpBox( 'config-db-name-help' ) ) .
			$this->getTextBox( 'wgDBmwschema', 'config-db-schema', array(), $this->parent->getHelpBox( 'config-db-schema-help' ) ) .
			Html::closeElement( 'fieldset' ) .
			$this->getInstallUserBox();
	}

	function submitConnectForm() {
		// Get variables from the request
		$newValues = $this->setVarsFromRequest( array( 'wgDBserver', 'wgDBport',
			'wgDBname', 'wgDBmwschema' ) );

		// Validate them
		$status = Status::newGood();
		if ( !strlen( $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-missing-db-name' );
		} elseif ( !preg_match( '/^[a-zA-Z0-9_]+$/', $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-invalid-db-name', $newValues['wgDBname'] );
		}
		if ( !preg_match( '/^[a-zA-Z0-9_]*$/', $newValues['wgDBmwschema'] ) ) {
			$status->fatal( 'config-invalid-schema', $newValues['wgDBmwschema'] );
		}

		// Submit user box
		if ( $status->isOK() ) {
			$status->merge( $this->submitInstallUserBox() );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		$this->useAdmin = true;
		// Try to connect
		$status->merge( $this->getConnection() );
		if ( !$status->isOK() ) {
			return $status;
		}

		//Make sure install user can create
		if( !$this->canCreateAccounts() ) {
			$status->fatal( 'config-pg-no-create-privs' );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Check version
		$version = $this->db->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-postgres-old', $this->minimumVersion, $version );
		}

		$this->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
		$this->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );
		return $status;
	}

	public function openConnection() {
		$status = Status::newGood();
		try {
			if ( $this->useAdmin ) {
				$db = new DatabasePostgres(
					$this->getVar( 'wgDBserver' ),
					$this->getVar( '_InstallUser' ),
					$this->getVar( '_InstallPassword' ),
					'template1' );
			} else {
				$db = new DatabasePostgres(
					$this->getVar( 'wgDBserver' ),
					$this->getVar( 'wgDBuser' ),
					$this->getVar( 'wgDBpassword' ),
					$this->getVar( 'wgDBname' ) );
			}
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}
		return $status;
	}

	protected function canCreateAccounts() {
		$this->useAdmin = true;
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return false;
		}
		$conn = $status->value;

		$superuser = $this->getVar( '_InstallUser' );

		$rights = $conn->selectField( 'pg_catalog.pg_user',
			'CASE WHEN usesuper IS TRUE THEN
				CASE WHEN usecreatedb IS TRUE THEN 3 ELSE 1 END
				ELSE CASE WHEN usecreatedb IS TRUE THEN 2 ELSE 0 END
				END AS rights',
			array( 'usename' => $superuser ), __METHOD__
		);

		if( !$rights || ( $rights != 1 && $rights != 3 ) ) {
			return false;
		}

		return true;
	}

	public function getSettingsForm() {
		if ( $this->canCreateAccounts() ) {
			$noCreateMsg = false;
		} else {
			$noCreateMsg = 'config-db-web-no-create-privs';
		}
		$s = $this->getWebUserBox( $noCreateMsg );

		return $s;
	}

	public function submitSettingsForm() {
		$status = $this->submitWebUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		// Validate the create checkbox
		if ( !$this->canCreateAccounts() ) {
			$this->setVar( '_CreateDBAccount', false );
			$create = false;
		} else {
			$create = $this->getVar( '_CreateDBAccount' );
		}

		// Don't test the web account if it is the same as the admin.
		if ( !$create && $this->getVar( 'wgDBuser' ) != $this->getVar( '_InstallUser' ) ) {
			// Test the web account
			try {
				$this->useAdmin = false;
				return $this->openConnection();
			} catch ( DBConnectionError $e ) {
				return Status::newFatal( 'config-connection-error', $e->getMessage() );
			}
		}

		return Status::newGood();
	}

	public function preInstall() {
		$commitCB = array(
			'name' => 'pg-commit',
			'callback' => array( $this, 'commitChanges' ),
		);
		$plpgCB = array(
			'name' => 'pg-plpgsql',
			'callback' => array( $this, 'setupPLpgSQL' ),
		);
		$this->parent->addInstallStep( $commitCB, 'interwiki' );
		$this->parent->addInstallStep( $plpgCB, 'database' );
		if( $this->getVar( '_CreateDBAccount' ) ) {
			$this->parent->addInstallStep( array(
				'name' => 'user',
				'callback' => array( $this, 'setupUser' ),
			) );
		}
	}

	function setupDatabase() {
		$this->useAdmin = true;
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->setupSchemaVars();
		$conn = $status->value;

		$dbName = $this->getVar( 'wgDBname' );
		$schema = $this->getVar( 'wgDBmwschema' );
		$user = $this->getVar( 'wgDBuser' );
		$safeschema = $conn->addIdentifierQuotes( $schema );
		$safeuser = $conn->addIdentifierQuotes( $user );

		$SQL = "SELECT 1 FROM pg_catalog.pg_database WHERE datname = " . $conn->addQuotes( $dbName );
		$rows = $conn->numRows( $conn->query( $SQL ) );
		if( !$rows ) {
			$safedb = $conn->addIdentifierQuotes( $dbName );
			$conn->query( "CREATE DATABASE $safedb OWNER $safeuser", __METHOD__ );
		} else {
			$conn->query( "ALTER DATABASE $safedb OWNER TO $safeuser", __METHOD__ );
		}
		
		$this->useAdmin = false;
			$status = $this->getConnection();
			if ( !$status->isOK() ) {
				return $status;
			}
		$conn = $status->value;

		if( !$conn->schemaExists( $schema ) ) {
			$result = $conn->query( "CREATE SCHEMA $safeschema AUTHORIZATION $safeuser" );
			if( !$result ) {
				$status->fatal( 'config-install-pg-schema-failed', $user, $schema );
			}
		} else {
			$safeschema2 = $conn->addQuotes( $schema );
			$SQL = "SELECT 'GRANT ALL ON '||pg_catalog.quote_ident(relname)||' TO $safeuser;'\n".
				"FROM pg_catalog.pg_class p, pg_catalog.pg_namespace n\n" .
				"WHERE relnamespace = n.oid AND n.nspname = $safeschema2\n" .
				"AND p.relkind IN ('r','S','v')\n";
			$SQL .= "UNION\n";
			$SQL .= "SELECT 'GRANT ALL ON FUNCTION '||pg_catalog.quote_ident(proname)||'('||\n".
				"pg_catalog.oidvectortypes(p.proargtypes)||') TO $safeuser;'\n" .
				"FROM pg_catalog.pg_proc p, pg_catalog.pg_namespace n\n" .
				"WHERE p.pronamespace = n.oid AND n.nspname = $safeschema2";
			$conn->query( "SET search_path = $safeschema" );
			$res = $conn->query( $SQL );
		}
		return $status;
	}

	function commitChanges() {
		$this->db->query( 'COMMIT' );
		return Status::newGood();
	}

	function setupUser() {
		if ( !$this->getVar( '_CreateDBAccount' ) ) {
			return Status::newGood();
		}

		$this->useAdmin = true;
		$status = $this->getConnection();

		if ( !$status->isOK() ) {
			return $status;
		}

		$schema = $this->getVar( 'wgDBmwschema' );
		$safeuser = $this->db->addIdentifierQuotes( $this->getVar( 'wgDBuser' ) );
		$safeusercheck = $this->db->addQuotes( $this->getVar( 'wgDBuser' ) );
		$safepass = $this->db->addQuotes( $this->getVar( 'wgDBpassword' ) );
		$safeschema = $this->db->addIdentifierQuotes( $schema );

		$rows = $this->db->numRows(
			$this->db->query( "SELECT 1 FROM pg_catalog.pg_shadow WHERE usename = $safeusercheck" )
		);
		if ( $rows < 1 ) {
			$res = $this->db->query( "CREATE USER $safeuser NOCREATEDB PASSWORD $safepass", __METHOD__ );
			if ( $res !== true && !( $res instanceOf ResultWrapper ) ) {
				$status->fatal( 'config-install-user-failed', $this->getVar( 'wgDBuser' ), $res );
			}
			if( $status->isOK() ) {
				$this->db->query("ALTER USER $safeuser SET search_path = $safeschema");
			}
		}

		return $status;
	}

	function getLocalSettings() {
		$port = $this->getVar( 'wgDBport' );
		$schema = $this->getVar( 'wgDBmwschema' );
		return
"# Postgres specific settings
\$wgDBport           = \"{$port}\";
\$wgDBmwschema       = \"{$schema}\";";
	}

	public function preUpgrade() {
		global $wgDBuser, $wgDBpassword;

		# Normal user and password are selected after this step, so for now
		# just copy these two
		$wgDBuser = $this->getVar( '_InstallUser' );
		$wgDBpassword = $this->getVar( '_InstallPassword' );
	}

	public function createTables() {
		$schema = $this->getVar( 'wgDBmwschema' );

		$this->db = null;
		$this->useAdmin = false;
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		if( $this->db->tableExists( 'user' ) ) {
			$status->warning( 'config-install-tables-exist' );
			return $status;
		}

		$this->db->begin( __METHOD__ );

		if( !$this->db->schemaExists( $schema ) ) {
			$status->error( 'config-install-pg-schema-not-exist' );
			return $status;
		}
		$safeschema = $this->db->addIdentifierQuotes( $schema );
		$this->db->query( "SET search_path = $safeschema" );
		$error = $this->db->sourceFile( $this->db->getSchema() );
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

	public function setupPLpgSQL() {
		$this->useAdmin = true;
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		$rows = $this->db->numRows(
			$this->db->query( "SELECT 1 FROM pg_catalog.pg_language WHERE lanname = 'plpgsql'" )
		);
		if ( $rows < 1 ) {
			// plpgsql is not installed, but if we have a pg_pltemplate table, we should be able to create it
			$SQL = "SELECT 1 FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON (n.oid = c.relnamespace) ".
				"WHERE relname = 'pg_pltemplate' AND nspname='pg_catalog'";
			$rows = $this->db->numRows( $this->db->query( $SQL ) );
			$dbName = $this->getVar( 'wgDBname' );
			if ( $rows >= 1 ) {
				$result = $this->db->query( 'CREATE LANGUAGE plpgsql' );
				if ( !$result ) {
					return Status::newFatal( 'config-pg-no-plpgsql', $dbName );
				}
			} else {
				return Status::newFatal( 'config-pg-no-plpgsql', $dbName );
			}
		}
		return Status::newGood();
	}
}
