<?php

function wfSpecialAllpages( $par=NULL )
{
	global $from, $indexMaxperpage;
	$indexMaxperpage = 480;

	if( $par ) {
		indexShowChunk( $par );
	} elseif( isset( $from ) ) {
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


#	$fromwhere = "FROM cur WHERE cur_namespace=0 AND cur_is_redirect=0";
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
		$sql = "SELECT cur_title $fromwhere $order LIMIT $from,2";
		$res = wfQuery( $sql, DB_READ, $fname );
	
		$s = wfFetchObject( $res );
		$outpoint = $s->cur_title;
		$out .= indexShowline( $inpoint, $outpoint );
	
		$s = wfFetchObject( $res );
		$inpoint = $s->cur_title;
		
		wfFreeResult( $res );
	}
	
	$from = $i * $indexMaxperpage;
	$sql = "SELECT cur_title $fromwhere $order LIMIT " . ($count-1) . ",1";
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
	global $wgOut, $wgUser, $indexMaxperpage;
	$sk = $wgUser->getSkin();
	
	$out = "";
	$sql = "SELECT cur_title
FROM cur
WHERE cur_namespace=0 AND cur_title >= '" . wfStrencode( $from ) . "'
ORDER BY cur_title
LIMIT {$indexMaxperpage}";
	$res = wfQuery( $sql, DB_READ, "indexShowChunk" );

# FIXME: Dynamic column widths, backlink to main list,
# side links to next and previous
	$n = 0;
	$out = "<table border=\"0\">\n<tr>";
	while( $s = wfFetchObject( $res ) ) {
		$t = Title::makeTitle( 0, $s->cur_title );
		if( $t ) {
			$link = $sk->makeKnownLinkObj( $t );
		} else {
			$link = "[[" . htmlspecialchars( $s->cur_title ) . "]]";
		}
		$out .= "<td width=\"33%\">$link</td>";
		$n = ++$n % 3;
		if( $n == 0 ) {
			$out .= "</tr>\n<tr>";
		}
	}
	if( $n != 0 ) {
		$out .= "</tr>\n";
	}
	$out .= "</table>";
#return $out;
	$wgOut->addHtml( $out );
}

?>
