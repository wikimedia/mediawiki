<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Entry point
 * @param string $par An article name ??
 */
function wfSpecialWhatlinkshere($par = NULL) {
	global $wgUser, $wgOut, $wgRequest;
	$fname = 'wfSpecialWhatlinkshere';

	$target = isset($par) ? $par : $wgRequest->getVal( 'target' );
	list( $limit, $offset ) = $wgRequest->getLimitOffset();	

	if (is_null($target)) {
		$wgOut->errorpage( 'notargettitle', 'notargettext' );
		return;
	}

	$nt = Title::newFromURL( $target );
	if( !$nt ) {
		$wgOut->errorpage( 'notargettitle', 'notargettext' );
		return;
	}
	$wgOut->setPagetitle( $nt->getPrefixedText() );
	$wgOut->setSubtitle( wfMsg( 'linklistsub' ) );

	$sk = $wgUser->getSkin();
	$isredir = ' (' . wfMsg( 'isredirect' ) . ")\n";

	$wgOut->addHTML('&lt; '.$sk->makeLinkObj($nt, '', 'redirect=no' )."<br />\n");

	wfShowIndirectLinks( 0, $nt, $limit, $offset );
}

/**
 * @param int   $level
 * @param Title $target
 * @param int   $limit
 * @param int   $offset
 * @access private
 */
function wfShowIndirectLinks( $level, $target, $limit, $offset = 0 ) {
	global $wgOut, $wgUser;
	$fname = 'wfShowIndirectLinks';

	$dbr =& wfGetDB( DB_READ );
	
	// Read one extra row as an at-end check
	$queryLimit = $limit + 1; 
	$limitSql = ( $level == 0 )
		? "$offset,$queryLimit"
		: $queryLimit;

	$res = $dbr->select( array( 'pagelinks', 'page' ),
		array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' ),
		array(
			'pl_from=page_id',
			'pl_namespace' => $target->getNamespace(),
			'pl_title'     => $target->getDbKey() ),
		$fname,
		array( 'LIMIT' => $limitSql ) );

	if ( 0 == $dbr->numRows( $res ) ) {
		if ( 0 == $level ) {
			$wgOut->addWikiText( wfMsg( 'nolinkshere' ) );
		}
		return;
	}
	if ( 0 == $level ) {
		$wgOut->addWikiText( wfMsg( 'linkshere' ) );
	}
	$sk = $wgUser->getSkin();
	$isredir = ' (' . wfMsg( 'isredirect' ) . ")\n";

	if( $dbr->numRows( $res ) == 0 ) {
		return;
	}
	$atend = ( $dbr->numRows( $res ) <= $limit );
	
	if( $level == 0 ) {
		$specialTitle = Title::makeTitle( NS_SPECIAL, 'Whatlinkshere' );
		$prevnext = wfViewPrevNext( $offset, $limit, $specialTitle,
			'target=' . urlencode( $target->getPrefixedDbKey() ),
			$atend );
		$wgOut->addHTML( $prevnext );
	}
	
	$wgOut->addHTML( '<ul>' );
	$linksShown = 0;
	while ( $row = $dbr->fetchObject( $res ) ) {
		if( ++$linksShown > $limit ) {
			// Last row is for checks only; don't display it.
			break;
		}
		
		$nt = Title::makeTitle( $row->page_namespace, $row->page_title );

		if ( $row->page_is_redirect ) {
			$extra = 'redirect=no';
		} else {
			$extra = '';
		}

		$link = $sk->makeKnownLinkObj( $nt, '', $extra );
		$wgOut->addHTML( '<li>'.$link );

		if ( $row->page_is_redirect ) {
			$wgOut->addHTML( $isredir );
			if ( $level < 2 ) {
				wfShowIndirectLinks( $level + 1, $nt, 500 );
			}
		}
		$wgOut->addHTML( "</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
	
	if( $level == 0 ) {
		$wgOut->addHTML( $prevnext );
	}
}

?>
