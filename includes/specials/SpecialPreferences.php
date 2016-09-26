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
	}

	public function doesWrites() {
		return true;
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
		$out->addModuleStyles( 'mediawiki.special.preferences.styles' );

		$session = $this->getRequest()->getSession();
		if ( $session->get( 'specialPreferencesSaveSuccess' ) ) {
			// Remove session data for the success message
			$session->remove( 'specialPreferencesSaveSuccess' );
			$out->addModuleStyles( 'mediawiki.notification.convertmessagebox.styles' );

			$out->addHTML(
				Html::rawElement(
					'div',
					[
						'class' => 'mw-preferences-messagebox mw-notify-success successbox',
						'id' => 'mw-preferences-success',
						'data-mw-autohide' => 'false',
					],
					Html::element( 'p', [], $this->msg( 'savedprefs' )->text() )
				)
			);
		}

		$this->addHelpLink( 'Help:Preferences' );

		// Load the user from the master to reduce CAS errors on double post (T95839)
		if ( $this->getRequest()->wasPosted() ) {
			$user = $this->getUser()->getInstanceForUpdate() ?: $this->getUser();
		} else {
			$user = $this->getUser();
		}

		$htmlForm = Preferences::getFormObject( $user, $this->getContext() );
		$htmlForm->setSubmitCallback( [ 'Preferences', 'tryUISubmit' ] );
		$sectionTitles = $htmlForm->getPreferenceSections();

		$prefTabs = '';
		foreach ( $sectionTitles as $key ) {
			$prefTabs .= Html::rawElement( 'li',
				[
					'role' => 'presentation',
					'class' => ( $key === 'personal' ) ? 'selected' : null
				],
				Html::rawElement( 'a',
					[
						'id' => 'preftab-' . $key,
						'role' => 'tab',
						'href' => '#mw-prefsection-' . $key,
						'aria-controls' => 'mw-prefsection-' . $key,
						'aria-selected' => ( $key === 'personal' ) ? 'true' : 'false',
						'tabIndex' => ( $key === 'personal' ) ? 0 : -1,
					],
					$htmlForm->getLegend( $key )
				)
			);
		}

		$out->addHTML(
			Html::rawElement( 'ul',
				[
					'id' => 'preftoc',
					'role' => 'tablist'
				],
				$prefTabs )
		);
		$htmlForm->show();
	}

	private function showResetForm() {
		if ( !$this->getUser()->isAllowed( 'editmyoptions' ) ) {
			throw new PermissionsError( 'editmyoptions' );
		}

		$this->getOutput()->addWikiMsg( 'prefs-reset-intro' );

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle( 'reset' ) ); // Reset subpage
		$htmlForm = new HTMLForm( [], $context, 'prefs-restore' );

		$htmlForm->setSubmitTextMsg( 'restoreprefs' );
		$htmlForm->setSubmitDestructive();
		$htmlForm->setSubmitCallback( [ $this, 'submitReset' ] );
		$htmlForm->suppressReset();

		$htmlForm->show();
	}

	public function submitReset( $formData ) {
		if ( !$this->getUser()->isAllowed( 'editmyoptions' ) ) {
			throw new PermissionsError( 'editmyoptions' );
		}

		$user = $this->getUser()->getInstanceForUpdate();
		$user->resetOptions( 'all', $this->getContext() );
		$user->saveSettings();

		// Set session data for the success message
		$this->getRequest()->getSession()->set( 'specialPreferencesSaveSuccess', 1 );

		$url = $this->getPageTitle()->getFullURL();
		$this->getOutput()->redirect( $url );

		return true;
	}

	protected function getGroupName() {
		return 'users';
	}
}
