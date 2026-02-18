<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;

/**
 * Shared code for pagelinks, templatelinks and existencelinks. These tables
 * all link to an arbitrary page identified by namespace and title.
 *
 * Link ID format: string[]:
 *   - 0: namespace ID
 *   - 1: title DB key
 *
 * @since 1.38
 */
abstract class GenericPageLinksTable extends TitleLinksTable {
	/**
	 * A 2d array representing the new links, with the namespace ID in the
	 * first key, the DB key in the second key, and the value arbitrary.
	 *
	 * @var array
	 */
	protected $newLinks = [];

	/**
	 * The existing links in the same format as self::$newLinks, or null if it
	 * has not been loaded yet.
	 *
	 * @var array|null
	 */
	private $existingLinks;

	/**
	 * @return string|null
	 */
	abstract protected function getFromNamespaceField();

	/** @inheritDoc */
	protected function getExistingFields() {
		return [
			'ns' => 'lt_namespace',
			'title' => 'lt_title',
		];
	}

	/**
	 * Get existing links as an associative array
	 *
	 * @return array
	 */
	private function getExistingLinks() {
		if ( $this->existingLinks === null ) {
			$this->existingLinks = [];
			foreach ( $this->fetchExistingRows() as $row ) {
				$this->existingLinks[$row->ns][$row->title] = 1;
			}
		}

		return $this->existingLinks;
	}

	/** @inheritDoc */
	protected function getNewLinkIDs() {
		foreach ( $this->newLinks as $ns => $links ) {
			foreach ( $links as $dbk => $unused ) {
				yield [ $ns, (string)$dbk ];
			}
		}
	}

	/** @inheritDoc */
	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $ns => $links ) {
			foreach ( $links as $dbk => $unused ) {
				yield [ $ns, (string)$dbk ];
			}
		}
	}

	/** @inheritDoc */
	protected function isExisting( $linkId ) {
		[ $ns, $dbk ] = $linkId;
		return isset( $this->getExistingLinks()[$ns][$dbk] );
	}

	/** @inheritDoc */
	protected function isInNewSet( $linkId ) {
		[ $ns, $dbk ] = $linkId;
		return isset( $this->newLinks[$ns][$dbk] );
	}

	/** @inheritDoc */
	protected function insertLink( $linkId ) {
		$row = [];
		$fromNamespaceField = $this->getFromNamespaceField();
		if ( $fromNamespaceField !== null ) {
			$row[$fromNamespaceField] = $this->getSourcePage()->getNamespace();
		}
		$row[$this->getTargetIdField()] = $this->linkTargetLookup->acquireLinkTargetId(
			$this->makeTitle( $linkId ),
			$this->getDB()
		);
		$this->insertRow( $row );
	}

	/** @inheritDoc */
	protected function deleteLink( $linkId ) {
		$this->deleteRow( [
			$this->getTargetIdField() => $this->linkTargetLookup->acquireLinkTargetId(
				$this->makeTitle( $linkId ),
				$this->getDB()
			)
		] );
	}

	/** @inheritDoc */
	protected function needForcedLinkRefresh() {
		return $this->isCrossNamespaceMove();
	}

	/** @inheritDoc */
	protected function makePageReferenceValue( $linkId ): PageReferenceValue {
		return PageReferenceValue::localReference( $linkId[0], $linkId[1] );
	}

	/** @inheritDoc */
	protected function makeTitle( $linkId ): Title {
		return Title::makeTitle( $linkId[0], $linkId[1] );
	}

	/** @inheritDoc */
	protected function deduplicateLinkIds( $linkIds ) {
		$seen = [];
		foreach ( $linkIds as $linkId ) {
			if ( !isset( $seen[$linkId[0]][$linkId[1]] ) ) {
				$seen[$linkId[0]][$linkId[1]] = true;
				yield $linkId;
			}
		}
	}
}
