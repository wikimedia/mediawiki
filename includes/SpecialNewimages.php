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
	
	$wpIlMatch = $wgRequest->getText( 'wpIlMatch' );
	$dbr =& wfGetDB( DB_SLAVE );
	$sk = $wgUser->getSkin();

	/** If we were clever, we'd use this to cache. */
	$latestTimestamp = wfTimestamp( TS_MW, $dbr->selectField(
		'image', 'img_timestamp',
		'', 'wfSpecialNewimages',
		array( 'ORDER BY' => 'img_timestamp DESC',
		       'LIMIT' => 1 ) ) );
	
	/** Hardcode this for now. */
	$limit = 48;
	
	$where = array();
	if ( !empty( $wpIlMatch ) ) {
		$nt = Title::newFromUrl( $wpIlMatch );
		if($nt ) {
			$m = $dbr->strencode( strtolower( $nt->getDBkey() ) );
			$m = str_replace( '%', "\\%", $m );
			$m = str_replace( '_', "\\_", $m );
			$where[] = "LCASE(img_name) LIKE '%{$m}%'";
		}
	}	
	
	$invertSort = false;
	if( $until = $wgRequest->getVal( 'until' ) ) {
		$where[] = 'img_timestamp < ' . $dbr->timestamp( $until );
	}
	if( $from = $wgRequest->getVal( 'from' ) ) {
		$where[] = 'img_timestamp >= ' . $dbr->timestamp( $from );
		$invertSort = true;
	}
	
	$res = $dbr->select( 'image',
		array( 'img_size', 'img_name', 'img_user', 'img_user_text',
		       'img_description', 'img_timestamp' ),
		$where,
		'wfSpecialNewimages',
		array( 'LIMIT' => $limit + 1,
		       'ORDER BY' => 'img_timestamp' . ( $invertSort ? '' : ' DESC' ) )
	);

	/**
	 * We have to flip things around to get the last N after a certain date
	 */
	$images = array();
	while ( $s = $dbr->fetchObject( $res ) ) {
		if( $invertSort ) {
			array_unshift( $images, $s );
		} else {
			array_push( $images, $s );
		}
	}
	$dbr->freeResult( $res );
	
	$gallery = new ImageGallery();
	$firstTimestamp = null;
	$lastTimestamp = null;
	$shownImages = 0;
	foreach( $images as $s ) {
		if( ++$shownImages > $limit ) {
			# One extra just to test for whether to show a page link;
			# don't actually show it.
			break;
		}
		
		$name = $s->img_name;
		$ut = $s->img_user_text;

		$nt = Title::newFromText( $name, NS_IMAGE );
		$img = Image::newFromTitle( $nt );
		$ul = $sk->makeLink( $wgContLang->getNsText( Namespace::getUser() ) . ":{$ut}", $ut );

		$gallery->add( $img, $ul.'<br /><i>'.$wgLang->timeanddate( $s->img_timestamp, true ).'</i><br />' );
		
		$timestamp = wfTImestamp( TS_MW, $s->img_timestamp );
		if( empty( $firstTimestamp ) ) {
			$firstTimestamp = $timestamp;
		}
		$lastTimestamp = $timestamp;
	}
	
	$bydate = wfMsg( 'bydate' );
	$lt = $wgLang->formatNum( min( $shownImages, $limit ) );
	$text = wfMsg( "imagelisttext",
		"<strong>{$lt}</strong>", "<strong>{$bydate}</strong>" );
	$wgOut->addHTML( "<p>{$text}\n</p>" );

	$cap = wfMsg( 'ilshowmatch' );
	$sub = wfMsg( 'ilsubmit' );
	$titleObj = Title::makeTitle( NS_SPECIAL, 'Newimages' );
	$action = $titleObj->escapeLocalURL(  "limit={$limit}" );

	$wgOut->addHTML( "<form id=\"imagesearch\" method=\"post\" action=\"" .
	  "{$action}\">" .
	  "{$cap}: <input type='text' size='8' name=\"wpIlMatch\" value=\"" .
	  htmlspecialchars( $wpIlMatch ) . "\" /> " .
	  "<input type='submit' name=\"wpIlSubmit\" value=\"{$sub}\" /></form>" );
	$here = $wgContLang->specialPage( 'Newimages' );

	/**
	 * Paging controls...
	 */
	$now = wfTimestamp( TS_MW );
	$date = $wgLang->timeanddate( $now );
	$dateLink = $sk->makeKnownLinkObj( $titleObj, wfMsg( 'rclistfrom', $date ), 'from=' . $now );
	
	$prevLink = wfMsg( 'prevn', $wgLang->formatNum( $limit ) );
	if( $firstTimestamp && $firstTimestamp != $latestTimestamp ) {
		$prevLink = $sk->makeKnownLinkObj( $titleObj, $prevLink, 'from=' . $firstTimestamp );
	}
	
	$nextLink = wfMsg( 'nextn', $wgLang->formatNum( $limit ) );
	if( $shownImages > $limit && $lastTimestamp ) {
		$nextLink = $sk->makeKnownLinkObj( $titleObj, $nextLink, 'until=' . $lastTimestamp );
	}
	
	$prevnext = '<p>' . wfMsg( 'viewprevnext', $prevLink, $nextLink, $dateLink ) . '</p>';
	$wgOut->addHTML( $prevnext );
	
	if( count( $images ) ) {
		$wgOut->addHTML( $gallery->toHTML() );
		$wgOut->addHTML( $prevnext );
	} else {
		$wgOut->addWikiText( wfMsg( 'noimages' ) );
	}
}

?>
