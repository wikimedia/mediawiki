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

use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\Query;
use Wikimedia\Rdbms\ServerInfo;
use Wikimedia\ScopedCallback;

/**
 * External storage in a SQL database.
 *
 * In this system, each store "location" maps to a database "cluster".
 * The clusters must be defined in the normal LBFactory configuration.
 *
 * @see ExternalStoreAccess
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
	 * Fetch data from given external store URL.
	 *
	 * The provided URL is in the form of `DB://cluster/id` or `DB://cluster/id/itemid`
	 * for concatenated storage if ConcatenatedGzipHistoryBlob was used.
	 *
	 * @param string $url
	 * @return string|false False if missing
	 * @see ExternalStoreMedium::fetchFromURL()
	 */
	public function fetchFromURL( $url ) {
		[ $cluster, $id, $itemID ] = $this->parseURL( $url );
		$ret = $this->fetchBlob( $cluster, $id, $itemID );

		if ( $itemID !== false && $ret !== false ) {
			return $ret->getItem( $itemID );
		}

		return $ret;
	}

	/**
	 * Fetch multiple URLs from given external store.
	 *
	 * The provided URLs are in the form of `DB://cluster/id`, or `DB://cluster/id/itemid`
	 * for concatenated storage if ConcatenatedGzipHistoryBlob was used.
	 *
	 * @param array $urls An array of external store URLs
	 * @return array A map from url to stored content. Failed results
	 *     are not represented.
	 */
	public function batchFetchFromURLs( array $urls ) {
		$batched = $inverseUrlMap = [];
		foreach ( $urls as $url ) {
			[ $cluster, $id, $itemID ] = $this->parseURL( $url );
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
		$blobsTable = $this->getTable( $location );

		$dbw = $this->getPrimary( $location );
		$dbw->newInsertQueryBuilder()
			->insertInto( $blobsTable )
			->row( [ 'blob_text' => $data ] )
			->caller( __METHOD__ )->execute();

		$id = $dbw->insertId();
		if ( !$id ) {
			throw new ExternalStoreException( __METHOD__ . ': no insert ID' );
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

		return ( $this->getLoadBalancer( $location )->getReadOnlyReason() !== false );
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
	 * @return \Wikimedia\Rdbms\IReadableDatabase
	 */
	public function getReplica( $cluster ) {
		$lb = $this->getLoadBalancer( $cluster );

		return $lb->getConnection(
			DB_REPLICA,
			[],
			$this->getDomainId( $lb->getServerInfo( ServerInfo::WRITER_INDEX ) ),
			$lb::CONN_TRX_AUTOCOMMIT
		);
	}

	/**
	 * Get a primary database connection for the specified cluster
	 *
	 * @param string $cluster Cluster name
	 * @return \Wikimedia\Rdbms\IMaintainableDatabase
	 * @since 1.37
	 */
	public function getPrimary( $cluster ) {
		$lb = $this->getLoadBalancer( $cluster );

		return $lb->getMaintenanceConnectionRef(
			DB_PRIMARY,
			[],
			$this->getDomainId( $lb->getServerInfo( ServerInfo::WRITER_INDEX ) ),
			$lb::CONN_TRX_AUTOCOMMIT
		);
	}

	/**
	 * @param array $server Primary DB server configuration array for LoadBalancer
	 * @return string|false Database domain ID or false
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
	 * Get the configured blobs table name for this database
	 *
	 * Typically, a suffix like "_clusterX" can be used to facilitate clean merging of
	 * read-only storage clusters by simply cloning tables to the new cluster servers.
	 *
	 * @param string $cluster Cluster name
	 * @return string Unqualified table name (e.g. "blobs_cluster32" or default "blobs")
	 * @internal Only for use within ExternalStoreDB and its core maintenance scripts
	 */
	public function getTable( string $cluster ) {
		$lb = $this->getLoadBalancer( $cluster );
		$info = $lb->getServerInfo( ServerInfo::WRITER_INDEX );

		return $info['blobs table'] ?? 'blobs';
	}

	/**
	 * Create the appropriate blobs table on this cluster
	 *
	 * @since 1.34
	 * @param string $cluster
	 */
	public function initializeTable( $cluster ) {
		global $IP;

		static $supportedTypes = [ 'mysql', 'sqlite' ];

		$dbw = $this->getPrimary( $cluster );
		if ( !in_array( $dbw->getType(), $supportedTypes, true ) ) {
			throw new DBUnexpectedError( $dbw, "RDBMS type '{$dbw->getType()}' not supported." );
		}

		$sqlFilePath = "$IP/maintenance/storage/blobs.sql";
		$sql = file_get_contents( $sqlFilePath );
		if ( $sql === false ) {
			throw new RuntimeException( "Failed to read '$sqlFilePath'." );
		}

		$blobsTable = $this->getTable( $cluster );
		$encTable = $dbw->tableName( $blobsTable );
		$sqlWithReplacedVars = str_replace(
			[ '/*$wgDBprefix*/blobs', '/*_*/blobs' ],
			[ $encTable, $encTable ],
			$sql
		);

		$dbw->query(
			new Query(
				$sqlWithReplacedVars,
				$dbw::QUERY_CHANGE_SCHEMA,
				'CREATE',
				$blobsTable,
				$sqlWithReplacedVars
			),
			__METHOD__
		);
	}

	/**
	 * Fetch a blob item out of the database; a cache of the last-loaded
	 * blob will be kept so that multiple loads out of a multi-item blob
	 * can avoid redundant database access and decompression.
	 * @param string $cluster
	 * @param string $id
	 * @param string $itemID
	 * @return HistoryBlob|false Returns false if missing
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
			$this->logger->debug( __METHOD__ . ": cache hit on $cacheID" );

			return $externalBlobCache[$cacheID];
		}

		$this->logger->debug( __METHOD__ . ": cache miss on $cacheID" );

		$blobsTable = $this->getTable( $cluster );

		$dbr = $this->getReplica( $cluster );
		$ret = $dbr->newSelectQueryBuilder()
			->select( 'blob_text' )
			->from( $blobsTable )
			->where( [ 'blob_id' => $id ] )
			->caller( __METHOD__ )->fetchField();

		if ( $ret === false ) {
			// Try the primary DB
			$this->logger->warning( __METHOD__ . ": primary DB fallback on $cacheID" );
			$trxProfiler = $this->lbFactory->getTransactionProfiler();
			$scope = $trxProfiler->silenceForScope( $trxProfiler::EXPECTATION_REPLICAS_ONLY );
			$dbw = $this->getPrimary( $cluster );
			$ret = $dbw->newSelectQueryBuilder()
				->select( 'blob_text' )
				->from( $blobsTable )
				->where( [ 'blob_id' => $id ] )
				->caller( __METHOD__ )->fetchField();
			ScopedCallback::consume( $scope );
			if ( $ret === false ) {
				$this->logger->warning( __METHOD__ . ": primary DB failed to find $cacheID" );
			}
		}
		if ( $itemID !== false && $ret !== false ) {
			// Unserialise object; caller extracts item
			$ret = HistoryBlobUtils::unserialize( $ret );
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
		$blobsTable = $this->getTable( $cluster );

		$dbr = $this->getReplica( $cluster );
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'blob_id', 'blob_text' ] )
			->from( $blobsTable )
			->where( [ 'blob_id' => array_keys( $ids ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$ret = [];
		$this->mergeBatchResult( $ret, $ids, $res );
		if ( $ids ) {
			// Try the primary
			$this->logger->info(
				__METHOD__ . ": primary fallback on '$cluster' for: " .
				implode( ',', array_keys( $ids ) )
			);
			$trxProfiler = $this->lbFactory->getTransactionProfiler();
			$scope = $trxProfiler->silenceForScope( $trxProfiler::EXPECTATION_REPLICAS_ONLY );
			$dbw = $this->getPrimary( $cluster );
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'blob_id', 'blob_text' ] )
				->from( $blobsTable )
				->where( [ 'blob_id' => array_keys( $ids ) ] )
				->caller( __METHOD__ )
				->fetchResultSet();
			ScopedCallback::consume( $scope );
			$this->mergeBatchResult( $ret, $ids, $res );
		}
		if ( $ids ) {
			$this->logger->error(
				__METHOD__ . ": primary on '$cluster' failed locating items: " .
				implode( ',', array_keys( $ids ) )
			);
		}

		return $ret;
	}

	/**
	 * Helper function for self::batchFetchBlobs for merging primary/replica DB results
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
				$ret[$id] = HistoryBlobUtils::unserialize( $row->blob_text );
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

	/**
	 * Get the cluster part of a URL
	 *
	 * @internal for installer
	 * @param string $url
	 * @return string|null
	 */
	public function getClusterForUrl( $url ) {
		$parts = explode( '/', $url );
		return $parts[2] ?? null;
	}

	/**
	 * Get the domain ID for a given cluster, which is false for the local wiki ID
	 *
	 * @internal for installer
	 * @param string $cluster
	 * @return string|false
	 */
	public function getDomainIdForCluster( $cluster ) {
		$lb = $this->getLoadBalancer( $cluster );
		return $this->getDomainId( $lb->getServerInfo( ServerInfo::WRITER_INDEX ) );
	}
}
