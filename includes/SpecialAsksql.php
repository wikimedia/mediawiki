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

		$wgOut->setPagetitle( wfMsg( "asksql" ) );
		$wgOut->addWikiText( wfMsg( "asksqltext" ) );

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
		global $wgOut, $wgUser, $wgServer, $wgScript, $wgArticlePath;
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
		$res = wfQuery( $wpSqlQuery, "SpecialAsksql::doSubmit" );

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
					if ( $x == "cur_title" or $x == "old_title" ) {
						$o = str_replace ( "$1" , rawurlencode( $o ) , $wgArticlePath ) ;
						$o = "<a href=\"{$o}\" class='internal'>" .
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

}

?>
