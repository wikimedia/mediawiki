<?php
/**
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
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Deferred\LinksUpdate\TemplateLinksTable;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Navigation\PagerNavigationBuilder;
use MediaWiki\Page\PageIdentity;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use SearchEngineFactory;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Implements Special:Whatlinkshere
 *
 * @ingroup SpecialPage
 */
class SpecialWhatLinksHere extends FormSpecialPage {
	/** @var Title */
	protected $target;

	/**
	 * Submitted parameters as processed by `HTMLForm`,
	 * including those for any fields added in the
	 * `SpecialPageBeforeFormDisplay` hook; unset until
	 * the form is processed (or if no form was submitted).
	 */
	private array $formData;
	private IConnectionProvider $dbProvider;
	private LinkBatchFactory $linkBatchFactory;
	private IContentHandlerFactory $contentHandlerFactory;
	private SearchEngineFactory $searchEngineFactory;
	private NamespaceInfo $namespaceInfo;
	private TitleFactory $titleFactory;
	private LinksMigration $linksMigration;

	private const LIMITS = [ 20, 50, 100, 250, 500 ];

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		IContentHandlerFactory $contentHandlerFactory,
		SearchEngineFactory $searchEngineFactory,
		NamespaceInfo $namespaceInfo,
		TitleFactory $titleFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Whatlinkshere' );
		$this->mIncludable = true;
		$this->dbProvider = $dbProvider;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleFactory = $titleFactory;
		$this->linksMigration = $linksMigration;
	}

	/**
	 * Get a better-looking target title from the subpage syntax.
	 * @param string|null $par
	 */
	protected function setParameter( $par ) {
		if ( $par ) {
			// The only difference that subpage syntax can have is the underscore.
			$par = str_replace( '_', ' ', $par );
		}
		parent::setParameter( $par );
	}

	/**
	 * We want the result displayed after the form, so we use this instead of onSubmit()
	 */
	public function onSuccess() {
		$this->getSkin()->setRelevantTitle( $this->target );

		$out = $this->getOutput();
		$out->setPageTitleMsg(
			$this->msg( 'whatlinkshere-title' )->plaintextParams( $this->target->getPrefixedText() )
		);
		$out->addBacklinkSubtitle( $this->target );

		[ $offsetNamespace, $offsetPageID, $dir ] = $this->parseOffsetAndDir();

		$this->showIndirectLinks(
			0,
			$this->target,
			$this->formData['limit'],
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
	 * @return array
	 */
	private function parseOffsetAndDir(): array {
		$from = (int)$this->formData['from'];

		if ( $from ) {
			$dir = 'next';
			$offsetNamespace = null;
			$offsetPageID = $from - 1;
		} else {
			$dir = $this->formData['dir'] ?? 'next';
			[ $offsetNamespaceString, $offsetPageIDString ] = explode(
				'|',
				$this->formData['offset'] . '|'
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
			$offsetTitle = $this->titleFactory->newFromID( $offsetPageID );
			$offsetNamespace = $offsetTitle ? $offsetTitle->getNamespace() : NS_MAIN;
		}

		return [ $offsetNamespace, $offsetPageID, $dir ];
	}

	/**
	 * @param int $level Recursion level
	 * @param LinkTarget $target Target title
	 * @param int $limit Number of entries to display
	 * @param int $offsetNamespace Display from this namespace number (included)
	 * @param int $offsetPageID Display from this article ID (excluded)
	 * @param string $dir 'next' or 'prev'
	 */
	private function showIndirectLinks(
		$level, LinkTarget $target, $limit, $offsetNamespace = 0, $offsetPageID = 0, $dir = 'next'
	) {
		$out = $this->getOutput();
		$dbr = $this->dbProvider->getReplicaDatabase();
		$hookRunner = $this->getHookRunner();

		$hidelinks = $this->formData['hidelinks'];
		$hideredirs = $this->formData['hideredirs'];
		$hidetrans = $this->formData['hidetrans'];
		$hideimages = $target->getNamespace() !== NS_FILE || ( $this->formData['hideimages'] ?? false );

		// For historical reasons `pagelinks` always contains an entry for the redirect target.
		// So we only need to query `redirect` if `pagelinks` isn't being queried.
		$fetchredirs = $hidelinks && !$hideredirs;

		// Build query conds in concert for all four tables...
		$conds = [];
		$conds['redirect'] = [
			'rd_namespace' => $target->getNamespace(),
			'rd_title' => $target->getDBkey(),
			'rd_interwiki' => '',
		];
		$conds['pagelinks'] = $this->linksMigration->getLinksConditions( 'pagelinks', $target );
		$conds['templatelinks'] = $this->linksMigration->getLinksConditions( 'templatelinks', $target );
		$conds['imagelinks'] = [
			'il_to' => $target->getDBkey(),
		];

		$namespace = $this->formData['namespace'];
		if ( $namespace !== '' ) {
			$invert = $this->formData['invert'];
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
			$op = $dir === 'prev' ? '<' : '>';
			$conds['redirect'][] = $dbr->buildComparison( $op, [
				'rd_from' => $offsetPageID,
			] );
			$conds['templatelinks'][] = $dbr->buildComparison( $op, [
				'tl_from_namespace' => $offsetNamespace,
				'tl_from' => $offsetPageID,
			] );
			$conds['pagelinks'][] = $dbr->buildComparison( $op, [
				'pl_from_namespace' => $offsetNamespace,
				'pl_from' => $offsetPageID,
			] );
			$conds['imagelinks'][] = $dbr->buildComparison( $op, [
				'il_from_namespace' => $offsetNamespace,
				'il_from' => $offsetPageID,
			] );
		}

		if ( $hideredirs ) {
			// For historical reasons `pagelinks` always contains an entry for the redirect target.
			// So we hide that link when $hideredirs is set. There's unfortunately no way to tell when a
			// redirect's content also links to the target.
			$conds['pagelinks']['rd_from'] = null;
		}

		$sortDirection = $dir === 'prev' ? SelectQueryBuilder::SORT_DESC : SelectQueryBuilder::SORT_ASC;

		$fname = __METHOD__;
		$queryFunc = function ( IReadableDatabase $dbr, $table, $fromCol ) use (
			$conds, $target, $limit, $sortDirection, $fname, $hookRunner
		) {
			// Read an extra row as an at-end check
			$queryLimit = $limit + 1;
			$on = [
				"rd_from = $fromCol",
				'rd_title' => $target->getDBkey(),
				'rd_namespace' => $target->getNamespace(),
				'rd_interwiki' => '',
			];
			// Inner LIMIT is 2X in case of stale backlinks with wrong namespaces
			$subQuery = $dbr->newSelectQueryBuilder()
				->table( $table )
				->fields( [ $fromCol, 'rd_from', 'rd_fragment' ] )
				->conds( $conds[$table] )
				->orderBy( [ $fromCol . '_namespace', $fromCol ], $sortDirection )
				->limit( 2 * $queryLimit )
				->leftJoin( 'redirect', 'redirect', $on );

			$queryBuilder = $dbr->newSelectQueryBuilder()
				->table( $subQuery, 'temp_backlink_range' )
				->join( 'page', 'page', "$fromCol = page_id" )
				->fields( [ 'page_id', 'page_namespace', 'page_title',
					'rd_from', 'rd_fragment', 'page_is_redirect' ] )
				->orderBy( [ 'page_namespace', 'page_id' ], $sortDirection )
				->limit( $queryLimit );

			$hookRunner->onSpecialWhatLinksHereQuery( $table, $this->formData, $queryBuilder );

			return $queryBuilder->caller( $fname )->fetchResultSet();
		};

		if ( $fetchredirs ) {
			$queryBuilder = $dbr->newSelectQueryBuilder()
				->table( 'redirect' )
				->fields( [ 'page_id', 'page_namespace', 'page_title', 'rd_from', 'rd_fragment', 'page_is_redirect' ] )
				->conds( $conds['redirect'] )
				->orderBy( 'rd_from', $sortDirection )
				->limit( $limit + 1 )
				->join( 'page', 'page', 'rd_from = page_id' );

			$hookRunner->onSpecialWhatLinksHereQuery( 'redirect', $this->formData, $queryBuilder );

			$rdRes = $queryBuilder->caller( __METHOD__ )->fetchResultSet();
		}

		if ( !$hidelinks ) {
			$plRes = $queryFunc( $dbr, 'pagelinks', 'pl_from' );
		}

		if ( !$hidetrans ) {

			$tlRes = $queryFunc(
				$this->dbProvider->getReplicaDatabase( TemplateLinksTable::VIRTUAL_DOMAIN ),
				'templatelinks',
				'tl_from'
			);
		}

		if ( !$hideimages ) {
			$ilRes = $queryFunc( $dbr, 'imagelinks', 'il_from' );
		}

		// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $rdRes is declared when fetching redirs
		if ( ( !$fetchredirs || !$rdRes->numRows() )
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $plRes is declared when fetching links
			&& ( $hidelinks || !$plRes->numRows() )
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $tlRes is declared when fetching trans
			&& ( $hidetrans || !$tlRes->numRows() )
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $ilRes is declared when fetching images
			&& ( $hideimages || !$ilRes->numRows() )
		) {
			if ( $level == 0 && !$this->including() ) {
				if ( $hidelinks || $hidetrans || $hideredirs ) {
					$msgKey = 'nolinkshere-filter';
				} elseif ( $namespace !== '' ) {
					$msgKey = 'nolinkshere-ns';
				} else {
					$msgKey = 'nolinkshere';
				}
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
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $rdRes is declared when fetching redirs
			foreach ( $rdRes as $row ) {
				$row->is_template = 0;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if ( !$hidelinks ) {
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $plRes is declared when fetching links
			foreach ( $plRes as $row ) {
				$row->is_template = 0;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if ( !$hidetrans ) {
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $tlRes is declared when fetching trans
			foreach ( $tlRes as $row ) {
				$row->is_template = 1;
				$row->is_image = 0;
				$rows[$row->page_id] = $row;
			}
		}
		if ( !$hideimages ) {
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $ilRes is declared when fetching images
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
				$nextNamespace = $rows[$limit - 1]->page_namespace ?? false;
				$nextPageId = $rows[$limit - 1]->page_id ?? false;
				// Remove undisplayed rows
				$rows = array_slice( $rows, 0, $limit );
			} else {
				$nextNamespace = false;
				$nextPageId = false;
			}
		}

		// Optimization: Batch preload all Title data in one query
		$lb = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $rows as $row ) {
			$lb->add( $row->page_namespace, $row->page_title );
		}
		$lb->execute();

		if ( $level == 0 && !$this->including() ) {
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
					$this->getConfig()->get( MainConfigNames::MaxRedirectLinksRetrieved )
				);
				$out->addHTML( Html::closeElement( 'li' ) );
			} else {
				$out->addHTML( $this->listItem( $row, $nt, $target ) );
			}
		}

		$out->addHTML( $this->listEnd() );

		if ( $level == 0 && !$this->including() ) {
			// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable $prevnext is defined with $level is 0
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable prevnext is set when used
			$out->addHTML( $prevnext );
		}
	}

	protected function listStart( int $level ): string {
		return Html::openElement( 'ul', ( $level ? [] : [ 'id' => 'mw-whatlinkshere-list' ] ) );
	}

	private function listItem( stdClass $row, PageIdentity $nt, LinkTarget $target, bool $notClose = false ): string {
		$legacyTitle = $this->titleFactory->newFromPageIdentity( $nt );

		if ( $row->rd_from || $row->page_is_redirect ) {
			$query = [ 'redirect' => 'no' ];
		} else {
			$query = [];
		}

		$dir = $this->getLanguage()->getDir();
		$link = Html::rawElement( 'bdi', [ 'dir' => $dir ], $this->getLinkRenderer()->makeKnownLink(
			$nt,
			null,
			$row->page_is_redirect ? [ 'class' => 'mw-redirect' ] : [],
			$query
		) );

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

		$legacyTarget = $this->titleFactory->newFromLinkTarget( $target );
		$this->getHookRunner()->onWhatLinksHereProps( $row, $legacyTitle, $legacyTarget, $props );

		if ( count( $props ) ) {
			$propsText = $this->msg( 'parentheses' )
				->rawParams( $this->getLanguage()->semicolonList( $props ) )->escaped();
		}

		# Space for utilities links, with a what-links-here link provided
		$wlhLink = $this->wlhLink(
			$legacyTitle,
			$this->msg( 'whatlinkshere-links' )->text(),
			$this->msg( 'editlink' )->text()
		);
		$wlh = Html::rawElement(
			'span',
			[ 'class' => 'mw-whatlinkshere-tools' ],
			$this->msg( 'parentheses' )->rawParams( $wlhLink )->escaped()
		);

		return $notClose ?
			Html::openElement( 'li' ) . "$link $propsText $wlh\n" :
			Html::rawElement( 'li', [], "$link $propsText $wlh" ) . "\n";
	}

	protected function listEnd(): string {
		return Html::closeElement( 'ul' );
	}

	protected function wlhLink( Title $target, string $text, string $editText ): string {
		static $title = null;
		$title ??= $this->getPageTitle();

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

	/**
	 * @param int|false $prevNamespace
	 * @param int|false $prevPageId
	 * @param int|false $nextNamespace
	 * @param int|false $nextPageId
	 */
	private function getPrevNext( $prevNamespace, $prevPageId, $nextNamespace, $nextPageId ): string {
		$navBuilder = new PagerNavigationBuilder( $this->getContext() );

		$navBuilder
			->setPage( $this->getPageTitle( $this->target->getPrefixedDBkey() ) )
			// Remove 'target', already included in the request title
			->setLinkQuery(
				array_diff_key(
					array_filter(
						$this->formData,
						static fn ( $value ) => $value !== null && $value !== '' && $value !== false
					),
					[ 'target' => null, 'from' => null ]
				)
			)
			->setLimits( self::LIMITS )
			->setLimitLinkQueryParam( 'limit' )
			->setCurrentLimit( $this->formData['limit'] )
			->setPrevMsg( 'whatlinkshere-prev' )
			->setNextMsg( 'whatlinkshere-next' );

		if ( $prevPageId != 0 ) {
			$navBuilder->setPrevLinkQuery( [ 'dir' => 'prev', 'offset' => "$prevNamespace|$prevPageId" ] );
		}
		if ( $nextPageId != 0 ) {
			$navBuilder->setNextLinkQuery( [ 'dir' => 'next', 'offset' => "$nextNamespace|$nextPageId" ] );
		}

		return $navBuilder->getHtml();
	}

	/** @inheritDoc */
	protected function getFormFields() {
		$this->addHelpLink( 'Help:What links here' );
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$fields = [
			'target' => [
				'type' => 'title',
				'name' => 'target',
				'id' => 'mw-whatlinkshere-target',
				'label-message' => 'whatlinkshere-page',
				'section' => 'whatlinkshere-target',
				'creatable' => true,
			],
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'id' => 'namespace',
				'label-message' => 'namespace',
				'all' => '',
				'default' => '',
				'filter-callback' => static function ( $value ) {
					return $value !== '' ? intval( $value ) : '';
				},
				'in-user-lang' => true,
				'section' => 'whatlinkshere-ns',
			],
			'invert' => [
				'type' => 'check',
				'name' => 'invert',
				'id' => 'nsinvert',
				'hide-if' => [ '===', 'namespace', '' ],
				'label-message' => 'invert',
				'help-message' => 'tooltip-whatlinkshere-invert',
				'help-inline' => false,
				'section' => 'whatlinkshere-ns'
			],
			'limit' => [
				'type' => 'hidden',
				'name' => 'limit',
				'default' => $this->getConfig()->get( MainConfigNames::QueryPageDefaultLimit ),
				'filter-callback' => static fn ( $value ) => max( 0, min( intval( $value ), 5000 ) ),
			],
			'offset' => [
				'type' => 'api',
				'name' => 'offset',
				'default' => '',
			],
			'dir' => [
				'type' => 'api',
				'name' => 'dir',
			],
			'from' => [
				'type' => 'api',
				'name' => 'from',
				'default' => 0,
			]
		];

		$filters = [ 'hidetrans', 'hidelinks', 'hideredirs' ];

		// Combined message keys: 'whatlinkshere-hideredirs', 'whatlinkshere-hidetrans',
		// 'whatlinkshere-hidelinks'
		// To be sure they will be found by grep
		foreach ( $filters as $filter ) {
			// Parameter only provided for backwards-compatibility with old translations
			$hide = $this->msg( 'hide' )->text();
			$msg = $this->msg( "whatlinkshere-{$filter}", $hide )->text();
			$fields[$filter] = [
				'type' => 'check',
				'name' => $filter,
				'label' => $msg,
				'section' => 'whatlinkshere-filter',
			];
		}

		return $fields;
	}

	protected function alterForm( HTMLForm $form ) {
		// This parameter from the subpage syntax is only added after constructing the form,
		// so we should add the dynamic field that depends on the user input here.

		// TODO: This looks not good. Ideally we can initialize it in onSubmit().
		// Maybe extend the hide-if feature to match prefixes on the client side.
		$this->target = Title::newFromText( $this->getRequest()->getText( 'target' ) );
		if ( $this->target && $this->target->getNamespace() == NS_FILE ) {
			$hide = $this->msg( 'hide' )->text();
			$msg = $this->msg( 'whatlinkshere-hideimages', $hide )->text();
			$form->addFields( [
				'hideimages' => [
					'type' => 'check',
					'name' => 'hideimages',
					'label' => $msg,
					'section' => 'whatlinkshere-filter',
				]
			] );
		}

		$form->setWrapperLegendMsg( 'whatlinkshere' )
			->setSubmitTextMsg( 'whatlinkshere-submit' );
	}

	/** @inheritDoc */
	protected function getShowAlways() {
		return true;
	}

	/** @inheritDoc */
	protected function getSubpageField() {
		return 'target';
	}

	/** @inheritDoc */
	public function onSubmit( array $data ) {
		$this->formData = $data;
		return true;
	}

	/** @inheritDoc */
	public function requiresPost() {
		return false;
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pagetools';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialWhatLinksHere::class, 'SpecialWhatLinksHere' );
