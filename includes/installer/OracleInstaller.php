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
		'wgDBport',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBprefix',
	);

	protected $internalDefaults = array(
		'_OracleDefTS' => 'USERS',
		'_OracleTempTS' => 'TEMP',
		'_OracleUseSysdba' => true
	);

	public $minimumVersion = '9.0.1'; // 9iR1

	public function getName() {
		return 'oracle';
	}

	public function isCompiled() {
		return self::checkExtension( 'oci8' );
	}

	public function getWebUserBox( $noCreateMsg = false ) {
		$this->parent->setVar( '_SameAccount', false );
		$this->parent->setVar( '_CreateDBAccount', true );
		$this->parent->setVar( 'wgDBname', '' );
		return Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-web-account' ) ) .
			Html::openElement( 'div', array( 'id' => 'dbOtherAccount' ) ) .
			$this->getTextBox( 'wgDBuser', 'config-db-username' ) .
			$this->getPasswordBox( 'wgDBpassword', 'config-db-password', array(), $this->parent->getHelpBox( 'config-db-web-help' ) ) .
			$this->getCheckBox( '_CreateDBAccount', 'config-db-web-create', array( 'disabled' => true ) ).
			Html::closeElement( 'div' ) . Html::closeElement( 'fieldset' );
	}

	public function getConnectForm() {
		$this->parent->setVar( '_InstallUser', 'sys' );
		$this->parent->setVar( 'wgDBserver', '' );
		return
			$this->getTextBox( 'wgDBserver', 'config-db-host-oracle', array(), $this->parent->getHelpBox( 'config-db-host-oracle-help' ) ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-wiki-settings' ) ) .
			$this->getTextBox( 'wgDBprefix', 'config-db-prefix' ) .
			$this->getTextBox( '_OracleDefTS', 'config-oracle-def-ts' ) .
			$this->getTextBox( '_OracleTempTS', 'config-oracle-temp-ts', array(), $this->parent->getHelpBox( 'config-db-oracle-help' ) ) .
			Html::closeElement( 'fieldset' ) .
			$this->getInstallUserBox().
			$this->getWebUserBox();
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

		// Try to connect
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		// Check version
		$version = $conn->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-oracle-old', $this->minimumVersion, $version );
		}

		return $status;
	}

	public function getConnection() {
		$status = Status::newGood();
		try {
			if ( $this->getVar( '_OracleUseSysdba' ) ) {
				$this->db = new DatabaseOracle(
					$this->getVar( 'wgDBserver' ),
					$this->getVar( '_InstallUser' ),
					$this->getVar( '_InstallPassword' ),
					$this->getVar( 'wgDBname' ),
					DBO_SYSDBA,
					$this->getVar( 'wgDBprefix' )
				);
			} else {
				$this->db = new DatabaseOracle(
					$this->getVar( 'wgDBserver' ),
					$this->getVar( 'wgDBuser' ),
					$this->getVar( 'wgDBpassword' ),
					$this->getVar( 'wgDBname' ),
					0,
					$this->getVar( 'wgDBprefix' )
				);
			}
			$status->value = $this->db;
		} catch ( DBConnectionError $e ) {
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
			array(
				'name' => 'user',
				'callback' => array( $this, 'setupUser' ),
			)
		);
		$this->parent->addInstallStep( $callback, 'database' );
	}


	public function setupDatabase() {
		$this->parent->setVar( '_OracleUseSysdba', false );
		$status = Status::newGood();
		return $status;
	}

	public function setupUser() {
		global $IP;

		if ( !$this->getVar( '_CreateDBAccount' ) ) {
			return Status::newGood();
		}
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		if ( !$this->db->selectDB( $this->getVar( 'wgDBuser' ) ) ) {
			/**
			 * The variables $_OracleDefTS, $_OracleTempTS are used by maintenance/oracle/user.sql
			 * Set here for fetching in DatabaseOracle::replaceVars()
			 */
			$GLOBALS['_OracleDefTS'] = $this->getVar( '_OracleDefTS' );
			$GLOBALS['_OracleTempTS'] = $this->getVar( '_OracleTempTS' );
			$error = $this->db->sourceFile( "$IP/maintenance/oracle/user.sql" );
			if ( $error !== true || !$this->db->selectDB( $this->getVar( 'wgDBuser' ) ) ) {
				$status->fatal( 'config-install-user-failed', $this->getVar( 'wgDBuser' ), $error );
			}
		}

		return $status;
	}

	/**
	 * Overload: after this action field info table has to be rebuilt
	 */
	public function createTables() {
		$status = parent::createTables();

		$this->db->query( 'BEGIN fill_wiki_info; END;' );

		return $status;
	}


	public function getLocalSettings() {
		$prefix = $this->getVar( 'wgDBprefix' );
		return
"# Oracle specific settings
\$wgDBprefix         = \"{$prefix}\";
";
	}

}
