<?php
/**
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
 * @file
 */

namespace MediaWiki\Site;

use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;

/**
 * Holds a list of sites stored in the database.
 *
 * @since 1.25
 * @ingroup Site
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 */
class DBSiteStore implements SiteStore {
	/** @var SiteList|null */
	protected $sites = null;
	/** @var IConnectionProvider */
	private $dbProvider;

	/**
	 * @since 1.27
	 * @param IConnectionProvider $dbProvider
	 */
	public function __construct( IConnectionProvider $dbProvider ) {
		$this->dbProvider = $dbProvider;
	}

	/**
	 * @see SiteStore::getSites
	 *
	 * @since 1.25
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

		$dbr = $this->dbProvider->getReplicaDatabase();

		$res = $dbr->newSelectQueryBuilder()
			->select( [
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
			] )
			->from( 'sites' )
			->orderBy( 'site_global_key' )
			->caller( __METHOD__ )->fetchResultSet();

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
		$ids = $dbr->newSelectQueryBuilder()
			->select( [ 'si_site', 'si_type', 'si_key', ] )
			->from( 'site_identifiers' )
			->caller( __METHOD__ )->fetchResultSet();

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
	 * @param string $globalId
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
	 * @param Site $site
	 * @return bool Success indicator
	 */
	public function saveSite( Site $site ) {
		return $this->saveSites( [ $site ] );
	}

	/**
	 * @see SiteStore::saveSites
	 *
	 * @since 1.25
	 * @param Site[] $sites
	 * @return bool Success indicator
	 */
	public function saveSites( array $sites ) {
		if ( !$sites ) {
			return true;
		}

		$dbw = $this->dbProvider->getPrimaryDatabase();

		$dbw->startAtomic( __METHOD__ );

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
				'site_language' => $site->getLanguageCode() ?? '',
				'site_protocol' => $site->getProtocol(),
				'site_domain' => strrev( $site->getDomain() ?? '' ) . '.',
				'site_data' => serialize( $site->getExtraData() ),

				// Site config
				'site_forward' => $site->shouldForward() ? 1 : 0,
				'site_config' => serialize( $site->getExtraConfig() ),
			];

			$rowId = $site->getInternalId();
			if ( $rowId !== null ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'sites' )
					->set( $fields )
					->where( [ 'site_id' => $rowId ] )
					->caller( __METHOD__ )->execute();
			} else {
				$dbw->newInsertQueryBuilder()
					->insertInto( 'sites' )
					->row( $fields )
					->caller( __METHOD__ )->execute();
				$rowId = $dbw->insertId();
			}

			foreach ( $site->getLocalIds() as $idType => $ids ) {
				foreach ( $ids as $id ) {
					$localIds[] = [ $rowId, $idType, $id ];
				}
			}
		}

		if ( $internalIds !== [] ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'site_identifiers' )
				->where( [ 'si_site' => $internalIds ] )
				->caller( __METHOD__ )->execute();
		}

		foreach ( $localIds as $localId ) {
			$dbw->newInsertQueryBuilder()
				->insertInto( 'site_identifiers' )
				->row( [ 'si_site' => $localId[0], 'si_type' => $localId[1], 'si_key' => $localId[2] ] )
				->caller( __METHOD__ )->execute();
		}

		$dbw->endAtomic( __METHOD__ );

		$this->reset();

		return true;
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
	 */
	public function clear() {
		$dbw = $this->dbProvider->getPrimaryDatabase();

		$dbw->startAtomic( __METHOD__ );
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'sites' )
			->where( IDatabase::ALL_ROWS )
			->caller( __METHOD__ )->execute();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'site_identifiers' )
			->where( IDatabase::ALL_ROWS )
			->caller( __METHOD__ )->execute();
		$dbw->endAtomic( __METHOD__ );

		$this->reset();
	}

}

/** @deprecated class alias since 1.41 */
class_alias( DBSiteStore::class, 'DBSiteStore' );
