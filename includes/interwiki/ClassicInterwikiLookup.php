<?php
/**
 * InterwikiLookup implementing the "classic" interwiki storage (hardcoded up to MW 1.26).
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

namespace MediaWiki\Interwiki;

use Interwiki;
use Language;
use MapCacheLRU;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MWException;
use WANObjectCache;
use WikiMap;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * InterwikiLookup implementing the "classic" interwiki storage (hardcoded up to MW 1.26).
 *
 * This implements two levels of caching (in-process array and a WANObjectCache)
 * and two storage backends (SQL and plain PHP arrays).
 *
 * All information is loaded on creation when called by $this->fetch( $prefix ).
 * All work is done on replica DB, because this should *never* change (except during
 * schema updates etc, which aren't wiki-related)
 *
 * @since 1.28
 */
class ClassicInterwikiLookup implements InterwikiLookup {

	/**
	 * @var MapCacheLRU
	 */
	private $localCache;

	/**
	 * @var Language
	 */
	private $contLang;

	/**
	 * @var WANObjectCache
	 */
	private $objectCache;

	/**
	 * @var int
	 */
	private $objectCacheExpiry;

	/**
	 * @var array|null Complete pregenerated data if available
	 */
	private $data;

	/**
	 * @var int
	 */
	private $interwikiScopes;

	/**
	 * @var string
	 */
	private $fallbackSite;

	/**
	 * @var string|null
	 */
	private $thisSite = null;

	/** @var HookRunner */
	private $hookRunner;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/**
	 * @param Language $contLang Language object used to convert prefixes to lower case
	 * @param WANObjectCache $objectCache Cache for interwiki info retrieved from the database
	 * @param HookContainer $hookContainer
	 * @param ILoadBalancer $loadBalancer
	 * @param int $objectCacheExpiry Expiry time for $objectCache, in seconds
	 * @param bool|array $interwikiData The pre-generated interwiki data, or
	 *   false to use the database.
	 * @param int $interwikiScopes Specify number of domains to check for messages:
	 *    - 1: Just local wiki level
	 *    - 2: wiki and global levels
	 *    - 3: site level as well as wiki and global levels
	 * @param string $fallbackSite The code to assume for the local site,
	 */
	public function __construct(
		Language $contLang,
		WANObjectCache $objectCache,
		HookContainer $hookContainer,
		ILoadBalancer $loadBalancer,
		$objectCacheExpiry,
		$interwikiData,
		$interwikiScopes,
		$fallbackSite
	) {
		$this->localCache = new MapCacheLRU( 1000 );

		$this->contLang = $contLang;
		$this->objectCache = $objectCache;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->loadBalancer = $loadBalancer;
		$this->objectCacheExpiry = $objectCacheExpiry;
		if ( is_array( $interwikiData ) ) {
			$this->data = $interwikiData;
		} elseif ( $interwikiData ) {
			throw new MWException(
				'Setting $wgInterwikiCache to a CDB path is no longer supported' );
		}
		$this->interwikiScopes = $interwikiScopes;
		$this->fallbackSite = $fallbackSite;
	}

	/**
	 * Check whether an interwiki prefix exists
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public function isValidInterwiki( $prefix ) {
		$result = $this->fetch( $prefix );

		return (bool)$result;
	}

	/**
	 * Fetch an Interwiki object
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	public function fetch( $prefix ) {
		if ( $prefix == '' ) {
			return null;
		}

		$prefix = $this->contLang->lc( $prefix );

		return $this->localCache->getWithSetCallback(
			$prefix,
			function () use ( $prefix ) {
				if ( $this->data !== null ) {
					$iw = $this->fetchPregenerated( $prefix );
				} else {
					$iw = $this->load( $prefix );
					if ( !$iw ) {
						$iw = false;
					}
				}
				return $iw;
			}
		);
	}

	/**
	 * Resets locally cached Interwiki objects. This is intended for use during testing only.
	 * This does not invalidate entries in the persistent cache, as invalidateCache() does.
	 * @since 1.27
	 */
	public function resetLocalCache() {
		$this->localCache->clear();
	}

	/**
	 * Purge the in-process and object cache for an interwiki prefix
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix ) {
		$this->localCache->clear( $prefix );

		$key = $this->objectCache->makeKey( 'interwiki', $prefix );
		$this->objectCache->delete( $key );
	}

	/**
	 * Fetch interwiki prefix data from local cache in constant database.
	 *
	 * @note More logic is explained in docs/Configuration.md.
	 *
	 * @param string $prefix Interwiki prefix
	 * @return Interwiki|false
	 */
	private function fetchPregenerated( $prefix ) {
		$value = $this->getPregeneratedEntry( $prefix );

		if ( $value ) {
			// Split values
			list( $local, $url ) = explode( ' ', $value, 2 );
			return new Interwiki( $prefix, $url, '', '', (int)$local );
		} else {
			return false;
		}
	}

	/**
	 * Get entry from pregenerated data
	 *
	 * @note More logic is explained in docs/Configuration.md.
	 *
	 * @param string $prefix Database key
	 * @return bool|string The interwiki entry or false if not found
	 */
	private function getPregeneratedEntry( $prefix ) {
		wfDebug( __METHOD__ . "( $prefix )" );

		$wikiId = WikiMap::getCurrentWikiId();

		// Resolve site name
		if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
			$this->thisSite = $this->data['__sites:' . $wikiId] ?? $this->fallbackSite;
		}

		$value = $this->data[$wikiId . ':' . $prefix] ?? false;
		// Site level
		if ( $value === false && $this->interwikiScopes >= 3 ) {
			$value = $this->data["_{$this->thisSite}:{$prefix}"] ?? false;
		}
		// Global Level
		if ( $value === false && $this->interwikiScopes >= 2 ) {
			$value = $this->data["__global:{$prefix}"] ?? false;
		}

		return $value;
	}

	/**
	 * Load the interwiki, trying first memcached then the DB
	 *
	 * @param string $prefix The interwiki prefix
	 * @return Interwiki|bool Interwiki if $prefix is valid, otherwise false
	 */
	private function load( $prefix ) {
		$iwData = [];
		if ( !$this->hookRunner->onInterwikiLoadPrefix( $prefix, $iwData ) ) {
			return $this->loadFromArray( $iwData );
		}

		if ( is_array( $iwData ) ) {
			$iw = $this->loadFromArray( $iwData );
			if ( $iw ) {
				return $iw; // handled by hook
			}
		}

		$fname = __METHOD__;
		$iwData = $this->objectCache->getWithSetCallback(
			$this->objectCache->makeKey( 'interwiki', $prefix ),
			$this->objectCacheExpiry,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $prefix, $fname ) {
				$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

				$setOpts += Database::getCacheSetOptions( $dbr );

				$row = $dbr->selectRow(
					'interwiki',
					self::selectFields(),
					[ 'iw_prefix' => $prefix ],
					$fname
				);

				return $row ? (array)$row : '!NONEXISTENT';
			}
		);

		if ( is_array( $iwData ) ) {
			return $this->loadFromArray( $iwData ) ?: false;
		}

		return false;
	}

	/**
	 * Fill in member variables from an array (e.g. memcached result, Database::fetchRow, etc)
	 *
	 * @param array $mc Associative array: row from the interwiki table
	 * @return Interwiki|bool Interwiki object or false if $mc['iw_url'] is not set
	 */
	private function loadFromArray( $mc ) {
		if ( isset( $mc['iw_url'] ) ) {
			$url = $mc['iw_url'];
			$local = $mc['iw_local'] ?? 0;
			$trans = $mc['iw_trans'] ?? 0;
			$api = $mc['iw_api'] ?? '';
			$wikiId = $mc['iw_wikiid'] ?? '';

			return new Interwiki( null, $url, $api, $wikiId, $local, $trans );
		}

		return false;
	}

	/**
	 * Fetch all interwiki prefixes from pregenerated data
	 *
	 * @param null|string $local If not null, limits output to local/non-local interwikis
	 * @return array List of prefixes, where each row is an associative array
	 */
	private function getAllPrefixesPregenerated( $local ) {
		wfDebug( __METHOD__ . "()" );

		$wikiId = WikiMap::getCurrentWikiId();

		$data = [];
		/* Resolve site name */
		if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
			$this->thisSite = $this->data['__sites:' . $wikiId] ?? $this->fallbackSite;
		}

		// List of interwiki sources
		$sources = [];
		// Global Level
		if ( $this->interwikiScopes >= 2 ) {
			$sources[] = '__global';
		}
		// Site level
		if ( $this->interwikiScopes >= 3 ) {
			$sources[] = '_' . $this->thisSite;
		}
		$sources[] = $wikiId;

		foreach ( $sources as $source ) {
			$list = $this->data['__list:' . $source] ?? '';
			foreach ( explode( ' ', $list ) as $iw_prefix ) {
				$row = $this->data["{$source}:{$iw_prefix}"] ?? null;
				if ( !$row ) {
					continue;
				}

				list( $iw_local, $iw_url ) = explode( ' ', $row );

				if ( $local !== null && $local != $iw_local ) {
					continue;
				}

				$data[$iw_prefix] = [
					'iw_prefix' => $iw_prefix,
					'iw_url' => $iw_url,
					'iw_local' => $iw_local,
				];
			}
		}

		return array_values( $data );
	}

	/**
	 * Given the array returned by getAllPrefixes(), build a PHP hash which
	 * can be given to self::__construct() as $interwikiData, i.e. as the
	 * value of $wgInterwikiCache.  This is used to construct mock
	 * interwiki lookup services for testing (in particular, parsertests).
	 * @param array $allPrefixes An array of interwiki information such as
	 *   would be returned by ::getAllPrefixes()
	 * @param int $scope The scope at which to insert interwiki prefixes.
	 *   See the $interwikiScopes parameter to ::__construct().
	 * @param ?string $thisSite The value of $thisSite, if $scope is 3.
	 * @return array A PHP associative array suitable to use as
	 *   $wgInterwikiCache
	 */
	public static function buildCdbHash(
		array $allPrefixes, int $scope = 1, ?string $thisSite = null
	): array {
		$result = [];
		$wikiId = WikiMap::getCurrentWikiId();
		$keyPrefix = ( $scope >= 2 ) ? '__global' : $wikiId;
		if ( $scope >= 3 && $thisSite ) {
			$result[ "__sites:$wikiId" ] = $thisSite;
			$keyPrefix = "_$thisSite";
		}
		$list = [];
		foreach ( $allPrefixes as $iwInfo ) {
			$prefix = $iwInfo['iw_prefix'];
			$result["$keyPrefix:$prefix"] = implode( ' ', [
				$iwInfo['iw_local'] ?? 0, $iwInfo['iw_url']
			] );
			$list[] = $prefix;
		}
		$result["__list:$keyPrefix"]  = implode( ' ', $list );
		$result["__list:__sites"] = $wikiId;
		return $result;
	}

	/**
	 * Fetch all interwiki prefixes from DB
	 *
	 * @param bool|null $local If not null, limits output to local/non-local interwikis
	 * @return array[] Interwiki rows
	 */
	private function getAllPrefixesDB( $local ) {
		$db = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$where = [];

		if ( $local !== null ) {
			$where['iw_local'] = (int)$local;
		}

		$res = $db->select( 'interwiki',
			self::selectFields(),
			$where, __METHOD__, [ 'ORDER BY' => 'iw_prefix' ]
		);

		$retval = [];
		foreach ( $res as $row ) {
			$retval[] = (array)$row;
		}

		return $retval;
	}

	/**
	 * Returns all interwiki prefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return array[] Interwiki rows, where each row is an associative array
	 */
	public function getAllPrefixes( $local = null ) {
		if ( $this->data !== null ) {
			return $this->getAllPrefixesPregenerated( $local );
		}

		return $this->getAllPrefixesDB( $local );
	}

	/**
	 * Return the list of interwiki fields that should be selected to create
	 * a new Interwiki object.
	 * @return string[]
	 */
	private static function selectFields() {
		return [
			'iw_prefix',
			'iw_url',
			'iw_api',
			'iw_wikiid',
			'iw_local',
			'iw_trans'
		];
	}

}
