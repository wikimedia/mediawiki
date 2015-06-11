<?php

namespace MediaWiki\Storage;

use Content;
use TitleValue;

/**
 * Service interface for storing Content objects associated with a revision.
 *
 * Content objects for each revision are organized into "slots". Each revision can have
 * only one Content object per slot.
 *
 * Slots come in several varieties:
 * - Immutable slots for primary data. Immutable slots cannot be updated, only superseded in a
 *   subsequent revision. Attempting to modify an immutable slot will cause a NotMutableException
 *   to be thrown.
 * - Mutable slots for derived data. Mutable slots of an existing revision can be updated.
 *   Some mutable slots may be configured to be volatile, meaning that the data stored there
 *   may become unavailable at some point.
 * - Virtual slots for content that is generated on demand. Virtual slots cannot read, but not
 *   written.  Attempting to modify a virtual slot will cause a NotMutableException
 *   to be thrown.
 *
 * @todo: decide whether we should expose blob addresses here. This would allow explicit linking
 *        of virtual content, and allow extensions to access blobs directly.
 *
 * @todo: explore a stateful builder/interactor variant of this interface, for modelign a
 *        transactional context. It would have startCreate/startUpdate for starting, and save/abort for
 *        ending the transaction, in addition to methods for adding Content, etc.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface RevisionContentStore {

	/**
	 * Returns the names of all known slots.
	 *
	 * @return string[]
	 */
	public function getContentSlots();

	/**
	 * Returns the names of slots defined to hold primary, user created data.
	 * Content from such slots would be shown in diffs, and cannot be added or
	 * updated after a revision has been created.
	 *
	 * @return string[]
	 */
	public function getPrimaryContentSlots();

	/**
	 * Returns the names of all updatable slots for derived data. Derived data can
	 * be added or updated after a revision was created.
	 *
	 * @return string[]
	 */
	public function getDerivedContentSlots();

	/**
	 * Initialize content slots for the given ID. All primary content that shall be associated
	 * with a revision has to be provided when calling initRevisionContent() on the revision.
	 *
	 * @todo: consider whether this should also create the primary revision entry
	 *        instead of getting a revision id from the caller.
	 *
	 * @param TitleValue $title the title of the page the revision belongs to
	 * @param int $revisionId the revision to associate the content with
	 * @param Content[] $slots an array of Content objects, with slot names as keys.
	 * @param string $timestamp touch date in TS_MW format
	 * @param int $parentRevision Id of the parent revision (or 0 if there is none).
	 */
	public function initRevisionContent( TitleValue $title, $revisionId, $slots, $timestamp, $parentRevision );

	/**
	 * Update content slots of a given revision. Only mutable (derived) slots can be updated.
	 * Attempting to udpated or add a primary or virtual slot will cause a NotMutableException.
	 *
	 * @param TitleValue $title the title of the page the revision belongs to
	 * @param int $revisionId the revision to associate the content with
	 * @param Content[] $slots an array of Content objects, with slot names as keys.
	 * @param string $timestamp touch date in TS_MW format
	 *
	 * @throws NotMutableException If one of the given slots is not mutable.
	 * @throws NotFoundException If the given revision does not exist.
	 */
	public function updateRevisionContent( TitleValue $title, $revisionId, array $slots, $timestamp );

}
