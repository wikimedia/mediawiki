<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 */
function wfSpecialSpecialpages() {
	global $wgOut, $wgUser, $wgMessageCache;

	$wgMessageCache->loadAllMessages();

	$wgOut->setRobotpolicy( 'index,nofollow' );
	$sk = $wgUser->getSkin();

	/** Pages available to all */
	wfSpecialSpecialpages_gen( SpecialPage::getRegularPages(), 'spheading', $sk, false );

	/** Restricted special pages */
	wfSpecialSpecialpages_gen( SpecialPage::getRestrictedPages(), 'restrictedpheading', $sk, false );
	
	/** Restricted logs */
	wfSpecialSpecialpages_gen( SpecialPage::getRestrictedLogs(), 'restrictedlheading', $sk, true );
}

/**
 * sub function generating the list of pages
 * @param $pages the list of pages
 * @param $heading header to be used
 * @param $sk skin object ???
 * @param $islog, is this for a list of log types?
 */
function wfSpecialSpecialpages_gen( $pages, $heading, $sk, $islog=false ) {
	global $wgOut, $wgUser, $wgSortSpecialPages;

	if( count( $pages ) == 0 ) {
		# Yeah, that was pointless. Thanks for coming.
		return;
	}

	/** Put them into a sortable array */
	$sortedPages = array();
	if( $islog ) {
		$sortedPages = $pages;
	} else {
		foreach ( $pages as $page ) {
			if ( $page->isListed() ) {
				$sortedPages[$page->getDescription()] = $page->getTitle();
			}
		}
	}

	/** Sort */
	if ( $wgSortSpecialPages ) {
		ksort( $sortedPages );
	}

	/** Now output the HTML */
	$wgOut->addHTML( '<h2>' . wfMsgHtml( $heading ) . "</h2>\n<ul>" );
	foreach ( $sortedPages as $desc => $title ) {
		$link = $sk->makeKnownLinkObj( $title , htmlspecialchars( $desc ) );
		$wgOut->addHTML( "<li>{$link}</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
}


