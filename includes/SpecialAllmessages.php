<?php
/**
 * Provide functions to generate a special page
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialAllmessages() {
	global $wgOut, $wgAllMessagesEn, $wgRequest, $wgMessageCache, $wgTitle;
	global $wgLanguageCode, $wgContLanguageCode, $wgContLang;
	global $wgUseDatabaseMessages;

	if(!$wgUseDatabaseMessages) {
		$wgOut->addHTML(wfMsg('allmessagesnotsupportedDB'));
		return;
	}

	$fname = "wfSpecialAllMessages";
	wfProfileIn( $fname );
	
	wfProfileIn( "$fname-setup");
	$ot = $wgRequest->getText( 'ot' );
	$mwMsg =& MagicWord::get( MAG_MSG );
	
	$navText = wfMsg( 'allmessagestext', $mwMsg->getSynonym( 0 ) );


	$first = true;
	$sortedArray = $wgAllMessagesEn;
	ksort( $sortedArray );
	$messages = array();
	$wgMessageCache->disableTransform();

	foreach ( $sortedArray as $key => $enMsg ) {
		$messages[$key]['enmsg'] = $enMsg;
		$messages[$key]['statmsg'] = wfMsgNoDb( $key );
		$messages[$key]['msg'] = wfMsg ( $key );
	}

	$wgMessageCache->enableTransform();
	wfProfileOut( "$fname-setup" );
	
	wfProfileIn( "$fname-output" );
	if ($ot == 'php') {
		$navText .= makePhp($messages);
		$wgOut->addHTML('PHP | <a href="'.$wgTitle->escapeLocalUrl('ot=html').'">HTML</a><pre>'.htmlspecialchars($navText).'</pre>');
	} else {
		$wgOut->addHTML( '<a href="'.$wgTitle->escapeLocalUrl('ot=php').'">PHP</a> | HTML' );
		$wgOut->addWikiText( $navText );
		$wgOut->addHTML( makeHTMLText( $messages ) );
	}
	wfProfileOut( "$fname-output" );
	
	wfProfileOut( $fname );
}

/**
 *
 */
function makePhp($messages) {
	global $wgLanguageCode;
	$txt = "\n\n".'$wgAllMessages'.ucfirst($wgLanguageCode).' = array('."\n";
	foreach( $messages as $key => $m ) {
		if(strtolower($wgLanguageCode) != 'en' and $m['msg'] == $m['enmsg'] ) {
			if (strstr($m['msg'],"\n")) {
				$txt.='/* ';
				$comment=' */';
			} else {
				$txt .= '#';
				$comment = '';
			}
		} elseif ($m['msg'] == '&lt;'.$key.'&gt;'){
			$m['msg'] = '';
			$comment = ' #empty';
		} else {
			$comment = '';
		}
		$txt .= "'".$key."' => \"".str_replace('"','\"',$m['msg'])."\",$comment\n";
	}
	$txt .= ');';
	return $txt;
}

/**
 *
 */
function makeHTMLText( $messages ) {
	global $wgLang, $wgUser, $wgLanguageCode, $wgContLanguageCode;
	$fname = "makeHTMLText";
	wfProfileIn( $fname );
	
	$sk =& $wgUser->getSkin();
	$talk = $wgLang->getNsText( NS_TALK );
	$mwnspace = $wgLang->getNsText( NS_MEDIAWIKI );
	$mwtalk = $wgLang->getNsText( NS_MEDIAWIKI_TALK );
	$txt = "

	<table border='1' cellspacing='0' width='100%'>
	<tr bgcolor='#b2b2ff'>
		<th>Name</th>
		<th>Default text</th>
		<th>Current text</th>
	</tr>";
	
	wfProfileIn( "$fname-check" );
	# This is a nasty hack to avoid doing independent existence checks
	# without sending the links and table through the slow wiki parser.
	$pageExists = array(
		NS_MEDIAWIKI => array(),
		NS_MEDIAWIKI_TALK => array()
	);
	$sql = "SELECT cur_namespace,cur_title FROM cur WHERE cur_namespace IN (" . NS_MEDIAWIKI . ", " . NS_MEDIAWIKI_TALK . ")";
	$dbr =& wfGetDB( DB_SLAVE );
	$res = $dbr->query( $sql );
	while( $s = $dbr->fetchObject( $res ) ) {
		$pageExists[$s->cur_namespace][$s->cur_title] = true;
	}
	$dbr->freeResult( $res );
	wfProfileOut( "$fname-check" );

	wfProfileIn( "$fname-output" );
	foreach( $messages as $key => $m ) {

		$title = $wgLang->ucfirst( $key );
		if($wgLanguageCode != $wgContLanguageCode)
			$title.="/$wgLanguageCode";
		$titleObj =& Title::makeTitle( NS_MEDIAWIKI, $title );
		$talkPage =& Title::makeTitle( NS_MEDIAWIKI_TALK, $title );

		$colorIt = ($m['statmsg'] == $m['msg']) ? " bgcolor=\"#f0f0ff\"" : " bgcolor=\"#ffe2e2\"";
		$message = htmlspecialchars( $m['statmsg'] );
		$mw = htmlspecialchars( $m['msg'] );
		
		#$pageLink = $sk->makeLinkObj( $titleObj, htmlspecialchars( $key ) );
		#$talkLink = $sk->makeLinkObj( $talkPage, htmlspecialchars( $talk ) );
		if( isset( $pageExists[NS_MEDIAWIKI][$title] ) ) {
			$pageLink = $sk->makeKnownLinkObj( $titleObj, htmlspecialchars( $key ) );
		} else {
			$pageLink = $sk->makeBrokenLinkObj( $titleObj, htmlspecialchars( $key ) );
		}
		if( isset( $pageExists[NS_MEDIAWIKI_TALK][$title] ) ) {
			$talkLink = $sk->makeKnownLinkObj( $talkPage, htmlspecialchars( $talk ) );
		} else {
			$talkLink = $sk->makeBrokenLinkObj( $talkPage, htmlspecialchars( $talk ) );
		}

		$txt .= 
		"<tr$colorIt><td>
		$pageLink<br />
		$talkLink
		</td><td>
		$message
		</td><td>
		$mw
		</td></tr>";
	}
	$txt .= "</table>";
	wfProfileOut( "$fname-output" );

	wfProfileOut( $fname );
	return $txt;
}

?>
