<?

# This script is included from update.php and install.php. Do not run it 
# by itself.

function initialiseMessages( $overwrite = false) {
	global $wgLang, $wgScript, $wgServer, $wgAllMessagesEn;

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
	
	print "Initialising \"MediaWiki\" namespace...";

	foreach ( $wgAllMessagesEn as $key => $enMsg ) {
		$message = wfMsgNoDB( $key );
		$titleObj = Title::newFromText( $key );
		$title = $titleObj->getDBkey();
		$dbencMsg = wfStrencode( $message );
		
		$doInsert = true;
		if ( $overwrite ) {
			$sql = "DELETE FROM cur WHERE cur_namespace=$ns AND cur_title='$title'";
			wfQuery( $sql, DB_WRITE, $fname );
		} else {	
			$row = wfGetArray("cur", array("1"), 	
				array("cur_namespace"=>$ns, "cur_title"=>$title));
			if ($row) {
				$doInsert = false;
			}
		}
		if ( $doInsert ) {
			$sql = "INSERT INTO cur (cur_namespace, cur_title, cur_text,
				cur_user_text, cur_timestamp, cur_restrictions,
				cur_is_new, inverse_timestamp, cur_touched) VALUES (
				$ns,
				'$title',
				'$dbencMsg',
				'MediaWiki default',
				'$timestamp',
				'sysop',
				1,
				'$invTimestamp',
				'$timestamp')";
			wfQuery( $sql, DB_WRITE, $fname );
		}
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
	$sql = "DELETE FROM cur WHERE cur_namespace=$ns AND cur_title='$title'";
	wfQuery( $sql, DB_WRITE, $fname );

	$sql = "INSERT INTO cur (cur_namespace, cur_title, cur_text,
		cur_user_text, cur_timestamp, cur_restrictions,
		cur_is_new, inverse_timestamp, cur_touched) VALUES (
		$ns,
		'$title',
		'$navText',
		'MediaWiki default',
		'$timestamp',
		'sysop',
		1,
		'$invTimestamp',
		'$timestamp')";
	wfQuery( $sql, DB_WRITE, $fname );
	print "done \n";
}

