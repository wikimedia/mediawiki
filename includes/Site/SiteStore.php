<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Site;

/**
 * Interface for storing and retrieving Site objects.
 *
 * Default implementation is DBSiteStore.
 *
 * @since 1.21
 * @ingroup Site
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface SiteStore extends SiteLookup {

	/**
	 * Saves the provided site.
	 *
	 * @since 1.21
	 * @param Site $site
	 * @return bool Success indicator
	 */
	public function saveSite( Site $site );

	/**
	 * Saves the provided sites.
	 *
	 * @since 1.21
	 * @param Site[] $sites
	 * @return bool Success indicator
	 */
	public function saveSites( array $sites );

	/**
	 * Deletes all sites from the database. After calling clear(), getSites() will return an empty
	 * list and getSite() will return null until saveSite() or saveSites() is called.
	 */
	public function clear();
}

/** @deprecated class alias since 1.42 */
class_alias( SiteStore::class, 'SiteStore' );
