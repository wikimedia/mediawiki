<?

# This script is included from update.php and install.php. Do not run it 
# by itself.

function initialiseMessages() {
	global $wgLang, $wgScript, $wgServer;

	$fname = "initialiseMessages";
	if ( !method_exists( $wgLang, "getAllMessages" ) ) {
		print "
Error, \$wgLang->getAllMessages() does not exist\n
You need to insert this function into your language file. For example:

function getAllMessages()
{
	global \$wgAllMessagesEn;
	return \$wgAllMessagesEn;
}
		\n";
		return;
	}
	$ns = NS_MEDIAWIKI;
	$sql = "SELECT 1 FROM cur WHERE cur_namespace=$ns LIMIT 1";
	$res = wfQuery( $sql, DB_READ, $fname );
	if ( wfNumRows( $res ) ) {
		print "MediaWiki: namespace already initialised\n";
		return;
	}

	$messages = $wgLang->getAllMessages();
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
	
	print "Initialising \"MediaWiki\" namespace...";

	foreach ( $messages as $key => $message ) {
		$titleObj = Title::newFromText( $key );
		$title = $titleObj->getDBkey();
		$dbencMsg = wfStrencode( $message );
		$sql = "INSERT INTO cur (cur_namespace, cur_title, cur_text,
			cur_user_text, cur_timestamp, cur_restrictions,
			cur_is_new, inverse_timestamp) VALUES (
			$ns,
			'$title',
			'$dbencMsg',
			'MediaWiki default',
			'$timestamp',
			'sysop',
			1,
			'$invTimestamp')";
		wfQuery( $sql, DB_WRITE, $fname );
		$mwObj =& MagicWord::get( MAG_MSGNW );
		$mw = $mwObj->getSynonym( 0 );
		$mw = str_replace( "$1", $key, $mw );

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
	$navText .= "</table>";
	$navText = wfStrencode( $navText );
	$title = wfMsgNoDB( "allmessages" );
	$sql = "INSERT INTO cur (cur_namespace, cur_title, cur_text,
		cur_user_text, cur_timestamp, cur_restrictions,
		cur_is_new, inverse_timestamp) VALUES (
		$ns,
		'$title',
		'$navText',
		'MediaWiki default',
		'$timestamp',
		'sysop',
		1,
		'$invTimestamp')";
	wfQuery( $sql, DB_WRITE, $fname );
	print "done \n";
}

