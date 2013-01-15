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
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Site {

	const TYPE_UNKNOWN = 'unknown';
	const TYPE_MEDIAWIKI = 'mediawiki';

	const GROUP_NONE = 'none';

	const ID_INTERWIKI = 'interwiki';
	const ID_EQUIVALENT = 'equivalent';

	const SOURCE_LOCAL = 'local';

	const PATH_LINK = 'link';

	/**
	 * Holds the local ids for this site.
	 * You can obtain them via @see getLocalIds
	 *
	 * @since 1.21
	 *
	 * @var array|boolean false
	 */
	protected $localIds = false;

	/**
	 * Returns the global site identifier (ie enwiktionary).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getGlobalId() {
		return $this->getField( 'global_key' );
	}

	/**
	 * Sets the global site identifier (ie enwiktionary).
	 *
	 * @since 1.21
	 *
	 * @param string $globalId
	 */
	public function setGlobalId( $globalId ) {
		$this->setField( 'global_key', $globalId );
	}

	/**
	 * Returns the type of the site (ie mediawiki).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getType() {
		return $this->getField( 'type' );
	}

	/**
	 * Gets the type of the site (ie wikipedia).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getGroup() {
		return $this->getField( 'group' );
	}

	/**
	 * Sets the type of the site (ie wikipedia).
	 *
	 * @since 1.21
	 *
	 * @param string $group
	 */
	public function setGroup( $group ) {
		$this->setField( 'group', $group );
	}

	/**
	 * Returns the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getSource() {
		return $this->getField( 'source' );
	}

	/**
	 * Sets the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @since 1.21
	 *
	 * @param string $source
	 */
	public function setSource( $source ) {
		$this->setField( 'source', $source );
	}

	/**
	 * Returns the domain of the site, ie en.wikipedia.org
	 * Or false if it's not known.
	 *
	 * @since 1.21
	 *
	 * @return string|false
	 */
	public function getDomain() {
		$path = $this->getLinkPath();

		if ( $path === false ) {
			return false;
		}

		return parse_url( $path, PHP_URL_HOST );
	}

	/**
	 * Returns the protocol of the site, ie 'http://', 'irc://', '//'
	 * Or false if it's not known.
	 *
	 * @since 1.21
	 *
	 * @throws MWException
	 * @return string|false
	 */
	public function getProtocol() {
		$path = $this->getLinkPath();

		if ( $path === false ) {
			return '';
		}

		$protocol = parse_url( $path, PHP_URL_SCHEME );

		// Malformed URL
		if ( $protocol === false ) {
			throw new MWException( "failed to parse URL $path" );
		}

		// No schema
		if ( $protocol === null ) {
			// Used for protocol relative URLs
			$protocol = '';
		}

		return $protocol;
	}

	/**
	 * Sets the path used to construct links with.
	 * Shall be equivalent to setPath( getLinkPathType(), $fullUrl ).
	 *
	 * @param string $fullUrl
	 *
	 * @since 1.21
	 *
	 * @throws MWException
	 */
	public function setLinkPath( $fullUrl ) {
		$type = $this->getLinkPathType();

		if ( $type === false ) {
			throw new MWException( "This SiteObject does not support link paths." );
		}

		$this->setPath( $type, $fullUrl );
	}

	/**
	 * Returns the path used to construct links with or false if there is no such path.
	 *
	 * Shall be equivalent to getPath( getLinkPathType() ).
	 *
	 * @return string|boolean false
	 */
	public function getLinkPath() {
		$type = $this->getLinkPathType();
		return $type === false ? false : $this->getPath( $type );
	}

	/**
	 * Returns the main path type, that is the type of the path that should generally be used to construct links
	 * to the target site.
	 *
	 * This default implementation returns SiteObject::PATH_LINK as the default path type. Subclasses can override this
	 * to define a different default path type, or return false to disable site links.
	 *
	 * @since 1.21
	 *
	 * @return string|boolean false
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
	 * @since 1.21
	 *
	 * @param bool|String $pageName
	 *
	 * @return string|boolean false
	 */
	public function getPageUrl( $pageName = false ) {
		$url = $this->getLinkPath();

		if ( $url === false ) {
			return false;
		}

		if ( $pageName !== false ) {
			$url = str_replace( '$1', rawurlencode( $pageName ), $url ) ;
		}

		return $url;
	}

	/**
	 * Returns $pageName without changes.
	 * Subclasses may override this to apply some kind of normalization.
	 *
	 * @see Site::normalizePageName
	 *
	 * @since 1.21
	 *
	 * @param string $pageName
	 *
	 * @return string
	 */
	public function normalizePageName( $pageName ) {
		return $pageName;
	}

	/**
	 * Returns the value of a type specific field, or the value
	 * of the $default parameter in case it's not set.
	 *
	 * @since 1.21
	 *
	 * @param string $fieldName
	 * @param mixed $default
	 *
	 * @return array
	 */
	protected function getExtraData( $fieldName, $default = null ) {
		$data = $this->getField( 'data', array() );
		return array_key_exists( $fieldName,$data ) ? $data[$fieldName] : $default;
	}

	/**
	 * Sets the value of a type specific field.
	 * @since 1.21
	 *
	 * @param string $fieldName
	 * @param mixed $value
	 */
	protected function setExtraData( $fieldName, $value = null ) {
		$data = $this->getField( 'data', array() );
		$data[$fieldName] = $value;
		$this->setField( 'data', $data );
	}

	/**
	 * Returns language code of the sites primary language.
	 * Or false if it's not known.
	 *
	 * @since 1.21
	 *
	 * @return string|false
	 */
	public function getLanguageCode() {
		return $this->getField( 'language', false );
	}

	/**
	 * Sets language code of the sites primary language.
	 *
	 * @since 1.21
	 *
	 * @param string $languageCode
	 */
	public function setLanguageCode( $languageCode ) {
		$this->setField( 'language', $languageCode );
	}

	/**
	 * Returns the local identifiers of this site.
	 *
	 * @since 1.21
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	protected function getLocalIds( $type ) {
		if ( $this->localIds === false ) {
			$this->loadLocalIds();
		}

		return array_key_exists( $type, $this->localIds ) ? $this->localIds[$type] : array();
	}

	/**
	 * Loads the local ids for the site.
	 *
	 * @since 1.21
	 */
	protected function loadLocalIds() {
		$dbr = wfGetDB( $this->getTable()->getReadDb() );

		$ids = $dbr->select(
			'site_identifiers',
			array(
				'si_type',
				'si_key',
			),
			array(
				'si_site' => $this->getId(),
			),
			__METHOD__
		);

		$this->localIds = array();

		foreach ( $ids as $id ) {
			$this->addLocalId( $id->si_type, $id->si_key );
		}
	}

	/**
	 * Adds a local identifier.
	 *
	 * @since 1.21
	 *
	 * @param string $type
	 * @param string $identifier
	 */
	public function addLocalId( $type, $identifier ) {
		if ( $this->localIds === false ) {
			$this->localIds = array();
		}

		if ( !array_key_exists( $type, $this->localIds ) ) {
			$this->localIds[$type] = array();
		}

		if ( !in_array( $identifier, $this->localIds[$type] ) ) {
			$this->localIds[$type][] = $identifier;
		}
	}

	/**
	 * Adds an interwiki id to the site.
	 *
	 * @since 1.21
	 *
	 * @param string $identifier
	 */
	public function addInterwikiId( $identifier ) {
		$this->addLocalId( 'interwiki', $identifier );
	}

	/**
	 * Adds a navigation id to the site.
	 *
	 * @since 1.21
	 *
	 * @param string $identifier
	 */
	public function addNavigationId( $identifier ) {
		$this->addLocalId( 'equivalent', $identifier );
	}

	/**
	 * Returns the interwiki link identifiers that can be used for this site.
	 *
	 * @since 1.21
	 *
	 * @return array of string
	 */
	public function getInterwikiIds() {
		return $this->getLocalIds( 'interwiki' );
	}

	/**
	 * Returns the equivalent link identifiers that can be used to make
	 * the site show up in interfaces such as the "language links" section.
	 *
	 * @since 1.21
	 *
	 * @return array of string
	 */
	public function getNavigationIds() {
		return $this->getLocalIds( 'equivalent' );
	}

	/**
	 * Returns the internal ID of the site.
	 *
	 * @since 1.21
	 *
	 * @return integer
	 */
	public function getInternalId() {
		return $this->getId();
	}

	/**
	 * Sets the path used to construct links with.
	 * Shall be equivalent to setPath( getLinkPathType(), $fullUrl ).
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 * @param string $fullUrl
	 */
	public function setPath( $pathType, $fullUrl ) {
		$paths = $this->getExtraData( 'paths', array() );
		$paths[$pathType] = $fullUrl;
		$this->setExtraData( 'paths', $paths );
	}

	/**
	 * Returns the path of the provided type or false if there is no such path.
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 *
	 * @return string|boolean false
	 */
	public function getPath( $pathType ) {
		$paths = $this->getExtraData( 'paths', array() );
		return array_key_exists( $pathType, $paths ) ? $paths[$pathType] : false;
	}

	/**
	 * Returns the paths as associative array.
	 * The keys are path types, the values are the path urls.
	 *
	 * @since 1.21
	 *
	 * @return array of string
	 */
	public function getAllPaths() {
		return $this->getExtraData( 'paths', array() );
	}

	/**
	 * Removes the path of the provided type if it's set.
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 */
	public function removePath( $pathType ) {
		$paths = $this->getExtraData( 'paths', array() );
		unset( $paths[$pathType] );
		$this->setExtraData( 'paths', $paths );
	}


}

/**
 * @deprecated
 */
class SiteObject extends Site {}