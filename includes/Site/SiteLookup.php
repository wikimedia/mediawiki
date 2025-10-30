<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Site;

/**
 * Interface to retrieve Site objects, for implementation by service classes.
 *
 * Default implementation is DBSiteStore.
 *
 * @since 1.25
 * @ingroup Site
 */
interface SiteLookup {

	/**
	 * Return the site with provided global ID, or null if there is no such site.
	 *
	 * @since 1.25
	 * @param string $globalId
	 * @return Site|null
	 */
	public function getSite( $globalId );

	/**
	 * Return a list of all sites.
	 *
	 * @since 1.25
	 * @return SiteList
	 */
	public function getSites();

}

/** @deprecated class alias since 1.42 */
class_alias( SiteLookup::class, 'SiteLookup' );
