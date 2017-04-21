<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME array structure
 *
 * @since 1.29
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
class HashSiteInfoLookup implements SiteInfoLookup {

	/**
	 * @var array[]
	 */
	private $idsBySite = null;

	/**
	 * @var array[]
	 */
	protected $sites;

	/**
	 * @var array[]
	 */
	protected $ids;

	/**
	 * HashSiteInfoLookup constructor.
	 *
	 * @param array[] $sites
	 * @param array[] $ids
	 */
	public function __construct( array $sites, array $ids ) {
		//FIXME: assert valid
		$this->sites = $sites;
		$this->ids = $ids;
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
		$siteId = $this->unalias( $siteId );

		$sites = $this->getSitesArray();

		if ( !isset( $sites[$siteId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $siteId );
		}

		$info = array_merge( $defaults, $sites[$siteId] );

		return $info;
	}

	/**
	 * @see SiteInfoLookup::listSites
	 *
	 * @return string[]
	 */
	public function listSites() {
		$sites = $this->getSitesArray();
		return array_keys( $sites );
	}

	/**
	 * @see SiteInfoLookup::listGroupMembers
	 *
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return string[]
	 */
	public function listGroupMembers( $field, $value ) {
		$sites = $this->getSitesArray();

		$sites = array_filter( $sites, function( $info ) use ( $field, $value ) {
			return array_key_exists( $field, $info ) && ( $info[$field] === $value );
		} );

		return array_keys( $sites );
	}

	/**
	 * @see SiteInfoLookup::getAssociatedLocalIds
	 *
	 * @param string $siteId
	 *
	 * @throws OutOfBoundsException
	 *
	 * @return array
	 */
	public function getAssociatedLocalIds( $siteId ) {
		$siteId = $this->unalias( $siteId );

		$ids = $this->getIdsBySiteArray();

		if ( !isset( $ids[$siteId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $siteId );
		}

		return $ids[$siteId];
	}

	/**
	 * @see SiteInfoLookup::getLocalId
	 *
	 * @param string $siteId
	 * @param string $scope
	 *
	 * @throws OutOfBoundsException
	 *
	 * @return string|null
	 */
	public function getLocalId( $siteId, $scope ) {
		$siteId = $this->unalias( $siteId );

		$ids = $this->getIdsBySiteArray();

		if ( !isset( $ids[$siteId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $siteId );
		}

		if ( empty( $ids[$siteId][$scope] ) ) {
			return null;
		}

		$id = reset( $ids[$siteId][$scope] );
		return $id;
	}

	/**
	 * @see SiteInfoLookup::resolveLocalId
	 *
	 * @param string $scope
	 * @param string $localId
	 *
	 * @return string|null
	 */
	public function resolveLocalId( $scope, $localId ) {
		$ids = $this->getIdArray();
		if ( !isset( $ids[$scope] ) ) {
			return null;
		}
		if ( !isset( $ids[$scope][$localId] ) ) {
			return null;
		}
		return $ids[$scope][$localId];
	}

	/**
	 * @see SiteInfoLookup::getIdMap
	 *
	 * @param string $scope
	 *
	 * @return array[]
	 */
	public function getIdMap( $scope ) {
		$ids = $this->getIdArray();
		if ( !isset( $ids[$scope] ) ) {
			return [];
		}
		return $ids[$scope];
	}

	private function unalias( $siteId ) {
		$unaliased = $this->resolveLocalId( SiteInfoLookup::ALIAS_ID, $siteId );
		return $unaliased ?: $siteId;
	}

	/**
	 * @return array[]
	 */
	private function getIdsBySiteArray() {
		if ( $this->idsBySite !== null ) {
			return $this->idsBySite;
		}

		$this->idsBySite = [];

		$idsByScope = $this->getIdArray();
		foreach ( $idsByScope as $scope => $idMap ) {
			foreach ( $idMap as $localId => $siteId ) {
				$this->idsBySite[$siteId][$scope][] = $localId;
			}
		}

		return $this->idsBySite;
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
	protected function getIdArray() {
		return $this->ids;
	}

}
