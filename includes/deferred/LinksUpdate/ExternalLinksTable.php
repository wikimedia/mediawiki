<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\ExternalLinks\LinkFilter;
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
		$links = LinkFilter::getIndexedUrlsNonReversed( array_keys( $parserOutput->getExternalLinks() ) );
		foreach ( $links as $link ) {
			$this->newLinks[$link] = true;
		}
	}

	protected function getTableName() {
		return 'externallinks';
	}

	protected function getFromField() {
		return 'el_from';
	}

	protected function getExistingFields() {
		return [ 'el_to_domain_index', 'el_to_path' ];
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
				$link = LinkFilter::reverseIndexes( $row->el_to_domain_index ) . $row->el_to_path;
				$this->existingLinks[$link] = true;
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
			$params = [
				'el_to_domain_index' => substr( $index[0], 0, 255 ),
				'el_to_path' => $index[1],
			];
			$this->insertRow( $params );
		}
	}

	protected function deleteLink( $linkId ) {
		foreach ( LinkFilter::makeIndexes( $linkId ) as $index ) {
			$this->deleteRow( [
				'el_to_domain_index' => substr( $index[0], 0, 255 ),
				'el_to_path' => $index[1]
			] );
		}
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
