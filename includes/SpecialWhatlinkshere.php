<?php

function wfSpecialWhatlinkshere($par = NULL)
{
	global $wgUser, $wgOut, $target;
	$fname = "wfSpecialWhatlinkshere";

	if($par) {
		$target = $par;
	} else {
		$target = wfCleanQueryVar( $_REQUEST['target'] );
	}
	if ( "" == $target ) {
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

	if ( 0 == $id ) {
		$sql = "SELECT bl_from FROM brokenlinks WHERE bl_to='" .
		  wfStrencode( $nt->getPrefixedDBkey() ) . "' LIMIT 500";
		$res = wfQuery( $sql, DB_READ, $fname );

		if ( 0 == wfNumRows( $res ) ) {
			$wgOut->addHTML( wfMsg( "nolinkshere" ) );
		} else {
			$wgOut->addHTML( wfMsg( "linkshere" ) );
			$wgOut->addHTML( "\n<ul>" );

			while ( $row = wfFetchObject( $res ) ) {
				$lid = $row->bl_from;
				$sql = "SELECT cur_namespace,cur_title,cur_is_redirect " .
				  "FROM cur WHERE cur_id={$lid}";
				$res2 = wfQuery( $sql, DB_READ, $fname );
				$s = wfFetchObject( $res2 );

				$n = Title::makeName( $s->cur_namespace, $s->cur_title );
				$link = $sk->makeKnownLink( $n, "", "redirect=no" );
				$wgOut->addHTML( "<li>{$link}" );

				if ( 1 == $s->cur_is_redirect ) {
					$wgOut->addHTML( $isredir );
					wfShowIndirectLinks( 1, $lid );
				}
				$wgOut->addHTML( "</li>\n" );
			}
			$wgOut->addHTML( "</ul>\n" );
			wfFreeResult( $res );
		}
	} else {
		wfShowIndirectLinks( 0, $id );
	}
}

function wfShowIndirectLinks( $level, $lid )
{
	global $wgOut, $wgUser;
	$fname = "wfShowIndirectLinks";

	$sql = "SELECT l_from FROM links WHERE l_to={$lid} LIMIT 500";
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
		$nt = Title::newFromDBkey( $row->l_from );
		if( !$nt ) {
			$wgOut->addHTML( "<!-- bad backlink: " . htmlspecialchars( $row->l_from ) . " -->\n" );
			continue;
		}
		$ns = $nt->getNamespace();
		$t = wfStrencode( $nt->getDBkey() );

	        # FIXME: this should be in a join above, or cached in the links table
		
		$sql = "SELECT cur_id,cur_is_redirect FROM cur " .
		  "WHERE cur_namespace={$ns} AND cur_title='{$t}'";
		$res2 = wfQuery( $sql, DB_READ, $fname );
		$s = wfFetchObject( $res2 );

		if ( 1 == $s->cur_is_redirect ) {
		    $extra = "redirect=no";
		} else {
		    $extra = "";
		}
	    
	        $link = $sk->makeKnownLink( $row->l_from, "", $extra );
		$wgOut->addHTML( "<li>{$link}" );

		if ( 1 == $s->cur_is_redirect ) {
		    $wgOut->addHTML( $isredir );
		    if ( $level < 2 ) {
			wfShowIndirectLinks( $level + 1, $s->cur_id );
		    }
		}
		$wgOut->addHTML( "</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
}

?>
