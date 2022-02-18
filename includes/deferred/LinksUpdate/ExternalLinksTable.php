<?php

namespace MediaWiki\Deferred\LinksUpdate;

use LinkFilter;
use ParserOutput;

/**
 * externallinks
 *
 * Link ID format: string URL
 *
 * @since 1.38
 */
class ExternalLinksTable extends LinksTable {
	private $newLinks = [];
	private $existingLinks;

	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->newLinks = $parserOutput->getExternalLinks();
	}

	protected function getTableName() {
		return 'externallinks';
	}

	protected function getFromField() {
		return 'el_from';
	}

	protected function getExistingFields() {
		return [ 'el_to' ];
	}

	/**
	 * Get the existing links as an array, where the key is the URL and the
	 * value is unused.
	 *
	 * @return array
	 */
	private function getExistingLinks() {
		if ( $this->existingLinks === null ) {
			$this->existingLinks = [];
			foreach ( $this->fetchExistingRows() as $row ) {
				$this->existingLinks[$row->el_to] = true;
			}
		}
		return $this->existingLinks;
	}

	protected function getNewLinkIDs() {
		foreach ( $this->newLinks as $link => $unused ) {
			yield (string)$link;
		}
	}

	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $link => $unused ) {
			yield (string)$link;
		}
	}

	protected function isExisting( $linkId ) {
		return \array_key_exists( $linkId, $this->getExistingLinks() );
	}

	protected function isInNewSet( $linkId ) {
		return \array_key_exists( $linkId, $this->newLinks );
	}

	protected function insertLink( $linkId ) {
		foreach ( LinkFilter::makeIndexes( $linkId ) as $index ) {
			$this->insertRow( [
				'el_to' => $linkId,
				'el_index' => $index,
				'el_index_60' => substr( $index, 0, 60 ),
			] );
		}
	}

	protected function deleteLink( $linkId ) {
		$this->deleteRow( [ 'el_to' => $linkId ] );
	}

	/**
	 * Get an array of URLs of the given type
	 *
	 * @param int $setType One of the link set constants as in LinksTable::getLinkIDs()
	 * @return string[]
	 */
	public function getStringArray( $setType ) {
		$ids = $this->getLinkIDs( $setType );
		if ( is_array( $ids ) ) {
			return $ids;
		} else {
			return iterator_to_array( $ids );
		}
	}
}
