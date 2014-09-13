<?php
/**
 * Base code for web installer pages.
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
 * Abstract class to define pages for the web installer.
 *
 * @ingroup Deployment
 * @since 1.17
 */
abstract class WebInstallerPage {

	/**
	 * The WebInstaller object this WebInstallerPage belongs to.
	 *
	 * @var WebInstaller
	 */
	public $parent;

	/**
	 * @return string
	 */
	abstract public function execute();

	/**
	 * @param WebInstaller $parent
	 */
	public function __construct( WebInstaller $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Is this a slow-running page in the installer? If so, WebInstaller will
	 * set_time_limit(0) before calling execute(). Right now this only applies
	 * to Install and Upgrade pages
	 *
	 * @return bool Always false in this default implementation.
	 */
	public function isSlow() {
		return false;
	}

	/**
	 * @param string $html
	 */
	public function addHTML( $html ) {
		$this->parent->output->addHTML( $html );
	}

	public function startForm() {
		$this->addHTML(
			"<div class=\"config-section\">\n" .
			Html::openElement(
				'form',
				array(
					'method' => 'post',
					'action' => $this->parent->getUrl( array( 'page' => $this->getName() ) )
				)
			) . "\n"
		);
	}

	/**
	 * @param string|bool $continue
	 * @param string|bool $back
	 */
	public function endForm( $continue = 'continue', $back = 'back' ) {
		$s = "<div class=\"config-submit\">\n";
		$id = $this->getId();

		if ( $id === false ) {
			$s .= Html::hidden( 'lastPage', $this->parent->request->getVal( 'lastPage' ) );
		}

		if ( $continue ) {
			// Fake submit button for enter keypress (bug 26267)
			// Messages: config-continue, config-restart, config-regenerate
			$s .= Xml::submitButton(
				wfMessage( "config-$continue" )->text(),
				array(
					'name' => "enter-$continue",
					'style' => 'visibility:hidden;overflow:hidden;width:1px;margin:0'
				)
			) . "\n";
		}

		if ( $back ) {
			// Message: config-back
			$s .= Xml::submitButton(
				wfMessage( "config-$back" )->text(),
				array(
					'name' => "submit-$back",
					'tabindex' => $this->parent->nextTabIndex()
				)
			) . "\n";
		}

		if ( $continue ) {
			// Messages: config-continue, config-restart, config-regenerate
			$s .= Xml::submitButton(
				wfMessage( "config-$continue" )->text(),
				array(
					'name' => "submit-$continue",
					'tabindex' => $this->parent->nextTabIndex(),
				)
			) . "\n";
		}

		$s .= "</div></form></div>\n";
		$this->addHTML( $s );
	}

	/**
	 * @return string
	 */
	public function getName() {
		return str_replace( 'WebInstaller_', '', get_class( $this ) );
	}

	/**
	 * @return string
	 */
	protected function getId() {
		return array_search( $this->getName(), $this->parent->pageSequence );
	}

	/**
	 * @param string $var
	 *
	 * @return mixed
	 */
	public function getVar( $var ) {
		return $this->parent->getVar( $var );
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setVar( $name, $value ) {
		$this->parent->setVar( $name, $value );
	}

	/**
	 * Get the starting tags of a fieldset.
	 *
	 * @param string $legend message name
	 *
	 * @return string
	 */
	protected function getFieldsetStart( $legend ) {
		return "\n<fieldset><legend>" . wfMessage( $legend )->escaped() . "</legend>\n";
	}

	/**
	 * Get the end tag of a fieldset.
	 *
	 * @return string
	 */
	protected function getFieldsetEnd() {
		return "</fieldset>\n";
	}

	/**
	 * Opens a textarea used to display the progress of a long operation
	 */
	protected function startLiveBox() {
		$this->addHTML(
			'<div id="config-spinner" style="display:none;">' .
			'<img src="../skins/common/images/ajax-loader.gif" /></div>' .
			'<script>jQuery( "#config-spinner" ).show();</script>' .
			'<div id="config-live-log">' .
			'<textarea name="LiveLog" rows="10" cols="30" readonly="readonly">'
		);
		$this->parent->output->flush();
	}

	/**
	 * Opposite to WebInstallerPage::startLiveBox
	 */
	protected function endLiveBox() {
		$this->addHTML( '</textarea></div>
<script>jQuery( "#config-spinner" ).hide()</script>' );
		$this->parent->output->flush();
	}

}

class WebInstaller_Language extends WebInstallerPage {

	/**
	 * @return string|null
	 */
	public function execute() {
		global $wgLang;
		$r = $this->parent->request;
		$userLang = $r->getVal( 'uselang' );
		$contLang = $r->getVal( 'ContLang' );

		$languages = Language::fetchLanguageNames();
		$lifetime = intval( ini_get( 'session.gc_maxlifetime' ) );
		if ( !$lifetime ) {
			$lifetime = 1440; // PHP default
		}

		if ( $r->wasPosted() ) {
			# Do session test
			if ( $this->parent->getSession( 'test' ) === null ) {
				$requestTime = $r->getVal( 'LanguageRequestTime' );
				if ( !$requestTime ) {
					// The most likely explanation is that the user was knocked back
					// from another page on POST due to session expiry
					$msg = 'config-session-expired';
				} elseif ( time() - $requestTime > $lifetime ) {
					$msg = 'config-session-expired';
				} else {
					$msg = 'config-no-session';
				}
				$this->parent->showError( $msg, $wgLang->formatTimePeriod( $lifetime ) );
			} else {
				if ( isset( $languages[$userLang] ) ) {
					$this->setVar( '_UserLang', $userLang );
				}
				if ( isset( $languages[$contLang] ) ) {
					$this->setVar( 'wgLanguageCode', $contLang );
				}

				return 'continue';
			}
		} elseif ( $this->parent->showSessionWarning ) {
			# The user was knocked back from another page to the start
			# This probably indicates a session expiry
			$this->parent->showError( 'config-session-expired',
				$wgLang->formatTimePeriod( $lifetime ) );
		}

		$this->parent->setSession( 'test', true );

		if ( !isset( $languages[$userLang] ) ) {
			$userLang = $this->getVar( '_UserLang', 'en' );
		}
		if ( !isset( $languages[$contLang] ) ) {
			$contLang = $this->getVar( 'wgLanguageCode', 'en' );
		}
		$this->startForm();
		$s = Html::hidden( 'LanguageRequestTime', time() ) .
			$this->getLanguageSelector( 'uselang', 'config-your-language', $userLang,
				$this->parent->getHelpBox( 'config-your-language-help' ) ) .
			$this->getLanguageSelector( 'ContLang', 'config-wiki-language', $contLang,
				$this->parent->getHelpBox( 'config-wiki-language-help' ) );
		$this->addHTML( $s );
		$this->endForm( 'continue', false );

		return null;
	}

	/**
	 * Get a "<select>" for selecting languages.
	 *
	 * @param string $name
	 * @param string $label
	 * @param string $selectedCode
	 * @param string $helpHtml
	 *
	 * @return string
	 */
	public function getLanguageSelector( $name, $label, $selectedCode, $helpHtml = '' ) {
		global $wgDummyLanguageCodes;

		$s = $helpHtml;

		$s .= Html::openElement( 'select', array( 'id' => $name, 'name' => $name,
				'tabindex' => $this->parent->nextTabIndex() ) ) . "\n";

		$languages = Language::fetchLanguageNames();
		ksort( $languages );
		foreach ( $languages as $code => $lang ) {
			if ( isset( $wgDummyLanguageCodes[$code] ) ) {
				continue;
			}
			$s .= "\n" . Xml::option( "$code - $lang", $code, $code == $selectedCode );
		}
		$s .= "\n</select>\n";

		return $this->parent->label( $label, $name, $s );
	}

}

class WebInstaller_ExistingWiki extends WebInstallerPage {

	/**
	 * @return string
	 */
	public function execute() {
		// If there is no LocalSettings.php, continue to the installer welcome page
		$vars = Installer::getExistingLocalSettings();
		if ( !$vars ) {
			return 'skip';
		}

		// Check if the upgrade key supplied to the user has appeared in LocalSettings.php
		if ( $vars['wgUpgradeKey'] !== false
			&& $this->getVar( '_UpgradeKeySupplied' )
			&& $this->getVar( 'wgUpgradeKey' ) === $vars['wgUpgradeKey']
		) {
			// It's there, so the user is authorized
			$status = $this->handleExistingUpgrade( $vars );
			if ( $status->isOK() ) {
				return 'skip';
			} else {
				$this->startForm();
				$this->parent->showStatusBox( $status );
				$this->endForm( 'continue' );

				return 'output';
			}
		}

		// If there is no $wgUpgradeKey, tell the user to add one to LocalSettings.php
		if ( $vars['wgUpgradeKey'] === false ) {
			if ( $this->getVar( 'wgUpgradeKey', false ) === false ) {
				$secretKey = $this->getVar( 'wgSecretKey' ); // preserve $wgSecretKey
				$this->parent->generateKeys();
				$this->setVar( 'wgSecretKey', $secretKey );
				$this->setVar( '_UpgradeKeySupplied', true );
			}
			$this->startForm();
			$this->addHTML( $this->parent->getInfoBox(
				wfMessage( 'config-upgrade-key-missing', "<pre dir=\"ltr\">\$wgUpgradeKey = '" .
					$this->getVar( 'wgUpgradeKey' ) . "';</pre>" )->plain()
			) );
			$this->endForm( 'continue' );

			return 'output';
		}

		// If there is an upgrade key, but it wasn't supplied, prompt the user to enter it

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$key = $r->getText( 'config_wgUpgradeKey' );
			if ( !$key || $key !== $vars['wgUpgradeKey'] ) {
				$this->parent->showError( 'config-localsettings-badkey' );
				$this->showKeyForm();

				return 'output';
			}
			// Key was OK
			$status = $this->handleExistingUpgrade( $vars );
			if ( $status->isOK() ) {
				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
				$this->showKeyForm();

				return 'output';
			}
		} else {
			$this->showKeyForm();

			return 'output';
		}
	}

	/**
	 * Show the "enter key" form
	 */
	protected function showKeyForm() {
		$this->startForm();
		$this->addHTML(
			$this->parent->getInfoBox( wfMessage( 'config-localsettings-upgrade' )->plain() ) .
			'<br />' .
			$this->parent->getTextBox( array(
				'var' => 'wgUpgradeKey',
				'label' => 'config-localsettings-key',
				'attribs' => array( 'autocomplete' => 'off' ),
			) )
		);
		$this->endForm( 'continue' );
	}

	/**
	 * @param string[] $names
	 * @param mixed[] $vars
	 *
	 * @return Status
	 */
	protected function importVariables( $names, $vars ) {
		$status = Status::newGood();
		foreach ( $names as $name ) {
			if ( !isset( $vars[$name] ) ) {
				$status->fatal( 'config-localsettings-incomplete', $name );
			}
			$this->setVar( $name, $vars[$name] );
		}

		return $status;
	}

	/**
	 * Initiate an upgrade of the existing database
	 *
	 * @param mixed[] $vars Variables from LocalSettings.php
	 *
	 * @return Status
	 */
	protected function handleExistingUpgrade( $vars ) {
		// Check $wgDBtype
		if ( !isset( $vars['wgDBtype'] ) ||
			!in_array( $vars['wgDBtype'], Installer::getDBTypes() )
		) {
			return Status::newFatal( 'config-localsettings-connection-error', '' );
		}

		// Set the relevant variables from LocalSettings.php
		$requiredVars = array( 'wgDBtype' );
		$status = $this->importVariables( $requiredVars, $vars );
		$installer = $this->parent->getDBInstaller();
		$status->merge( $this->importVariables( $installer->getGlobalNames(), $vars ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		if ( isset( $vars['wgDBadminuser'] ) ) {
			$this->setVar( '_InstallUser', $vars['wgDBadminuser'] );
		} else {
			$this->setVar( '_InstallUser', $vars['wgDBuser'] );
		}
		if ( isset( $vars['wgDBadminpassword'] ) ) {
			$this->setVar( '_InstallPassword', $vars['wgDBadminpassword'] );
		} else {
			$this->setVar( '_InstallPassword', $vars['wgDBpassword'] );
		}

		// Test the database connection
		$status = $installer->getConnection();
		if ( !$status->isOK() ) {
			// Adjust the error message to explain things correctly
			$status->replaceMessage( 'config-connection-error',
				'config-localsettings-connection-error' );

			return $status;
		}

		// All good
		$this->setVar( '_ExistingDBSettings', true );

		return $status;
	}

}

class WebInstaller_Welcome extends WebInstallerPage {

	/**
	 * @return string
	 */
	public function execute() {
		if ( $this->parent->request->wasPosted() ) {
			if ( $this->getVar( '_Environment' ) ) {
				return 'continue';
			}
		}
		$this->parent->output->addWikiText( wfMessage( 'config-welcome' )->plain() );
		$status = $this->parent->doEnvironmentChecks();
		if ( $status->isGood() ) {
			$this->parent->output->addHTML( '<span class="success-message">' .
				wfMessage( 'config-env-good' )->escaped() . '</span>' );
			$this->parent->output->addWikiText( wfMessage( 'config-copyright',
				SpecialVersion::getCopyrightAndAuthorList() )->plain() );
			$this->startForm();
			$this->endForm();
		} else {
			$this->parent->showStatusMessage( $status );
		}

		return '';
	}

}

class WebInstaller_DBConnect extends WebInstallerPage {

	/**
	 * @return string|null When string, "skip" or "continue"
	 */
	public function execute() {
		if ( $this->getVar( '_ExistingDBSettings' ) ) {
			return 'skip';
		}

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$status = $this->submit();

			if ( $status->isGood() ) {
				$this->setVar( '_UpgradeDone', false );

				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
			}
		}

		$this->startForm();

		$types = "<ul class=\"config-settings-block\">\n";
		$settings = '';
		$defaultType = $this->getVar( 'wgDBtype' );

		// Messages: config-dbsupport-mysql, config-dbsupport-postgres, config-dbsupport-oracle,
		// config-dbsupport-sqlite, config-dbsupport-mssql
		$dbSupport = '';
		foreach ( Installer::getDBTypes() as $type ) {
			$dbSupport .= wfMessage( "config-dbsupport-$type" )->plain() . "\n";
		}
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-support-info', trim( $dbSupport ) )->text() ) );

		// It's possible that the library for the default DB type is not compiled in.
		// In that case, instead select the first supported DB type in the list.
		$compiledDBs = $this->parent->getCompiledDBs();
		if ( !in_array( $defaultType, $compiledDBs ) ) {
			$defaultType = $compiledDBs[0];
		}

		foreach ( $compiledDBs as $type ) {
			$installer = $this->parent->getDBInstaller( $type );
			$types .=
				'<li>' .
				Xml::radioLabel(
					$installer->getReadableName(),
					'DBType',
					$type,
					"DBType_$type",
					$type == $defaultType,
					array( 'class' => 'dbRadio', 'rel' => "DB_wrapper_$type" )
				) .
				"</li>\n";

			// Messages: config-header-mysql, config-header-postgres, config-header-oracle,
			// config-header-sqlite
			$settings .= Html::openElement(
					'div',
					array(
						'id' => 'DB_wrapper_' . $type,
						'class' => 'dbWrapper'
					)
				) .
				Html::element( 'h3', array(), wfMessage( 'config-header-' . $type )->text() ) .
				$installer->getConnectForm() .
				"</div>\n";
		}

		$types .= "</ul><br style=\"clear: left\"/>\n";

		$this->addHTML( $this->parent->label( 'config-db-type', false, $types ) . $settings );
		$this->endForm();

		return null;
	}

	/**
	 * @return Status
	 */
	public function submit() {
		$r = $this->parent->request;
		$type = $r->getVal( 'DBType' );
		if ( !$type ) {
			return Status::newFatal( 'config-invalid-db-type' );
		}
		$this->setVar( 'wgDBtype', $type );
		$installer = $this->parent->getDBInstaller( $type );
		if ( !$installer ) {
			return Status::newFatal( 'config-invalid-db-type' );
		}

		return $installer->submitConnectForm();
	}

}

class WebInstaller_Upgrade extends WebInstallerPage {

	/**
	 * @return bool Always true.
	 */
	public function isSlow() {
		return true;
	}

	/**
	 * @return string|null
	 */
	public function execute() {
		if ( $this->getVar( '_UpgradeDone' ) ) {
			// Allow regeneration of LocalSettings.php, unless we are working
			// from a pre-existing LocalSettings.php file and we want to avoid
			// leaking its contents
			if ( $this->parent->request->wasPosted() && !$this->getVar( '_ExistingDBSettings' ) ) {
				// Done message acknowledged
				return 'continue';
			} else {
				// Back button click
				// Show the done message again
				// Make them click back again if they want to do the upgrade again
				$this->showDoneMessage();

				return 'output';
			}
		}

		// wgDBtype is generally valid here because otherwise the previous page
		// (connect) wouldn't have declared its happiness
		$type = $this->getVar( 'wgDBtype' );
		$installer = $this->parent->getDBInstaller( $type );

		if ( !$installer->needsUpgrade() ) {
			return 'skip';
		}

		if ( $this->parent->request->wasPosted() ) {
			$installer->preUpgrade();

			$this->startLiveBox();
			$result = $installer->doUpgrade();
			$this->endLiveBox();

			if ( $result ) {
				// If they're going to possibly regenerate LocalSettings, we
				// need to create the upgrade/secret keys. Bug 26481
				if ( !$this->getVar( '_ExistingDBSettings' ) ) {
					$this->parent->generateKeys();
				}
				$this->setVar( '_UpgradeDone', true );
				$this->showDoneMessage();

				return 'output';
			}
		}

		$this->startForm();
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-can-upgrade', $GLOBALS['wgVersion'] )->plain() ) );
		$this->endForm();

		return null;
	}

	public function showDoneMessage() {
		$this->startForm();
		$regenerate = !$this->getVar( '_ExistingDBSettings' );
		if ( $regenerate ) {
			$msg = 'config-upgrade-done';
		} else {
			$msg = 'config-upgrade-done-no-regenerate';
		}
		$this->parent->disableLinkPopups();
		$this->addHTML(
			$this->parent->getInfoBox(
				wfMessage( $msg,
					$this->getVar( 'wgServer' ) .
					$this->getVar( 'wgScriptPath' ) . '/index' .
					$this->getVar( 'wgScriptExtension' )
				)->plain(), 'tick-32.png'
			)
		);
		$this->parent->restoreLinkPopups();
		$this->endForm( $regenerate ? 'regenerate' : false, false );
	}

}

class WebInstaller_DBSettings extends WebInstallerPage {

	/**
	 * @return string|null
	 */
	public function execute() {
		$installer = $this->parent->getDBInstaller( $this->getVar( 'wgDBtype' ) );

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$status = $installer->submitSettingsForm();
			if ( $status === false ) {
				return 'skip';
			} elseif ( $status->isGood() ) {
				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
			}
		}

		$form = $installer->getSettingsForm();
		if ( $form === false ) {
			return 'skip';
		}

		$this->startForm();
		$this->addHTML( $form );
		$this->endForm();

		return null;
	}

}

class WebInstaller_Name extends WebInstallerPage {

	/**
	 * @return string
	 */
	public function execute() {
		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			if ( $this->submit() ) {
				return 'continue';
			}
		}

		$this->startForm();

		// Encourage people to not name their site 'MediaWiki' by blanking the
		// field. I think that was the intent with the original $GLOBALS['wgSitename']
		// but these two always were the same so had the effect of making the
		// installer forget $wgSitename when navigating back to this page.
		if ( $this->getVar( 'wgSitename' ) == 'MediaWiki' ) {
			$this->setVar( 'wgSitename', '' );
		}

		// Set wgMetaNamespace to something valid before we show the form.
		// $wgMetaNamespace defaults to $wgSiteName which is 'MediaWiki'
		$metaNS = $this->getVar( 'wgMetaNamespace' );
		$this->setVar(
			'wgMetaNamespace',
			wfMessage( 'config-ns-other-default' )->inContentLanguage()->text()
		);

		$this->addHTML(
			$this->parent->getTextBox( array(
				'var' => 'wgSitename',
				'label' => 'config-site-name',
				'help' => $this->parent->getHelpBox( 'config-site-name-help' )
			) ) .
			// getRadioSet() builds a set of labeled radio buttons.
			// For grep: The following messages are used as the item labels:
			// config-ns-site-name, config-ns-generic, config-ns-other
			$this->parent->getRadioSet( array(
				'var' => '_NamespaceType',
				'label' => 'config-project-namespace',
				'itemLabelPrefix' => 'config-ns-',
				'values' => array( 'site-name', 'generic', 'other' ),
				'commonAttribs' => array( 'class' => 'enableForOther',
					'rel' => 'config_wgMetaNamespace' ),
				'help' => $this->parent->getHelpBox( 'config-project-namespace-help' )
			) ) .
			$this->parent->getTextBox( array(
				'var' => 'wgMetaNamespace',
				'label' => '', // @todo Needs a label?
				'attribs' => array( 'readonly' => 'readonly', 'class' => 'enabledByOther' )
			) ) .
			$this->getFieldSetStart( 'config-admin-box' ) .
			$this->parent->getTextBox( array(
				'var' => '_AdminName',
				'label' => 'config-admin-name',
				'help' => $this->parent->getHelpBox( 'config-admin-help' )
			) ) .
			$this->parent->getPasswordBox( array(
				'var' => '_AdminPassword',
				'label' => 'config-admin-password',
			) ) .
			$this->parent->getPasswordBox( array(
				'var' => '_AdminPassword2',
				'label' => 'config-admin-password-confirm'
			) ) .
			$this->parent->getTextBox( array(
				'var' => '_AdminEmail',
				'label' => 'config-admin-email',
				'help' => $this->parent->getHelpBox( 'config-admin-email-help' )
			) ) .
			$this->parent->getCheckBox( array(
				'var' => '_Subscribe',
				'label' => 'config-subscribe',
				'help' => $this->parent->getHelpBox( 'config-subscribe-help' )
			) ) .
			$this->getFieldSetEnd() .
			$this->parent->getInfoBox( wfMessage( 'config-almost-done' )->text() ) .
			// getRadioSet() builds a set of labeled radio buttons.
			// For grep: The following messages are used as the item labels:
			// config-optional-continue, config-optional-skip
			$this->parent->getRadioSet( array(
				'var' => '_SkipOptional',
				'itemLabelPrefix' => 'config-optional-',
				'values' => array( 'continue', 'skip' )
			) )
		);

		// Restore the default value
		$this->setVar( 'wgMetaNamespace', $metaNS );

		$this->endForm();

		return 'output';
	}

	/**
	 * @return bool
	 */
	public function submit() {
		$retVal = true;
		$this->parent->setVarsFromRequest( array( 'wgSitename', '_NamespaceType',
			'_AdminName', '_AdminPassword', '_AdminPassword2', '_AdminEmail',
			'_Subscribe', '_SkipOptional', 'wgMetaNamespace' ) );

		// Validate site name
		if ( strval( $this->getVar( 'wgSitename' ) ) === '' ) {
			$this->parent->showError( 'config-site-name-blank' );
			$retVal = false;
		}

		// Fetch namespace
		$nsType = $this->getVar( '_NamespaceType' );
		if ( $nsType == 'site-name' ) {
			$name = $this->getVar( 'wgSitename' );
			// Sanitize for namespace
			// This algorithm should match the JS one in WebInstallerOutput.php
			$name = preg_replace( '/[\[\]\{\}|#<>%+? ]/', '_', $name );
			$name = str_replace( '&', '&amp;', $name );
			$name = preg_replace( '/__+/', '_', $name );
			$name = ucfirst( trim( $name, '_' ) );
		} elseif ( $nsType == 'generic' ) {
			$name = wfMessage( 'config-ns-generic' )->text();
		} else { // other
			$name = $this->getVar( 'wgMetaNamespace' );
		}

		// Validate namespace
		if ( strpos( $name, ':' ) !== false ) {
			$good = false;
		} else {
			// Title-style validation
			$title = Title::newFromText( $name );
			if ( !$title ) {
				$good = $nsType == 'site-name';
			} else {
				$name = $title->getDBkey();
				$good = true;
			}
		}
		if ( !$good ) {
			$this->parent->showError( 'config-ns-invalid', $name );
			$retVal = false;
		}

		// Make sure it won't conflict with any existing namespaces
		global $wgContLang;
		$nsIndex = $wgContLang->getNsIndex( $name );
		if ( $nsIndex !== false && $nsIndex !== NS_PROJECT ) {
			$this->parent->showError( 'config-ns-conflict', $name );
			$retVal = false;
		}

		$this->setVar( 'wgMetaNamespace', $name );

		// Validate username for creation
		$name = $this->getVar( '_AdminName' );
		if ( strval( $name ) === '' ) {
			$this->parent->showError( 'config-admin-name-blank' );
			$cname = $name;
			$retVal = false;
		} else {
			$cname = User::getCanonicalName( $name, 'creatable' );
			if ( $cname === false ) {
				$this->parent->showError( 'config-admin-name-invalid', $name );
				$retVal = false;
			} else {
				$this->setVar( '_AdminName', $cname );
			}
		}

		// Validate password
		$msg = false;
		$pwd = $this->getVar( '_AdminPassword' );
		$user = User::newFromName( $cname );
		if ( $user ) {
			$valid = $user->getPasswordValidity( $pwd );
		} else {
			$valid = 'config-admin-name-invalid';
		}
		if ( strval( $pwd ) === '' ) {
			# $user->getPasswordValidity just checks for $wgMinimalPasswordLength.
			# This message is more specific and helpful.
			$msg = 'config-admin-password-blank';
		} elseif ( $pwd !== $this->getVar( '_AdminPassword2' ) ) {
			$msg = 'config-admin-password-mismatch';
		} elseif ( $valid !== true ) {
			$msg = $valid;
		}
		if ( $msg !== false ) {
			call_user_func_array( array( $this->parent, 'showError' ), (array)$msg );
			$this->setVar( '_AdminPassword', '' );
			$this->setVar( '_AdminPassword2', '' );
			$retVal = false;
		}

		// Validate e-mail if provided
		$email = $this->getVar( '_AdminEmail' );
		if ( $email && !Sanitizer::validateEmail( $email ) ) {
			$this->parent->showError( 'config-admin-error-bademail' );
			$retVal = false;
		}
		// If they asked to subscribe to mediawiki-announce but didn't give
		// an e-mail, show an error. Bug 29332
		if ( !$email && $this->getVar( '_Subscribe' ) ) {
			$this->parent->showError( 'config-subscribe-noemail' );
			$retVal = false;
		}

		return $retVal;
	}

}

class WebInstaller_Options extends WebInstallerPage {

	/**
	 * @return string|null
	 */
	public function execute() {
		if ( $this->getVar( '_SkipOptional' ) == 'skip' ) {
			return 'skip';
		}
		if ( $this->parent->request->wasPosted() ) {
			if ( $this->submit() ) {
				return 'continue';
			}
		}

		$emailwrapperStyle = $this->getVar( 'wgEnableEmail' ) ? '' : 'display: none';
		$this->startForm();
		$this->addHTML(
			# User Rights
			// getRadioSet() builds a set of labeled radio buttons.
			// For grep: The following messages are used as the item labels:
			// config-profile-wiki, config-profile-no-anon, config-profile-fishbowl, config-profile-private
			$this->parent->getRadioSet( array(
				'var' => '_RightsProfile',
				'label' => 'config-profile',
				'itemLabelPrefix' => 'config-profile-',
				'values' => array_keys( $this->parent->rightsProfiles ),
			) ) .
			$this->parent->getInfoBox( wfMessage( 'config-profile-help' )->plain() ) .

			# Licensing
			// getRadioSet() builds a set of labeled radio buttons.
			// For grep: The following messages are used as the item labels:
			// config-license-cc-by, config-license-cc-by-sa, config-license-cc-by-nc-sa,
			// config-license-cc-0, config-license-pd, config-license-gfdl,
			// config-license-none, config-license-cc-choose
			$this->parent->getRadioSet( array(
				'var' => '_LicenseCode',
				'label' => 'config-license',
				'itemLabelPrefix' => 'config-license-',
				'values' => array_keys( $this->parent->licenses ),
				'commonAttribs' => array( 'class' => 'licenseRadio' ),
			) ) .
			$this->getCCChooser() .
			$this->parent->getHelpBox( 'config-license-help' ) .

			# E-mail
			$this->getFieldSetStart( 'config-email-settings' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnableEmail',
				'label' => 'config-enable-email',
				'attribs' => array( 'class' => 'showHideRadio', 'rel' => 'emailwrapper' ),
			) ) .
			$this->parent->getHelpBox( 'config-enable-email-help' ) .
			"<div id=\"emailwrapper\" style=\"$emailwrapperStyle\">" .
			$this->parent->getTextBox( array(
				'var' => 'wgPasswordSender',
				'label' => 'config-email-sender'
			) ) .
			$this->parent->getHelpBox( 'config-email-sender-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnableUserEmail',
				'label' => 'config-email-user',
			) ) .
			$this->parent->getHelpBox( 'config-email-user-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnotifUserTalk',
				'label' => 'config-email-usertalk',
			) ) .
			$this->parent->getHelpBox( 'config-email-usertalk-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnotifWatchlist',
				'label' => 'config-email-watchlist',
			) ) .
			$this->parent->getHelpBox( 'config-email-watchlist-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEmailAuthentication',
				'label' => 'config-email-auth',
			) ) .
			$this->parent->getHelpBox( 'config-email-auth-help' ) .
			"</div>" .
			$this->getFieldSetEnd()
		);

		$extensions = $this->parent->findExtensions();

		if ( $extensions ) {
			$extHtml = $this->getFieldSetStart( 'config-extensions' );

			foreach ( $extensions as $ext ) {
				$extHtml .= $this->parent->getCheckBox( array(
					'var' => "ext-$ext",
					'rawtext' => $ext,
				) );
			}

			$extHtml .= $this->parent->getHelpBox( 'config-extensions-help' ) .
				$this->getFieldSetEnd();
			$this->addHTML( $extHtml );
		}

		// Having / in paths in Windows looks funny :)
		$this->setVar( 'wgDeletedDirectory',
			str_replace(
				'/', DIRECTORY_SEPARATOR,
				$this->getVar( 'wgDeletedDirectory' )
			)
		);

		$uploadwrapperStyle = $this->getVar( 'wgEnableUploads' ) ? '' : 'display: none';
		$this->addHTML(
			# Uploading
			$this->getFieldSetStart( 'config-upload-settings' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnableUploads',
				'label' => 'config-upload-enable',
				'attribs' => array( 'class' => 'showHideRadio', 'rel' => 'uploadwrapper' ),
				'help' => $this->parent->getHelpBox( 'config-upload-help' )
			) ) .
			'<div id="uploadwrapper" style="' . $uploadwrapperStyle . '">' .
			$this->parent->getTextBox( array(
				'var' => 'wgDeletedDirectory',
				'label' => 'config-upload-deleted',
				'attribs' => array( 'dir' => 'ltr' ),
				'help' => $this->parent->getHelpBox( 'config-upload-deleted-help' )
			) ) .
			'</div>' .
			$this->parent->getTextBox( array(
				'var' => 'wgLogo',
				'label' => 'config-logo',
				'attribs' => array( 'dir' => 'ltr' ),
				'help' => $this->parent->getHelpBox( 'config-logo-help' )
			) )
		);
		$this->addHTML(
			$this->parent->getCheckBox( array(
				'var' => 'wgUseInstantCommons',
				'label' => 'config-instantcommons',
				'help' => $this->parent->getHelpBox( 'config-instantcommons-help' )
			) ) .
			$this->getFieldSetEnd()
		);

		$caches = array( 'none' );
		if ( count( $this->getVar( '_Caches' ) ) ) {
			$caches[] = 'accel';
		}
		$caches[] = 'memcached';

		// We'll hide/show this on demand when the value changes, see config.js.
		$cacheval = $this->getVar( 'wgMainCacheType' );
		if ( !$cacheval ) {
			// We need to set a default here; but don't hardcode it
			// or we lose it every time we reload the page for validation
			// or going back!
			$cacheval = 'none';
		}
		$hidden = ( $cacheval == 'memcached' ) ? '' : 'display: none';
		$this->addHTML(
			# Advanced settings
			$this->getFieldSetStart( 'config-advanced-settings' ) .
			# Object cache settings
			// getRadioSet() builds a set of labeled radio buttons.
			// For grep: The following messages are used as the item labels:
			// config-cache-none, config-cache-accel, config-cache-memcached
			$this->parent->getRadioSet( array(
				'var' => 'wgMainCacheType',
				'label' => 'config-cache-options',
				'itemLabelPrefix' => 'config-cache-',
				'values' => $caches,
				'value' => $cacheval,
			) ) .
			$this->parent->getHelpBox( 'config-cache-help' ) .
			"<div id=\"config-memcachewrapper\" style=\"$hidden\">" .
			$this->parent->getTextArea( array(
				'var' => '_MemCachedServers',
				'label' => 'config-memcached-servers',
				'help' => $this->parent->getHelpBox( 'config-memcached-help' )
			) ) .
			'</div>' .
			$this->getFieldSetEnd()
		);
		$this->endForm();

		return null;
	}

	/**
	 * @return string
	 */
	public function getCCPartnerUrl() {
		$server = $this->getVar( 'wgServer' );
		$exitUrl = $server . $this->parent->getUrl( array(
			'page' => 'Options',
			'SubmitCC' => 'indeed',
			'config__LicenseCode' => 'cc',
			'config_wgRightsUrl' => '[license_url]',
			'config_wgRightsText' => '[license_name]',
			'config_wgRightsIcon' => '[license_button]',
		) );
		$styleUrl = $server . dirname( dirname( $this->parent->getUrl() ) ) .
			'/skins/common/config-cc.css';
		$iframeUrl = 'http://creativecommons.org/license/?' .
			wfArrayToCgi( array(
				'partner' => 'MediaWiki',
				'exit_url' => $exitUrl,
				'lang' => $this->getVar( '_UserLang' ),
				'stylesheet' => $styleUrl,
			) );

		return $iframeUrl;
	}

	/**
	 * @return string
	 */
	public function getCCChooser() {
		$iframeAttribs = array(
			'class' => 'config-cc-iframe',
			'name' => 'config-cc-iframe',
			'id' => 'config-cc-iframe',
			'frameborder' => 0,
			'width' => '100%',
			'height' => '100%',
		);
		if ( $this->getVar( '_CCDone' ) ) {
			$iframeAttribs['src'] = $this->parent->getUrl( array( 'ShowCC' => 'yes' ) );
		} else {
			$iframeAttribs['src'] = $this->getCCPartnerUrl();
		}
		$wrapperStyle = ( $this->getVar( '_LicenseCode' ) == 'cc-choose' ) ? '' : 'display: none';

		return "<div class=\"config-cc-wrapper\" id=\"config-cc-wrapper\" style=\"$wrapperStyle\">\n" .
			Html::element( 'iframe', $iframeAttribs, '', false /* not short */ ) .
			"</div>\n";
	}

	/**
	 * @return string
	 */
	public function getCCDoneBox() {
		$js = "parent.document.getElementById('config-cc-wrapper').style.height = '$1';";
		// If you change this height, also change it in config.css
		$expandJs = str_replace( '$1', '54em', $js );
		$reduceJs = str_replace( '$1', '70px', $js );

		return '<p>' .
			Html::element( 'img', array( 'src' => $this->getVar( 'wgRightsIcon' ) ) ) .
			'&#160;&#160;' .
			htmlspecialchars( $this->getVar( 'wgRightsText' ) ) .
			"</p>\n" .
			"<p style=\"text-align: center;\">" .
			Html::element( 'a',
				array(
					'href' => $this->getCCPartnerUrl(),
					'onclick' => $expandJs,
				),
				wfMessage( 'config-cc-again' )->text()
			) .
			"</p>\n" .
			"<script>\n" .
			# Reduce the wrapper div height
			htmlspecialchars( $reduceJs ) .
			"\n" .
			"</script>\n";
	}

	public function submitCC() {
		$newValues = $this->parent->setVarsFromRequest(
			array( 'wgRightsUrl', 'wgRightsText', 'wgRightsIcon' ) );
		if ( count( $newValues ) != 3 ) {
			$this->parent->showError( 'config-cc-error' );

			return;
		}
		$this->setVar( '_CCDone', true );
		$this->addHTML( $this->getCCDoneBox() );
	}

	/**
	 * @return bool
	 */
	public function submit() {
		$this->parent->setVarsFromRequest( array( '_RightsProfile', '_LicenseCode',
			'wgEnableEmail', 'wgPasswordSender', 'wgEnableUploads', 'wgLogo',
			'wgEnableUserEmail', 'wgEnotifUserTalk', 'wgEnotifWatchlist',
			'wgEmailAuthentication', 'wgMainCacheType', '_MemCachedServers',
			'wgUseInstantCommons' ) );

		if ( !array_key_exists( $this->getVar( '_RightsProfile' ), $this->parent->rightsProfiles )
		) {
			reset( $this->parent->rightsProfiles );
			$this->setVar( '_RightsProfile', key( $this->parent->rightsProfiles ) );
		}

		$code = $this->getVar( '_LicenseCode' );
		if ( $code == 'cc-choose' ) {
			if ( !$this->getVar( '_CCDone' ) ) {
				$this->parent->showError( 'config-cc-not-chosen' );

				return false;
			}
		} elseif ( array_key_exists( $code, $this->parent->licenses ) ) {
			// Messages:
			// config-license-cc-by, config-license-cc-by-sa, config-license-cc-by-nc-sa,
			// config-license-cc-0, config-license-pd, config-license-gfdl, config-license-none,
			// config-license-cc-choose
			$entry = $this->parent->licenses[$code];
			if ( isset( $entry['text'] ) ) {
				$this->setVar( 'wgRightsText', $entry['text'] );
			} else {
				$this->setVar( 'wgRightsText', wfMessage( 'config-license-' . $code )->text() );
			}
			$this->setVar( 'wgRightsUrl', $entry['url'] );
			$this->setVar( 'wgRightsIcon', $entry['icon'] );
		} else {
			$this->setVar( 'wgRightsText', '' );
			$this->setVar( 'wgRightsUrl', '' );
			$this->setVar( 'wgRightsIcon', '' );
		}

		$extsAvailable = $this->parent->findExtensions();
		$extsToInstall = array();
		foreach ( $extsAvailable as $ext ) {
			if ( $this->parent->request->getCheck( 'config_ext-' . $ext ) ) {
				$extsToInstall[] = $ext;
			}
		}
		$this->parent->setVar( '_Extensions', $extsToInstall );

		if ( $this->getVar( 'wgMainCacheType' ) == 'memcached' ) {
			$memcServers = explode( "\n", $this->getVar( '_MemCachedServers' ) );
			if ( !$memcServers ) {
				$this->parent->showError( 'config-memcache-needservers' );

				return false;
			}

			foreach ( $memcServers as $server ) {
				$memcParts = explode( ":", $server, 2 );
				if ( !isset( $memcParts[0] )
					|| ( !IP::isValid( $memcParts[0] )
						&& ( gethostbyname( $memcParts[0] ) == $memcParts[0] ) )
				) {
					$this->parent->showError( 'config-memcache-badip', $memcParts[0] );

					return false;
				} elseif ( !isset( $memcParts[1] ) ) {
					$this->parent->showError( 'config-memcache-noport', $memcParts[0] );

					return false;
				} elseif ( $memcParts[1] < 1 || $memcParts[1] > 65535 ) {
					$this->parent->showError( 'config-memcache-badport', 1, 65535 );

					return false;
				}
			}
		}

		return true;
	}

}

class WebInstaller_Install extends WebInstallerPage {

	/**
	 * @return bool Always true.
	 */
	public function isSlow() {
		return true;
	}

	/**
	 * @return string|bool
	 */
	public function execute() {
		if ( $this->getVar( '_UpgradeDone' ) ) {
			return 'skip';
		} elseif ( $this->getVar( '_InstallDone' ) ) {
			return 'continue';
		} elseif ( $this->parent->request->wasPosted() ) {
			$this->startForm();
			$this->addHTML( "<ul>" );
			$results = $this->parent->performInstallation(
				array( $this, 'startStage' ),
				array( $this, 'endStage' )
			);
			$this->addHTML( "</ul>" );
			// PerformInstallation bails on a fatal, so make sure the last item
			// completed before giving 'next.' Likewise, only provide back on failure
			$lastStep = end( $results );
			$continue = $lastStep->isOK() ? 'continue' : false;
			$back = $lastStep->isOK() ? false : 'back';
			$this->endForm( $continue, $back );
		} else {
			$this->startForm();
			$this->addHTML( $this->parent->getInfoBox( wfMessage( 'config-install-begin' )->plain() ) );
			$this->endForm();
		}

		return true;
	}

	/**
	 * @param string $step
	 */
	public function startStage( $step ) {
		// Messages: config-install-database, config-install-tables, config-install-interwiki,
		// config-install-stats, config-install-keys, config-install-sysop, config-install-mainpage
		$this->addHTML( "<li>" . wfMessage( "config-install-$step" )->escaped() .
			wfMessage( 'ellipsis' )->escaped() );

		if ( $step == 'extension-tables' ) {
			$this->startLiveBox();
		}
	}

	/**
	 * @param string $step
	 * @param Status $status
	 */
	public function endStage( $step, $status ) {
		if ( $step == 'extension-tables' ) {
			$this->endLiveBox();
		}
		$msg = $status->isOk() ? 'config-install-step-done' : 'config-install-step-failed';
		$html = wfMessage( 'word-separator' )->escaped() . wfMessage( $msg )->escaped();
		if ( !$status->isOk() ) {
			$html = "<span class=\"error\">$html</span>";
		}
		$this->addHTML( $html . "</li>\n" );
		if ( !$status->isGood() ) {
			$this->parent->showStatusBox( $status );
		}
	}

}

class WebInstaller_Complete extends WebInstallerPage {

	public function execute() {
		// Pop up a dialog box, to make it difficult for the user to forget
		// to download the file
		$lsUrl = $this->getVar( 'wgServer' ) . $this->parent->getURL( array( 'localsettings' => 1 ) );
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
			strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false
		) {
			// JS appears to be the only method that works consistently with IE7+
			$this->addHtml( "\n<script>jQuery( function () { document.location = " .
				Xml::encodeJsVar( $lsUrl ) . "; } );</script>\n" );
		} else {
			$this->parent->request->response()->header( "Refresh: 0;url=$lsUrl" );
		}

		$this->startForm();
		$this->parent->disableLinkPopups();
		$this->addHTML(
			$this->parent->getInfoBox(
				wfMessage( 'config-install-done',
					$lsUrl,
					$this->getVar( 'wgServer' ) .
					$this->getVar( 'wgScriptPath' ) . '/index' .
					$this->getVar( 'wgScriptExtension' ),
					'<downloadlink/>'
				)->plain(), 'tick-32.png'
			)
		);
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-extension-link' )->text() ) );

		$this->parent->restoreLinkPopups();
		$this->endForm( false, false );
	}

}

class WebInstaller_Restart extends WebInstallerPage {

	/**
	 * @return string|null
	 */
	public function execute() {
		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$really = $r->getVal( 'submit-restart' );
			if ( $really ) {
				$this->parent->reset();
			}

			return 'continue';
		}

		$this->startForm();
		$s = $this->parent->getWarningBox( wfMessage( 'config-help-restart' )->plain() );
		$this->addHTML( $s );
		$this->endForm( 'restart' );

		return null;
	}

}

abstract class WebInstaller_Document extends WebInstallerPage {

	/**
	 * @return string
	 */
	abstract protected function getFileName();

	public function execute() {
		$text = $this->getFileContents();
		$text = InstallDocFormatter::format( $text );
		$this->parent->output->addWikiText( $text );
		$this->startForm();
		$this->endForm( false );
	}

	/**
	 * @return string
	 */
	public function getFileContents() {
		$file = __DIR__ . '/../../' . $this->getFileName();
		if ( !file_exists( $file ) ) {
			return wfMessage( 'config-nofile', $file )->plain();
		}

		return file_get_contents( $file );
	}

}

class WebInstaller_Readme extends WebInstaller_Document {

	/**
	 * @return string
	 */
	protected function getFileName() {
		return 'README';
	}

}

class WebInstaller_ReleaseNotes extends WebInstaller_Document {

	/**
	 * @throws MWException
	 * @return string
	 */
	protected function getFileName() {
		global $wgVersion;

		if ( !preg_match( '/^(\d+)\.(\d+).*/i', $wgVersion, $result ) ) {
			throw new MWException( 'Variable $wgVersion has an invalid value.' );
		}

		return 'RELEASE-NOTES-' . $result[1] . '.' . $result[2];
	}

}

class WebInstaller_UpgradeDoc extends WebInstaller_Document {

	/**
	 * @return string
	 */
	protected function getFileName() {
		return 'UPGRADE';
	}

}

class WebInstaller_Copying extends WebInstaller_Document {

	/**
	 * @return string
	 */
	protected function getFileName() {
		return 'COPYING';
	}

}
