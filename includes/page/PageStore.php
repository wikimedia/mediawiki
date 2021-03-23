<?php

namespace MediaWiki\Page;

use DBAccessObjectUtils;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Linker\LinkTarget;
use stdClass;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @since 1.36
 */
class PageStore implements PageLookup {

	/** @var string|false */
	private $wikiId;

	/** @var ILoadBalancer */
	private $dbLoadBalancer;

	/** @var ServiceOptions */
	private $options;

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
	 * @param false|string $wikiId
	 */
	public function __construct(
		ServiceOptions $options,
		ILoadBalancer $dbLoadBalancer,
		$wikiId = WikiAwareEntity::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->wikiId = $wikiId;
		$this->options = $options;
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
		[ $mode, $options ] = DBAccessObjectUtils::getDBOptions( $queryFlags );

		$dbr = $this->getDBConnectionRef( $mode );
		$queryBuilder = $this->getSelectQueryBuilder( $dbr )
			->options( $options )
			->conds( $conds )
			->caller( __METHOD__ );

		$row = $queryBuilder->fetchRow();

		if ( !$row ) {
			return null;
		}
		return $this->newPageRecordFromRow( $row );
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
	 * @unstable
	 *
	 * @param IDatabase|null $db
	 *
	 * @return SelectQueryBuilder
	 */
	public function getSelectQueryBuilder( IDatabase $db = null ): SelectQueryBuilder {
		$db = $db ?: $this->getDBConnectionRef();
		$queryBuilder = $db->newSelectQueryBuilder();
		$queryBuilder->table( 'page' );
		$queryBuilder->fields( [
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
		] );

		if ( $this->options->get( 'PageLanguageUseDB' ) ) {
			$queryBuilder->field( 'page_lang' );
		}

		return $queryBuilder;
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 * @return IDatabase
	 */
	private function getDBConnectionRef( int $mode = DB_REPLICA ): IDatabase {
		return $this->dbLoadBalancer->getConnectionRef( $mode, [], $this->wikiId );
	}

}
