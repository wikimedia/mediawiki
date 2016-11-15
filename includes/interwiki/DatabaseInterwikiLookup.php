<?php
namespace MediaWiki\Interwiki;

/**
 * DB InterwikiLookup implementation
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

use Database;
use Hooks;
use Interwiki;
use Language;
use WANObjectCache;

/**
 * InterwikiLookup implementation based on a Database storage and the interwiki table.
 * All work is done on replica DB, because this should *never* change (except during
 * schema updates etc, which aren't wiki-related)
 *
 * This implementation adds a caching layer with a WANObjectCache.
 */
class DatabaseInterwikiLookup extends BaseInterwikiLookup {
	/**
	 * @var WANObjectCache
	 */
	private $objectCache;

	/**
	 * @var int
	 */
	private $objectCacheExpiry;

	/**
	 * Build a new DatabaseInterwikiLookup.
	 * @param Language $language
	 * @param WANObjectCache $objectCache
	 * @param int $objectCacheExpiry
	 */
	public function __construct(
		Language $language,
		WANObjectCache $objectCache,
		$objectCacheExpiry
	) {
		parent::__construct( $language );
		$this->objectCache = $objectCache;
		$this->objectCacheExpiry = $objectCacheExpiry;
	}

	/**
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	protected function internalFetch( $prefix ) {
		$iw = $this->load( $prefix );
		if ( !$iw ) {
			$iw = false;
		}
		return $iw;
	}


	/**
	 * Purge the in-process and object cache for an interwiki prefix
	 * @param string $prefix
	 */
	protected function internalInvalidateCache( $prefix ) {
		$key = $this->objectCache->makeKey( 'interwiki', $prefix );
		$this->objectCache->delete( $key );
	}

	/**
	 * Load the interwiki, trying first memcached then the DB
	 *
	 * @param string $prefix The interwiki prefix
	 * @return Interwiki|bool Interwiki if $prefix is valid, otherwise false
	 */
	private function load( $prefix ) {
		$iwData = [];
		if ( !Hooks::run( 'InterwikiLoadPrefix', [ $prefix, &$iwData ] ) ) {
			return $this->loadFromArray( $iwData );
		}

		if ( is_array( $iwData ) ) {
			$iw = $this->loadFromArray( $iwData );
			if ( $iw ) {
				return $iw; // handled by hook
			}
		}

		$iwData = $this->objectCache->getWithSetCallback(
			$this->objectCache->makeKey( 'interwiki', $prefix ),
			$this->objectCacheExpiry,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $prefix ) {
				$dbr = wfGetDB( DB_REPLICA ); // TODO: inject LoadBalancer

				$setOpts += Database::getCacheSetOptions( $dbr );

				$row = $dbr->selectRow(
					'interwiki',
					self::selectFields(),
					[ 'iw_prefix' => $prefix ],
					__METHOD__
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
			$local = isset( $mc['iw_local'] ) ? $mc['iw_local'] : 0;
			$trans = isset( $mc['iw_trans'] ) ? $mc['iw_trans'] : 0;
			$api = isset( $mc['iw_api'] ) ? $mc['iw_api'] : '';
			$wikiId = isset( $mc['iw_wikiid'] ) ? $mc['iw_wikiid'] : '';

			return new Interwiki( null, $url, $api, $wikiId, $local, $trans );
		}

		return false;
	}

	/**
	 * Fetch all interwiki prefixes from DB
	 *
	 * @param string|null $local If not null, limits output to local/non-local interwikis
	 * @return array[] Interwiki rows
	 */
	public function getAllPrefixes( $local = null ) {
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

