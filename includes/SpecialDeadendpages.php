<?

function wfSpecialDeadendpages()
{
    global $wgUser, $wgOut, $wgLang, $wgTitle;
    $fname = "wfSpecialDeadendpages";
    
    # Cache
    $vsp = $wgLang->getValidSpecialPages();
    $log = new LogPage( $vsp["Deadendpages"] );
    $log->mUpdateRecentChanges = false;
    
    global $wgMiserMode;
    if ( $wgMiserMode ) {
	$log->showAsDisabledPage();
	return;
    }
    
    list( $limit, $offset ) = wfCheckLimits();

    # Note: title is only the same as l_from for main namespace, 
    # but that's what we want, anyways

    # XXX: Left joins are losey
    
    $sql = "SELECT cur_title " . 
      "FROM cur LEFT JOIN links ON cur_title = l_from " .
      "WHERE l_from IS NULL " .
      "AND cur_namespace = 0 " .
      "ORDER BY cur_title " . 
      "LIMIT {$offset}, {$limit}";
    
    $res = wfQuery( $sql, DB_READ, $fname );
    
    $sk = $wgUser->getSkin();
    
    $top = wfShowingResults( $offset, $limit );
    $wgOut->addHTML( "<p>{$top}\n" );
    
    $sl = wfViewPrevNext( $offset, $limit,
			  $wgLang->specialPage( "Deadendpages" ) );
    $wgOut->addHTML( "<br>{$sl}\n" );
    
    $s = "<ol start=" . ( $offset + 1 ) . ">";
    while ( $obj = wfFetchObject( $res ) ) {
	$link = $sk->makeKnownLink( $obj->cur_title, "" );
	$s .= "<li>{$link}</li>\n";
    }
    wfFreeResult( $res );
    $s .= "</ol>";
    $wgOut->addHTML( $s );
    $wgOut->addHTML( "<p>{$sl}\n" );
    
    # Saving cache
    if ( $offset > 0 OR $limit < 50 ) return ; #Not suitable
	$log->replaceContent( $s );
}

?>
