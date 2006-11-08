<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialSpecialpages() {
	global $wgOut;

	$wgOut->setRobotpolicy( 'index,nofollow' );

	/** Pages available to all */
	wfSpecialSpecialpages_gen( SpecialPage::getRegularPages(), 'spheading' );

	/** Restricted special pages */
	wfSpecialSpecialpages_gen( SpecialPage::getRestrictedPages(), 'restrictedpheading' );
}

/**
 * sub function generating the list of pages
 * @param $pages the list of pages
 * @param $heading header to be used
 */
function wfSpecialSpecialpages_gen( $pages, $heading ) {
	global $wgOut, $wgSortSpecialPages;

	if( count( $pages ) == 0 ) {
		# Yeah, that was pointless. Thanks for coming.
		return;
	}

	/** Put them into a sortable array */
	$sortedPages = array();
	foreach ( $pages as $name => $page ) {
		if ( $page->isListed() ) {
			$sortedPages[$page->getDescription()] = $page->getTitle();
		}
	}

	/** Sort */
	if ( $wgSortSpecialPages ) {
		ksort( $sortedPages );
	}

	/** Now output the HTML */
	$wgOut->addHTML( '<h2>' . wfMsgHtml( $heading ) . "</h2>\n<ul>" );
	foreach ( $sortedPages as $desc => $title ) {
		$link = Linker::makeKnownLinkObj( $title, $desc );
		$wgOut->addHTML( "<li>{$link}</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
}

?>
