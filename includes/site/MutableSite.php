<?php
namespace MediaWiki\Site;

use Site;
use Wikimedia\Assert\Assert;

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
	 * @param string $globalId
	 * @param string $type see the TYPE_XXX constants
	 */
	public function __construct( $globalId, $type = self::TYPE_UNKNOWN ) {
		$this->type = $type;
		$this->ids[ self::ID_GLOBAL ][] = $globalId;
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
	 */
	public function setSource( $source ) {
		Assert::parameterType( 'string', $source, '$source' );

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
	 * Sets the global site identifier (ie enwiktionary).
	 *
	 * @note: Equivalent to $this->setIds( self::ID_GLOBAL, [ $globalId ] );
	 * @deprecated: use setIds() instead.
	 *
	 * @param string $globalId
	 */
	public function setGlobalId( $globalId ) {
		$this->setIds( self::ID_GLOBAL, [ $globalId ] );
	}

	/**
	 * Gets the group of the site (ie wikipedia).
	 *
	 * @return string|null
	 */
	public function getGroup() {
		$groups = $this->getGroups( self::GROUP_FAMILY );
		return empty( $groups ) ? null : reset( $groups );
	}

	/**
	 * Sets the group of the site (ie wikipedia).
	 *
	 * @note: Equivalent to $this->setGroups( self::GROUP_FAMILY, [ $group ] );
	 * @deprecated: use setGroups() instead.
	 *
	 * @param string $group
	 */
	public function setGroup( $group ) {
		$this->setGroups( self::GROUP_FAMILY, [ $group ] );
	}

	/**
	 * Sets this site's IDs in the given $scope.
	 *
	 * @param string $scope The scope of the names in $groups, see the GROUP_XXX constants.
	 * @param string[] $ids
	 */
	public function setIds( $scope, array $ids ) {
		Assert::parameterElementType( 'string', $ids, '$ids' );
		$this->ids[$scope] = $ids;
	}

	/**
	 * Sets the group membership for groups opf the given $scope.
	 *
	 * @param string $scope The scope of the names in $groups, see the GROUP_XXX constants.
	 * @param string[] $groups
	 */
	public function setGroups( $scope, array $groups ) {
		Assert::parameterElementType( 'string', $groups, '$groups' );
		$this->groups[$scope] = $groups;
	}

	/**
	 * Sets all paths associated with this site.
	 *
	 * @param string[] $paths A map of path type to path pattern
	 */
	public function setAllPaths( array $paths ) {
		Assert::parameterElementType( 'string', $paths, '$paths' );
		$this->paths = $paths;
	}

	/**
	 * @param string $pathType
	 * @param string $path
	 */
	public function setPath( $pathType, $path ) {
		Assert::parameterType( 'string', $path, '$path' );
		$this->paths[$pathType] = $path;
	}

	/**
	 * @param string $path
	 */
	public function setLinkPath( $path ) {
		$this->setPath( self::PATH_LINK, $path );
	}

	/**
	 * Sets all paths associated with this site.
	 *
	 * @param mixed[] $properties An associative array of properties associated with the site.
	 */
	public function setAllProperties( array $properties ) {
		Assert::parameterElementType( 'string|integer|double|boolean|null', $properties, '$properties' );
		$this->properties = $properties;
	}

	/**
	 * @param string $name
	 * @param string|integer|double|boolean|null $value
	 */
	public function setProperty( $name, $value ) {
		Assert::parameterType( 'string|integer|double|boolean|null', $value, '$value' );
		$this->properties[$name] = $value;
	}

	/**
	 * Gets if site.tld/path/key:pageTitle should forward users to  the page on
	 * the actual site, where "key" is the local identifier.
	 *
	 * @return bool
	 */
	public function shouldForward() {
		return (bool)$this->getProperty( 'forward', false );
	}

	/**
	 * Sets if site.tld/path/key:pageTitle should forward users to  the page on
	 * the actual site, where "key" is the local identifier.
	 *
	 * @param bool $shouldForward
	 */
	public function setForward( $shouldForward ) {
		$this->setProperty( 'forward', $shouldForward );
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
	 * Returns a path for the given $pathType, like getPath(), but with any placeholders
	 * ($1, $2, etc) expanded to any additional parameters provided to this function.
	 *
	 * @param string $pathType
	 * @param string ... parameters to substitute in. URL encoding is applied before subsitution.
	 *
	 * @return string|null
	 */
	public function expandPath( $pathType ) {
		$url = $this->getPath( $pathType );

		if ( $url === null ) {
			return null;
		}

		$params = func_get_args();
		array_shift( $params );

		$n = 0;
		foreach ( $params as $p ) {
			$n++;
			$url = str_replace( '$' . $n, rawurlencode( $p ), $url );
		}

		return $url;
	}

	/**
	 * Returns getLinkPath() if $pagename is false, and expandPath( self::PATH_LINK, $pageName )
	 * otherwise.
	 *
	 * @param bool|string $pageName
	 *
	 * @return null|string
	 */
	public function getPageUrl( $pageName = false ) {
		if ( $pageName === false ) {
			return $this->getLinkPath();
		} else {
			return $this->expandPath( self::PATH_LINK, $pageName );
		}
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
		$this->setProperty( 'language', $languageCode );
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
	 * @param string $scope The groups name's scope.
	 *        See the Site::GROUP_XXX constants for possible values.
	 *
	 * @return string[]
	 */
	public function getGroups( $scope ) {
		return isset( $this->groups[$scope] ) ? $this->groups[$scope] : [];
	}

	/**
	 * Returns all group name scopes fro which IDs are known.
	 *
	 * @return string[]
	 */
	public function getGroupScopes() {
		return array_keys( $this->groups );
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
	 * Returns the properties as associative array.
	 *
	 * @return mixed[]
	 */
	public function getAllProperties() {
		return $this->properties;
	}

	/**
	 * @param string $scope The ID's scope. See the Site::ID_XXX constants for possible values.
	 *
	 * @return string[]
	 */
	public function getIds( $scope ) {
		return isset( $this->ids[$scope] ) ? $this->ids[$scope] : [];
	}

	/**
	 * Returns all ID scopes fro which IDs are known.
	 *
	 * @return string[]
	 */
	public function getIdScopes() {
		return array_keys( $this->ids );
	}

}
