<?php

namespace MediaWiki\Storage;

use Content;
use Title;
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
 * @todo: If we want to support updatable derived slots, we will need an
 *        updateRevisionContent() method here.
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
	public function listContentSlots();

	/**
	 * Initialize content slots for the given revision. All primary content that shall be associated
	 * with a revision has to be provided when calling initRevisionContent() on the revision. All
	 * provided slots are considered primary.
	 *
	 * @todo Use PageRecord instead of Title
	 *
	 * @param Title $title the title of the page the revision belongs to
	 * @param int $revisionId the revision to associate the content with
	 * @param Content[] $slots an array of Content objects, with slot names as keys.
	 * @param string $timestamp touch date in TS_MW format
	 * @param int $parentRevision Id of the parent revision (or 0 if there is none).
	 */
	public function storeRevisionContent( Title $title, $revisionId, $slots, $timestamp, $parentRevision );

	/**
	 * Associates auxilliary data with the given revision. No primary slots can be stored this way.
	 * Existing data in any of the slots will be replaced.
	 *
	 * @todo Use PageRecord instead of Title
	 *
	 * @param Title $title the title of the page the revision belongs to
	 * @param int $revisionId the revision to associate the content with
	 * @param Content[] $slots an array of Content objects, with slot names as keys.
	 * @param string $timestamp touch date in TS_MW format
	 */
	public function storeDerivedData( Title $title, $revisionId, $slots, $timestamp );

}
