<?php

function wfSpecialAllmessages()
{
	global $wgUser, $wgOut, $wgAllMessagesEn, $wgServer, $wgScript, $wgLang;
	$fname = "wfSpecialAllmessages";
	
	$talk = $wgLang->getNsText( NS_TALK );
	$mwtalk = $wgLang->getNsText( NS_MEDIAWIKI_TALK );
	$mwMsg =& MagicWord::get( MAG_MSG );
	$navText = str_replace( "$1", "allmessagestext", $mwMsg->getSynonym( 0 ) );
	$navText .= "

<table border=1 cellspacing=0 width=100%><tr bgcolor=#b2b2ff><td>
  '''Name'''
</td><td>
  '''Default text'''
</td><td>
  '''Current text'''
</td></tr>";
	
	$first = true;
	$sortedArray = $wgAllMessagesEn;
	ksort( $sortedArray );
	
	foreach ( $sortedArray as $key => $enMsg ) {
		
		$titleObj = Title::newFromText( $key );
		$title = $titleObj->getDBkey();
		
		$message = wfMsgNoDB( $key );
		$mw = wfMsg ( $key );

		$colorIt = ($message == $mw) ? " bgcolor=\"#f0f0ff\"" : " bgcolor=\"#ffe2e2\"";
		
		$message = wfEscapeWikiText( $message );
		$mw = wfEscapeWikiText( $mw );
		
# [$wgServer$wgScript?title=MediaWiki:$title&action=edit $key]<br>
		$navText .= 
"<tr$colorIt><td>
  [[MediaWiki:$title|$key]]<br>
  [[$mwtalk:$title|$talk]]
</td><td>
  $message
</td><td>
  $mw
</td></tr>";
	}

	$navText .= "</table>";

	$wgOut->addWikiText( $navText );

	return;
}

?>
