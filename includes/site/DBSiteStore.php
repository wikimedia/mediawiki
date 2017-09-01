<?php

use Wikimedia\Rdbms\LoadBalancer;

/**
 * Represents the site configuration of a wiki.
 * Holds a list of sites (ie SiteList), stored in the database.
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
 * @since 1.25
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 */
class DBSiteStore implements SiteStore {

	/**
	 * @var SiteList|null
	 */
	protected $sites = null;

	/**
	 * @var LoadBalancer
	 */
	private $dbLoadBalancer;

	/**
	 * @since 1.27
	 *
	 * @todo: inject some kind of connection manager that is aware of the target wiki,
	 * instead of injecting a LoadBalancer.
	 *
	 * @param LoadBalancer $dbLoadBalancer
	 */
	public function __construct( LoadBalancer $dbLoadBalancer ) {
		$this->dbLoadBalancer = $dbLoadBalancer;
	}

	/**
	 * @see SiteStore::getSites
	 *
	 * @since 1.25
	 *
	 * @return SiteList
	 */
	public function getSites() {
		$this->loadSites();

		return $this->sites;
	}

	/**
	 * Fetches the site from the database and loads them into the sites field.
	 *
	 * @since 1.25
	 */
	protected function loadSites() {
		$this->sites = new SiteList();

		$dbr = $this->dbLoadBalancer->getConnection( DB_REPLICA );

		$res = $dbr->select(
			'sites',
			[
				'site_id',
				'site_global_key',
				'site_type',
				'site_group',
				'site_source',
				'site_language',
				'site_protocol',
				'site_domain',
				'site_data',
				'site_forward',
				'site_config',
			],
			'',
			__METHOD__,
			[ 'ORDER BY' => 'site_global_key' ]
		);

		foreach ( $res as $row ) {
			$site = Site::newForType( $row->site_type );
			$site->setGlobalId( $row->site_global_key );
			$site->setInternalId( (int)$row->site_id );
			$site->setForward( (bool)$row->site_forward );
			$site->setGroup( $row->site_group );
			$site->setLanguageCode( $row->site_language === ''
				? null
				: $row->site_language
			);
			$site->setSource( $row->site_source );
			$site->setExtraData( unserialize( $row->site_data ) );
			$site->setExtraConfig( unserialize( $row->site_config ) );
			$this->sites[] = $site;
		}

		// Batch load the local site identifiers.
		$ids = $dbr->select(
			'site_identifiers',
			[
				'si_site',
				'si_type',
				'si_key',
			],
			[],
			__METHOD__
		);

		foreach ( $ids as $id ) {
			if ( $this->sites->hasInternalId( $id->si_site ) ) {
				$site = $this->sites->getSiteByInternalId( $id->si_site );
				$site->addLocalId( $id->si_type, $id->si_key );
				$this->sites->setSite( $site );
			}
		}
	}

	/**
	 * @see SiteStore::getSite
	 *
	 * @since 1.25
	 *
	 * @param string $globalId
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		if ( $this->sites === null ) {
			$this->sites = $this->getSites();
		}

		return $this->sites->hasSite( $globalId ) ? $this->sites->getSite( $globalId ) : null;
	}

	/**
	 * @see SiteStore::saveSite
	 *
	 * @since 1.25
	 *
	 * @param Site $site
	 *
	 * @return bool Success indicator
	 */
	public function saveSite( Site $site ) {
		return $this->saveSites( [ $site ] );
	}

	/**
	 * @see SiteStore::saveSites
	 *
	 * @since 1.25
	 *
	 * @param Site[] $sites
	 *
	 * @return bool Success indicator
	 */
	public function saveSites( array $sites ) {
		if ( empty( $sites ) ) {
			return true;
		}

		$dbw = $this->dbLoadBalancer->getConnection( DB_MASTER );

		$dbw->startAtomic( __METHOD__ );

		$success = true;

		$internalIds = [];
		$localIds = [];

		foreach ( $sites as $site ) {
			if ( $site->getInternalId() !== null ) {
				$internalIds[] = $site->getInternalId();
			}

			$fields = [
				// Site data
				'site_global_key' => $site->getGlobalId(), // TODO: check not null
				'site_type' => $site->getType(),
				'site_group' => $site->getGroup(),
				'site_source' => $site->getSource(),
				'site_language' => $site->getLanguageCode() === null ? '' : $site->getLanguageCode(),
				'site_protocol' => $site->getProtocol(),
				'site_domain' => strrev( $site->getDomain() ) . '.',
				'site_data' => serialize( $site->getExtraData() ),

				// Site config
				'site_forward' => $site->shouldForward() ? 1 : 0,
				'site_config' => serialize( $site->getExtraConfig() ),
			];

			$rowId = $site->getInternalId();
			if ( $rowId !== null ) {
				$success = $dbw->update(
					'sites', $fields, [ 'site_id' => $rowId ], __METHOD__
				) && $success;
			} else {
				$success = $dbw->insert( 'sites', $fields, __METHOD__ ) && $success;
				$rowId = $dbw->insertId();
			}

			foreach ( $site->getLocalIds() as $idType => $ids ) {
				foreach ( $ids as $id ) {
					$localIds[] = [ $rowId, $idType, $id ];
				}
			}
		}

		if ( $internalIds !== [] ) {
			$dbw->delete(
				'site_identifiers',
				[ 'si_site' => $internalIds ],
				__METHOD__
			);
		}

		foreach ( $localIds as $localId ) {
			$dbw->insert(
				'site_identifiers',
				[
					'si_site' => $localId[0],
					'si_type' => $localId[1],
					'si_key' => $localId[2],
				],
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );

		$this->reset();

		return $success;
	}

	/**
	 * Resets the SiteList
	 *
	 * @since 1.25
	 */
	public function reset() {
		$this->sites = null;
	}

	/**
	 * Clears the list of sites stored in the database.
	 *
	 * @see SiteStore::clear()
	 *
	 * @return bool Success
	 */
	public function clear() {
		$dbw = $this->dbLoadBalancer->getConnection( DB_MASTER );

		$dbw->startAtomic( __METHOD__ );
		$ok = $dbw->delete( 'sites', '*', __METHOD__ );
		$ok = $dbw->delete( 'site_identifiers', '*', __METHOD__ ) && $ok;
		$dbw->endAtomic( __METHOD__ );

		$this->reset();

		return $ok;
	}

}
