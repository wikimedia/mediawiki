<?php

class SpecialStyleGuide extends UnlistedSpecialPage {
	protected $modules = array();

	public function __construct() {
		parent::__construct( 'StyleGuide' );
	}

	public function execute( $pageName = '' ) {

		$this->setHeaders();
		$out = $this->getOutput();
		// FIXME: i18n
		$out->setPageTitle( 'MediaWiki UI Living Style Guide' );
		$out->addModuleStyles( 'mediawiki.special.styleGuide' );
		if ( !$pageName ) {
			$pageName = 'index';
		}

		$localPath = dirname( __FILE__ ) . "/../../resources/mediawiki.ui/docs/fragments/$pageName.html";
		if ( file_exists( $localPath ) ) {
			$contents = file_get_contents( $localPath );
			$out->addHtml( $contents );
		} else {
			$out->showFileNotFoundError( "$pageName.html" );
		}
	}
}
