<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 29.08.17
 * Time: 19:44
 */
namespace MediaWiki\Storage;

use MediaWiki\Linker\LinkTarget;
use Title;
use Wikimedia\Rdbms\IDatabase;

/**
 * Service for looking up page revisions.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
interface RevisionLookup {

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaces Revision::newFromId
	 *
	 * $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master
	 *      RevisionRecord::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $id
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionById( $id, $flags = 0 );

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * MCR migration note: this replaces Revision::newFromTitle
	 *
	 * $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master
	 *      RevisionRecord::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param LinkTarget $linkTarget
	 * @param int $id (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTitle( LinkTarget $linkTarget, $id = 0, $flags = 0 );

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaces Revision::newFromPageId
	 *
	 * $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master (since 1.20)
	 *      RevisionRecord::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionByPageId( $pageId, $revId = 0, $flags = 0 );

	/**
	 * Get previous revision for this title
	 *
	 * MCR migration note: this replaces Revision::getPrevious
	 *
	 * @param Title $title
	 * @param $revId
	 *
	 * @return RevisionRecord|null
	 */
	public function getPreviousRevision( Title $title, $revId );

	/**
	 * Get next revision for this title
	 *
	 * MCR migration note: this replaces Revision::getNext
	 *
	 * @return RevisionRecord|null
	 */
	public function getNextRevision( Title $title, $revId );

	/**
	 * Load a revision based on a known page ID and current revision ID from the DB
	 *
	 * This method allows for the use of caching, though accessing anything that normally
	 * requires permission checks (aside from the text) will trigger a small DB lookup.
	 * The title will also be lazy loaded, though setTitle() can be used to preload it.
	 *
	 * MCR migration note: this replaces Revision::newKnownCurrent
	 *
	 * @param IDatabase $db
	 * @param int $pageId Page ID
	 * @param int $revId 1 current revision of this page
	 * @return RevisionRecord|bool Returns false if missing
	 * @since 1.31
	 */
	public function getKnownCurrentRevision( IDatabase $db, $pageId, $revId );
}