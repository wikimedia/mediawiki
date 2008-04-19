<?php
/**
 * @TODO: Use some variant of Pager or something; the pagination here is lousy.
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

/**
 * implements Special:Whatlinkshere
 * @addtogroup SpecialPage
 */
class WhatLinksHerePage {
	var $request, $par;
	var $limit, $from, $back, $target;
	var $selfTitle, $skin;
	var $hideredirs, $hidetrans, $hidelinks, $fetchlinks;

	private $namespace;

	function WhatLinksHerePage( $request, $par = null ) {
		global $wgUser;
		$this->request = $request;
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

		$this->hideredirs = $this->request->getInt( 'hideredirs' );
		$this->hidetrans = $this->request->getInt( 'hidetrans' );
		$this->hidelinks = $this->request->getInt( 'hidelinks' );
		$this->fetchlinks = !$this->hidelinks || !$this->hideredirs;

		$targetString = isset($this->par) ? $this->par : $this->request->getVal( 'target' );

		if ( is_null( $targetString ) ) {
			$wgOut->addHTML( $this->whatlinkshereForm() );
			return;
		}

		$this->target = Title::newFromURL( $targetString );
		if( !$this->target ) {
			$wgOut->addHTML( $this->whatlinkshereForm() );
			return;
		}
		$this->selfTitle = Title::makeTitleSafe( NS_SPECIAL,
			'Whatlinkshere/' . $this->target->getPrefixedDBkey() );

		$wgOut->setPageTitle( wfMsg( 'whatlinkshere-title', $this->target->getPrefixedText() ) );
		$wgOut->setSubtitle( wfMsg( 'linklistsub' ) );

		$wgOut->addHTML( wfMsgExt( 'whatlinkshere-barrow', array( 'escapenoentities') ) . ' '  .$this->skin->makeLinkObj($this->target, '', 'redirect=no' )."<br />\n");

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
		global $wgOut, $wgMaxRedirectLinksRetrieved;
		$dbr = wfGetDB( DB_SLAVE );
		$options = array();

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
		if( $this->hideredirs ) {
			$plConds['page_is_redirect'] = 0;
		} elseif( $this->hidelinks ) {
			$plConds['page_is_redirect'] = 1;
		}

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
			$from = (int)$from; // just in case
			$tlConds[] = "tl_from >= $from";
			$plConds[] = "pl_from >= $from";
		}

		// Read an extra row as an at-end check
		$queryLimit = $limit + 1;

		// enforce join order, sometimes namespace selector may
		// trigger filesorts which are far less efficient than scanning many entries
		$options[] = 'STRAIGHT_JOIN';

		$options['LIMIT'] = $queryLimit;
		$fields = array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' );

		if( $this->fetchlinks ) {
			$options['ORDER BY'] = 'pl_from';
			$plRes = $dbr->select( array( 'pagelinks', 'page' ), $fields,
				$plConds, __METHOD__, $options );
		}

		if( !$this->hidetrans ) {
			$options['ORDER BY'] = 'tl_from';
			$tlRes = $dbr->select( array( 'templatelinks', 'page' ), $fields,
				$tlConds, __METHOD__, $options );
		}

		if( ( !$this->fetchlinks || !$dbr->numRows( $plRes ) ) && ( $this->hidetrans || !$dbr->numRows( $tlRes ) ) ) {
			if ( 0 == $level ) {
				$options = array(); // reinitialize for a further namespace search
				// really no links to here
				$options['namespace'] = $this->namespace;
				$options['target'] = $this->target->getPrefixedText();
				list( $options['limit'], $options['offset']) = wfCheckLimits();
				$wgOut->addHTML( $this->whatlinkshereForm( $options ) );
				$errMsg = isset( $this->namespace ) ? 'nolinkshere-ns' : 'nolinkshere';
				$wgOut->addWikiMsg( $errMsg, $this->target->getPrefixedText() );
				// Show filters only if there are links
				if( $this->hidelinks || $this->hidetrans || $this->hideredirs )
					$wgOut->addHTML( $this->getFilterPanel() );
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
		$options['target'] = $this->target->getPrefixedText();

		// Read the rows into an array and remove duplicates
		// templatelinks comes second so that the templatelinks row overwrites the
		// pagelinks row, so we get (inclusion) rather than nothing
		if( $this->fetchlinks ) {
			while ( $row = $dbr->fetchObject( $plRes ) ) {
				$row->is_template = 0;
				$rows[$row->page_id] = $row;
			}
			$dbr->freeResult( $plRes );

		}
		if( !$this->hidetrans ) {
			while ( $row = $dbr->fetchObject( $tlRes ) ) {
				$row->is_template = 1;
				$rows[$row->page_id] = $row;
			}
			$dbr->freeResult( $tlRes );
		}

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
			$wgOut->addHTML( $this->getFilterPanel() );
			$wgOut->addWikiMsg( 'linkshere', $this->target->getPrefixedText() );

			$prevnext = $this->getPrevNext( $limit, $prevId, $nextId, $options['namespace'] );
			$wgOut->addHTML( $prevnext );
		}

		$wgOut->addHTML( $this->listStart() );
		foreach ( $rows as $row ) {
			$nt = Title::makeTitle( $row->page_namespace, $row->page_title );

			$wgOut->addHTML( $this->listItem( $row, $nt ) );

			if ( $row->page_is_redirect && $level < 2 ) {
				$this->showIndirectLinks( $level + 1, $nt, $wgMaxRedirectLinksRetrieved );
			}
		}

		$wgOut->addHTML( $this->listEnd() );

		if( $level == 0 ) {
			$wgOut->addHTML( $prevnext );
		}
	}

	protected function listStart() {
		return Xml::openElement( 'ul' );
	}

	protected function listItem( $row, $nt ) {
		# local message cache
		static $msgcache = null;
		if ( $msgcache === null ) {
			static $msgs = array( 'isredirect', 'istemplate', 'semicolon-separator',
				'whatlinkshere-links' );
			$msgcache = array();
			foreach ( $msgs as $msg ) {
				$msgcache[$msg] = wfMsgHtml( $msg );
			}
		}

		$suppressRedirect = $row->page_is_redirect ? 'redirect=no' : '';
		$link = $this->skin->makeKnownLinkObj( $nt, '', $suppressRedirect );

		// Display properties (redirect or template)
		$propsText = '';
		$props = array();
		if ( $row->page_is_redirect )
			$props[] = $msgcache['isredirect'];
		if ( $row->is_template )
			$props[] = $msgcache['istemplate'];

		if ( count( $props ) ) {
			$propsText = '(' . implode( $msgcache['semicolon-separator'], $props ) . ')';
		}

		# Space for utilities links, with a what-links-here link provided
		$wlhLink = $this->wlhLink( $nt, $msgcache['whatlinkshere-links'] );
		$wlh = Xml::wrapClass( "($wlhLink)", 'mw-whatlinkshere-tools' );

		return Xml::tags( 'li', null, "$link $propsText $wlh" ) . "\n";
	}

	protected function listEnd() {
		return Xml::closeElement( 'ul' );
	}

	protected function wlhLink( Title $target, $text ) {
		static $title = null;
		if ( $title === null )
			$title = SpecialPage::getTitleFor( 'Whatlinkshere' );

		$targetText = $target->getPrefixedUrl();
		return $this->skin->makeKnownLinkObj( $title, $text, 'target=' . $targetText );
	}

	function makeSelfLink( $text, $query ) {
		return $this->skin->makeKnownLinkObj( $this->selfTitle, $text, $query );
	}

	/** Get a query string to append to hide appropriate entries */
	function getHideParams() {
		return ($this->hidetrans  ? '&hidetrans=1'  : '')
			. ($this->hidelinks  ? '&hidelinks=1'  : '')
			. ($this->hideredirs ? '&hideredirs=1' : '');
	}

	function getPrevNext( $limit, $prevId, $nextId ) {
		global $wgLang;
		$fmtLimit = $wgLang->formatNum( $limit );
		$prev = wfMsgExt( 'whatlinkshere-prev', array( 'parsemag', 'escape' ), $fmtLimit );
		$next = wfMsgExt( 'whatlinkshere-next', array( 'parsemag', 'escape' ), $fmtLimit );

		$nsText = '';
		if( is_int($this->namespace) ) {
			$nsText = "&namespace={$this->namespace}";
		}

		$hideParams = $this->getHideParams();

		if ( 0 != $prevId ) {
			$prevLink = $this->makeSelfLink( $prev, "limit={$limit}&from={$this->back}{$nsText}{$hideParams}" );
		} else {
			$prevLink = $prev;
		}
		if ( 0 != $nextId ) {
			$nextLink = $this->makeSelfLink( $next, "limit={$limit}&from={$nextId}&back={$prevId}{$nsText}{$hideParams}" );
		} else {
			$nextLink = $next;
		}
		$nums = $this->numLink( 20, $prevId ) . ' | ' .
		  $this->numLink( 50, $prevId ) . ' | ' .
		  $this->numLink( 100, $prevId ) . ' | ' .
		  $this->numLink( 250, $prevId ) . ' | ' .
		  $this->numLink( 500, $prevId );

		return wfMsgHtml( 'viewprevnext', $prevLink, $nextLink, $nums );
	}

	function numLink( $limit, $from, $ns = null ) {
		global $wgLang;
		$query = "limit={$limit}&from={$from}";
		if( is_int($this->namespace) ) { $query .= "&namespace={$this->namespace}";}
		$fmtLimit = $wgLang->formatNum( $limit );
		return $this->makeSelfLink( $fmtLimit, $query.$this->getHideParams() );
	}

	function whatlinkshereForm( $options = array( 'target' => '', 'namespace' => '' ) ) {
		global $wgScript, $wgTitle;

		$options['title'] = $wgTitle->getPrefixedText();

		$f = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), wfMsg( 'whatlinkshere' ) ) .
			Xml::inputLabel( wfMsg( 'whatlinkshere-page' ), 'target', 'mw-whatlinkshere-target', 40, $options['target'] ) . ' ';

		foreach ( $options as $name => $value ) {
			if( $name === 'namespace' || $name === 'target' )
				continue;
			$f .= "\t" . Xml::hidden( $name, $value ). "\n";
		}

		$f .= Xml::label( wfMsg( 'namespace' ), 'namespace' ) . ' ' .
			Xml::namespaceSelector( $options['namespace'], '' ) .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) . "\n";

		return $f;
	}

	/** Set the namespace we are filtering on */
	private function setNamespace( $ns ) {
		$this->namespace = $ns;
	}

	function getFilterPanel() {
		$show = wfMsgHtml( 'show' );
		$hide = wfMsgHtml( 'hide' );
		$links = array();
		foreach( array( 'hidetrans', 'hidelinks', 'hideredirs' ) as $type ) {
			$chosen = $this->$type;
			$msg = wfMsgHtml( "whatlinkshere-{$type}", $chosen ? $show : $hide );
			$url = $this->request->appendQueryValue( $type, intval( !$chosen ), true );
			$links[] = $this->makeSelfLink( $msg, $url );
		}
		return '<p>' . implode( '&nbsp;|&nbsp;', $links ) . '</p>';
	}
}
