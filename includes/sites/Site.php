<?php

namespace MW;

/**
 * Interface for site objects.
 *
 * @since 1.20
 *
 * @file
 * @ingroup Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface Site extends \IORMRow {

	/**
	 *
	 *
	 * @since 1.20
	 *
	 * @return SiteConfig
	 */
	public function getConfig();

	/**
	 * Returns the global site identifier (ie enwiktionary).
	 *
	 * @since 1.20
	 *
	 * @return integer
	 */
	public function getGlobalId();

	/**
	 * Returns the type of the site (ie SITE_TYPE_MEDIAWIKI).
	 *
	 * @since 1.20
	 *
	 * @return integer
	 */
	public function getType();

	/**
	 * Returns the type of the site (ie SITE_GROUP_WIKIPEDIA).
	 *
	 * @since 1.20
	 *
	 * @return integer
	 */
	public function getGroup();

	/**
	 * Returns the base URL of the site, ie http://en.wikipedia.org
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Returns language code of the sites primary language.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getLanguage();

	/**
	 * Returns the full page path (ie site url + relative page path).
	 * The page title should go at the $1 marker. If the $pageName
	 * argument is provided, the marker will be replaced by it's value.
	 *
	 * @since 1.20
	 *
	 * @param string|false $pageName
	 *
	 * @return string
	 */
	public function getPagePath( $pageName = false );

	/**
	 * Returns the full file path (ie site url + relative file path).
	 * The path should go at the $1 marker. If the $path
	 * argument is provided, the marker will be replaced by it's value.
	 *
	 * @since 1.20
	 *
	 * @param string|false $path
	 *
	 * @return string
	 */
	public function getFilePath( $path = false );

	/**
	 * Returns the relative page path.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getRelativePagePath();

	/**
	 * Returns the relative file path.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getRelativeFilePath();

	/**
	 * Returns an array with additional data part of the
	 * site definition. This is meant for usage by fields
	 * we never need to search against and for those that
	 * are site type specific, ie "allows file uploads"
	 * for MediaWiki sites.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getExtraData();

}