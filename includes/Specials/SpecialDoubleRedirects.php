<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Html\Html;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of redirects to another redirecting page.
 *
 * The software will by default not follow double redirects, to prevent loops.
 * Editors are encouraged to fix these, and can discover them via this page.
 *
 * @ingroup SpecialPage
 */
class SpecialDoubleRedirects extends QueryPage {

	private IContentHandlerFactory $contentHandlerFactory;
	private LinkBatchFactory $linkBatchFactory;

	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'DoubleRedirects' );
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->setDatabaseProvider( $dbProvider );
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
		return $this->msg( 'doubleredirectstext' )->parseAsBlock();
	}

	private function reallyGetQueryInfo( ?int $namespace = null, ?string $title = null ): array {
		$limitToTitle = !( $namespace === null && $title === null );
		$retval = [
			'tables' => [
				'ra' => 'redirect',
				'rb' => 'redirect',
				'pa' => 'page',
				'pb' => 'page'
			],
			'fields' => [
				'namespace' => 'pa.page_namespace',
				'title' => 'pa.page_title',

				'b_namespace' => 'pb.page_namespace',
				'b_title' => 'pb.page_title',
				'b_fragment' => 'ra.rd_fragment',

				// Select fields from redirect instead of page. Because there may
				// not actually be a page table row for this target (e.g. for interwiki redirects)
				'c_namespace' => 'rb.rd_namespace',
				'c_title' => 'rb.rd_title',
				'c_fragment' => 'rb.rd_fragment',
				'c_interwiki' => 'rb.rd_interwiki',
			],
			'conds' => [
				'ra.rd_from = pa.page_id',

				// Filter out redirects where the target goes interwiki (T42353).
				// This isn't an optimization, it is required for correct results,
				// otherwise a non-double redirect like Bar -> w:Foo will show up
				// like "Bar -> Foo -> w:Foo".
				'ra.rd_interwiki' => '',

				'pb.page_namespace = ra.rd_namespace',
				'pb.page_title = ra.rd_title',

				'rb.rd_from = pb.page_id',
			]
		];

		if ( $limitToTitle ) {
			$retval['conds']['pa.page_namespace'] = $namespace;
			$retval['conds']['pa.page_title'] = $title;
		}

		return $retval;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return $this->reallyGetQueryInfo();
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		return [ 'ra.rd_namespace', 'ra.rd_title' ];
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		// If no Title B or C is in the query, it means this came from
		// querycache (which only saves the 3 columns for title A).
		// That does save the bulk of the query cost, but now we need to
		// get a little more detail about each individual entry quickly
		// using the filter of reallyGetQueryInfo.
		$deep = false;
		if ( $result ) {
			if ( isset( $result->b_namespace ) ) {
				$deep = $result;
			} else {
				$qi = $this->reallyGetQueryInfo(
					$result->namespace,
					$result->title
				);
				$deep = $this->getDatabaseProvider()->getReplicaDatabase()->newSelectQueryBuilder()
					->queryInfo( $qi )
					->caller( __METHOD__ )
					->fetchRow();
			}
		}

		$titleA = Title::makeTitle( $result->namespace, $result->title );

		$linkRenderer = $this->getLinkRenderer();
		if ( !$deep ) {
			return '<del>' . $linkRenderer->makeLink( $titleA, null, [], [ 'redirect' => 'no' ] ) . '</del>';
		}

		// if the page is editable, add an edit link
		if (
			// check user permissions
			$this->getAuthority()->isAllowed( 'edit' ) &&
			// check, if the content model is editable through action=edit
			$this->contentHandlerFactory->getContentHandler( $titleA->getContentModel() )
				->supportsDirectEditing()
		) {
			$edit = $linkRenderer->makeKnownLink(
				$titleA,
				$this->msg( 'parentheses', $this->msg( 'editlink' )->text() )->text(),
				[],
				[ 'action' => 'edit' ]
			);
		} else {
			$edit = '';
		}

		$arrow = $this->getLanguage()->getArrow();
		$contentLanguage = $this->getContentLanguage();
		$bdiAttrs = [
			'dir' => $contentLanguage->getDir(),
			'lang' => $contentLanguage->getHtmlCode(),
		];
		$linkA = Html::rawElement( 'bdi', $bdiAttrs, $linkRenderer->makeKnownLink(
			$titleA,
			null,
			[],
			[ 'redirect' => 'no' ]
		) );

		$titleB = Title::makeTitle( $deep->b_namespace, $deep->b_title );
		// We show fragment, but don't link to it, as it probably doesn't exist anymore.
		$titleBFrag = Title::makeTitle( $deep->b_namespace, $deep->b_title, $deep->b_fragment );
		$linkB = Html::rawElement( 'bdi', $bdiAttrs, $linkRenderer->makeKnownLink(
			$titleB,
			$titleBFrag->getFullText(),
			[],
			[ 'redirect' => 'no' ]
		) );

		$titleC = Title::makeTitle(
			$deep->c_namespace,
			$deep->c_title,
			$deep->c_fragment,
			$deep->c_interwiki
		);
		$linkC = Html::rawElement( 'bdi', $bdiAttrs,
			$linkRenderer->makeKnownLink( $titleC, $titleC->getFullText() )
		);

		return ( "{$linkA} {$edit} {$arrow} {$linkB} {$arrow} {$linkC}" );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->addHelpLink( 'Help:Redirects' );
		parent::execute( $par );
	}

	/**
	 * Cache page content model and gender distinction for performance
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
			if ( isset( $row->b_namespace ) ) {
				// lazy loaded when using cached results
				$batch->add( $row->b_namespace, $row->b_title );
			}
			if ( isset( $row->c_interwiki ) && !$row->c_interwiki ) {
				// lazy loaded when using cached result, not added when interwiki link
				$batch->add( $row->c_namespace, $row->c_title );
			}
		}
		$batch->execute();

		// Back to start for display
		$res->seek( 0 );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialDoubleRedirects::class, 'SpecialDoubleRedirects' );
