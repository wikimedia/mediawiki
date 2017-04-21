<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME
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
	private $groupsBySite = null;

	/**
	 * @var array[]
	 */
	protected $sites;

	/**
	 * @var array[]
	 */
	protected $ids;

	/**
	 * @var array[]
	 */
	protected $groups;

	/**
	 * HashSiteInfoLookup constructor.
	 *
	 * @param array[] $sites
	 * @param array[] $ids
	 * @param array[] $groups
	 */
	public function __construct( array $sites, array $ids, array $groups ) {
		//FIXME: assert valid
		$this->sites = $sites;
		$this->ids = $ids;
		$this->groups = $groups;
	}

	/**
	 * Returns the info array for the given site.
	 *
	 * @param string $globalId
	 *
	 * @param string[] $keys The info keys the caller is interested in.
	 *        If this parameter is not null, implementations may omit any key not given in $keys
	 *        from the result, but must guarantee that all these keys are set.
	 *        If for any of these keys no value is known, that key must be mapped to null
	 *        in the array returned.
	 *
	 * @throws OutOfBoundsException if $globalId is not a known site
	 *
	 * @return array An associative array representing properties of the given site.
	 *         See SITE_XXX for well known keys.
	 *
	 */
	public function getSiteInfo( $globalId, array $keys = null ) {
		$sites = $this->getSitesArray();

		if ( !isset( $sites[$globalId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $globalId );
		}

		$info = $this->applyKeySet( $sites[$globalId], $keys );

		return $info;
	}

	/**
	 * Returns an info array for each site.
	 *
	 * @param string[] $keys The info keys the caller is interested in.
	 *        If this parameter is not null, implementations may omit any key not given in $keys
	 *        from the result, but must guarantee that all these keys are set.
	 *        If for any of these keys no value is known, that key must be mapped to null
	 *        in the array returned.
	 *
	 * @return array[] An associative array mapping global site IDs to associative arrays,
	 *         similar to the ones returned by getSiteInfo().
	 */
	public function listSiteInfo( array $keys = null ) {
		$sites = $this->getSitesArray();
		$result = [];

		foreach ( $sites as $id => $info ) {
			$result[$id] = $this->applyKeySet( $info, $keys );
		}

		return $result;
	}

	/**
	 * @param array $info
	 * @param array|null $keys
	 *
	 * @return array
	 */
	private function applyKeySet( array $info, $keys ) {
		if ( $keys !== null ) {
			$nulls = array_fill_keys( $keys, null );
			$info = array_merge( $nulls, $info );
			$info = array_intersect_key( $info, array_flip( $keys ) );
		}

		return $info;
	}

	/**
	 * List all known sites.
	 *
	 * @return string[] The global site IDs of all known sites.
	 */
	public function listSites() {
		$sites = $this->getSitesArray();
		return array_keys( $sites );
	}

	/**
	 * Returns the groups the site belongs to.
	 *
	 * @param string $globalId
	 *
	 * @throws OutOfBoundsException if $globalId is not a known site
	 *
	 * @return array Associative array giving the group memberships of the given site.
	 *         Keys represent the grouping criterion. XXX_GROUP constants for well known keys.
	 */
	public function getSiteGroups( $globalId ) {
		$groups = $this->getGroupsBySiteArray();
		if ( !isset( $groups[$globalId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $globalId );
		}
		return $groups[$globalId];
	}

	/**
	 * Returns the IDs that refer to a site in a given context.
	 *
	 * @param string $globalId
	 *
	 * @throws OutOfBoundsException if $globalId is not a known site
	 *
	 * @return array Associative array giving all identifiers of the given site.
	 *         Keys represent the context in which an id is valid, see the XXX_ID constants
	 *         for well known keys. Values are lists of identifiers, the first entry being the
	 *         preferred id in a given context.
	 */
	public function getSiteIds( $globalId ) {
		$ids = $this->getIdsBySiteArray();
		if ( !isset( $ids[$globalId] ) ) {
			throw new OutOfBoundsException( 'Unknown Site: ' . $globalId );
		}
		return $ids[$globalId];
	}

	/**
	 * Returns the global site ID of the site identified by the given ID in the given context.
	 *
	 * @param string $context The context in which to resolve the ID. See XXX_ID for well known
	 *        values.
	 * @param string $id The ID to resolve.
	 *
	 * @return string|null The site's global ID, or null if no such site is known.
	 */
	public function getGlobalId( $context, $id ) {
		$ids = $this->getIdArray();
		if ( !isset( $ids[$context] ) ) {
			return null;
		}
		if ( !isset( $ids[$context][$id] ) ) {
			return null;
		}
		return $ids[$context][$id];
	}

	/**
	 * Returns the global IDs of the members of the given group.
	 *
	 * @param string $criterion The grouping criterion. See XXX_GROUP for well known values.
	 * @param string $group The name of the group to list.
	 *
	 * @return string[] The global site IDs of all members of the given group. If the group is
	 *         not known, an empty list will be returned.
	 */
	public function listGroupMembers( $criterion, $group ) {
		$groups = $this->getGroupArray();
		if ( !isset( $groups[$criterion] ) ) {
			return [];
		}
		if ( !isset( $groups[$criterion][$group] ) ) {
			return [];
		}
		return $groups[$criterion][$group];
	}

	/**
	 * Returns the mapping of all IDs defined in the given context.
	 *
	 * @param string $context The context in which to resolve the ID. See XXX_ID for well known
	 *        values.
	 *
	 * @return array[] An associative array giving the ID resolution for the given context.
	 *         Keys are identifiers valid in the context, values are global site IDs.
	 */
	public function listIds( $context ) {
		$ids = $this->getIdArray();
		if ( !isset( $ids[$context] ) ) {
			return [];
		}
		return $ids[$context];
	}

	/**
	 * @return array[]
	 */
	private function getIdsBySiteArray() {
		if ( $this->idsBySite !== null ) {
			return $this->idsBySite;
		}

		$this->idsBySite = [];

		$idsByContext = $this->getIdArray();
		foreach ( $idsByContext as $context => $idMap ) {
			foreach ( $idMap as $localId => $globalId ) {
				$this->idsBySite[$globalId][$context][] = $localId;
			}
		}

		return $this->idsBySite;
	}

	/**
	 * @return array[]
	 */
	private function getGroupsBySiteArray() {
		if ( $this->groupsBySite !== null ) {
			return $this->groupsBySite;
		}

		$this->groupsBySite = [];

		$groupsByCriterion = $this->getGroupArray();
		foreach ( $groupsByCriterion as $criterion => $groups ) {
			foreach ( $groups as $groupName => $members ) {
				foreach ( $members as $globalId ) {
					$this->groupsBySite[$globalId][$criterion] = $groupName;
				}
			}
		}

		return $this->groupsBySite;
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

	/**
	 * @return array[]
	 */
	protected function getGroupArray() {
		return $this->groups;
	}

}
