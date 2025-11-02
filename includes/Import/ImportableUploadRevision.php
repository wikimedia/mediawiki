<?php

use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @since 1.31
 */
interface ImportableUploadRevision {

	/**
	 * @since 1.31
	 * @return string Archive name of a revision if archived.
	 */
	public function getArchiveName();

	/**
	 * @since 1.31
	 * @return Title
	 */
	public function getTitle();

	/**
	 * @since 1.31
	 * @return string TS_MW timestamp, a string with 14 digits
	 */
	public function getTimestamp();

	/**
	 * @since 1.31
	 * @return string|null HTTP source of revision to be used for downloading.
	 */
	public function getSrc();

	/**
	 * @since 1.31
	 * @return string Local file source of the revision.
	 */
	public function getFileSrc();

	/**
	 * @since 1.31
	 * @return bool Is the return of getFileSrc only temporary?
	 */
	public function isTempSrc();

	/**
	 * @since 1.31
	 * @return string|bool sha1 of the revision, false if not set or errors occur.
	 */
	public function getSha1();

	/**
	 * @deprecated since 1.39, use {@see getUser} instead; this is almost always null anyway
	 * @since 1.31
	 * @return User|null Typically null, use {@see getUser} instead
	 */
	public function getUserObj();

	/**
	 * @since 1.31
	 * @return string The username of the user that created this revision
	 */
	public function getUser();

	/**
	 * @since 1.31
	 * @return string
	 */
	public function getComment();

}
