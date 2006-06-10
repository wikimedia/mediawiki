<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Entry point
 * @param string $par An article name ??
 */
function wfSpecialWhatlinkshere($par = NULL) {
	global $wgRequest;
	$page = new WhatLinksHerePage( $wgRequest, $par );
	$page->execute();
}

class WhatLinksHerePage {
	var $request, $par;
	var $limit, $from, $dir, $target;
	var $selfTitle, $skin;

	function WhatLinksHerePage( &$request, $par = null ) {
		global $wgUser;
		$this->request =& $request;
		$this->skin =& $wgUser->getSkin();
		$this->par = $par;
	}

	function execute() {
		global $wgOut;

		$this->limit = min( $this->request->getInt( 'limit', 50 ), 5000 );
		if ( $this->limit <= 0 ) {
			$this->limit = 50;
		}
		$this->from = $this->request->getInt( 'from' );
		$this->dir = $this->request->getText( 'dir', 'next' );
		if ( $this->dir != 'prev' ) {
			$this->dir = 'next';
		}

		$targetString = isset($this->par) ? $this->par : $this->request->getVal( 'target' );

		if (is_null($targetString)) {
			$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
			return;
		}

		$this->target = Title::newFromURL( $targetString );
		if( !$this->target ) {
			$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
			return;
		}
		$this->selfTitle = Title::makeTitleSafe( NS_SPECIAL,
			'Whatlinkshere/' . $this->target->getPrefixedDBkey() );
		$wgOut->setPagetitle( $this->target->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( 'linklistsub' ) );

		$isredir = ' (' . wfMsg( 'isredirect' ) . ")\n";

		$wgOut->addHTML('&lt; '.$this->skin->makeLinkObj($this->target, '', 'redirect=no' )."<br />\n");

		$this->showIndirectLinks( 0, $this->target, $this->limit, $this->from, $this->dir );
	}

	/**
	 * @param int       $level      Recursion level
	 * @param Title     $target     Target title
	 * @param int       $limit      Number of entries to display
	 * @param Title     $from       Display from this article ID
	 * @param string    $dir        'next' or 'prev', whether $fromTitle is the start or end of the list
	 * @private
	 */
	function showIndirectLinks( $level, $target, $limit, $from = 0, $dir = 'next' ) {
		global $wgOut;
		$fname = 'WhatLinksHerePage::showIndirectLinks';

		$dbr =& wfGetDB( DB_READ );

		extract( $dbr->tableNames( 'pagelinks', 'templatelinks', 'page' ) );

		// Some extra validation
		$from = intval( $from );
		if ( !$from && $dir == 'prev' ) {
			// Before start? No make sense
			$dir = 'next';
		}

		// Make the query
		$plConds = array(
			'page_id=pl_from',
			'pl_namespace' => $target->getNamespace(),
			'pl_title' => $target->getDBkey(),
		);

		$tlConds = array(
			'page_id=tl_from',
			'tl_namespace' => $target->getNamespace(),
			'tl_title' => $target->getDBkey(),
		);

		if ( $from ) {
			if ( 'prev' == $dir ) {
				$offsetCond = "page_id < $from";
				$options = array( 'ORDER BY page_id DESC' );
			} else {
				$offsetCond = "page_id >= $from";
				$options = array( 'ORDER BY page_id' );
			}
		} else {
			$offsetCond = false;
			$options = array( 'ORDER BY page_id,is_template DESC' );
		}
		// Read an extra row as an at-end check
		$queryLimit = $limit + 1;
		$options['LIMIT'] = $queryLimit;
		if ( $offsetCond ) {
			$tlConds[] = $offsetCond;
			$plConds[] = $offsetCond;
		}
		$fields = array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' );

		$plRes = $dbr->select( array( 'pagelinks', 'page' ), $fields,
			$plConds, $fname, $options );
		$tlRes = $dbr->select( array( 'templatelinks', 'page' ), $fields,
			$tlConds, $fname, $options );

		if ( !$dbr->numRows( $plRes ) && !$dbr->numRows( $tlRes ) ) {
			if ( 0 == $level ) {
				$wgOut->addWikiText( wfMsg( 'nolinkshere' ) );
			}
			return;
		}

		// Read the rows into an array and remove duplicates
		// templatelinks comes second so that the templatelinks row overwrites the
		// pagelinks row, so we get (inclusion) rather than nothing
		while ( $row = $dbr->fetchObject( $plRes ) ) {
			$row->is_template = 0;
			$rows[$row->page_id] = $row;
		}
		$dbr->freeResult( $plRes );
		while ( $row = $dbr->fetchObject( $tlRes ) ) {
			$row->is_template = 1;
			$rows[$row->page_id] = $row;
		}
		$dbr->freeResult( $tlRes );

		// Sort by key and then change the keys to 0-based indices
		ksort( $rows );
		$rows = array_values( $rows );

		$numRows = count( $rows );

		// Work out the start and end IDs, for prev/next links
		if ( $dir == 'prev' ) {
			// Descending order
			if ( $numRows > $limit ) {
				// More rows available before these ones
				// Get the ID from the next row past the end of the displayed set
				$prevId = $rows[$limit]->page_id;
				// Remove undisplayed rows
				$rows = array_slice( $rows, 0, $limit );
			} else {
				// No more rows available before
				$prevId = 0;
			}
			// Assume that the ID specified in $from exists, so there must be another page
			$nextId = $from;

			// Reverse order ready for display
			$rows = array_reverse( $rows );
		} else {
			// Ascending
			if ( $numRows > $limit ) {
				// More rows available after these ones
				// Get the ID from the last row in the result set
				$nextId = $rows[$limit]->page_id;
				// Remove undisplayed rows
				$rows = array_slice( $rows, 0, $limit );
			} else {
				// No more rows after
				$nextId = false;
			}
			$prevId = $from;
		}

		if ( 0 == $level ) {
			$wgOut->addWikiText( wfMsg( 'linkshere' ) );
		}
		$isredir = wfMsg( 'isredirect' );
		$istemplate = wfMsg( 'istemplate' );

		if( $level == 0 ) {
			$prevnext = $this->getPrevNext( $limit, $prevId, $nextId );
			$wgOut->addHTML( $prevnext );
		}

		$wgOut->addHTML( '<ul>' );
		foreach ( $rows as $row ) {
			$nt = Title::makeTitle( $row->page_namespace, $row->page_title );

			if ( $row->page_is_redirect ) {
				$extra = 'redirect=no';
			} else {
				$extra = '';
			}

			$link = $this->skin->makeKnownLinkObj( $nt, '', $extra );
			$wgOut->addHTML( '<li>'.$link );

			// Display properties (redirect or template)
			$props = array();
			if ( $row->page_is_redirect ) {
				$props[] = $isredir;
			}
			if ( $row->is_template ) {
				$props[] = $istemplate;
			}
			if ( count( $props ) ) {
				// FIXME? Cultural assumption, hard-coded punctuation
				$wgOut->addHTML( ' (' . implode( ', ', $props ) . ') ' );
			}

			if ( $row->page_is_redirect ) {
				if ( $level < 2 ) {
					$this->showIndirectLinks( $level + 1, $nt, 500 );
				}
			}
			$wgOut->addHTML( "</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" );

		if( $level == 0 ) {
			$wgOut->addHTML( $prevnext );
		}
	}

	function makeSelfLink( $text, $query ) {
		return $this->skin->makeKnownLinkObj( $this->selfTitle, $text, $query );
	}

	function getPrevNext( $limit, $prevId, $nextId ) {
		global $wgLang;
		$fmtLimit = $wgLang->formatNum( $limit );
		$prev = wfMsg( 'prevn', $fmtLimit );
		$next = wfMsg( 'nextn', $fmtLimit );

		if ( 0 != $prevId ) {
			$prevLink = $this->makeSelfLink( $prev, "limit={$limit}&from={$prevId}&dir=prev" );
		} else {
			$prevLink = $prev;
		}
		if ( 0 != $nextId ) {
			$nextLink = $this->makeSelfLink( $next, "limit={$limit}&from={$nextId}" );
		} else {
			$nextLink = $next;
		}
		$nums = $this->numLink( 20, $prevId ) . ' | ' .
		  $this->numLink( 50, $prevId ) . ' | ' .
		  $this->numLink( 100, $prevId ) . ' | ' .
		  $this->numLink( 250, $prevId ) . ' | ' .
		  $this->numLink( 500, $prevId );

		return wfMsg( 'viewprevnext', $prevLink, $nextLink, $nums );
	}

	function numLink( $limit, $from ) {
		global $wgLang;
		$query = "limit={$limit}&from={$from}";
		$fmtLimit = $wgLang->formatNum( $limit );
		return $this->makeSelfLink( $fmtLimit, $query );
	}
}

?>
