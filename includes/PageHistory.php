<?php

/* Page history
   Split off from Article.php and Skin.php, 2003-12-22
*/

class PageHistory {
	var $mArticle, $mTitle, $mSkin;
	var $lastline, $lastdate;
	
	function PageHistory( $article ) {
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
	}
	
	# This shares a lot of issues (and code) with Recent Changes

	function history()
	{
		global $wgUser, $wgOut, $wgLang;

		# If page hasn't changed, client can cache this
		
		if( $wgOut->checkLastModified( $this->mArticle->getTimestamp() ) ){
			# Client cache fresh and headers sent, nothing more to do.
			return;
		}
		$fname = "PageHistory::history";
		wfProfileIn( $fname );

		$wgOut->setPageTitle( $this->mTitle->getPRefixedText() );
		$wgOut->setSubtitle( wfMsg( "revhistory" ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		if( $this->mTitle->getArticleID() == 0 ) {
			$wgOut->addHTML( wfMsg( "nohistory" ) );
			wfProfileOut( $fname );
			return;
		}
		
		list( $limit, $offset ) = wfCheckLimits();
		
		/* We have to draw the latest revision from 'cur' */
		$rawlimit = $limit;
		$rawoffset = $offset - 1;
		if( 0 == $offset ) {
			$rawlimit--;
			$rawoffset = 0;
		}
		/* Check one extra row to see whether we need to show 'next' and diff links */
		$limitplus = $rawlimit + 1;
		
		$namespace = $this->mTitle->getNamespace();
		$title = $this->mTitle->getText();
		$sql = "SELECT old_id,old_user," .
		  "old_comment,old_user_text,old_timestamp,old_minor_edit ".
		  "FROM old USE INDEX (name_title_timestamp) " .
		  "WHERE old_namespace={$namespace} AND " .
		  "old_title='" . wfStrencode( $this->mTitle->getDBkey() ) . "' " .
		  "ORDER BY inverse_timestamp LIMIT $rawoffset, $limitplus";
		$res = wfQuery( $sql, DB_READ, $fname );

		$revs = wfNumRows( $res );
		$atend = ($revs < $limitplus);
		
		$this->mSkin = $wgUser->getSkin();
		$numbar = wfViewPrevNext(
			$offset, $limit,
			$this->mTitle->getPrefixedText(),
			"action=history" );
		$s = $numbar;
		$s .= $this->beginHistoryList();

		if( $offset == 0 )
		$s .= $this->historyLine( $this->mArticle->getTimestamp(), $this->mArticle->getUser(),
		  $this->mArticle->getUserText(), $namespace,
		  $title, 0, $this->mArticle->getComment(),
		  ( $this->mArticle->getMinorEdit() > 0 ) );

		while ( $line = wfFetchObject( $res ) ) {
			$s .= $this->historyLine( $line->old_timestamp, $line->old_user,
			  $line->old_user_text, $namespace,
			  $title, $line->old_id,
			  $line->old_comment, ( $line->old_minor_edit > 0 ) );
		}
		$s .= $this->endHistoryList( !$atend );
		$s .= $numbar;
		$wgOut->addHTML( $s );
		wfProfileOut( $fname );
	}

	function beginHistoryList()
	{
		$this->lastdate = $this->lastline = "";
		$s = "\n<p>" . wfMsg( "histlegend" ) . "\n<ul>";
		return $s;
	}

	function endHistoryList( $skip = false )
	{
		$last = wfMsg( "last" );

		$s = $skip ? "" : preg_replace( "/!OLDID![0-9]+!/", $last, $this->lastline );
		$s .= "</ul>\n";
		return $s;
	}

	function historyLine( $ts, $u, $ut, $ns, $ttl, $oid, $c, $isminor )
	{
		global $wgLang;

		$artname = Title::makeName( $ns, $ttl );
		$last = wfMsg( "last" );
		$cur = wfMsg( "cur" );
		$cr = wfMsg( "currentrev" );

		if ( $oid && $this->lastline ) {
			$ret = preg_replace( "/!OLDID!([0-9]+)!/", $this->mSkin->makeKnownLink(
			  $artname, $last, "diff=\\1&oldid={$oid}" ), $this->lastline );
		} else {
			$ret = "";
		}
		$dt = $wgLang->timeanddate( $ts, true );

		if ( $oid ) {
			$q = "oldid={$oid}";
		} else {
			$q = "";
		}
		$link = $this->mSkin->makeKnownLink( $artname, $dt, $q );

		if ( 0 == $u ) {
			$ul = $this->mSkin->makeKnownLink( $wgLang->specialPage( "Contributions" ),
				$ut, "target=" . $ut );
		} else { 
			$ul = $this->mSkin->makeLink( $wgLang->getNsText(
				Namespace::getUser() ) . ":{$ut}", $ut );
		}

		$s = "<li>";
		if ( $oid ) {
			$curlink = $this->mSkin->makeKnownLink( $artname, $cur,
			  "diff=0&oldid={$oid}" );
		} else {
			$curlink = $cur;
		}
		$s .= "({$curlink}) (!OLDID!{$oid}!) . .";

		$M = wfMsg( "minoreditletter" );
		if ( $isminor ) {
			$s .= " <strong>{$M}</strong>";
		}
		$s .= " {$link} . . {$ul}";

		if ( "" != $c && "*" != $c ) {
			$s .= " <em>(" . wfEscapeHTML($c) . ")</em>";
		}
		$s .= "</li>\n";

		$this->lastline = $s;
		return $ret;
	}

}

?>
