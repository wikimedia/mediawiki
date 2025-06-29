<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\JobQueue\Utils\PurgeJobUtils;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\Title\Title;

/**
 * imagelinks
 *
 * Link ID format: string image name
 *
 * @since 1.38
 */
class ImageLinksTable extends TitleLinksTable {
	/**
	 * @var array New links with the name in the key, value arbitrary
	 */
	private $newLinks;

	/**
	 * @var array Existing links with the name in the key, value arbitrary
	 */
	private $existingLinks;

	public function setParserOutput( ParserOutput $parserOutput ) {
		// Convert the format of the local links
		$this->newLinks = [];
		foreach (
			$parserOutput->getLinkList( ParserOutputLinkTypes::MEDIA )
			as [ 'link' => $link ]
		) {
			$this->newLinks[$link->getDBkey()] = 1;
		}
	}

	/** @inheritDoc */
	protected function getTableName() {
		return 'imagelinks';
	}

	/** @inheritDoc */
	protected function getFromField() {
		return 'il_from';
	}

	/** @inheritDoc */
	protected function getExistingFields() {
		return [ 'il_to' ];
	}

	/** @inheritDoc */
	protected function getNewLinkIDs() {
		foreach ( $this->newLinks as $link => $unused ) {
			yield (string)$link;
		}
	}

	/**
	 * Get existing links with the name in the key, value arbitrary.
	 *
	 * @return array
	 */
	private function getExistingLinks() {
		if ( $this->existingLinks === null ) {
			$this->existingLinks = [];
			foreach ( $this->fetchExistingRows() as $row ) {
				$this->existingLinks[$row->il_to] = true;
			}
		}
		return $this->existingLinks;
	}

	/** @inheritDoc */
	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $link => $unused ) {
			yield (string)$link;
		}
	}

	/** @inheritDoc */
	protected function isExisting( $linkId ) {
		return \array_key_exists( $linkId, $this->getExistingLinks() );
	}

	/** @inheritDoc */
	protected function isInNewSet( $linkId ) {
		return \array_key_exists( $linkId, $this->newLinks );
	}

	/** @inheritDoc */
	protected function needForcedLinkRefresh() {
		return $this->isCrossNamespaceMove();
	}

	/** @inheritDoc */
	protected function insertLink( $linkId ) {
		$this->insertRow( [
			'il_from_namespace' => $this->getSourcePage()->getNamespace(),
			'il_to' => $linkId
		] );
	}

	/** @inheritDoc */
	protected function deleteLink( $linkId ) {
		$this->deleteRow( [ 'il_to' => $linkId ] );
	}

	/** @inheritDoc */
	protected function makePageReferenceValue( $linkId ): PageReferenceValue {
		return PageReferenceValue::localReference( NS_FILE, $linkId );
	}

	/** @inheritDoc */
	protected function makeTitle( $linkId ): Title {
		return Title::makeTitle( NS_FILE, $linkId );
	}

	/** @inheritDoc */
	protected function deduplicateLinkIds( $linkIds ) {
		if ( !is_array( $linkIds ) ) {
			$linkIds = iterator_to_array( $linkIds );
		}
		return array_unique( $linkIds );
	}

	protected function finishUpdate() {
		// A update of namespace on cross namespace move is detected as insert + delete,
		// but the updates are not needed there.
		$allInsertedLinks = array_column( $this->insertedLinks, 0 );
		$allDeletedLinks = array_column( $this->deletedLinks, 0 );
		$insertedLinks = array_diff( $allInsertedLinks, $allDeletedLinks );
		$deletedLinks = array_diff( $allDeletedLinks, $allInsertedLinks );

		$this->invalidateImageDescriptions( $insertedLinks, $deletedLinks );
	}

	/**
	 * Invalidate all image description pages which had links added or removed
	 * @param array $insertedLinks
	 * @param array $deletedLinks
	 */
	private function invalidateImageDescriptions( array $insertedLinks, array $deletedLinks ) {
		PurgeJobUtils::invalidatePages(
			$this->getDB(), NS_FILE,
			array_merge( $insertedLinks, $deletedLinks ) );
	}
}
