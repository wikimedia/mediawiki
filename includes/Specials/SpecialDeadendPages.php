<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Deferred\LinksUpdate\PageLinksTable;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\SpecialPage\PageQueryPage;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of pages that contain no links to other pages.
 *
 * @ingroup SpecialPage
 */
class SpecialDeadendPages extends PageQueryPage {

	private NamespaceInfo $namespaceInfo;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Deadendpages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->setLanguageConverter( $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() ) );
	}

	/** @inheritDoc */
	protected function getPageHeader() {
		return $this->msg( 'deadendpagestext' )->parseAsBlock();
	}

	/**
	 * LEFT JOIN is expensive
	 *
	 * @return bool
	 */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/**
	 * @return bool
	 */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [
			'tables' => [ 'page', 'pagelinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'pl_from' => null,
				'page_namespace' => $this->namespaceInfo->getContentNamespaces(),
				'page_is_redirect' => 0
			],
			'join_conds' => [
				'pagelinks' => [
					'LEFT JOIN',
					[ 'page_id=pl_from' ]
				]
			]
		];
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			PageLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort
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
class_alias( SpecialDeadendPages::class, 'SpecialDeadendPages' );
