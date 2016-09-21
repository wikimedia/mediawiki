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

/**
 * DB accessable external objects.
 *
 * In this system, each store "location" maps to a database "cluster".
 * The clusters must be defined in the normal LBFactory configuration.
 *
 * @ingroup ExternalStorage
 */
class ExternalStoreDB extends ExternalStoreMedium {
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

	public function store( $location, $data ) {
		$dbw = $this->getMaster( $location );
		$id = $dbw->nextSequenceValue( 'blob_blob_id_seq' );
		$dbw->insert( $this->getTable( $dbw ),
			[ 'blob_id' => $id, 'blob_text' => $data ],
			__METHOD__ );
		$id = $dbw->insertId();
		if ( !$id ) {
			throw new MWException( __METHOD__ . ': no insert ID' );
		}

		return "DB://$location/$id";
	}

	/**
	 * Get a LoadBalancer for the specified cluster
	 *
	 * @param string $cluster Cluster name
	 * @return LoadBalancer
	 */
	function getLoadBalancer( $cluster ) {
		$wiki = isset( $this->params['wiki'] ) ? $this->params['wiki'] : false;

		return wfGetLBFactory()->getExternalLB( $cluster, $wiki );
	}

	/**
	 * Get a replica DB connection for the specified cluster
	 *
	 * @param string $cluster Cluster name
	 * @return IDatabase
	 */
	function getSlave( $cluster ) {
		global $wgDefaultExternalStore;

		$wiki = isset( $this->params['wiki'] ) ? $this->params['wiki'] : false;
		$lb = $this->getLoadBalancer( $cluster );

		if ( !in_array( "DB://" . $cluster, (array)$wgDefaultExternalStore ) ) {
			wfDebug( "read only external store\n" );
			$lb->allowLagged( true );
		} else {
			wfDebug( "writable external store\n" );
		}

		$db = $lb->getConnectionRef( DB_REPLICA, [], $wiki );
		$db->clearFlag( DBO_TRX ); // sanity

		return $db;
	}

	/**
	 * Get a master database connection for the specified cluster
	 *
	 * @param string $cluster Cluster name
	 * @return IDatabase
	 */
	function getMaster( $cluster ) {
		$wiki = isset( $this->params['wiki'] ) ? $this->params['wiki'] : false;
		$lb = $this->getLoadBalancer( $cluster );

		$db = $lb->getConnectionRef( DB_MASTER, [], $wiki );
		$db->clearFlag( DBO_TRX ); // sanity

		return $db;
	}

	/**
	 * Get the 'blobs' table name for this database
	 *
	 * @param IDatabase $db
	 * @return string Table name ('blobs' by default)
	 */
	function getTable( $db ) {
		$table = $db->getLBInfo( 'blobs table' );
		if ( is_null( $table ) ) {
			$table = 'blobs';
		}

		return $table;
	}

	/**
	 * Fetch a blob item out of the database; a cache of the last-loaded
	 * blob will be kept so that multiple loads out of a multi-item blob
	 * can avoid redundant database access and decompression.
	 * @param string $cluster
	 * @param string $id
	 * @param string $itemID
	 * @return HistoryBlob|bool Returns false if missing
	 * @private
	 */
	function fetchBlob( $cluster, $id, $itemID ) {
		/**
		 * One-step cache variable to hold base blobs; operations that
		 * pull multiple revisions may often pull multiple times from
		 * the same blob. By keeping the last-used one open, we avoid
		 * redundant unserialization and decompression overhead.
		 */
		static $externalBlobCache = [];

		$cacheID = ( $itemID === false ) ? "$cluster/$id" : "$cluster/$id/";
		if ( isset( $externalBlobCache[$cacheID] ) ) {
			wfDebugLog( 'ExternalStoreDB-cache',
				"ExternalStoreDB::fetchBlob cache hit on $cacheID" );

			return $externalBlobCache[$cacheID];
		}

		wfDebugLog( 'ExternalStoreDB-cache',
			"ExternalStoreDB::fetchBlob cache miss on $cacheID" );

		$dbr = $this->getSlave( $cluster );
		$ret = $dbr->selectField( $this->getTable( $dbr ),
			'blob_text', [ 'blob_id' => $id ], __METHOD__ );
		if ( $ret === false ) {
			wfDebugLog( 'ExternalStoreDB',
				"ExternalStoreDB::fetchBlob master fallback on $cacheID" );
			// Try the master
			$dbw = $this->getMaster( $cluster );
			$ret = $dbw->selectField( $this->getTable( $dbw ),
				'blob_text', [ 'blob_id' => $id ], __METHOD__ );
			if ( $ret === false ) {
				wfDebugLog( 'ExternalStoreDB',
					"ExternalStoreDB::fetchBlob master failed to find $cacheID" );
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
	function batchFetchBlobs( $cluster, array $ids ) {
		$dbr = $this->getSlave( $cluster );
		$res = $dbr->select( $this->getTable( $dbr ),
			[ 'blob_id', 'blob_text' ], [ 'blob_id' => array_keys( $ids ) ], __METHOD__ );
		$ret = [];
		if ( $res !== false ) {
			$this->mergeBatchResult( $ret, $ids, $res );
		}
		if ( $ids ) {
			wfDebugLog( __CLASS__, __METHOD__ .
				" master fallback on '$cluster' for: " .
				implode( ',', array_keys( $ids ) ) );
			// Try the master
			$dbw = $this->getMaster( $cluster );
			$res = $dbw->select( $this->getTable( $dbr ),
				[ 'blob_id', 'blob_text' ],
				[ 'blob_id' => array_keys( $ids ) ],
				__METHOD__ );
			if ( $res === false ) {
				wfDebugLog( __CLASS__, __METHOD__ . " master failed on '$cluster'" );
			} else {
				$this->mergeBatchResult( $ret, $ids, $res );
			}
		}
		if ( $ids ) {
			wfDebugLog( __CLASS__, __METHOD__ .
				" master on '$cluster' failed locating items: " .
				implode( ',', array_keys( $ids ) ) );
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
			isset( $path[4] ) ? $path[4] : false // itemID
		];
	}
}
