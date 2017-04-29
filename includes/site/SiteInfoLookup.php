<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME overview -> docs/sites.txt
 * FIXME contexts, aliases and site ids
 *
 * @since 1.32
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
	const SITE_DESCRIPTION = 'description';
	const SITE_LANGUAGE = 'language';
	const SITE_CONTENT_LANGUAGE = 'content-language';
	const SITE_NOMINAL_LANGUAGE = 'nominal-language';
	const SITE_FAMILY = 'family';
	const SITE_DB_NAME = 'db-name';
	const SITE_DB_CLUSTER = 'db-cluster';
	const SITE_IS_FORWARDABLE = 'forwardable';
	const SITE_IS_TRANSCLUDABLE = 'transcludable';
	const SITE_IS_PRIVATE = 'private';
	const SITE_IS_CLOSED = 'closed';
	const SITE_DOMAIN = 'domain';
	const SITE_BASE_URL = 'base-url';
	const SITE_SCRIPT_PATH = 'script-path';
	const SITE_LINK_PATH = 'link-path';
	const SITE_DATA_PATH = 'data-path';

	// FIXME: document all the semantics!
	const GLOBAL_ID = 'global';
	const ALIAS_ID = 'alias';
	const INTERWIKI_ID = 'interwiki-map';
	const INTERLANGUAGE_ID = 'interlanguage-map';
	const DOMAIN_ID = 'domain-map';
	const DATABASE_ID = 'db-name-map';

	/**
	 * Returns the canonical ID of the local site (the "self" ID).
	 * In case multiple SiteInfoLookup instances exist in a single process, this ID
	 * should refer to the site for which the alias mappings provided by the instance apply.
	 *
	 * @return mixed
	 */
	public function getLocalSiteId();

	/**
	 * List all sites with the given value for the given field in the info array.
	 *
	 * @param string $property A field name in the site's info array.
	 * @param mixed $value The expected value in the site's info array. If not given, all
	 * sites that have the riven property set are returned.
	 *
	 * @return string[] The site IDs of the sites with the given property value.
	 */
	public function findSites( $property, $value = null );

	/**
	 * Returns the site ID for the given alias.
	 *
	 * @param string $alias The alias to resolve.
	 * @param string $context The context in which to resolve the ID. See XXX_ID for well known
	 *        values. If not given, all contexts are used, and the ID is returned as-is if it
	 *        is already a site ID.
	 *
	 * @return null|string The site's site ID, or null if no such site is known in the
	 *         given context.
	 */
	public function getSiteId( $alias, $context = null );

	/**
	 * Returns an alias for the given site for use in the given context.
	 * This is the reverse of getSiteId().
	 *
	 * @param string $id ID of the site to get an alias for.
	 * @param string $context The context in which to resolve the ID. See XXX_ID for well known
	 *        values.
	 *
	 * @return null|string An alias to use for the given site in the given context,
	 *         or null if no such alias exists.
	 */
	public function getSiteAlias( $id, $context );

	/**
	 * Returns the site ID for the given alias.
	 *
	 * @param string $id The ID or alias of the site.
	 * @param string $property the name of the property to look up
	 * @param mixed|null $default a value to assume if the property is not set for this site.
	 *
	 * @throws OutOfBoundsException if $siteId is not a known site
	 * @return null|string if no such site is known.
	 */
	public function getSiteProperty( $id, $property, $default = null );

}
