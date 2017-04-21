<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME overview -> docs/sites.txt
 * FIXME contexts, local and global ids
 * FIXME groups
 * FIXME aliases
 *
 * @since 1.32
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
interface SiteInfoMaintenanceLookup extends SiteInfoLookup {

	/**
	 * Returns the info array for the given site.
	 *
	 * @param string $siteId The ID of alias of the desired site.
	 *
	 * @param array $defaults Defaults to apply to the result.
	 *
	 * @throws OutOfBoundsException if $siteId is not a known site
	 *
	 * @return array An associative array representing properties of the given site.
	 *         See SITE_XXX for well known keys.
	 *
	 */
	public function getSiteInfo( $siteId, array $defaults = [] );

	/**
	 * List all known sites.
	 *
	 * @return string[] The site IDs of all known sites.
	 */
	public function listSites();

	/**
	 * Returns the aliases that refer to a site in different contexts.
	 *
	 * @deprecated This method is born deprecated, it only exists for backwards compatibility
	 *             reasons. There should be no need to list the aliases of a site.
	 *             Use getLocalId() instead.
	 *
	 * @param string $siteId The global ID of the desired site. Aliasing applies.
	 *
	 * @throws OutOfBoundsException if $siteId is not a known site
	 *
	 * @return array Associative array giving all identifiers of the given site.
	 *         Keys represent the context in which an id is valid, see the XXX_ID constants
	 *         for well known keys. Values are lists of identifiers, the first entry being the
	 *         preferred id in a given context.
	 */
	public function getAliasesFor( $siteId );

	/**
	 * Returns the mapping of all aliases defined in the given context.
	 *
	 * @param string $context The context in which to resolve the ID. See XXX_ID for well known
	 *        values. See class level documentation for more information.
	 *
	 * @return array[] An associative array giving the ID resolution for the given context.
	 *         Keys are local identifiers valid in the context, values are global site IDs.
	 */
	public function getAliasMap( $context );

}
