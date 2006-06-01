<?php
/**
 * Page history
 *
 * Split off from Article.php and Skin.php, 2003-12-22
 * @package MediaWiki
 */

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
		global $wgOut, $wgRequest, $wgTitle;

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
		$wgOut->setSyndicated( true );

		$feedType = $wgRequest->getVal( 'feed' );
		if( $feedType ) {
			wfProfileOut( $fname );
			return $this->feed( $feedType );
		}

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

		$wgOut->addHTML( $s );
		wfProfileOut( $fname );
	}

	/** @todo document */
	function beginHistoryList() {
		global $wgTitle;
		$this->lastdate = '';
		$s = wfMsgExt( 'histlegend', array( 'parse') );
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
	function historyLine( $row, $next, $counter = '', $notificationtimestamp = false, $latest = false, $firstInList = false ) {
		global $wgUser;
		$rev = new Revision( $row );

		$s = '<li>';
		$curlink = $this->curLink( $rev, $latest );
		$lastlink = $this->lastLink( $rev, $next, $counter );
		$arbitrary = $this->diffButtons( $rev, $firstInList, $counter );
		$link = $this->revLink( $rev );
		$user = $this->mSkin->revUserLink( $rev );

		$s .= "($curlink) ($lastlink) $arbitrary";
		
		if( $wgUser->isAllowed( 'deleterevision' ) ) {
			$revdel = Title::makeTitle( NS_SPECIAL, 'Revisiondelete' );
			if( $firstInList ) {
				// We don't currently handle well changing the top revision's settings
				$del = wfMsgHtml( 'rev-delundel' );
			} else {
				$del = $this->mSkin->makeKnownLinkObj( $revdel,
					wfMsg( 'rev-delundel' ),
					'target=' . urlencode( $this->mTitle->getPrefixedDbkey() ) .
					'&oldid=' . urlencode( $rev->getId() ) );
			}
			$s .= "(<small>$del</small>) ";
		}
		
		$s .= " $link <span class='history-user'>$user</span>";

		if( $row->rev_minor_edit ) {
			$s .= ' ' . wfElement( 'span', array( 'class' => 'minor' ), wfMsg( 'minoreditletter') );
		}

		$s .= $this->mSkin->revComment( $rev );
		if ($notificationtimestamp && ($row->rev_timestamp >= $notificationtimestamp)) {
			$s .= ' <span class="updatedmarker">' .  wfMsgHtml( 'updatedmarker' ) . '</span>';
		}
		if( $row->rev_deleted & MW_REV_DELETED_TEXT ) {
			$s .= ' ' . wfMsgHtml( 'deletedrev' );
		}
		$s .= "</li>\n";

		return $s;
	}
	
	/** @todo document */
	function revLink( $rev ) {
		global $wgLang;
		$date = $wgLang->timeanddate( wfTimestamp(TS_MW, $rev->getTimestamp()), true );
		if( $rev->userCan( MW_REV_DELETED_TEXT ) ) {
			$link = $this->mSkin->makeKnownLinkObj(
				$this->mTitle, $date, "oldid=" . $rev->getId() );
		} else {
			$link = $date;
		}
		if( $rev->isDeleted( MW_REV_DELETED_TEXT ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}

	/** @todo document */
	function curLink( $rev, $latest ) {
		$cur = wfMsgExt( 'cur', array( 'escape') );
		if( $latest || !$rev->userCan( MW_REV_DELETED_TEXT ) ) {
			return $cur;
		} else {
			return $this->mSkin->makeKnownLinkObj(
				$this->mTitle, $cur,
				'diff=' . $this->getLatestID() .
				"&oldid=" . $rev->getId() );
		}
	}

	/** @todo document */
	function lastLink( $rev, $next, $counter ) {
		$last = wfMsgExt( 'last', array( 'escape' ) );
		if( is_null( $next ) ) {
			if( $rev->getTimestamp() == $this->getEarliestOffset() ) {
				return $last;
			} else {
				// Cut off by paging; there are more behind us...
				return $this->mSkin->makeKnownLinkObj(
					$this->mTitle,
					$last,
					"diff=" . $rev->getId() . "&oldid=prev" );
			}
		} elseif( !$rev->userCan( MW_REV_DELETED_TEXT ) ) {
			return $last;
		} else {
			return $this->mSkin->makeKnownLinkObj(
				$this->mTitle,
				$last,
				"diff=" . $rev->getId() . "&oldid={$next->rev_id}"
				/*,
				'',
				'',
				"tabindex={$counter}"*/ );
		}
	}

	/** @todo document */
	function diffButtons( $rev, $firstInList, $counter ) {
		if( $this->linesonpage > 1) {
			$radio = array(
				'type'  => 'radio',
				'value' => $rev->getId(),
# do we really need to flood this on every item?
#				'title' => wfMsgHtml( 'selectolderversionfordiff' )
			);

			if( !$rev->userCan( MW_REV_DELETED_TEXT ) ) {
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
			array('rev_id', 'rev_page', 'rev_text_id', 'rev_user', 'rev_comment', 'rev_user_text',
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
		global $wgLang;

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
	
	
	/**
	 * Output a subscription feed listing recent edits to this page.
	 * @param string $type
	 */
	function feed( $type ) {
		global $wgFeedClasses;
		if( !isset( $wgFeedClasses[$type] ) ) {
			global $wgOut;
			$wgOut->addWikiText( wfMsg( 'feed-invalid' ) );
			return;
		}
		
		$feed = new $wgFeedClasses[$type](
			$this->mTitle->getPrefixedText() . ' - ' .
				wfMsgForContent( 'history-feed-title' ),
			wfMsgForContent( 'history-feed-description' ),
			$this->mTitle->getFullUrl( 'action=history' ) );

		$items = $this->fetchRevisions(10, 0, DIR_NEXT);
		$feed->outHeader();
		if( $items ) {
			foreach( $items as $row ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		} else {
			$feed->outItem( $this->feedEmpty() );
		}
		$feed->outFooter();
	}
	
	function feedEmpty() {
		global $wgOut;
		return new FeedItem(
			wfMsgForContent( 'nohistory' ),
			$wgOut->parse( wfMsgForContent( 'history-feed-empty' ) ),
			$this->mTitle->getFullUrl(),
			wfTimestamp( TS_MW ),
			'',
			$this->mTitle->getTalkPage()->getFullUrl() );
	}
	
	/**
	 * Generate a FeedItem object from a given revision table row
	 * Borrows Recent Changes' feed generation functions for formatting;
	 * includes a diff to the previous revision (if any).
	 *
	 * @param $row
	 * @return FeedItem
	 */
	function feedItem( $row ) {
		$rev = new Revision( $row );
		$text = rcFormatDiffRow( $this->mTitle,
			$this->mTitle->getPreviousRevisionID( $rev->getId() ),
			$rev->getId(),
			$rev->getTimestamp(),
			$rev->getComment() );
		
		if( $rev->getComment() == '' ) {
			global $wgContLang;
			$title = wfMsgForContent( 'history-feed-item-nocomment',
				$rev->getUserText(),
				$wgContLang->timeanddate( $rev->getTimestamp() ) );
		} else {
			$title = $rev->getUserText() . ": " . $this->stripComment( $rev->getComment() );
		}

		return new FeedItem(
			$title,
			$text,
			$this->mTitle->getFullUrl( 'diff=' . $rev->getId() . '&oldid=prev' ),
			$rev->getTimestamp(),
			$rev->getUserText(),
			$this->mTitle->getTalkPage()->getFullUrl() );
	}
	
	/**
	 * Quickie hack... strip out wikilinks to more legible form from the comment.
	 */
	function stripComment( $text ) {
		return preg_replace( '/\[\[([^]]*\|)?([^]]+)\]\]/', '\2', $text );
	}


}

?>
