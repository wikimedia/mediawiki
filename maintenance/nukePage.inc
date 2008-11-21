<?php

/**
 * Support functions for the nukeArticle script
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

require_once( 'purgeOldText.inc' );

function NukePage( $name, $delete = false ) {

	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();
	
	$tbl_pag = $dbw->tableName( 'page' );
	$tbl_rec = $dbw->tableName( 'recentchanges' );
	$tbl_rev = $dbw->tableName( 'revision' );
	
	# Get page ID
	echo( "Searching for \"$name\"..." );
	$title = Title::newFromText( $name );
	if( $title ) {
		$id   = $title->getArticleID();
		$real = $title->getPrefixedText();
		$isGoodArticle = $title->isContentPage();
		echo( "found \"$real\" with ID $id.\n" );
		
		# Get corresponding revisions
		echo( "Searching for revisions..." );
		$res = $dbw->query( "SELECT rev_id FROM $tbl_rev WHERE rev_page = $id" );
		while( $row = $dbw->fetchObject( $res ) ) {
			$revs[] = $row->rev_id;
		}
		$count = count( $revs );
		echo( "found $count.\n" );
		
		# Delete the page record and associated recent changes entries
		if( $delete ) {
			echo( "Deleting page record..." );
			$dbw->query( "DELETE FROM $tbl_pag WHERE page_id = $id" );
			echo( "done.\n" );
			echo( "Cleaning up recent changes..." );
			$dbw->query( "DELETE FROM $tbl_rec WHERE rc_cur_id = $id" );
			echo( "done.\n" );
		}

		$dbw->commit();
		
		# Delete revisions as appropriate
		if( $delete && $count ) {
			echo( "Deleting revisions..." );
			DeleteRevisions( $revs );
			echo( "done.\n" );
			PurgeRedundantText( true );
		}
		
		# Update stats as appropriate
		if ( $delete ) {
			echo( "Updating site stats..." );
			$ga = $isGoodArticle ? -1 : 0; // if it was good, decrement that too
			$stats = new SiteStatsUpdate( 0, -$count, $ga, -1 );
			$stats->doUpdate();
			echo( "done.\n" );
		}
		
		
	} else {
		echo( "not found in database.\n" );
		$dbw->commit();
	}
	
}

function DeleteRevisions( $ids ) {

	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();
	
	$tbl_rev = $dbw->tableName( 'revision' );
	
	$set = implode( ', ', $ids );
	$dbw->query( "DELETE FROM $tbl_rev WHERE rev_id IN ( $set )" );
	
	$dbw->commit();	
	
}

?>