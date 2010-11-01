<?php
/**
 * Implements Special:Newimages
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * @todo FIXME: this code is crap, should use Pager and Database::select().
 */
function wfSpecialNewimages( $par, $specialPage ) {
	global $wgUser, $wgOut, $wgLang, $wgRequest, $wgMiserMode;

	$wpIlMatch = $wgRequest->getText( 'wpIlMatch' );
	$dbr = wfGetDB( DB_SLAVE );
	$sk = $wgUser->getSkin();
	$shownav = !$specialPage->including();
	$hidebots = $wgRequest->getBool( 'hidebots' , 1 );

	$hidebotsql = '';
	if ( $hidebots ) {
		# Make a list of group names which have the 'bot' flag set.
		$botconds = array();
		foreach ( User::getGroupsWithPermission('bot') as $groupname ) {
			$botconds[] = 'ug_group = ' . $dbr->addQuotes( $groupname );
		}

		# If not bot groups, do not set $hidebotsql
		if ( $botconds ) {
			$isbotmember = $dbr->makeList( $botconds, LIST_OR );

			# This join, in conjunction with WHERE ug_group IS NULL, returns
			# only those rows from IMAGE where the uploading user is not a mem-
			# ber of a group which has the 'bot' permission set.
			$ug = $dbr->tableName( 'user_groups' );
			$hidebotsql = " LEFT JOIN $ug ON img_user=ug_user AND ($isbotmember)";
		}
	}

	$image = $dbr->tableName( 'image' );

	$sql = "SELECT img_timestamp from $image";
	if ($hidebotsql) {
		$sql .= "$hidebotsql WHERE ug_group IS NULL";
	}
	$sql .= ' ORDER BY img_timestamp DESC';
	$sql = $dbr->limitResult($sql, 1, false);
	$res = $dbr->query( $sql, __FUNCTION__ );
	$row = $dbr->fetchRow( $res );
	if( $row !== false ) {
		$ts = $row[0];
	} else {
		$ts = false;
	}

	# If we were clever, we'd use this to cache.
	$latestTimestamp = wfTimestamp( TS_MW, $ts );

	# Hardcode this for now.
	$limit = 48;
	$parval = intval( $par );
	if ( $parval ) {
		if ( $parval <= $limit && $parval > 0 ) {
			$limit = $parval;
		}
	}

	$where = array();
	$searchpar = array();
	if ( $wpIlMatch != '' && !$wgMiserMode) {
		$nt = Title::newFromURL( $wpIlMatch );
		if( $nt ) {
			$where[] = 'LOWER(img_name) ' .  $dbr->buildLike( $dbr->anyString(), strtolower( $nt->getDBkey() ), $dbr->anyString() );
			$searchpar['wpIlMatch'] = $wpIlMatch;
		}
	}

	$invertSort = false;
	$until = $wgRequest->getVal( 'until' );
	if( $until ) {
		$where[] = "img_timestamp < '" . $dbr->timestamp( $until ) . "'";
	}
	$from = $wgRequest->getVal( 'from' );
	if( $from ) {
		$where[] = "img_timestamp >= '" . $dbr->timestamp( $from ) . "'";
		$invertSort = true;
	}
	$sql = 'SELECT img_size, img_name, img_user, img_user_text,'.
	     "img_description,img_timestamp FROM $image";

	if( $hidebotsql ) {
		$sql .= $hidebotsql;
		$where[] = 'ug_group IS NULL';
	}
	if( count( $where ) ) {
		$sql .= ' WHERE ' . $dbr->makeList( $where, LIST_AND );
	}
	$sql.=' ORDER BY img_timestamp '. ( $invertSort ? '' : ' DESC' );
	$sql = $dbr->limitResult($sql, ( $limit + 1 ), false);
	$res = $dbr->query( $sql, __FUNCTION__ );

	/**
	 * We have to flip things around to get the last N after a certain date
	 */
	$images = array();
	foreach ( $res as $s ) {
		if( $invertSort ) {
			array_unshift( $images, $s );
		} else {
			array_push( $images, $s );
		}
	}

	$gallery = new ImageGallery();
	$firstTimestamp = null;
	$lastTimestamp = null;
	$shownImages = 0;
	foreach( $images as $s ) {
		$shownImages++;
		if( $shownImages > $limit ) {
			# One extra just to test for whether to show a page link;
			# don't actually show it.
			break;
		}

		$name = $s->img_name;
		$ut = $s->img_user_text;

		$nt = Title::newFromText( $name, NS_FILE );
		$ul = $sk->link( Title::makeTitle( NS_USER, $ut ), $ut );

		$gallery->add( $nt, "$ul<br />\n<i>".htmlspecialchars($wgLang->timeanddate( $s->img_timestamp, true ))."</i><br />\n" );

		$timestamp = wfTimestamp( TS_MW, $s->img_timestamp );
		if( empty( $firstTimestamp ) ) {
			$firstTimestamp = $timestamp;
		}
		$lastTimestamp = $timestamp;
	}

	$titleObj = SpecialPage::getTitleFor( 'Newimages' );
	$action = $titleObj->getLocalURL( $hidebots ? '' : 'hidebots=0' );
	if ( $shownav && !$wgMiserMode ) {
		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'action' => $action, 'method' => 'post', 'id' => 'imagesearch' ) ) .
			Xml::fieldset( wfMsg( 'newimages-legend' ) ) .
			Xml::inputLabel( wfMsg( 'newimages-label' ), 'wpIlMatch', 'wpIlMatch', 20, $wpIlMatch ) . ' ' .
			Xml::submitButton( wfMsg( 'ilsubmit' ), array( 'name' => 'wpIlSubmit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		 );
	}

	$bydate = wfMsg( 'bydate' );
	$lt = $wgLang->formatNum( min( $shownImages, $limit ) );
	if ( $shownav ) {
		$text = wfMsgExt( 'imagelisttext', array('parse'), $lt, $bydate );
		$wgOut->addHTML( $text . "\n" );
	}

	/**
	 * Paging controls...
	 */

	# If we change bot visibility, this needs to be carried along.
	if( !$hidebots ) {
		$botpar = array( 'hidebots' => 0 );
	} else {
		$botpar = array();
	}
	$now = wfTimestampNow();
	$d = $wgLang->date( $now, true );
	$t = $wgLang->time( $now, true );
	$query = array_merge(
		array( 'from' => $now ),
		$botpar,
		$searchpar
	);

	$dateLink = $sk->linkKnown(
		$titleObj,
		htmlspecialchars( wfMsg( 'sp-newimages-showfrom', $d, $t ) ),
		array(),
		$query
	);

	$query = array_merge(
		array( 'hidebots' => ( $hidebots ? 0 : 1 ) ),
		$searchpar
	);

	$showhide = $hidebots ? wfMsg( 'show' ) : wfMsg( 'hide' );

	$botLink = $sk->linkKnown(
		$titleObj,
		htmlspecialchars( wfMsg( 'showhidebots', $showhide ) ),
		array(),
		$query
	);

	$opts = array( 'parsemag', 'escapenoentities' );
	$prevLink = wfMsgExt( 'pager-newer-n', $opts, $wgLang->formatNum( $limit ) );
	if( $firstTimestamp && $firstTimestamp != $latestTimestamp ) {
		$query = array_merge(
			array( 'from' => $firstTimestamp ),
			$botpar,
			$searchpar
		);

		$prevLink = $sk->linkKnown(
			$titleObj,
			$prevLink,
			array(),
			$query
		);
	}

	$nextLink = wfMsgExt( 'pager-older-n', $opts, $wgLang->formatNum( $limit ) );
	if( $invertSort || ( $shownImages > $limit && $lastTimestamp ) ) {
		$query = array_merge(
			array( 'until' => ( $lastTimestamp ? $lastTimestamp : "" ) ),
			$botpar,
			$searchpar
		);

		$nextLink = $sk->linkKnown(
			$titleObj,
			$nextLink,
			array(),
			$query
		);

	}

	$prevnext = '<p>' . $botLink . ' '. wfMsgHtml( 'viewprevnext', $prevLink, $nextLink, $dateLink ) .'</p>';

	if ($shownav)
		$wgOut->addHTML( $prevnext );

	if( count( $images ) ) {
		$wgOut->addHTML( $gallery->toHTML() );
		if ($shownav)
			$wgOut->addHTML( $prevnext );
	} else {
		$wgOut->addWikiMsg( 'noimages' );
	}
}
