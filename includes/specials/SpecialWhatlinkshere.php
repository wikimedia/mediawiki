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
class SpecialWhatLinksHere extends IncludableSpecialPage {
	/** @var FormOptions */
	protected $opts;

	protected $selfTitle;

	/** @var Title */
	protected $target;

	protected $limits = array( 20, 50, 100, 250, 500 );

	public function __construct() {
		parent::__construct( 'Whatlinkshere' );
	}

	function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		$opts = new FormOptions();

		$opts->add( 'target', '' );
		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'limit', $this->getConfig()->get( 'QueryPageDefaultLimit' ) );
		$opts->add( 'from', 0 );
		$opts->add( 'back', 0 );
		$opts->add( 'hideredirs', false );
		$opts->add( 'hidetrans', false );
		$opts->add( 'hidelinks', false );
		$opts->add( 'hideimages', false );

		$opts->fetchValuesFromRequest( $this->getRequest() );
		$opts->validateIntBounds( 'limit', 0, 5000 );

		// Give precedence to subpage syntax
		if ( $par !== null ) {
			$opts->setValue( 'target', $par );
		}

		// Bind to member variable
		$this->opts = $opts;

		$this->target = Title::newFromURL( $opts->getValue( 'target' ) );
		if ( !$this->target ) {
			$out->addHTML( $this->whatlinkshereForm() );

			return;
		}

		$this->getSkin()->setRelevantTitle( $this->target );

		$this->selfTitle = $this->getPageTitle( $this->target->getPrefixedDBkey() );

		$out->setPageTitle( $this->msg( 'whatlinkshere-title', $this->target->getPrefixedText() ) );
		$out->addBacklinkSubtitle( $this->target );
		$this->showIndirectLinks(
			0,
			$this->target,
			$opts->getValue( 'limit' ),
			$opts->getValue( 'from' ),
			$opts->getValue( 'back' )
		);
	}

	/**
	 * @param int $level Recursion level
	 * @param Title $target Target title
	 * @param int $limit Number of entries to display
	 * @param int $from Display from this article ID (default: 0)
	 * @param int $back Display from this article ID at backwards scrolling (default: 0)
	 */
	function showIndirectLinks( $level, $target, $limit, $from = 0, $back = 0 ) {
		$out = $this->getOutput();
		$dbr = wfGetDB( DB_SLAVE );

		$hidelinks = $this->opts->getValue( 'hidelinks' );
		$hideredirs = $this->opts->getValue( 'hideredirs' );
		$hidetrans = $this->opts->getValue( 'hidetrans' );
		$hideimages = $target->getNamespace() != NS_FILE || $this->opts->getValue( 'hideimages' );

		$fetchlinks = ( !$hidelinks || !$hideredirs );

		// Build query conds in concert for all three tables...
		$conds['pagelinks'] = array(
			'pl_namespace' => $target->getNamespace(),
			'pl_title' => $target->getDBkey(),
		);
		$conds['templatelinks'] = array(
			'tl_namespace' => $target->getNamespace(),
			'tl_title' => $target->getDBkey(),
		);
		$conds['imagelinks'] = array(
			'il_to' => $target->getDBkey(),
		);

		$useLinkNamespaceDBFields = $this->getConfig()->get( 'UseLinkNamespaceDBFields' );
		$namespace = $this->opts->getValue( 'namespace' );
		if ( is_int( $namespace ) ) {
			if ( $useLinkNamespaceDBFields ) {
				$conds['pagelinks']['pl_from_namespace'] = $namespace;
				$conds['templatelinks']['tl_from_namespace'] = $namespace;
				$conds['imagelinks']['il_from_namespace'] = $namespace;
			} else {
				$conds['pagelinks']['page_namespace'] = $namespace;
				$conds['templatelinks']['page_namespace'] = $namespace;
				$conds['imagelinks']['page_namespace'] = $namespace;
			}
		}

		if ( $from ) {
			$conds['templatelinks'][] = "tl_from >= $from";
			$conds['pagelinks'][] = "pl_from >= $from";
			$conds['imagelinks'][] = "il_from >= $from";
		}

		if ( $hideredirs ) {
			$conds['pagelinks']['rd_from'] = null;
		} elseif ( $hidelinks ) {
			$conds['pagelinks'][] = 'rd_from is NOT NULL';
		}

		$queryFunc = function ( $dbr, $table, $fromCol ) use ( $conds, $target, $limit, $useLinkNamespaceDBFields ) {
			// Read an extra row as an at-end check
			$queryLimit = $limit + 1;
			$on = array(
				"rd_from = $fromCol",
				'rd_title' => $target->getDBkey(),
				'rd_interwiki = ' . $dbr->addQuotes( '' ) . ' OR rd_interwiki IS NULL'
			);
			if ( $useLinkNamespaceDBFields ) { // migration check
				$on['rd_namespace'] = $target->getNamespace();
			}
			// Inner LIMIT is 2X in case of stale backlinks with wrong namespaces
			$subQuery = $dbr->selectSqlText(
				array( $table, 'page', 'redirect' ),
				array( $fromCol, 'rd_from' ),
				$conds[$table],
				__CLASS__ . '::showIndirectLinks',
				array( 'ORDER BY' => $fromCol, 'LIMIT' => 2 * $queryLimit ),
				array(
					'page' => array( 'INNER JOIN', "$fromCol = page_id" ),
					'redirect' => array( 'LEFT JOIN', $on )
				)
			);
			return $dbr->select(
				array( 'page', 'temp_backlink_range' => "($subQuery)" ),
				array( 'page_id', 'page_namespace', 'page_title', 'rd_from' ),
				array(),
				__CLASS__ . '::showIndirectLinks',
				array( 'ORDER BY' => 'page_id', 'LIMIT' => $queryLimit ),
				array( 'page' => array( 'INNER JOIN', "$fromCol = page_id" ) )
			);
		};

		if ( $fetchlinks ) {
			$plRes = $queryFunc( $dbr, 'pagelinks', 'pl_from' );
		}

		if ( !$hidetrans ) {
			$tlRes = $queryFunc( $dbr, 'templatelinks', 'tl_from' );
		}

		if ( !$hideimages ) {
			$ilRes = $queryFunc( $dbr, 'imagelinks', 'il_from' );
		}

		if ( ( !$fetchlinks || !$plRes->numRows() )
			&& ( $hidetrans || !$tlRes->numRows() )
			&& ( $hideimages || !$ilRes->numRows() )
		) {
			if ( 0 == $level ) {
				if ( !$this->including() ) {
					$out->addHTML( $this->whatlinkshereForm() );

					// Show filters only if there are links
					if ( $hidelinks || $hidetrans || $hideredirs || $hideimages ) {
						$out->addHTML( $this->getFilterPanel() );
					}
					$errMsg = is_int( $namespace ) ? 'nolinkshere-ns' : 'nolinkshere';
					$out->addWikiMsg( $errMsg, $this->target->getPrefixedText() );
					$out->setStatusCode( 404 );
				}
			}

			return;
		}

		// Read the rows into an array and remove duplicates
		// templatelinks comes second so that the templatelinks row overwrites the
		// pagelinks row, so we get (inclusion) rather than nothing
		if ( $fetchlinks ) {
			foreach ( $plRes as $row ) {
				$row->is_template = 0;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if ( !$hidetrans ) {
			foreach ( $tlRes as $row ) {
				$row->is_template = 1;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if ( !$hideimages ) {
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
			if ( !$this->including() ) {
				$out->addHTML( $this->whatlinkshereForm() );
				$out->addHTML( $this->getFilterPanel() );
				$out->addWikiMsg( 'linkshere', $this->target->getPrefixedText() );

				$prevnext = $this->getPrevNext( $prevId, $nextId );
				$out->addHTML( $prevnext );
			}
		}
		$out->addHTML( $this->listStart( $level ) );
		foreach ( $rows as $row ) {
			$nt = Title::makeTitle( $row->page_namespace, $row->page_title );

			if ( $row->rd_from && $level < 2 ) {
				$out->addHTML( $this->listItem( $row, $nt, $target, true ) );
				$this->showIndirectLinks( $level + 1, $nt, $this->getConfig()->get( 'MaxRedirectLinksRetrieved' ) );
				$out->addHTML( Xml::closeElement( 'li' ) );
			} else {
				$out->addHTML( $this->listItem( $row, $nt, $target ) );
			}
		}

		$out->addHTML( $this->listEnd() );

		if ( $level == 0 ) {
			if ( !$this->including() ) {
				$out->addHTML( $prevnext );
			}
		}
	}

	protected function listStart( $level ) {
		return Xml::openElement( 'ul', ( $level ? array() : array( 'id' => 'mw-whatlinkshere-list' ) ) );
	}

	protected function listItem( $row, $nt, $target, $notClose = false ) {
		$dirmark = $this->getLanguage()->getDirMark();

		# local message cache
		static $msgcache = null;
		if ( $msgcache === null ) {
			static $msgs = array( 'isredirect', 'istemplate', 'semicolon-separator',
				'whatlinkshere-links', 'isimage' );
			$msgcache = array();
			foreach ( $msgs as $msg ) {
				$msgcache[$msg] = $this->msg( $msg )->escaped();
			}
		}

		if ( $row->rd_from ) {
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
		if ( $row->rd_from ) {
			$props[] = $msgcache['isredirect'];
		}
		if ( $row->is_template ) {
			$props[] = $msgcache['istemplate'];
		}
		if ( $row->is_image ) {
			$props[] = $msgcache['isimage'];
		}

		wfRunHooks( 'WhatLinksHereProps', array( $row, $nt, $target, &$props ) );

		if ( count( $props ) ) {
			$propsText = $this->msg( 'parentheses' )
				->rawParams( implode( $msgcache['semicolon-separator'], $props ) )->escaped();
		}

		# Space for utilities links, with a what-links-here link provided
		$wlhLink = $this->wlhLink( $nt, $msgcache['whatlinkshere-links'] );
		$wlh = Xml::wrapClass(
			$this->msg( 'parentheses' )->rawParams( $wlhLink )->escaped(),
			'mw-whatlinkshere-tools'
		);

		return $notClose ?
			Xml::openElement( 'li' ) . "$link $propsText $dirmark $wlh\n" :
			Xml::tags( 'li', null, "$link $propsText $dirmark $wlh" ) . "\n";
	}

	protected function listEnd() {
		return Xml::closeElement( 'ul' );
	}

	protected function wlhLink( Title $target, $text ) {
		static $title = null;
		if ( $title === null ) {
			$title = $this->getPageTitle();
		}

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
		$currentLimit = $this->opts->getValue( 'limit' );
		$prev = $this->msg( 'whatlinkshere-prev' )->numParams( $currentLimit )->escaped();
		$next = $this->msg( 'whatlinkshere-next' )->numParams( $currentLimit )->escaped();

		$changed = $this->opts->getChangedValues();
		unset( $changed['target'] ); // Already in the request title

		if ( 0 != $prevId ) {
			$overrides = array( 'from' => $this->opts->getValue( 'back' ) );
			$prev = $this->makeSelfLink( $prev, array_merge( $changed, $overrides ) );
		}
		if ( 0 != $nextId ) {
			$overrides = array( 'from' => $nextId, 'back' => $prevId );
			$next = $this->makeSelfLink( $next, array_merge( $changed, $overrides ) );
		}

		$limitLinks = array();
		$lang = $this->getLanguage();
		foreach ( $this->limits as $limit ) {
			$prettyLimit = htmlspecialchars( $lang->formatNum( $limit ) );
			$overrides = array( 'limit' => $limit );
			$limitLinks[] = $this->makeSelfLink( $prettyLimit, array_merge( $changed, $overrides ) );
		}

		$nums = $lang->pipeList( $limitLinks );

		return $this->msg( 'viewprevnext' )->rawParams( $prev, $next, $nums )->escaped();
	}

	function whatlinkshereForm() {
		// We get nicer value from the title object
		$this->opts->consumeValue( 'target' );
		// Reset these for new requests
		$this->opts->consumeValues( array( 'back', 'from' ) );

		$target = $this->target ? $this->target->getPrefixedText() : '';
		$namespace = $this->opts->consumeValue( 'namespace' );

		# Build up the form
		$f = Xml::openElement( 'form', array( 'action' => wfScript() ) );

		# Values that should not be forgotten
		$f .= Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() );
		foreach ( $this->opts->getUnconsumedValues() as $name => $value ) {
			$f .= Html::hidden( $name, $value );
		}

		$f .= Xml::fieldset( $this->msg( 'whatlinkshere' )->text() );

		# Target input
		$f .= Xml::inputLabel( $this->msg( 'whatlinkshere-page' )->text(), 'target',
			'mw-whatlinkshere-target', 40, $target );

		$f .= ' ';

		# Namespace selector
		$f .= Html::namespaceSelector(
			array(
				'selected' => $namespace,
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			), array(
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			)
		);

		$f .= ' ';

		# Submit
		$f .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() );

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
		$show = $this->msg( 'show' )->escaped();
		$hide = $this->msg( 'hide' )->escaped();

		$changed = $this->opts->getChangedValues();
		unset( $changed['target'] ); // Already in the request title

		$links = array();
		$types = array( 'hidetrans', 'hidelinks', 'hideredirs' );
		if ( $this->target->getNamespace() == NS_FILE ) {
			$types[] = 'hideimages';
		}

		// Combined message keys: 'whatlinkshere-hideredirs', 'whatlinkshere-hidetrans',
		// 'whatlinkshere-hidelinks', 'whatlinkshere-hideimages'
		// To be sure they will be found by grep
		foreach ( $types as $type ) {
			$chosen = $this->opts->getValue( $type );
			$msg = $chosen ? $show : $hide;
			$overrides = array( $type => !$chosen );
			$links[] = $this->msg( "whatlinkshere-{$type}" )->rawParams(
				$this->makeSelfLink( $msg, array_merge( $changed, $overrides ) ) )->escaped();
		}

		return Xml::fieldset(
			$this->msg( 'whatlinkshere-filters' )->text(),
			$this->getLanguage()->pipeList( $links )
		);
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
