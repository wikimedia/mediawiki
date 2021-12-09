<?php

namespace MediaWiki\Deferred\LinksUpdate;

use Collation;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\WikiPageFactory;
use NamespaceInfo;
use ParserOutput;
use PurgeJobUtils;
use Title;

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

	/** @var \ILanguageConverter */
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
		foreach ( $parserOutput->getCategories() as $name => $sortKeyPrefix ) {
			// If the sort key is longer then 255 bytes, it is truncated by DB,
			// and then doesn't match when comparing existing vs current
			// categories, causing T27254.
			$sortKeyPrefix = mb_strcut( $sortKeyPrefix, 0, 255 );

			$targetTitle = Title::makeTitleSafe( NS_CATEGORY, $name );
			$this->languageConverter->findVariantLink( $name, $targetTitle, true );

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
		return [ 'cl_to', 'cl_sortkey_prefix' ];
	}

	/**
	 * Get the new link IDs. The link ID is a list with the name in the first
	 * element and the sort key prefix in the second element.
	 *
	 * @return iterable<array>
	 */
	protected function getNewLinkIDs() {
		foreach ( $this->newLinks as $name => [ $prefix, $sortKey ] ) {
			yield [ $name, $prefix ];
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
			$this->existingLinks = [];
			foreach ( $this->fetchExistingRows() as $row ) {
				$this->existingLinks[$row->cl_to] = $row->cl_sortkey_prefix;
			}
		}
		return $this->existingLinks;
	}

	/**
	 * @return \Generator
	 */
	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $name => $sortkey ) {
			yield [ $name, $sortkey ];
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

		$this->insertRow( [
			'cl_to' => $name,
			'cl_sortkey' => $sortKey,
			'cl_timestamp' => $this->getDB()->timestamp(),
			'cl_sortkey_prefix' => $prefix,
			'cl_collation' => $this->collationName,
			'cl_type' => $this->categoryType,
		] );
	}

	protected function deleteLink( $linkId ) {
		$this->deleteRow( [ 'cl_to' => $linkId[0] ] );
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
		$this->invalidateCategories();
		$this->updateCategoryCounts();
	}

	private function invalidateCategories() {
		$changedCategoryNames = array_unique( array_merge(
			array_column( $this->insertedLinks, 0 ),
			array_column( $this->deletedLinks, 0 )
		) );
		PurgeJobUtils::invalidatePages(
			$this->getDB(), NS_CATEGORY, $changedCategoryNames );
	}

	/**
	 * Update all the appropriate counts in the category table.
	 */
	private function updateCategoryCounts() {
		if ( !$this->insertedLinks && !$this->deletedLinks ) {
			return;
		}

		$domainId = $this->getDB()->getDomainID();
		$wp = $this->wikiPageFactory->newFromTitle( $this->getSourcePage() );
		$lbf = $this->getLBFactory();
		$size = $this->getBatchSize();
		// T163801: try to release any row locks to reduce contention
		$lbf->commitAndWaitForReplication(
			__METHOD__, $this->getTransactionTicket(), [ 'domain' => $domainId ] );

		if ( count( $this->insertedLinks ) + count( $this->deletedLinks ) < $size ) {
			$wp->updateCategoryCounts(
				array_column( $this->insertedLinks, 0 ),
				array_column( $this->deletedLinks, 0 ),
				$this->getSourcePageId()
			);
			$lbf->commitAndWaitForReplication(
				__METHOD__, $this->getTransactionTicket(), [ 'domain' => $domainId ] );
		} else {
			$addedChunks = array_chunk( array_column( $this->insertedLinks, 0 ), $size );
			foreach ( $addedChunks as $chunk ) {
				$wp->updateCategoryCounts( $chunk, [], $this->getSourcePageId() );
				$lbf->commitAndWaitForReplication(
					__METHOD__, $this->getTransactionTicket(), [ 'domain' => $domainId ] );
			}

			$deletedChunks = array_chunk( array_column( $this->deletedLinks, 0 ), $size );
			foreach ( $deletedChunks as $chunk ) {
				$wp->updateCategoryCounts( [], $chunk, $this->getSourcePageId() );
				$lbf->commitAndWaitForReplication(
					__METHOD__, $this->getTransactionTicket(), [ 'domain' => $domainId ] );
			}

		}
	}
}
