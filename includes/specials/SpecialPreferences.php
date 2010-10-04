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

	function execute( $par ) {
		global $wgOut, $wgUser, $wgRequest;

		$this->setHeaders();
		$this->outputHeader();
		$wgOut->disallowUserJs();  # Prevent hijacked user scripts from sniffing passwords etc.

		if ( $wgUser->isAnon() ) {
			$wgOut->showErrorPage( 'prefsnologin', 'prefsnologintext', array( $this->getTitle()->getPrefixedDBkey() ) );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		if ( $par == 'reset' ) {
			$this->showResetForm();
			return;
		}

		$wgOut->addModules( 'mediawiki.legacy.prefs' );
		$wgOut->addModuleScripts( 'mediawiki.specials.preferences' );

		if ( $wgRequest->getCheck( 'success' ) ) {
			$wgOut->wrapWikiMsg(
				"<div class=\"successbox\"><strong>\n$1\n</strong></div><div id=\"mw-pref-clear\"></div>",
				'savedprefs'
			);
		}
		
		if ( $wgRequest->getCheck( 'eauth' ) ) {
			$wgOut->wrapWikiMsg( "<div class='error' style='clear: both;'>\n$1\n</div>",
									'eauthentsent', $wgUser->getName() );
		}

		$htmlForm = Preferences::getFormObject( $wgUser );
		$htmlForm->setSubmitCallback( array( 'Preferences', 'tryUISubmit' ) );

		$htmlForm->show();
	}

	function showResetForm() {
		global $wgOut;

		$wgOut->addWikiMsg( 'prefs-reset-intro' );

		$htmlForm = new HTMLForm( array(), 'prefs-restore' );

		$htmlForm->setSubmitText( wfMsg( 'restoreprefs' ) );
		$htmlForm->setTitle( $this->getTitle( 'reset' ) );
		$htmlForm->setSubmitCallback( array( __CLASS__, 'submitReset' ) );
		$htmlForm->suppressReset();

		$htmlForm->show();
	}

	static function submitReset( $formData ) {
		global $wgUser, $wgOut;
		$wgUser->resetOptions();
		$wgUser->saveSettings();

		$url = SpecialPage::getTitleFor( 'Preferences' )->getFullURL( 'success' );

		$wgOut->redirect( $url );

		return true;
	}
}
