<?php

namespace MediaWiki\Storage;

use Content;

/**
 * Service interface for retrieving Content objects associated with a revision.
 * Content objects for each revision are organized into "slots". Each revision can have
 * only one Content object per slot.
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
	public function getContent( $revisionId, $slot );

	/**
	 * @param int $revisionId
	 * @param string $slot
	 *
	 * @todo: decide if we need a RevisionContentRecord class
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string[] a list of content roles available for the given revision.
	 *         If the revision is not found, an empty list is returned.
	 */
	public function listContent( $revisionId );

}
