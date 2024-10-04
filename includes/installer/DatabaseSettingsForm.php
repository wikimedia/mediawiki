<?php

namespace MediaWiki\Installer;

use MediaWiki\Html\Html;
use MediaWiki\Status\Status;

/**
 * @internal
 */
class DatabaseSettingsForm extends DatabaseForm {

	/**
	 * Get HTML for a web form that retrieves settings used for installation.
	 * $this->parent can be assumed to be a WebInstaller.
	 * If the DB type has no settings beyond those already configured with
	 * getConnectForm(), this should return false.
	 * @return string|false
	 */
	public function getHtml() {
		return false;
	}

	/**
	 * Set variables based on the request array, assuming it was submitted via
	 * the form return by getSettingsForm().
	 *
	 * @return Status
	 */
	public function submit() {
		return Status::newGood();
	}

	/**
	 * Get a standard web-user fieldset
	 * @param string|false $noCreateMsg Message to display instead of the creation checkbox.
	 *   Set this to false to show a creation checkbox (default).
	 *
	 * @return string
	 */
	protected function getWebUserBox( $noCreateMsg = false ) {
		$wrapperStyle = $this->getVar( '_SameAccount' ) ? 'display: none' : '';
		$s = "<span class=\"cdx-card\"><span class=\"cdx-card__text\">" .
			Html::element(
				'span',
				[ 'class' => 'cdx-card__text__title' ],
				wfMessage( 'config-db-web-account' )->text()
			) .
			$this->getCheckBox(
				'_SameAccount', 'config-db-web-account-same',
				[ 'class' => 'hideShowRadio cdx-checkbox__input', 'rel' => 'dbOtherAccount' ]
			) .
			Html::openElement( 'div', [ 'id' => 'dbOtherAccount', 'style' => $wrapperStyle ] ) .
			$this->getTextBox( 'wgDBuser', 'config-db-username' ) .
			$this->getPasswordBox( 'wgDBpassword', 'config-db-password' ) .
			$this->webInstaller->getHelpBox( 'config-db-web-help' );
		if ( $noCreateMsg ) {
			$s .= Html::warningBox( wfMessage( $noCreateMsg )->parse(), 'config-warning-box' );
		} else {
			$s .= $this->getCheckBox( '_CreateDBAccount', 'config-db-web-create' );
		}
		$s .= Html::closeElement( 'div' ) . "</span></span></span>";

		return $s;
	}

	/**
	 * Submit the form from getWebUserBox().
	 *
	 * @return Status
	 */
	public function submitWebUserBox() {
		$this->setVarsFromRequest(
			[ 'wgDBuser', 'wgDBpassword', '_SameAccount', '_CreateDBAccount' ]
		);

		if ( $this->getVar( '_SameAccount' ) ) {
			$this->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
			$this->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );
		}

		if ( $this->getVar( '_CreateDBAccount' ) && strval( $this->getVar( 'wgDBpassword' ) ) == '' ) {
			return Status::newFatal( 'config-db-password-empty', $this->getVar( 'wgDBuser' ) );
		}

		return Status::newGood();
	}

}
