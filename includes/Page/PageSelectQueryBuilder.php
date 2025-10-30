<?php

namespace MediaWiki\Page;

use Iterator;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @since 1.36
 */
class PageSelectQueryBuilder extends SelectQueryBuilder {

	/** @var PageStore */
	private $pageStore;

	/** @var LinkCache|null */
	private $linkCache;

	/**
	 * @param IReadableDatabase $db
	 * @param PageStore $pageStore
	 * @param LinkCache|null $linkCache A link cache to store any retrieved rows into
	 *
	 * @internal
	 */
	public function __construct( IReadableDatabase $db, PageStore $pageStore, ?LinkCache $linkCache = null ) {
		parent::__construct( $db );
		$this->pageStore = $pageStore;
		$this->table( 'page' );
		$this->linkCache = $linkCache;
	}

	/**
	 * Find by provided page ids.
	 *
	 * @param int|int[] $pageIds
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function wherePageIds( $pageIds ): self {
		Assert::parameterType( [ 'integer', 'array' ], $pageIds, '$pageIds' );

		if ( $pageIds ) {
			$this->conds( [ 'page_id' => $pageIds ] );
		} else {
			$this->conds( '0 = 1' ); // force empty result set
		}

		return $this;
	}

	/**
	 * Find by provided namespace.
	 *
	 * @param int $namespace
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function whereNamespace( int $namespace ): self {
		$this->conds( [ 'page_namespace' => $namespace ] );
		return $this;
	}

	/**
	 * Find by provided prefix.
	 *
	 * @param int $namespace
	 * @param string $prefix
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function whereTitlePrefix( int $namespace, string $prefix ): self {
		$this->whereNamespace( $namespace );
		$this->conds(
			$this->db->expr( 'page_title', IExpression::LIKE, new LikeValue( $prefix, $this->db->anyString() ) )
		);
		return $this;
	}

	/**
	 * Find by provided page titles.
	 *
	 * @param int $namespace
	 * @param string|string[] $pageTitles
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function whereTitles( int $namespace, $pageTitles ): self {
		Assert::parameterType( [ 'string', 'array' ], $pageTitles, '$pageTitles' );
		$this->conds( [ 'page_namespace' => $namespace ] );
		$this->conds( [ 'page_title' => $pageTitles ] );
		return $this;
	}

	/**
	 * Order results by namespace and title in $direction
	 *
	 * @param string $dir one of self::SORT_ASC or self::SORT_DESC
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function orderByTitle( string $dir = self::SORT_ASC ): self {
		$this->orderBy( [ 'page_namespace', 'page_title' ], $dir );
		return $this;
	}

	/**
	 * Order results by page id.
	 *
	 * @param string $dir one of self::SORT_ASC or self::SORT_DESC
	 *
	 * @return PageSelectQueryBuilder
	 */
	public function orderByPageId( string $dir = self::SORT_ASC ): self {
		$this->orderBy( 'page_id', $dir );
		return $this;
	}

	/**
	 * Fetch a single PageRecord that matches specified criteria.
	 *
	 * @return PageRecord|null
	 */
	public function fetchPageRecord(): ?PageRecord {
		$this->fields( $this->pageStore->getSelectFields() );

		$row = $this->fetchRow();
		if ( !$row ) {
			return null;
		}

		$rec = $this->pageStore->newPageRecordFromRow( $row );
		if ( $this->linkCache ) {
			$this->linkCache->addGoodLinkObjFromRow( $rec, $row );
		}
		return $rec;
	}

	/**
	 * Fetch PageRecords for the specified query.
	 *
	 * @return Iterator<ExistingPageRecord>
	 */
	public function fetchPageRecords(): Iterator {
		$this->fields( $this->pageStore->getSelectFields() );

		$result = $this->fetchResultSet();
		foreach ( $result as $row ) {
			$rec = $this->pageStore->newPageRecordFromRow( $row );
			if ( $this->linkCache ) {
				$this->linkCache->addGoodLinkObjFromRow( $rec, $row );
			}
			yield $rec;
		}
		$result->free();
	}

	/**
	 * Fetch PageRecords for the specified query as an associative
	 * array, using page IDs as array keys.
	 *
	 * @return ExistingPageRecord[]
	 */
	public function fetchPageRecordArray(): array {
		$recs = [];

		foreach ( $this->fetchPageRecords() as $rec ) {
			$recs[ $rec->getId() ] = $rec;
		}

		return $recs;
	}

	/**
	 * Returns an array of page ids matching the query.
	 *
	 * @return int[]
	 */
	public function fetchPageIds(): array {
		$this->field( 'page_id' );
		return array_map( 'intval', $this->fetchFieldValues() );
	}

}
