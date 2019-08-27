<?php
/**
 * External storage in SQL database.
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
 * @file
 */

use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\MaintainableDBConnRef;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBUnexpectedError;

/**
 * DB accessible external objects.
 *
 * In this system, each store "location" maps to a database "cluster".
 * The clusters must be defined in the normal LBFactory configuration.
 *
 * @ingroup ExternalStorage
 */
class ExternalStoreDB extends ExternalStoreMedium {
	/** @var LBFactory */
	private $lbFactory;

	/**
	 * @see ExternalStoreMedium::__construct()
	 * @param array $params Additional parameters include:
	 *   - lbFactory: an LBFactory instance
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		if ( !isset( $params['lbFactory'] ) || !( $params['lbFactory'] instanceof LBFactory ) ) {
			throw new InvalidArgumentException( "LBFactory required in 'lbFactory' field." );
		}
		$this->lbFactory = $params['lbFactory'];
	}

	/**
	 * The provided URL is in the form of DB://cluster/id
	 * or DB://cluster/id/itemid for concatened storage.
	 *
	 * @param string $url
	 * @return string|bool False if missing
	 * @see ExternalStoreMedium::fetchFromURL()
	 */
	public function fetchFromURL( $url ) {
		list( $cluster, $id, $itemID ) = $this->parseURL( $url );
		$ret = $this->fetchBlob( $cluster, $id, $itemID );

		if ( $itemID !== false && $ret !== false ) {
			return $ret->getItem( $itemID );
		}

		return $ret;
	}

	/**
	 * Fetch data from given external store URLs.
	 * The provided URLs are in the form of DB://cluster/id
	 * or DB://cluster/id/itemid for concatened storage.
	 *
	 * @param array $urls An array of external store URLs
	 * @return array A map from url to stored content. Failed results
	 *     are not represented.
	 */
	public function batchFetchFromURLs( array $urls ) {
		$batched = $inverseUrlMap = [];
		foreach ( $urls as $url ) {
			list( $cluster, $id, $itemID ) = $this->parseURL( $url );
			$batched[$cluster][$id][] = $itemID;
			// false $itemID gets cast to int, but should be ok
			// since we do === from the $itemID in $batched
			$inverseUrlMap[$cluster][$id][$itemID] = $url;
		}
		$ret = [];
		foreach ( $batched as $cluster => $batchByCluster ) {
			$res = $this->batchFetchBlobs( $cluster, $batchByCluster );
			/** @var HistoryBlob $blob */
			foreach ( $res as $id => $blob ) {
				foreach ( $batchByCluster[$id] as $itemID ) {
					$url = $inverseUrlMap[$cluster][$id][$itemID];
					if ( $itemID === false ) {
						$ret[$url] = $blob;
					} else {
						$ret[$url] = $blob->getItem( $itemID );
					}
				}
			}
		}

		return $ret;
	}

	/**
	 * @inheritDoc
	 */
	public function store( $location, $data ) {
		$dbw = $this->getMaster( $location );
		$dbw->insert(
			$this->getTable( $dbw, $location ),
			[ 'blob_text' => $data ],
			__METHOD__
		);
		$id = $dbw->insertId();
		if ( !$id ) {
			throw new MWException( __METHOD__ . ': no insert ID' );
		}

		return "DB://$location/$id";
	}

	/**
	 * @inheritDoc
	 */
	public function isReadOnly( $location ) {
		if ( parent::isReadOnly( $location ) ) {
			return true;
		}

		$lb = $this->getLoadBalancer( $location );
		$domainId = $this->getDomainId( $lb->getServerInfo( $lb->getWriterIndex() ) );

		return ( $lb->getReadOnlyReason( $domainId ) !== false );
	}

	/**
	 * Get a LoadBalancer for the specified cluster
	 *
	 * @param string $cluster Cluster name
	 * @return ILoadBalancer
	 */
	private function getLoadBalancer( $cluster ) {
		return $this->lbFactory->getExternalLB( $cluster );
	}

	/**
	 * Get a replica DB connection for the specified cluster
	 *
	 * @since 1.34
	 * @param string $cluster Cluster name
	 * @return DBConnRef
	 */
	public function getReplica( $cluster ) {
		$lb = $this->getLoadBalancer( $cluster );

		return $lb->getConnectionRef(
			DB_REPLICA,
			[],
			$this->getDomainId( $lb->getServerInfo( $lb->getWriterIndex() ) ),
			$lb::CONN_TRX_AUTOCOMMIT
		);
	}

	/**
	 * Get a replica DB connection for the specified cluster
	 *
	 * @param string $cluster Cluster name
	 * @return DBConnRef
	 * @deprecated since 1.34
	 */
	public function getSlave( $cluster ) {
		return $this->getReplica( $cluster );
	}

	/**
	 * Get a master database connection for the specified cluster
	 *
	 * @param string $cluster Cluster name
	 * @return MaintainableDBConnRef
	 */
	public function getMaster( $cluster ) {
		$lb = $this->getLoadBalancer( $cluster );

		return $lb->getMaintenanceConnectionRef(
			DB_MASTER,
			[],
			$this->getDomainId( $lb->getServerInfo( $lb->getWriterIndex() ) ),
			$lb::CONN_TRX_AUTOCOMMIT
		);
	}

	/**
	 * @param array $server Master DB server configuration array for LoadBalancer
	 * @return string|bool Database domain ID or false
	 */
	private function getDomainId( array $server ) {
		if ( $this->isDbDomainExplicit ) {
			return $this->dbDomain; // explicit foreign domain
		}

		if ( isset( $server['dbname'] ) ) {
			// T200471: for b/c, treat any "dbname" field as forcing which database to use.
			// MediaWiki/LoadBalancer previously did not enforce any concept of a local DB
			// domain, but rather assumed that the LB server configuration matched $wgDBname.
			// This check is useful when the external storage DB for this cluster does not use
			// the same name as the corresponding "main" DB(s) for wikis.
			$domain = new DatabaseDomain(
				$server['dbname'],
				$server['schema'] ?? null,
				$server['tablePrefix'] ?? ''
			);

			return $domain->getId();
		}

		return false; // local LB domain
	}

	/**
	 * Get the 'blobs' table name for this database
	 *
	 * @param IDatabase $db
	 * @param string|null $cluster Cluster name
	 * @return string Table name ('blobs' by default)
	 */
	public function getTable( $db, $cluster = null ) {
		if ( $cluster !== null ) {
			$lb = $this->getLoadBalancer( $cluster );
			$info = $lb->getServerInfo( $lb->getWriterIndex() );
			if ( isset( $info['blobs table'] ) ) {
				return $info['blobs table'];
			}
		}

		return $db->getLBInfo( 'blobs table' ) ?? 'blobs'; // b/c
	}

	/**
	 * Create the appropriate blobs table on this cluster
	 *
	 * @see getTable()
	 * @since 1.34
	 * @param string $cluster
	 */
	public function initializeTable( $cluster ) {
		global $IP;

		static $supportedTypes = [ 'mysql', 'sqlite' ];

		$dbw = $this->getMaster( $cluster );
		if ( !in_array( $dbw->getType(), $supportedTypes, true ) ) {
			throw new DBUnexpectedError( $dbw, "RDBMS type '{$dbw->getType()}' not supported." );
		}

		$sqlFilePath = "$IP/maintenance/storage/blobs.sql";
		$sql = file_get_contents( $sqlFilePath );
		if ( $sql === false ) {
			throw new RuntimeException( "Failed to read '$sqlFilePath'." );
		}

		$rawTable = $this->getTable( $dbw, $cluster ); // e.g. "blobs_cluster23"
		$encTable = $dbw->tableName( $rawTable );
		$dbw->query(
			str_replace(
				[ '/*$wgDBprefix*/blobs', '/*_*/blobs' ],
				[ $encTable, $encTable ],
				$sql
			),
			__METHOD__,
			$dbw::QUERY_IGNORE_DBO_TRX
		);
	}

	/**
	 * Fetch a blob item out of the database; a cache of the last-loaded
	 * blob will be kept so that multiple loads out of a multi-item blob
	 * can avoid redundant database access and decompression.
	 * @param string $cluster
	 * @param string $id
	 * @param string $itemID
	 * @return HistoryBlob|bool Returns false if missing
	 */
	private function fetchBlob( $cluster, $id, $itemID ) {
		/**
		 * One-step cache variable to hold base blobs; operations that
		 * pull multiple revisions may often pull multiple times from
		 * the same blob. By keeping the last-used one open, we avoid
		 * redundant unserialization and decompression overhead.
		 */
		static $externalBlobCache = [];

		$cacheID = ( $itemID === false ) ? "$cluster/$id" : "$cluster/$id/";
		$cacheID = "$cacheID@{$this->dbDomain}";

		if ( isset( $externalBlobCache[$cacheID] ) ) {
			$this->logger->debug( "ExternalStoreDB::fetchBlob cache hit on $cacheID" );

			return $externalBlobCache[$cacheID];
		}

		$this->logger->debug( "ExternalStoreDB::fetchBlob cache miss on $cacheID" );

		$dbr = $this->getReplica( $cluster );
		$ret = $dbr->selectField(
			$this->getTable( $dbr, $cluster ),
			'blob_text',
			[ 'blob_id' => $id ],
			__METHOD__
		);
		if ( $ret === false ) {
			$this->logger->info( "ExternalStoreDB::fetchBlob master fallback on $cacheID" );
			// Try the master
			$dbw = $this->getMaster( $cluster );
			$ret = $dbw->selectField(
				$this->getTable( $dbw, $cluster ),
				'blob_text',
				[ 'blob_id' => $id ],
				__METHOD__
			);
			if ( $ret === false ) {
				$this->logger->error( "ExternalStoreDB::fetchBlob master failed to find $cacheID" );
			}
		}
		if ( $itemID !== false && $ret !== false ) {
			// Unserialise object; caller extracts item
			$ret = unserialize( $ret );
		}

		$externalBlobCache = [ $cacheID => $ret ];

		return $ret;
	}

	/**
	 * Fetch multiple blob items out of the database
	 *
	 * @param string $cluster A cluster name valid for use with LBFactory
	 * @param array $ids A map from the blob_id's to look for to the requested itemIDs in the blobs
	 * @return array A map from the blob_id's requested to their content.
	 *   Unlocated ids are not represented
	 */
	private function batchFetchBlobs( $cluster, array $ids ) {
		$dbr = $this->getReplica( $cluster );
		$res = $dbr->select(
			$this->getTable( $dbr, $cluster ),
			[ 'blob_id', 'blob_text' ],
			[ 'blob_id' => array_keys( $ids ) ],
			__METHOD__
		);

		$ret = [];
		if ( $res !== false ) {
			$this->mergeBatchResult( $ret, $ids, $res );
		}
		if ( $ids ) {
			$this->logger->info(
				__METHOD__ . ": master fallback on '$cluster' for: " .
				implode( ',', array_keys( $ids ) )
			);
			// Try the master
			$dbw = $this->getMaster( $cluster );
			$res = $dbw->select(
				$this->getTable( $dbr, $cluster ),
				[ 'blob_id', 'blob_text' ],
				[ 'blob_id' => array_keys( $ids ) ],
				__METHOD__ );
			if ( $res === false ) {
				$this->logger->error( __METHOD__ . ": master failed on '$cluster'" );
			} else {
				$this->mergeBatchResult( $ret, $ids, $res );
			}
		}
		if ( $ids ) {
			$this->logger->error(
				__METHOD__ . ": master on '$cluster' failed locating items: " .
				implode( ',', array_keys( $ids ) )
			);
		}

		return $ret;
	}

	/**
	 * Helper function for self::batchFetchBlobs for merging master/replica DB results
	 * @param array &$ret Current self::batchFetchBlobs return value
	 * @param array &$ids Map from blob_id to requested itemIDs
	 * @param mixed $res DB result from Database::select
	 */
	private function mergeBatchResult( array &$ret, array &$ids, $res ) {
		foreach ( $res as $row ) {
			$id = $row->blob_id;
			$itemIDs = $ids[$id];
			unset( $ids[$id] ); // to track if everything is found
			if ( count( $itemIDs ) === 1 && reset( $itemIDs ) === false ) {
				// single result stored per blob
				$ret[$id] = $row->blob_text;
			} else {
				// multi result stored per blob
				$ret[$id] = unserialize( $row->blob_text );
			}
		}
	}

	/**
	 * @param string $url
	 * @return array
	 */
	protected function parseURL( $url ) {
		$path = explode( '/', $url );

		return [
			$path[2], // cluster
			$path[3], // id
			$path[4] ?? false // itemID
		];
	}
}
