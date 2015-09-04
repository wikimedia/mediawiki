<?php
/**
 * Implements Special:Preferences
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
 * @ingroup SpecialPage
 */

/**
 * A special page that allows users to change their preferences
 *
 * @ingroup SpecialPage
 */
class SpecialPreferences extends SpecialPage {
	function __construct() {
		parent::__construct( 'Preferences' );
		$this->validTabs = array(
			'profile',
			'rendering',
			'skin',
			'dateformat',
			'files',
			'editing',
			'rc',
			'personal',
			'misc',
		);
		$this->externalPrefs = array();
	}

	/**
	 * @param {String} $key valid key as specified in validTabs private property
	 * @return {HtmlForm}
	 */
	public function getSectionPreferencesForm( $key ) {
		$prefs = array();
		$user = $this->getUser();
		$ctx = $this->getContext();
		switch ( $key ) {
			case 'rendering':
				Preferences::renderingPreferences( $user, $ctx, $prefs );
				break;
			case 'profile':
				Preferences::profilePreferences( $user, $ctx, $prefs );
				break;
			case 'skin':
				Preferences::skinPreferences( $user, $ctx, $prefs );
				break;
			case 'dateformat':
				Preferences::datetimePreferences( $user, $ctx, $prefs );
				break;
			case 'editing':
				Preferences::editingPreferences( $user, $ctx, $prefs );
				break;
			case 'personal':
				Preferences::profilePreferences( $user, $ctx, $prefs );
				break;
			case 'files':
				Preferences::filesPreferences( $user, $ctx, $prefs );
				break;
			case 'rc':
				Preferences::rcPreferences( $user, $ctx, $prefs );
				break;
			default:
				$prefs = $this->externalPrefs[$key];
				break;
		}
		Preferences::loadPreferenceValues( $user, $ctx, $prefs );
		$htmlForm = new PreferencesForm( $prefs, $ctx, 'prefs' );
		$htmlForm->suppressReset();
		$htmlForm->setModifiedUser( $user );
		$htmlForm->setId( 'mw-prefs-form' );
		$htmlForm->setSubmitText( $ctx->msg( 'saveprefs' )->text() );
		$htmlForm->setAction( SpecialPage::getTitleFor( $this->getName(), $key )->getLocalUrl() );
		return $htmlForm;
	}

	/**
	 * Runs GetPreferences hook and constructs a mechanism for rendering preferences from other extensions as a
	 * separate preference pane.
	 */
	public function loadExternalPreferences() {
		$defaults = array();
		Hooks::run( 'GetPreferences', array( $this->getUser(), &$defaults ) );
		$this->externalPrefs = $defaults;
		foreach( $defaults as $key => $row ) {
			if ( isset( $row["section"] ) ) {
				$section = explode( '/', $row["section"] )[0];
				if ( !in_array( $section, $this->validTabs ) ) {
					$this->validTabs[]  = $section;
					$this->externalPrefs[$section] = array();
				}
				$this->externalPrefs[$section][$key] = $row;
			}
		}
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->disallowUserJs(); # Prevent hijacked user scripts from sniffing passwords etc.

		$this->requireLogin( 'prefsnologintext2' );
		$this->checkReadOnly();

		if ( $par == 'reset' ) {
			$this->showResetForm();

			return;
		}

		$out->addModules( 'mediawiki.special.preferences' );

		if ( $this->getRequest()->getCheck( 'success' ) ) {
			$out->wrapWikiMsg(
				Xml::tags(
					'div',
					array( 'class' => 'successbox', 'id' => 'mw-preferences-success' ),
					'$1'
				),
				'savedprefs'
			);
		}

		$this->addHelpLink( 'Help:Preferences' );

		$this->loadExternalPreferences();

		if ( $par && in_array( $par, $this->validTabs ) ) {
			$htmlForm = $this->getSectionPreferencesForm( $par );
		} else {
			$htmlForm = Preferences::getFormObject( $this->getUser(), $this->getContext() );
		}
		$htmlForm->setSubmitCallback( array( 'Preferences', 'tryUISubmit' ) );

		$htmlForm->show();
	}
	
	private function showResetForm() {
		if ( !$this->getUser()->isAllowed( 'editmyoptions' ) ) {
			throw new PermissionsError( 'editmyoptions' );
		}

		$this->getOutput()->addWikiMsg( 'prefs-reset-intro' );

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle( 'reset' ) ); // Reset subpage
		$htmlForm = new HTMLForm( array(), $context, 'prefs-restore' );

		$htmlForm->setSubmitTextMsg( 'restoreprefs' );
		$htmlForm->setSubmitDestructive();
		$htmlForm->setSubmitCallback( array( $this, 'submitReset' ) );
		$htmlForm->suppressReset();

		$htmlForm->show();
	}

	public function submitReset( $formData ) {
		if ( !$this->getUser()->isAllowed( 'editmyoptions' ) ) {
			throw new PermissionsError( 'editmyoptions' );
		}

		$user = $this->getUser();
		$user->resetOptions( 'all', $this->getContext() );
		$user->saveSettings();

		$url = $this->getPageTitle()->getFullURL( 'success' );

		$this->getOutput()->redirect( $url );

		return true;
	}

	protected function getGroupName() {
		return 'users';
	}
}
