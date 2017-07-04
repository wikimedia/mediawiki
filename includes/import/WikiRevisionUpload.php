<?php

/**
 * @since 1.31
 */
interface WikiRevisionUpload {

	/**
	 * @since 1.31
	 * @return string
	 */
	public function getArchiveName();

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
	 * @return mixed
	 */
	public function getSrc();

	/**
	 * @since 1.31
	 * @return string
	 */
	public function getFileSrc();

	/**
	 * @since 1.31
	 * @return bool
	 */
	public function isTempSrc();

	/**
	 * @since 1.31
	 * @return bool|string
	 */
	public function getSha1();

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
	 * @return string
	 */
	public function getComment();

}
