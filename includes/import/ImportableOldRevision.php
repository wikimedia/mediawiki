<?php

/**
 * @since 1.31
 */
interface ImportableOldRevision {

	/**
	 * @since 1.31
	 * @return User
	 */
	public function getUserObj();

	/**
	 * @since 1.31
	 * @return string
	 */
	public function getUser();

	/**
	 * @since 1.31
	 * @return Title
	 */
	public function getTitle();

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
	 * @return string
	 */
	public function getModel();

	/**
	 * @since 1.31
	 * @return string
	 */
	public function getFormat();

	/**
	 * @since 1.31
	 * @return Content
	 */
	public function getContent();

	/**
	 * @since 1.31
	 * @return bool
	 */
	public function getMinor();

	/**
	 * @since 1.31
	 * @return bool|string
	 */
	public function getSha1Base36();

}
