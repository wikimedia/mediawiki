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

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->disallowUserJs();  # Prevent hijacked user scripts from sniffing passwords etc.

		$user = $this->getUser();
		if ( $user->isAnon() ) {
			$out->showErrorPage( 'prefsnologin', 'prefsnologintext', array( $this->getTitle()->getPrefixedDBkey() ) );
			return;
		}
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		if ( $par == 'reset' ) {
			$this->showResetForm();
			return;
		}

		$out->addModules( 'mediawiki.special.preferences' );

		if ( $this->getRequest()->getCheck( 'success' ) ) {
			$out->wrapWikiMsg(
				"<div class=\"successbox\"><strong>\n$1\n</strong></div><div id=\"mw-pref-clear\"></div>",
				'savedprefs'
			);
		}

		$htmlForm = Preferences::getFormObject( $user, $this->getContext() );
		$htmlForm->setSubmitCallback( array( 'Preferences', 'tryUISubmit' ) );

		$htmlForm->show();
	}

	private function showResetForm() {
		$this->getOutput()->addWikiMsg( 'prefs-reset-intro' );

		$htmlForm = new HTMLForm( array(), $this->getContext(), 'prefs-restore' );

		$htmlForm->setSubmitText( wfMsg( 'restoreprefs' ) );
		$htmlForm->setTitle( $this->getTitle( 'reset' ) );
		$htmlForm->setSubmitCallback( array( $this, 'submitReset' ) );
		$htmlForm->suppressReset();

		$htmlForm->show();
	}

	public function submitReset( $formData ) {
		$user = $this->getUser();
		$user->resetOptions();
		$user->saveSettings();

		$url = SpecialPage::getTitleFor( 'Preferences' )->getFullURL( 'success' );

		$this->getOutput()->redirect( $url );

		return true;
	}
}
