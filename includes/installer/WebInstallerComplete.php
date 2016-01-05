<?php

class WebInstallerComplete extends WebInstallerPage {

	public function execute() {
		// Pop up a dialog box, to make it difficult for the user to forget
		// to download the file
		$lsUrl = $this->getVar( 'wgServer' ) . $this->parent->getURL( array( 'localsettings' => 1 ) );
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
			strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false
		) {
			// JS appears to be the only method that works consistently with IE7+
			$this->addHtml( "\n<script>jQuery( function () { location.href = " .
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
					$this->getVar( 'wgScriptPath' ) . '/index.php',
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


