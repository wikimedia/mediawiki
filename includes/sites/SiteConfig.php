<?php

namespace MW;

/**
 * Interface for site configuration objects.
 *
 * @since 1.20
 *
 * @file
 * @ingroup Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface SiteConfig {

	/**
	 * Returns the local identifier (ie "en") of the site.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getLocalId();

	/**
	 * Returns if inline links to this site should be allowed.
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function getLinkInline();

	/**
	 * returns if the sit should show up in intersite navigation interfaces.
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function getLinkNavigation();

	/**
	 * Returns if site.tld/path/key:pageTitle should forward users to  the page on
	 * the actual site, where "key" os either the local or global identifier.
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function getForward();

	/**
	 * Returns an array with additional info part of the
	 * site configuration. This is meant for usage by fields
	 * we never need to search against and for those that
	 * are site type specific, ie "allow template transclusion"
	 * for MediaWiki sites.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getExtraInfo();

}