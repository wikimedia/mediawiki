<?php

function linkToMathImage ( $tex, $outputhash )
{
	global $wgMathPath;
	return "<img src=\"".$wgMathPath."/".$outputhash.".png\" alt=\"".wfEscapeHTML($tex)."\">";
}

function renderMath( $tex )
{
	global $wgUser, $wgMathDirectory, $wgTmpDirectory, $wgInputEncoding;
	global $wgTexvc;
	$mf   = wfMsg( "math_failure" );
	$munk = wfMsg( "math_unknown_error" );

	$fname = "renderMath";

	$math = $wgUser->getOption("math");
	if( $math == 3 ) {
		return ('$ '.wfEscapeHTML($tex).' $');
	}

	$md5 = md5($tex);
	$md5_sql = mysql_escape_string(pack("H32", $md5));
	if ($math == 0) {
		$sql = "SELECT math_outputhash FROM math WHERE math_inputhash = '$md5_sql'";
	} else {
		$sql = "SELECT math_outputhash,math_html_conservativeness,math_html FROM math WHERE math_inputhash = '$md5_sql'";
	}

	$res = wfQuery( $sql, DB_READ, $fname );

	if( $rpage = wfFetchObject ( $res ) ) {
		$outputhash = unpack( "H32md5", $rpage->math_outputhash . "                " );
		$outputhash = $outputhash ['md5'];
		if( file_exists( "$wgMathDirectory/$outputhash.png" ) ) {
			if (($math == 0) || ($rpage->math_html == '') || (($math == 1) && ($rpage->math_html_conservativeness != 2)) || (($math == 4) && ($rpage->math_html_conservativeness == 0))) {
				return linkToMathImage ( $tex, $outputhash );
			} else {
				return $rpage->math_html;
			}
		}
	}
	
	# Ensure that the temp and output directories are available before continuing...
	if( !file_exists( $wgMathDirectory ) ) {
		if( !@mkdir( $wgMathDirectory ) ) {
			return "<b>$mf (" . wfMsg( "math_bad_output" ) . ")</b>";
		}
	} elseif( !is_dir( $wgMathDirectory ) || !is_writable( $wgMathDirectory ) ) {
		return "<b>$mf (" . wfMsg( "math_bad_output" ) . ")</b>";
	}
	if( !file_exists( $wgTmpDirectory ) ) {
		if( !@mkdir( $wgTmpDirectory ) ) {
			return "<b>$mf (" . wfMsg( "math_bad_tmpdir" ) . ")</b>";
		}
	} elseif( !is_dir( $wgTmpDirectory ) || !is_writable( $wgTmpDirectory ) ) {
		return "<b>$mf (" . wfMsg( "math_bad_tmpdir" ) . ")</b>";
	}
	
	if( !is_executable( $wgTexvc ) ) {
		return "<b>$mf (" . wfMsg( "math_notexvc" ) . ")</b>";
	}
	$cmd = $wgTexvc." ".
		escapeshellarg($wgTmpDirectory)." ".
		escapeshellarg($wgMathDirectory)." ".
		escapeshellarg($tex)." ".
		escapeshellarg($wgInputEncoding);
	wfDebug( "TeX: $cmd" );
	$contents = `$cmd`;

	if (strlen($contents) == 0) {
		return "<b>".$mf." (".$munk."): ".wfEscapeHTML($tex)."</b>";
	}
	
	$retval = substr ($contents, 0, 1);
	if (($retval == "C") || ($retval == "M") || ($retval == "L")) {
	    if ($retval == "C")
		$conservativeness = 2;
	    else if ($retval == "M")
		$conservativeness = 1;
	    else
		$conservativeness = 0;
	    $outdata = substr ($contents, 33);

	    $i = strpos($outdata, "\000");

	    $outhtml = substr($outdata, 0, $i);
	    $mathml = substr($outdata, $i+1);

	    $sql_html = "'".mysql_escape_string($outhtml)."'";
	    $sql_mathml = "'".mysql_escape_string($mathml)."'";
	} else if (($retval == "c") || ($retval == "m") || ($retval == "l"))  {
	    $outhtml = substr ($contents, 33);
	    if ($retval == "c")
		$conservativeness = 2;
	    else if ($retval == "m")
		$conservativeness = 1;
	    else
		$conservativeness = 0;
	    $sql_html = "'".mysql_escape_string($outhtml)."'";
	    $mathml = '';
	    $sql_mathml = 'NULL';
	} else if ($retval == "X") {
	    $outhtml = '';
	    $mathml = substr ($contents, 33);
	    $sql_html = 'NULL';
	    $sql_mathml = "'".mysql_escape_string($mathml)."'";
	    $conservativeness = 0;
	} else if ($retval == "+") {
	    $outhtml = '';
	    $mathml = '';
	    $sql_html = 'NULL';
	    $sql_mathml = 'NULL';
	    $conservativeness = 0;
	} else {
	    if ($retval == "E")
		$errmsg = wfMsg( "math_lexing_error" );
	    else if ($retval == "S")
		$errmsg = wfMsg( "math_syntax_error" );
	    else if ($retval == "F")
		$errmsg = wfMsg( "math_unknown_function" );
	    else
		$errmsg = $munk;
	    return "<h3>".$mf." (".$errmsg.substr($contents, 1)."): ".wfEscapeHTML($tex)."</h3>";
	}

	$outmd5 = substr ($contents, 1, 32);
	if (!preg_match("/^[a-f0-9]{32}$/", $outmd5)) {
		return "<b>".$mf." (".$munk."): ".wfEscapeHTML($tex)."</b>";
	}

	if( !file_exists( "$wgMathDirectory/$outmd5.png" ) ) {
		$errmsg = wfMsg( "math_image_error" );
		return "<h3>$mf ($errmsg): " . wfEscapeHTML($tex) . "</h3>";
	}

	$outmd5_sql = mysql_escape_string(pack("H32", $outmd5));

	$sql = "REPLACE INTO math VALUES ('".$md5_sql."', '".$outmd5_sql."', ".$conservativeness.", ".$sql_html.", ".$sql_mathml.")";
	
	$res = wfQuery( $sql, DB_READ, $fname );
	# we don't really care if it fails

	if (($math == 0) || ($rpage->math_html == '') || (($math == 1) && ($conservativeness != 2)) || (($math == 4) && ($conservativeness == 0)))
	    return linkToMathImage($tex, $outmd5);
	else
	    return $outhtml;
}

?>
