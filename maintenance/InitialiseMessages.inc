<?php

# This script is included from update.php and install.php. Do not run it 
# by itself.

function initialiseMessages( $overwrite = false) {
	global $wgLang, $wgScript, $wgServer, $wgAllMessagesEn;
	global $wgOut, $wgArticle, $wgUser;
	global $wgMessageCache, $wgMemc, $wgDBname, $wgDatabaseMessages;
	$wgMessageCache->disable();

	$fname = "initialiseMessages";
	$ns = NS_MEDIAWIKI;

	$timestamp = wfTimestampNow();
	$invTimestamp = wfInvertTimestamp( $timestamp );
	$navText = wfMsgNoDB( "allmessagestext" );
	$navText .= "

<table border=1 width=100%><tr><td>
  '''Name'''
</td><td>
  '''Default text'''
</td><td>
  '''Current text'''
</td></tr>";
	
	print "Initialising \"MediaWiki\" namespace...\n";
	$sql = "SELECT cur_title FROM cur WHERE cur_namespace=$ns AND cur_title IN(";

	$first = true;
	$sortedArray = $wgAllMessagesEn;
	ksort( $sortedArray );

	foreach ( $sortedArray as $key => $enMsg ) {
		if ( $first ) {
			$first = false;
		} else {
			$sql .= ",";
		}

		$message = wfMsgNoDB( $key );
		$titleObj = Title::newFromText( $key );
		$enctitle = wfStrencode($titleObj->getDBkey());
		$sql .= "'$enctitle'";
	}
	$sql .= ")";
	print "Reading...";
	$res = wfQuery( $sql, DB_READ );
	print ".";
	$row = wfFetchObject( $res );
	print "done\n";

	print "Setting up...";
	$exists = array();
	while ( $row ) {
		$exists[$row->cur_title] = 1;
		$row = wfFetchObject( $res );
	}
	print ".";
	$sql = "INSERT INTO cur (cur_namespace, cur_title, cur_text,
		cur_user_text, cur_timestamp, cur_restrictions,
		cur_is_new, inverse_timestamp, cur_touched) VALUES 	";
	$first = true;
	$mwObj =& MagicWord::get( MAG_MSGNW );
	$msgnw = $mwObj->getSynonym( 0 );
	print "Done\n";
	
	print "Processing...";
	
	foreach ( $sortedArray as $key => $enMsg ) {
		$message = wfMsgNoDB( $key );
		$titleObj = Title::newFromText( $key );
		$title = $titleObj->getDBkey();
		$dbencMsg = wfStrencode( $message );

		$doInsert = true;
		if ( $overwrite ) {
			wfQuery( "DELETE FROM cur WHERE cur_namespace=$ns AND cur_title='$title'", DB_WRITE, $fname );
		} else {	
			if (array_key_exists($title, $exists)) {
				$doInsert = false;
			}
		}
		if ( $doInsert ) {
			if ( $first ) {
				$first = false;
			} else {
				$sql .= ",";
			}
			$sql .=
				"($ns,
				'$title',
				'$dbencMsg',
				'MediaWiki default',
				'$timestamp',
				'sysop',
				1,
				'$invTimestamp',
				'$timestamp')";
		}
		$mw = str_replace( "$1", $key, $msgnw );

		$message = wfEscapeWikiText( $message );
		$navText .= 
"<tr><td>
  [$wgServer$wgScript?title=MediaWiki:$title&action=edit $key]
</td><td>
  $message
</td><td>
  $mw
</td></tr>";
	}
	print "Done\n";
	print "Writing...";

	if ( !$first ) {
		wfQuery( $sql, DB_WRITE, $fname );
	}

	print "Done\n";

	$navText .= "</table>";

	$title = wfMsgNoDB( "allmessages" );
	$titleObj = Title::makeTitle( NS_MEDIAWIKI, $title );
	$wgArticle = new Article( $titleObj );
	$wgOut->disable();
	$wgUser = User::newFromName( 'MediaWiki default' );
	print "Writing to " . $titleObj->getPrefixedDBkey() . "\n";
	if ( $titleObj->getArticleID() ) {
		$wgArticle->updateArticle( $navText, '', 0, 0 );
	} else {
		$wgArticle->insertNewArticle( $navText, '', 0, 0 );
	}
	
	if( $wgDatabaseMessages ) {
		print "Clearing message cache...";
		$wgMemc->delete( "$wgDBname:messages" );
		print "Done.\n";
	}
	
	print "Finished\n";
}

?>