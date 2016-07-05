<?php

namespace MediaWiki\Storage;

use Content;
use Message;
use Revision;
use User;

/**
 * Controller for updating a wiki page. This is a high level controller,
 * which performs permission checks and secondary data updates.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface PageUpdateController {

	/**
	 * @param string $slotName
	 * @param Content $content
	 */
	public function setSlotContent( $slotName, Content $content );

	/**
	 * @param string $slotName
	 */
	public function removeSlot( $slotName );

	/**
	 * Saves a new revision of the page.
	 *
	 * @param string $summary
	 * @param User $user
	 * @param int $flags
	 * @param array $tags
	 *
	 * @throws StorageException
	 * @return Revision
	 */
	public function save( $summary, User $user, $flags = 0, $tags = [] );

	/**
	 * Aborts the page update.
	 */
	public function abort();

	/**
	 * Aborts the page update if save() was not yet called.
	 *
	 * Implementations should call this from the destructor.
	 */
	public function cleanup();

	/**
	 * Whether this page update is a page creation.
	 *
	 * When called before save(), this indicates whether calling save()
	 * will attempt to create the page. When called after save(), this
	 * indicates whether save actually created a new page.
	 *
	 * @return bool
	 */
	public function isNew();

	/**
	 * Whether this page update actually changes the page.
	 *
	 * This indicates whether any of the Content objects set via setSlotContent() are different
	 * from the previous Content of the respective slots. This does not reliably indicate whether
	 * a new entry in the revision table was (or will be) created. Use isEdit() for that.
	 *
	 * @see isEdit
	 *
	 * @return bool
	 */
	public function isContentChange();

	/**
	 * Whether this page update creates a new revision.
	 *
	 * When called before save(), this is the same as isContentChange(). When called after save(),
	 * this indicates whether a new entry in the revision table was created. Depending on the
	 * $flags parameter passed to save(), a new revision entry may be created even if the content
	 * was not changed (i.e. a "null revision" was created, as opposed to performing a "null edit",
	 * which updates secondary information, but does not create a revision.
	 *
	 * @return bool
	 */
	public function isEdit();

}
