<?php

interface SiteStore {

	/**
	 * Saves the provided site.
	 *
	 * @since 1.21
	 *
	 * @param Site $site
	 *
	 * @return boolean Success indicator
	 */
	public function saveSite( Site $site );

	/**
	 * Saves the provided sites.
	 *
	 * @since 1.21
	 *
	 * @param Site[] $sites
	 *
	 * @return boolean Success indicator
	 */
	public function saveSites( array $sites );

	/**
	 * Returns the site with provided global id, or null if there is no such site.
	 *
	 * @since 1.21
	 *
	 * @param string $globalId
	 * @param string $source either 'cache' or 'recache'.
	 * If 'cache', the values are allowed (but not obliged) to come from a cache.
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId, $source = 'cache' );

	/**
	 * Returns a list of all sites. By default this site is
	 * fetched from the cache, which can be changed to loading
	 * the list from the database using the $useCache parameter.
	 *
	 * @since 1.21
	 *
	 * @param string $source either 'cache' or 'recache'.
	 * If 'cache', the values are allowed (but not obliged) to come from a cache.
	 *
	 * @return SiteList
	 */
	public function getSites( $source = 'cache' );

}