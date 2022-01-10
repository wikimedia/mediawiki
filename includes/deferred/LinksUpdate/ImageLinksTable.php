<?php

namespace MediaWiki\Deferred\LinksUpdate;

use ChangeTags;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Page\PageReferenceValue;
use ParserOutput;
use PurgeJobUtils;
use Title;

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
		$this->newLinks = $parserOutput->getImages();
	}

	protected function getTableName() {
		return 'imagelinks';
	}

	protected function getFromField() {
		return 'il_from';
	}

	protected function getExistingFields() {
		return [ 'il_to' ];
	}

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

	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $link => $unused ) {
			yield $link;
		}
	}

	protected function isExisting( $linkId ) {
		return \array_key_exists( $linkId, $this->getExistingLinks() );
	}

	protected function isInNewSet( $linkId ) {
		return \array_key_exists( $linkId, $this->newLinks );
	}

	protected function needExistingLinkRefresh() {
		return $this->isCrossNamespaceMove();
	}

	protected function insertLink( $linkId ) {
		$this->insertRow( [
			'il_from_namespace' => $this->getSourcePage()->getNamespace(),
			'il_to' => $linkId
		] );
	}

	protected function deleteLink( $linkId ) {
		$this->deleteRow( [ 'il_to' => $linkId ] );
	}

	protected function makePageReferenceValue( $linkId ): PageReferenceValue {
		return new PageReferenceValue( NS_FILE, $linkId, WikiAwareEntity::LOCAL );
	}

	protected function makeTitle( $linkId ): Title {
		return Title::makeTitle( NS_FILE, $linkId );
	}

	protected function deduplicateLinkIds( $linkIds ) {
		if ( !is_array( $linkIds ) ) {
			$linkIds = iterator_to_array( $linkIds );
		}
		return array_unique( $linkIds );
	}

	protected function finishUpdate() {
		$this->updateChangeTags();
		$this->invalidateImageDescriptions();
	}

	/**
	 * Add the mw-add-media or mw-remove-media change tags to the edit if appropriate
	 */
	private function updateChangeTags() {
		$enabledTags = ChangeTags::getSoftwareTags();
		$mediaChangeTags = [];
		if ( count( $this->insertedLinks ) && in_array( 'mw-add-media', $enabledTags ) ) {
			$mediaChangeTags[] = 'mw-add-media';
		}
		if ( count( $this->deletedLinks ) && in_array( 'mw-remove-media', $enabledTags ) ) {
			$mediaChangeTags[] = 'mw-remove-media';
		}
		$revisionRecord = $this->getRevision();
		if ( $revisionRecord && count( $mediaChangeTags ) ) {
			ChangeTags::addTags( $mediaChangeTags, null, $revisionRecord->getId() );
		}
	}

	/**
	 * Invalidate all image description pages which had links added or removed
	 */
	private function invalidateImageDescriptions() {
		PurgeJobUtils::invalidatePages(
			$this->getDB(), NS_FILE,
			array_merge( $this->insertedLinks, $this->deletedLinks ) );
	}
}
