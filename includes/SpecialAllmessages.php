<?php

	function wfSpecialAllmessages()
	{
		global $wgOut, $wgAllMessagesEn, $wgRequest;
		$ot = $wgRequest->getText('ot');
		$mwMsg =& MagicWord::get( MAG_MSG );
		set_time_limit(0);
		$navText = str_replace( "$1", $mwMsg->getSynonym( 0 ), wfMsg("allmessagestext" ) );

		$first = true;
		$sortedArray = $wgAllMessagesEn;
		ksort( $sortedArray );
		$messages = array();
		foreach ( $sortedArray as $key => $enMsg ) {

			$messages[$key]['enmsg'] = $enMsg;
			$messages[$key]['statmsg'] = wfMsgNoDB( $key );
			$messages[$key]['msg'] = wfMsg ( $key );
		}
		if ($ot == 'php') {
			$navText .= makePhp($messages);
			$wgOut->addHTML('<pre>'.htmlspecialchars($navText).'</pre>');
		} else {
			$navText .= makeWikiText($messages);
			$wgOut->addWikiText( $navText );
		}
		return;
	}
	function makePhp($messages) {
		global $wgLanguageCode;
		$txt = "\n\n".'$wgAllMessages'.ucfirst($wgLanguageCode).' = array('."\n";
		foreach( $messages as $key => $m ) {
			if(strtolower($wgLanguageCode) != 'en' and $m['msg'] == $m['enmsg'] ) {
				$comment = ' #default';
			} elseif ($m['msg'] == '&lt;'.$key.'&gt;'){
				$m['msg'] = '';
				$comment = ' #empty';
			} else {
				$comment = '';
			}
			$txt .= "    '".$key."' => \"".str_replace('"','\"',$m['msg'])."\",$comment\n";
		}
		$txt .= ');';
		return $txt;
	}


	function makeWikiText($messages) {
		global $wgLang;
		$talk = $wgLang->getNsText( NS_TALK );
		$mwnspace = $wgLang->getNsText( NS_MEDIAWIKI );
		$mwtalk = $wgLang->getNsText( NS_MEDIAWIKI_TALK );
		$txt = "

		<table border=1 cellspacing=0 width=100%><tr bgcolor=#b2b2ff><td>
		'''Name'''
		</td><td>
		'''Default text'''
		</td><td>
		'''Current text'''
		</td></tr>";
		foreach( $messages as $key => $m ) {
			$titleObj = Title::newFromText( $key );
			$title = $titleObj->getDBkey();

			$colorIt = ($m['statmsg'] == $m['msg']) ? " bgcolor=\"#f0f0ff\"" : " bgcolor=\"#ffe2e2\"";
			$message = wfEscapeWikiText( $m['statmsg'] );
			$mw = wfEscapeWikiText( $m['msg'] );

			$txt .= 
			"<tr$colorIt><td>
			[[$mwnspace:$title|$key]]<br>
			[[$mwtalk:$title|$talk]]
			</td><td>
			$message
			</td><td>
			$mw
			</td></tr>";
		}
		$txt .= "</table>";

		return $txt;
	}

	?>
