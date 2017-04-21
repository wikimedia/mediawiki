<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME
 *
 * @since 1.29
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
interface SiteInfoLookup {

	const SITE_TYPE = 'type';
	const SITE_BASE_URL = 'script-path';
	const SITE_SCRIPT_PATH = 'script-path';
	const SITE_ARTICLE_PATH = 'article-path';
	const SITE_IS_FORWARDABLE = 'forwardable';
	const SITE_IS_TRANSCLUDABLE = 'transcludable';

	const FAMILY_GROUP = 'language';
	const LANGUAGE_GROUP = 'language';
	const DATABASE_GROUP = 'database';

	const INTERWIKI_ID = 'interwiki';
	const NAVIGATION_ID = 'navigation';
	const DATABASE_ID = 'database';
	const DOMAIN_ID = 'domain';

	/**
	 * Returns the info array for the given site.
	 *
	 * @param string $globalId
	 *
	 * @param string[] $keys The info keys the caller is interested in.
	 *        If this parameter is not null, implementations may omit any key not given in $keys
	 *        from the result, but must guarantee that all these keys are set.
	 *        If for any of these keys no value is known, that key must be mapped to null
	 *        in the array returned.
	 *
	 * @throws OutOfBoundsException if $globalId is not a known site
	 *
	 * @return array An associative array representing properties of the given site.
	 *         See SITE_XXX for well known keys.
	 *
	 */
	public function getSiteInfo( $globalId, array $keys = null );

	/**
	 * Returns an info array for each site.
	 *
	 * @param string[] $keys The info keys the caller is interested in.
	 *        If this parameter is not null, implementations may omit any key not given in $keys
	 *        from the result, but must guarantee that all these keys are set.
	 *        If for any of these keys no value is known, that key must be mapped to null
	 *        in the array returned.
	 *
	 * @return array[] An associative array mapping global site IDs to associative arrays,
	 *         similar to the ones returned by getSiteInfo().
	 */
	public function listSiteInfo( array $keys = null );

	/**
	 * List all known sites.
	 *
	 * @return string[] The global site IDs of all known sites.
	 */
	public function listSites();

	/**
	 * Returns the groups the site belongs to.
	 *
	 * @param string $globalId
	 *
	 * @throws OutOfBoundsException if $globalId is not a known site
	 *
	 * @return array Associative array giving the group memberships of the given site.
	 *         Keys represent the grouping criterion. XXX_GROUP constants for well known keys.
	 */
	public function getSiteGroups( $globalId );

	/**
	 * Returns the IDs that refer to a site in a given context.
	 *
	 * @param string $globalId
	 *
	 * @throws OutOfBoundsException if $globalId is not a known site
	 *
	 * @return array Associative array giving all identifiers of the given site.
	 *         Keys represent the context in which an id is valid, see the XXX_ID constants
	 *         for well known keys. Values are lists of identifiers, the first entry being the
	 *         preferred id in a given context.
	 */
	public function getSiteIds( $globalId );

	/**
	 * Returns the global site ID of the site identified by the given ID in the given context.
	 *
	 * @param string $context The context in which to resolve the ID. See XXX_ID for well known
	 *        values.
	 * @param string $id The ID to resolve.
	 *
	 * @return string|null The site's global ID, or null if no such site is known.
	 */
	public function getGlobalId( $context, $id );

	/**
	 * Returns the global IDs of the members of the given group.
	 *
	 * @param string $criterion The grouping criterion. See XXX_GROUP for well known values.
	 * @param string $group The name of the group to list.
	 *
	 * @return string[] The global site IDs of all members of the given group. If the group is
	 *         not known, an empty list will be returned.
	 */
	public function listGroupMembers( $criterion, $group );

	/**
	 * Returns the mapping of all IDs defined in the given context.
	 *
	 * @param string $context The context in which to resolve the ID. See XXX_ID for well known
	 *        values.
	 *
	 * @return array[] An associative array giving the ID resolution for the given context.
	 *         Keys are identifiers valid in the context, values are global site IDs.
	 */
	public function listIds( $context );

}
