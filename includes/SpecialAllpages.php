<?php

function wfSpecialAllpages( $par=NULL )
{
	global $indexMaxperpage, $wgRequest;
	$indexMaxperpage = 480;
	$from = $wgRequest->getVal( 'from' );
	
	if( $par ) {
		indexShowChunk( $par );
	} elseif( !is_null( $from ) ) {
		indexShowChunk( $from );
	} else {
		indexShowToplevel();
	}
}

function indexShowToplevel()
{
	global $wgOut, $indexMaxperpage, $wgLang;
	$fname = "indexShowToplevel";

	# Cache
	$vsp = $wgLang->getValidSpecialPages();
	$log = new LogPage( $vsp["Allpages"] );
	$log->mUpdateRecentChanges = false;

	global $wgMiserMode;
	if ( $wgMiserMode ) {
		$log->showAsDisabledPage();
		return;
	}
	
	$dbr =& wfGetDB( DB_SLAVE );
	$cur = $dbr->tableName( 'cur' );
	$fromwhere = "FROM $cur WHERE cur_namespace=0";
	$order = 'ORDER BY cur_title';
	$out = "";
	$where = array( 'cur_namespace' => 0 );

	$count = $dbr->selectField( 'cur', 'COUNT(*)', $where, $fname );
	$sections = ceil( $count / $indexMaxperpage );
	$inpoint = $dbr->selectField( 'cur', 'cur_title', $where, $fname, $order );
	
	$out .= "<table>\n";
	# There's got to be a cleaner way to do this!
	for( $i = 1; $i < $sections; $i++ ) {
		$from = $i * $indexMaxperpage;
		$sql = "SELECT cur_title $fromwhere $order ".$dbr->limitResult(2,$from);
		$res = $dbr->query( $sql, $fname );

		$s = $dbr->fetchObject( $res );
		$outpoint = $s->cur_title;
		$out .= indexShowline( $inpoint, $outpoint );

		$s = $dbr->fetchObject( $res );
		$inpoint = $s->cur_title;

		$dbr->freeResult( $res );
	}

	$from = $i * $indexMaxperpage;
	$sql = "SELECT cur_title $fromwhere $order ".wfLimitResult(1,$count-1);
	$res = $dbr->query( $sql, $fname );
	$s = $dbr->fetchObject( $res );
	$outpoint = $s->cur_title;
	$out .= indexShowline( $inpoint, $outpoint );
	$out .= "</table>\n";

	# Saving cache
	$log->replaceContent( $out );

	$wgOut->addHtml( $out );
}

function indexShowline( $inpoint, $outpoint )
{
	global $wgOut, $wgLang, $wgUser;
	$sk = $wgUser->getSkin();
	$dbr =& wfGetDB( DB_SLAVE );

	# Fixme: this is ugly
	$out = wfMsg(
		"alphaindexline",
		$sk->makeKnownLink( $wgLang->specialPage( "Allpages" ),
			str_replace( "_", " ", $inpoint ),
			"from=" . $dbr->strencode( $inpoint ) ) . "</td><td>",
		"</td><td align=\"left\">" .
		str_replace( "_", " ", $outpoint )
		);
	return "<tr><td align=\"right\">{$out}</td></tr>\n";
}

function indexShowChunk( $from )
{
	global $wgOut, $wgUser, $indexMaxperpage, $wgLang;
	$sk = $wgUser->getSkin();
	$maxPlusOne = $indexMaxperpage + 1;

	$out = "";
	$dbr =& wfGetDB( DB_SLAVE );
	$cur = $dbr->tableName( 'cur' );
	$sql = "SELECT cur_title FROM $cur WHERE cur_namespace=0 AND cur_title >= '"
		. $dbr->strencode( $from ) . "' ORDER BY cur_title LIMIT " . $maxPlusOne;
	$res = $dbr->query( $sql, "indexShowChunk" );

	### FIXME: side link to previous

	$n = 0;
	$out = "<table border=\"0\" width=\"100%\">\n";
	while( ($n < $indexMaxperpage) && ($s = $dbr->fetchObject( $res )) ) {
		$t = Title::makeTitle( 0, $s->cur_title );
		if( $t ) {
			$link = $sk->makeKnownLinkObj( $t );
		} else {
			$link = "[[" . htmlspecialchars( $s->cur_title ) . "]]";
		}
		if( $n % 3 == 0 ) {
			$out .= "<tr>\n";
		}
		$out .= "<td>$link</td>";
		$n++;
		if( $n % 3 == 0 ) {
			$out .= "</tr>\n";
		}
	}
	if( ($n % 3) != 0 ) {
		$out .= "</tr>\n";
	}
	$out .= "</table>";

	$out2 = "<div style='text-align: right; font-size: smaller; margin-bottom: 1em;'>" .
			$sk->makeKnownLink( $wgLang->specialPage( "Allpages" ),
				wfMsg ( 'allpages' ) );
	if ( ($n == $indexMaxperpage) && ($s = $dbr->fetchObject( $res )) ) {
		$out2 .= " | " . $sk->makeKnownLink(
			$wgLang->specialPage( "Allpages" ),
			wfMsg ( 'nextpage', $s->cur_title ),
			"from=" . $dbr->strencode( $s->cur_title ) );
	}
	$out2 .= "</div>";

	$wgOut->addHtml( $out2 . $out );
}

?>
