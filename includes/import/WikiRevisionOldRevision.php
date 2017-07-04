<?php

/**
 * @since 1.30
 */
interface WikiRevisionOldRevision {

	/**
	 * @since 1.30
	 * @return User
	 */
	public function getUserObj();

	/**
	 * @since 1.30
	 * @return string
	 */
	public function getUser();

	/**
	 * @since 1.30
	 * @return Title
	 */
	public function getTitle();

	/**
	 * @since 1.30
	 * @return string
	 */
	public function getTimestamp();

	/**
	 * @since 1.30
	 * @return string
	 */
	public function getComment();

	/**
	 * @since 1.30
	 * @return string
	 */
	public function getModel();

	/**
	 * @since 1.30
	 * @return string
	 */
	public function getFormat();

	/**
	 * @since 1.30
	 * @return Content
	 */
	public function getContent();

	/**
	 * @since 1.30
	 * @return bool
	 */
	public function getMinor();

}
