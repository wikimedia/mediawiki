<?php
/**
 * Script to initialise the MediaWiki namespace
 *
 * $Id$
 *
 * This script is included from update.php and install.php. Do not run it 
 * by itself.
 *
 * @deprecated
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
function initialiseMessages( $overwrite = false, $messageArray = false ) {
	global $wgLang, $wgScript, $wgServer, $wgAllMessagesEn;
	global $wgOut, $wgArticle, $wgUser;
	global $wgMessageCache, $wgMemc, $wgDBname, $wgUseMemCached;

	# Initialise $wgOut and $wgUser for a command line script
	$wgOut->disable();

	$wgUser = new User;
	$wgUser->setLoaded( true ); # Don't load from DB
	$wgUser->setName( 'MediaWiki default' );
	
	# Don't try to draw messages from the database we're initialising
	$wgMessageCache->disable();

	$fname = 'initialiseMessages';
	$ns = NS_MEDIAWIKI;
	# cur_user_text responsible for the modifications
	# Don't change it unless you're prepared to update the DBs accordingly, otherwise the 
	# default messages won't be overwritte
	$username = 'MediaWiki default';

	
	print "Initialising \"MediaWiki\" namespace...\n";

	
	$dbr =& wfGetDB( DB_SLAVE );
	$dbw =& wfGetDB( DB_MASTER );
	$cur = $dbr->tableName( 'cur' );

	$timestamp = wfTimestampNow();
	$invTimestamp = wfInvertTimestamp( $timestamp );

	$sql = "SELECT cur_title,cur_is_new,cur_user_text FROM $cur WHERE cur_namespace=$ns AND cur_title IN(";

	# Get keys from $wgAllMessagesEn, which is more complete than the local language
	$first = true;
	if ( $messageArray ) {
		$sortedArray = $messageArray;
	} else {
		$sortedArray = $wgAllMessagesEn;
	}
	
	ksort( $sortedArray );

	# SELECT all existing messages
	# Can't afford to be locking all rows for update, this script can take quite a long time to complete
	foreach ( $sortedArray as $key => $enMsg ) {
		if ( $key == '' ) {
			continue; // Skip odd members
		}
		if ( $first ) {
			$first = false;
		} else {
			$sql .= ',';
		}
		$titleObj = Title::newFromText( $key );
		$enctitle = $dbr->strencode($titleObj->getDBkey());
		$sql .= "'$enctitle'";
	}
	$sql .= ')';
	$res = $dbr->query( $sql );
	$row = $dbr->fetchObject( $res );

	# Read the results into an array
	# Decide whether or not each one needs to be overwritten
	$existingTitles = array();
	while ( $row ) {
		if ( $row->cur_user_text != $username ) {
			$existingTitles[$row->cur_title] = 'keep';
		} else {
			$existingTitles[$row->cur_title] = 'chuck';
		}

		$row = $dbr->fetchObject( $res );
	}

	# Insert queries are done in one multi-row insert
	# Here's the start of it:
	$arr = array();
	$talk = $wgLang->getNsText( NS_TALK );
	$mwtalk = $wgLang->getNsText( NS_MEDIAWIKI_TALK );
	
	# Process each message
	foreach ( $sortedArray as $key => $enMsg ) {
		if ( $key == '' ) {
			continue; // Skip odd members
		}
		# Get message text
		if ( $messageArray ) {
			$message = $enMsg;
		} else {
			$message = wfMsgNoDB( $key );
		}
		$titleObj = Title::newFromText( $key );
		$title = $titleObj->getDBkey();
		$dbencMsg = $dbw->strencode( $message );

		# Update messages which already exist
		if ( array_key_exists( $title, $existingTitles ) ) {
			if ( $existingTitles[$title] == 'chuck' || $overwrite) {
				# print "$title\n";
				$mwTitleObj = Title::makeTitle( NS_MEDIAWIKI, $title );
				$article = new Article( $mwTitleObj );
				$article->quickEdit( $message );
			}
			$doInsert = false;
		} else {
			array_push( $arr, array(
				'cur_namespace' => $ns,
				'cur_title' => $title,
				'cur_text' => $dbencMsg,
				'cur_user' => 0,
				'cur_user_text' => $username,
				'cur_timestamp' => $dbw->timestamp( $timestamp ),
				'cur_restrictions' => 'sysop',
				'cur_is_new' => 1,
				'inverse_timestamp' => $invTimestamp,
				'cur_touched' => $dbw->timestamp( $timestamp ) ) );
		}
	}

	$dbw->insertArray( $cur, $arr, $fname );

	# Clear the relevant memcached key
	print 'Clearing message cache...';
	$wgMessageCache->clear();
	print "Done.\n";
}

function loadLanguageFile( $filename )
{
	$contents = file_get_contents( $filename );
	# Remove header line
	$p = strpos( $contents, "\n" ) + 1;
	$contents = substr( $contents, $p );
	# Unserialize
	return unserialize( $contents );
}

function doUpdates() {
	global $wgDeferredUpdateList;
	foreach ( $wgDeferredUpdateList as $up ) { $up->doUpdate(); }
}

?>
