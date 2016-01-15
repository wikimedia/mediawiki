<?php
if (!defined('MEDIAWIKI')) die();

class SpecialRandomrootpage extends RandomPage {

	public function __construct() {
		parent::__construct( 'Randomrootpage' );
		$dbr = wfGetDB( DB_SLAVE );
		$this->extra[] = 'page_title NOT ' . $dbr->buildLike( $dbr->anyString(), '/', $dbr->anyString() );
	}

	// Don't select redirects
	public function isRedirect(){
		return false;
	}
}
