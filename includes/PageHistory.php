<?php
/**
 * Page history
 *
 * Split off from Article.php and Skin.php, 2003-12-22
 * @package MediaWiki
 */

/** */
include_once ( 'SpecialValidate.php' );

define('DIR_PREV', 0);
define('DIR_NEXT', 1);

/**
 * This class handles printing the history page for an article.  In order to
 * be efficient, it uses timestamps rather than offsets for paging, to avoid
 * costly LIMIT,offset queries.
 *
 * Construct it by passing in an Article, and call $h->history() to print the
 * history.
 *
 * @package MediaWiki
 */

class PageHistory {
	var $mArticle, $mTitle, $mSkin;
	var $lastdate;
	var $linesonpage;
	var $mNotificationTimestamp;

	/**
	 * Construct a new PageHistory.
	 *
	 * @param Article $article
	 * @returns nothing
	 */
	function PageHistory($article) {
		global $wgUser;

		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
		$this->mNotificationTimestamp = NULL;
		$this->mSkin = $wgUser->getSkin();
	}

	/**
	 * Print the history page for an article.
	 *
	 * @returns nothing
	 */
	function history() {
		global $wgUser, $wgOut, $wgLang, $wgShowUpdatedMarker, $wgRequest,
			$wgTitle, $wgUseValidation;

		/*
		 * Allow client caching.
		 */

		if( $wgOut->checkLastModified( $this->mArticle->getTimestamp() ) )
			/* Client cache fresh and headers sent, nothing more to do. */
			return;

		$fname = 'PageHistory::history';
		wfProfileIn( $fname );

		/*
		 * Setup page variables.
		 */
		$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( 'revhistory' ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setArticleRelated( true );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		/*
		 * Fail if article doesn't exist.
		 */
		$id = $this->mTitle->getArticleID();
		if( $id == 0 ) {
			$wgOut->addWikiText( wfMsg( 'nohistory' ) );
			wfProfileOut( $fname );
			return;
		}

		/*
		 * Extract limit, the number of revisions to show, and
		 * offset, the timestamp to begin at, from the URL.
		 */
		$limit = $wgRequest->getInt('limit', 50);
		$offset = $wgRequest->getText('offset');

		/* Offset must be an integral. */
		if (!strlen($offset) || !preg_match("/^[0-9]+$/", $offset))
			$offset = 0;

		/*
		 * "go=last" means to jump to the last history page.
		 */
		if (($gowhere = $wgRequest->getText("go")) !== NULL) {
			switch ($gowhere) {
			case "first":
				if (($lastid = $this->getLastOffsetForPaging($id, $limit)) === NULL)
					break;
				$gourl = $wgTitle->getLocalURL("action=history&limit={$limit}&offset={$lastid}");
				break;
			default:
				$gourl = NULL;
			}

			if (!is_null($gourl)) {
				$wgOut->redirect($gourl);
				return;
			}
		}

		/*
		 * Fetch revisions.
		 *
		 * If the user clicked "previous", we retrieve the revisions backwards,
		 * then reverse them.  This is to avoid needing to know the timestamp of
		 * previous revisions when generating the URL.
		 */
		$direction = $this->getDirection();
		$revisions = $this->fetchRevisions($limit, $offset, $direction);
		$navbar = $this->makeNavbar($revisions, $offset, $limit, $direction);

		/*
		 * We fetch one more revision than needed to get the timestamp of the
		 * one after this page (and to know if it exists).
		 *
		 * linesonpage stores the actual number of lines.
		 */
		if (count($revisions) < $limit + 1)
			$this->linesonpage = count($revisions);
		else
			$this->linesonpage = count($revisions) - 1;

		/* Un-reverse revisions */
		if ($direction == DIR_PREV)
			$revisions = array_reverse($revisions);

		/*
		 * Print the top navbar.
		 */
		$s = $navbar;
		$s .= $this->beginHistoryList();
		$counter = 1;

		/*
		 * Print each revision, excluding the one-past-the-end, if any.
		 */
		foreach (array_slice($revisions, 0, $limit) as $i => $line) {
			$first = !$i && $offset == 0;
			$next = isset( $revisions[$i + 1] ) ? $revisions[$i + 1 ] : null;
			$s .= $this->historyLine($line, $next, $counter, $this->getNotificationTimestamp(), $first);
			$counter++;
		}

		/*
		 * End navbar.
		*/
		$s .= $this->endHistoryList();
		$s .= $navbar;

		/*
		 * Article validation line.
		 */
		if ($wgUseValidation)
			$s .= '<p>' . Validation::getStatisticsLink( $this->mArticle ) . '</p>' ;

		$wgOut->addHTML( $s );
		wfProfileOut( $fname );
	}

	/** @todo document */
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

	/** @todo document */
	function endHistoryList() {
		$last = wfMsg( 'last' );

		$s = '</ul>';
		$s .= $this->submitButton( array( 'id' => 'historysubmit' ) );
		$s .= '</form>';
		return $s;
	}

	/** @todo document */
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

	/** @todo document */
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
		if ($this->getNotificationTimestamp() && ($row->rev_timestamp >= $this->getNotificationTimestamp())) {
			$s .= wfMsg( 'updatedmarker' );
		}
		if( $row->rev_deleted ) {
			$s .= "</span> " . htmlspecialchars( wfMsg( 'deletedrev' ) );
		}
		$s .= '</li>';

		return $s;
	}

	/** @todo document */
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

	/** @todo document */
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
				'diff=' . $this->getLatestID($this->mTitle->getArticleID())
				. '&oldid=' . $row->rev_id );
		}
	}

	/** @todo document */
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
			  "diff={$row->rev_id}&oldid={$next->rev_id}",
			  '',
			  '',
			  ' tabindex="'.$counter.'"' );
		}
	}

	/** @todo document */
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

	/** @todo document */
	function getLatestOffset($id) {
		return $this->getExtremeOffset( $id, 'max' );
	}

	/** @todo document */
	function getEarliestOffset($id) {
		return $this->getExtremeOffset( $id, 'min' );
	}

	/** @todo document */
	function getExtremeOffset( $id, $func ) {
		$db =& wfGetDB(DB_SLAVE);
		return $db->selectField( 'revision',
			"$func(rev_timestamp)",
			array( 'rev_page' => $id ),
			'PageHistory::getExtremeOffset' );
	}

	/** @todo document */
	function getLatestID( $id ) {
		$db =& wfGetDB(DB_SLAVE);
		return $db->selectField( 'revision',
			"max(rev_id)",
			array( 'rev_page' => $id ),
			'PageHistory::getLatestID' );
	}

	/** @todo document */
	function getLastOffsetForPaging( $id, $step = 50 ) {
		$db =& wfGetDB(DB_SLAVE);
		$revision = $db->tableName( 'revision' );
		$sql = "SELECT rev_timestamp FROM $revision WHERE rev_page = $id " .
			"ORDER BY rev_timestamp ASC LIMIT $step";
		$res = $db->query( $sql, "PageHistory::getLastOffsetForPaging" );
		$n = $db->numRows( $res );

		$last = null;
		while( $obj = $db->fetchObject( $res ) ) {
			$last = $obj->rev_timestamp;
		}
		$db->freeResult( $res );
		return $last;
	}

	/** @todo document */
	function getDirection() {
		global $wgRequest;

		if ($wgRequest->getText("dir") == "prev")
			return DIR_PREV;
		else
			return DIR_NEXT;
	}

	/** @todo document */
	function fetchRevisions($limit, $offset, $direction) {
		global $wgUser, $wgShowUpdatedMarker;

		/* Check one extra row to see whether we need to show 'next' and diff links */
		$limitplus = $limit + 1;

		$namespace = $this->mTitle->getNamespace();
		$title = $this->mTitle->getText();
		$uid = $wgUser->getID();
		$db =& wfGetDB( DB_SLAVE );

		$use_index = $db->useIndexClause( 'page_timestamp' );
		$revision = $db->tableName( 'revision' );

		$limits = $offsets = "";

		if ($direction == DIR_PREV)
			list($dirs, $oper) = array("ASC", ">=");
		else	/* $direction = DIR_NEXT */
			list($dirs, $oper) = array("DESC", "<=");

		if ($offset)
			$offsets .= " AND rev_timestamp $oper '$offset' ";

		if ($limit)
			$limits .= " LIMIT $limitplus ";
		$page_id = $this->mTitle->getArticleID();

		$sql = "SELECT rev_id,rev_user," .
		  "rev_comment,rev_user_text,rev_timestamp,rev_minor_edit,rev_deleted ".
		  "FROM $revision $use_index " .
		  "WHERE rev_page=$page_id " .
		  $offsets .
		  "ORDER BY rev_timestamp $dirs " .
		  $limits;
		$res = $db->query($sql, "PageHistory::fetchRevisions");

		$result = array();
		while (($obj = $db->fetchObject($res)) != NULL)
			$result[] = $obj;

		return $result;
	}

	/** @todo document */
	function getNotificationTimestamp() {
		global $wgUser, $wgShowUpdatedMarker;

		if ($this->mNotificationTimestamp !== NULL)
			return $this->mNotificationTimestamp;

		if ($wgUser->getID() == 0 || !$wgShowUpdatedMarker)
			return $this->mNotificationTimestamp = false;

		$db =& wfGetDB(DB_SLAVE);

		$this->mNotificationTimestamp =	$db->selectField(
			'watchlist',
			'wl_notificationtimestamp',
			array(	'wl_namespace' => $this->mTitle->getNamespace(),
				'wl_title' => $this->mTitle->getDBkey(),
				'wl_user' => $wgUser->getID()
			),
			"PageHistory::getNotficationTimestamp");

		return $this->mNotificationTimestamp;
	}

	/** @todo document */
	function makeNavbar($revisions, $offset, $limit, $direction) {
		global $wgTitle, $wgLang;

		$revisions = array_slice($revisions, 0, $limit);

		$pageid = $this->mTitle->getArticleID();
		$latestTimestamp = $this->getLatestOffset( $pageid );
		$earliestTimestamp = $this->getEarliestOffset( $pageid );

		/*
		 * When we're displaying previous revisions, we need to reverse
		 * the array, because it's queried in reverse order.
		 */
		if ($direction == DIR_PREV)
			$revisions = array_reverse($revisions);

		/*
		 * lowts is the timestamp of the first revision on this page.
		 * hights is the timestamp of the last revision.
		 */

		$lowts = $hights = 0;

		if( count( $revisions ) ) {
			$latestShown = $revisions[0]->rev_timestamp;
			$earliestShown = $revisions[count($revisions) - 1]->rev_timestamp;
		}

		$firsturl = $wgTitle->escapeLocalURL("action=history&limit={$limit}&go=first");
		$lasturl = $wgTitle->escapeLocalURL("action=history&limit={$limit}");
		$firsttext = wfMsgHtml('histfirst');
		$lasttext = wfMsgHtml('histlast');

		$prevurl = $wgTitle->escapeLocalURL("action=history&dir=prev&offset={$latestShown}&limit={$limit}");
		$nexturl = $wgTitle->escapeLocalURL("action=history&offset={$earliestShown}&limit={$limit}");

		$urls = array();
		foreach (array(20, 50, 100, 250, 500) as $num) {
			$urls[] = "<a href=\"".$wgTitle->escapeLocalURL(
				"action=history&offset={$offset}&limit={$num}")."\">".$wgLang->formatNum($num)."</a>";
		}

		$bits = implode($urls, ' | ');

		wfDebug("latestShown=$latestShown latestTimestamp=$latestTimestamp\n");
		if( $latestShown < $latestTimestamp ) {
			$prevtext = "<a href=\"$prevurl\">".wfMsgHtml("prevn", $limit)."</a>";
			$lasttext = "<a href=\"$lasturl\">$lasttext</a>";
		} else {
			$prevtext = wfMsgHtml("prevn", $limit);
		}

		wfDebug("earliestShown=$earliestShown earliestTimestamp=$earliestTimestamp\n");
		if( $earliestShown > $earliestTimestamp ) {
			$nexttext = "<a href=\"$nexturl\">".wfMsgHtml("nextn", $limit)."</a>";
			$firsttext = "<a href=\"$firsturl\">$firsttext</a>";
		} else {
			$nexttext = wfMsgHtml("nextn", $limit);
		}

		$firstlast = "($lasttext | $firsttext)";

		return "$firstlast " . wfMsgHtml("viewprevnext", $prevtext, $nexttext, $bits);
	}
}

?>
