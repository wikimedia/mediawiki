<?

function wfSpecialExport( $page = "" ) {
	global $wgOut, $wgLang;
	
	if( $_REQUEST['action'] == 'submit') {
		$page = $_REQUEST['pages'];
		$curonly = isset($_REQUEST['curonly']) ? true : false;
	} else {
		$curonly = true;
	}
	
	if( $page != "" ) {
		header( "Content-type: application/xml; charset=utf-8" );
		$pages = explode( "\n", $page );
		$xml = pages2xml( $pages, $curonly );
		echo $xml;
		exit(1);
	}
	
	$wgOut->addWikiText( wfMsg( "exporttext" ) );
	$action = wfLocalUrlE( $wgLang->SpecialPage( "Export" ) );
	$wgOut->addHTML( "
<form method='post' action=\"$action\">
<input type='hidden' name='action' value='submit' />
<textarea name='pages' cols='40' rows='10'></textarea><br />
<label><input type='checkbox' name='curonly' value='true' checked />
" . wfMsg( "exportcuronly" ) . "</label><br />
<input type='submit' />
</form>
" );
}

function pages2xml( $pages, $curonly = false ) {
	global $wgLanguageCode, $wgInputEncoding, $wgLang;
	$xml = "<mediawiki version=\"0.1\" xml:ns=\"$wgLanguageCode\">\n";
	foreach( $pages as $page ) {
		$xml .= page2xml( $page, $curonly );
	}
	$xml .= "</mediawiki>\n";
	if($wgInputEncoding != "utf-8")
		$xml = $wgLang->iconv( $wgInputEncoding, "utf-8", $xml );
	return $xml;
}

function page2xml( $page, $curonly, $full = false ) {
	global $wgInputCharset, $wgLang;
	$title = Title::NewFromText( $page );
	$t = wfStrencode( $title->getDBKey() );
	$ns = $title->getNamespace();
	$sql = "SELECT cur_id as id,cur_timestamp as timestamp,cur_user as user,cur_user_text as user_text," .
		"cur_restrictions as restrictions,cur_comment as comment,cur_text as text FROM cur " .
		"WHERE cur_namespace=$ns AND cur_title='$t'";
	$res = wfQuery( $sql );
	if( $s = wfFetchObject( $res ) ) {
		$tl = htmlspecialchars( $title->getPrefixedText() );
		$xml = "  <page>\n";
		$xml .= "    <title>$tl</title>\n";
		if( $full ) {
			$xml .= "    <id>$s->id</id>\n";
		}
		if( $s->restrictions ) {
			$xml .= "    <restrictions>$s->restrictions</restrictions>\n";
		}
		if( !$curonly ) {
			$sql = "SELECT old_id as id,old_timestamp as timestamp, old_user as user, old_user_text as user_text," .
				"old_comment as comment, old_text as text FROM old " .
				"WHERE old_namespace=$ns AND old_title='$t' ORDER BY old_timestamp";
			$res = wfQuery( $sql );

			while( $s = wfFetchObject( $res ) ) {
				$xml .= revision2xml( $s, $full, false );
			}
		}
		$xml .= revision2xml( $s, $full, true );
		$xml .= "  </page>\n";
		return $xml;
	} else {
		return "";
	}
}

function revision2xml( $s, $full, $cur ) {
	$ts = wfTimestamp2ISO8601( $s->timestamp );
	$xml = "    <revision>\n";
	if($full && !$cur)
		$xml .= "    <id>$s->id</id>\n";
	$xml .= "      <timestamp>$ts</timestamp>\n";
	if($s->user) {
		$u = "<username>" . htmlspecialchars( $s->user_text ) . "</username>";
		if($full)
			$u .= "<id>$s->user</id>";
	} else {
		$u = "<ip>" . htmlspecialchars( $s->user_text ) . "</ip>";
	}
	$xml .= "      <contributor>$u</contributor>\n";
	if($s->minor) {
		$xml .= "      <minor/>\n";
	}
	if($s->comment != "") {
		$c = htmlspecialchars( $s->comment );
		$xml .= "      <comment>$c</comment>\n";
	}
	$t = htmlspecialchars( $s->text );
	$xml .= "      <text>$t</text>\n";
	$xml .= "    </revision>\n";
	return $xml;
}

function wfTimestamp2ISO8601( $ts ) {
	#2003-08-05T18:30:02Z
	return preg_replace( '/^(....)(..)(..)(..)(..)(..)$/', '$1-$2-$3T$4:$5:$6Z', $ts );
}

?>
