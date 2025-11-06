<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\SpecialPage\PageQueryPage;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of articles with no article linking to them,
 * thus being lonely.
 *
 * @ingroup SpecialPage
 */
class SpecialLonelyPages extends PageQueryPage {

	private NamespaceInfo $namespaceInfo;
	private LinksMigration $linksMigration;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Lonelypages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->setLanguageConverter( $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() ) );
		$this->linksMigration = $linksMigration;
	}

	/** @inheritDoc */
	protected function getPageHeader() {
		return $this->msg( 'lonelypagestext' )->parseAsBlock();
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
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
	public function getQueryInfo() {
		$queryInfo = $this->linksMigration->getQueryInfo( 'pagelinks', 'pagelinks', 'LEFT JOIN' );
		$tables = [ 'page', 'linktarget', 'templatelinks', 'pagelinks' ];
		$conds = [
			'pl_from' => null,
			'page_namespace' => $this->namespaceInfo->getContentNamespaces(),
			'page_is_redirect' => 0,
			'tl_from' => null,
		];
		$joinConds = [
			'templatelinks' => [ 'LEFT JOIN', [ 'tl_target_id=lt_id' ] ],
			'linktarget' => [
				'LEFT JOIN', [
					"lt_namespace = page_namespace",
					"lt_title = page_title"
				]
			]
		];

		if ( !in_array( 'linktarget', $queryInfo['tables'] ) ) {
			$joinConds['pagelinks'] = [
				'LEFT JOIN', [
					"pl_namespace = page_namespace",
					"pl_title = page_title"
				]
			];
		}

		// Allow extensions to modify the query
		$this->getHookRunner()->onLonelyPagesQuery( $tables, $conds, $joinConds );

		return [
			'tables' => $tables,
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => $conds,
			'join_conds' => array_merge( $joinConds, $queryInfo['joins'] )
		];
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort in MySQL 5
		if ( count( $this->namespaceInfo->getContentNamespaces() ) > 1 ) {
			return [ 'page_namespace', 'page_title' ];
		} else {
			return [ 'page_title' ];
		}
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialLonelyPages::class, 'SpecialLonelyPages' );
