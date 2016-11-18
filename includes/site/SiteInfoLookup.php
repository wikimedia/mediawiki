<?php

/**
 * Interface for service objects providing a lookup for site information.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.29
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
interface SiteInfoLookup {

	const FAMILY_GROUP = 'family';
	const LANGUAGE_GROUP = 'language';
	const DATABASE_GROUP = 'database';

	const GLOBAL_ID = 'global';
	const INTERWIKI_ID = 'interwiki';
	const INTERLANGUAGE_ID = 'interlanguage';

	const SITE_TYPE_PROP = 'site-type';
	const CONTENT_LANG_PROP = 'content-lang';
	const PAGE_URL_PROP = 'page-url';
	const RESOURCE_URL_PROP = 'resource-url';
	const CONCEPT_URI_PROP = 'concept-uri';
	const DATABASE_NAME_PROP = 'content-lang';

	const ALLOW_FORWARD_PROP = 'allow-forward';
	const ALLOW_TRANSCLUDE_PROP = 'allow-transclude';

	const GLOBAL_IDS_PROP = 'global-ids';
	const INTERWIKI_IDS_PROP = 'interwiki-ids';
	const INTERLANGUAGE_IDS_PROP = 'interlanguage-ids';

	const FAMILY_GROUP_PROP = 'family-group';
	const LANGUAGE_GROUP_PROP = 'language-group';
	const DATABASE_GROUP_PROP = 'database-group';

	const SITE_TYPE_UNKNOWN = 'unknown';
	const SITE_TYPE_MEDIAWIKI = 'mediawiki';

	/**
	 * @param string $scope
	 * @param string $group
	 *
	 * @return string[] a list of site ids
	 */
	public function getGroupMembers( $scope, $group );

	/**
	 * @param string $scope
	 * @param string $siteId global siteId
	 *
	 * @return string the group the site is a member of in the given scope, or null if none
	 */
	public function getSiteMembership( $scope, $siteId );

	/**
	 * @param string $scope
	 * @param string $id
	 *
	 * @return string the global site ID associated with $id in $scope, or null if none.
	 */
	public function resolveSiteId( $scope, $id );

	/**
	 * @param string $scope
	 * @param string $siteId global siteId
	 *
	 * @return string[] IDs of the given site in the given scope.
	 */
	public function getSiteIds( $scope, $siteId );

	/**
	 * @param string $siteId A canonical global site ID
	 * @param string $name The name of the desired property
	 * @param mixed $default
	 *
	 * @return mixed the property value, or $default if the property is undefined.
	 */
	public function getSiteProperty( $siteId, $name, $default = null );

}
