<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of redirects to non-existent pages.
 *
 * Editors are encouraged to fix these by editing them to redirect to
 * an existing page instead.
 *
 * How it works, from a performance perspective:
 *
 * 1. Identify source pages,
 *    in doQuery (cached for MiserMode wikis).
 *
 * 2. Render source links,
 *    in formatResult(). Pages may change between cache and now, and
 *    LinkRenderer doesn't know anyway, so we batch preload page info
 *    for all source pages in preprocessResults(),
 *    consumed by LinkRenderer calls in formatResult().
 *
 * 3. Identify redirect destination.
 *    For uncached, this happens in doQuery() by adding extra fields.
 *    For MiserMode, the redirect target is loaded from database
 *    and added to the batch as well.
 *
 * 4. Render destination links,
 *    in formatResult(). Pages may change between cache and now.
 *    So we batch preload page for all destination pages in
 *    preprocessResults(), consumed by LinkRenderer in formatResult().
 *
 * @ingroup SpecialPage
 */
class SpecialBrokenRedirects extends QueryPage {

	private IContentHandlerFactory $contentHandlerFactory;
	/** @var array<int,array<string,Title>> namespace and title map to redirect targets */
	private array $redirectTargets = [];

	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'BrokenRedirects' );
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	protected function getPageHeader() {
		return $this->msg( 'brokenredirectstext' )->parseAsBlock();
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$dbr = $this->getDatabaseProvider()->getReplicaDatabase();

		return [
			'tables' => [
				'redirect',
				'p1' => 'page',
				'p2' => 'page',
			],
			'fields' => [
				'namespace' => 'p1.page_namespace',
				'title' => 'p1.page_title',
				'rd_namespace',
				'rd_title',
				'rd_fragment',
			],
			'conds' => [
				// Exclude pages that don't exist locally as wiki pages, but aren't "broken" either: special
				// pages and interwiki links.
				$dbr->expr( 'rd_namespace', '>=', 0 ),
				'rd_interwiki' => '',
				'p2.page_namespace' => null,
			],
			'join_conds' => [
				'p1' => [ 'JOIN', [
					'rd_from=p1.page_id',
				] ],
				'p2' => [ 'LEFT JOIN', [
					'rd_namespace=p2.page_namespace',
					'rd_title=p2.page_title'
				] ],
			],
		];
	}

	/**
	 * @return array
	 */
	protected function getOrderFields() {
		return [ 'rd_namespace', 'rd_title', 'rd_from' ];
	}

	/**
	 * Preload LinkRenderer for source and destination
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = $this->getLinkBatchFactory()->newLinkBatch()->setCaller( __METHOD__ );
		$cached = $this->isCached();
		foreach ( $res as $row ) {
			// Preload LinkRenderer data for source links
			$batch->add( $row->namespace, $row->title );
			if ( !$cached ) {
				// Preload LinkRenderer data for destination links
				$batch->add( $row->rd_namespace, $row->rd_title );
			}
		}
		if ( $cached ) {
			// Preload redirect targets and LinkRenderer data for destination links
			$rdRes = $db->newSelectQueryBuilder()
				->select( [ 'page_namespace', 'page_title', 'rd_namespace', 'rd_title', 'rd_fragment' ] )
				->from( 'page' )
				->join( 'redirect', null, 'page_id = rd_from' )
				->where( $batch->constructSet( 'page', $db ) )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $rdRes as $rdRow ) {
				$batch->add( $rdRow->rd_namespace, $rdRow->rd_title );
				$this->redirectTargets[$rdRow->page_namespace][$rdRow->page_title] =
					Title::makeTitle( $rdRow->rd_namespace, $rdRow->rd_title, $rdRow->rd_fragment );
			}
		}
		$batch->execute();
		// Rewind for display
		$res->seek( 0 );
	}

	protected function getRedirectTarget( stdClass $result ): ?Title {
		if ( isset( $result->rd_title ) ) {
			return Title::makeTitle(
				$result->rd_namespace,
				$result->rd_title,
				$result->rd_fragment
			);
		} else {
			return $this->redirectTargets[$result->namespace][$result->title] ?? null;
		}
	}

	/**
	 * @param Skin $skin
	 * @param \stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$fromObj = Title::makeTitle( $result->namespace, $result->title );
		$toObj = $this->getRedirectTarget( $result );

		$linkRenderer = $this->getLinkRenderer();

		if ( $toObj === null || $toObj->isKnown() ) {
			return '<del>' . $linkRenderer->makeLink( $fromObj ) . '</del>';
		}

		$from = $linkRenderer->makeKnownLink(
			$fromObj,
			null,
			[],
			[ 'redirect' => 'no' ]
		);
		$links = [];
		// if the page is editable, add an edit link
		if (
			// check user permissions
			$this->getAuthority()->isAllowed( 'edit' ) &&
			// check, if the content model is editable through action=edit
			$this->contentHandlerFactory->getContentHandler( $fromObj->getContentModel() )
				->supportsDirectEditing()
		) {
			$links[] = $linkRenderer->makeKnownLink(
				$fromObj,
				$this->msg( 'brokenredirects-edit' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
		}
		$to = $linkRenderer->makeBrokenLink( $toObj, $toObj->getFullText() );
		$arr = $this->getLanguage()->getArrow();

		$out = $from . $this->msg( 'word-separator' )->escaped();

		if ( $this->getAuthority()->isAllowed( 'delete' ) ) {
			$links[] = $linkRenderer->makeKnownLink(
				$fromObj,
				$this->msg( 'brokenredirects-delete' )->text(),
				[],
				[
					'action' => 'delete',
					'wpReason' => $this->msg( 'brokenredirects-delete-reason' )
						->inContentLanguage()
						->text()
				]
			);
		}

		if ( $links ) {
			$out .= $this->msg( 'parentheses' )->rawParams( $this->getLanguage()
				->pipeList( $links ) )->escaped();
		}
		$out .= " {$arr} {$to}";

		return $out;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->addHelpLink( 'Help:Redirects' );
		parent::execute( $par );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialBrokenRedirects::class, 'SpecialBrokenRedirects' );
