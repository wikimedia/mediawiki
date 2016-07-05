<?php
namespace MediaWiki\Storage;

use Content;
use MediaWiki\Linker\LinkTarget;


/**
 * A value object representing revision meta-data.
 */
interface RevisionRecord {

	/**
	 * Get revision ID
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return int
	 */
	public function getParentId();

	/**
	 * Returns the length of the text in this revision.
	 *
	 * @return int
	 */
	public function getSize();

	/**
	 * Returns the base36 sha1 of the text in this revision.
	 *
	 * @return string
	 */
	public function getSha1();

	/**
	 * Returns the title of the page associated with this entry.
	 *
	 * @return LinkTarget
	 */
	public function getTitle();

	/**
	 * Get the page ID
	 *
	 * @return int
	 */
	public function getPage();

	/**
	 * Returns revision's user id
	 *
	 * @return int
	 */
	public function getUser();

	/**
	 * Returns revision's username.
	 *
	 * @return string
	 */
	public function getUserText();

	/**
	 * Returns revision's comment.
	 *
	 * @return string
	 */
	public function getComment();

	/**
	 * @return bool
	 */
	public function isMinor();

	/**
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @return bool
	 */
	public function isDeleted( $field );

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * @return int
	 */
	public function getVisibility();

	/**
	 * @return string
	 */
	public function getTimestamp();

	/**
	 * @return bool
	 */
	public function isCurrent();

	/**
	 * @return string[] The names of all primary slots in this revision
	 */
	public function listPrimarySlots();

	/**
	 * @param string $slot the name of the desired slot
	 *
	 * @return ContentBlobInfo
	 * @throws NotFoundException
	 */
	public function getSlotInfo( $slot );

	/**
	 * @param string $slot the name of the desired slot
	 *
	 * @note Implementations must call copy() on any Content objects they return, so modifications
	 * to the Content objects do not affect the result of future calls.
	 *
	 * @return Content
	 * @throws NotFoundException
	 */
	public function getSlotContent( $slot );

}