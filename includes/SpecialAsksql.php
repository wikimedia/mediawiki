<?php

function wfSpecialAsksql()
{
	global $wgUser, $wgOut, $action;

	if ( ! $wgUser->isSysop() ) {
		$wgOut->sysopRequired();
		return;
	}
	$fields = array( "wpSqlQuery" );
	wfCleanFormFields( $fields );
	$f = new SqlQueryForm();

	if ( "submit" == $action ) { $f->doSubmit(); }
	else { $f->showForm( "" ); }
}

class SqlQueryForm {

	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpSqlQuery;
		global $wgLogQueries;

		$wgOut->setPagetitle( wfMsg( "asksql" ) );
		$note = wfMsg( "asksqltext" );
		if($wgLogQueries)
			$note .= " " . wfMsg( "sqlislogged" );
		$wgOut->addWikiText( $note );

		if ( "" != $err ) {
			$wgOut->addHTML( "<p><font color='red' size='+1'>" . htmlspecialchars($err) . "</font>\n" );
		}
		if ( ! $wpSqlQuery ) { $wpSqlQuery = "SELECT ... FROM ... WHERE ..."; }
		$q = wfMsg( "sqlquery" );
		$qb = wfMsg( "querybtn" );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Asksql" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		$wgOut->addHTML( "<p>
<form id=\"asksql\" method=\"post\" action=\"{$action}\">
<table border=0><tr>
<td align=right>{$q}:</td>
<td align=left>
<textarea name=\"wpSqlQuery\" cols=80 rows=4 wrap=\"virtual\">"
. htmlspecialchars($wpSqlQuery) ."
</textarea>
</td>
</tr><tr>
<td>&nbsp;</td><td align=\"left\">
<input type=submit name=\"wpQueryBtn\" value=\"{$qb}\">
</td></tr></table>
</form>\n" );

	}

	function doSubmit()
	{
		global $wgOut, $wgUser, $wgServer, $wgScript, $wgArticlePath, $wgLang;
		global $wpSqlQuery;
		global $wgDBserver, $wgDBsqluser, $wgDBsqlpassword, $wgDBname, $wgSqlTimeout;

		# Use a limit, folks!
		$wpSqlQuery = trim( $wpSqlQuery );
		if( preg_match( "/^SELECT/i", $wpSqlQuery )
			and !preg_match( "/LIMIT/i", $wpSqlQuery ) ) {
			$wpSqlQuery .= " LIMIT 100";
		}
		$conn = Database::newFromParams( $wgDBserver, $wgDBsqluser, $wgDBsqlpassword, $wgDBname );

		$this->logQuery( $wpSqlQuery );

		# Start timer, will kill the DB thread in $wgSqlTimeout seconds
		$conn->startTimer( $wgSqlTimeout );
		$res = $conn->query( $wpSqlQuery, "SpecialAsksql::doSubmit" );
		$conn->stopTimer();
		$this->logFinishedQuery();

		$n = 0;
		@$n = $conn->numFields( $res );
		$titleList = false;

		if ( $n ) {
			$k = array();
			for ( $x = 0; $x < $n; ++$x ) {
				array_push( $k, $conn->fieldName( $res, $x ) );
			}

			if ( $n == 2 && in_array( "cur_title", $k ) && in_array( "cur_namespace", $k ) ) {
				$titleList = true;
			}

			$a = array();
			while ( $s = $conn->fetchObject( $res ) ) {
				array_push( $a, $s );
			}
			$conn->freeResult( $res );

			if ( $titleList ) {
				$r = "";
				foreach ( $a as $y ) {
					$sTitle = htmlspecialchars( $y->cur_title );
					if ( $y->cur_namespace ) {
						$sNamespace = $wgLang->getNsText( $y->cur_namespace );
						$link = "$sNamespace:$sTitle";
					} else {
						$link = "$sTitle";
					}
					$skin = $wgUser->getSkin();
					$link = $skin->makeLink( $link );
					$r .= "* [[$link]]<br>\n";	
				}
			} else {

				$r = "<table border=1 bordercolor=black cellspacing=0 " .
				  "cellpadding=2><tr>\n";
				foreach ( $k as $x ) $r .= "<th>" . htmlspecialchars( $x ) . "</th>";
				$r .= "</tr>\n";

				foreach ( $a as $y ) {
					$r .= "<tr>";
					foreach ( $k as $x ) {
						$o = $y->$x ;
						if ( $x == "cur_title" or $x == "old_title" or $x == "rc_title") {
							$namespace = 0;
							if( $x == "cur_title" ) $namespace = $y->cur_namespace;
							if( $x == "old_title" ) $namespace = $y->old_namespace;
							if( $x == "rc_title" ) $namespace = $y->rc_namespace;
							if( $namespace ) $o = $wgLang->getNsText( $namespace ) . ":" . $o;
							$o = "<a href=\"" . wfLocalUrlE($o) . "\" class='internal'>" .
							  htmlspecialchars( $y->$x ) . "</a>" ;
							} else {
							$o = htmlspecialchars( $o );
							}
						$r .= "<td>" . $o . "</td>\n";
					}
					$r .= "</tr>\n";
				}
				$r .= "</table>\n";
			}
		}
		$this->showForm( wfMsg( "querysuccessful" ) );
		$wgOut->addHTML( "<hr>{$r}\n" );
	}

	function logQuery( $q ) {
		global $wgSqlLogFile, $wgLogQueries, $wgUser;
		if(!$wgLogQueries) return;
		
		$f = fopen( $wgSqlLogFile, "a" );
		fputs( $f, "\n\n" . wfTimestampNow() .
			" query by " . $wgUser->getName() .
			":\n$q\n" );
		fclose( $f );
		$this->starttime = wfTime();
	}
	
	function logFinishedQuery() {
		global $wgSqlLogFile, $wgLogQueries;
		if(!$wgLogQueries) return;
		
		$interval = wfTime() - $this->starttime;
		
		$f = fopen( $wgSqlLogFile, "a" );
		fputs( $f, "finished at " . wfTimestampNow() . "; took $interval secs\n" );
		fclose( $f );
	}

}

?>
