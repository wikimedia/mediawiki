<?php
/**
 * Script to initialise the MediaWiki namespace
 *
 * This script is included from update.php and install.php. Do not run it
 * by itself.
 *
 * @deprecated
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
function initialiseMessages( $overwrite = false, $messageArray = false, $outputCallback = false ) {
	global $wgContLang, $wgContLanguageCode;
	global $wgContLangClass;
	global $wgDisableLangConversion;
	global $wgForceUIMsgAsContentMsg;
	global $wgLanguageNames;
	global $IP;

	# overwrite language conversion	option so that all variants
	# of the messages are initialised
	$wgDisableLangConversion = false;

	if ( $messageArray ) {
		$sortedArray = $messageArray;
	} else {
		$sortedArray = Language::getMessagesFor( 'en' );
	}

	ksort( $sortedArray );
	$messages=array();

	$variants = $wgContLang->getVariants();
	if(!in_array($wgContLanguageCode, $variants))
		$variants[]=$wgContLanguageCode;

	foreach ($variants as $v) {
		$lang = Language::factory( $v );

		if($v==$wgContLanguageCode)
			$suffix='';
		else
			$suffix="/$v";
		foreach ($sortedArray as $key => $msg) {
			$messages[$key.$suffix] = $lang->getMessage($key);
		}
	}

	require_once('languages/Names.php');

    /*
	  initialize all messages in $wgForceUIMsgAsContentMsg for all
	  languages in Names.php
    */
	if( is_array( $wgForceUIMsgAsContentMsg ) ) {
		foreach( $wgForceUIMsgAsContentMsg as $uikey ) {
			foreach( $wgLanguageNames as $code => $name) {
				if( $code == $wgContLanguageCode )
					continue;
				$msg = $wgContLang->getMessage( $uikey );
				if( $msg )
					$messages[$uikey. '/' . $code] = $msg;
			}
		}
	}
	initialiseMessagesReal( $overwrite, $messages, $outputCallback );
}

/** */
function initialiseMessagesReal( $overwrite = false, $messageArray = false, $outputCallback = false ) {
	global $wgContLang, $wgScript, $wgServer, $wgLanguageCode;
	global $wgOut, $wgArticle, $wgUser;
	global $wgMessageCache, $wgMemc, $wgUseMemCached;

	# Initialise $wgOut and $wgUser for a command line script
	$wgOut->disable();

	$wgUser = new User;
	$wgUser->setLoaded( true ); # Don't load from DB
	$wgUser->setName( 'MediaWiki default' );

	# Don't try to draw messages from the database we're initialising
	$wgMessageCache->disable();
	$wgMessageCache->disableTransform();

	$fname = 'initialiseMessages';
	$ns = NS_MEDIAWIKI;
	# username responsible for the modifications
	# Don't change it unless you're prepared to update the DBs accordingly, otherwise the
	# default messages won't be overwritten
	$username = 'MediaWiki default';

	if ( !$outputCallback ) {
		# Print is not a function, and there doesn't appear to be any built-in 
		# workalikes, so let's just make our own anonymous function to do the 
		# same thing. 
		$outputCallback = create_function( '$s', 'print $s;' );
	}

	$outputCallback( "Initialising \"MediaWiki\" namespace for language code $wgLanguageCode...\n" );

	# Check that the serialized data files are OK
	if ( Language::isLocalisationOutOfDate( $wgLanguageCode ) ) {
		$outputCallback( "Warning: serialized data file may be out of date.\n" );
	}

	$dbr =& wfGetDB( DB_SLAVE );
	$dbw =& wfGetDB( DB_MASTER );
	$page = $dbr->tableName( 'page' );
	$revision = $dbr->tableName( 'revision' );

	$timestamp = wfTimestampNow();

	$first = true;
	if ( $messageArray ) {
		$sortedArray = $messageArray;
	} else {
		$sortedArray = $wgContLang->getAllMessages();
	}

	ksort( $sortedArray );

	# SELECT all existing messages
	# Can't afford to be locking all rows for update, this script can take quite a long time to complete
	$rows = array();
	$nitems = count($sortedArray);
	$maxitems = $dbr->maxListLen();
	$pos = 0;
	if ($maxitems)
		$chunks = array_chunk($sortedArray, $maxitems);
	else
		$chunks = array($sortedArray);

	foreach ($chunks as $chunk) {
		$first = true;
		$sql = "SELECT page_title,page_is_new,rev_user_text FROM $page, $revision WHERE
			page_namespace=$ns AND rev_id=page_latest AND page_title IN(";

		foreach ( $chunk as $key => $enMsg ) {
			if ( $key == '' ) {
				continue; // Skip odd members
			}
			if ( $first ) {
				$first = false;
			} else {
				$sql .= ',';
			}
			$titleObj = Title::newFromText( $wgContLang->ucfirst( $key ) );
			$enctitle = $dbr->strencode($titleObj->getDBkey());
			$sql .= "'$enctitle'";
		}

		$sql .= ')';
		$res = $dbr->query( $sql );
		while ($row = $dbr->fetchObject($res))
			$rows[] = $row;
	}

	# Read the results into an array
	# Decide whether or not each one needs to be overwritten
	$existingTitles = array();
	foreach ($rows as $row) {
		if ( $row->rev_user_text != $username  && $row->rev_user_text != 'Template namespace initialisation script' ) {
			$existingTitles[$row->page_title] = 'keep';
		} else {
			$existingTitles[$row->page_title] = 'chuck';
		}
	}

	# Insert queries are done in one multi-row insert
	# Here's the start of it:
	$arr = array();
	$talk = $wgContLang->getNsText( NS_TALK );
	$mwtalk = $wgContLang->getNsText( NS_MEDIAWIKI_TALK );

	$numUpdated = 0;
	$numKept = 0;
	$numInserted = 0;
	
	# Merge these into a single transaction for speed
	$dbw->begin();

	# Process each message
	foreach ( $sortedArray as $key => $message ) {
		if ( $key == '' ) {
			continue; // Skip odd members
		}
		# Get message text
		if ( !$messageArray ) {
			$message = wfMsgNoDBForContent( $key );
		}
		if ( is_null( $message ) ) {
			# This happens sometimes with out of date serialized data files
			$outputCallback( "Warning: Skipping null message $key\n" );
			continue;
		}

		$titleObj = Title::newFromText( $wgContLang->ucfirst( $key ), NS_MEDIAWIKI );
		$title = $titleObj->getDBkey();

		# Update messages which already exist
		if ( array_key_exists( $title, $existingTitles ) ) {
			if ( $existingTitles[$title] == 'chuck' || $overwrite) {
				# Don't bother writing a new revision if we're the same
				# as the current text!
				$revision = Revision::newFromTitle( $titleObj );
				if( is_null( $revision ) || $revision->getText() != $message ) {
					$article = new Article( $titleObj );
					$article->quickEdit( $message );
					++$numUpdated;
				} else {
					++$numKept;
				}
			} else {
				++$numKept;
			}
		} else {
			$article = new Article( $titleObj );
			$newid = $article->insertOn( $dbw );
			# FIXME: set restrictions
			$revision = new Revision( array(
				'page'      => $newid,
				'text'      => $message,
				'user'      => 0,
				'user_text' => $username,
				'comment'   => '',
				) );
			$revid = $revision->insertOn( $dbw );
			$article->updateRevisionOn( $dbw, $revision );
			++$numInserted;
		}
	}
	$dbw->commit();

	# Clear the relevant memcached key
	$wgMessageCache->clear();
	$outputCallback( "Done. Updated: $numUpdated, inserted: $numInserted, kept: $numKept.\n" );
}

/** */
function loadLanguageFile( $filename ) {
	$contents = file_get_contents( $filename );
	# Remove header line
	$p = strpos( $contents, "\n" ) + 1;
	$contents = substr( $contents, $p );
	# Unserialize
	return unserialize( $contents );
}

/** */
function doUpdates() {
	global $wgDeferredUpdateList;
	foreach ( $wgDeferredUpdateList as $up ) { $up->doUpdate(); }
}
?>
