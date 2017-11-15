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

		$out->addModules( 'mediawiki.special.preferences.ooui' );
		$out->addModuleStyles( 'mediawiki.special.preferences.styles.ooui' );

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

		$htmlForm = $this->getFormObject( $user, $this->getContext() );
		$htmlForm->setSubmitCallback( [ 'Preferences', 'tryUISubmit' ] );

		$prefTabs = [];
		foreach ( $htmlForm->getPreferenceSections() as $key ) {
			$prefTabs[] = [
				'name' => $key,
				'label' => $htmlForm->getLegend( $key ),
			];
		}
		$out->addJsConfigVars( 'wgPreferencesTabs', $prefTabs );

		// TODO: Render fake tabs here to avoid FOUC.
		// $out->addHTML( $fakeTabs );

		$htmlForm->show();
	}

	/**
	 * Get the preferences form to use.
	 * @param User $user The user.
	 * @param IContextSource $context The context.
	 * @return PreferencesForm|HTMLForm
	 */
	protected function getFormObject( $user, IContextSource $context ) {
		return Preferences::getFormObject( $user, $context );
	}

	protected function showResetForm() {
		if ( !$this->getUser()->isAllowed( 'editmyoptions' ) ) {
			throw new PermissionsError( 'editmyoptions' );
		}

		$this->getOutput()->addWikiMsg( 'prefs-reset-intro' );

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle( 'reset' ) ); // Reset subpage
		$htmlForm = HTMLForm::factory( 'ooui', [], $context, 'prefs-restore' );

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

		$url = $this->getPageTitle()->getFullUrlForRedirect();
		$this->getOutput()->redirect( $url );

		return true;
	}

	protected function getGroupName() {
		return 'users';
	}
}
