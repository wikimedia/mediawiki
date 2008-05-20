<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 *
 */
function wfSpecialSpecialpages() {
	global $wgOut, $wgUser, $wgMessageCache;

	$wgMessageCache->loadAllMessages();

	$wgOut->setRobotpolicy( 'noindex,nofollow' );  # Is this really needed?
	$sk = $wgUser->getSkin();

	/** Pages available to all */
	wfSpecialSpecialpages_gen( SpecialPage::getRegularPages(), 'spheading', $sk );

	/** Restricted special pages */
	wfSpecialSpecialpages_gen( SpecialPage::getRestrictedPages(), 'restrictedpheading', $sk );
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
	$groups = array();
	foreach ( $pages as $page ) {
		if ( $page->isListed() ) {
			$group = SpecialPage::getGroup( $page );
			if( !isset($groups[$group]) ) {
				$groups[$group] = array();
			}
			$groups[$group][$page->getDescription()] = $page->getTitle();
		}
	}

	/** Sort */
	if ( $wgSortSpecialPages ) {
		foreach( $groups as $group => $sortedPages ) {
			ksort( $groups[$group] );
		}
	}

	/** Always move "other" to end */
	if( array_key_exists('other',$groups) ) {
		$other = $groups['other'];
		unset( $groups['other'] );
		$groups['other'] = $other;
	}

	/** Now output the HTML */
	$wgOut->addHTML( '<h2>' . wfMsgHtml( $heading ) . "</h2>\n" );
	foreach ( $groups as $group => $sortedPages ) {
		$middle = ceil( count($sortedPages)/2 );
		$total = count($sortedPages);
		$count = 0;

		$wgOut->addHTML( "<h4 class='mw-specialpagesgroup'>".wfMsgHtml("specialpages-group-$group")."</h4>\n" );
		$wgOut->addHTML( "<table style='width: 100%;' class='mw-specialpages-table'><tr>" );
		$wgOut->addHTML( "<td width='30%' valign='top'><ul>\n" );
		foreach ( $sortedPages as $desc => $title ) {
			$link = $sk->makeKnownLinkObj( $title , htmlspecialchars( $desc ) );
			$wgOut->addHTML( "<li>{$link}</li>\n" );

			# Slit up the larger groups
			$count++;
			if( $total > 3 && $count == $middle ) {
				$wgOut->addHTML( "</ul></td><td width='10%'></td><td width='30%' valign='top'><ul>" );
			}
		}
		$wgOut->addHTML( "</ul></td><td width='30%' valign='top'></td></tr></table>\n" );
	}
}
