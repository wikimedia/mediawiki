<?php

namespace MediaWiki\Installer;

use MediaWiki\Html\Html;
use MediaWiki\Status\Status;

/**
 * @internal
 */
abstract class DatabaseConnectForm extends DatabaseForm {

	/**
	 * Get HTML for a web form that configures this database. Configuration
	 * at this time should be the minimum needed to connect and test
	 * whether install or upgrade is required.
	 *
	 * If this is called, $this->parent can be assumed to be a WebInstaller.
	 */
	abstract public function getHtml();

	/**
	 * Set variables based on the request array, assuming it was submitted
	 * via the form returned by getConnectForm(). Validate the connection
	 * settings by attempting to connect with them.
	 *
	 * If this is called, $this->parent can be assumed to be a WebInstaller.
	 *
	 * @return Status
	 */
	abstract public function submit();

	/**
	 * Get a standard install-user fieldset.
	 *
	 * @return string
	 */
	protected function getInstallUserBox() {
		return "<span class=\"cdx-card\"><span class=\"cdx-card__text\">" .
			Html::element(
				'span',
				[ 'class' => 'cdx-card__text__title' ],
				wfMessage( 'config-db-install-account' )->text()
			) .
			"<span class=\"cdx-card__text__description\">" .
			$this->getTextBox(
				'_InstallUser',
				'config-db-username',
				[ 'dir' => 'ltr' ],
				$this->webInstaller->getHelpBox( 'config-db-install-username' )
			) .
			$this->getPasswordBox(
				'_InstallPassword',
				'config-db-password',
				[ 'dir' => 'ltr' ],
				$this->webInstaller->getHelpBox( 'config-db-install-password' )
			) .
			"</span></span></span>";
	}

	/**
	 * Submit a standard install user fieldset.
	 * @return Status
	 */
	protected function submitInstallUserBox() {
		$this->setVarsFromRequest( [ '_InstallUser', '_InstallPassword' ] );

		return Status::newGood();
	}

}
