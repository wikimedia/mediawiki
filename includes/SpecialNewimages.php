<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

require_once( 'ImageGallery.php' );

/**
 *
 */
function wfSpecialNewimages() {
	global $wgUser, $wgOut, $wgLang, $wgContLang, $wgRequest;
	
	$sort = $wgRequest->getVal( 'sort' );
	$wpIlMatch = $wgRequest->getText( 'wpIlMatch' );
	$dbr =& wfGetDB( DB_SLAVE );
	$image = $dbr->tableName( 'image' );
	$sql = "SELECT img_size,img_name,img_user,img_user_text," .
	  "img_description,img_timestamp FROM $image";

	$bydate = wfMsg( 'bydate' );

	if ( !empty( $wpIlMatch ) ) {
		$nt = Title::newFromUrl( $wpIlMatch );
		if($nt ) {
			$m = $dbr->strencode( strtolower( $nt->getDBkey() ) );
			$m = str_replace( '%', "\\%", $m );
			$m = str_replace( '_', "\\_", $m );
			$sql .= " WHERE LCASE(img_name) LIKE '%{$m}%'";
		}
	}
	$sort = 'bydate';
	$sql .= ' ORDER BY img_timestamp DESC';
	$st = $bydate;
	
	list( $limit, $offset ) = wfCheckLimits( 50 );
	if ( 0 == $limit ) {
		$lt = wfMsg( 'all' );
	} else {
		$lt = $wgLang->formatNum( "${limit}" );
		$sql .= " LIMIT {$limit}";
	}
	$wgOut->addHTML( "<p>" . wfMsg( "imglegend" ) . "</p>\n" );

	$text = wfMsg( "imagelisttext",
		"<strong>{$lt}</strong>", "<strong>{$st}</strong>" );
	$wgOut->addHTML( "<p>{$text}\n</p>" );

	$sk = $wgUser->getSkin();
	$cap = wfMsg( 'ilshowmatch' );
	$sub = wfMsg( 'ilsubmit' );
	$titleObj = Title::makeTitle( NS_SPECIAL, 'Newimages' );
	$action = $titleObj->escapeLocalURL(  "sort={$sort}&limit={$limit}" );

	$wgOut->addHTML( "<form id=\"imagesearch\" method=\"post\" action=\"" .
	  "{$action}\">" .
	  "{$cap}: <input type='text' size='8' name=\"wpIlMatch\" value=\"" .
	  htmlspecialchars( $wpIlMatch ) . "\" /> " .
	  "<input type='submit' name=\"wpIlSubmit\" value=\"{$sub}\" /></form>" );
	$nums = array( 50, 100, 250, 500 );
	$here = $wgContLang->specialPage( 'Imagelist' );

	$fill = '';
	$first = true;
	foreach ( $nums as $num ) {
		if ( ! $first ) { $fill .= ' | '; }
		$first = false;

		$fill .= $sk->makeKnownLink( $here, $wgLang->formatNum( $num ),
		  "sort=bydate&limit={$num}&wpIlMatch=" . urlencode( $wpIlMatch ) );
	}
	$text = wfMsg( 'showlast', $fill, $bydate );
	$wgOut->addHTML( $text."\n" );

	$i=0;
	$res = $dbr->query( $sql, 'wfSpecialImagelist' );

	$gallery = new ImageGallery();

	while ( $s = $dbr->fetchObject( $res ) ) {
		$name = $s->img_name;
		$ut = $s->img_user_text;

		$nt = Title::newFromText( $name, NS_IMAGE );
		$img = Image::newFromTitle( $nt );
		$ul = $sk->makeLink( $wgContLang->getNsText( Namespace::getUser() ) . ":{$ut}", $ut );

		$gallery->add( $img, $ul.'<br /><i>'.$wgLang->timeanddate( $s->img_timestamp, true ).'</i><br />' );
		$i++;
	}
	$wgOut->addHTML( $gallery->toHTML() );
	$dbr->freeResult( $res );
}

?>
