<?php

namespace MediaWiki\Storage;

use Content;

/**
 * Service interface for retrieving Content objects associated with a revision.
 *
 * Content objects for each revision are organized into "slots". Each revision can have
 * only one Content object per slot.
 *
 * Slots come in several varieties:
 * - Immutable slots for primary data. Immutable slots cannot be updated, only superseded in a
 *   subsequent revision.
 * - Mutable slots for derived data. Mutable slots of an existing revision can be updated.
 * - Virtual slots for content that is generated on demand. Virtual slots cannot read, but not
 *   written.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface RevisionContentLookup {

	/**
	 * Slot name for the main content.
	 */
	const MAIN_CONTENT = 'main';

	/**
	 * @param int $revisionId
	 * @param string $slot
	 *
	 * @throws NotFoundException if the requested revision or slot was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return Content
	 */
	public function getRevisionContent( $revisionId, $slot );

	/**
	 * @param int $revisionId
	 *
	 * @todo: decide if we need a RevisionContentRecord class
	 * @todo: decide if we need a SlotInfo class
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string[] a list of content roles available for the given revision.
	 *         If the revision is not found, an empty list is returned.
	 */
	public function listRevisionSlots( $revisionId );

}
