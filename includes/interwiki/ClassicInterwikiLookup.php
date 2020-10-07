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

use Cdb\Exception as CdbException;
use Cdb\Reader as CdbReader;
use Interwiki;
use Language;
use MapCacheLRU;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use WANObjectCache;
use WikiMap;
use Wikimedia\Rdbms\Database;

/**
 * InterwikiLookup implementing the "classic" interwiki storage (hardcoded up to MW 1.26).
 *
 * This implements two levels of caching (in-process array and a WANObjectCache)
 * and tree storage backends (SQL, CDB, and plain PHP arrays).
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
	 * @var bool|array|string
	 */
	private $cdbData;

	/**
	 * @var int
	 */
	private $interwikiScopes;

	/**
	 * @var string
	 */
	private $fallbackSite;

	/**
	 * @var CdbReader|null
	 */
	private $cdbReader = null;

	/**
	 * @var string|null
	 */
	private $thisSite = null;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @param Language $contLang Language object used to convert prefixes to lower case
	 * @param WANObjectCache $objectCache Cache for interwiki info retrieved from the database
	 * @param HookContainer $hookContainer
	 * @param int $objectCacheExpiry Expiry time for $objectCache, in seconds
	 * @param bool|array|string $cdbData The path of a CDB file, or
	 *        an array resembling the contents of a CDB file,
	 *        or false to use the database.
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
		$objectCacheExpiry,
		$cdbData,
		$interwikiScopes,
		$fallbackSite
	) {
		$this->localCache = new MapCacheLRU( 100 );

		$this->contLang = $contLang;
		$this->objectCache = $objectCache;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->objectCacheExpiry = $objectCacheExpiry;
		$this->cdbData = $cdbData;
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
		if ( $this->localCache->has( $prefix ) ) {
			return $this->localCache->get( $prefix );
		}

		if ( $this->cdbData ) {
			$iw = $this->getInterwikiCached( $prefix );
		} else {
			$iw = $this->load( $prefix );
			if ( !$iw ) {
				$iw = false;
			}
		}
		$this->localCache->set( $prefix, $iw );

		return $iw;
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
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param string $prefix Interwiki prefix
	 * @return Interwiki|false
	 */
	private function getInterwikiCached( $prefix ) {
		$value = $this->getInterwikiCacheEntry( $prefix );

		if ( $value ) {
			// Split values
			list( $local, $url ) = explode( ' ', $value, 2 );
			return new Interwiki( $prefix, $url, '', '', (int)$local );
		} else {
			return false;
		}
	}

	/**
	 * Get entry from interwiki cache
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param string $prefix Database key
	 * @return bool|string The interwiki entry or false if not found
	 */
	private function getInterwikiCacheEntry( $prefix ) {
		wfDebug( __METHOD__ . "( $prefix )" );

		$wikiId = WikiMap::getCurrentWikiId();

		$value = false;
		try {
			// Resolve site name
			if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
				$this->thisSite = $this->getCacheValue( '__sites:' . $wikiId );
				if ( $this->thisSite == '' ) {
					$this->thisSite = $this->fallbackSite;
				}
			}

			$value = $this->getCacheValue( $wikiId . ':' . $prefix );
			// Site level
			if ( $value == '' && $this->interwikiScopes >= 3 ) {
				$value = $this->getCacheValue( "_{$this->thisSite}:{$prefix}" );
			}
			// Global Level
			if ( $value == '' && $this->interwikiScopes >= 2 ) {
				$value = $this->getCacheValue( "__global:{$prefix}" );
			}
			if ( $value == 'undef' ) {
				$value = '';
			}
		} catch ( CdbException $e ) {
			wfDebug( __METHOD__ . ": CdbException caught, error message was "
				. $e->getMessage() );
		}

		return $value;
	}

	private function getCacheValue( $key ) {
		if ( $this->cdbReader === null ) {
			if ( is_string( $this->cdbData ) ) {
				$this->cdbReader = \Cdb\Reader::open( $this->cdbData );
			} elseif ( is_array( $this->cdbData ) ) {
				$this->cdbReader = new \Cdb\Reader\Hash( $this->cdbData );
			} else {
				$this->cdbReader = false;
			}
		}

		if ( $this->cdbReader ) {
			return $this->cdbReader->get( $key );
		} else {
			return false;
		}
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
				$dbr = wfGetDB( DB_REPLICA ); // TODO: inject LoadBalancer

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
	 * Fetch all interwiki prefixes from interwiki cache
	 *
	 * @param null|string $local If not null, limits output to local/non-local interwikis
	 * @return array List of prefixes, where each row is an associative array
	 */
	private function getAllPrefixesCached( $local ) {
		wfDebug( __METHOD__ . "()" );

		$wikiId = WikiMap::getCurrentWikiId();

		$data = [];
		try {
			/* Resolve site name */
			if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
				$site = $this->getCacheValue( '__sites:' . $wikiId );

				if ( $site == '' ) {
					$this->thisSite = $this->fallbackSite;
				} else {
					$this->thisSite = $site;
				}
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
				$list = $this->getCacheValue( '__list:' . $source );
				foreach ( explode( ' ', $list ) as $iw_prefix ) {
					$row = $this->getCacheValue( "{$source}:{$iw_prefix}" );
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
		} catch ( CdbException $e ) {
			wfDebug( __METHOD__ . ": CdbException caught, error message was "
				. $e->getMessage() );
		}

		return array_values( $data );
	}

	/**
	 * Fetch all interwiki prefixes from DB
	 *
	 * @param string|null $local If not null, limits output to local/non-local interwikis
	 * @return array[] Interwiki rows
	 */
	private function getAllPrefixesDB( $local ) {
		$db = wfGetDB( DB_REPLICA ); // TODO: inject DB LoadBalancer

		$where = [];

		if ( $local !== null ) {
			if ( $local == 1 ) {
				$where['iw_local'] = 1;
			} elseif ( $local == 0 ) {
				$where['iw_local'] = 0;
			}
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
		if ( $this->cdbData ) {
			return $this->getAllPrefixesCached( $local );
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
