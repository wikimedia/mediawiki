<?php
/**
 * @todo Use some variant of Pager or something; the pagination here is lousy.
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * implements Special:Whatlinkshere
 * @ingroup SpecialPage
 */
class SpecialWhatLinksHere extends SpecialPage {

	// Stored objects
	protected $opts, $target, $selfTitle;

	// Stored globals
	protected $skin;

	protected $limits = array( 20, 50, 100, 250, 500 );

	public function __construct() {
		parent::__construct( 'Whatlinkshere' );
		global $wgUser;
		$this->skin = $wgUser->getSkin();
	}

	function execute( $par ) {
		global $wgOut, $wgRequest;

		$this->setHeaders();

		$opts = new FormOptions();

		$opts->add( 'target', '' );
		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'limit', 50 );
		$opts->add( 'from', 0 );
		$opts->add( 'back', 0 );
		$opts->add( 'hideredirs', false );
		$opts->add( 'hidetrans', false );
		$opts->add( 'hidelinks', false );
		$opts->add( 'hideimages', false );

		$opts->fetchValuesFromRequest( $wgRequest );
		$opts->validateIntBounds( 'limit', 0, 5000 );

		// Give precedence to subpage syntax
		if ( isset($par) ) {
			$opts->setValue( 'target', $par );
		}

		// Bind to member variable
		$this->opts = $opts;

		$this->target = Title::newFromURL( $opts->getValue( 'target' ) );
		if( !$this->target ) {
			$wgOut->addHTML( $this->whatlinkshereForm() );
			return;
		}

		$this->selfTitle = SpecialPage::getTitleFor( 'Whatlinkshere', $this->target->getPrefixedDBkey() );

		$wgOut->setPageTitle( wfMsg( 'whatlinkshere-title', $this->target->getPrefixedText() ) );
		$wgOut->setSubtitle( wfMsg( 'whatlinkshere-backlink', $this->skin->link( $this->target, $this->target->getPrefixedText(), array(), array( 'redirect' => 'no'  ) ) ) );

		$this->showIndirectLinks( 0, $this->target, $opts->getValue( 'limit' ),
			$opts->getValue( 'from' ), $opts->getValue( 'back' ) );
	}

	/**
	 * @param $level  int     Recursion level
	 * @param $target Title   Target title
	 * @param $limit  int     Number of entries to display
	 * @param $from   Title   Display from this article ID
	 * @param $back   Title   Display from this article ID at backwards scrolling
	 * @private
	 */
	function showIndirectLinks( $level, $target, $limit, $from = 0, $back = 0 ) {
		global $wgOut, $wgMaxRedirectLinksRetrieved;
		$dbr = wfGetDB( DB_SLAVE );
		$options = array();

		$hidelinks = $this->opts->getValue( 'hidelinks' );
		$hideredirs = $this->opts->getValue( 'hideredirs' );
		$hidetrans = $this->opts->getValue( 'hidetrans' );
		$hideimages = $target->getNamespace() != NS_FILE || $this->opts->getValue( 'hideimages' );

		$fetchlinks = (!$hidelinks || !$hideredirs);

		// Make the query
		$plConds = array(
			'page_id=pl_from',
			'pl_namespace' => $target->getNamespace(),
			'pl_title' => $target->getDBkey(),
		);
		if( $hideredirs ) {
			$plConds['page_is_redirect'] = 0;
		} elseif( $hidelinks ) {
			$plConds['page_is_redirect'] = 1;
		}

		$tlConds = array(
			'page_id=tl_from',
			'tl_namespace' => $target->getNamespace(),
			'tl_title' => $target->getDBkey(),
		);

		$ilConds = array(
			'page_id=il_from',
			'il_to' => $target->getDBkey(),
		);

		$namespace = $this->opts->getValue( 'namespace' );
		if ( is_int($namespace) ) {
			$plConds['page_namespace'] = $namespace;
			$tlConds['page_namespace'] = $namespace;
			$ilConds['page_namespace'] = $namespace;
		}

		if ( $from ) {
			$tlConds[] = "tl_from >= $from";
			$plConds[] = "pl_from >= $from";
			$ilConds[] = "il_from >= $from";
		}

		// Read an extra row as an at-end check
		$queryLimit = $limit + 1;

		// Enforce join order, sometimes namespace selector may
		// trigger filesorts which are far less efficient than scanning many entries
		$options[] = 'STRAIGHT_JOIN';

		$options['LIMIT'] = $queryLimit;
		$fields = array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' );

		if( $fetchlinks ) {
			$options['ORDER BY'] = 'pl_from';
			$plRes = $dbr->select( array( 'pagelinks', 'page' ), $fields,
				$plConds, __METHOD__, $options );
		}

		if( !$hidetrans ) {
			$options['ORDER BY'] = 'tl_from';
			$tlRes = $dbr->select( array( 'templatelinks', 'page' ), $fields,
				$tlConds, __METHOD__, $options );
		}

		if( !$hideimages ) {
			$options['ORDER BY'] = 'il_from';
			$ilRes = $dbr->select( array( 'imagelinks', 'page' ), $fields,
				$ilConds, __METHOD__, $options );
		}

		if( ( !$fetchlinks || !$dbr->numRows($plRes) ) && ( $hidetrans || !$dbr->numRows($tlRes) ) && ( $hideimages || !$dbr->numRows($ilRes) ) ) {
			if ( 0 == $level ) {
				$wgOut->addHTML( $this->whatlinkshereForm() );

				// Show filters only if there are links
				if( $hidelinks || $hidetrans || $hideredirs || $hideimages )
					$wgOut->addHTML( $this->getFilterPanel() );

				$errMsg = is_int($namespace) ? 'nolinkshere-ns' : 'nolinkshere';
				$wgOut->addWikiMsg( $errMsg, $this->target->getPrefixedText() );
			}
			return;
		}

		// Read the rows into an array and remove duplicates
		// templatelinks comes second so that the templatelinks row overwrites the
		// pagelinks row, so we get (inclusion) rather than nothing
		if( $fetchlinks ) {
			while ( $row = $dbr->fetchObject( $plRes ) ) {
				$row->is_template = 0;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
			$dbr->freeResult( $plRes );

		}
		if( !$hidetrans ) {
			while ( $row = $dbr->fetchObject( $tlRes ) ) {
				$row->is_template = 1;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
			$dbr->freeResult( $tlRes );
		}
		if( !$hideimages ) {
			while ( $row = $dbr->fetchObject( $ilRes ) ) {
				$row->is_template = 0;
				$row->is_image = 1;
				$rows[$row->page_id] = $row;
			}
			$dbr->freeResult( $ilRes );
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
			$wgOut->addHTML( $this->whatlinkshereForm() );
			$wgOut->addHTML( $this->getFilterPanel() );
			$wgOut->addWikiMsg( 'linkshere', $this->target->getPrefixedText() );

			$prevnext = $this->getPrevNext( $prevId, $nextId );
			$wgOut->addHTML( $prevnext );
		}

		$wgOut->addHTML( $this->listStart() );
		foreach ( $rows as $row ) {
			$nt = Title::makeTitle( $row->page_namespace, $row->page_title );

			if ( $row->page_is_redirect && $level < 2 ) {
				$wgOut->addHTML( $this->listItem( $row, $nt, true ) );
				$this->showIndirectLinks( $level + 1, $nt, $wgMaxRedirectLinksRetrieved );
				$wgOut->addHTML( Xml::closeElement( 'li' ) );
			} else {
				$wgOut->addHTML( $this->listItem( $row, $nt ) );
			}
		}

		$wgOut->addHTML( $this->listEnd() );

		if( $level == 0 ) {
			$wgOut->addHTML( $prevnext );
		}
	}

	protected function listStart() {
		return Xml::openElement( 'ul', array ( 'id' => 'mw-whatlinkshere-list' ) );
	}

	protected function listItem( $row, $nt, $notClose = false ) {
		# local message cache
		static $msgcache = null;
		if ( $msgcache === null ) {
			static $msgs = array( 'isredirect', 'istemplate', 'semicolon-separator',
				'whatlinkshere-links', 'isimage' );
			$msgcache = array();
			foreach ( $msgs as $msg ) {
				$msgcache[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
			}
		}

		if( $row->page_is_redirect ) {
			$query = array( 'redirect' => 'no' );
		} else {
			$query = array();
		}

		$link = $this->skin->linkKnown(
			$nt,
			null,
			array(),
			$query
		);

		// Display properties (redirect or template)
		$propsText = '';
		$props = array();
		if ( $row->page_is_redirect )
			$props[] = $msgcache['isredirect'];
		if ( $row->is_template )
			$props[] = $msgcache['istemplate'];
		if( $row->is_image )
			$props[] = $msgcache['isimage'];

		if ( count( $props ) ) {
			$propsText = '(' . implode( $msgcache['semicolon-separator'], $props ) . ')';
		}

		# Space for utilities links, with a what-links-here link provided
		$wlhLink = $this->wlhLink( $nt, $msgcache['whatlinkshere-links'] );
		$wlh = Xml::wrapClass( "($wlhLink)", 'mw-whatlinkshere-tools' );

		return $notClose ?
			Xml::openElement( 'li' ) . "$link $propsText $wlh\n" :
			Xml::tags( 'li', null, "$link $propsText $wlh" ) . "\n";
	}

	protected function listEnd() {
		return Xml::closeElement( 'ul' );
	}

	protected function wlhLink( Title $target, $text ) {
		static $title = null;
		if ( $title === null )
			$title = SpecialPage::getTitleFor( 'Whatlinkshere' );

		return $this->skin->linkKnown(
			$title,
			$text,
			array(),
			array( 'target' => $target->getPrefixedText() )
		);
	}

	function makeSelfLink( $text, $query ) {
		return $this->skin->linkKnown(
			$this->selfTitle,
			$text,
			array(),
			$query
		);
	}

	function getPrevNext( $prevId, $nextId ) {
		global $wgLang;
		$currentLimit = $this->opts->getValue( 'limit' );
		$fmtLimit = $wgLang->formatNum( $currentLimit );
		$prev = wfMsgExt( 'whatlinkshere-prev', array( 'parsemag', 'escape' ), $fmtLimit );
		$next = wfMsgExt( 'whatlinkshere-next', array( 'parsemag', 'escape' ), $fmtLimit );

		$changed = $this->opts->getChangedValues();
		unset($changed['target']); // Already in the request title

		if ( 0 != $prevId ) {
			$overrides = array( 'from' => $this->opts->getValue( 'back' ) );
			$prev = $this->makeSelfLink( $prev, array_merge( $changed, $overrides ) );
		}
		if ( 0 != $nextId ) {
			$overrides = array( 'from' => $nextId, 'back' => $prevId );
			$next = $this->makeSelfLink( $next, array_merge( $changed, $overrides ) );
		}

		$limitLinks = array();
		foreach ( $this->limits as $limit ) {
			$prettyLimit = $wgLang->formatNum( $limit );
			$overrides = array( 'limit' => $limit );
			$limitLinks[] = $this->makeSelfLink( $prettyLimit, array_merge( $changed, $overrides ) );
		}

		$nums = $wgLang->pipeList( $limitLinks );

		return wfMsgHtml( 'viewprevnext', $prev, $next, $nums );
	}

	function whatlinkshereForm() {
		global $wgScript;

		// We get nicer value from the title object
		$this->opts->consumeValue( 'target' );
		// Reset these for new requests
		$this->opts->consumeValues( array( 'back', 'from' ) );

		$target = $this->target ? $this->target->getPrefixedText() : '';
		$namespace = $this->opts->consumeValue( 'namespace' );

		# Build up the form
		$f = Xml::openElement( 'form', array( 'action' => $wgScript ) );
		
		# Values that should not be forgotten
		$f .= Xml::hidden( 'title', SpecialPage::getTitleFor( 'Whatlinkshere' )->getPrefixedText() );
		foreach ( $this->opts->getUnconsumedValues() as $name => $value ) {
			$f .= Xml::hidden( $name, $value );
		}

		$f .= Xml::fieldset( wfMsg( 'whatlinkshere' ) );

		# Target input
		$f .= Xml::inputLabel( wfMsg( 'whatlinkshere-page' ), 'target',
				'mw-whatlinkshere-target', 40, $target );

		$f .= ' ';

		# Namespace selector
		$f .= Xml::label( wfMsg( 'namespace' ), 'namespace' ) . '&#160;' .
			Xml::namespaceSelector( $namespace, '' );

		$f .= ' ';

		# Submit
		$f .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );

		# Close
		$f .= Xml::closeElement( 'fieldset' ) . Xml::closeElement( 'form' ) . "\n";

		return $f;
	}

	/**
	 * Create filter panel
	 * 
	 * @return string HTML fieldset and filter panel with the show/hide links
	 */
	function getFilterPanel() {
		global $wgLang;
		$show = wfMsgHtml( 'show' );
		$hide = wfMsgHtml( 'hide' );

		$changed = $this->opts->getChangedValues();
		unset($changed['target']); // Already in the request title

		$links = array();
		$types = array( 'hidetrans', 'hidelinks', 'hideredirs' );
		if( $this->target->getNamespace() == NS_FILE )
			$types[] = 'hideimages';

		// Combined message keys: 'whatlinkshere-hideredirs', 'whatlinkshere-hidetrans', 'whatlinkshere-hidelinks', 'whatlinkshere-hideimages'
		// To be sure they will be find by grep
		foreach( $types as $type ) {
			$chosen = $this->opts->getValue( $type );
			$msg = $chosen ? $show : $hide;
			$overrides = array( $type => !$chosen );
			$links[] =  wfMsgHtml( "whatlinkshere-{$type}", $this->makeSelfLink( $msg, array_merge( $changed, $overrides ) ) );
		}
		return Xml::fieldset( wfMsg( 'whatlinkshere-filters' ), $wgLang->pipeList( $links ) );
	}
}
