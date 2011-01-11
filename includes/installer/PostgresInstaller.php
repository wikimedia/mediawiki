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
		'wgDBts2schema',
	);

	var $minimumVersion = '8.1';
	private $ts2MaxVersion = '8.3'; // Doing ts2 is not necessary in PG > 8.3

	function getName() {
		return 'postgres';
	}

	public function isCompiled() {
		return self::checkExtension( 'pgsql' );
	}

	function getConnectForm() {
		return
			$this->getTextBox( 'wgDBserver', 'config-db-host', array(), $this->parent->getHelpBox( 'config-db-host-help' ) ) .
			$this->getTextBox( 'wgDBport', 'config-db-port' ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-wiki-settings' ) ) .
			$this->getTextBox( 'wgDBname', 'config-db-name', array(), $this->parent->getHelpBox( 'config-db-name-help' ) ) .
			$this->getTextBox( 'wgDBmwschema', 'config-db-schema', array(), $this->parent->getHelpBox( 'config-db-schema-help' ) ) .
			$this->getTextBox( 'wgDBts2schema', 'config-db-ts2-schema' ) .
			Html::closeElement( 'fieldset' ) .
			$this->getInstallUserBox();
	}

	function submitConnectForm() {
		// Get variables from the request
		$newValues = $this->setVarsFromRequest( array( 'wgDBserver', 'wgDBport',
			'wgDBname', 'wgDBmwschema', 'wgDBts2schema' ) );

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
		if ( !preg_match( '/^[a-zA-Z0-9_]*$/', $newValues['wgDBts2schema'] ) ) {
			$status->fatal( 'config-invalid-ts2schema', $newValues['wgDBts2schema'] );
		}

		// Submit user box
		if ( $status->isOK() ) {
			$status->merge( $this->submitInstallUserBox() );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Try to connect
		$status->merge( $this->getConnection() );
		if ( !$status->isOK() ) {
			return $status;
		}

/*		//Make sure install user can create
		$status->merge( $this->canCreateAccounts() );
		if ( !$status->isOK() ) {
			return $status;
		} */

		// Check version
		$version = $this->db->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-postgres-old', $this->minimumVersion, $version );
		}

		$this->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
		$this->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );
		return $status;
	}

	function getConnection($database = 'template1') {
		$status = Status::newGood();

		try {
			$this->db = new DatabasePostgres(
				$this->getVar( 'wgDBserver' ),
				$this->getVar( '_InstallUser' ),
				$this->getVar( '_InstallPassword' ),
				$database );
			$status->value = $this->db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}
		return $status;
	}

	protected function canCreateAccounts() {
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

		if( !$rights ) {
			return false;
		}

		if( $rights != 1 && $rights != 3 ) {
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
				new DatabasePostgres(
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

		return Status::newGood();
	}

	public function preInstall() {
		$commitCB = array(
			'name' => 'pg-commit',
			'callback' => array( $this, 'commitChanges' ),
		);
		$userCB = array(
			'name' => 'user',
			'callback' => array( $this, 'setupUser' ),
		);
		$ts2CB = array(
			'name' => 'pg-ts2',
			'callback' => array( $this, 'setupTs2' ),
		);
		$plpgCB = array(
			'name' => 'pg-plpgsql',
			'callback' => array( $this, 'setupPLpgSQL' ),
		);
		$this->parent->addInstallStep( $commitCB, 'interwiki' );
		$this->parent->addInstallStep( $userCB );
		$this->parent->addInstallStep( $ts2CB, 'database' );
		$this->parent->addInstallStep( $plpgCB, 'database' );
	}

	function setupDatabase() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		$dbName = $this->getVar( 'wgDBname' );
        $SQL = "SELECT 1 FROM pg_catalog.pg_database WHERE datname = " . $conn->addQuotes( $dbName );
        $rows = $conn->numRows( $conn->query( $SQL ) );
		if( !$rows ) {
			$schema = $this->getVar( 'wgDBmwschema' );
			$user = $this->getVar( 'wgDBuser' );

			$safeschema = $conn->addIdentifierQuotes( $schema );
			$safeuser = $conn->addIdentifierQuotes( $user );

			$safedb = $conn->addIdentifierQuotes( $dbName );

			$conn->query( "CREATE DATABASE $safedb OWNER $safeuser", __METHOD__ );

			$conn = new DatabasePostgres(
                    $this->getVar( 'wgDBserver' ),
                    $this->getVar( 'wgDBuser' ),
                    $this->getVar( 'wgDBpassword' ),
                    $dbName,
                    false,
                    0,
                    $this->getVar( 'wgDBprefix' )
                );

			$result = $conn->schemaExists( $schema );
			if( !$result ) {
				$result = $conn->query( "CREATE SCHEMA $safeschema AUTHORIZATION $safeuser" );
				if( !$result ) {
					$status->fatal( 'config-install-pg-schema-failed', $user, $schema );
				}
			} else {
				$safeschema2 = $conn->addQuotes( $schema );
				 $SQL = "SELECT 'GRANT ALL ON '||pg_catalog.quote_ident(relname)||' TO $safeuser;'\n".
                    "FROM pg_catalog.pg_class p, pg_catalog.pg_namespace n\n".
                    "WHERE relnamespace = n.oid AND n.nspname = $safeschema2\n".
                    "AND p.relkind IN ('r','S','v')\n";
                $SQL .= "UNION\n";
                $SQL .= "SELECT 'GRANT ALL ON FUNCTION '||pg_catalog.quote_ident(proname)||'('||\n".
                    "pg_catalog.oidvectortypes(p.proargtypes)||') TO $safeuser;'\n".
                    "FROM pg_catalog.pg_proc p, pg_catalog.pg_namespace n\n".
                    "WHERE p.pronamespace = n.oid AND n.nspname = $safeschema2";
                $res = $conn->query( $SQL );
				$conn->query( "SET search_path = $safeschema" );
			}
		}
		return $status;
	}

	/**
	 * Ts2 isn't needed in newer versions of Postgres, so wrap it in a nice big
	 * version check and skip it if we're new. Maybe we can bump $minimumVersion
	 * one day and render this obsolete :)
	 *
	 * @return Status
	 */
	function setupTs2() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		if( version_compare( $this->db->getServerVersion(), $this->ts2MaxVersion, '<' ) ) {
			if ( !$this->db->tableExists( 'pg_ts_cfg', $wgDBts2schema ) ) {
				return Status::newFatal( 
					'config-install-pg-ts2-failed',
					$this->getVar( 'wgDBname' ),
					'http://www.devx.com/opensource/Article/21674/0/page/2'
				);
			}
			$safeuser = $this->db->addQuotes( $this->getVar( 'wgDBuser' ) );
			foreach ( array( 'cfg', 'cfgmap', 'dict', 'parser' ) as $table ) {
				$sql = "GRANT SELECT ON pg_ts_$table TO $safeuser";
				$this->db->query( $sql, __METHOD__ );
			}
		}
		return Status::newGood();
	}

	function commitChanges() {
		$this->db->query( 'COMMIT' );
		return Status::newGood();
	}

	function setupUser() {
		if ( !$this->getVar( '_CreateDBAccount' ) ) {
			return Status::newGood();
		}

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		$db = $this->getVar( 'wgDBname' );
		$this->db->selectDB( $db );
		$safeuser = $this->db->addIdentifierQuotes( $this->getVar( 'wgDBuser' ) );
		$safepass = $this->db->addQuotes( $this->getVar( 'wgDBpassword' ) );
		$res = $this->db->query( "CREATE USER $safeuser NOCREATEDB PASSWORD $safepass", __METHOD__ );
		return $status;

		if ( $res !== true ) {
			$status->fatal( 'config-install-user-failed', $this->getVar( 'wgDBuser' ) );
		}

		return $status;
	}

	function getLocalSettings() {
		$port = $this->getVar( 'wgDBport' );
		$schema = $this->getVar( 'wgDBmwschema' );
		$ts2 = $this->getVar( 'wgDBts2schema' );
		return
"# Postgres specific settings
\$wgDBport           = \"{$port}\";
\$wgDBmwschema       = \"{$schema}\";
\$wgDBts2schema      = \"{$ts2}\";";
	}

	public function preUpgrade() {
		global $wgDBuser, $wgDBpassword;

		# Normal user and password are selected after this step, so for now
		# just copy these two
		$wgDBuser = $this->getVar( '_InstallUser' );
		$wgDBpassword = $this->getVar( '_InstallPassword' );
	}

	public function setupPLpgSQL() {
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
