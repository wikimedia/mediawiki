<?php
/**
 * @deprecated
 * @package MediaWiki
 * @subpackage MaintenanceArchive
 */

/** */
function isTemplateInitialised() {
	$fname = 'isTemplateInitialised';

	$dbw =& wfGetDB( DB_MASTER );
	$res = $dbw->select( 'cur', 1, array( 'cur_namespace' => NS_TEMPLATE ), $fname, array( 'LIMIT' => 1 ) );
	return $dbw->numRows( $res ) ? true : false;
}

function moveCustomMessages( $phase ) {
	global $wgUser, $wgAllMessagesEn, $wgDeferredUpdateList, $wgLang;
	global $targets, $template, $replaceCount;

	$wgUser = new User;
	$wgUser->setLoaded( true ); # Don't load from DB
	$wgUser->setName( "Template namespace initialisation script" );
	$wgUser->addRight( "bot" );

	$dbw =& wfGetDB( DB_MASTER );

	$dbw->ignoreErrors( true );

	# Compose DB key array
	$dbkeys = array();

	foreach ( $wgAllMessagesEn as $key => $enValue ) {
		$title = Title::newFromText( $key );
		$dbkeys[$title->getDBkey()] = 1;
	}

	$res = $dbw->select( 'cur', array( 'cur_id', 'cur_title' ), array( 'cur_namespace' => NS_MEDIAWIKI ) );

	# Compile target array
	$targets = array();
	while ( $row = $dbw->fetchObject( $res ) ) {
		if ( !array_key_exists( $row->cur_title, $dbkeys ) ) {
			$targets[$row->cur_title] = 1;
		}
	}
	$dbw->freeResult( $res );

	# Create redirects from destination to source
	if ( $phase == 0 || $phase == 1 ) {
		print "Creating redirects\n";
		foreach ( $targets as $partial => $dummy ) {
			print "$partial...";
			$nt = Title::makeTitle( NS_TEMPLATE, $partial );
			$ot = Title::makeTitle( NS_MEDIAWIKI, $partial );

			if ( $nt->createRedirect( $ot, "" ) ) {
				print "redirected\n";
			} else {
				print "not redirected\n";
			}
		}
		if ( $phase == 0 ) {
			print "\nRedirects created. Update live script files now.\nPress ENTER to continue.\n\n";
			readconsole();
		}
	}

	# Move pages
	if ( $phase == 0 || $phase == 2 ) {
		print "\nMoving pages...\n";
		foreach ( $targets as $partial => $dummy ) {
			$dbw->query( "BEGIN" );
			$ot = Title::makeTitle( NS_MEDIAWIKI, $partial );
			$nt = Title::makeTitle( NS_TEMPLATE, $partial );
			print "$partial...";

			if ( $ot->moveNoAuth( $nt ) === true ) {
				print "moved\n";
			} else {
				print "not moved\n";
			}
			# Do deferred updates
			while ( count( $wgDeferredUpdateList ) ) {
				$up = array_pop( $wgDeferredUpdateList );
				$up->doUpdate();
			}
			$dbw->query( "COMMIT" );
		}
	}

	# Convert text
	if ( $phase == 0 || $phase == 3 ) {
		print "\nConverting text...\n";
		
		$parser = new Parser;
		$options = ParserOptions::newFromUser( $wgUser );
		$completedTitles = array();
		$titleChars = Title::legalChars();
		$mediaWiki = $wgLang->getNsText( NS_MEDIAWIKI );
		$template = $wgLang->getNsText( NS_TEMPLATE );
		$linkRegex = "/\[\[$mediaWiki:([$titleChars]*?)\]\]/";
		$msgRegex = "/{{msg:([$titleChars]*?)}}/";

		foreach ( $targets as $partial => $dummy ) {
			$dest = Title::makeTitle( NS_MEDIAWIKI, $partial );
			$linksTo = $dest->getLinksTo();
			foreach( $linksTo as $source ) {
				$dbw->query( "BEGIN" );
				$pdbk = $source->getPrefixedDBkey();
				if ( !array_key_exists( $pdbk, $completedTitles ) ) {	
					$completedTitles[$pdbk] = 1;
					$id = $source->getArticleID();
					$row = $dbw->selectRow( 'cur', array( 'cur_text' ), 
						array( 'cur_id' => $source->getArticleID() ) );
					$parser->startExternalParse( $source, $options, OT_WIKI );
					$text = $parser->strip( $row->cur_text, $stripState, false );
					# {{msg}} -> {{}}
					$text = preg_replace( $msgRegex, "{{\$1}}", $text );
					# [[MediaWiki:]] -> [[Template:]]
					$text = preg_replace_callback( $linkRegex, "wfReplaceMediaWiki", $text );
					$text = $parser->unstrip( $text, $stripState );
					$text = $parser->unstripNoWiki( $text, $stripState );
					if ( $text != $row->cur_text ) {
						print "$pdbk\n";
						$art = new Article( $source );
						$art->updateArticle( $text, "", false, false );
						# Do deferred updates
						while ( count( $wgDeferredUpdateList ) ) {
							$up = array_pop( $wgDeferredUpdateList );
							$up->doUpdate();
						}
					} else {
						print "($pdbk)\n";
					}
				} 
				$dbw->query( "COMMIT" );
			}
		}
	}
}


#--------------------------------------------------------------------------------------------------------------
function wfReplaceMediaWiki( $m ) {
	global $targets, $template, $replaceCount;
	$title = Title::newFromText( $m[1] );
	$partial = $title->getDBkey();

	if ( array_key_exists( $partial, $targets ) ) {
		$text = "[[$template:{$m[1]}]]";
	} else {
		$text = $m[0];
	}
	return $text;
}

?>
