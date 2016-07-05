<?php

namespace MediaWiki\Storage;

use Content;

/**
 * Service interface for retrieving Content objects associated with a revision.
 *
 * Content objects for each revision are organized into "slots". Each revision can have
 * only one Content object per slot, which is considered immutable user supplied data
 * ("primary content").
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface RevisionContentLookup extends RevisionInfoLookup {

	/**
	 * Slot name for the main content.
	 */
	const MAIN_CONTENT = 'main';

	/**
	 * @param int $revisionId
	 * @param string $slot
	 *
	 * @todo We probably need to enforce access restrictrions here,
	 * like Revision::getContent does! Can we do that in a decorator?
	 *
	 * @throws NotFoundException if the requested revision or slot was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return Content
	 */
	public function getRevisionContent( $revisionId, $slot );

	/**
	 * Returns information associated with the
	 *
	 * @param int $revisionId
	 * @param string $name
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return mixed
	 */
	public function getRevisionInfo( $revisionId, $name );

	/**
	 * @param int $revisionId
	 *
	 * @todo: primary or all? do we need $flags?
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return ContentBlobInfo[] a list of content roles available for the given revision.
	 *         If the revision is not found, an empty list is returned.
	 */
	public function getRevisionSlots( $revisionId );

}
