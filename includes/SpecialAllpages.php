<?php

function wfSpecialAllpages( $par=NULL )
{
	global $indexMaxperpage, $wgRequest, $wgLoadBalancer;
	$indexMaxperpage = 480;
	$from = $wgRequest->getVal( 'from' );
	
	$wgLoadBalancer->force(-1);

	if( $par ) {
		indexShowChunk( $par );
	} elseif( !is_null( $from ) ) {
		indexShowChunk( $from );
	} else {
		indexShowToplevel();
	}

	$wgLoadBalancer->force(0);
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

	$fromwhere = "FROM cur WHERE cur_namespace=0";
	$order = "ORDER BY cur_title";
	$out = "";

	$sql = "SELECT COUNT(*) AS count $fromwhere";
	$res = wfQuery( $sql, DB_READ, $fname );
	$s = wfFetchObject( $res );
	$count = $s->count;
	$sections = ceil( $count / $indexMaxperpage );

	$sql = "SELECT cur_title $fromwhere $order LIMIT 1";
	$res = wfQuery( $sql, DB_READ, $fname );
	$s = wfFetchObject( $res );
	$inpoint = $s->cur_title;

	$out .= "<table>\n";
	# There's got to be a cleaner way to do this!
	for( $i = 1; $i < $sections; $i++ ) {
		$from = $i * $indexMaxperpage;
		$sql = "SELECT cur_title $fromwhere $order ".wfLimitResult(2,$from);
		$res = wfQuery( $sql, DB_READ, $fname );

		$s = wfFetchObject( $res );
		$outpoint = $s->cur_title;
		$out .= indexShowline( $inpoint, $outpoint );

		$s = wfFetchObject( $res );
		$inpoint = $s->cur_title;

		wfFreeResult( $res );
	}

	$from = $i * $indexMaxperpage;
	$sql = "SELECT cur_title $fromwhere $order ".wfLimitResult(1,$count-1);
	$res = wfQuery( $sql, DB_READ, $fname );
	$s = wfFetchObject( $res );
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

	# Fixme: this is ugly
	$out = wfMsg(
		"alphaindexline",
		$sk->makeKnownLink( $wgLang->specialPage( "Allpages" ),
			str_replace( "_", " ", $inpoint ),
			"from=" . wfStrencode( $inpoint ) ) . "</td><td>",
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
	$sql = "SELECT cur_title FROM cur WHERE cur_namespace=0 AND cur_title >= '"
		. wfStrencode( $from ) . "' ORDER BY cur_title LIMIT " . $maxPlusOne;
	$res = wfQuery( $sql, DB_READ, "indexShowChunk" );

	### FIXME: side link to previous

	$n = 0;
	$out = "<table border=\"0\" width=\"100%\">\n";
	while( ($n < $indexMaxperpage) && ($s = wfFetchObject( $res )) ) {
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
	if ( ($n == $indexMaxperpage) && ($s = wfFetchObject( $res )) ) {
		$out2 .= " | " . $sk->makeKnownLink(
			$wgLang->specialPage( "Allpages" ),
			wfMsg ( 'nextpage', $s->cur_title ),
			"from=" . wfStrencode( $s->cur_title ) );
	}
	$out2 .= "</div>";

	$wgOut->addHtml( $out2 . $out );
}

?>
