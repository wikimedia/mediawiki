<?php

namespace MediaWiki\Storage;

use Content;

/**
 * Service interface for storing Content objects associated with a revision.
 *
 * Content objects for each revision are organized into "slots". Each revision can have
 * only one Content object per slot.
 *
 * Slots containing derived data are considered mutable: for a given revision, the Content of a
 * mutable slot may be replaced with new content. Slots with primary data are considered
 * immutable.
 *
 * @todo: decide whether we should expose blob addresses here
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface RevisionContentStore {

	/**
	 * Returns true if the given slot is mutable. Slots that are intended for derived content
	 * are considered mutable.
	 *
	 * @param string $slot
	 *
	 * @return mixed
	 */
	public function isMutableSlot( $slot );

	/**
	 * @param int $revisionId the revision to associate the content with
	 * @param string $slot the slot for the given content
	 * @param Content $content the content to associate with the revision
	 * @param string $timestamp touch date in TS_MW format
	 *
	 * @throws NotFoundException if the given slot is not known
	 * @throws NotMutableException if the given slot is not mutable, and already has content.
	 * @throws StorageException if a storage level error occurred
	 */
	public function putContent( $revisionId, $slot, Content $content, $flags, $timestamp );

	/**
	 * Copy the association of content objects from one revision to another. Only content
	 * that is not marked as derived is copied. Slots which already have an association for
	 * the target revision are skipped.
	 *
	 * This is useful when creating new revisions which change only one of several content slots.
	 * The content of the other (primary, non-derived) slots should be the same as in the previous
	 * revision.
	 *
	 * @param $fromRevision
	 * @param $toRevision
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string[] the names of the slots that were copied.
	 */
	public function copyPrimaryContentAssociations( $fromRevision, $toRevision );

}
