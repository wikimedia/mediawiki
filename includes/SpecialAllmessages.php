<?php
/**
 * Use this special page to get a list of the MediaWiki system messages.
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor.
 */
function wfSpecialAllmessages() {
	global $wgOut, $wgRequest, $wgMessageCache, $wgTitle;
	global $wgUseDatabaseMessages;

	# The page isn't much use if the MediaWiki namespace is not being used
	if( !$wgUseDatabaseMessages ) {
		$wgOut->addWikiText( wfMsg( 'allmessagesnotsupportedDB' ) );
		return;
	}

	wfProfileIn( __METHOD__ );

	wfProfileIn( __METHOD__ . '-setup' );
	$ot = $wgRequest->getText( 'ot' );

	$navText = wfMsg( 'allmessagestext' );

	# Make sure all extension messages are available
	MessageCache::loadAllMessages();

	$first = true;
	$sortedArray = array_merge( Language::getMessagesFor( 'en' ), $wgMessageCache->getExtensionMessagesFor( 'en' ) );
	ksort( $sortedArray );
	$messages = array();
	$wgMessageCache->disableTransform();

	foreach ( $sortedArray as $key => $value ) {
		$messages[$key]['enmsg'] = $value;
		$messages[$key]['statmsg'] = wfMsgNoDb( $key );
		$messages[$key]['msg'] = wfMsg ( $key );
	}

	$wgMessageCache->enableTransform();
	wfProfileOut( __METHOD__ . '-setup' );

	wfProfileIn( __METHOD__ . '-output' );
	if ( $ot == 'php' ) {
		$navText .= makePhp( $messages );
		$wgOut->addHTML( 'PHP | <a href="' . $wgTitle->escapeLocalUrl( 'ot=html' ) . '">HTML</a><pre>' . htmlspecialchars( $navText ) . '</pre>' );
	} else {
		$wgOut->addHTML( '<a href="' . $wgTitle->escapeLocalUrl( 'ot=php' ) . '">PHP</a> | HTML' );
		$wgOut->addWikiText( $navText );
		$wgOut->addHTML( makeHTMLText( $messages ) );
	}
	wfProfileOut( __METHOD__ . '-output' );

	wfProfileOut( __METHOD__ );
}

/**
 * Create the messages array, formatted in PHP to copy to language files.
 * @param $messages Messages array.
 * @return The PHP messages array.
 * @todo Make suitable for language files.
 */
function makePhp( $messages ) {
	global $wgLang;
	$txt = "\n\n\$messages = array(\n";
	foreach( $messages as $key => $m ) {
		if( $wgLang->getCode() != 'en' && $m['msg'] == $m['enmsg'] ) {
			continue;
		} else if ( wfEmptyMsg( $key, $m['msg'] ) ) {
			$m['msg'] = '';
			$comment = ' #empty';
		} else {
			$comment = '';
		}
		$txt .= "'$key' => '" . preg_replace( "/(?<!\\\\)'/", "\'", $m['msg']) . "',$comment\n";
	}
	$txt .= ');';
	return $txt;
}

/**
 * Create a list of messages, formatted in HTML as a list of messages and values and showing differences between the default language file message and the message in MediaWiki: namespace.
 * @param $messages Messages array.
 * @return The HTML list of messages.
 */
function makeHTMLText( $messages ) {
	global $wgLang, $wgContLang, $wgUser;
	wfProfileIn( __METHOD__ );

	$sk =& $wgUser->getSkin();
	$talk = $wgLang->getNsText( NS_TALK );
	$mwnspace = $wgLang->getNsText( NS_MEDIAWIKI );
	$mwtalk = $wgLang->getNsText( NS_MEDIAWIKI_TALK );

	$input = wfElement( 'input', array(
		'type'    => 'text',
		'id'      => 'allmessagesinput',
		'onkeyup' => 'allmessagesfilter()'
	), '' );
	$checkbox = wfElement( 'input', array(
		'type'    => 'button',
		'value'   => wfMsgHtml( 'allmessagesmodified' ),
		'id'      => 'allmessagescheckbox',
		'onclick' => 'allmessagesmodified()'
	), '' );

	$txt = '<span id="allmessagesfilter" style="display: none;">' . wfMsgHtml( 'allmessagesfilter' ) . " {$input}{$checkbox} " . '</span>';

	$txt .= '
<table border="1" cellspacing="0" width="100%" id="allmessagestable">
	<tr>
		<th rowspan="2">' . wfMsgHtml( 'allmessagesname' ) . '</th>
		<th>' . wfMsgHtml( 'allmessagesdefault' ) . '</th>
	</tr>
	<tr>
		<th>' . wfMsgHtml( 'allmessagescurrent' ) . '</th>
	</tr>';

	wfProfileIn( __METHOD__ . "-check" );

	# This is a nasty hack to avoid doing independent existence checks
	# without sending the links and table through the slow wiki parser.
	$pageExists = array(
		NS_MEDIAWIKI => array(),
		NS_MEDIAWIKI_TALK => array()
	);
	$dbr =& wfGetDB( DB_SLAVE );
	$page = $dbr->tableName( 'page' );
	$sql = "SELECT page_namespace,page_title FROM $page WHERE page_namespace IN (" . NS_MEDIAWIKI . ", " . NS_MEDIAWIKI_TALK . ")";
	$res = $dbr->query( $sql );
	while( $s = $dbr->fetchObject( $res ) ) {
		$pageExists[$s->page_namespace][$s->page_title] = true;
	}
	$dbr->freeResult( $res );
	wfProfileOut( __METHOD__ . "-check" );

	wfProfileIn( __METHOD__ . "-output" );

	$i = 0;

	foreach( $messages as $key => $m ) {
		$title = $wgLang->ucfirst( $key );
		if( $wgLang->getCode() != $wgContLang->getCode() ) {
			$title .= '/' . $wgLang->getCode();
		}

		$titleObj =& Title::makeTitle( NS_MEDIAWIKI, $title );
		$talkPage =& Title::makeTitle( NS_MEDIAWIKI_TALK, $title );

		$changed = ( $m['statmsg'] != $m['msg'] );
		$message = htmlspecialchars( $m['statmsg'] );
		$mw = htmlspecialchars( $m['msg'] );

		if( isset( $pageExists[NS_MEDIAWIKI][$title] ) ) {
			$pageLink = $sk->makeKnownLinkObj( $titleObj, "<span id=\"sp-allmessages-i-$i\">" .  htmlspecialchars( $key ) . '</span>' );
		} else {
			$pageLink = $sk->makeBrokenLinkObj( $titleObj, "<span id=\"sp-allmessages-i-$i\">" .  htmlspecialchars( $key ) . '</span>' );
		}
		if( isset( $pageExists[NS_MEDIAWIKI_TALK][$title] ) ) {
			$talkLink = $sk->makeKnownLinkObj( $talkPage, htmlspecialchars( $talk ) );
		} else {
			$talkLink = $sk->makeBrokenLinkObj( $talkPage, htmlspecialchars( $talk ) );
		}
		
		$anchor = 'msg_' . htmlspecialchars( strtolower( $title ) );
		$anchor = "<a id=\"$anchor\" name=\"$anchor\"></a>";

		if( $changed ) {
			$txt .= "
	<tr class=\"orig\" id=\"sp-allmessages-r1-$i\">
		<td rowspan=\"2\">
			$anchor$pageLink<br />$talkLink
		</td><td>
$message
		</td>
	</tr><tr class=\"new\" id=\"sp-allmessages-r2-$i\">
		<td>
$mw
		</td>
	</tr>";
		} else {
			$txt .= "
	<tr class=\"def\" id=\"sp-allmessages-r1-$i\">
		<td>
			$anchor$pageLink<br />$talkLink
		</td><td>
$mw
		</td>
	</tr>";
		}
		$i++;
	}
	$txt .= '</table>';
	wfProfileOut( __METHOD__ . '-output' );

	wfProfileOut( __METHOD__ );
	return $txt;
}

?>
