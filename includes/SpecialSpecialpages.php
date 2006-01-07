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
	global $wgOut, $wgUser, $wgAvailableRights;

	$wgOut->setRobotpolicy( 'index,nofollow' );
	$sk = $wgUser->getSkin();

	# Get listable pages, in a 2-d array with the first dimension being user right
	$pages = SpecialPage::getPages();

	/** Pages available to all */
	wfSpecialSpecialpages_gen($pages[''],'spheading',$sk);

	/** Restricted special pages */
	$rpages = array();
	foreach($wgAvailableRights as $right) {
		/** only show pages a user can access */
		if( $wgUser->isAllowed($right) ) {
			/** some rights might not have any special page associated */
			if(isset($pages[$right])) {
				$rpages = array_merge( $rpages, $pages[$right] );
			}
		}
	}
	wfSpecialSpecialpages_gen( $rpages, 'restrictedpheading', $sk );
}

/**
 * sub function generating the list of pages
 * @param $pages the list of pages
 * @param $heading header to be used
 * @param $sk skin object ???
 */
function wfSpecialSpecialpages_gen($pages,$heading,$sk) {
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
		$link = $sk->makeKnownLinkObj( $title, $desc );
		$wgOut->addHTML( "<li>{$link}</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
}

?>
