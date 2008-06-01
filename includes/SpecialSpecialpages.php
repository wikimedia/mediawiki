<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 *
 */
function wfSpecialSpecialpages() {
	global $wgOut, $wgUser, $wgMessageCache, $wgSortSpecialPages;

	$wgMessageCache->loadAllMessages();

	$wgOut->setRobotpolicy( 'noindex,nofollow' );  # Is this really needed?
	$sk = $wgUser->getSkin();

	$pages = SpecialPage::getUsablePages();
	
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
			$groups[$group][$page->getDescription()] = array( $page->getTitle(), $page->isRestricted() );
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
	$restrictedPages = false;
	foreach ( $groups as $group => $sortedPages ) {
		$wgOut->addHTML( "<h4 class='mw-specialpagesgroup'>".wfMsgHtml("specialpages-group-$group")."</h4>\n" );
		$wgOut->addHTML( "<ul class='mw-specialpages-section'>" );
		foreach ( $sortedPages as $desc => $specialpage ) {
			list( $title, $restricted ) = $specialpage;
			$link = $sk->makeKnownLinkObj( $title , htmlspecialchars( $desc ) );
			if( $restricted ) {
				$wgOut->addHTML( "<li class='mw-specialpages-page mw-specialpagerestricted'>{$link}</li>\n" );
				$restrictedPages = true;
			} else {
				$wgOut->addHTML( "<li class='mw-specialpages-page'>{$link}</li>\n" );
			}
		}
		$wgOut->addHTML( "</ul>\n" );
	}
	$wgOut->addHTML(
		Xml::openElement('div', array( 'class' => 'mw-specialpages-notes' )).
		wfMsgWikiHtml('specialpages-note').
		Xml::closeElement('div')
	);
	
}