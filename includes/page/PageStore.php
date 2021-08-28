<?php

namespace MediaWiki\Page;

use DBAccessObjectUtils;
use EmptyIterator;
use InvalidArgumentException;
use Iterator;
use MalformedTitleException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Linker\LinkTarget;
use NamespaceInfo;
use stdClass;
use TitleParser;
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

	/** @var TitleParser */
	private $titleParser;

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
	 * @param false|string $wikiId
	 */
	public function __construct(
		ServiceOptions $options,
		ILoadBalancer $dbLoadBalancer,
		NamespaceInfo $namespaceInfo,
		TitleParser $titleParser,
		$wikiId = WikiAwareEntity::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleParser = $titleParser;
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

		return $this->loadPageFromConditions( $conds, $queryFlags );
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

			if ( $page->exists() ) {
				// if we have a page ID, use it
				$id = $page->getId( $this->wikiId );
				return $this->getPageById( $id, $queryFlags );
			}
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
