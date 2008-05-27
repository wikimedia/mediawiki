<?php
/**
 * @todo Use some variant of Pager or something; the pagination here is lousy.
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * Entry point
 * @param $par String: An article name ??
 */
function wfSpecialWhatlinkshere($par = NULL) {
	global $wgRequest;
	$page = new WhatLinksHerePage( $wgRequest, $par );
	$page->execute();
}

/**
 * implements Special:Whatlinkshere
 * @ingroup SpecialPage
 */
class WhatLinksHerePage {
	// Stored data
	protected $par;

	// Stored objects
	protected $opts, $target, $selfTitle;

	// Stored globals
	protected $skin, $request;

	protected $limits = array( 20, 50, 100, 250, 500 );

	function WhatLinksHerePage( $request, $par = null ) {
		global $wgUser;
		$this->request = $request;
		$this->skin = $wgUser->getSkin();
		$this->par = $par;
	}

	function execute() {
		global $wgOut;

		$opts = new FormOptions();

		$opts->add( 'target', '' );
		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'hideredirs', false );
		$opts->add( 'hidetrans', false );
		$opts->add( 'hidelinks', false );
		$opts->add( 'hideimages', false );

		$opts->fetchValuesFromRequest( $this->request );

		// Give precedence to subpage syntax
		if ( isset($this->par) ) {
			$opts->setValue( 'target', $this->par );
		}

		// Bind to member variable
		$this->opts = $opts;

		$this->target = Title::newFromURL( $opts->getValue( 'target' ) );
		if( !$this->target ) {
			$wgOut->addHTML( $this->whatlinkshereForm() );
			return;
		}

		$this->selfTitle = SpecialPage::getTitleFor( 'Whatlinkshere', $this->target->getPrefixedDBkey() );

		$wgOut->setPageTitle( wfMsgExt( 'whatlinkshere-title', 'escape', $this->target->getPrefixedText() ) );
		$wgOut->setSubtitle( wfMsgHtml( 'linklistsub' ) );

		$wgOut->addHTML( wfMsgExt( 'whatlinkshere-barrow', array( 'escapenoentities') ) . ' ' .
			$this->skin->makeLinkObj($this->target, '', 'redirect=no' )."<br />\n");

		$this->showIndirectLinks( 0, $this->target );
	}

	/**
	 * @param int       $level      Recursion level
	 * @param Title     $target     Target title
	 * @param int       $limit      Result limit (used for redirects)
	 * For the top level, this outputs the link list to $wgOut.
	 * For sublevels, it returns the string of the HTML.
	 * @returns mixed (true/string)
	 */
	function showIndirectLinks( $level, $target, $limit=false ) {
		global $wgOut;
		$dbr = wfGetDB( DB_SLAVE );
		$options = array();

		$hidelinks = $this->opts->getValue( 'hidelinks' );
		$hideredirs = $this->opts->getValue( 'hideredirs' );
		$hidetrans = $this->opts->getValue( 'hidetrans' );
		$hideimages = $target->getNamespace() != NS_IMAGE || $this->opts->getValue( 'hideimages' );

		$fetchlinks = (!$hidelinks || !$hideredirs);
		
		$namespace = $this->opts->getValue( 'namespace' );
		
		$pager = new WhatLinksHerePager( $this, $target, $level, $limit, $namespace, 
			$hidelinks, $hideredirs, $hidetrans, $hideimages );

		if( !$pager->getNumRows() ) {
			if ( 0 == $level ) {
				$wgOut->addHTML( $this->whatlinkshereForm() );
				$errMsg = is_int($namespace) ? 'nolinkshere-ns' : 'nolinkshere';
				$wgOut->addWikiMsg( $errMsg, $this->target->getPrefixedText() );
				// Show filters only if there are links
				if( $hidelinks || $hidetrans || $hideredirs || $hideimages )
					$wgOut->addHTML( $this->getFilterPanel() );
			}
			return;
		}
		
		// If this is a sub-level, return out a string
		if( $level > 0 ) {
			return $pager->getBody();
		}

		if( $level == 0 ) {
			$wgOut->addHTML( $this->whatlinkshereForm() );
			$wgOut->addHTML( $this->getFilterPanel() );
			$wgOut->addWikiMsg( 'linkshere', $this->target->getPrefixedText() );
			$wgOut->addHTML( '<hr/>' );
			// Add nav links only to top list
			$wgOut->addHTML( $pager->getNavigationBar() );
		}
		
		$wgOut->addHTML( $pager->getBody() );
		
		if ( $level == 0 ) {
			// Add nav links only to top list
			$wgOut->addHTML( $pager->getNavigationBar() );
		}
		return true;
	}

	public function listItem( $row, $nt, $notClose = false ) {
		# local message cache
		static $msgcache = null;
		if ( $msgcache === null ) {
			static $msgs = array( 'isredirect', 'istemplate', 'semicolon-separator',
				'whatlinkshere-links', 'isimage' );
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
		if ( $row->template )
			$props[] = $msgcache['istemplate'];
		if ( $row->image )
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

	function whatlinkshereForm() {
		global $wgScript, $wgTitle;

		// We get nicer value from the title object
		$this->opts->consumeValue( 'target' );

		$target = $this->target ? $this->target->getPrefixedText() : '';
		$namespace = $this->opts->consumeValue( 'namespace' );

		# Build up the form
		$f = Xml::openElement( 'form', array( 'action' => $wgScript ) );
		
		# Values that should not be forgotten
		$f .= Xml::hidden( 'title', $wgTitle->getPrefixedText() );
		foreach ( $this->opts->getUnconsumedValues() as $name => $value ) {
			$f .= Xml::hidden( $name, $value );
		}

		$f .= Xml::fieldset( wfMsg( 'whatlinkshere' ) );

		# Target input
		$f .= Xml::inputLabel( wfMsg( 'whatlinkshere-page' ), 'target',
				'mw-whatlinkshere-target', 40, $target );

		$f .= ' ';

		# Namespace selector
		$f .= Xml::label( wfMsg( 'namespace' ), 'namespace' ) . '&nbsp;' .
			Xml::namespaceSelector( $namespace, '' );

		# Submit
		$f .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );

		# Close
		$f .= Xml::closeElement( 'fieldset' ) . Xml::closeElement( 'form' ) . "\n";

		return $f;
	}

	function getFilterPanel() {
		$show = wfMsgHtml( 'show' );
		$hide = wfMsgHtml( 'hide' );

		$changed = $this->opts->getChangedValues();
		unset($changed['target']); // Already in the request title

		$links = array();
		$types = array( 'hidetrans', 'hidelinks', 'hideredirs' );
		if( $this->target->getNamespace() == NS_IMAGE )
			$types[] = 'hideimages';
		foreach( $types as $type ) {
			$chosen = $this->opts->getValue( $type );
			$msg = wfMsgHtml( "whatlinkshere-{$type}", $chosen ? $show : $hide );
			$overrides = array( $type => !$chosen );
			$links[] = $this->makeSelfLink( $msg, wfArrayToCGI( $overrides, $changed ) );
		}
		return Xml::fieldset( wfMsg( 'whatlinkshere-filters' ), implode( '&nbsp;|&nbsp;', $links ) );
	}
}

/**
 * Pager for Special:WhatLinksHere
 * @ingroup SpecialPage Pager
 */
class WhatLinksHerePager extends AlphabeticPager {
	protected $form, $target, $level, $limit, $namespace, $hidelinks, $hideredirs, $hidetrans, $hideimages;
	
	function __construct( $form, $target, $level, $limit, $namespace, $hidelinks, $hideredirs, $hidetrans, $hideimages ) {
		parent::__construct();
		$this->mForm = $form;

		$this->target = $target;
		$this->namespace = $namespace;
		$this->hidelinks = (bool)$hidelinks;
		$this->hideredirs = (bool)$hideredirs;
		$this->hidetrans = (bool)$hidetrans;
		$this->hideimages = (bool)$hideimages;
		
		$this->upperLimit = $limit; // limit cannot pass this
		$this->level = intval($level); // recursion depth
	}
	
	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target->getPrefixedDBKey();
		return $query;
	}
	
	function getQueryInfo() {
		$queryInfo = array();
		$options = array();

		$fields = array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' );

		$plConds = array(
			'page_id=pl_from',
			'pl_namespace' => $this->target->getNamespace(),
			'pl_title' => $this->target->getDBkey(),
		);
		if( $this->hideredirs ) {
			$plConds['page_is_redirect'] = 0;
		} else if( $this->hidelinks ) {
			$plConds['page_is_redirect'] = 1;
		}

		$tlConds = array(
			'page_id=tl_from',
			'tl_namespace' => $this->target->getNamespace(),
			'tl_title' => $this->target->getDBkey(),
		);

		$ilConds = array(
			'page_id=il_from',
			'il_to' => $this->target->getDBkey(),
		);

		if( is_int($this->namespace) ) {
			$plConds['page_namespace'] = $this->namespace;
			$tlConds['page_namespace'] = $this->namespace;
			$ilConds['page_namespace'] = $this->namespace;
		}

		$plOptions['ORDER BY'] = 'pl_from';
		$tlOptions['ORDER BY'] = 'tl_from';
		$ilOptions['ORDER BY'] = 'il_from';
		
		// Enforce join order, sometimes namespace selector may
        // trigger filesorts which are far less efficient than scanning many entries
		$plOptions['USE INDEX'] = array('pagelinks' => 'pl_namespace');
		$tlOptions['USE INDEX'] = array('templatelinks' => 'tl_namespace');
		$ilOptions['USE INDEX'] = array('imagelinks' => 'il_to');

		$fetchlinks = (!$this->hidelinks || !$this->hideredirs);
		if( $fetchlinks ) {
			$plQueryInfo = array(
				'tables'  => array( 'pagelinks', 'page' ),
				'fields'  => array_merge($fields,array('0 AS is_template','0 AS is_image')),
				'conds'   => $plConds,
				'options' => array_merge($options,$plOptions)
			);
			$queryInfo[] = $plQueryInfo;
		}

		if( !$this->hidetrans ) {
			$tlQueryInfo = array(
				'tables'  => array( 'templatelinks', 'page' ),
				'fields'  => array_merge($fields,array('1 AS is_template','0 AS is_image')),
				'conds'   => $tlConds,
				'options' => array_merge($options,$tlOptions)
			);
			$queryInfo[] = $tlQueryInfo;
		}
		
		if( !$this->hideimages ) {
			$ilQueryInfo = array(
				'tables'  => array( 'imagelinks', 'page' ),
				'fields'  => array_merge($fields,array('0 AS is_template','1 AS is_image')),
				'conds'   => $ilConds,
				'options' => array_merge($options,$ilOptions)
			);
			$queryInfo[] = $ilQueryInfo;
		}
		return $queryInfo;
	}
	
	/**
	 * Do a query with specified parameters
	 *
	 * @param string $offset Index offset, inclusive
	 * @param integer $limit Exact query limit
	 * @param boolean $descending Query direction, false for ascending, true for descending
	 * @return ResultWrapper
	 */
	function reallyDoQuery( $offset, $limit, $descending ) {
		$fname = __METHOD__ . ' (' . get_class( $this ) . ')';
		$queryInfo = $this->getQueryInfo();
		# Make sure a valid wrapper is always returned!
		if( empty($queryInfo) ) {
			return new ResultWrapper( $this->mDb, false );
		}
		$SQLqueries = array();
		// Redirect subqueries have an upper limit for perfomance.
		// If one is given, override the requested limit with this if smaller.
		$limit = $this->upperLimit ? min( $limit, $this->upperLimit ) : $limit;
		// Some code here lifted from Pager.php
		foreach( $queryInfo as $info ) {
			$tables = $info['tables'];
			$fields = $info['fields'];
			$conds = isset( $info['conds'] ) ? $info['conds'] : array();
			$options = isset( $info['options'] ) ? $info['options'] : array();
			$join_conds = isset( $info['join_conds'] ) ? $info['join_conds'] : array();
			$pagefield = $options['ORDER BY'];
			if ( $descending ) {
				$operator = '>';
			} else {
				$options['ORDER BY'] .= ' DESC';
				$operator = '<';
			}
			if ( $offset != '' ) {
				$conds[] = $pagefield . $operator . $this->mDb->addQuotes( $offset );
			}
			$options['LIMIT'] = intval( $limit );
			$SQLqueries[] = '('.$this->mDb->selectSQLText( $tables, $fields, $conds, $fname, $options, $join_conds ).')';
		}
		// Contruct the final query. UNION the mini-queries and merge the results.
		$fields = 'page_id,page_namespace,page_title,page_is_redirect,MAX(is_template) AS template,MAX(is_image) AS image';
		$SQL = "SELECT $fields FROM (" . implode(' UNION ',$SQLqueries) . ") AS result_links";
		// Remove duplicates within the result set.
		$SQL .= ' GROUP BY page_id';
		// Use proper order of result set
		$SQL .= $descending ? " ORDER BY {$this->mIndexField} DESC" : " ORDER BY {$this->mIndexField}";
		// Cut off at the specified limit
		$SQL .= ' LIMIT ' . intval( $limit );
		# Run the query!
		$res = $this->mDb->query( $SQL, __METHOD__ );
		return new ResultWrapper( $this->mDb, $res );
	}

	function getIndexField() {
		return 'page_id';
	}

	function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$lb = new LinkBatch;
		while( $row = $this->mResult->fetchObject() ) {
			$lb->add( $row->page_namespace, $row->page_title );
		}
		$lb->execute();
		wfProfileOut( __METHOD__ );
		return "<ul>\n";
	}

	function getEndBody() {
		return "</ul>\n";
	}
	
	function formatRow( $row ) {
		global $wgMaxRedirectLinksRetrieved;
		$nt = Title::makeTitle( $row->page_namespace, $row->page_title );
		# For redirects, recursively list out the links there...unless
		# we hit the maxixum recurision depth.
		if( $row->page_is_redirect && $this->level < 2 ) {
			return $this->mForm->listItem( $row, $nt, true ) .
				$this->mForm->showIndirectLinks( $this->level + 1, $nt, $wgMaxRedirectLinksRetrieved ) .
				Xml::closeElement( 'li' );
		} else {
			return $this->mForm->listItem( $row, $nt );
		}
	}
}
