<?

function wfSpecialRandompage()
{
	global $wgOut, $wgTitle, $wgArticle, $force;
	$fname = "wfSpecialRandompage";

	wfSeedRandom();
	$sqlget = "SELECT cur_id,cur_title
		FROM cur USE INDEX (cur_random)
		WHERE cur_namespace=0 AND cur_is_redirect=0
		AND cur_random>RAND()
		ORDER BY cur_random
		LIMIT 1";
	$res = wfQuery( $sqlget, $fname );
	if( $s = wfFetchObject( $res ) ) {
		$sql = "UPDATE cur SET cur_random=RAND() WHERE cur_id={$s->cur_id}";
		wfQuery( $sql, $fname );
		$rt = wfUrlEncode( $s->cur_title );
	} else {
		# No articles?!
		$rt = "";
	}

	$wgOut->reportTime(); # for logfile
	$wgOut->redirect( wfLocalUrl( $rt ) );
}

?>
