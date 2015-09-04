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
	/**
	 * An array mapping tabs to preferences.
	 * @property array mPrefs
	 */
	protected $mPrefs;
		/**
		 * List of preference panes that can be displayed.
		 * @property string[] $validTabs
		 */
	protected $mSections;

	function __construct() {
		parent::__construct( 'Preferences' );
		$this->mPrefs = array();
		$this->mSections = array();
	}

	/**
	 * @param string $key valid key as specified in validTabs private property
	 * @return HTMLForm
	 */
	protected function getSectionPreferencesForm( $key ) {
		$prefs = array();
		$user = $this->getUser();
		$ctx = $this->getContext();
		switch ( $key ) {
			case 'rendering':
				Preferences::renderingPreferences( $user, $ctx, $prefs );
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
				$prefs = $this->mPrefs[$key];
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
	 * Obtains the preferences to be displayed and identifies the sections that are
	 * valid for display.
	 */
	protected function loadTabs() {
		$defaults = Preferences::getPreferences( $this->getUser(), $this->getContext() );
		foreach ( $defaults as $key => $row ) {
			if ( isset( $row["section"] ) ) {
				$section = explode( '/', $row["section"] );
				$section = $section[0];
				if ( !in_array( $section, $this->mSections ) ) {
					$this->mSections[] = $section;
					$this->mPrefs[$section] = array();
				}
				$this->mPrefs[$section][$key] = $row;
			}
		}
	}

	/**
	 * Return an HTML list of links to available preference sections.
	 * @param string $activeSection the section which is currently selected.
	 * @return string
	 */
	protected function getSectionLinksHTML( $activeSection ) {
		$html = Html::openElement( 'div', array( 'class' => 'pref-section-tabs' ) );
		foreach ( $this->mSections as $section ) {
			$html .= new OOUI\ButtonWidget( array(
				'flags' => $activeSection === $section ? array( 'progressive' ) : array(),
				'href' => SpecialPage::getTitleFor( 'Preferences', 'sections/' . $section )
						->getLocalURL(),
				'label' => $this->msg( 'prefs-' . $section )->text(),
			) );
		}
		$html .= Html::closeElement( 'div' );
		return $html;
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

		if ( $this->getRequest()->getCheck( 'success' ) ) {
			$out->wrapWikiMsg(
				Html::rawElement(
					'div',
					array(
						'class' => 'mw-preferences-messagebox successbox',
						'id' => 'mw-preferences-success'
					),
					Html::element( 'p', array(), '$1' )
				),
				'savedprefs'
			);
		}

		$this->addHelpLink( 'Help:Preferences' );

		$this->loadTabs();

		if ( $par && substr( $par, 0, 8 ) === 'sections' ) {
			$out->enableOOUI();
			$route = explode( '/', $par );
			$htmlForm = false;
			if ( isset( $route[1] ) ) {
				$section = $route[1];
			} else {
				$section = 'personal';
			}
			$out->addHTML( $this->getSectionLinksHTML( $section ) );

			if ( in_array( $section, $this->mSections ) ) {
				$htmlForm = $this->getSectionPreferencesForm( $section );
			} elseif ( isset( $route[1] ) ){
				$out->addHtml(
					Html::element( 'div', array( 'class' => 'warningbox' ),
						$this->msg( 'prefs-section-warning' ) )
				);
			}
		} else {
			$out->addModules( 'mediawiki.special.preferences' );
			$htmlForm = Preferences::getFormObject( $this->getUser(), $this->getContext() );
		}

		if ( $htmlForm ) {
			$htmlForm->setSubmitCallback( array( 'Preferences', 'tryUISubmit' ) );
			$htmlForm->show();
		}
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
