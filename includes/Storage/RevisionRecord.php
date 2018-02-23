<?php

namespace MediaWiki\Storage;

use Content;
use Title;
use User;

interface RevisionRecord {

	/**
	 * @since 1.31
	 * @return User
	 */
	public function getUser();

	/**
	 * @since 1.31
	 * @return Title
	 */
	public function getPageAsLinkTarget();

	/**
	 * @since 1.31
	 * @return string
	 */
	public function getTimestamp();

	/**
	 * @since 1.31
	 * @return string
	 */
	public function getComment();

	/**
	 * @since 1.31
	 * @return Content
	 */
	public function getContent();

	/**
	 * @since 1.31
	 * @return bool
	 */
	public function isMinor();

	/**
	 * @since 1.31
	 * @return bool|string
	 */
	public function getSha1();

}
