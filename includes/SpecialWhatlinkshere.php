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

	$wgOut->addHTML('&lt; '.$sk->makeKnownLinkObj($nt, '', 'redirect=no' )."<br />\n");

	$specialTitle = Title::makeTitle( NS_SPECIAL, 'Whatlinkshere' );
	$wgOut->addHTML( wfViewPrevNext( $offset, $limit, $specialTitle, 'target=' . urlencode( $target ) ) );

	wfShowIndirectLinks( 0, $nt, $limit, $offset );
	$wgOut->addHTML( wfViewPrevNext( $offset, $limit, $specialTitle, 'target=' . urlencode( $target ) ) );
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
	
	if ( $level == 0 ) {
		$limitSql = $dbr->limitResult( $limit, $offset );
	} else {
		$limitSql = "LIMIT $limit";
	}

	$res = $dbr->select( array( 'pagelinks', 'page' ),
		array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' ),
		array(
			'pl_from=page_id',
			'pl_namespace' => $target->getNamespace(),
			'pl_title'     => $target->getDbKey() ),
		$fname,
		$limitSql );

	if ( 0 == $dbr->numRows( $res ) ) {
		if ( 0 == $level ) {
			$wgOut->addHTML( wfMsg( 'nolinkshere' ) );
		}
		return;
	}
	if ( 0 == $level ) {
		$wgOut->addHTML( wfMsg( 'linkshere' ) );
	}
	$sk = $wgUser->getSkin();
	$isredir = ' (' . wfMsg( 'isredirect' ) . ")\n";

	$wgOut->addHTML( '<ul>' );
	while ( $row = $dbr->fetchObject( $res ) ) {
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
}

?>
