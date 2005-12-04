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
	var $mLatestId = null;

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

		$this->defaultLimit = 50;
	}

	/**
	 * Print the history page for an article.
	 *
	 * @returns nothing
	 */
	function history() {
		global $wgUser, $wgOut, $wgRequest, $wgTitle, $wgUseValidation;

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
		if( !$this->mTitle->exists() ) {
			$wgOut->addWikiText( wfMsg( 'nohistory' ) );
			wfProfileOut( $fname );
			return;
		}

		$dbr =& wfGetDB(DB_SLAVE);

		/*
		 * Extract limit, the number of revisions to show, and
		 * offset, the timestamp to begin at, from the URL.
		 */
		$limit = $wgRequest->getInt('limit', $this->defaultLimit);
		$offset = $wgRequest->getText('offset');

		/* Offset must be an integral. */
		if (!strlen($offset) || !preg_match("/^[0-9]+$/", $offset))
			$offset = 0;
#		$offset = $dbr->timestamp($offset);
		$dboffset = $offset === 0 ? 0 : $dbr->timestamp($offset);
		/*
		 * "go=last" means to jump to the last history page.
		 */
		if (($gowhere = $wgRequest->getText("go")) !== NULL) {
			switch ($gowhere) {
			case "first":
				if (($lastid = $this->getLastOffsetForPaging($this->mTitle->getArticleID(), $limit)) === NULL)
					break;
				$gourl = $wgTitle->getLocalURL("action=history&limit={$limit}&offset=".
						wfTimestamp(TS_MW, $lastid));
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
		$revisions = $this->fetchRevisions($limit, $dboffset, $direction);
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
			$latest = !$i && $offset == 0;
			$firstInList = !$i;
			$next = isset( $revisions[$i + 1] ) ? $revisions[$i + 1 ] : null;
			$s .= $this->historyLine($line, $next, $counter, $this->getNotificationTimestamp(), $latest, $firstInList);
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
		$s = wfMsgWikiHtml( 'histlegend' );
		$s .= '<form action="' . $wgTitle->escapeLocalURL( '-' ) . '" method="get">';
		$prefixedkey = htmlspecialchars($wgTitle->getPrefixedDbKey());
		
		// The following line is SUPPOSED to have double-quotes around the
		// $prefixedkey variable, because htmlspecialchars() doesn't escape
		// single-quotes.
		//
		// On at least two occasions people have changed it to single-quotes,
		// which creates invalid HTML and incorrect display of the resulting
		// link.
		//
		// Please do not break this a third time. Thank you for your kind
		// consideration and cooperation.
		//
		$s .= "<input type='hidden' name='title' value=\"{$prefixedkey}\" />\n";
		
		$s .= $this->submitButton();
		$s .= '<ul id="pagehistory">' . "\n";
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
					'accesskey' => wfMsgHtml( 'accesskey-compareselectedversions' ),
					'title'     => wfMsgHtml( 'tooltip-compareselectedversions' ),
					'value'     => wfMsgHtml( 'compareselectedversions' ),
				) ) )
			: '';
	}

	/** @todo document */
	function historyLine( $row, $next, $counter = '', $notificationtimestamp = false, $latest = false, $firstInList = false ) {

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
		/* This feature is not yet used according to schema */
		if( $row->rev_deleted ) {
			$s .= '<span class="history-deleted">';
		}
		$curlink = $this->curLink( $row, $latest );
		$lastlink = $this->lastLink( $row, $next, $counter );
		$arbitrary = $this->diffButtons( $row, $firstInList, $counter );
		$link = $this->revLink( $row );

		$s .= "($curlink) ($lastlink) $arbitrary $link <span class='history-user'>$ul</span>";

		if( $row->rev_minor_edit ) {
			$s .= ' ' . wfElement( 'span', array( 'class' => 'minor' ), wfMsgHtml( 'minoreditletter') );
		}

		$s .= $this->mSkin->commentBlock( $row->rev_comment, $this->mTitle );
		if ($notificationtimestamp && ($row->rev_timestamp >= $notificationtimestamp)) {
			$s .= ' <span class="updatedmarker">' .  wfMsgHtml( 'updatedmarker' ) . '</span>';
		}
		if( $row->rev_deleted ) {
			$s .= '</span> ' . wfMsgHtml( 'deletedrev' );
		}
		$s .= "</li>\n";

		return $s;
	}

	/** @todo document */
	function revLink( $row ) {
		global $wgUser, $wgLang;
		$date = $wgLang->timeanddate( wfTimestamp(TS_MW, $row->rev_timestamp), true );
		if( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) {
			return $date;
		} else {
			return $this->mSkin->makeKnownLinkObj(
				$this->mTitle, $date, "oldid={$row->rev_id}" );
		}
	}

	/** @todo document */
	function curLink( $row, $latest ) {
		global $wgUser;
		$cur = wfMsgHtml( 'cur' );
		if( $latest
			|| ( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) ) {
			return $cur;
		} else {
			return $this->mSkin->makeKnownLinkObj(
				$this->mTitle, $cur,
				'diff=' . $this->getLatestID() .
				"&oldid={$row->rev_id}" );
		}
	}

	/** @todo document */
	function lastLink( $row, $next, $counter ) {
		global $wgUser;
		$last = htmlspecialchars( wfMsg( 'last' ) );
		if( is_null( $next ) ) {
			if( $row->rev_timestamp == $this->getEarliestOffset() ) {
				return $last;
			} else {
				// Cut off by paging; there are more behind us...
				return $this->mSkin->makeKnownLinkObj(
					$this->mTitle,
					$last,
					"diff={$row->rev_id}&oldid=prev" );
			}
		} elseif( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) {
			return $last;
		} else {
			return $this->mSkin->makeKnownLinkObj(
				$this->mTitle,
				$last,
				"diff={$row->rev_id}&oldid={$next->rev_id}"
				/*,
				'',
				'',
				"tabindex={$counter}"*/ );
		}
	}

	/** @todo document */
	function diffButtons( $row, $firstInList, $counter ) {
		global $wgUser;
		if( $this->linesonpage > 1) {
			$radio = array(
				'type'  => 'radio',
				'value' => $row->rev_id,
# do we really need to flood this on every item?
#				'title' => wfMsgHtml( 'selectolderversionfordiff' )
			);

			if( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) {
				$radio['disabled'] = 'disabled';
			}

			/** @todo: move title texts to javascript */
			if ( $firstInList ) {
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
	function getLatestOffset( $id = null ) {
		if ( $id === null) $id = $this->mTitle->getArticleID();
		return $this->getExtremeOffset( $id, 'max' );
	}

	/** @todo document */
	function getEarliestOffset( $id = null ) {
		if ( $id === null) $id = $this->mTitle->getArticleID();
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
	function getLatestId() {
		if( is_null( $this->mLatestId ) ) {
			$id = $this->mTitle->getArticleID();
			$db =& wfGetDB(DB_SLAVE);
			$this->mLatestId = $db->selectField( 'revision',
				"max(rev_id)",
				array( 'rev_page' => $id ),
				'PageHistory::getLatestID' );
		}
		return $this->mLatestId;
	}

	/** @todo document */
	function getLastOffsetForPaging( $id, $step ) {
		$fname = 'PageHistory::getLastOffsetForPaging';

		$dbr =& wfGetDB(DB_SLAVE);
		$res = $dbr->select(
			'revision',
			'rev_timestamp',
			"rev_page=$id",
			$fname,
			array('ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => $step));

		$n = $dbr->numRows( $res );
		$last = null;
		while( $obj = $dbr->fetchObject( $res ) ) {
			$last = $obj->rev_timestamp;
		}
		$dbr->freeResult( $res );
		return $last;
	}

	/**
	 * @return returns the direction of browsing watchlist
	 */
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
		$fname = 'PageHistory::fetchRevisions';

		$dbr =& wfGetDB( DB_SLAVE );

		if ($direction == DIR_PREV)
			list($dirs, $oper) = array("ASC", ">=");
		else /* $direction == DIR_NEXT */
			list($dirs, $oper) = array("DESC", "<=");

		if ($offset)
			$offsets = array("rev_timestamp $oper '$offset'");
		else
			$offsets = array();

		$page_id = $this->mTitle->getArticleID();

		$res = $dbr->select(
			'revision',
			array('rev_id', 'rev_user', 'rev_comment', 'rev_user_text',
				'rev_timestamp', 'rev_minor_edit', 'rev_deleted'),
			array_merge(array("rev_page=$page_id"), $offsets),
			$fname,
			array('ORDER BY' => "rev_timestamp $dirs",
				'USE INDEX' => 'page_timestamp', 'LIMIT' => $limit)
			);

		$result = array();
		while (($obj = $dbr->fetchObject($res)) != NULL)
			$result[] = $obj;

		return $result;
	}

	/** @todo document */
	function getNotificationTimestamp() {
		global $wgUser, $wgShowUpdatedMarker;
		$fname = 'PageHistory::getNotficationTimestamp';

		if ($this->mNotificationTimestamp !== NULL)
			return $this->mNotificationTimestamp;

		if ($wgUser->isAnon() || !$wgShowUpdatedMarker)
			return $this->mNotificationTimestamp = false;

		$dbr =& wfGetDB(DB_SLAVE);

		$this->mNotificationTimestamp = $dbr->selectField(
			'watchlist',
			'wl_notificationtimestamp',
			array(	'wl_namespace' => $this->mTitle->getNamespace(),
				'wl_title' => $this->mTitle->getDBkey(),
				'wl_user' => $wgUser->getID()
			),
			$fname);

		return $this->mNotificationTimestamp;
	}

	/** @todo document */
	function makeNavbar($revisions, $offset, $limit, $direction) {
		global $wgTitle, $wgLang;

		$revisions = array_slice($revisions, 0, $limit);

		$latestTimestamp = wfTimestamp(TS_MW, $this->getLatestOffset());
		$earliestTimestamp = wfTimestamp(TS_MW, $this->getEarliestOffset());

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
			$latestShown = wfTimestamp(TS_MW, $revisions[0]->rev_timestamp);
			$earliestShown = wfTimestamp(TS_MW, $revisions[count($revisions) - 1]->rev_timestamp);
		}

		/* Don't announce the limit everywhere if it's the default */
		$usefulLimit = $limit == $this->defaultLimit ? '' : $limit;

		$urls = array();
		foreach (array(20, 50, 100, 250, 500) as $num) {
			$urls[] = $this->MakeLink( $wgLang->formatNum($num),
				array('offset' => $offset == 0 ? '' : wfTimestamp(TS_MW, $offset), 'limit' => $num, ) );
		}

		$bits = implode($urls, ' | ');

		wfDebug("latestShown=$latestShown latestTimestamp=$latestTimestamp\n");
		if( $latestShown < $latestTimestamp ) {
			$prevtext = $this->MakeLink( wfMsgHtml("prevn", $limit),
				array( 'dir' => 'prev', 'offset' => $latestShown, 'limit' => $usefulLimit ) );
			$lasttext = $this->MakeLink( wfMsgHtml('histlast'),
				array( 'limit' => $usefulLimit ) );
		} else {
			$prevtext = wfMsgHtml("prevn", $limit);
			$lasttext = wfMsgHtml('histlast');
		}

		wfDebug("earliestShown=$earliestShown earliestTimestamp=$earliestTimestamp\n");
		if( $earliestShown > $earliestTimestamp ) {
			$nexttext = $this->MakeLink( wfMsgHtml("nextn", $limit),
				array( 'offset' => $earliestShown, 'limit' => $usefulLimit ) );
			$firsttext = $this->MakeLink( wfMsgHtml('histfirst'),
				array( 'go' => 'first', 'limit' => $usefulLimit ) );
		} else {
			$nexttext = wfMsgHtml("nextn", $limit);
			$firsttext = wfMsgHtml('histfirst');
		}

		$firstlast = "($lasttext | $firsttext)";

		return "$firstlast " . wfMsgHtml("viewprevnext", $prevtext, $nexttext, $bits);
	}

	function MakeLink($text, $query = NULL) {
		if ( $query === null ) return $text;
		return $this->mSkin->makeKnownLinkObj(
				$this->mTitle, $text,
				wfArrayToCGI( $query, array( 'action' => 'history' )));
	}


}

?>
