<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\Utils\PurgeJobUtils;
use MediaWiki\MainConfigNames;
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
	public const VIRTUAL_DOMAIN = 'virtual-imagelinks';

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ImageLinksSchemaMigrationStage
	];

	private int $migrationStage;

	/**
	 * @var array New links with the name in the key, value arbitrary
	 */
	private $newLinks;

	/**
	 * @var array Existing links with the name in the key, value arbitrary
	 */
	private $existingLinks;

	public function __construct( ServiceOptions $options ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->migrationStage = $options->get( MainConfigNames::ImageLinksSchemaMigrationStage );
	}

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
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			return [ 'il_to' ];
		} else {
			return [ 'lt_title' ];
		}
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
				if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
					$this->existingLinks[$row->il_to] = true;
				} else {
					$this->existingLinks[$row->lt_title] = true;
				}
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
		$insertedLink = [
			'il_from_namespace' => $this->getSourcePage()->getNamespace(),
		];
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			$insertedLink['il_to'] = $linkId;
		}
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_NEW ) {
			$insertedLink['il_target_id'] = $this->linkTargetLookup->acquireLinkTargetId(
				$this->makeTitle( $linkId ),
				$this->getDB()
			);
		}
		$this->insertRow( $insertedLink );
	}

	/** @inheritDoc */
	protected function deleteLink( $linkId ) {
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			$this->deleteRow( [ 'il_to' => $linkId ] );
		} else {
			$this->deleteRow( [
				'il_target_id' => $this->linkTargetLookup->acquireLinkTargetId(
					$this->makeTitle( $linkId ),
					$this->getDB()
				)
			] );
		}
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

	protected function linksTargetNormalizationStage(): int {
		return $this->migrationStage;
	}

	/** @inheritDoc */
	protected function virtualDomain() {
		return self::VIRTUAL_DOMAIN;
	}
}
