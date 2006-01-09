<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */


require_once ( "SpecialFilelist.php" ) ;

function wfSpecialImagelist( $par, $specialPage ) {
	wfSpecialFilelist ( $par , $specialPage , false ) ;
}

# _____________________________________________
# The rest of this file is obsolete
# _____________________________________________


/**
 *
 */
function OLD_wfSpecialImagelist() {
	global $wgUser, $wgOut, $wgLang, $wgRequest, $wgMiserMode;

	$sort = $wgRequest->getVal( 'sort' );
	$wpIlMatch = $wgRequest->getText( 'wpIlMatch' );
	$dbr =& wfGetDB( DB_SLAVE );
	$image = $dbr->tableName( 'image' );
	$sql = "SELECT img_size,img_name,img_user,img_user_text," .
	  "img_description,img_timestamp FROM $image";

	$byname = wfMsg( "byname" );
	$bydate = wfMsg( "bydate" );
	$bysize = wfMsg( "bysize" );

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
		$st = $bysize;
	} else if ( "byname" == $sort ) {
		$sql .= " ORDER BY img_name";
		$st = $byname;
	} else {
		$sort = "bydate";
		$sql .= " ORDER BY img_timestamp DESC";
		$st = $bydate;
	}
	list( $limit, $offset ) = wfCheckLimits( 50 );
	if ( 0 == $limit ) {
		$lt = wfMsg( 'imagelistall' );
	} else {
		$lt = $wgLang->formatNum( "${limit}" );
		$sql .= " LIMIT {$limit}";
	}
	$wgOut->addHTML( "<p>" . wfMsg( "imglegend" ) . "</p>\n" );

	$text = wfMsg( "imagelisttext",
		"<strong>{$lt}</strong>", "<strong>{$st}</strong>" );
	$wgOut->addHTML( "<p>{$text}\n</p>" );

	$sk = $wgUser->getSkin();
	$sub = wfMsg( "ilsubmit" );
	$titleObj = Title::makeTitle( NS_SPECIAL, "Imagelist" );
	$action = $titleObj->escapeLocalURL(  "sort={$sort}&limit={$limit}" );

	if ( !$wgMiserMode ) {
		$wgOut->addHTML( "<form id=\"imagesearch\" method=\"post\" action=\"" .
		  "{$action}\">" .
		  "<input type='text' size='20' name=\"wpIlMatch\" value=\"" .
		  htmlspecialchars( $wpIlMatch ) . "\" /> " .
		  "<input type='submit' name=\"wpIlSubmit\" value=\"{$sub}\" /></form>" );
	}
	$nums = array( 50, 100, 250, 500 );
	$here = Title::makeTitle( NS_SPECIAL, 'Imagelist' );

	$fill = "";
	$first = true;
	foreach ( $nums as $num ) {
		if ( ! $first ) { $fill .= " | "; }
		$first = false;

		$fill .= $sk->makeKnownLinkObj( $here, $wgLang->formatNum( $num ),
		  "sort=byname&limit={$num}&wpIlMatch=" . urlencode( $wpIlMatch ) );
	}
	$text = wfMsg( "showlast", $fill, $byname );
	$wgOut->addHTML( "<p>{$text}<br />\n" );

	$fill = "";
	$first = true;
	foreach ( $nums as $num ) {
		if ( ! $first ) { $fill .= " | "; }
		$first = false;

		$fill .= $sk->makeKnownLinkObj( $here, $wgLang->formatNum( $num ),
		  "sort=bysize&limit={$num}&wpIlMatch=" . urlencode( $wpIlMatch ) );
	}
	$text = wfMsg( "showlast", $fill, $bysize );
	$wgOut->addHTML( "{$text}<br />\n" );

	$fill = "";
	$first = true;
	foreach ( $nums as $num ) {
		if ( ! $first ) { $fill .= " | "; }
		$first = false;

		$fill .= $sk->makeKnownLinkObj( $here, $wgLang->formatNum( $num ),
		  "sort=bydate&limit={$num}&wpIlMatch=" . urlencode( $wpIlMatch ) );
	}
	$text = wfMsg( "showlast", $fill, $bydate );
	$wgOut->addHTML( "{$text}</p>\n<p>" );

	$res = $dbr->query( $sql, "wfSpecialImagelist" );
	while ( $s = $dbr->fetchObject( $res ) ) {
		$name = $s->img_name;
		$ut = $s->img_user_text;
		if ( 0 == $s->img_user ) {
			$ul = $ut;
		} else {
			$ul = $sk->makeLinkObj( Title::makeTitle( NS_USER, $ut ), $ut );
		}

		$ilink = "<a href=\"" . htmlspecialchars( Image::imageUrl( $name ) ) .
		  "\">" . strtr(htmlspecialchars( $name ), '_', ' ') . "</a>";

		$nb = wfMsg( "nbytes", $wgLang->formatNum( $s->img_size ) );
		$l = "(" .
		  $sk->makeKnownLinkObj( Title::makeTitle( NS_IMAGE, $name ),
		  wfMsg( "imgdesc" ) ) .
		  ") {$ilink} . . {$nb} . . {$ul} . . " .
		  $wgLang->timeanddate( $s->img_timestamp, true );

		$l .= $sk->commentBlock( $s->img_description );
		$wgOut->addHTML( "{$l}<br />\n" );
	}
	$wgOut->addHTML( "</p>" );
	$dbr->freeResult( $res );
}

?>
