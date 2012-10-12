<?php

/**
 * Interface for site objects.
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
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface Site {

	const TYPE_UNKNOWN = 'unknown';
	const TYPE_MEDIAWIKI = 'mediawiki';

	const GROUP_NONE = 'none';

	const ID_INTERWIKI = 'interwiki';
	const ID_EQUIVALENT = 'equivalent';

	const SOURCE_LOCAL = 'local';

	/**
	 * Returns the global site identifier (ie enwiktionary).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getGlobalId();

	/**
	 * Sets the global site identifier (ie enwiktionary).
	 *
	 * @since 1.21
	 *
	 * @param string $globalId
	 */
	public function setGlobalId( $globalId );

	/**
	 * Returns the type of the site (ie mediawiki).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Sets the type of the site (ie mediawiki).
	 * TODO: remove, we cannot change this after instantiation
	 *
	 * @since 1.21
	 *
	 * @param string $type
	 */
	public function setType( $type );

	/**
	 * Gets the type of the site (ie wikipedia).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getGroup();

	/**
	 * Sets the type of the site (ie wikipedia).
	 *
	 * @since 1.21
	 *
	 * @param string $group
	 */
	public function setGroup( $group );

	/**
	 * Returns the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getSource();

	/**
	 * Sets the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @since 1.21
	 *
	 * @param string $source
	 */
	public function setSource( $source );

	/**
	 * Returns the protocol of the site, ie 'http://', 'irc://', '//'
	 * Or false if it's not known.
	 *
	 * @since 1.21
	 *
	 * @return string|false
	 */
	public function getProtocol();

	/**
	 * Returns the domain of the site, ie en.wikipedia.org
	 * Or false if it's not known.
	 *
	 * @since 1.21
	 *
	 * @return string|false
	 */
	public function getDomain();

	/**
	 * Returns the full URL for the given page on the site.
	 * Or false if the needed information is not known.
	 *
	 * This generated URL is usually based upon the path returned by getLinkPath(),
	 * but this is not a requirement.
	 *
	 * @since 1.21
	 * @see Site::getLinkPath()
	 *
	 * @param bool|String $page
	 *
	 * @return string|false
	 */
	public function getPageUrl( $page = false );

	/**
	 * Returns language code of the sites primary language.
	 * Or false if it's not known.
	 *
	 * @since 1.21
	 *
	 * @return string|false
	 */
	public function getLanguageCode();

	/**
	 * Sets language code of the sites primary language.
	 *
	 * @since 1.21
	 *
	 * @param string $languageCode
	 */
	public function setLanguageCode( $languageCode );

	/**
	 * Returns the normalized, canonical form of the given page name.
	 * How normalization is performed or what the properties of a normalized name are depends on the site.
	 * The general contract of this method is that the normalized form shall refer to the same content
	 * as the original form, and any other page name referring to the same content will have the same normalized form.
	 *
	 * Note that this method may call out to the target site to perform the normalization, so it may be slow
	 * and fail due to IO errors.
	 *
	 * @since 1.21
	 *
	 * @param string $pageName
	 *
	 * @return string the normalized page name
	 */
	public function normalizePageName( $pageName );

	/**
	 * Returns the interwiki link identifiers that can be used for this site.
	 *
	 * @since 1.21
	 *
	 * @return array of string
	 */
	public function getInterwikiIds();

	/**
	 * Returns the equivalent link identifiers that can be used to make
	 * the site show up in interfaces such as the "language links" section.
	 *
	 * @since 1.21
	 *
	 * @return array of string
	 */
	public function getNavigationIds();

	/**
	 * Adds an local identifier to the site.
	 *
	 * @since 1.21
	 *
	 * @param string $type The type of the identifier, element of the Site::ID_ enum
	 * @param string $identifier
	 */
	public function addLocalId( $type, $identifier );

	/**
	 * Adds an interwiki id to the site.
	 *
	 * @since 1.21
	 *
	 * @param string $identifier
	 */
	public function addInterwikiId( $identifier );

	/**
	 * Adds a navigation id to the site.
	 *
	 * @since 1.21
	 *
	 * @param string $identifier
	 */
	public function addNavigationId( $identifier );

	/**
	 * Saves the site.
	 *
	 * @since 1.21
	 *
	 * @param string|null $functionName
	 */
	public function save( $functionName = null );

	/**
	 * Returns the internal ID of the site.
	 *
	 * @since 1.21
	 *
	 * @return integer
	 */
	public function getInternalId();

	/**
	 * Sets the provided url as path of the specified type.
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 * @param string $fullUrl
	 */
	public function setPath( $pathType, $fullUrl );

	/**
	 * Returns the path of the provided type or false if there is no such path.
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 *
	 * @return string|false
	 */
	public function getPath( $pathType );

	/**
	 * Sets the path used to construct links with.
	 * Shall be equivalent to setPath( getLinkPathType(), $fullUrl ).
	 *
	 * @param string $fullUrl
	 *
	 * @since 1.21
	 */
	public function setLinkPath( $fullUrl );

	/**
	 * Returns the path used to construct links with or false if there is no such path.
	 * Shall be equivalent to getPath( getLinkPathType() ).
	 *
	 * @return string|false
	 */
	public function getLinkPath();

	/**
	 * Returns the path type used to construct links with.
	 *
	 * @return string|false
	 */
	public function getLinkPathType();

	/**
	 * Returns the paths as associative array.
	 * The keys are path types, the values are the path urls.
	 *
	 * @since 1.21
	 *
	 * @return array of string
	 */
	public function getAllPaths();

	/**
	 * Removes the path of the provided type if it's set.
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 */
	public function removePath( $pathType );

}