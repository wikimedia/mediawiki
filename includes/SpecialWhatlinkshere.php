<?php

function wfSpecialWhatlinkshere($par = NULL)
{
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
	
	if ( 0 == $id ) {
		$sql = "SELECT cur_id,cur_namespace,cur_title,cur_is_redirect FROM brokenlinks,cur WHERE bl_to='" .
		  wfStrencode( $nt->getPrefixedDBkey() ) . "' AND bl_from=cur_id LIMIT $limit";
		$res = wfQuery( $sql, DB_READ, $fname );

		if ( 0 == wfNumRows( $res ) ) {
			$wgOut->addHTML( wfMsg( "nolinkshere" ) );
		} else {
			$wgOut->addHTML( wfMsg( "linkshere" ) );
			$wgOut->addHTML( "\n<ul>" );

			while ( $row = wfFetchObject( $res ) ) {
				$nt = Title::makeTitle( $row->cur_namespace, $row->cur_title );
				if( !$nt ) {
					continue;
				}
				$link = $sk->makeKnownLinkObj( $nt, "", "redirect=no" );
				$wgOut->addHTML( "<li>{$link}" );

				if ( $row->cur_is_redirect ) {
					$wgOut->addHTML( $isredir );
					wfShowIndirectLinks( 1, $row->cur_id, $limit );
				}
				$wgOut->addHTML( "</li>\n" );
			}
			$wgOut->addHTML( "</ul>\n" );
			wfFreeResult( $res );
		}
	} else {
		wfShowIndirectLinks( 0, $id, $limit );
	}
}

function wfShowIndirectLinks( $level, $lid, $limit )
{
	global $wgOut, $wgUser;
	$fname = "wfShowIndirectLinks";

	$sql = "SELECT cur_id,cur_namespace,cur_title,cur_is_redirect FROM links,cur WHERE l_to={$lid} AND l_from=cur_id LIMIT $limit";
	$res = wfQuery( $sql, DB_READ, $fname );

	if ( 0 == wfNumRows( $res ) ) {
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
	while ( $row = wfFetchObject( $res ) ) {
		$nt = Title::makeTitle( $row->cur_namespace, $row->cur_title );
		if( !$nt ) {
			$wgOut->addHTML( "<!-- bad backlink: " . htmlspecialchars( $row->l_from ) . " -->\n" );
			continue;
		}
		
		if ( $row->cur_is_redirect ) {
		    $extra = "redirect=no";
		} else {
		    $extra = "";
		}
	    
		$link = $sk->makeKnownLinkObj( $nt, "", $extra );
		$wgOut->addHTML( "<li>{$link}" );

		if ( $row->cur_is_redirect ) {
		    $wgOut->addHTML( $isredir );
		    if ( $level < 2 ) {
			wfShowIndirectLinks( $level + 1, $row->cur_id, $limit );
		    }
		}
		$wgOut->addHTML( "</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
}

?>
