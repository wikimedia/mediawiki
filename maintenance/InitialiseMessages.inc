<?php
# Script to initialise the MediaWiki namespace

# This script is included from update.php and install.php. Do not run it 
# by itself.

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

	$timestamp = wfTimestampNow();
	$invTimestamp = wfInvertTimestamp( $timestamp );
	$navText = '{{int:allmessagestext}}';
	$navText .= "

<table border=1 width=100%><tr><td>
'''Name'''
</td><td>
'''Default text'''
</td><td>
'''Current text'''
</td></tr>";
	
	print "Initialising \"MediaWiki\" namespace...\n";
	$sql = "SELECT cur_title,cur_is_new,cur_user_text FROM cur WHERE cur_namespace=$ns AND cur_title IN(";

	# Get keys from $wgAllMessagesEn, which is more complete than the local language
	$first = true;
	if ( $messageArray ) {
		$sortedArray = $messageArray;
	} else {
		$sortedArray = $wgAllMessagesEn;
	}
	
	ksort( $sortedArray );
	
	# SELECT all existing messages
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
		$enctitle = wfStrencode($titleObj->getDBkey());
		$sql .= "'$enctitle'";
	}
	$sql .= ')';
	$res = wfQuery( $sql, DB_READ );
	$row = wfFetchObject( $res );

	# Read the results into an array
	# Decide whether or not each one needs to be overwritten
	$existingTitles = array();
	while ( $row ) {
		if ( $row->cur_user_text != $username ) {
			$existingTitles[$row->cur_title] = 'keep';
		} else {
			$existingTitles[$row->cur_title] = 'chuck';
		}

		$row = wfFetchObject( $res );
	}

	# Insert queries are done in one multi-row insert
	# Here's the start of it:
	$sql = "INSERT INTO cur (cur_namespace, cur_title, cur_text,
		cur_user_text, cur_timestamp, cur_restrictions,
		cur_is_new, inverse_timestamp, cur_touched) VALUES 	";
	$first = true;
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
		$dbencMsg = wfStrencode( $message );

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
			# Queue for insertion
			if ( $first ) {
				$first = false;
			} else {
				$sql .= ',';
			}
			$sql .=
			  "($ns,
			  '$title',
			  '$dbencMsg',
			  '$username',
			  '$timestamp',
			  'sysop',
			  1,
			  '$invTimestamp',
			  '$timestamp')";
		}
		
		# Make table row for navigation page
		$message = wfEscapeWikiText( $message );
		$navText .= 
"<tr><td>
[$wgServer$wgScript?title=MediaWiki:$title&action=edit $key]<br>
[[$mwtalk:$title|$talk]]
</td><td>
$message
</td><td>
{{int:$title}}
</td></tr>";
	}

	# Perform the insert query
	if ( !$first ) {
		wfQuery( $sql, DB_WRITE, $fname );
	}

	# Write the navigation page

	$navText .= '</table>';
	$title = wfMsgNoDB( 'allmessages' );
	$titleObj = Title::makeTitle( NS_WIKIPEDIA, $title );
	$wgArticle = new Article( $titleObj );
	$wgOut->disable();
	$wgUser = User::newFromName( 'MediaWiki default' );
	if ( $titleObj->getArticleID() ) {
		$wgArticle->updateArticle( $navText, '', 0, 0 );
	} else {
		$wgArticle->insertNewArticle( $navText, '', 0, 0 );
	}
	
	# Clear the relevant memcached key
	if( $wgUseMemCached ) {
		print 'Clearing message cache...';
		$wgMemc->delete( $wgDBname.':messages' );
		print "Done.\n";
	}
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
