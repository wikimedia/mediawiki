<?php
# Move "custom messages" from the MediaWiki namespace to the Template namespace
# Usage: php moveCustomMessages.php [<lang>] [phase]

# Script works in three phases:
#   1. Create redirects from Template to MediaWiki namespace. Skip if you don't want them
#   2. Move pages from MediaWiki to Template namespace.
#   3. Convert the text to suit the new syntax

chdir( ".." );
require_once( "commandLine.inc" );

$phase = 0;
if ( is_numeric( @$argv[2] ) && $argv[2] > 0) {
	$phase = intval($argv[2]);
}

$wgUser = new User;
$wgUser->setLoaded( true ); # Don't load from DB
$wgUser->setName( "Template namespace initialisation script" );
$wgUser->addRight( "bot" );

# Compose DB key array
global $wgAllMessagesEn;
$dbkeys = array();

foreach ( $wgAllMessagesEn as $key => $enValue ) {
	$title = Title::newFromText( $key );
	$dbkeys[$title->getDBkey()] = 1;
}

$sql = "SELECT cur_id, cur_title FROM cur WHERE cur_namespace= " . NS_MEDIAWIKI;
$res = wfQuery( $sql, DB_READ );

# Compile target array
$targets = array();
while ( $row = wfFetchObject( $res ) ) {
	if ( !array_key_exists( $row->cur_title, $dbkeys ) ) {
		$targets[$row->cur_title] = 1;
	}
}
wfFreeResult( $res );

# Create redirects from destination to source
if ( $phase == 0 || $phase == 1 ) {
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
	print "\n";
	foreach ( $targets as $partial => $dummy ) {
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
	}
}

# Convert text
if ( $phase == 0 || $phase == 3 ) {
	print "\n";
	
	$parser = new Parser;
	$options = ParserOptions::newFromUser( $wgUser );
	$completedTitles = array();
	$titleChars = Title::legalChars();
	$mediaWiki = $wgLang->getNsText( NS_MEDIAWIKI );
	$template = $wgLang->getNsText( NS_TEMPLATE );
	$linkRegex = "/\[\[$mediaWiki:([$titleChars]*?)\]\]/";
	$msgRegex = "/{{msg:([$titleChars]*?)}}/";

	foreach ( $targets as $partial => $dummy ) {
		$dest = Title::makeTitle( NS_TEMPLATE, $partial );
		$linksTo = $dest->getLinksTo();
		foreach( $linksTo as $source ) {
			$pdbk = $source->getPrefixedDBkey();
			print "$pdbk...";
			if ( !array_key_exists( $pdbk, $completedTitles ) ) {	
				$completedTitles[$pdbk] = 1;
				$id = $source->getArticleID();
				$row = wfGetArray( 'cur', array( 'cur_text' ), 
					array( 'cur_id' => $source->getArticleID() ) );
				$parser->startExternalParse( $source, $options, OT_WIKI );
				$text = $parser->strip( $row->cur_text, $stripState, false );
				# {{msg}} -> {{}}
				$text = preg_replace( $msgRegex, "{{\$1}}", $text );
				# [[MediaWiki:]] -> [[Template:]]
				$text = preg_replace_callback( $linkRegex, "wfReplaceMediaWiki", $text );
				$text = $parser->unstrip( $text, $stripState );
				if ( $text != $row->cur_text ) {
					wfUpdateArray( 'cur', array( 'cur_text' => $text ), array( 'cur_id' => $id ) );
					print "modified\n";
				} else {
					print "not modified\n";
				}
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
