<?

function wfSpecialRandompage()
{
	global $wgOut, $wgTitle, $wgArticle, $force;
	$fname = "wfSpecialRandompage";

	wfSeedRandom();
	$rand = mt_rand() / mt_getrandmax();
	$sqlget = "SELECT cur_id,cur_title
		FROM cur USE INDEX (cur_random)
		WHERE cur_namespace=0 AND cur_is_redirect=0
		AND cur_random>$rand
		ORDER BY cur_random
		LIMIT 1";
	$res = wfQuery( $sqlget, $fname );
	if( $s = wfFetchObject( $res ) ) {
		$rt = wfUrlEncode( $s->cur_title );
	} else {
		# No articles?!
		$rt = "";
	}

	$wgOut->reportTime(); # for logfile
	$wgOut->redirect( wfLocalUrl( $rt ) );
}

?>
