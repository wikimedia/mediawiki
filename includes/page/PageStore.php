<?php

namespace MediaWiki\Page;

use DBAccessObjectUtils;
use EmptyIterator;
use Iterator;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Linker\LinkTarget;
use NamespaceInfo;
use stdClass;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @since 1.36
 *
 * @unstable
 */
class PageStore implements PageLookup {

	/** @var ServiceOptions */
	private $options;

	/** @var ILoadBalancer */
	private $dbLoadBalancer;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var string|false */
	private $wikiId;

	/**
	 * @internal for use by service wiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'LanguageCode',
		'PageLanguageUseDB',
	];

	/**
	 * @param ServiceOptions $options
	 * @param ILoadBalancer $dbLoadBalancer
	 * @param NamespaceInfo $namespaceInfo
	 * @param false|string $wikiId
	 */
	public function __construct(
		ServiceOptions $options,
		ILoadBalancer $dbLoadBalancer,
		NamespaceInfo $namespaceInfo,
		$wikiId = WikiAwareEntity::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->namespaceInfo = $namespaceInfo;
		$this->wikiId = $wikiId;
	}

	/**
	 * @param LinkTarget $link
	 * @param int $queryFlags
	 *
	 * @return ProperPageIdentity
	 */
	public function getPageForLink(
		LinkTarget $link,
		int $queryFlags = self::READ_NORMAL
	): ProperPageIdentity {
		Assert::parameter( !$link->isExternal(), '$link', 'must not be external' );
		Assert::parameter( $link->getDBkey() !== '', '$link', 'must not be relative' );

		$ns = $link->getNamespace();

		// Map Media links to File namespace
		if ( $ns === NS_MEDIA ) {
			$ns = NS_FILE;
		}

		Assert::parameter( $ns >= 0, '$link', 'must not be virtual' );

		$page = $this->getPageByName( $ns, $link->getDBkey() );

		if ( !$page ) {
			$page = new PageIdentityValue( 0, $ns, $link->getDBkey(), $this->wikiId );
		}

		return $page;
	}

	/**
	 * @param int $namespace
	 * @param string $dbKey
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null
	 */
	public function getPageByName(
		int $namespace,
		string $dbKey,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord {
		Assert::parameter( $dbKey !== '', '$dbKey', 'must not be empty' );
		Assert::parameter( !strpos( $dbKey, ' ' ), '$dbKey', 'must not contain spaces' );
		Assert::parameter( $namespace >= 0, '$namespace', 'must not be virtual' );

		$conds = [
			'page_namespace' => $namespace,
			'page_title' => $dbKey,
		];

		return $this->loadPageFromConditions( $conds, $queryFlags );
	}

	/**
	 * @param int $pageId
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null
	 */
	public function getPageById(
		int $pageId,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord {
		Assert::parameter( $pageId > 0, '$pageId', 'must be greater than zero' );

		$conds = [
			'page_id' => $pageId,
		];

		return $this->loadPageFromConditions( $conds, $queryFlags );
	}

	/**
	 * @param PageIdentity $page
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null The page's PageRecord, or null if the page was not found.
	 */
	public function getPageByIdentity(
		PageIdentity $page,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord {
		Assert::parameter( $page->canExist(), '$page', 'Mut be a proper page' );

		$page->assertWiki( $this->wikiId );

		if ( $page instanceof ExistingPageRecord && $queryFlags === self::READ_NORMAL ) {
			return $page;
		}

		if ( !$page->exists() ) {
			return null;
		}

		$id = $page->getId( $this->wikiId );
		return $this->getPageById( $id );
	}

	/**
	 * @param array $conds
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null
	 */
	private function loadPageFromConditions(
		array $conds,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord {
		$queryBuilder = $this->newSelectQueryBuilder( $queryFlags )
			->conds( $conds )
			->caller( __METHOD__ );

		return $queryBuilder->fetchPageRecord();
	}

	/**
	 * @internal
	 *
	 * @param stdClass $row
	 *
	 * @return ExistingPageRecord
	 */
	public function newPageRecordFromRow( stdClass $row ): ExistingPageRecord {
		if ( empty( $row->page_lang ) ) {
			$row->page_lang = $this->options->get( 'LanguageCode' );
		}

		return new PageStoreRecord(
			$row,
			$this->wikiId
		);
	}

	/**
	 * @internal
	 *
	 * @return string[]
	 */
	public function getSelectFields(): array {
		$fields = [
			'page_id',
			'page_namespace',
			'page_title',
			'page_is_redirect',
			'page_is_new',
			'page_touched',
			'page_links_updated',
			'page_latest',
			'page_len',
			'page_content_model'
		];

		if ( $this->options->get( 'PageLanguageUseDB' ) ) {
			$fields[] = 'page_lang';
		}

		return $fields;
	}

	/**
	 * @unstable
	 *
	 * @param IDatabase|int|null $dbOrFlags The database connection to use, or a READ_XXX constant
	 *        indicating what kind of database connection to use.
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function newSelectQueryBuilder( $dbOrFlags = self::READ_NORMAL ): SelectQueryBuilder {
		if ( $dbOrFlags instanceof IDatabase ) {
			$db = $dbOrFlags;
			$options = [];
		} else {
			[ $mode, $options ] = DBAccessObjectUtils::getDBOptions( $dbOrFlags );
			$db = $this->getDBConnectionRef( $mode );
		}

		$queryBuilder = new PageSelectQueryBuilder( $db, $this );
		$queryBuilder->options( $options );

		return $queryBuilder;
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 * @return IDatabase
	 */
	private function getDBConnectionRef( int $mode = DB_REPLICA ): IDatabase {
		return $this->dbLoadBalancer->getConnectionRef( $mode, [], $this->wikiId );
	}

	/**
	 * Get all subpages of this page.
	 * Will return an empty list of the namespace doesn't support subpages.
	 *
	 * @param PageIdentity $page
	 * @param int $limit Maximum number of subpages to fetch
	 *
	 * @return Iterator<ExistingPageRecord>
	 */
	public function getSubpages( PageIdentity $page, int $limit ): Iterator {
		if ( !$this->namespaceInfo->hasSubpages( $page->getNamespace() ) ) {
			return new EmptyIterator();
		}

		return $this->newSelectQueryBuilder()
			->whereTitlePrefix( $page->getNamespace(), $page->getDBkey() . '/' )
			->orderByTitle()
			->options( [ 'LIMIT' => $limit ] )
			->caller( __METHOD__ )
			->fetchPageRecords();
	}

}
