<?php

namespace MediaWiki\Deferred\LinksUpdate;

use Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\ExternalLinks\LinkFilter;
use MediaWiki\MainConfigNames;
use ParserOutput;

/**
 * externallinks
 *
 * Link ID format: string URL
 *
 * @since 1.38
 */
class ExternalLinksTable extends LinksTable {
	private const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ExternalLinksSchemaMigrationStage,
	];

	private $newLinks = [];
	private $existingLinks;
	/** @var int */
	private $migrationStage;

	public function __construct( Config $config ) {
		$options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->migrationStage = $options->get( MainConfigNames::ExternalLinksSchemaMigrationStage );
	}

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
			$params = [ 'el_to' => $linkId ];
			if ( $this->migrationStage & SCHEMA_COMPAT_WRITE_OLD ) {
				$params['el_index'] = implode( '', $index );
				$params['el_index_60'] = substr( implode( '', $index ), 0, 60 );
			}
			if ( $this->migrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
				$params['el_to_domain_index'] = substr( $index[0], 0, 255 );
				$params['el_to_path'] = $index[1];
			}
			$this->insertRow( $params );
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
