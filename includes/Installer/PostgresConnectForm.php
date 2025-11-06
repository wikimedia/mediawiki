<?php

namespace MediaWiki\Installer;

use MediaWiki\Html\Html;
use MediaWiki\Status\Status;

/**
 * @internal
 */
class PostgresConnectForm extends DatabaseConnectForm {

	/** @inheritDoc */
	public function getHtml() {
		return $this->getTextBox(
				'wgDBserver',
				'config-db-host',
				[],
				$this->webInstaller->getHelpBox( 'config-db-host-help' )
			) .
			$this->getTextBox( 'wgDBport', 'config-db-port' ) .
			$this->getCheckBox( 'wgDBssl', 'config-db-ssl' ) .
			"<span class=\"cdx-card\"><span class=\"cdx-card__text\">" .
			Html::element(
				'span',
				[ 'class' => 'cdx-card__text__title' ],
				wfMessage( 'config-db-wiki-settings' )->text()
			) .
			$this->getTextBox(
				'wgDBname',
				'config-db-name',
				[],
				$this->webInstaller->getHelpBox( 'config-db-name-help' )
			) .
			$this->getTextBox(
				'wgDBmwschema',
				'config-db-schema',
				[],
				$this->webInstaller->getHelpBox( 'config-db-schema-help' )
			) .
			"</span></span></span>" .
			$this->getInstallUserBox();
	}

	/** @inheritDoc */
	public function submit() {
		// Get variables from the request
		$newValues = $this->setVarsFromRequest( [
			'wgDBserver',
			'wgDBport',
			'wgDBssl',
			'wgDBname',
			'wgDBmwschema'
		] );

		// Validate them
		$status = Status::newGood();
		if ( ( $newValues['wgDBname'] ?? '' ) === '' ) {
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

		$status = $this->getPostgresInstaller()->getConnection( DatabaseInstaller::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return $status;
		}

		$conn = $status->getDB();

		// Check version
		$status = PostgresInstaller::meetsMinimumRequirement( $conn );
		if ( !$status->isOK() ) {
			return $status;
		}

		$this->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
		$this->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );

		return Status::newGood();
	}

	/**
	 * Downcast the DatabaseInstaller
	 */
	private function getPostgresInstaller(): PostgresInstaller {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->dbInstaller;
	}

}
