<?php
/**
 * Page history
 * 
 * Split off from Article.php and Skin.php, 2003-12-22
 * @package MediaWiki
 */

/**
 * @todo document
 * @package MediaWiki
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

	function history() {
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

	function beginHistoryList() {
		global $wgTitle;
		$this->lastdate = $this->lastline = '';
		$s = '<p>' . wfMsg( 'histlegend' ) . '</p>';
		$s .= '<form action="' . $wgTitle->escapeLocalURL( '-' ) . '" method="get">';
		$prefixedkey = htmlspecialchars($wgTitle->getPrefixedDbKey());
		$s .= "<input type='hidden' name='title' value=\"{$prefixedkey}\" />\n";
		$s .= !empty($this->submitbuttonhtml1) ? $this->submitbuttonhtml1."\n":'';
		$s .= '<ul id="pagehistory">';
		return $s;
	}

	function endHistoryList( $skip = false ) {
		$last = wfMsg( 'last' );

		$s = $skip ? '' : preg_replace( "/!OLDID![0-9]+!/", $last, $this->lastline );
		$s .= '</ul>';
		$s .= !empty($this->submitbuttonhtml2) ? $this->submitbuttonhtml2 : '';
		$s .= '</form>';
		return $s;
	}

	function historyLine( $ts, $u, $ut, $ns, $ttl, $oid, $c, $isminor, $counter = '' ) {
		global $wgLang, $wgContLang;

		static $message;
		if( !isset( $message ) ) {
			foreach( explode( ' ', 'cur last selectolderversionfordiff selectnewerversionfordiff minoreditletter' ) as $msg ) {
				$message[$msg] = wfMsg( $msg );
			}
		}
		
		if ( $oid && $this->lastline ) {
			$ret = preg_replace( "/!OLDID!([0-9]+)!/", $this->mSkin->makeKnownLinkObj(
			  $this->mTitle, $message['last'], "diff=\\1&oldid={$oid}",'' ,'' ,' tabindex="'.$counter.'"' ), $this->lastline );
		} else {
			$ret = '';
		}
		$dt = $wgLang->timeanddate( $ts, true );

		if ( $oid ) {
			$q = 'oldid='.$oid;
		} else {
			$q = '';
		}
		$link = $this->mSkin->makeKnownLinkObj( $this->mTitle, $dt, $q );

		if ( 0 == $u ) {
			$contribsPage =& Title::makeTitle( NS_SPECIAL, 'Contributions' );
			$ul = $this->mSkin->makeKnownLinkObj( $contribsPage,
				htmlspecialchars( $ut ), 'target=' . urlencode( $ut ) );
		} else {
			$userPage =& Title::makeTitle( NS_USER, $ut );
			$ul = $this->mSkin->makeLink( $userPage , htmlspecialchars( $ut ) );
		}

		$s = '<li>';
		if ( $oid ) {
			$curlink = $this->mSkin->makeKnownLinkObj( $this->mTitle, $message['cur'],
			  'diff=0&oldid='.$oid );
		} else {
			$curlink = $message['cur'];
		}
		$arbitrary = '';
		if( $this->linesonpage > 1) {
			# XXX: move title texts to javascript
			$checkmark = '';
			if ( !$oid ) {
				$arbitrary = '<input type="radio" style="visibility:hidden" name="oldid" value="'.$oid.'" title="'.$message['selectolderversionfordiff'].'" />';
				$checkmark = ' checked="checked"';
			} else {
				if( $counter == 2 ) $checkmark = ' checked="checked"';
				$arbitrary = '<input type="radio" name="oldid" value="'.$oid.'" title="'.$message['selectolderversionfordiff'].'"'.$checkmark.' />';
				$checkmark = '';
			}
			$arbitrary .= '<input type="radio" name="diff" value="'.$oid.'" title="'.$message['selectnewerversionfordiff'].'"'.$checkmark.' />';
		}
		$s .= "({$curlink}) (!OLDID!{$oid}!) $arbitrary {$link} <span class='user'>{$ul}</span>";
		$s .= $isminor ? ' <span class="minor">'.$message['minoreditletter'].'</span>': '' ;


		if ( '' != $c && '*' != $c ) {
			$c = $this->mSkin->formatcomment( $c, $this->mTitle );
			$s .= " <em>($c)</em>";
		}
		$s .= '</li>';

		$this->lastline = $s;
		return $ret;
	}

}

?>
