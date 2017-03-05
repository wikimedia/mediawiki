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
class Site implements Serializable {
	const TYPE_UNKNOWN = 'unknown';
	const TYPE_MEDIAWIKI = 'mediawiki';

	const GROUP_NONE = 'none';

	const ID_INTERWIKI = 'interwiki';
	const ID_EQUIVALENT = 'equivalent';

	const SOURCE_LOCAL = 'local';

	const PATH_LINK = 'link';

	/**
	 * A version ID that identifies the serialization structure used by getSerializationData()
	 * and unserialize(). This is useful for constructing cache keys in cases where the cache relies
	 * on serialization for storing the SiteList.
	 *
	 * @var string A string uniquely identifying the version of the serialization structure.
	 */
	const SERIAL_VERSION_ID = '2013-01-23';

	/**
	 * @since 1.21
	 *
	 * @var string|null
	 */
	protected $globalId = null;

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $type = self::TYPE_UNKNOWN;

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $group = self::GROUP_NONE;

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $source = self::SOURCE_LOCAL;

	/**
	 * @since 1.21
	 *
	 * @var string|null
	 */
	protected $languageCode = null;

	/**
	 * Holds the local ids for this site.
	 * local id type => [ ids for this type (strings) ]
	 *
	 * @since 1.21
	 *
	 * @var array[]
	 */
	protected $localIds = [];

	/**
	 * @since 1.21
	 *
	 * @var array
	 */
	protected $extraData = [];

	/**
	 * @since 1.21
	 *
	 * @var array
	 */
	protected $extraConfig = [];

	/**
	 * @since 1.21
	 *
	 * @var bool
	 */
	protected $forward = false;

	/**
	 * @since 1.21
	 *
	 * @var int|null
	 */
	protected $internalId = null;

	/**
	 * Constructor.
	 *
	 * @since 1.21
	 *
	 * @param string $type
	 */
	public function __construct( $type = self::TYPE_UNKNOWN ) {
		$this->type = $type;
	}

	/**
	 * Returns the global site identifier (ie enwiktionary).
	 *
	 * @since 1.21
	 *
	 * @return string|null
	 */
	public function getGlobalId() {
		return $this->globalId;
	}

	/**
	 * Sets the global site identifier (ie enwiktionary).
	 *
	 * @since 1.21
	 *
	 * @param string|null $globalId
	 *
	 * @throws MWException
	 */
	public function setGlobalId( $globalId ) {
		if ( $globalId !== null && !is_string( $globalId ) ) {
			throw new MWException( '$globalId needs to be string or null' );
		}

		$this->globalId = $globalId;
	}

	/**
	 * Returns the type of the site (ie mediawiki).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Gets the group of the site (ie wikipedia).
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * Sets the group of the site (ie wikipedia).
	 *
	 * @since 1.21
	 *
	 * @param string $group
	 *
	 * @throws MWException
	 */
	public function setGroup( $group ) {
		if ( !is_string( $group ) ) {
			throw new MWException( '$group needs to be a string' );
		}

		$this->group = $group;
	}

	/**
	 * Returns the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * Sets the source of the site data (ie 'local', 'wikidata', 'my-magical-repo').
	 *
	 * @since 1.21
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
	 * @since 1.21
	 *
	 * @return bool
	 */
	public function shouldForward() {
		return $this->forward;
	}

	/**
	 * Sets if site.tld/path/key:pageTitle should forward users to  the page on
	 * the actual site, where "key" is the local identifier.
	 *
	 * @since 1.21
	 *
	 * @param bool $shouldForward
	 *
	 * @throws MWException
	 */
	public function setForward( $shouldForward ) {
		if ( !is_bool( $shouldForward ) ) {
			throw new MWException( '$shouldForward needs to be a boolean' );
		}

		$this->forward = $shouldForward;
	}

	/**
	 * Returns the domain of the site, ie en.wikipedia.org
	 * Or false if it's not known.
	 *
	 * @since 1.21
	 *
	 * @return string|null
	 */
	public function getDomain() {
		$path = $this->getLinkPath();

		if ( $path === null ) {
			return null;
		}

		return parse_url( $path, PHP_URL_HOST );
	}

	/**
	 * Returns the protocol of the site.
	 *
	 * @since 1.21
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getProtocol() {
		$path = $this->getLinkPath();

		if ( $path === null ) {
			return '';
		}

		$protocol = parse_url( $path, PHP_URL_SCHEME );

		// Malformed URL
		if ( $protocol === false ) {
			throw new MWException( "failed to parse URL '$path'" );
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

		if ( $type === null ) {
			throw new MWException( "This Site does not support link paths." );
		}

		$this->setPath( $type, $fullUrl );
	}

	/**
	 * Returns the path used to construct links with or false if there is no such path.
	 *
	 * Shall be equivalent to getPath( getLinkPathType() ).
	 *
	 * @return string|null
	 */
	public function getLinkPath() {
		$type = $this->getLinkPathType();
		return $type === null ? null: $this->getPath( $type );
	}

	/**
	 * Returns the main path type, that is the type of the path that should
	 * generally be used to construct links to the target site.
	 *
	 * This default implementation returns Site::PATH_LINK as the default path
	 * type. Subclasses can override this to define a different default path
	 * type, or return false to disable site links.
	 *
	 * @since 1.21
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
	 * @since 1.21
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
	 * Returns the type specific fields.
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getExtraData() {
		return $this->extraData;
	}

	/**
	 * Sets the type specific fields.
	 *
	 * @since 1.21
	 *
	 * @param array $extraData
	 */
	public function setExtraData( array $extraData ) {
		$this->extraData = $extraData;
	}

	/**
	 * Returns the type specific config.
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getExtraConfig() {
		return $this->extraConfig;
	}

	/**
	 * Sets the type specific config.
	 *
	 * @since 1.21
	 *
	 * @param array $extraConfig
	 */
	public function setExtraConfig( array $extraConfig ) {
		$this->extraConfig = $extraConfig;
	}

	/**
	 * Returns language code of the sites primary language.
	 * Or null if it's not known.
	 *
	 * @since 1.21
	 *
	 * @return string|null
	 */
	public function getLanguageCode() {
		return $this->languageCode;
	}

	/**
	 * Sets language code of the sites primary language.
	 *
	 * @since 1.21
	 *
	 * @param string $languageCode
	 */
	public function setLanguageCode( $languageCode ) {
		if ( !Language::isValidCode( $languageCode ) ) {
			throw new InvalidArgumentException( "$languageCode is not a valid language code." );
		}
		$this->languageCode = $languageCode;
	}

	/**
	 * Returns the set internal identifier for the site.
	 *
	 * @since 1.21
	 *
	 * @return string|null
	 */
	public function getInternalId() {
		return $this->internalId;
	}

	/**
	 * Sets the internal identifier for the site.
	 * This typically is a primary key in a db table.
	 *
	 * @since 1.21
	 *
	 * @param int|null $internalId
	 */
	public function setInternalId( $internalId = null ) {
		$this->internalId = $internalId;
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
			$this->localIds = [];
		}

		if ( !array_key_exists( $type, $this->localIds ) ) {
			$this->localIds[$type] = [];
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
		$this->addLocalId( self::ID_INTERWIKI, $identifier );
	}

	/**
	 * Adds a navigation id to the site.
	 *
	 * @since 1.21
	 *
	 * @param string $identifier
	 */
	public function addNavigationId( $identifier ) {
		$this->addLocalId( self::ID_EQUIVALENT, $identifier );
	}

	/**
	 * Returns the interwiki link identifiers that can be used for this site.
	 *
	 * @since 1.21
	 *
	 * @return string[]
	 */
	public function getInterwikiIds() {
		return array_key_exists( self::ID_INTERWIKI, $this->localIds )
			? $this->localIds[self::ID_INTERWIKI]
			: [];
	}

	/**
	 * Returns the equivalent link identifiers that can be used to make
	 * the site show up in interfaces such as the "language links" section.
	 *
	 * @since 1.21
	 *
	 * @return string[]
	 */
	public function getNavigationIds() {
		return array_key_exists( self::ID_EQUIVALENT, $this->localIds )
			? $this->localIds[self::ID_EQUIVALENT] :
			[];
	}

	/**
	 * Returns all local ids
	 *
	 * @since 1.21
	 *
	 * @return array[]
	 */
	public function getLocalIds() {
		return $this->localIds;
	}

	/**
	 * Sets the path used to construct links with.
	 * Shall be equivalent to setPath( getLinkPathType(), $fullUrl ).
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 * @param string $fullUrl
	 *
	 * @throws MWException
	 */
	public function setPath( $pathType, $fullUrl ) {
		if ( !is_string( $fullUrl ) ) {
			throw new MWException( '$fullUrl needs to be a string' );
		}

		if ( !array_key_exists( 'paths', $this->extraData ) ) {
			$this->extraData['paths'] = [];
		}

		$this->extraData['paths'][$pathType] = $fullUrl;
	}

	/**
	 * Returns the path of the provided type or false if there is no such path.
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 *
	 * @return string|null
	 */
	public function getPath( $pathType ) {
		$paths = $this->getAllPaths();
		return array_key_exists( $pathType, $paths ) ? $paths[$pathType] : null;
	}

	/**
	 * Returns the paths as associative array.
	 * The keys are path types, the values are the path urls.
	 *
	 * @since 1.21
	 *
	 * @return string[]
	 */
	public function getAllPaths() {
		return array_key_exists( 'paths', $this->extraData ) ? $this->extraData['paths'] : [];
	}

	/**
	 * Removes the path of the provided type if it's set.
	 *
	 * @since 1.21
	 *
	 * @param string $pathType
	 */
	public function removePath( $pathType ) {
		if ( array_key_exists( 'paths', $this->extraData ) ) {
			unset( $this->extraData['paths'][$pathType] );
		}
	}

	/**
	 * @since 1.21
	 *
	 * @param string $siteType
	 *
	 * @return Site
	 */
	public static function newForType( $siteType ) {
		global $wgSiteTypes;

		if ( array_key_exists( $siteType, $wgSiteTypes ) ) {
			return new $wgSiteTypes[$siteType]();
		}

		return new Site();
	}

	/**
	 * @see Serializable::serialize
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function serialize() {
		$fields = [
			'globalid' => $this->globalId,
			'type' => $this->type,
			'group' => $this->group,
			'source' => $this->source,
			'language' => $this->languageCode,
			'localids' => $this->localIds,
			'config' => $this->extraConfig,
			'data' => $this->extraData,
			'forward' => $this->forward,
			'internalid' => $this->internalId,

		];

		return serialize( $fields );
	}

	/**
	 * @see Serializable::unserialize
	 *
	 * @since 1.21
	 *
	 * @param string $serialized
	 */
	public function unserialize( $serialized ) {
		$fields = unserialize( $serialized );

		$this->__construct( $fields['type'] );

		$this->setGlobalId( $fields['globalid'] );
		$this->setGroup( $fields['group'] );
		$this->setSource( $fields['source'] );
		$this->setLanguageCode( $fields['language'] );
		$this->localIds = $fields['localids'];
		$this->setExtraConfig( $fields['config'] );
		$this->setExtraData( $fields['data'] );
		$this->setForward( $fields['forward'] );
		$this->setInternalId( $fields['internalid'] );
	}
}
