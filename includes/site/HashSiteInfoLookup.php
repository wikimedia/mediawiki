<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME document array structure
 *
 * @since 1.32
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
class HashSiteInfoLookup implements SiteInfoLookup, SiteInfoMaintenanceLookup {

	/**
	 * @var array[]
	 */
	private $aliasesById = null;

	/**
	 * @var array[]
	 */
	protected $sites;

	/**
	 * @var array[]
	 */
	protected $aliases;

	/**
	 * @var string
	 */
	private $localId;

	/**
	 * HashSiteInfoLookup constructor.
	 *
	 * @param string $localId ID of the wiki for which this lookup is valid.
	 * @param array[] $sites
	 * @param array[] $ids
	 */
	public function __construct( $localId, array $sites, array $ids ) {
		// FIXME: assert valid
		$this->localId = $localId;
		$this->sites = $sites;
		$this->aliases = $ids;
	}

	/**
	 * Creates aliases for the given context based on the given site property.
	 *
	 * @param $property
	 * @param $context
	 */
	public function emulateAliases( $property, $context ) {
		$sites = $this->getSitesArray();

		foreach ( $sites as $id => $info ) {
			if ( isset( $info[$property] ) ) {
				$alias = $info[$property];
				$this->aliases[$context][$alias] = $id;
			}
		}
	}

	/**
	 * Returns the canonical ID of the local site (the "self" ID).
	 * In case multiple SiteInfoLookup instances exist in a single process, this ID
	 * should refer to the site for which the alias mappings provided by the instance apply.
	 *
	 * @return mixed
	 */
	public function getLocalSiteId() {
		return $this->localId;
	}

	/**
	 * @see SiteInfoLookup::getSiteInfo
	 *
	 * @param string $siteId
	 * @param array $defaults
	 *
	 * @throws OutOfBoundsException
	 * @return array
	 */
	public function getSiteInfo( $siteId, array $defaults = [] ) {
		$siteId = $this->getSiteId( $siteId );

		$sites = $this->getSitesArray();

		if ( !isset( $sites[$siteId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $siteId );
		}

		$info = array_merge( $defaults, $sites[$siteId] );

		return $info;
	}

	/**
	 * @see SiteInfoLookup::getSiteProperty
	 *
	 * @param string $siteId The ID or alias of the site.
	 * @param string $property the name of the property to look up
	 * @param mixed|null $default a value to assume if the property is not set for this site.
	 *
	 * @throws OutOfBoundsException if $siteId is not a known site
	 * @return null|string if no such site is known.
	 */
	public function getSiteProperty( $siteId, $property, $default = null ) {
		$siteId = $this->getSiteId( $siteId );

		$sites = $this->getSitesArray();

		if ( !isset( $sites[$siteId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $siteId );
		}

		if ( !isset( $sites[$siteId][$property] ) ) {
			return $default;
		}

		return $sites[$siteId][$property];
	}

	/**
	 * @see SiteInfoLookup::findSites
	 *
	 * @return string[]
	 */
	public function listSites() {
		$sites = $this->getSitesArray();
		return array_keys( $sites );
	}

	/**
	 * @see listSites::findSites
	 *
	 * @param string $property
	 * @param mixed $value
	 *
	 * @return string[]
	 */
	public function findSites( $property, $value = null ) {
		$sites = $this->getSitesArray();

		$sites = array_filter( $sites, function( $info ) use ( $property, $value ) {
			if ( !isset( $info[$property] ) ) {
				return false;
			} elseif ( $value === null ) {
				return true;
			} else {
				return $info[$property] === $value;
			}
		} );

		return array_keys( $sites );
	}

	/**
	 * @see SiteInfoLookup::getAliasesFor
	 *
	 * @param string $siteId
	 *
	 * @throws OutOfBoundsException
	 *
	 * @return array
	 */
	public function getAliasesFor( $siteId ) {
		$siteId = $this->getSiteId( $siteId );

		if ( !isset( $siteId ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $siteId );
		}

		$ids = $this->getAliasesBySiteArray();

		if ( !isset( $ids[$siteId] ) ) {
			return [];
		}

		return $ids[$siteId];
	}

	/**
	 * @see SiteInfoLookup::getSiteAlias
	 *
	 * @param string $siteId
	 * @param string $context
	 *
	 * @throws OutOfBoundsException
	 *
	 * @return string|null
	 */
	public function getSiteAlias( $siteId, $context ) {
		$siteId = $this->getSiteId( $siteId );

		$ids = $this->getAliasesBySiteArray();

		if ( !isset( $ids[$siteId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $siteId );
		}

		if ( empty( $ids[$siteId][$context] ) ) {
			return null;
		}

		$id = reset( $ids[$siteId][$context] );
		return $id;
	}

	/**
	 * @see SiteInfoLookup::getSiteId
	 *
	 * @param string $alias
	 *
	 * @param string $context
	 * @return null|string
	 */
	public function getSiteId( $alias, $context = null ) {
		$sites = $this->getSitesArray();
		if ( isset( $sites[$alias] ) ) {
			return $alias;
		}

		$aliases = $this->getAliasArray();

		$keys = $context === null ? array_keys( $aliases ) : (array)$context;

		foreach ( $keys as $k ) {
			if ( isset( $aliases[$k][$alias] ) ) {
				return $aliases[$k][$alias];
			}
		}

		return null;
	}

	/**
	 * @see SiteInfoLookup::getAliasMap
	 *
	 * @param string $context
	 *
	 * @return array[]
	 */
	public function getAliasMap( $context ) {
		$ids = $this->getAliasArray();
		if ( !isset( $ids[$context] ) ) {
			return [];
		}
		return $ids[$context];
	}

	/**
	 * @return array[]
	 */
	private function getAliasesBySiteArray() {
		if ( $this->aliasesById !== null ) {
			return $this->aliasesById;
		}

		$this->aliasesById = [];

		$idsByScope = $this->getAliasArray();
		foreach ( $idsByScope as $context => $idMap ) {
			foreach ( $idMap as $localId => $siteId ) {
				$this->aliasesById[$siteId][$context][] = $localId;
			}
		}

		return $this->aliasesById;
	}

	/**
	 * @return array[]
	 */
	protected function getSitesArray() {
		return $this->sites;
	}

	/**
	 * @return array[]
	 */
	protected function getAliasArray() {
		return $this->aliases;
	}

}
