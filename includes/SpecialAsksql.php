<?

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
		$action = wfLocalUrlE( $wgLang->specialPage( "Asksql" ),
		  "action=submit" );

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
		global $wgDBsqluser, $wgDBsqlpassword;

		# Use a limit, folks!
		$wpSqlQuery = trim( $wpSqlQuery );
		if( preg_match( "/^SELECT/i", $wpSqlQuery )
			and !preg_match( "/LIMIT/i", $wpSqlQuery ) ) {
			$wpSqlQuery .= " LIMIT 100";
		}
		if ( ! $wgUser->isDeveloper() ) {
			$connection = wfGetDB( $wgDBsqluser, $wgDBsqlpassword );
		}
		$this->logQuery( $wpSqlQuery );
		$res = wfQuery( $wpSqlQuery, DB_WRITE, "SpecialAsksql::doSubmit" );
		$this->logFinishedQuery();

		$n = 0;
		@$n = wfNumFields( $res );
		if ( $n ) {
			$k = array();
			for ( $x = 0; $x < $n; ++$x ) {
				array_push( $k, wfFieldName( $res, $x ) );
			}
			$a = array();
			while ( $s = wfFetchObject( $res ) ) {
				array_push( $a, $s );
			}
			wfFreeResult( $res );

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
		$this->starttime = microtime();
	}
	
	function logFinishedQuery() {
		global $wgSqlLogFile, $wgLogQueries;
		if(!$wgLogQueries) return;
		
		list($sec, $usec) = explode( " ", microtime() );
		list($sec1, $usec1) = explode( " ", $this->starttime );
		$interval = ($sec + $usec) - ($sec1 + $usec1);
		
		$f = fopen( $wgSqlLogFile, "a" );
		fputs( $f, "finished at " . wfTimestampNow() . "; took $interval secs\n" );
		fclose( $f );
	}

}

?>
