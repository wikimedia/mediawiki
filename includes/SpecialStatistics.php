<?php
/**
*
* @package MediaWiki
* @subpackage SpecialPage
*/

/**
* constructor
*/
function wfSpecialStatistics() {
	global $wgOut, $wgLang, $wgRequest;
	$fname = 'wfSpecialStatistics';

	$action = $wgRequest->getVal( 'action' );

	$dbr =& wfGetDB( DB_SLAVE );
	extract( $dbr->tableNames( 'page', 'site_stats', 'user', 'user_groups' ) );

	$row = $dbr->selectRow( 'site_stats', '*', false, $fname );
	$views = $row->ss_total_views;
	$edits = $row->ss_total_edits;
	$good = $row->ss_good_articles;
	$images = $row->ss_images;

	# This code is somewhat schema-agnostic, because I'm changing it in a minor release -- TS
	if ( isset( $row->ss_total_pages ) && $row->ss_total_pages == -1 ) {
		# Update schema
		$u = new SiteStatsUpdate( 0, 0, 0 );
		$u->doUpdate();
		$row = $dbr->selectRow( 'site_stats', '*', false, $fname );
	}

	if ( isset( $row->ss_total_pages ) ) {
		$total = $row->ss_total_pages;
	} else {
		$sql = "SELECT COUNT(page_namespace) AS total FROM $page";
		$res = $dbr->query( $sql, $fname );
		$pageRow = $dbr->fetchObject( $res );
		$total = $pageRow->total;
	}

	if ( isset( $row->ss_users ) ) {
		$users = $row->ss_users;
	} else {
		$sql = "SELECT MAX(user_id) AS total FROM $user";
		$res = $dbr->query( $sql, $fname );
		$userRow = $dbr->fetchObject( $res );
		$users = $userRow->total;
	}

	$admins = $dbr->selectField( 'user_groups', 'COUNT(*)', array( 'ug_group' => 'sysop' ), $fname );
	$numJobs = $dbr->selectField( 'job', 'COUNT(*)', '', $fname );

	if ($action == 'raw') {
		$wgOut->disable();
		header( 'Pragma: nocache' );
		echo "total=$total;good=$good;views=$views;edits=$edits;users=$users;admins=$admins;images=$images;jobs=$numJobs\n";
		return;
	} else {
		$text = '==' . wfMsg( 'sitestats' ) . "==\n" ;
		$text .= wfMsg( 'sitestatstext',
			$wgLang->formatNum( $total ),
			$wgLang->formatNum( $good ),
			$wgLang->formatNum( $views ),
			$wgLang->formatNum( $edits ),
			$wgLang->formatNum( sprintf( '%.2f', $total ? $edits / $total : 0 ) ),
			$wgLang->formatNum( sprintf( '%.2f', $edits ? $views / $edits : 0 ) ),
			$wgLang->formatNum( $numJobs ),
			$wgLang->formatNum( $images )
	   	);

		$text .= "\n==" . wfMsg( 'userstats' ) . "==\n";

		$text .= wfMsg( 'userstatstext',
			$wgLang->formatNum( $users ),
			$wgLang->formatNum( $admins ),
			'[[' . wfMsgForContent( 'grouppage-sysop' ) . ']]', # TODO somehow remove, kept for backwards compatibility
			$wgLang->formatNum( sprintf( '%.2f', $admins / $users * 100 ) ),
			User::makeGroupLinkWiki( 'sysop' )
		);

		$wgOut->addWikiText( $text );

		global $wgDisableCounters, $wgMiserMode, $wgUser, $wgLang, $wgContLang;
		if( !$wgDisableCounters && !$wgMiserMode ) {
			$sql = "SELECT page_namespace, page_title, page_counter FROM {$page} WHERE page_is_redirect = 0 AND page_counter > 0 ORDER BY page_counter DESC";
			$sql = $dbr->limitResult($sql, 10, 0);
			$res = $dbr->query( $sql, $fname );
			if( $res ) {
				$wgOut->addHtml( '<h2>' . wfMsgHtml( 'statistics-mostpopular' ) . '</h2>' );
				$skin =& $wgUser->getSkin();
				$wgOut->addHtml( '<ol>' );
				while( $row = $dbr->fetchObject( $res ) ) {
					$link = $skin->makeKnownLinkObj( Title::makeTitleSafe( $row->page_namespace, $row->page_title ) );
					$dirmark = $wgContLang->getDirMark();
					$wgOut->addHtml( '<li>' . $link . $dirmark . ' [' . $wgLang->formatNum( $row->page_counter ) . ']</li>' );
				}
				$wgOut->addHtml( '</ol>' );
				$dbr->freeResult( $res );
			}
		}
		
	}
}
?>
