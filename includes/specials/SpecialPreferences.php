<?php

class SpecialPreferences extends SpecialPage {
	function __construct() {
		parent::__construct( 'Preferences' );
	}
	
	function execute( $par ) {
		global $wgOut, $wgUser, $wgRequest;
		
		$this->setHeaders();
		$this->outputHeader();

		$wgOut->addScriptFile( 'prefs.js' );

		$wgOut->disallowUserJs();  # Prevent hijacked user scripts from sniffing passwords etc.
		
		if ( $wgUser->isAnon() ) {
			$wgOut->showErrorPage( 'prefsnologin', 'prefsnologintext', array($this->getTitle()->getPrefixedDBkey()) );
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

		if ( $wgRequest->getCheck( 'success' ) ) {
			$wgOut->wrapWikiMsg(
				'<div class="successbox"><strong>$1</strong></div>',
				'savedprefs'
			);
		}
		
		$htmlForm = Preferences::getFormObject( $wgUser );

		$htmlForm->show();
	}
	
	function showResetForm() {
		global $wgOut;
		
		$wgOut->addWikiMsg( 'prefs-reset-intro' );
		
		$htmlForm = new HTMLForm( array(), 'prefs-restore' );
		
		$htmlForm->setSubmitText( wfMsg( 'restoreprefs' ) );
		$htmlForm->setTitle( $this->getTitle('reset') );
		$htmlForm->setSubmitCallback( array( __CLASS__, 'submitReset' ) );
		$htmlForm->suppressReset();
		
		$htmlForm->show();
	}
	
	static function submitReset( $formData ) {
		global $wgUser, $wgOut;
		$wgUser->resetOptions();
		
		$url = SpecialPage::getTitleFor( 'Preferences' )->getFullURL( 'success' );
		
		$wgOut->redirect( $url );
		
		return true;
	}
}
