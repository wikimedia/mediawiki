<?php

namespace MediaWiki\Deferred\LinksUpdate;

use Collation;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use PurgeJobUtils;

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

	/**
	 * @param LanguageConverterFactory $converterFactory
	 * @param NamespaceInfo $namespaceInfo
	 * @param WikiPageFactory $wikiPageFactory
	 * @param Collation $collation
	 * @param string $collationName
	 * @param string $tableName
	 * @param bool $isTempTable
	 */
	public function __construct(
		LanguageConverterFactory $converterFactory,
		NamespaceInfo $namespaceInfo,
		WikiPageFactory $wikiPageFactory,
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
		foreach ( $parserOutput->getCategoryNames() as $name ) {
			$sortKey = $parserOutput->getCategorySortKey( $name );
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

			$targetTitle = Title::makeTitle( NS_CATEGORY, $name );
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

	protected function getTableName() {
		return $this->tableName;
	}

	protected function getFromField() {
		return 'cl_from';
	}

	protected function getExistingFields() {
		$fields = [ 'cl_to', 'cl_sortkey_prefix' ];
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
			$this->existingLinks[$row->cl_to] = $row->cl_sortkey_prefix;
			if ( $force ) {
				$this->savedTimestamps[$row->cl_to] = $row->cl_timestamp;
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

	private function getSavedTimestamps() {
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

	protected function isExisting( $linkId ) {
		$links = $this->getExistingLinks();
		[ $name, $prefix ] = $linkId;
		return \array_key_exists( $name, $links ) && $links[$name] === $prefix;
	}

	protected function isInNewSet( $linkId ) {
		[ $name, $prefix ] = $linkId;
		return \array_key_exists( $name, $this->newLinks )
			&& $this->newLinks[$name][0] === $prefix;
	}

	protected function insertLink( $linkId ) {
		[ $name, $prefix ] = $linkId;
		$sortKey = $this->newLinks[$name][1];
		$savedTimestamps = $this->getSavedTimestamps();

		// Preserve cl_timestamp in the case of a forced refresh
		$timestamp = $this->getDB()->timestamp( $savedTimestamps[$name] ?? 0 );

		$this->insertRow( [
			'cl_to' => $name,
			'cl_sortkey' => $sortKey,
			'cl_timestamp' => $timestamp,
			'cl_sortkey_prefix' => $prefix,
			'cl_collation' => $this->collationName,
			'cl_type' => $this->categoryType,
		] );
	}

	protected function deleteLink( $linkId ) {
		$this->deleteRow( [ 'cl_to' => $linkId[0] ] );
	}

	protected function needForcedLinkRefresh() {
		// cl_sortkey and possibly cl_type will change if it is a page move
		return $this->isMove();
	}

	protected function makePageReferenceValue( $linkId ): PageReferenceValue {
		return new PageReferenceValue( NS_CATEGORY, $linkId[0], WikiAwareEntity::LOCAL );
	}

	protected function makeTitle( $linkId ): Title {
		return Title::makeTitle( NS_CATEGORY, $linkId[0] );
	}

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
}
