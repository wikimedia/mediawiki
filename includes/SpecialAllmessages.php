<?php

	function wfSpecialAllmessages()
	{
		global $wgOut, $wgAllMessagesEn, $wgRequest, $wgMessageCache, $wgTitle;
		$ot = $wgRequest->getText('ot');
		$mwMsg =& MagicWord::get( MAG_MSG );
		set_time_limit(0);
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
		if ($ot == 'html') {
			$navText .= makeWikiText($messages);
			$wgOut->addHTML('<a href="'.$wgTitle->getLocalUrl('ot=php').'">PHP</a> | HTML');
			$wgOut->addWikiText($navText);
		} else {
			$navText .= makePhp($messages);
			$wgOut->addHTML('PHP | <a href="'.$wgTitle->getLocalUrl('ot=html').'">HTML</a><pre>'.htmlspecialchars($navText).'</pre>');
		}
		return;
	}
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
