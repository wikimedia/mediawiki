<?php

namespace MediaWiki\Page;

use DBAccessObjectUtils;
use EmptyIterator;
use InvalidArgumentException;
use Iterator;
use LinkCache;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MalformedTitleException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Linker\LinkTarget;
use NamespaceInfo;
use NullStatsdDataFactory;
use stdClass;
use TitleParser;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

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

	/** @var TitleParser */
	private $titleParser;

	/** @var LinkCache|null */
	private $linkCache;

	/** @var StatsdDataFactoryInterface */
	private $stats;

	/** @var string|false */
	private $wikiId;

	/**
	 * @internal for use by service wiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'PageLanguageUseDB',
	];

	/**
	 * @param ServiceOptions $options
	 * @param ILoadBalancer $dbLoadBalancer
	 * @param NamespaceInfo $namespaceInfo
	 * @param TitleParser $titleParser
	 * @param ?LinkCache $linkCache
	 * @param ?StatsdDataFactoryInterface $stats
	 * @param false|string $wikiId
	 */
	public function __construct(
		ServiceOptions $options,
		ILoadBalancer $dbLoadBalancer,
		NamespaceInfo $namespaceInfo,
		TitleParser $titleParser,
		?LinkCache $linkCache,
		?StatsdDataFactoryInterface $stats,
		$wikiId = WikiAwareEntity::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleParser = $titleParser;
		$this->wikiId = $wikiId;
		$this->linkCache = $linkCache;
		$this->stats = $stats ?: new NullStatsdDataFactory();

		if ( $wikiId !== WikiAwareEntity::LOCAL && $linkCache ) {
			// LinkCache currently doesn't support cross-wiki PageReferences.
			// Once it does, this check can go away. At that point, LinkCache should
			// probably also no longer be optional.
			throw new InvalidArgumentException( "Can't use LinkCache with pages from $wikiId" );
		}
	}

	/**
	 * @param string $metric
	 */
	private function incrementStats( string $metric ) {
		$this->stats->increment( "PageStore.{$metric}" );
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

		Assert::parameter( $ns >= 0, '$link', 'namespace must not be virtual' );

		$page = $this->getPageByName( $ns, $link->getDBkey(), $queryFlags );

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

		if ( $this->linkCache ) {
			return $this->getPageByNameViaLinkCache( $namespace, $dbKey, $queryFlags );
		} else {
			return $this->loadPageFromConditions( $conds, $queryFlags );
		}
	}

	/**
	 * @param int $namespace
	 * @param string $dbKey
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null
	 */
	private function getPageByNameViaLinkCache(
		int $namespace,
		string $dbKey,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord {
		$conds = [
			'page_namespace' => $namespace,
			'page_title' => $dbKey,
		];

		if ( $queryFlags === self::READ_NORMAL && $this->linkCache->isBadLink( $conds ) ) {
			$this->incrementStats( "LinkCache.hit.bad.early" );
			return null;
		}

		$caller = __METHOD__;
		$hitOrMiss = 'hit';

		// Try to get the row from LinkCache, providing a callback to fetch it if it's not cached.
		// When getGoodLinkRow() returns, LinkCache should have an entry for the row, good or bad.
		$row = $this->linkCache->getGoodLinkRow(
			$namespace,
			$dbKey,
			function ( IDatabase $dbr, $ns, $dbkey, array $options )
				use ( $conds, $caller, &$hitOrMiss )
			{
				$hitOrMiss = 'miss';
				$row = $this->newSelectQueryBuilder( $dbr )
					->fields( $this->getSelectFields() )
					->conds( $conds )
					->options( $options )
					->caller( $caller )
					->fetchRow();

				return $row;
			},
			$queryFlags
		);

		if ( $row ) {
			try {
				// NOTE: LinkCache may not include namespace and title in the cached row,
				//       since it's already used as the cache key!
				$row->page_namespace = $namespace;
				$row->page_title = $dbKey;
				$page = $this->newPageRecordFromRow( $row );

				// We were able to use the row we got from link cache.
				$this->incrementStats( "LinkCache.{$hitOrMiss}.good" );
			} catch ( InvalidArgumentException $e ) {
				// The cached row was incomplete or corrupt,
				// just keep going and load from the database.
				$page = $this->loadPageFromConditions( $conds, $queryFlags );

				if ( $page ) {
					// PageSelectQueryBuilder should have added the full row to the LinkCache now.
					$this->incrementStats( "LinkCache.{$hitOrMiss}.incomplete.loaded" );
				} else {
					// If we get here, an incomplete row was cached, but we failed to
					// load the full row from the database. This should only happen
					// if the page was deleted under out feet, which should be very rare.
					// Update the LinkCache to reflect the new situation.
					$this->linkCache->addBadLinkObj( $conds );
					$this->incrementStats( "LinkCache.{$hitOrMiss}.incomplete.missing" );
				}
			}
		} else {
			$this->incrementStats( "LinkCache.{$hitOrMiss}.bad.late" );
			$page = null;
		}

		return $page;
	}

	/**
	 * @since 1.37
	 *
	 * @param string $text
	 * @param int $defaultNamespace Namespace to assume per default (usually NS_MAIN)
	 * @param int $queryFlags
	 *
	 * @return ProperPageIdentity|null
	 */
	public function getPageByText(
		string $text,
		int $defaultNamespace = NS_MAIN,
		int $queryFlags = self::READ_NORMAL
	): ?ProperPageIdentity {
		try {
			$title = $this->titleParser->parseTitle( $text, $defaultNamespace );
			return $this->getPageForLink( $title, $queryFlags );
		} catch ( MalformedTitleException | InvalidArgumentException $e ) {
			// Note that even some well-formed links are still invalid parameters
			// for getPageForLink(), e.g. interwiki links or special pages.
			return null;
		}
	}

	/**
	 * @since 1.37
	 *
	 * @param string $text
	 * @param int $defaultNamespace Namespace to assume per default (usually NS_MAIN)
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null
	 */
	public function getExistingPageByText(
		string $text,
		int $defaultNamespace = NS_MAIN,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord {
		$pageIdentity = $this->getPageByText( $text, $defaultNamespace, $queryFlags );
		if ( !$pageIdentity ) {
			return null;
		}
		return $this->getPageByReference( $pageIdentity, $queryFlags );
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

		// XXX: no caching needed?

		return $this->loadPageFromConditions( $conds, $queryFlags );
	}

	/**
	 * @param PageReference $page
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null The page's PageRecord, or null if the page was not found.
	 */
	public function getPageByReference(
		PageReference $page,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord {
		$page->assertWiki( $this->wikiId );
		Assert::parameter( $page->getNamespace() >= 0, '$page', 'namespace must not be virtual' );

		if ( $page instanceof ExistingPageRecord && $queryFlags === self::READ_NORMAL ) {
			return $page;
		}
		if ( $page instanceof PageIdentity ) {
			Assert::parameter( $page->canExist(), '$page', 'Must be a proper page' );
		}
		return $this->getPageByName( $page->getNamespace(), $page->getDBkey(), $queryFlags );
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

		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
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

		// Since we are putting rows into LinkCache, we need to include all fields
		// that LinkCache needs.
		$fields = array_unique(
			array_merge( $fields, LinkCache::getSelectFields() )
		);

		return $fields;
	}

	/**
	 * @unstable
	 *
	 * @param IDatabase|int $dbOrFlags The database connection to use, or a READ_XXX constant
	 *        indicating what kind of database connection to use.
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function newSelectQueryBuilder( $dbOrFlags = self::READ_NORMAL ): PageSelectQueryBuilder {
		if ( $dbOrFlags instanceof IDatabase ) {
			$db = $dbOrFlags;
			$options = [];
		} else {
			[ $mode, $options ] = DBAccessObjectUtils::getDBOptions( $dbOrFlags );
			$db = $this->getDBConnectionRef( $mode );
		}

		$queryBuilder = new PageSelectQueryBuilder( $db, $this, $this->linkCache );
		$queryBuilder->options( $options );

		return $queryBuilder;
	}

	/**
	 * @param int $mode DB_PRIMARY or DB_REPLICA
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
