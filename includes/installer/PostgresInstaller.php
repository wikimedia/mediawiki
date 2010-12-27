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

		// Check version
		$version = $this->db->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-postgres-old', $this->minimumVersion, $version );
		}

		$this->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
		$this->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );
		return $status;
	}

	function getConnection() {
		$status = Status::newGood();

		try {
			$this->db = new DatabasePostgres(
				$this->getVar( 'wgDBserver' ),
				$this->getVar( '_InstallUser' ),
				$this->getVar( '_InstallPassword' ),
				$this->getVar( 'wgDBname' ) );
			$status->value = $this->db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}
		return $status;
	}

	public function preInstall() {
		# Add our user callback to installSteps, right before the tables are created.
		$callback = array(
			'name' => 'pg-commit',
			'callback' => array( $this, 'commitChanges' ),
		);
		$this->parent->addInstallStep( $callback, 'interwiki' );
	}

	function setupDatabase() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		// Make sure that we can write to the correct schema
		// If not, Postgres will happily and silently go to the next search_path item
		$schema = $this->getVar( 'wgDBmwschema' );
		$ctest = 'mediawiki_test_table';
		$safeschema = $conn->addIdentifierQuotes( $schema );
		if ( $conn->tableExists( $ctest, $schema ) ) {
			$conn->query( "DROP TABLE $safeschema.$ctest" );
		}
		$res = $conn->query( "CREATE TABLE $safeschema.$ctest(a int)" );
		if ( !$res ) {
			$status->fatal( 'config-install-pg-schema-failed',
				$this->getVar( 'wgDBuser'), $schema );
		}
		$conn->query( "DROP TABLE $safeschema.$ctest" );

		return $status;
	}

	function commitChanges() {
		$this->db->query( 'COMMIT' );

		return Status::newGood();
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

	private function setupPLpgSQL() {
		$rows = $this->numRows( 
			$this->db->query( "SELECT 1 FROM pg_catalog.pg_language WHERE lanname = 'plpgsql'" )
		);
		if ( $rows < 1 ) {
			// plpgsql is not installed, but if we have a pg_pltemplate table, we should be able to create it
			$SQL = "SELECT 1 FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON (n.oid = c.relnamespace) ".
				"WHERE relname = 'pg_pltemplate' AND nspname='pg_catalog'";
			$rows = $this->numRows( $this->db->query( $SQL ) );
			global $wgDBname;
			if ( $rows >= 1 ) {
				$result = $this->db->query( 'CREATE LANGUAGE plpgsql' );
				if ( !$result ) {
					return Status::newFatal( 'pg-no-plpgsql', $wgDBname );
				}
			} else {
				return Status::newFatal( 'pg-no-plpgsql', $wgDBname );
			}
		}
		return Status::newGood();
	}
}
