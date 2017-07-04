<?php

/**
 * @since 1.30
 */
interface WikiRevisionUpload {

	/**
	 * @since 1.30
	 * @return string
	 */
	public function getArchiveName();

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
	public function getFileSrc();

	/**
	 * @since 1.30
	 * @return bool
	 */
	public function isTempSrc();

	/**
	 * @since 1.30
	 * @return bool|string
	 */
	public function downloadSource();

	/**
	 * @since 1.30
	 * @return bool|string
	 */
	public function getSha1();

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
	 * @return string
	 */
	public function getComment();

}
