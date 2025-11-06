<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Deferred\LinksUpdate\CategoryLinksTable;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\SpecialPage\PageQueryPage;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of pages without any category.
 *
 * @todo FIXME: Make $requestedNamespace selectable, unify all subclasses into one
 *
 * @ingroup SpecialPage
 */
class SpecialUncategorizedPages extends PageQueryPage {
	/** @var int|false */
	protected $requestedNamespace = false;

	private NamespaceInfo $namespaceInfo;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Uncategorizedpages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->setLanguageConverter( $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() ) );
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
	public function execute( $par ) {
		$this->addHelpLink( 'Help:Categories' );
		parent::execute( $par );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [
			'tables' => [ 'page', 'categorylinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			// default for page_namespace is all content namespaces (if requestedNamespace is false)
			// otherwise, page_namespace is requestedNamespace
			'conds' => [
				'cl_from' => null,
				'page_namespace' => $this->requestedNamespace !== false
						? $this->requestedNamespace
						: $this->namespaceInfo->getContentNamespaces(),
				'page_is_redirect' => 0
			],
			'join_conds' => [
				'categorylinks' => [ 'LEFT JOIN', 'cl_from = page_id' ]
			]
		];
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			CategoryLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort
		if ( $this->requestedNamespace === false &&
			count( $this->namespaceInfo->getContentNamespaces() ) > 1
		) {
			return [ 'page_namespace', 'page_title' ];
		}

		return [ 'page_title' ];
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUncategorizedPages::class, 'SpecialUncategorizedPages' );
