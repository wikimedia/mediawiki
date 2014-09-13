<?php
/**
 * Microsoft SQL Server-specific installer.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for setting up the MediaWiki database using Microsoft SQL Server.
 *
 * @ingroup Deployment
 * @since 1.23
 */
class MssqlInstaller extends DatabaseInstaller {

	protected $globalNames = array(
		'wgDBserver',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBmwschema',
		'wgDBprefix',
		'wgDBWindowsAuthentication',
	);

	protected $internalDefaults = array(
		'_InstallUser' => 'sa',
		'_InstallWindowsAuthentication' => 'sqlauth',
		'_WebWindowsAuthentication' => 'sqlauth',
	);

	public $minimumVersion = '9.00.1399'; // SQL Server 2005 RTM (TODO: are SQL Express version numbers different?)

	// These are schema-level privs
	// Note: the web user will be created will full permissions if possible, this permission
	// list is only used if we are unable to grant full permissions.
	public $webUserPrivs = array(
		'DELETE',
		'INSERT',
		'SELECT',
		'UPDATE',
		'EXECUTE',
	);

	/**
	 * @return string
	 */
	public function getName() {
		return 'mssql';
	}

	/**
	 * @return Bool
	 */
	public function isCompiled() {
		return self::checkExtension( 'sqlsrv' );
	}

	/**
	 * @return string
	 */
	public function getConnectForm() {
		if ( $this->getVar( '_InstallWindowsAuthentication' ) == 'windowsauth' ) {
			$displayStyle = 'display: none;';
		} else {
			$displayStyle = 'display: block;';
		}

		return $this->getTextBox(
			'wgDBserver',
			'config-db-host',
			array(),
			$this->parent->getHelpBox( 'config-db-host-help' )
		) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMessage( 'config-db-wiki-settings' )->text() ) .
			$this->getTextBox( 'wgDBname', 'config-db-name', array( 'dir' => 'ltr' ),
				$this->parent->getHelpBox( 'config-db-name-help' ) ) .
			$this->getTextBox( 'wgDBmwschema', 'config-db-schema', array( 'dir' => 'ltr' ),
				$this->parent->getHelpBox( 'config-db-schema-help' ) ) .
			$this->getTextBox( 'wgDBprefix', 'config-db-prefix', array( 'dir' => 'ltr' ),
				$this->parent->getHelpBox( 'config-db-prefix-help' ) ) .
			Html::closeElement( 'fieldset' ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMessage( 'config-db-install-account' )->text() ) .
			$this->getRadioSet( array(
				'var' => '_InstallWindowsAuthentication',
				'label' => 'config-mssql-auth',
				'itemLabelPrefix' => 'config-mssql-',
				'values' => array( 'sqlauth', 'windowsauth' ),
				'itemAttribs' => array(
					'sqlauth' => array(
						'class' => 'showHideRadio',
						'rel' => 'dbCredentialBox',
					),
					'windowsauth' => array(
						'class' => 'hideShowRadio',
						'rel' => 'dbCredentialBox',
					)
				),
				'help' => $this->parent->getHelpBox( 'config-mssql-install-auth' )
			) ) .
			Html::openElement( 'div', array( 'id' => 'dbCredentialBox', 'style' => $displayStyle ) ) .
			$this->getTextBox(
				'_InstallUser',
				'config-db-username',
				array( 'dir' => 'ltr' ),
				$this->parent->getHelpBox( 'config-db-install-username' )
			) .
			$this->getPasswordBox(
				'_InstallPassword',
				'config-db-password',
				array( 'dir' => 'ltr' ),
				$this->parent->getHelpBox( 'config-db-install-password' )
			) .
			Html::closeElement( 'div' ) .
			Html::closeElement( 'fieldset' );
	}

	public function submitConnectForm() {
		// Get variables from the request.
		$newValues = $this->setVarsFromRequest( array( 'wgDBserver', 'wgDBname', 'wgDBmwschema', 'wgDBprefix' ) );

		// Validate them.
		$status = Status::newGood();
		if ( !strlen( $newValues['wgDBserver'] ) ) {
			$status->fatal( 'config-missing-db-host' );
		}
		if ( !strlen( $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-missing-db-name' );
		} elseif ( !preg_match( '/^[a-z0-9_]+$/i', $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-invalid-db-name', $newValues['wgDBname'] );
		}
		if ( !preg_match( '/^[a-z0-9_]*$/i', $newValues['wgDBmwschema'] ) ) {
			$status->fatal( 'config-invalid-schema', $newValues['wgDBmwschema'] );
		}
		if ( !preg_match( '/^[a-z0-9_]*$/i', $newValues['wgDBprefix'] ) ) {
			$status->fatal( 'config-invalid-db-prefix', $newValues['wgDBprefix'] );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Check for blank schema and remap to dbo
		if ( $newValues['wgDBmwschema'] === '' ) {
			$this->setVar( 'wgDBmwschema', 'dbo' );
		}

		// User box
		$this->setVarsFromRequest( array( '_InstallUser', '_InstallPassword', '_InstallWindowsAuthentication' ) );

		// Try to connect
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		/**
		 * @var $conn DatabaseBase
		 */
		$conn = $status->value;

		// Check version
		$version = $conn->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-mssql-old', $this->minimumVersion, $version );
		}

		return $status;
	}

	/**
	 * @return Status
	 */
	public function openConnection() {
		global $wgDBWindowsAuthentication;
		$status = Status::newGood();
		$user = $this->getVar( '_InstallUser' );
		$password = $this->getVar( '_InstallPassword' );

		if ( $this->getVar( '_InstallWindowsAuthentication' ) == 'windowsauth' ) {
			// Use Windows authentication for this connection
			$wgDBWindowsAuthentication = true;
		} else {
			$wgDBWindowsAuthentication = false;
		}

		try {
			$db = DatabaseBase::factory( 'mssql', array(
				'host' => $this->getVar( 'wgDBserver' ),
				'user' => $user,
				'password' => $password,
				'dbname' => false,
				'flags' => 0,
				'tablePrefix' => $this->getVar( 'wgDBprefix' ) ) );
			$db->prepareStatements( false );
			$db->scrollableCursor( false );
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}

		return $status;
	}

	public function preUpgrade() {
		global $wgDBuser, $wgDBpassword;

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			$this->parent->showStatusError( $status );

			return;
		}
		/**
		 * @var $conn DatabaseBase
		 */
		$conn = $status->value;
		$conn->selectDB( $this->getVar( 'wgDBname' ) );

		# Normal user and password are selected after this step, so for now
		# just copy these two
		$wgDBuser = $this->getVar( '_InstallUser' );
		$wgDBpassword = $this->getVar( '_InstallPassword' );
	}

	/**
	 * Return true if the install user can create accounts
	 *
	 * @return bool
	 */
	public function canCreateAccounts() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return false;
		}
		/** @var $conn DatabaseBase */
		$conn = $status->value;

		// We need the server-level ALTER ANY LOGIN permission to create new accounts
		$res = $conn->query( "SELECT permission_name FROM sys.fn_my_permissions( NULL, 'SERVER' )" );
		$serverPrivs = array(
			'ALTER ANY LOGIN' => false,
			'CONTROL SERVER' => false,
		);

		foreach ( $res as $row ) {
			$serverPrivs[$row->permission_name] = true;
		}

		if ( !$serverPrivs['ALTER ANY LOGIN'] ) {
			return false;
		}

		// Check to ensure we can grant everything needed as well
		// We can't actually tell if we have WITH GRANT OPTION for a given permission, so we assume we do
		// and just check for the permission
		// http://technet.microsoft.com/en-us/library/ms178569.aspx
		// The following array sets up which permissions imply whatever permissions we specify
		$implied = array(
			// schema           database  server
			'DELETE'  => array( 'DELETE', 'CONTROL SERVER' ),
			'EXECUTE' => array( 'EXECUTE', 'CONTROL SERVER' ),
			'INSERT'  => array( 'INSERT', 'CONTROL SERVER' ),
			'SELECT'  => array( 'SELECT', 'CONTROL SERVER' ),
			'UPDATE'  => array( 'UPDATE', 'CONTROL SERVER' ),
		);

		$grantOptions = array_flip( $this->webUserPrivs );

		// Check for schema and db-level permissions, but only if the schema/db exists
		$schemaPrivs = $dbPrivs = array(
			'DELETE' => false,
			'EXECUTE' => false,
			'INSERT' => false,
			'SELECT' => false,
			'UPDATE' => false,
		);

		$dbPrivs['ALTER ANY USER'] = false;

		if ( $this->databaseExists( $this->getVar( 'wgDBname' ) ) ) {
			$conn->selectDB( $this->getVar( 'wgDBname' ) );
			$res = $conn->query( "SELECT permission_name FROM sys.fn_my_permissions( NULL, 'DATABASE' )" );

			foreach ( $res as $row ) {
				$dbPrivs[$row->permission_name] = true;
			}

			// If the db exists, we need ALTER ANY USER privs on it to make a new user
			if ( !$dbPrivs['ALTER ANY USER'] ) {
				return false;
			}

			if ( $this->schemaExists( $this->getVar( 'wgDBmwschema' ) ) ) {
				// wgDBmwschema is validated to only contain alphanumeric + underscore, so this is safe
				$res = $conn->query( "SELECT permission_name FROM sys.fn_my_permissions( '{$this->getVar( 'wgDBmwschema' )}', 'SCHEMA' )" );

				foreach ( $res as $row ) {
					$schemaPrivs[$row->permission_name] = true;
				}
			}
		}

		// Now check all the grants we'll need to be doing to see if we can
		foreach ( $this->webUserPrivs as $permission ) {
			if ( ( isset( $schemaPrivs[$permission] ) && $schemaPrivs[$permission] )
					|| ( isset( $dbPrivs[$implied[$permission][0]] ) && $dbPrivs[$implied[$permission][0]] )
					|| ( isset( $serverPrivs[$implied[$permission][1]] ) && $serverPrivs[$implied[$permission][1]] ) ) {

				unset( $grantOptions[$permission] );
			}
		}

		if ( count( $grantOptions ) ) {
			// Can't grant everything
			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getSettingsForm() {
		if ( $this->canCreateAccounts() ) {
			$noCreateMsg = false;
		} else {
			$noCreateMsg = 'config-db-web-no-create-privs';
		}
		$wrapperStyle = $this->getVar( '_SameAccount' ) ? 'display: none' : '';
		$displayStyle = $this->getVar( '_WebWindowsAuthentication' ) == 'windowsauth' ? 'display: none' : '';
		$s = Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMessage( 'config-db-web-account' )->text() ) .
			$this->getCheckBox(
				'_SameAccount', 'config-db-web-account-same',
				array( 'class' => 'hideShowRadio', 'rel' => 'dbOtherAccount' )
			) .
			Html::openElement( 'div', array( 'id' => 'dbOtherAccount', 'style' => $wrapperStyle ) ) .
			$this->getRadioSet( array(
				'var' => '_WebWindowsAuthentication',
				'label' => 'config-mssql-auth',
				'itemLabelPrefix' => 'config-mssql-',
				'values' => array( 'sqlauth', 'windowsauth' ),
				'itemAttribs' => array(
					'sqlauth' => array(
						'class' => 'showHideRadio',
						'rel' => 'dbCredentialBox',
					),
					'windowsauth' => array(
						'class' => 'hideShowRadio',
						'rel' => 'dbCredentialBox',
					)
				),
				'help' => $this->parent->getHelpBox( 'config-mssql-web-auth' )
			) ) .
			Html::openElement( 'div', array( 'id' => 'dbCredentialBox', 'style' => $displayStyle ) ) .
			$this->getTextBox( 'wgDBuser', 'config-db-username' ) .
			$this->getPasswordBox( 'wgDBpassword', 'config-db-password' ) .
			Html::closeElement( 'div' );

		if ( $noCreateMsg ) {
			$s .= $this->parent->getWarningBox( wfMessage( $noCreateMsg )->plain() );
		} else {
			$s .= $this->getCheckBox( '_CreateDBAccount', 'config-db-web-create' );
		}

		$s .= Html::closeElement( 'div' ) . Html::closeElement( 'fieldset' );

		return $s;
	}

	/**
	 * @return Status
	 */
	public function submitSettingsForm() {
		$this->setVarsFromRequest(
			array( 'wgDBuser', 'wgDBpassword', '_SameAccount', '_CreateDBAccount', '_WebWindowsAuthentication' )
		);

		if ( $this->getVar( '_SameAccount' ) ) {
			$this->setVar( '_WebWindowsAuthentication', $this->getVar( '_InstallWindowsAuthentication' ) );
			$this->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
			$this->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );
		}

		if ( $this->getVar( '_WebWindowsAuthentication' ) == 'windowsauth' ) {
			$this->setVar( 'wgDBuser', '' );
			$this->setVar( 'wgDBpassword', '' );
			$this->setVar( 'wgDBWindowsAuthentication', true );
		} else {
			$this->setVar( 'wgDBWindowsAuthentication', false );
		}

		if ( $this->getVar( '_CreateDBAccount' ) && $this->getVar( '_WebWindowsAuthentication' ) == 'sqlauth' && strval( $this->getVar( 'wgDBpassword' ) ) == '' ) {
			return Status::newFatal( 'config-db-password-empty', $this->getVar( 'wgDBuser' ) );
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
			$user = $this->getVar( 'wgDBuser' );
			$password = $this->getVar( 'wgDBpassword' );

			if ( $this->getVar( '_WebWindowsAuthentication' ) == 'windowsauth' ) {
				$user = 'windowsauth';
				$password = 'windowsauth';
			}

			try {
				DatabaseBase::factory( 'mssql', array(
					'host' => $this->getVar( 'wgDBserver' ),
					'user' => $user,
					'password' => $password,
					'dbname' => false,
					'flags' => 0,
					'tablePrefix' => $this->getVar( 'wgDBprefix' ),
					'schema' => $this->getVar( 'wgDBmwschema' ),
				) );
			} catch ( DBConnectionError $e ) {
				return Status::newFatal( 'config-connection-error', $e->getMessage() );
			}
		}

		return Status::newGood();
	}

	public function preInstall() {
		# Add our user callback to installSteps, right before the tables are created.
		$callback = array(
			'name' => 'user',
			'callback' => array( $this, 'setupUser' ),
		);
		$this->parent->addInstallStep( $callback, 'tables' );
	}

	/**
	 * @return Status
	 */
	public function setupDatabase() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		/** @var DatabaseBase $conn */
		$conn = $status->value;
		$dbName = $this->getVar( 'wgDBname' );
		$schemaName = $this->getVar( 'wgDBmwschema' );
		if ( !$this->databaseExists( $dbName ) ) {
			$conn->query( "CREATE DATABASE " . $conn->addIdentifierQuotes( $dbName ), __METHOD__ );
			$conn->selectDB( $dbName );
			if ( !$this->schemaExists( $schemaName ) ) {
				$conn->query( "CREATE SCHEMA " . $conn->addIdentifierQuotes( $schemaName ), __METHOD__ );
			}
			if ( !$this->catalogExists( $schemaName ) ) {
				$conn->query( "CREATE FULLTEXT CATALOG " . $conn->addIdentifierQuotes( $schemaName ), __METHOD__ );
			}
		}
		$this->setupSchemaVars();

		return $status;
	}

	/**
	 * @return Status
	 */
	public function setupUser() {
		$dbUser = $this->getVar( 'wgDBuser' );
		if ( $dbUser == $this->getVar( '_InstallUser' )
				|| ( $this->getVar( '_InstallWindowsAuthentication' ) == 'windowsauth'
					&& $this->getVar( '_WebWindowsAuthentication' ) == 'windowsauth' ) ) {
			return Status::newGood();
		}
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		$this->setupSchemaVars();
		$dbName = $this->getVar( 'wgDBname' );
		$this->db->selectDB( $dbName );
		$server = $this->getVar( 'wgDBserver' );
		$password = $this->getVar( 'wgDBpassword' );
		$schemaName = $this->getVar( 'wgDBmwschema' );

		if ( $this->getVar( '_WebWindowsAuthentication' ) == 'windowsauth' ) {
			$dbUser = 'windowsauth';
			$password = 'windowsauth';
		}

		if ( $this->getVar( '_CreateDBAccount' ) ) {
			$tryToCreate = true;
		} else {
			$tryToCreate = false;
		}

		$escUser = $this->db->addIdentifierQuotes( $dbUser );
		$escDb = $this->db->addIdentifierQuotes( $dbName );
		$escSchema = $this->db->addIdentifierQuotes( $schemaName );
		$grantableNames = array();
		if ( $tryToCreate ) {
			$escPass = $this->db->addQuotes( $password );

			if ( !$this->loginExists( $dbUser ) ) {
				try {
					$this->db->begin();
					$this->db->selectDB( 'master' );
					$logintype = $this->getVar( '_WebWindowsAuthentication' ) == 'windowsauth' ? 'FROM WINDOWS' : "WITH PASSWORD = $escPass";
					$this->db->query( "CREATE LOGIN $escUser $logintype" );
					$this->db->selectDB( $dbName );
					$this->db->query( "CREATE USER $escUser FOR LOGIN $escUser WITH DEFAULT_SCHEMA = $escSchema" );
					$this->db->commit();
					$grantableNames[] = $dbUser;
				} catch ( DBQueryError $dqe ) {
					$this->db->rollback();
					$status->warning( 'config-install-user-create-failed', $dbUser, $dqe->getText() );
				}
			} elseif ( !$this->userExists( $dbUser ) ) {
				try {
					$this->db->begin();
					$this->db->selectDB( $dbName );
					$this->db->query( "CREATE USER $escUser FOR LOGIN $escUser WITH DEFAULT_SCHEMA = $escSchema" );
					$this->db->commit();
					$grantableNames[] = $dbUser;
				} catch ( DBQueryError $dqe ) {
					$this->db->rollback();
					$status->warning( 'config-install-user-create-failed', $dbUser, $dqe->getText() );
				}
			} else {
				$status->warning( 'config-install-user-alreadyexists', $dbUser );
				$grantableNames[] = $dbUser;
			}
		}

		// Try to grant to all the users we know exist or we were able to create
		$this->db->selectDB( $dbName );
		foreach ( $grantableNames as $name ) {
			try {
				// First try to grant full permissions
				$fullPrivArr = array(
					'BACKUP DATABASE', 'BACKUP LOG', 'CREATE FUNCTION', 'CREATE PROCEDURE',
					'CREATE TABLE', 'CREATE VIEW', 'CREATE FULLTEXT CATALOG', 'SHOWPLAN'
				);
				$fullPrivList = implode( ', ', $fullPrivArr );
				$this->db->begin();
				$this->db->query( "GRANT $fullPrivList ON DATABASE :: $escDb TO $escUser", __METHOD__ );
				$this->db->query( "GRANT CONTROL ON SCHEMA :: $escSchema TO $escUser", __METHOD__ );
				$this->db->commit();
			} catch ( DBQueryError $dqe ) {
				// If that fails, try to grant the limited subset specified in $this->webUserPrivs
				try {
					$privList = implode( ', ', $this->webUserPrivs );
					$this->db->rollback();
					$this->db->begin();
					$this->db->query( "GRANT $privList ON SCHEMA :: $escSchema TO $escUser", __METHOD__ );
					$this->db->commit();
				} catch ( DBQueryError $dqe ) {
					$this->db->rollback();
					$status->fatal( 'config-install-user-grant-failed', $dbUser, $dqe->getText() );
				}
				// Also try to grant SHOWPLAN on the db, but don't fail if we can't
				// (just makes a couple things in mediawiki run slower since
				// we have to run SELECT COUNT(*) instead of getting the query plan)
				try {
					$this->db->query( "GRANT SHOWPLAN ON DATABASE :: $escDb TO $escUser", __METHOD__ );
				} catch ( DBQueryError $dqe ) {
				}
			}
		}

		return $status;
	}

	public function createTables() {
		$status = parent::createTables();

		// Do last-minute stuff like fulltext indexes (since they can't be inside a transaction)
		if ( $status->isOk() ) {
			$searchindex = $this->db->tableName( 'searchindex' );
			$schema = $this->db->addIdentifierQuotes( $this->getVar( 'wgDBmwschema' ) );
			try {
				$this->db->query( "CREATE FULLTEXT INDEX ON $searchindex (si_title, si_text) KEY INDEX si_page ON $schema" );
			} catch ( DBQueryError $dqe ) {
				$status->fatal( 'config-install-tables-failed', $dqe->getText() );
			}
		}

		return $status;
	}

	/**
	 * Try to see if the login exists
	 * @param string $user Username to check
	 * @return boolean
	 */
	private function loginExists( $user ) {
		$res = $this->db->selectField( 'sys.sql_logins', 1, array( 'name' => $user ) );
		return (bool)$res;
	}

	/**
	 * Try to see if the user account exists
	 * We assume we already have the appropriate database selected
	 * @param string $user Username to check
	 * @return boolean
	 */
	private function userExists( $user ) {
		$res = $this->db->selectField( 'sys.sysusers', 1, array( 'name' => $user ) );
		return (bool)$res;
	}

	/**
	 * Try to see if a given database exists
	 * @param string $dbName Database name to check
	 * @return boolean
	 */
	private function databaseExists( $dbName ) {
		$res = $this->db->selectField( 'sys.databases', 1, array( 'name' => $dbName ) );
		return (bool)$res;
	}

	/**
	 * Try to see if a given schema exists
	 * We assume we already have the appropriate database selected
	 * @param string $schemaName Schema name to check
	 * @return boolean
	 */
	private function schemaExists( $schemaName ) {
		$res = $this->db->selectField( 'sys.schemas', 1, array( 'name' => $schemaName ) );
		return (bool)$res;
	}

	/**
	 * Try to see if a given fulltext catalog exists
	 * We assume we already have the appropriate database selected
	 * @param string $schemaName Catalog name to check
	 * @return boolean
	 */
	private function catalogExists( $catalogName ) {
		$res = $this->db->selectField( 'sys.fulltext_catalogs', 1, array( 'name' => $catalogName ) );
		return (bool)$res;
	}

	/**
	 * Get variables to substitute into tables.sql and the SQL patch files.
	 *
	 * @return array
	 */
	public function getSchemaVars() {
		return array(
			'wgDBname' => $this->getVar( 'wgDBname' ),
			'wgDBmwschema' => $this->getVar( 'wgDBmwschema' ),
			'wgDBuser' => $this->getVar( 'wgDBuser' ),
			'wgDBpassword' => $this->getVar( 'wgDBpassword' ),
		);
	}

	public function getLocalSettings() {
		$schema = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgDBmwschema' ) );
		$prefix = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgDBprefix' ) );
		$windowsauth = $this->getVar( 'wgDBWindowsAuthentication' ) ? 'true' : 'false';

		return "# MSSQL specific settings
\$wgDBWindowsAuthentication = {$windowsauth};
\$wgDBmwschema = \"{$schema}\";
\$wgDBprefix = \"{$prefix}\";";
	}
}
