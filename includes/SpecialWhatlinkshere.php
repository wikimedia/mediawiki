<?php
/**
 *
 * @addtogroup SpecialPage
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
	var $limit, $from, $back, $target, $namespace;
	var $selfTitle, $skin;

	function WhatLinksHerePage( &$request, $par = null ) {
		global $wgUser;
		$this->request =& $request;
		$this->skin = $wgUser->getSkin();
		$this->par = $par;
	}

	function execute() {
		global $wgOut;

		$this->limit = min( $this->request->getInt( 'limit', 50 ), 5000 );
		if ( $this->limit <= 0 ) {
			$this->limit = 50;
		}
		$this->from = $this->request->getInt( 'from' );
		$this->back = $this->request->getInt( 'back' );

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

		$wgOut->addHTML( wfMsg( 'whatlinkshere-barrow' ) . ' '  .$this->skin->makeLinkObj($this->target, '', 'redirect=no' )."<br />\n");

		$this->showIndirectLinks( 0, $this->target, $this->limit, $this->from, $this->back );
	}

	/**
	 * @param int       $level      Recursion level
	 * @param Title     $target     Target title
	 * @param int       $limit      Number of entries to display
	 * @param Title     $from       Display from this article ID
	 * @param Title     $back       Display from this article ID at backwards scrolling
	 * @private
	 */
	function showIndirectLinks( $level, $target, $limit, $from = 0, $back = 0 ) {
		global $wgOut;
		$fname = 'WhatLinksHerePage::showIndirectLinks';
		$dbr = wfGetDB( DB_READ );

		$ns = $this->request->getIntOrNull( 'namespace' );
		if ( isset( $ns ) ) {
			$options['namespace'] = $ns;
			$this->setNamespace( $options['namespace'] );
		} else {
			$options['namespace'] = '';
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

		if ( $this->namespace !== null ){
			$plConds['page_namespace'] = (int)$this->namespace;
			$tlConds['page_namespace'] = (int)$this->namespace;
		}

		if ( $from ) {
			$offsetCond = "page_id >= $from";
			$options = array( 'ORDER BY page_id' );
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
			if ( 0 == $level && !isset( $this->namespace ) ) {
				// really no links to here
				$wgOut->addWikiText( wfMsg( 'nolinkshere', $this->target->getPrefixedText() ) );
			} elseif ( 0 == $level && isset( $this->namespace ) ) {
				// no links from requested namespace to here
				$options = array(); // reinitialize for a further namespace search
				$options['namespace'] = $this->namespace;
				$options['target'] = $this->target->getPrefixedText();
				list( $options['limit'], $options['offset']) = wfCheckLimits();
				$wgOut->addHTML( $this->whatlinkshereForm( $options ) );
				$wgOut->addWikiText( wfMsg( 'nolinkshere-ns', $this->target->getPrefixedText() ) );
			}
			return;
		}

		$options = array();
		list( $options['limit'], $options['offset']) = wfCheckLimits();
		if ( ( $ns = $this->request->getVal( 'namespace', null ) ) !== null && $ns !== '' && ctype_digit($ns) ) {
			$options['namespace'] = intval( $ns );
			$this->setNamespace( $options['namespace'] );
		} else {
			$options['namespace'] = '';
			$this->setNamespace( null );
		}
		$options['offset'] = $this->request->getVal( 'offset' );
		/* Offset must be an integral. */
		if ( !strlen( $options['offset'] ) || !preg_match( '/^[0-9]+$/', $options['offset'] ) )
		$options['offset'] = '';
		$options['target'] = $this->target->getPrefixedDBkey();

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

		if ( $level == 0 ) {
			$wgOut->addHTML( $this->whatlinkshereForm( $options ) );
			$wgOut->addWikiText( wfMsg( 'linkshere', $this->target->getPrefixedText() ) );
		}
		$isredir = wfMsg( 'isredirect' );
		$istemplate = wfMsg( 'istemplate' );

		if( $level == 0 ) {
			$prevnext = $this->getPrevNext( $limit, $prevId, $nextId, $options['namespace'] );
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

	function getPrevNext( $limit, $prevId, $nextId, $namespace ) {
		global $wgLang;
		$fmtLimit = $wgLang->formatNum( $limit );
		$prev = wfMsg( 'whatlinkshere-prev', $fmtLimit );
		$next = wfMsg( 'whatlinkshere-next', $fmtLimit );

		if ( 0 != $prevId ) {
			$prevLink = $this->makeSelfLink( $prev, "limit={$limit}&from={$this->back}&namespace={$namespace}" );
		} else {
			$prevLink = $prev;
		}
		if ( 0 != $nextId ) {
			$nextLink = $this->makeSelfLink( $next, "limit={$limit}&from={$nextId}&back={$prevId}&namespace={$namespace}" );
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

	function whatlinkshereForm( $options ) {
		global $wgScript, $wgTitle;

		$options['title'] = $wgTitle->getPrefixedText();

		$f = Xml::openElement( 'form', array( 'method' => 'get', 'action' => "$wgScript" ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'whatlinkshere' ) );

		foreach ( $options as $name => $value ) {
			if( $name === 'namespace') continue;
			$f .= "\t" . Xml::hidden( $name, $value ). "\n";
		}

		$f .= Xml::label( wfMsg( 'namespace' ), 'namespace' ) . ' ' .
			Xml::namespaceSelector( $options['namespace'], '' ) .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
			'</fieldset>' .
			Xml::closeElement( 'form' ) . "\n";

		return $f;
	}

	function setNamespace( $ns ) {
		$this->namespace = $ns;
	}

}

?>
