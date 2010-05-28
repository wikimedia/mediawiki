<?php

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
		
		$wgOut->addScriptFile( 'prefs.js' );

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
