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

include_once ( "SpecialValidate.php" ) ;
 
class PageHistory {
	var $mArticle, $mTitle, $mSkin;
	var $lastdate;
	var $linesonpage;
	function PageHistory( $article ) {
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
	}

	# This shares a lot of issues (and code) with Recent Changes

	function history() {
		global $wgUser, $wgOut, $wgLang, $wgShowUpdatedMarker, $wgRequest,
			$wgTitle, $wgUseValidation ;

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

		$id = $this->mTitle->getArticleID();
		if( $id == 0 ) {
			$wgOut->addHTML( wfMsg( 'nohistory' ) );
			wfProfileOut( $fname );
			return;
		}

		$limit = $wgRequest->getInt('limit');
		if (!$limit) $limit = 50;
		$offset = $wgRequest->getText('offset');
		if (!isset($offset) || !preg_match("/^[0-9]+$/", $offset)) $offset = 0;

		/* Check one extra row to see whether we need to show 'next' and diff links */
		$limitplus = $limit + 1;

		$namespace = $this->mTitle->getNamespace();
		$title = $this->mTitle->getText();
		$uid = $wgUser->getID();
		$db =& wfGetDB( DB_SLAVE );
		if ($uid && $wgShowUpdatedMarker && $wgUser->getOption( 'showupdated' ))
			$notificationtimestamp = $db->selectField( 'watchlist',
				'wl_notificationtimestamp',
				array( 'wl_namespace' => $namespace, 'wl_title' => $this->mTitle->getDBkey(), 'wl_user' => $uid ),
				$fname );
		else $notificationtimestamp = false;

		$use_index = $db->useIndexClause( 'page_timestamp' );
		$revision = $db->tableName( 'revision' );

		$limits = $offsets = "";
		$dir = 0;
		if ($wgRequest->getText("dir") == "prev")
			$dir = 1;

		list($dirs, $oper) = array("DESC", "<");
		if ($dir) {
			list($dirs, $oper) = array("ASC", ">");
		}

		if ($offset)
			$offsets .= " AND rev_timestamp $oper '$offset' ";
		if ($limit)
			$limits .= " LIMIT $limitplus ";

		$sql = "SELECT rev_id,rev_user," .
		  "rev_comment,rev_user_text,rev_timestamp,rev_minor_edit,rev_deleted ".
		  "FROM $revision $use_index " .
		  "WHERE rev_page=$id " .
		  $offsets .
		  "ORDER BY rev_timestamp $dirs " .
		  $limits;
		$res = $db->query( $sql, $fname );

		$revs = $db->numRows( $res );

		if( $revs < $limitplus ) // the sql above tries to fetch one extra
			$this->linesonpage = $revs;
		else
			$this->linesonpage = $revs - 1;

		$atend = ($revs < $limitplus);

		$this->mSkin = $wgUser->getSkin();

		$pages = array();
		$lowts = 0;
		while ($line = $db->fetchObject($res)) {
			$pages[] = $line;
		}
		if ($dir) $pages = array_reverse($pages);
		if (count($pages) > 1)
			$lowts = $pages[count($pages) - 2]->rev_timestamp;
		else
			$lowts = $pages[count($pages) - 1]->rev_timestamp;


		$prevurl = $wgTitle->escapeLocalURL("action=history&dir=prev&offset={$offset}&limit={$limit}");
		$nexturl = $wgTitle->escapeLocalURL("action=history&offset={$lowts}&limit={$limit}");
		$urls = array();
		foreach (array(20, 50, 100, 250, 500) as $num) {
			$urls[] = "<a href=\"".$wgTitle->escapeLocalURL(
				"action=history&offset={$offset}&limit={$num}")."\">".$wgLang->formatNum($num)."</a>";
		}
		$bits = implode($urls, ' | ');
		$numbar = wfMsg("viewprevnext", 
				"<a href=\"$prevurl\">".wfMsg("prevn", $limit)."</a>", 
				"<a href=\"$nexturl\">".wfMsg("nextn", $limit)."</a>", 
				$bits);

		$s = $numbar;
		$s .= $this->beginHistoryList();
		$counter = 1;
		foreach($pages as $i => $line) {
			$first = ($counter == 1 && $offset == 0);
			$next = isset( $pages[$i + 1] ) ? $pages[$i + 1 ] : null;
			$s .= $this->historyLine( $line, $next, $counter, $notificationtimestamp, $first );
			$counter++;
		}
		$s .= $this->endHistoryList( !$atend );
		$s .= $numbar;
		
		# Validation line
		if ( isset ( $wgUseValidation ) && $wgUseValidation ) {
			$s .= "<p>" . Validation::link2statistics ( $this->mArticle ) . "</p>" ;
			}
		
		$wgOut->addHTML( $s );
		wfProfileOut( $fname );
	}

	function beginHistoryList() {
		global $wgTitle;
		$this->lastdate = '';
		$s = '<p>' . wfMsg( 'histlegend' ) . '</p>';
		$s .= '<form action="' . $wgTitle->escapeLocalURL( '-' ) . '" method="get">';
		$prefixedkey = htmlspecialchars($wgTitle->getPrefixedDbKey());
		$s .= "<input type='hidden' name='title' value=\"{$prefixedkey}\" />\n";
		$s .= $this->submitButton();
		$s .= '<ul id="pagehistory">';
		return $s;
	}

	function endHistoryList() {
		$last = wfMsg( 'last' );

		$s = '</ul>';
		$s .= $this->submitButton( array( 'id' => 'historysubmit' ) );
		$s .= '</form>';
		return $s;
	}
	
	function submitButton( $bits = array() ) {
		return ( $this->linesonpage > 0 )
			? wfElement( 'input', array_merge( $bits,
				array(
					'class'     => 'historysubmit',
					'type'      => 'submit',
					'accesskey' => wfMsg( 'accesskey-compareselectedversions' ),
					'title'     => wfMsg( 'tooltip-compareselectedversions' ),
					'value'     => wfMsg( 'compareselectedversions' ),
				) ) )
			: '';
	}

	function historyLine( $row, $next, $counter = '', $notificationtimestamp = false, $latest = false ) {
		global $wgLang, $wgContLang;

		static $message;
		if( !isset( $message ) ) {
			foreach( explode( ' ', 'cur last selectolderversionfordiff selectnewerversionfordiff minoreditletter' ) as $msg ) {
				$message[$msg] = wfMsg( $msg );
			}
		}
		
		$link = $this->revLink( $row );

		if ( 0 == $row->rev_user ) {
			$contribsPage =& Title::makeTitle( NS_SPECIAL, 'Contributions' );
			$ul = $this->mSkin->makeKnownLinkObj( $contribsPage,
				htmlspecialchars( $row->rev_user_text ),
				'target=' . urlencode( $row->rev_user_text ) );
		} else {
			$userPage =& Title::makeTitle( NS_USER, $row->rev_user_text );
			$ul = $this->mSkin->makeLinkObj( $userPage , htmlspecialchars( $row->rev_user_text ) );
		}

		$s = '<li>';
		if( $row->rev_deleted ) {
			$s .= '<span class="deleted">';
		}
		$curlink = $this->curLink( $row, $latest );
		$lastlink = $this->lastLink( $row, $next, $counter );
		$arbitrary = $this->diffButtons( $row, $latest, $counter );
		$s .= "({$curlink}) ({$lastlink}) $arbitrary {$link} <span class='user'>{$ul}</span>";

		if( $row->rev_minor_edit ) {
			$s .= ' ' . wfElement( 'span', array( 'class' => 'minor' ), $message['minoreditletter'] );
		}


		$s .= $this->mSkin->commentBlock( $row->rev_comment, $this->mTitle );
		if ($notificationtimestamp && ($row->rev_timestamp >= $notificationtimestamp)) {
			$s .= wfMsg( 'updatedmarker' );
		}
		if( $row->rev_deleted ) {
			$s .= "</span> " . htmlspecialchars( wfMsg( 'deletedrev' ) );
		}
		$s .= '</li>';

		return $s;
	}

	function revLink( $row ) {
		global $wgUser, $wgLang;
		$date = $wgLang->timeanddate( $row->rev_timestamp, true );
		if( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) {
			return $date;
		} else {
			return $this->mSkin->makeKnownLinkObj(
				$this->mTitle,
				$date,
				'oldid='.$row->rev_id );
		}
	}
	
	function curLink( $row, $latest ) {
		global $wgUser;
		$cur = htmlspecialchars( wfMsg( 'cur' ) );
		if( $latest
			|| ( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) ) {
			return $cur;
		} else {
			return $this->mSkin->makeKnownLinkObj(
				$this->mTitle,
				$cur,
				'diff=0&oldid=' . $row->rev_id );
		}
	}
	
	function lastLink( $row, $next, $counter ) {
		global $wgUser;
		$last = htmlspecialchars( wfMsg( 'last' ) );
		if( is_null( $next )
			|| ( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) ) {
			return $last;
		} else {
			return $this->mSkin->makeKnownLinkObj(
			  $this->mTitle,
			  $last,
			  "diff={$next->rev_id}&oldid={$row->rev_id}",
			  '',
			  '',
			  ' tabindex="'.$counter.'"' );
		}
	}
	
	function diffButtons( $row, $latest, $counter ) {
		global $wgUser;
		if( $this->linesonpage > 1) {
			$radio = array(
				'type'  => 'radio',
				'value' => $row->rev_id,
				'title' => wfMsg( 'selectolderversionfordiff' )
			);
			if( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) {
				$radio['disabled'] = 'disabled';
			}
			
			# XXX: move title texts to javascript
			if ( $latest ) {
				$first = wfElement( 'input', array_merge(
					$radio,
					array(
						'style' => 'visibility:hidden',
						'name'  => 'oldid' ) ) );
				$checkmark = array( 'checked' => 'checked' );
			} else {
				if( $counter == 2 ) {
					$checkmark = array( 'checked' => 'checked' );
				} else {
					$checkmark = array();
				}
				$first = wfElement( 'input', array_merge(
					$radio,
					$checkmark,
					array( 'name'  => 'oldid' ) ) );
				$checkmark = array();
			}
			$second = wfElement( 'input', array_merge(
				$radio,
				$checkmark,
				array( 'name'  => 'diff' ) ) );
			return $first . $second;
		} else {
			return '';
		}
	}
	
}

?>
