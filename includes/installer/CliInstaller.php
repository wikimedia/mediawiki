<?php

function wfInstallerConfig() {
	// Don't access the database
	$GLOBALS['wgUseDatabaseMessages'] = false;
	// Debug-friendly
	$GLOBALS['wgShowExceptionDetails'] = true;
	// Don't break forms
	$GLOBALS['wgExternalLinkTarget'] = '_blank';

	// Extended debugging. Maybe disable before release?
	$GLOBALS['wgShowSQLErrors'] = true;
	$GLOBALS['wgShowDBErrorBacktrace'] = true;
}

class CliInstaller extends Installer {

	/** Constructor */
	function __construct( $siteName, $admin = null, $option = array()) {
		parent::__construct();

		if ( isset( $option['lang'] ) ) {
			global $wgLang, $wgContLang, $wgLanguageCode;
			$this->setVar( '_UserLang', $option['lang'] );
			$wgContLang = Language::factory( $option['lang'] );
			$wgLang = Language::factory( $option['lang'] );
			$wgLanguageCode = $option['lang'];
		}

		$this->setVar( 'wgSitename', $siteName );
		if ( $admin ) {
			$this->setVar( '_AdminName', $admin );
		} else {
			$this->setVar( '_AdminName', wfMsgForContent( 'config-admin-default-username' ) );
		}
	}

	/**
	 * Main entry point.
	 */
	function execute( ) {
		var_dump($this->getVar('_AdminName'));
	}

	function showMessage( $msg /*, ... */ ) {
		echo "Message: $msg\n";
	}

	function showStatusError( $status ) {
		echo "Error: $status\n";
	}
}
