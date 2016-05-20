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
 * @since 1.28
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
class MutableSite implements Site {

	/**
	 * A version ID that identifies the serialization structure used by getSerializationData()
	 * and unserialize(). This is useful for constructing cache keys in cases where the cache relies
	 * on serialization for storing the SiteList.
	 *
	 * @var string A string uniquely identifying the version of the serialization structure.
	 */
	const SERIAL_VERSION_ID = '2016-05-20';

	/**
	 * @var string
	 */
	protected $type = self::TYPE_UNKNOWN;

	/**
	 * @var string[][] Lists of group names by scope
	 */
	protected $groups = [];

	/**
	 * @var string[][] Lists of IDs by scope
	 */
	protected $ids = [];

	/**
	 * @var string[] Lists of paths (typically, URL patterns)
	 */
	protected $paths = [];

	/**
	 * @var mixed[] A map of properties
	 */
	protected $properties = [];

	/**
	 * @var string
	 */
	protected $source = self::SOURCE_LOCAL;

	/**
	 * Constructor.
	 *
	 * @param $globalId
	 * @param string $type see the TYPE_XXX constants
	 * @param string $source see the SOURCE_XXX constants
	 */
	public function __construct( $globalId, $type = self::TYPE_UNKNOWN, $source = self::SOURCE_LOCAL ) {
		$this->type = $type;
		$this->ids[ self::ID_GLOBAL ][] = $globalId;
		$this->source = $source;
	}

	/**
	 * Returns the global site identifier (ie enwiktionary).
	 *
	 * @return string|null
	 */
	public function getGlobalId() {
		$ids = $this->getIds( self::ID_GLOBAL );
		return reset( $ids );
	}

	/**
	 * Returns the type of the site (ie mediawiki).
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Gets the group of the site (ie wikipedia).
	 *
	 * @return string
	 */
	public function getGroup() {
		$ids = $this->getIds( self::GROUP_FAMILY );
		return reset( $ids );
	}

	/**
	 * Sets the group of the site (ie wikipedia).
	 *
	 * @param string $group
	 *
	 * @throws MWException
	 */
	public function setGroups( $group ) {
		if ( !is_string( $group ) ) {
			throw new MWException( '$group needs to be a string' );
		}

		$this->group = $group;
	}

	/**
	 * Returns the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * Sets the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @param string $source
	 *
	 * @throws MWException
	 */
	public function setSource( $source ) {
		if ( !is_string( $source ) ) {
			throw new MWException( '$source needs to be a string' );
		}

		$this->source = $source;
	}

	/**
	 * Gets if site.tld/path/key:pageTitle should forward users to  the page on
	 * the actual site, where "key" is the local identifier.
	 *
	 * @return bool
	 */
	public function shouldForward() {
		return $this->getProperty( 'forward', false );
	}

	/**
	 * Returns the domain of the site, ie en.wikipedia.org
	 * Or false if it's not known.
	 *
	 * This returns $this->getProperty( 'domain' ) if set,
	 * and a value derived from getLinkPath() otherwise.
	 *
	 * @return string|null
	 */
	public function getDomain() {
		$domain = $this->getProperty( 'domain' );

		if ( $domain !== null ) {
			return strval( $domain );
		}

		$path = $this->getLinkPath();

		if ( $path !== null ) {
			return parse_url( $path, PHP_URL_HOST );
		}

		return null;
	}

	/**
	 * Returns the protocol of the site.
	 * This returns $this->getProperty( 'protocol' ) if set,
	 * and a value derived from getLinkPath() otherwise.
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getProtocol() {
		$protocol = $this->getProperty( 'protocol' );

		if ( $protocol !== null ) {
			return strval( $protocol );
		}

		$path = $this->getLinkPath();

		if ( $path === null ) {
			return '';
		}

		$protocol = parse_url( $path, PHP_URL_SCHEME );

		// No schema
		if ( $protocol === null || $protocol === false ) {
			// Used for protocol relative URLs
			$protocol = '';
		}

		return $protocol;
	}

	/**
	 * Returns the path used to construct links with or false if there is no such path.
	 *
	 * @return string|null
	 */
	public function getLinkPath() {
		return $this->getPath( self::PATH_LINK );
	}

	/**
	 * Returns the main path type, that is the type of the path that should
	 * generally be used to construct links to the target site.
	 *
	 * This default implementation returns Site::PATH_LINK as the default path
	 * type. Subclasses can override this to define a different default path
	 * type, or return false to disable site links.
	 *
	 * @return string|null
	 */
	public function getLinkPathType() {
		return self::PATH_LINK;
	}

	/**
	 * Returns the full URL for the given page on the site.
	 * Or false if the needed information is not known.
	 *
	 * This generated URL is usually based upon the path returned by getLinkPath(),
	 * but this is not a requirement.
	 *
	 * This implementation returns a URL constructed using the path returned by getLinkPath().
	 *
	 * @param bool|string $pageName
	 *
	 * @return string|bool
	 */
	public function getPageUrl( $pageName = false ) {
		$url = $this->getLinkPath();

		if ( $url === false ) {
			return false;
		}

		if ( $pageName !== false ) {
			$url = str_replace( '$1', rawurlencode( $pageName ), $url );
		}

		return $url;
	}

	/**
	 * Returns $pageName without changes.
	 * Subclasses may override this to apply some kind of normalization.
	 *
	 * @see Site::normalizePageName
	 *
	 * @param string $pageName
	 *
	 * @return string
	 */
	public function normalizePageName( $pageName ) {
		return $pageName;
	}

	/**
	 * Returns language code of the sites primary language.
	 * Or null if it's not known.
	 *
	 * @return string|null
	 */
	public function getLanguageCode() {
		return $this->getProperty( 'language' );
	}

	/**
	 * Sets language code of the sites primary language.
	 *
	 * @param string $languageCode
	 */
	public function setLanguageCode( $languageCode ) {
		return $this->setProperty( 'language', $languageCode );
	}

	/**
	 * Adds an identifier.
	 *
	 * @param string $scope
	 * @param string $identifier
	 */
	public function addLocalId( $scope, $identifier ) {
		$this->ids[$scope][] = $identifier;
	}

	/**
	 * Adds an interwiki id to the site.
	 *
	 * @param string $identifier
	 */
	public function addInterwikiId( $identifier ) {
		$this->addLocalId( self::ID_INTERWIKI, $identifier );
	}

	/**
	 * Adds a navigation id to the site.
	 *
	 * @param string $identifier
	 */
	public function addNavigationId( $identifier ) {
		$this->addLocalId( self::ID_EQUIVALENT, $identifier );
	}

	/**
	 * Returns the interwiki link identifiers that can be used for this site.
	 *
	 * @return string[]
	 */
	public function getInterwikiIds() {
		return $this->getIds( self::ID_INTERWIKI );
	}

	/**
	 * Returns the equivalent link identifiers that can be used to make
	 * the site show up in interfaces such as the "language links" section.
	 *
	 * @return string[]
	 */
	public function getNavigationIds() {
		return $this->getIds( self::ID_EQUIVALENT );
	}

	/**
	 * Returns the path of the provided type or false if there is no such path.
	 *
	 * @param string $pathType
	 *
	 * @return string|null
	 */
	public function getPath( $pathType ) {
		return array_key_exists( $pathType, $this->paths ) ? $this->paths[$pathType] : null;
	}

	/**
	 * Returns the paths as associative array.
	 * The keys are path types, the values are the path urls.
	 *
	 * @return string[]
	 */
	public function getAllPaths() {
		return $this->paths;
	}

	/**
	 * @param string $siteType
	 *
	 * @return Site
	 */
	public static function newForType( $siteType ) {
		global $wgSiteTypes;

		if ( array_key_exists( $siteType, $wgSiteTypes ) ) {
			return new $wgSiteTypes[$siteType]();
		}

		return new MutableSite();
	}

	/**
	 * @param string $scope The groups name's scope.
	 *        See the Site::GROUP_XXX constants for possible values.
	 *
	 * @return string[]
	 */
	public function getGroups( $scope ) {
		return isset( $this->groups[$scope] ) ? $this->groups[$scope] : [];
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed The value associated with $name, or $default if no such value is known.
	 */
	public function getProperty( $name, $default = null ) {
		return isset( $this->properties[$name] ) ? $this->properties[$name] : $default;
	}

	/**
	 * @param string $scope The ID's scope. See the Site::ID_XXX constants for possible values.
	 *
	 * @return string[]
	 */
	public function getIds( $scope ) {
		return isset( $this->ids[$scope] ) ? $this->ids[$scope] : [];
	}
}
