<?php

/**
 * Represents a single site.
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
 * @since 1.21
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
interface Site {

	const TYPE_UNKNOWN = 'unknown';
	const TYPE_MEDIAWIKI = 'mediawiki';

	const GROUP_FAMILY = 'family';
	const GROUP_LANGUAGE = 'language';
	const GROUP_NONE = 'none'; // TODO: get rid of this...

	const ID_GLOBAL = 'global';
	const ID_INTERWIKI = 'interwiki';
	const ID_EQUIVALENT = 'equivalent'; // FIXME: phase this out, it's confusing??

	const SOURCE_LOCAL = 'local';

	const PATH_LINK = 'link';
	const PATH_FILE = 'file_path';
	const PATH_PAGE = 'page_path'; // FIXME: alias / fallback to 'link'!!

	/**
	 * Returns the global site identifier (e.g. enwiktionary).
	 *
	 * @note Must be implemented to be equivalent to getIds( 'global' )[0];
	 *
	 * @since 1.21
	 *
	 * @return string|null
	 */
	public function getGlobalId();

	/**
	 * Returns the type of the site (e.g. mediawiki).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getType();


	/**
	 * @param string $scope The groups name's scope. See the Site::GROUP_XXX constants for possible values.
	 *
	 * @return string[]
	 */
	public function getGroups( $scope );

	/**
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed The value associated with $name, or $default if no such value is known.
	 */
	public function getProperty( $name, $default = null );

	/**
	 * Gets the first group in the family scope (e.g. wikipedia).
	 *
	 * @note Must be implemented to be equivalent to getGroups( 'family' )[0];
	 *
	 * @since 1.21
	 * @deprecated since 1.28, use getGroups( 'family' )[0] instead.
	 *
	 * @return string
	 */
	public function getGroup();

	/**
	 * @param string $scope The ID's scope. See the Site::ID_XXX constants for possible values.
	 *
	 * @return string[]
	 */
	public function getIds( $scope );

	/**
	 * Gets if site.tld/path/key:pageTitle should forward users to  the page on
	 * the actual site, where "key" is the local identifier.
	 *
	 * @note Must be implemented to be equivalent to getProperty( 'forward', false );
	 *
	 * @since 1.21
	 *
	 * @return bool
	 */
	public function shouldForward();

	/**
	 * Returns the domain of the site, ie en.wikipedia.org
	 * Or false if it's not known.
	 *
	 * Implementations may derive this value from the string returned by getLinkPath().
	 *
	 * @since 1.21
	 *
	 * @return string|null
	 */
	public function getDomain();

	/**
	 * Returns the protocol of the site.
	 *
	 * Implementations may derive this value from the string returned by getLinkPath().
	 *
	 * @since 1.21
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getProtocol();

	/**
	 * Returns the path used to construct links with or null if there is no such path.
	 *
	 * The returned path shall contain placeholders like $1, see getPageUrl() and expandPath().
	 *
	 * Shall be equivalent to getPath( PATH_LINK ).
	 *
	 * @return string|null
	 */
	public function getLinkPath();

	/**
	 * Shall be implemented to be equivalen to expandPath( PATH_LINK, $pageName ).
	 *
	 * @deprecated use expandPath() instead.
	 *
	 * @param bool $pageName
	 *
	 * @return string|null
	 */
	public function getPageUrl( $pageName = false );

	/**
	 * Returns a path for the given $pathType, like getPath(), but with any placeholders
	 * ($1, $2, etc) expanded to any additional parameters provided to this function.
	 *
	 * @param string $pathType
	 * @param string ... parameters to substitute in. URL encoding is applied before subsitution.
	 *
	 * @return string|null
	 */
	public function expandPath( $pathType );

	/**
	 * Returns language code of the sites primary language.
	 * Or null if it's not known.
	 *
	 * Shall be equivalent to getProperty( 'language' );
	 *
	 * @since 1.21
	 *
	 * @return string|null
	 */
	public function getLanguageCode();

	/**
	 * Returns the interwiki link identifiers that can be used for this site.
	 *
	 * Shall be equivalent to getIds( ID_INTERWIKI )
	 *
	 * @since 1.21
	 *
	 * @return string[]
	 */
	public function getInterwikiIds();

	/**
	 * Returns the equivalent link identifiers that can be used to make
	 * the site show up in interfaces such as the "language links" section.
	 *
	 * Shall be implemented to be the same as getIds( ID_EQUIVALENT ), with
	 * an optional fallback to getInterwikiIds().
	 *
	 * @since 1.21
	 *
	 * @return string[]
	 */
	public function getNavigationIds();

	/**
	 * Returns the path of the provided type or false if there is no such path.
	 *
	 * The returned path may contain placeholders like $1, see expandPath().
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 *
	 * @return string|null
	 */
	public function getPath( $pathType );

	/**
	 * Returns the paths as associative array.
	 * The keys are path types, the values are the path urls.
	 *
	 * @since 1.21
	 *
	 * @return string[]
	 */
	public function getAllPaths();

}
