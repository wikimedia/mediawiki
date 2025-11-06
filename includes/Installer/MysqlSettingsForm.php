<?php

namespace MediaWiki\Installer;

use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DBConnectionError;

/**
 * @internal
 */
class MysqlSettingsForm extends DatabaseSettingsForm {

	/**
	 * @return string
	 */
	public function getHtml() {
		if ( $this->getMysqlInstaller()->canCreateAccounts() ) {
			$noCreateMsg = false;
		} else {
			$noCreateMsg = 'config-db-web-no-create-privs';
		}
		$s = $this->getWebUserBox( $noCreateMsg );

		// Do engine selector
		$engines = $this->getMysqlInstaller()->getEngines();
		// If the current default engine is not supported, use an engine that is
		if ( !in_array( $this->getVar( '_MysqlEngine' ), $engines ) ) {
			$this->setVar( '_MysqlEngine', reset( $engines ) );
		}

		// If the current default charset is not supported, use a charset that is
		$charsets = $this->getMysqlInstaller()->getCharsets();
		if ( !in_array( $this->getVar( '_MysqlCharset' ), $charsets ) ) {
			$this->setVar( '_MysqlCharset', reset( $charsets ) );
		}

		return $s;
	}

	/**
	 * @return Status
	 */
	public function submit() {
		$this->setVarsFromRequest( [ '_MysqlEngine', '_MysqlCharset' ] );
		$status = $this->submitWebUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		// Validate the create checkbox
		$canCreate = $this->getMysqlInstaller()->canCreateAccounts();
		if ( !$canCreate ) {
			$this->setVar( '_CreateDBAccount', false );
			$create = false;
		} else {
			$create = $this->getVar( '_CreateDBAccount' );
		}

		if ( !$create ) {
			// Test the web account
			try {
				MediaWikiServices::getInstance()->getDatabaseFactory()->create( 'mysql', [
					'host' => $this->getVar( 'wgDBserver' ),
					'user' => $this->getVar( 'wgDBuser' ),
					'password' => $this->getVar( 'wgDBpassword' ),
					'ssl' => $this->getVar( 'wgDBssl' ),
					'dbname' => null,
					'flags' => 0,
					'tablePrefix' => $this->getVar( 'wgDBprefix' )
				] );
			} catch ( DBConnectionError $e ) {
				return Status::newFatal( 'config-connection-error', $e->getMessage() );
			}
		}

		// Validate engines and charsets
		// This is done pre-submit already, so it's just for security
		$engines = $this->getMysqlInstaller()->getEngines();
		if ( !in_array( $this->getVar( '_MysqlEngine' ), $engines ) ) {
			$this->setVar( '_MysqlEngine', reset( $engines ) );
		}
		$charsets = $this->getMysqlInstaller()->getCharsets();
		if ( !in_array( $this->getVar( '_MysqlCharset' ), $charsets ) ) {
			$this->setVar( '_MysqlCharset', reset( $charsets ) );
		}

		return Status::newGood();
	}

	/**
	 * Downcast the DatabaseInstaller
	 */
	private function getMysqlInstaller(): MysqlInstaller {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->dbInstaller;
	}

}
