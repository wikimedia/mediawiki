<?php

namespace MediaWiki\Deferred\LinksUpdate;

use Collation;
use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\Utils\PurgeJobUtils;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * categorylinks
 *
 * Link ID format: string[]
 *   - 0: Category name
 *   - 1: User-specified sort key (cl_sortkey_prefix)
 *
 * @since 1.38
 */
class CategoryLinksTable extends TitleLinksTable {
	/**
	 * @var array Associative array of new links, with the category name in the
	 *   key. The value is a list consisting of the sort key prefix and the sort
	 *   key.
	 */
	private $newLinks = [];

	/**
	 * @var array|null Associative array of existing links, or null if it has
	 *   not been loaded yet
	 */
	private $existingLinks;

	/**
	 * @var array Associative array of saved timestamps, if there is a force
	 *   refresh due to a page move
	 */
	private $savedTimestamps = null;

	/** @var ILanguageConverter */
	private $languageConverter;

	/** @var \Collation */
	private $collation;

	/** @var string The collation name for cl_collation */
	private $collationName;

	/** @var string The table name */
	private $tableName = 'categorylinks';

	/** @var bool */
	private $isTempTable;

	/** @var string The category type, which depends on the source page */
	private $categoryType;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	private const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CategoryLinksSchemaMigrationStage,
	];

	/** @var int */
	private $migrationStage;

	private NameTableStore $collationNameStore;

	/**
	 * @param LanguageConverterFactory $converterFactory
	 * @param NamespaceInfo $namespaceInfo
	 * @param WikiPageFactory $wikiPageFactory
	 * @param ILoadBalancer $loadBalancer
	 * @param WANObjectCache $WANObjectCache
	 * @param Config $config
	 * @param Collation $collation
	 * @param string $collationName
	 * @param string $tableName
	 * @param bool $isTempTable
	 */
	public function __construct(
		LanguageConverterFactory $converterFactory,
		NamespaceInfo $namespaceInfo,
		WikiPageFactory $wikiPageFactory,
		ILoadBalancer $loadBalancer,
		WANObjectCache $WANObjectCache,
		Config $config,
		Collation $collation,
		$collationName,
		$tableName,
		$isTempTable
	) {
		$this->languageConverter = $converterFactory->getLanguageConverter();
		$this->namespaceInfo = $namespaceInfo;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->collation = $collation;
		$this->collationName = $collationName;
		$this->tableName = $tableName;
		$this->isTempTable = $isTempTable;

		$options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->migrationStage = $options->get( MainConfigNames::CategoryLinksSchemaMigrationStage );
		$this->collationNameStore = new NameTableStore(
			$loadBalancer,
			$WANObjectCache,
			LoggerFactory::getInstance( 'SecondaryDataUpdate' ),
			'collation',
			'collation_id',
			'collation_name'
		);
	}

	/**
	 * Cache the category type after the source page has been set
	 */
	public function startUpdate() {
		$this->categoryType = $this->namespaceInfo
			->getCategoryLinkType( $this->getSourcePage()->getNamespace() );
	}

	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->newLinks = [];
		$sourceTitle = Title::castFromPageIdentity( $this->getSourcePage() );
		$sortKeyInputs = [];
		foreach (
			$parserOutput->getLinkList( ParserOutputLinkTypes::CATEGORY )
			as [ 'link' => $targetTitle, 'sort' => $sortKey ]
		) {
			'@phan-var string $sortKey'; // sort key will never be null

			if ( $sortKey == '' ) {
				$sortKey = $parserOutput->getPageProperty( "defaultsort" ) ?? '';
			}
			$sortKey = $this->languageConverter->convertCategoryKey( $sortKey );

			// Clean up the sort key, regardless of source
			$sortKey = Sanitizer::decodeCharReferences( $sortKey );
			$sortKey = str_replace( "\n", '', $sortKey );

			// If the sort key is longer then 255 bytes, it is truncated by DB,
			// and then doesn't match when comparing existing vs current
			// categories, causing T27254.
			$sortKeyPrefix = mb_strcut( $sortKey, 0, 255 );

			$name = $targetTitle->getDBkey();
			$targetTitle = Title::castFromLinkTarget( $targetTitle );
			$this->languageConverter->findVariantLink( $name, $targetTitle, true );
			// Ignore the returned text, DB key should be used for links (T328477).
			$name = $targetTitle->getDBKey();

			// Treat custom sort keys as a prefix, so that if multiple
			// things are forced to sort as '*' or something, they'll
			// sort properly in the category rather than in page_id
			// order or such.
			$sortKeyInputs[$name] = $sourceTitle->getCategorySortkey( $sortKeyPrefix );
			$this->newLinks[$name] = [ $sortKeyPrefix ];
		}
		$sortKeys = $this->collation->getSortKeys( $sortKeyInputs );
		foreach ( $sortKeys as $name => $sortKey ) {
			$this->newLinks[$name][1] = $sortKey;
		}
	}

	/** @inheritDoc */
	protected function getTableName() {
		return $this->tableName;
	}

	/** @inheritDoc */
	protected function getFromField() {
		return 'cl_from';
	}

	/** @inheritDoc */
	protected function getExistingFields() {
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			$fields = [ 'cl_to', 'cl_sortkey_prefix' ];
		} else {
			$fields = [ 'lt_title', 'cl_sortkey_prefix' ];
		}
		if ( $this->needForcedLinkRefresh() ) {
			$fields[] = 'cl_timestamp';
		}
		return $fields;
	}

	/**
	 * Get the new link IDs. The link ID is a list with the name in the first
	 * element and the sort key prefix in the second element.
	 *
	 * @return iterable<array>
	 */
	protected function getNewLinkIDs() {
		foreach ( $this->newLinks as $name => [ $prefix, ] ) {
			yield [ (string)$name, $prefix ];
		}
	}

	/**
	 * Get the existing links from the database
	 */
	private function fetchExistingLinks() {
		$this->existingLinks = [];
		$this->savedTimestamps = [];
		$force = $this->needForcedLinkRefresh();
		foreach ( $this->fetchExistingRows() as $row ) {
			if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
				$title = $row->cl_to;
			} else {
				$title = $row->lt_title;
			}
			$this->existingLinks[$title] = $row->cl_sortkey_prefix;
			if ( $force ) {
				$this->savedTimestamps[$title] = $row->cl_timestamp;
			}
		}
	}

	/**
	 * Get the existing links as an associative array, with the category name
	 * in the key and the sort key prefix in the value.
	 *
	 * @return array
	 */
	private function getExistingLinks() {
		if ( $this->existingLinks === null ) {
			$this->fetchExistingLinks();
		}
		return $this->existingLinks;
	}

	private function getSavedTimestamps(): array {
		if ( $this->savedTimestamps === null ) {
			$this->fetchExistingLinks();
		}
		return $this->savedTimestamps;
	}

	/**
	 * @return \Generator
	 */
	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $name => $sortkey ) {
			yield [ (string)$name, $sortkey ];
		}
	}

	/** @inheritDoc */
	protected function isExisting( $linkId ) {
		$links = $this->getExistingLinks();
		[ $name, $prefix ] = $linkId;
		return \array_key_exists( $name, $links ) && $links[$name] === $prefix;
	}

	/** @inheritDoc */
	protected function isInNewSet( $linkId ) {
		[ $name, $prefix ] = $linkId;
		return \array_key_exists( $name, $this->newLinks )
			&& $this->newLinks[$name][0] === $prefix;
	}

	/** @inheritDoc */
	protected function insertLink( $linkId ) {
		[ $name, $prefix ] = $linkId;
		$sortKey = $this->newLinks[$name][1];
		$savedTimestamps = $this->getSavedTimestamps();

		// Preserve cl_timestamp in the case of a forced refresh
		$timestamp = $this->getDB()->timestamp( $savedTimestamps[$name] ?? 0 );

		$targetFields = [];
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_NEW ) {
			$targetFields['cl_target_id'] = $this->linkTargetLookup->acquireLinkTargetId(
				$this->makeTitle( $linkId ),
				$this->getDB()
			);
			$targetFields['cl_collation_id'] = $this->collationNameStore->acquireId( $this->collationName );
		}
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			$targetFields['cl_to'] = $name;
			$targetFields['cl_collation'] = $this->collationName;
		}

		$this->insertRow( array_merge( [
			'cl_sortkey' => $sortKey,
			'cl_timestamp' => $timestamp,
			'cl_sortkey_prefix' => $prefix,
			'cl_type' => $this->categoryType,
		], $targetFields ) );
	}

	/** @inheritDoc */
	protected function deleteLink( $linkId ) {
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			$this->deleteRow( [ 'cl_to' => $linkId[0] ] );
		} else {
			$this->deleteRow( [
				'cl_target_id' => $this->linkTargetLookup->acquireLinkTargetId(
					$this->makeTitle( $linkId ),
					$this->getDB()
				)
			] );
		}
	}

	/** @inheritDoc */
	protected function needForcedLinkRefresh() {
		// cl_sortkey and possibly cl_type will change if it is a page move
		return $this->isMove();
	}

	/** @inheritDoc */
	protected function makePageReferenceValue( $linkId ): PageReferenceValue {
		return PageReferenceValue::localReference( NS_CATEGORY, $linkId[0] );
	}

	/** @inheritDoc */
	protected function makeTitle( $linkId ): Title {
		return Title::makeTitle( NS_CATEGORY, $linkId[0] );
	}

	/** @inheritDoc */
	protected function deduplicateLinkIds( $linkIds ) {
		$seen = [];
		foreach ( $linkIds as $linkId ) {
			if ( !\array_key_exists( $linkId[0], $seen ) ) {
				$seen[$linkId[0]] = true;
				yield $linkId;
			}
		}
	}

	protected function finishUpdate() {
		if ( $this->isTempTable ) {
			// Don't do invalidations for temporary collations
			return;
		}

		// A update of sortkey on move is detected as insert + delete,
		// but the categories does not need to update the counters or invalidate caches
		$allInsertedLinks = array_column( $this->insertedLinks, 0 );
		$allDeletedLinks = array_column( $this->deletedLinks, 0 );
		$insertedLinks = array_diff( $allInsertedLinks, $allDeletedLinks );
		$deletedLinks = array_diff( $allDeletedLinks, $allInsertedLinks );

		$this->invalidateCategories( $insertedLinks, $deletedLinks );
		$this->updateCategoryCounts( $insertedLinks, $deletedLinks );
	}

	private function invalidateCategories( array $insertedLinks, array $deletedLinks ) {
		$changedCategoryNames = array_merge(
			$insertedLinks,
			$deletedLinks
		);
		PurgeJobUtils::invalidatePages(
			$this->getDB(), NS_CATEGORY, $changedCategoryNames );
	}

	/**
	 * Update all the appropriate counts in the category table.
	 * @param array $insertedLinks
	 * @param array $deletedLinks
	 */
	private function updateCategoryCounts( array $insertedLinks, array $deletedLinks ) {
		if ( !$insertedLinks && !$deletedLinks ) {
			return;
		}

		$domainId = $this->getDB()->getDomainID();
		$wp = $this->wikiPageFactory->newFromTitle( $this->getSourcePage() );
		$lbf = $this->getLBFactory();
		$size = $this->getBatchSize();
		// T163801: try to release any row locks to reduce contention
		$lbf->commitAndWaitForReplication( __METHOD__, $this->getTransactionTicket() );

		if ( count( $insertedLinks ) + count( $deletedLinks ) < $size ) {
			$wp->updateCategoryCounts(
				$insertedLinks,
				$deletedLinks,
				$this->getSourcePageId()
			);
			$lbf->commitAndWaitForReplication( __METHOD__, $this->getTransactionTicket() );
		} else {
			$addedChunks = array_chunk( $insertedLinks, $size );
			foreach ( $addedChunks as $chunk ) {
				$wp->updateCategoryCounts( $chunk, [], $this->getSourcePageId() );
				if ( count( $addedChunks ) > 1 ) {
					$lbf->commitAndWaitForReplication( __METHOD__, $this->getTransactionTicket() );
				}
			}

			$deletedChunks = array_chunk( $deletedLinks, $size );
			foreach ( $deletedChunks as $chunk ) {
				$wp->updateCategoryCounts( [], $chunk, $this->getSourcePageId() );
				if ( count( $deletedChunks ) > 1 ) {
					$lbf->commitAndWaitForReplication( __METHOD__, $this->getTransactionTicket() );
				}
			}

		}
	}

	protected function linksTargetNormalizationStage(): int {
		return $this->migrationStage;
	}

	protected function fetchExistingRows(): IResultWrapper {
		$queryBuilder = $this->getDB()->newSelectQueryBuilder()
			->select( $this->getExistingFields() )
			->from( $this->getTableName() )
			->where( $this->getFromConds() );

		// This read is for updating, it's conceptually better to use the write config
		if ( !( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) ) {
			$queryBuilder->join( 'linktarget', null, [ 'cl_target_id=lt_id' ] );
		}

		return $queryBuilder
			->caller( __METHOD__ )
			->fetchResultSet();
	}
}
