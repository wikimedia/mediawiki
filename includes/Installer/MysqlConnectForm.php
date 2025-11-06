<?php

namespace MediaWiki\Installer;

use MediaWiki\Html\Html;
use MediaWiki\Status\Status;

/**
 * @internal
 */
class MysqlConnectForm extends DatabaseConnectForm {
	/**
	 * @return string
	 */
	public function getHtml() {
		return $this->getTextBox(
				'wgDBserver',
				'config-db-host',
				[],
				$this->webInstaller->getHelpBox( 'config-db-host-help' )
			) .
			$this->getCheckBox( 'wgDBssl', 'config-db-ssl' ) .
			"<span class=\"cdx-card\"><span class=\"cdx-card__text\">" .
			Html::element(
				'span',
				[ 'class' => 'cdx-card__text__title' ],
				wfMessage( 'config-db-wiki-settings' )->text()
			) .
			"<span class=\"cdx-card__text__description\">" .
			$this->getTextBox( 'wgDBname', 'config-db-name', [ 'dir' => 'ltr' ],
				$this->webInstaller->getHelpBox( 'config-db-name-help' ) ) .
			$this->getTextBox( 'wgDBprefix', 'config-db-prefix', [ 'dir' => 'ltr' ],
				$this->webInstaller->getHelpBox( 'config-db-prefix-help' ) ) .
			"</span></span></span>" .
			$this->getInstallUserBox();
	}

	/** @inheritDoc */
	public function submit() {
		// Get variables from the request.
		$newValues = $this->setVarsFromRequest( [ 'wgDBserver', 'wgDBname', 'wgDBprefix', 'wgDBssl' ] );

		// Validate them.
		$status = Status::newGood();
		if ( ( $newValues['wgDBserver'] ?? '' ) === '' ) {
			$status->fatal( 'config-missing-db-host' );
		}
		if ( ( $newValues['wgDBname'] ?? '' ) === '' ) {
			$status->fatal( 'config-missing-db-name' );
		} elseif ( !preg_match( '/^[a-z0-9+_-]+$/i', $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-invalid-db-name', $newValues['wgDBname'] );
		}
		if ( !preg_match( '/^[a-z0-9_-]*$/i', $newValues['wgDBprefix'] ) ) {
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
		$status = $this->dbInstaller->getConnection( DatabaseInstaller::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return $status;
		}

		// Check version
		return MysqlInstaller::meetsMinimumRequirement( $status->getDB() );
	}
}
