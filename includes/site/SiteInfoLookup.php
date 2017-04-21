<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME overview
 * FIXME scopes, local and global ids
 * FIXME groups
 * FIXME aliases
 *
 * @since 1.29
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
interface SiteInfoLookup {

	// FIXME: document all the semantics!
	const TYPE_UNKNOWN = 'unknown';
	const TYPE_MEDIAWIKI = 'mediawiki';

	// FIXME: document all the semantics!
	const SITE_TYPE = 'type';
	const SITE_NAME = 'name';
	const SITE_LANGUAGE = 'language';
	const SITE_FAMILY = 'family';
	const SITE_DATABASE = 'database';
	const SITE_IS_FORWARDABLE = 'forwardable';
	const SITE_IS_TRANSCLUDABLE = 'transcludable';
	const SITE_BASE_URL = 'base-url';
	const SITE_SCRIPT_PATH = 'script-path';
	const SITE_ARTICLE_PATH = 'article-path';

	// FIXME: document all the semantics!
	const ALIAS_ID = 'alias';
	const INTERWIKI_ID = 'interwiki';
	const NAVIGATION_ID = 'navigation';
	const DATABASE_ID = 'database';
	const DOMAIN_ID = 'domain';

	/**
	 * Returns the info array for the given site.
	 *
	 * @param string $siteId The global ID of the desired site. Aliasing applies.
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
	 * List all sites with the given value for the given field in the info array.
	 *
	 * @param string $field A field name in the site's info array.
	 * @param mixed $value The expected value in the site's info array.
	 *
	 * @return string[] The site IDs of the sites with the given property value.
	 */
	public function listGroupMembers( $field, $value );

	/**
	 * Returns the local IDs that refer to a site in different scopes.
	 *
	 * @deprecated This method is born deprecated, it only exists for backwards compatibility
	 *             reasons. There should be no need to list the local IDs of a site.
	 *             Use getLocalId() instead.
	 *
	 * @param string $siteId The global ID of the desired site. Aliasing applies.
	 *
	 * @throws OutOfBoundsException if $siteId is not a known site
	 *
	 * @return array Associative array giving all identifiers of the given site.
	 *         Keys represent the scope in which an id is valid, see the XXX_ID constants
	 *         for well known keys. Values are lists of identifiers, the first entry being the
	 *         preferred id in a given scope.
	 */
	public function getAssociatedLocalIds( $siteId );

	/**
	 * Returns the local ID that refer to a site in the given scope.
	 *
	 * @param string $siteId The global ID of the desired site. Aliasing applies.
	 * @param string $scope The scope in which to resolve the ID. See XXX_ID for well known
	 *        values. See class level documentation for more information.
	 *
	 * @throws OutOfBoundsException if $siteId is not a known site
	 *
	 * @return string|null The site's preferred local ID in the given scope, or null if
	 *         no local id is defined for $siteId in $scope.
	 */
	public function getLocalId( $siteId, $scope );

	/**
	 * Returns the global site ID of the site identified by the given local ID in the given scope.
	 *
	 * @param string $scope The scope in which to resolve the ID. See XXX_ID for well known
	 *        values. See class level documentation for more information.
	 * @param string $localId The scope-relative ID to resolve.
	 *
	 * @return string|null The site's global site ID, or null if no such site is known.
	 */
	public function resolveLocalId( $scope, $localId );

	/**
	 * Returns the mapping of all local IDs defined in the given scope.
	 *
	 * @param string $scope The scope in which to resolve the ID. See XXX_ID for well known
	 *        values. See class level documentation for more information.
	 *
	 * @return array[] An associative array giving the ID resolution for the given scope.
	 *         Keys are local identifiers valid in the scope, values are global site IDs.
	 */
	public function getIdMap( $scope );

}
