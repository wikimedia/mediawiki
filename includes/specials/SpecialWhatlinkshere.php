<?php
/**
 * Implements Special:Whatlinkshere
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @todo Use some variant of Pager or something; the pagination here is lousy.
 */

/**
 * Implements Special:Whatlinkshere
 *
 * @ingroup SpecialPage
 */
class SpecialWhatLinksHere extends SpecialPage {

	// Stored objects
	protected $opts, $target, $selfTitle;

	protected $limits = array( 20, 50, 100, 250, 500 );

	public function __construct() {
		parent::__construct( 'Whatlinkshere' );
	}

	function execute( $par ) {
		$out = $this->getOutput();
		
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

		$opts->fetchValuesFromRequest( $this->getRequest() );
		$opts->validateIntBounds( 'limit', 0, 5000 );

		// Give precedence to subpage syntax
		if ( isset($par) ) {
			$opts->setValue( 'target', $par );
		}

		// Bind to member variable
		$this->opts = $opts;

		$this->target = Title::newFromURL( $opts->getValue( 'target' ) );
		if( !$this->target ) {
			$out->addHTML( $this->whatlinkshereForm() );
			return;
		}

		$this->getSkin()->setRelevantTitle( $this->target );


		$this->selfTitle = $this->getTitle( $this->target->getPrefixedDBkey() );

		$out->setPageTitle( wfMsg( 'whatlinkshere-title', $this->target->getPrefixedText() ) );
		$out->setSubtitle( wfMsg( 'whatlinkshere-backlink', Linker::link( $this->target, $this->target->getPrefixedText(), array(), array( 'redirect' => 'no'  ) ) ) );

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
		global $wgMaxRedirectLinksRetrieved;
		$out = $this->getOutput();
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
				$out->addHTML( $this->whatlinkshereForm() );

				// Show filters only if there are links
				if( $hidelinks || $hidetrans || $hideredirs || $hideimages )
					$out->addHTML( $this->getFilterPanel() );

				$errMsg = is_int($namespace) ? 'nolinkshere-ns' : 'nolinkshere';
				$out->addWikiMsg( $errMsg, $this->target->getPrefixedText() );
			}
			return;
		}

		// Read the rows into an array and remove duplicates
		// templatelinks comes second so that the templatelinks row overwrites the
		// pagelinks row, so we get (inclusion) rather than nothing
		if( $fetchlinks ) {
			foreach ( $plRes as $row ) {
				$row->is_template = 0;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if( !$hidetrans ) {
			foreach ( $tlRes as $row ) {
				$row->is_template = 1;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if( !$hideimages ) {
			foreach ( $ilRes as $row ) {
				$row->is_template = 0;
				$row->is_image = 1;
				$rows[$row->page_id] = $row;
			}
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
			$out->addHTML( $this->whatlinkshereForm() );
			$out->addHTML( $this->getFilterPanel() );
			$out->addWikiMsg( 'linkshere', $this->target->getPrefixedText() );

			$prevnext = $this->getPrevNext( $prevId, $nextId );
			$out->addHTML( $prevnext );
		}

		$out->addHTML( $this->listStart( $level ) );
		foreach ( $rows as $row ) {
			$nt = Title::makeTitle( $row->page_namespace, $row->page_title );

			if ( $row->page_is_redirect && $level < 2 ) {
				$out->addHTML( $this->listItem( $row, $nt, true ) );
				$this->showIndirectLinks( $level + 1, $nt, $wgMaxRedirectLinksRetrieved );
				$out->addHTML( Xml::closeElement( 'li' ) );
			} else {
				$out->addHTML( $this->listItem( $row, $nt ) );
			}
		}

		$out->addHTML( $this->listEnd() );

		if( $level == 0 ) {
			$out->addHTML( $prevnext );
		}
	}

	protected function listStart( $level ) {
		return Xml::openElement( 'ul', ( $level ? array() : array( 'id' => 'mw-whatlinkshere-list' ) ) );
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

		$link = Linker::linkKnown(
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
			$title = $this->getTitle();

		return Linker::linkKnown(
			$title,
			$text,
			array(),
			array( 'target' => $target->getPrefixedText() )
		);
	}

	function makeSelfLink( $text, $query ) {
		return Linker::linkKnown(
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
		$f .= Html::hidden( 'title', $this->getTitle()->getPrefixedText() );
		foreach ( $this->opts->getUnconsumedValues() as $name => $value ) {
			$f .= Html::hidden( $name, $value );
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
