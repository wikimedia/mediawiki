<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialImagelist() {
	global $wgUser, $wgOut, $wgLang, $wgContLang, $wgRequest, $wgMiserMode;

	$sort = $wgRequest->getVal( 'sort' );
	$wpIlMatch = $wgRequest->getText( 'wpIlMatch' );
	$dbr =& wfGetDB( DB_SLAVE );
	$image = $dbr->tableName( 'image' );
	$sql = "SELECT img_size,img_name,img_user,img_user_text," .
	  "img_description,img_timestamp FROM $image";

	if ( !$wgMiserMode && !empty( $wpIlMatch ) ) {
		$nt = Title::newFromUrl( $wpIlMatch );
		if($nt ) {
			$m = $dbr->strencode( strtolower( $nt->getDBkey() ) );
			$m = str_replace( "%", "\\%", $m );
			$m = str_replace( "_", "\\_", $m );
			$sql .= " WHERE LCASE(img_name) LIKE '%{$m}%'";
		}
	}

	if ( "bysize" == $sort ) {
		$sql .= " ORDER BY img_size DESC";
	} else if ( "byname" == $sort ) {
		$sql .= " ORDER BY img_name";
	} else {
		$sort = "bydate";
		$sql .= " ORDER BY img_timestamp DESC";
	}

	list( $limit, $offset ) = wfCheckLimits( 50 );
	$lt = $wgLang->formatNum( "${limit}" );
	$sql .= " LIMIT {$limit}";

	$wgOut->addWikiText( wfMsg( 'imglegend' ) );
	$wgOut->addHTML( wfMsgExt( 'imagelisttext', array('parse'), $lt, wfMsg( $sort ) ) );

	$sk = $wgUser->getSkin();
	$titleObj = Title::makeTitle( NS_SPECIAL, "Imagelist" );
	$action = $titleObj->escapeLocalURL( "sort={$sort}&limit={$limit}" );

	if ( !$wgMiserMode ) {
		$wgOut->addHTML( "<form id=\"imagesearch\" method=\"post\" action=\"" .
		  "{$action}\">" .
			wfElement( 'input',
				array(
					'type' => 'text',
					'size' => '20',
					'name' => 'wpIlMatch',
					'value' => $wpIlMatch, )) .
			wfElement( 'input',
				array(
					'type' => 'submit',
					'name' => 'wpIlSubmit',
					'value' => wfMsg( 'ilsubmit'), )) .
			'</form>' );
	}

	$here = Title::makeTitle( NS_SPECIAL, 'Imagelist' );

	foreach ( array( 'byname', 'bysize', 'bydate') as $sorttype ) {
		$urls = null;
		foreach ( array( 50, 100, 250, 500 ) as $num ) {
			$urls[] = $sk->makeKnownLinkObj( $here, $wgLang->formatNum( $num ),
		  "sort={$sorttype}&limit={$num}&wpIlMatch=" . urlencode( $wpIlMatch ) );
		}
		$sortlinks[] = wfMsgExt(
			'showlast',
			array( 'parseinline', 'replaceafter' ),
			implode($urls, ' | '),
			wfMsgExt( $sorttype, array('escape') )
		);
	}
	$wgOut->addHTML( implode( $sortlinks, "<br />\n") . "\n\n<hr />" );

	// lines
	$wgOut->addHTML( '<p>' );
	$res = $dbr->query( $sql, "wfSpecialImagelist" );

	while ( $s = $dbr->fetchObject( $res ) ) {
		$name = $s->img_name;
		$ut = $s->img_user_text;
		if ( 0 == $s->img_user ) {
			$ul = $ut;
		} else {
			$ul = $sk->makeLinkObj( Title::makeTitle( NS_USER, $ut ), $ut );
		}

		$dirmark = $wgContLang->getDirMark(); // to keep text in correct direction

		$ilink = "<a href=\"" . htmlspecialchars( Image::imageUrl( $name ) ) .
		  "\">" . strtr(htmlspecialchars( $name ), '_', ' ') . "</a>";

		$nb = wfMsgExt( 'nbytes', array( 'parsemag', 'escape'),
			$wgLang->formatNum( $s->img_size ) );

		$desc = $sk->makeKnownLinkObj( Title::makeTitle( NS_IMAGE, $name ),
		  wfMsg( 'imgdesc' ) );

		$date = $wgLang->timeanddate( $s->img_timestamp, true );
		$comment = $sk->commentBlock( $s->img_description );

		$l = "({$desc}) {$dirmark}{$ilink} . . {$dirmark}{$nb} . . {$dirmark}{$ul}".
			" . . {$dirmark}{$date} . . {$dirmark}{$comment}<br />\n";
		$wgOut->addHTML( $l );
	}

	$dbr->freeResult( $res );
	$wgOut->addHTML( '</p>' );
}

?>
