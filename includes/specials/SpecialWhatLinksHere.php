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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\SelectQueryBuilder;

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

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var SearchEngineFactory */
	private $searchEngineFactory;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	protected $limits = [ 20, 50, 100, 250, 500 ];

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param SearchEngineFactory $searchEngineFactory
	 * @param NamespaceInfo $namespaceInfo
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		LinkBatchFactory $linkBatchFactory,
		IContentHandlerFactory $contentHandlerFactory,
		SearchEngineFactory $searchEngineFactory,
		NamespaceInfo $namespaceInfo
	) {
		parent::__construct( 'Whatlinkshere' );
		$this->loadBalancer = $loadBalancer;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->namespaceInfo = $namespaceInfo;
	}

	public function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:What links here' );
		$out->addModuleStyles( 'mediawiki.special.changeslist' );
		$out->addModules( 'mediawiki.special.recentchanges' );

		$opts = new FormOptions();

		$opts->add( 'target', '' );
		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'limit', $this->getConfig()->get( 'QueryPageDefaultLimit' ) );
		$opts->add( 'offset', '' );
		$opts->add( 'from', 0 );
		$opts->add( 'dir', 'next' );
		$opts->add( 'hideredirs', false );
		$opts->add( 'hidetrans', false );
		$opts->add( 'hidelinks', false );
		$opts->add( 'hideimages', false );
		$opts->add( 'invert', false );

		$opts->fetchValuesFromRequest( $this->getRequest() );
		$opts->validateIntBounds( 'limit', 0, 5000 );

		// Give precedence to subpage syntax
		if ( $par !== null ) {
			$opts->setValue( 'target', $par );
		}

		// Bind to member variable
		$this->opts = $opts;

		$this->target = Title::newFromText( $opts->getValue( 'target' ) );
		if ( !$this->target ) {
			if ( !$this->including() ) {
				$out->addHTML( $this->whatlinkshereForm() );
			}

			return;
		}

		$this->getSkin()->setRelevantTitle( $this->target );

		$this->selfTitle = $this->getPageTitle( $this->target->getPrefixedDBkey() );

		$out->setPageTitle( $this->msg( 'whatlinkshere-title', $this->target->getPrefixedText() ) );
		$out->addBacklinkSubtitle( $this->target );

		[ $offsetNamespace, $offsetPageID, $dir ] = $this->parseOffsetAndDir( $opts );

		$this->showIndirectLinks(
			0,
			$this->target,
			$opts->getValue( 'limit' ),
			$offsetNamespace,
			$offsetPageID,
			$dir
		);
	}

	/**
	 * Parse the offset and direction parameters.
	 *
	 * Three parameter kinds are supported:
	 * * from=123 (legacy), where page ID 123 is the first included one
	 * * offset=123&dir=next/prev (legacy), where page ID 123 is the last excluded one
	 * * offset=0|123&dir=next/prev (current), where namespace 0 page ID 123 is the last excluded one
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	private function parseOffsetAndDir( FormOptions $opts ): array {
		$from = $opts->getValue( 'from' );
		$opts->reset( 'from' );

		if ( $from ) {
			$dir = 'next';
			$offsetNamespace = null;
			$offsetPageID = $from - 1;
		} else {
			$dir = $opts->getValue( 'dir' );
			[ $offsetNamespaceString, $offsetPageIDString ] = explode(
				'|',
				$opts->getValue( 'offset' ) . '|'
			);
			if ( !$offsetPageIDString ) {
				$offsetPageIDString = $offsetNamespaceString;
				$offsetNamespaceString = '';
			}
			if ( is_numeric( $offsetNamespaceString ) ) {
				$offsetNamespace = (int)$offsetNamespaceString;
			} else {
				$offsetNamespace = null;
			}
			$offsetPageID = (int)$offsetPageIDString;
		}

		if ( $offsetNamespace === null ) {
			$offsetTitle = MediaWikiServices::getInstance()
				->getTitleFactory()
				->newFromID( $offsetPageID );
			$offsetNamespace = $offsetTitle ? $offsetTitle->getNamespace() : 0;
		}

		return [ $offsetNamespace, $offsetPageID, $dir ];
	}

	/**
	 * @param int $level Recursion level
	 * @param Title $target Target title
	 * @param int $limit Number of entries to display
	 * @param int $offsetNamespace Display from this namespace number (included)
	 * @param int $offsetPageID Display from this article ID (excluded)
	 * @param string $dir 'next' or 'prev'
	 */
	private function showIndirectLinks(
		$level, $target, $limit, $offsetNamespace = 0, $offsetPageID = 0, $dir = 'next'
	) {
		$out = $this->getOutput();
		$dbr = $this->loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );

		$hidelinks = $this->opts->getValue( 'hidelinks' );
		$hideredirs = $this->opts->getValue( 'hideredirs' );
		$hidetrans = $this->opts->getValue( 'hidetrans' );
		$hideimages = $target->getNamespace() !== NS_FILE || $this->opts->getValue( 'hideimages' );

		// For historical reasons `pagelinks` always contains an entry for the redirect target.
		// So we only need to query `redirect` if `pagelinks` isn't being queried.
		$fetchredirs = $hidelinks && !$hideredirs;

		// Build query conds in concert for all four tables...
		$conds = [];
		$conds['redirect'] = [
			'rd_namespace' => $target->getNamespace(),
			'rd_title' => $target->getDBkey(),
		];
		$conds['pagelinks'] = [
			'pl_namespace' => $target->getNamespace(),
			'pl_title' => $target->getDBkey(),
		];
		$conds['templatelinks'] = [
			'tl_namespace' => $target->getNamespace(),
			'tl_title' => $target->getDBkey(),
		];
		$conds['imagelinks'] = [
			'il_to' => $target->getDBkey(),
		];

		$namespace = $this->opts->getValue( 'namespace' );
		if ( is_int( $namespace ) ) {
			$invert = $this->opts->getValue( 'invert' );
			if ( $invert ) {
				// Select all namespaces except for the specified one.
				// This allows the database to use the *_from_namespace index. (T241837)
				$namespaces = array_diff(
					$this->namespaceInfo->getValidNamespaces(), [ $namespace ] );
			} else {
				$namespaces = $namespace;
			}
		} else {
			// Select all namespaces.
			// This allows the database to use the *_from_namespace index. (T297754)
			$namespaces = $this->namespaceInfo->getValidNamespaces();
		}
		$conds['redirect']['page_namespace'] = $namespaces;
		$conds['pagelinks']['pl_from_namespace'] = $namespaces;
		$conds['templatelinks']['tl_from_namespace'] = $namespaces;
		$conds['imagelinks']['il_from_namespace'] = $namespaces;

		if ( $offsetPageID ) {
			$rel = $dir === 'prev' ? '<' : '>';
			$conds['redirect'][] = "rd_from $rel $offsetPageID";
			$conds['templatelinks'][] = "(tl_from_namespace = $offsetNamespace AND tl_from $rel $offsetPageID " .
				"OR tl_from_namespace $rel $offsetNamespace)";
			$conds['pagelinks'][] = "(pl_from_namespace = $offsetNamespace AND pl_from $rel $offsetPageID " .
				"OR pl_from_namespace $rel $offsetNamespace)";
			$conds['imagelinks'][] = "(il_from_namespace = $offsetNamespace AND il_from $rel $offsetPageID " .
				"OR il_from_namespace $rel $offsetNamespace)";
		}

		if ( $hideredirs ) {
			// For historical reasons `pagelinks` always contains an entry for the redirect target.
			// So we hide that link when $hideredirs is set. There's unfortunately no way to tell when a
			// redirect's content also links to the target.
			$conds['pagelinks']['rd_from'] = null;
		}

		$sortDirection = $dir === 'prev' ? SelectQueryBuilder::SORT_DESC : SelectQueryBuilder::SORT_ASC;

		$fname = __METHOD__;
		$queryFunc = static function ( IDatabase $dbr, $table, $fromCol ) use (
			$conds, $target, $limit, $sortDirection, $fname
		) {
			// Read an extra row as an at-end check
			$queryLimit = $limit + 1;
			$on = [
				"rd_from = $fromCol",
				'rd_title' => $target->getDBkey(),
				'rd_namespace' => $target->getNamespace(),
				'rd_interwiki = ' . $dbr->addQuotes( '' ) . ' OR rd_interwiki IS NULL'
			];
			// Inner LIMIT is 2X in case of stale backlinks with wrong namespaces
			$subQuery = $dbr->newSelectQueryBuilder()
				->table( $table )
				->fields( [ $fromCol, 'rd_from', 'rd_fragment' ] )
				->conds( $conds[$table] )
				->orderBy( [ $fromCol . '_namespace', $fromCol ], $sortDirection )
				->limit( 2 * $queryLimit )
				->leftJoin( 'redirect', 'redirect', $on );

			return $dbr->newSelectQueryBuilder()
				->table( $subQuery, 'temp_backlink_range' )
				->join( 'page', 'page', "$fromCol = page_id" )
				->fields( [ 'page_id', 'page_namespace', 'page_title',
					'rd_from', 'rd_fragment', 'page_is_redirect' ] )
				->orderBy( [ 'page_namespace', 'page_id' ], $sortDirection )
				->limit( $queryLimit )
				->caller( $fname )
				->fetchResultSet();
		};

		if ( $fetchredirs ) {
			$rdRes = $dbr->newSelectQueryBuilder()
				->table( 'redirect' )
				->fields( [ 'page_id', 'page_namespace', 'page_title', 'rd_from', 'rd_fragment', 'page_is_redirect' ] )
				->conds( $conds['redirect'] )
				->orderBy( 'rd_from', $sortDirection )
				->limit( $limit + 1 )
				->join( 'page', 'page', 'rd_from = page_id' )
				->caller( __METHOD__ )
				->fetchResultSet();
		}

		if ( !$hidelinks ) {
			$plRes = $queryFunc( $dbr, 'pagelinks', 'pl_from' );
		}

		if ( !$hidetrans ) {
			$tlRes = $queryFunc( $dbr, 'templatelinks', 'tl_from' );
		}

		if ( !$hideimages ) {
			$ilRes = $queryFunc( $dbr, 'imagelinks', 'il_from' );
		}

		if ( ( !$fetchredirs || !$rdRes->numRows() )
			&& ( $hidelinks || !$plRes->numRows() )
			&& ( $hidetrans || !$tlRes->numRows() )
			&& ( $hideimages || !$ilRes->numRows() )
		) {
			if ( $level == 0 && !$this->including() ) {
				$out->addHTML( $this->whatlinkshereForm() );

				// Show filters only if there are links
				if ( $hidelinks || $hidetrans || $hideredirs || $hideimages ) {
					$out->addHTML( $this->getFilterPanel() );
				}
				$msgKey = is_int( $namespace ) ? 'nolinkshere-ns' : 'nolinkshere';
				$link = $this->getLinkRenderer()->makeLink(
					$this->target,
					null,
					[],
					$this->target->isRedirect() ? [ 'redirect' => 'no' ] : []
				);

				$errMsg = $this->msg( $msgKey )
					->params( $this->target->getPrefixedText() )
					->rawParams( $link )
					->parseAsBlock();
				$out->addHTML( $errMsg );
				$out->setStatusCode( 404 );
			}

			return;
		}

		// Read the rows into an array and remove duplicates
		// templatelinks comes third so that the templatelinks row overwrites the
		// pagelinks/redirect row, so we get (inclusion) rather than nothing
		$rows = [];
		if ( $fetchredirs ) {
			foreach ( $rdRes as $row ) {
				$row->is_template = 0;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if ( !$hidelinks ) {
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

		// Sort by namespace + page ID, changing the keys to 0-based indices
		usort( $rows, static function ( $rowA, $rowB ) {
			if ( $rowA->page_namespace !== $rowB->page_namespace ) {
				return $rowA->page_namespace < $rowB->page_namespace ? -1 : 1;
			}
			if ( $rowA->page_id !== $rowB->page_id ) {
				return $rowA->page_id < $rowB->page_id ? -1 : 1;
			}
			return 0;
		} );

		$numRows = count( $rows );

		// Work out the start and end IDs, for prev/next links
		if ( !$limit ) { // T289351
			$nextNamespace = $nextPageId = $prevNamespace = $prevPageId = false;
			$rows = [];
		} elseif ( $dir === 'prev' ) {
			if ( $numRows > $limit ) {
				// More rows available after these ones
				// Get the next row from the last row in the result set
				$nextNamespace = $rows[$limit]->page_namespace;
				$nextPageId = $rows[$limit]->page_id;
				// Remove undisplayed rows, for dir='prev' we need to discard first record after sorting
				$rows = array_slice( $rows, 1, $limit );
				// Get the prev row from the first displayed row
				$prevNamespace = $rows[0]->page_namespace;
				$prevPageId = $rows[0]->page_id;
			} else {
				// Get the next row from the last displayed row
				$nextNamespace = $rows[$numRows - 1]->page_namespace;
				$nextPageId = $rows[$numRows - 1]->page_id;
				$prevNamespace = false;
				$prevPageId = false;
			}
		} else {
			// If offset is not set disable prev link
			$prevNamespace = $offsetPageID ? $rows[0]->page_namespace : false;
			$prevPageId = $offsetPageID ? $rows[0]->page_id : false;
			if ( $numRows > $limit ) {
				// Get the next row from the last displayed row
				$nextNamespace = $rows[$limit - 1]->page_namespace;
				$nextPageId = $rows[$limit - 1]->page_id;
				// Remove undisplayed rows
				$rows = array_slice( $rows, 0, $limit );
			} else {
				$nextNamespace = false;
				$nextPageId = false;
			}
		}

		// use LinkBatch to make sure, that all required data (associated with Titles)
		// is loaded in one query
		$lb = $this->linkBatchFactory->newLinkBatch();
		foreach ( $rows as $row ) {
			$lb->add( $row->page_namespace, $row->page_title );
		}
		$lb->execute();

		if ( $level == 0 && !$this->including() ) {
			$out->addHTML( $this->whatlinkshereForm() );
			$out->addHTML( $this->getFilterPanel() );

			$link = $this->getLinkRenderer()->makeLink(
				$this->target,
				null,
				[],
				$this->target->isRedirect() ? [ 'redirect' => 'no' ] : []
			);

			$msg = $this->msg( 'linkshere' )
				->params( $this->target->getPrefixedText() )
				->rawParams( $link )
				->parseAsBlock();
			$out->addHTML( $msg );

			$out->addWikiMsg( 'whatlinkshere-count', Message::numParam( count( $rows ) ) );

			$prevnext = $this->getPrevNext( $prevNamespace, $prevPageId, $nextNamespace, $nextPageId );
			$out->addHTML( $prevnext );
		}
		$out->addHTML( $this->listStart( $level ) );
		foreach ( $rows as $row ) {
			$nt = Title::makeTitle( $row->page_namespace, $row->page_title );

			if ( $row->rd_from && $level < 2 ) {
				$out->addHTML( $this->listItem( $row, $nt, $target, true ) );
				$this->showIndirectLinks(
					$level + 1,
					$nt,
					$this->getConfig()->get( 'MaxRedirectLinksRetrieved' )
				);
				$out->addHTML( Xml::closeElement( 'li' ) );
			} else {
				$out->addHTML( $this->listItem( $row, $nt, $target ) );
			}
		}

		$out->addHTML( $this->listEnd() );

		if ( $level == 0 && !$this->including() ) {
			$out->addHTML( $prevnext );
		}
	}

	protected function listStart( $level ) {
		return Xml::openElement( 'ul', ( $level ? [] : [ 'id' => 'mw-whatlinkshere-list' ] ) );
	}

	protected function listItem( $row, $nt, $target, $notClose = false ) {
		$dirmark = $this->getLanguage()->getDirMark();

		if ( $row->rd_from ) {
			$query = [ 'redirect' => 'no' ];
		} else {
			$query = [];
		}

		$link = $this->getLinkRenderer()->makeKnownLink(
			$nt,
			null,
			$row->page_is_redirect ? [ 'class' => 'mw-redirect' ] : [],
			$query
		);

		// Display properties (redirect or template)
		$propsText = '';
		$props = [];
		if ( (string)$row->rd_fragment !== '' ) {
			$props[] = $this->msg( 'whatlinkshere-sectionredir' )
				->rawParams( $this->getLinkRenderer()->makeLink(
					$target->createFragmentTarget( $row->rd_fragment ),
					$row->rd_fragment
				) )->escaped();
		} elseif ( $row->rd_from ) {
			$props[] = $this->msg( 'isredirect' )->escaped();
		}
		if ( $row->is_template ) {
			$props[] = $this->msg( 'istemplate' )->escaped();
		}
		if ( $row->is_image ) {
			$props[] = $this->msg( 'isimage' )->escaped();
		}

		$this->getHookRunner()->onWhatLinksHereProps( $row, $nt, $target, $props );

		if ( count( $props ) ) {
			$propsText = $this->msg( 'parentheses' )
				->rawParams( $this->getLanguage()->semicolonList( $props ) )->escaped();
		}

		# Space for utilities links, with a what-links-here link provided
		$wlhLink = $this->wlhLink(
			$nt,
			$this->msg( 'whatlinkshere-links' )->text(),
			$this->msg( 'editlink' )->text()
		);
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

	protected function wlhLink( Title $target, $text, $editText ) {
		static $title = null;
		if ( $title === null ) {
			$title = $this->getPageTitle();
		}

		$linkRenderer = $this->getLinkRenderer();

		// always show a "<- Links" link
		$links = [
			'links' => $linkRenderer->makeKnownLink(
				$title,
				$text,
				[],
				[ 'target' => $target->getPrefixedText() ]
			),
		];

		// if the page is editable, add an edit link
		if (
			// check user permissions
			$this->getAuthority()->isAllowed( 'edit' ) &&
			// check, if the content model is editable through action=edit
			$this->contentHandlerFactory->getContentHandler( $target->getContentModel() )
				->supportsDirectEditing()
		) {
			$links['edit'] = $linkRenderer->makeKnownLink(
				$target,
				$editText,
				[],
				[ 'action' => 'edit' ]
			);
		}

		// build the links html
		return $this->getLanguage()->pipeList( $links );
	}

	private function makeSelfLink( $text, $query ) {
		return $this->getLinkRenderer()->makeKnownLink(
			$this->selfTitle,
			$text,
			[],
			$query
		);
	}

	private function getPrevNext( $prevNamespace, $prevPageId, $nextNamespace, $nextPageId ) {
		$currentLimit = $this->opts->getValue( 'limit' );
		$prev = $this->msg( 'whatlinkshere-prev' )->numParams( $currentLimit )->text();
		$next = $this->msg( 'whatlinkshere-next' )->numParams( $currentLimit )->text();

		$changed = $this->opts->getChangedValues();
		unset( $changed['target'] ); // Already in the request title

		if ( $prevPageId != 0 ) {
			$overrides = [ 'dir' => 'prev', 'offset' => "$prevNamespace|$prevPageId", ];
			$prev = Message::rawParam( $this->makeSelfLink( $prev, array_merge( $changed, $overrides ) ) );
		}
		if ( $nextPageId != 0 ) {
			$overrides = [ 'dir' => 'next', 'offset' => "$nextNamespace|$nextPageId", ];
			$next = Message::rawParam( $this->makeSelfLink( $next, array_merge( $changed, $overrides ) ) );
		}

		$limitLinks = [];
		$lang = $this->getLanguage();
		foreach ( $this->limits as $limit ) {
			$prettyLimit = $lang->formatNum( $limit );
			$overrides = [ 'limit' => $limit ];
			$limitLinks[] = $this->makeSelfLink( $prettyLimit, array_merge( $changed, $overrides ) );
		}

		$nums = $lang->pipeList( $limitLinks );

		return $this->msg( 'viewprevnext' )->params( $prev, $next )->rawParams( $nums )->escaped();
	}

	private function whatlinkshereForm() {
		// We get nicer value from the title object
		$this->opts->consumeValue( 'target' );

		$target = $this->target ? $this->target->getPrefixedText() : '';
		$namespace = $this->opts->consumeValue( 'namespace' );
		$nsinvert = $this->opts->consumeValue( 'invert' );

		# Build up the form
		$f = Xml::openElement( 'form', [ 'action' => wfScript() ] );

		# Values that should not be forgotten
		$f .= Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() );
		foreach ( $this->opts->getUnconsumedValues() as $name => $value ) {
			$f .= Html::hidden( $name, $value );
		}

		$f .= Xml::fieldset( $this->msg( 'whatlinkshere' )->text() );

		# Target input (.mw-searchInput enables suggestions)
		$f .= Xml::inputLabel( $this->msg( 'whatlinkshere-page' )->text(), 'target',
			'mw-whatlinkshere-target', 40, $target, [ 'class' => 'mw-searchInput' ] );

		$f .= ' ';

		# Namespace selector
		$f .= Html::namespaceSelector(
			[
				'selected' => $namespace,
				'all' => '',
				'label' => $this->msg( 'namespace' )->text(),
				'in-user-lang' => true,
			], [
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			]
		);

		$hidden = $namespace === '' ? ' mw-input-hidden' : '';
		$f .= ' ' .
			Html::rawElement( 'span',
				[ 'class' => 'mw-input-with-label' . $hidden ],
				Xml::checkLabel(
					$this->msg( 'invert' )->text(),
					'invert',
					'nsinvert',
					$nsinvert,
					[ 'title' => $this->msg( 'tooltip-whatlinkshere-invert' )->text() ]
				)
			);

		$f .= ' ';

		# Submit
		$f .= Xml::submitButton( $this->msg( 'whatlinkshere-submit' )->text() );

		# Close
		$f .= Xml::closeElement( 'fieldset' ) . Xml::closeElement( 'form' ) . "\n";

		return $f;
	}

	/**
	 * Create filter panel
	 *
	 * @return string HTML fieldset and filter panel with the show/hide links
	 */
	private function getFilterPanel() {
		$show = $this->msg( 'show' )->text();
		$hide = $this->msg( 'hide' )->text();

		$changed = $this->opts->getChangedValues();
		unset( $changed['target'] ); // Already in the request title

		$links = [];
		$types = [ 'hidetrans', 'hidelinks', 'hideredirs' ];
		if ( $this->target->getNamespace() === NS_FILE ) {
			$types[] = 'hideimages';
		}

		// Combined message keys: 'whatlinkshere-hideredirs', 'whatlinkshere-hidetrans',
		// 'whatlinkshere-hidelinks', 'whatlinkshere-hideimages'
		// To be sure they will be found by grep
		foreach ( $types as $type ) {
			$chosen = $this->opts->getValue( $type );
			$msg = $chosen ? $show : $hide;
			$overrides = [ $type => !$chosen ];
			$links[] = $this->msg( "whatlinkshere-{$type}" )->rawParams(
				$this->makeSelfLink( $msg, array_merge( $changed, $overrides ) ) )->escaped();
		}

		return Xml::fieldset(
			$this->msg( 'whatlinkshere-filters' )->text(),
			$this->getLanguage()->pipeList( $links )
		);
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
