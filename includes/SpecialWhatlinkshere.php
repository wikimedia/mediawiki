<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialWhatlinkshere($par = NULL) {
	global $wgUser, $wgOut, $wgRequest;
	$fname = "wfSpecialWhatlinkshere";

	$target = $wgRequest->getVal( 'target' );
	$limit = $wgRequest->getInt( 'limit', 500 );
	
	if(!empty($par)) {
		$target = $par;
	} else if ( is_null( $target ) ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}

	$nt = Title::newFromURL( $target );
	if( !$nt ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$wgOut->setPagetitle( $nt->getPrefixedText() );
	$wgOut->setSubtitle( wfMsg( "linklistsub" ) );

	$id = $nt->getArticleID();
	$sk = $wgUser->getSkin();
	$isredir = " (" . wfMsg( "isredirect" ) . ")\n";

	$wgOut->addHTML("&lt; ".$sk->makeKnownLinkObj($nt, "", "redirect=no" )."<br />\n");
	$dbr =& wfGetDB( DB_SLAVE );
	extract( $dbr->tableNames( 'page', 'brokenlinks', 'links' ) );

	if ( 0 == $id ) {
		$sql = "SELECT page_id,page_namespace,page_title,page_is_redirect FROM $brokenlinks,$page WHERE bl_to='" .
		  $dbr->strencode( $nt->getPrefixedDBkey() ) . "' AND bl_from=page_id LIMIT $limit";
		$res = $dbr->query( $sql, $fname );

		if ( 0 == $dbr->numRows( $res ) ) {
			$wgOut->addHTML( wfMsg( "nolinkshere" ) );
		} else {
			$wgOut->addHTML( wfMsg( "linkshere" ) );
			$wgOut->addHTML( "\n<ul>" );

			while ( $row = $dbr->fetchObject( $res ) ) {
				$nt = Title::makeTitle( $row->page_namespace, $row->page_title );
				if( !$nt ) {
					continue;
				}
				$link = $sk->makeKnownLinkObj( $nt, "", "redirect=no" );
				$wgOut->addHTML( "<li>{$link}" );

				if ( $row->page_is_redirect ) {
					$wgOut->addHTML( $isredir );
					wfShowIndirectLinks( 1, $row->page_id, $limit );
				}
				$wgOut->addHTML( "</li>\n" );
			}
			$wgOut->addHTML( "</ul>\n" );
			$dbr->freeResult( $res );
		}
	} else {
		wfShowIndirectLinks( 0, $id, $limit );
	}
}

/**
 *
 */
function wfShowIndirectLinks( $level, $lid, $limit ) {
	global $wgOut, $wgUser;
	$fname = "wfShowIndirectLinks";

	$dbr =& wfGetDB( DB_READ );
	extract( $dbr->tableNames( 'links','page' ) );

	$sql = "SELECT page_id,page_namespace,page_title,page_is_redirect FROM $links,$page WHERE l_to={$lid} AND l_from=page_id LIMIT $limit";
	$res = $dbr->query( $sql, $fname );

	if ( 0 == $dbr->numRows( $res ) ) {
		if ( 0 == $level ) {
			$wgOut->addHTML( wfMsg( "nolinkshere" ) );
		}
		return;
	}
	if ( 0 == $level ) {
		$wgOut->addHTML( wfMsg( "linkshere" ) );
	}
	$sk = $wgUser->getSkin();
	$isredir = " (" . wfMsg( "isredirect" ) . ")\n";

	$wgOut->addHTML( "<ul>" );
	while ( $row = $dbr->fetchObject( $res ) ) {
		$nt = Title::makeTitle( $row->page_namespace, $row->page_title );
		if( !$nt ) {
			$wgOut->addHTML( "<!-- bad backlink: " . htmlspecialchars( $row->l_from ) . " -->\n" );
			continue;
		}

		if ( $row->page_is_redirect ) {
			$extra = "redirect=no";
		} else {
			$extra = "";
		}

		$link = $sk->makeKnownLinkObj( $nt, "", $extra );
		$wgOut->addHTML( "<li>{$link}" );

		if ( $row->page_is_redirect ) {
			$wgOut->addHTML( $isredir );
			if ( $level < 2 ) {
				wfShowIndirectLinks( $level + 1, $row->page_id, $limit );
			}
		}
		$wgOut->addHTML( "</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
}

?>
