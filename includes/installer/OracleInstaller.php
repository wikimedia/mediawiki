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
		'_InstallUser' => 'sys',
		'_InstallPassword' => '',
	);

	public function getName() {
		return 'oracle';
	}

	public function isCompiled() {
		return self::checkExtension( 'oci8' );
	}

	public function getConnectForm() {
		return
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), wfMsg( 'config-db-wiki-settings' ) ) .
			$this->getTextBox( 'wgDBname', 'config-db-name' ) .
			$this->parent->getHelpBox( 'config-db-name-help' ) .
			$this->getTextBox( 'wgDBprefix', 'config-db-prefix' ) .
			$this->parent->getHelpBox( 'config-db-prefix-help' ) .
			Xml::closeElement( 'fieldset' ) .
			$this->getInstallUserBox();
	}

	public function submitConnectForm() {
		// Get variables from the request
		$newValues = $this->setVarsFromRequest( array( 'wgDBname', 'wgDBprefix' ) );

		// Validate them
		$status = Status::newGood();
		if ( !strlen( $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-missing-db-name' );
		} elseif ( !preg_match( '/^[a-zA-Z0-9_]+$/', $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-invalid-db-name', $newValues['wgDBname'] );
		}
		if ( !preg_match( '/^[a-zA-Z0-9_]*$/', $newValues['wgDBprefix'] ) ) {
			$status->fatal( 'config-invalid-schema', $newValues['wgDBprefix'] );
		}

		// Submit user box
		if ( $status->isOK() ) {
			$status->merge( $this->submitInstallUserBox() );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Try to connect
		if ( $status->isOK() ) {
			$status->merge( $this->attemptConnection() );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Check version
/*
		$version = $this->conn->getServerVersion();
		if ( version_compare( $version, $this->minimumVersion ) < 0 ) {
			return Status::newFatal( 'config-postgres-old', $this->minimumVersion, $version );
		}
*/
		return $status;
	}


	public function getSettingsForm() {
		// TODO
	}
	
	public function submitSettingsForm() {
		// TODO
	}

	public function getConnection() {
		// TODO
	}

	public function setupDatabase() {
		// TODO
	}

	public function createTables() {
		// TODO
	}

	public function getLocalSettings() {
		$prefix = $this->getVar( 'wgDBprefix' );
		return
"# Oracle specific settings
\$wgDBprefix         = \"{$prefix}\";";
	}
	
}