<?php

namespace MediaWiki\Storage;

use Content;
use ContentHandler;
use InvalidArgumentException;
use MediaWiki\Storage\Sql\RevisionSlotTable;
use TitleValue;
use Wikimedia\Assert\Assert;

/**
 *
 * A RevisionContentStore implementation based on mapping slot names to BlobStores.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class SlotMappingRevisionContentStore implements RevisionContentStore, RevisionContentLookup {

	/**
	 * @var string[] mapping of slot names to blob store names for use with $this->blobStoreRegistry
	 */
	private $slotStores = array();

	/**
	 * @var string[] mapping of slot names to slot types
	 */
	private $slotTypes = array();

	/**
	 * @var BlobStoreRegistry
	 */
	private $blobStoreRegistry;

	/**
	 * @var RevisionSlotTable
	 */
	private $slotTable;

	/**
	 * @var BlobLookup
	 */
	private $blobLookup;

	/**
	 * @param array[] $slotDefinitions Slot definitions, mapping slot names to associative arrays
	 *        that contain the fields "type" => slot type, and "store" => store name.
	 *
	 * @param BlobStoreRegistry $blobStoreFactory
	 * @param RevisionSlotTable $slotTable
	 */
	public function __construct( array $slotDefinitions, BlobStoreRegistry $blobStoreFactory, BlobLookup $blobLookup, RevisionSlotTable $slotTable ) {
		foreach ( $slotDefinitions as $slot => $def ) {
			//TODO: check fields, check type, check value
			$this->slotTypes[$slot] = $def['type'];
			$this->slotStores[$slot] = $def['store'];
		}

		$this->blobStoreRegistry = $blobStoreFactory;
		$this->slotTable = $slotTable;
		$this->blobLookup = $blobLookup;
	}

	/**
	 * @param string $slot
	 *
	 * @return BlobStore
	 */
	private function getSlotStore( $slot ) {
		if ( !isset( $this->slotStores[$slot] ) ) {
			throw new NotFoundException( 'No such slot: ' . $slot );
		}

		$store = $this->storeFactory->getStore( $this->slotTypes[$slot] );
		return $store;
	}

	/**
	 * @param string $slot
	 *
	 * @return string slot type ("primary", "derived", or "virtual")
	 */
	private function getSlotsWithType( $type ) {
		$types = $this->slotTypes;

		return array_filter( array_keys( $this->slotTypes ),
			function ( $slot ) use ( $types, $type ) {
				return $types[$slot] === $type;
			}
		);
	}

	/**
	 * @see RevisionContentStore::initRevisionContent()
	 *
	 * @param TitleValue $title the title of the page the revision belongs to
	 * @param int $revisionId the revision to associate the content with
	 * @param Content[] $slots an array of Content objects, with slot names as keys.
	 * @param string $timestamp touch date in TS_MW format
	 * @param int $parentRevision Id of the parent revision (or 0 if there is none).
	 */
	public function initRevisionContent( TitleValue $title, $revisionId, $slots, $timestamp, $parentRevision ) {
		$primarySlots = array_flip( $this->getPrimaryContentSlots() );
		$hasPrimary = false;
		foreach ( $slots as $slot => $content ) {
			if ( array_key_exists( $slot, $primarySlots ) ) {
				$hasPrimary = true;
				break;
			}
		}

		if ( !$hasPrimary ) {
			throw new InvalidArgumentException( '$slots must contain at least one primary content slot' );
		}

		$existingAddresses = $this->slotTable->getContentAddresses( $revisionId );

		if ( !empty( $existingAddresses ) ) {
			throw new StorageException( "Revision $revisionId already has content" );
		}

		$this->upsertRevisionContent( $title, $revisionId, $slots, $existingAddresses, $timestamp );

		if ( $parentRevision > 0 ) {
			$this->slotTable->copyPrimaryContentAssociations( $fromRevision, $toRevision, $this->getPrimarySlots() );
		}
	}

	/**
	 * @see RevisionContentStore::updateRevisionContent()
	 *
	 * @param TitleValue $title the title of the page the revision belongs to
	 * @param int $revisionId the revision to associate the content with
	 * @param Content[] $slots an array of Content objects, with slot names as keys.
	 * @param string $timestamp touch date in TS_MW format
	 */
	public function updateRevisionContent( TitleValue $title, $revisionId, array $slots, $timestamp ) {
		// Check updatability for all before storing anything
		$derivedSlots = array_flip( $this->getDerivedContentSlots() );
		foreach ( $slots as $slot => $content ) {
			if ( !array_key_exists( $slot, $derivedSlots ) ) {
				throw new NotMutableException( "Cannot update immutable slot $slot of revision $revisionId." );
			}
		}

		$existingAddresses = $this->slotTable->getContentAddresses( $revisionId );

		if ( empty( $existingAddresses ) ) {
			throw new StorageException( 'replaceRevisionContent() can not be used on blank revisions.' );
		}

		$this->upsertRevisionContent( $title, $revisionId, $slots, $existingAddresses, $timestamp );
	}

	/**
	 * @param TitleValue $title the title of the page the revision belongs to
	 * @param int $revisionId the revision to associate the content with
	 * @param Content[] $slots an array of Content objects, with slot names as keys.
	 * @param string[] $existingAddresses
	 * @param string $timestamp touch date in TS_MW format
	 */
	private function upsertRevisionContent( TitleValue $title, $revisionId, array $slots, array $existingAddresses, $timestamp ) {
		Assert::parameterElementType( 'Content', $slots, '$slots' );
		Assert::parameterType( 'string', $revisionId, '$revisionId' );
		Assert::parameterType( 'string', $timestamp, '$timestamp' );

		$newAddresses = array();
		foreach ( $slots as $slot => $content ) {
			$oldAddress = isset( $existingAddresses[$slot] ) ? $existingAddresses[$slot] : null;
			$newAddresses[$slot] = $this->storeSlotContent( $title, $revisionId, $slot, $content, $timestamp, $oldAddress );
		}

		$this->slotTable->putContentAddresses( $revisionId, $newAddresses, $timestamp );
	}

	/**
	 * @see RevisionContentStore::getRevisionContent()
	 *
	 * @param int $revisionId
	 * @param string $slot
	 *
	 * @throws NotFoundException if the requested revision or slot was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return Content
	 */
	public function getRevisionContent( $revisionId, $slot ) {
		$record = $this->slotTable->getContentRecord( $revisionId, $slot );

		$blob = $this->blobLookup->loadData( $record['blob_address'] );
		$model = $this->blobLookup->loadData( $record['content_model'] );
		$format = $this->blobLookup->loadData( $record['content_format'] );

		$handler = ContentHandler::getForModelID( $model ); //TODO: create an injectable ContentHandlerRegistry
		$content = $handler->unserializeContent( $blob, $format );

		return $content;
	}

	/**
	 * @see RevisionContentStore::listRevisionSlots()
	 *
	 * @param int $revisionId
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string[] a list slots available for the given revision.
	 *         If the revision is not found, an empty list is returned.
	 */
	public function listRevisionSlots( $revisionId ) {
		$addresses = $this->slotTable->getContentAddresses( $revisionId );
		return array_keys( $addresses );
	}

	/**
	 * Returns the names of all known slots.
	 *
	 * @return string[]
	 */
	public function getContentSlots() {
		return array_keys( $this->slotTypes );
	}

	/**
	 * @see RevisionContentStore::getPrimaryContentSlots()
	 *
	 * @return string[]
	 */
	public function getPrimaryContentSlots() {
		return $this->getSlotsWithType( 'primary' );
	}

	/**
	 * @see RevisionContentStore::getPrimaryContentSlots()
	 *
	 * @return string[]
	 */
	public function getDerivedContentSlots() {
		return $this->getSlotsWithType( 'derived' );
	}

}
