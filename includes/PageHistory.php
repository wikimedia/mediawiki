<?php

/* Page history
   Split off from Article.php and Skin.php, 2003-12-22
*/

class PageHistory {
	var $mArticle, $mTitle, $mSkin;
	var $lastline, $lastdate;
	var $linesonpage;
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
		$fname = 'PageHistory::history';
		wfProfileIn( $fname );

		$wgOut->setPageTitle( $this->mTitle->getPRefixedText() );
		$wgOut->setSubtitle( wfMsg( 'revhistory' ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setArticleRelated( true );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		if( $this->mTitle->getArticleID() == 0 ) {
			$wgOut->addHTML( wfMsg( 'nohistory' ) );
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
		
		$db =& wfGetDB( DB_SLAVE );
		$use_index = $db->useIndexClause( 'name_title_timestamp' );
		$oldtable = $db->tableName( 'old' );

		$sql = "SELECT old_id,old_user," .
		  "old_comment,old_user_text,old_timestamp,old_minor_edit ".
		  "FROM $oldtable $use_index " .
		  "WHERE old_namespace={$namespace} AND " .
		  "old_title='" . $db->strencode( $this->mTitle->getDBkey() ) . "' " .
		  "ORDER BY inverse_timestamp".$db->limitResult($limitplus,$rawoffset);
		$res = $db->query( $sql, $fname );

		$revs = $db->numRows( $res );
		
		if( $revs < $limitplus ) // the sql above tries to fetch one extra
			$this->linesonpage = $revs;
		else
			$this->linesonpage = $revs - 1;

		$atend = ($revs < $limitplus);
		
		$this->mSkin = $wgUser->getSkin();
		$numbar = wfViewPrevNext(
			$offset, $limit,
			$this->mTitle->getPrefixedText(),
			'action=history', $atend );
		$s = $numbar;
		if($this->linesonpage > 0) {
			$submitpart1 = '<input class="historysubmit" type="submit" accesskey="'.wfMsg('accesskey-compareselectedversions').
			'" title="'.wfMsg('tooltip-compareselectedversions').'" value="'.wfMsg('compareselectedversions').'"';
			$this->submitbuttonhtml1 = $submitpart1 . ' />';
			$this->submitbuttonhtml2 = $submitpart1 . ' id="historysubmit" />';
		}
		$s .= $this->beginHistoryList();
		$counter = 1;
		if( $offset == 0 ){
			$this->linesonpage++;
			$s .= $this->historyLine( 
				$this->mArticle->getTimestamp(), 
				$this->mArticle->getUser(),
				$this->mArticle->getUserText(), $namespace,
				$title, 0, $this->mArticle->getComment(),
				( $this->mArticle->getMinorEdit() > 0 ),
				$counter++
			);
		}
		while ( $line = $db->fetchObject( $res ) ) {
			$s .= $this->historyLine( 
				$line->old_timestamp, $line->old_user,
				$line->old_user_text, $namespace,
				$title, $line->old_id,
				$line->old_comment, ( $line->old_minor_edit > 0 ),
				$counter++
			);
		}
		$s .= $this->endHistoryList( !$atend );
		$s .= $numbar;
		$wgOut->addHTML( $s );
		wfProfileOut( $fname );
	}

	function beginHistoryList()
	{
		global $wgTitle;
		$this->lastdate = $this->lastline = '';
		$s = "\n<p>" . wfMsg( 'histlegend' ).'</p>'; 
		$s .="\n<form action=\"" . $wgTitle->escapeLocalURL( '-' ) . "\" method=\"get\">";
		$s .= "<input type=\"hidden\" name=\"title\" value=\"".htmlspecialchars($wgTitle->getPrefixedDbKey())."\"/>\n";
		$s .= !empty($this->submitbuttonhtml1) ? $this->submitbuttonhtml1."\n":'';
		$s .= "" . "\n<ul id=\"pagehistory\" >";
		return $s;
	}

	function endHistoryList( $skip = false )
	{
		$last = wfMsg( 'last' );

		$s = $skip ? '' : preg_replace( "/!OLDID![0-9]+!/", $last, $this->lastline );
		$s .= "</ul>";
		$s .= !empty($this->submitbuttonhtml2) ? $this->submitbuttonhtml2."\n":'';
		$s .= "</form>\n";
		return $s;
	}

	function historyLine( $ts, $u, $ut, $ns, $ttl, $oid, $c, $isminor, $counter = '' )
	{
		global $wgLang;

		$artname = Title::makeName( $ns, $ttl );
		$last = wfMsg( 'last' );
		$cur = wfMsg( 'cur' );
		$cr = wfMsg( 'currentrev' );

		if ( $oid && $this->lastline ) {
			$ret = preg_replace( "/!OLDID!([0-9]+)!/", $this->mSkin->makeKnownLink(
			  $artname, $last, "diff=\\1&oldid={$oid}",'' ,'' ,' tabindex="'.$counter.'"' ), $this->lastline );
		} else {
			$ret = '';
		}
		$dt = $wgLang->timeanddate( $ts, true );

		if ( $oid ) {
			$q = 'oldid='.$oid;
		} else {
			$q = '';
		}
		$link = $this->mSkin->makeKnownLink( $artname, $dt, $q );

		if ( 0 == $u ) {
			$ul = $this->mSkin->makeKnownLink( $wgLang->specialPage( 'Contributions' ),
				htmlspecialchars( $ut ), 'target=' . urlencode( $ut ) );
		} else { 
			$ul = $this->mSkin->makeLink( $wgLang->getNsText(
				Namespace::getUser() ) . ':'.$ut , htmlspecialchars( $ut ) );
		}

		$s = '<li>';
		if ( $oid ) {
			$curlink = $this->mSkin->makeKnownLink( $artname, $cur,
			  'diff=0&oldid='.$oid );
		} else {
			$curlink = $cur;
		}
		$arbitrary = '';
		if( $this->linesonpage > 1) {
			# XXX: move title texts to javascript
			$checkmark = '';
			if ( !$oid ) {
				$arbitrary = '<input type="radio" style="visibility:hidden" name="oldid" value="'.$oid.'" title="'.wfMsg('selectolderversionfordiff').'" />';
				$checkmark = ' checked="checked"';
			} else {
				if( $counter == 2 ) $checkmark = ' checked="checked"';
				$arbitrary = '<input type="radio" name="oldid" value="'.$oid.'" title="'.wfMsg('selectolderversionfordiff').'"'.$checkmark.' />';
				$checkmark = '';
			}
			$arbitrary .= '<input type="radio" name="diff" value="'.$oid.'" title="'.wfMsg('selectnewerversionfordiff').'"'.$checkmark.' />';
		}
		$s .= "({$curlink}) (!OLDID!{$oid}!) $arbitrary {$link} <span class='user'>{$ul}</span>";
		$s .= $isminor ? ' <span class="minor">'.wfMsg( "minoreditletter" ).'</span>': '' ;
		

		if ( '' != $c && '*' != $c ) {
			$c = $this->mSkin->formatcomment($c,$this->mTitle);
			$s .= " <em>(" . $c . ")</em>";
		}
		$s .= "</li>\n";

		$this->lastline = $s;
		return $ret;
	}

}

?>
