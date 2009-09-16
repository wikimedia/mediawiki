<?php
/**
 * Special page designed for basic benchmarking of
 * MediaWiki since it doesn't really do much.
 *
 * @ingroup SpecialPage
 */
class SpecialBlankpage extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'Blankpage' );
	}
	public function execute( $par ) {
		global $wgOut;
		$this->setHeaders();
		$wgOut->addWikiMsg('intentionallyblankpage');
	}
}
