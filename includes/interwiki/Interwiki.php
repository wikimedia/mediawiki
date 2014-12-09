<?php
/**
 * Interwiki table entry.
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
use \Cdb\Exception as CdbException;
use \Cdb\Reader as CdbReader;

/**
 * The interwiki class
 * All information is loaded on creation when called by Interwiki::fetch( $prefix ).
 * All work is done on slave, because this should *never* change (except during
 * schema updates etc, which aren't wiki-related)
 */
class Interwiki {
	// Cache - removes oldest entry when it hits limit
	protected static $smCache = array();
	const CACHE_LIMIT = 100; // 0 means unlimited, any other value is max number of entries.

	/** @var string The interwiki prefix, (e.g. "Meatball", or the language prefix "de") */
	protected $mPrefix;

	/** @var string The URL of the wiki, with "$1" as a placeholder for an article name. */
	protected $mURL;

	/** @var string The URL of the file api.php  */
	protected $mAPI;

	/** @var string The name of the database (for a connection to be established
	 *    with wfGetLB( 'wikiid' ))
	 */
	protected $mWikiID;

	/** @var bool Whether the wiki is in this project */
	protected $mLocal;

	/** @var bool Whether interwiki transclusions are allowed */
	protected $mTrans;

	public function __construct( $prefix = null, $url = '', $api = '', $wikiId = '', $local = 0,
		$trans = 0
	) {
		$this->mPrefix = $prefix;
		$this->mURL = $url;
		$this->mAPI = $api;
		$this->mWikiID = $wikiId;
		$this->mLocal = $local;
		$this->mTrans = $trans;
	}

	/**
	 * Check whether an interwiki prefix exists
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public static function isValidInterwiki( $prefix ) {
		$result = self::fetch( $prefix );

		return (bool)$result;
	}

	/**
	 * Fetch an Interwiki object
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	public static function fetch( $prefix ) {
		global $wgContLang;

		if ( $prefix == '' ) {
			return null;
		}

		$prefix = $wgContLang->lc( $prefix );
		if ( isset( self::$smCache[$prefix] ) ) {
			return self::$smCache[$prefix];
		}

		global $wgInterwikiCache;
		if ( $wgInterwikiCache ) {
			$iw = Interwiki::getInterwikiCached( $prefix );
		} else {
			$iw = Interwiki::load( $prefix );
			if ( !$iw ) {
				$iw = false;
			}
		}

		if ( self::CACHE_LIMIT && count( self::$smCache ) >= self::CACHE_LIMIT ) {
			reset( self::$smCache );
			unset( self::$smCache[key( self::$smCache )] );
		}

		self::$smCache[$prefix] = $iw;

		return $iw;
	}

	/**
	 * Fetch interwiki prefix data from local cache in constant database.
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param string $prefix Interwiki prefix
	 * @return Interwiki
	 */
	protected static function getInterwikiCached( $prefix ) {
		$value = self::getInterwikiCacheEntry( $prefix );

		$s = new Interwiki( $prefix );
		if ( $value != '' ) {
			// Split values
			list( $local, $url ) = explode( ' ', $value, 2 );
			$s->mURL = $url;
			$s->mLocal = (int)$local;
		} else {
			$s = false;
		}

		return $s;
	}

	/**
	 * Get entry from interwiki cache
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param string $prefix Database key
	 * @return string The interwiki entry
	 */
	protected static function getInterwikiCacheEntry( $prefix ) {
		global $wgInterwikiCache, $wgInterwikiScopes, $wgInterwikiFallbackSite;
		static $db, $site;

		wfDebug( __METHOD__ . "( $prefix )\n" );
		$value = false;
		try {
			if ( !$db ) {
				$db = CdbReader::open( $wgInterwikiCache );
			}
			/* Resolve site name */
			if ( $wgInterwikiScopes >= 3 && !$site ) {
				$site = $db->get( '__sites:' . wfWikiID() );
				if ( $site == '' ) {
					$site = $wgInterwikiFallbackSite;
				}
			}

			$value = $db->get( wfMemcKey( $prefix ) );
			// Site level
			if ( $value == '' && $wgInterwikiScopes >= 3 ) {
				$value = $db->get( "_{$site}:{$prefix}" );
			}
			// Global Level
			if ( $value == '' && $wgInterwikiScopes >= 2 ) {
				$value = $db->get( "__global:{$prefix}" );
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

	/**
	 * Load the interwiki, trying first memcached then the DB
	 *
	 * @param string $prefix The interwiki prefix
	 * @return Interwiki|bool Interwiki if $prefix is valid, otherwise false
	 */
	protected static function load( $prefix ) {
		global $wgMemc, $wgInterwikiExpiry;

		$iwData = array();
		if ( !Hooks::run( 'InterwikiLoadPrefix', array( $prefix, &$iwData ) ) ) {
			return Interwiki::loadFromArray( $iwData );
		}

		if ( !$iwData ) {
			$key = wfMemcKey( 'interwiki', $prefix );
			$iwData = $wgMemc->get( $key );
			if ( $iwData === '!NONEXISTENT' ) {
				// negative cache hit
				return false;
			}
		}

		// is_array is hack for old keys
		if ( $iwData && is_array( $iwData ) ) {
			$iw = Interwiki::loadFromArray( $iwData );
			if ( $iw ) {
				return $iw;
			}
		}

		$db = wfGetDB( DB_SLAVE );

		$row = $db->fetchRow( $db->select(
			'interwiki',
			self::selectFields(),
			array( 'iw_prefix' => $prefix ),
			__METHOD__
		) );

		$iw = Interwiki::loadFromArray( $row );
		if ( $iw ) {
			$mc = array(
				'iw_url' => $iw->mURL,
				'iw_api' => $iw->mAPI,
				'iw_local' => $iw->mLocal,
				'iw_trans' => $iw->mTrans
			);
			$wgMemc->add( $key, $mc, $wgInterwikiExpiry );

			return $iw;
		}

		// negative cache hit
		$wgMemc->add( $key, '!NONEXISTENT', $wgInterwikiExpiry );

		return false;
	}

	/**
	 * Fill in member variables from an array (e.g. memcached result, Database::fetchRow, etc)
	 *
	 * @param array $mc Associative array: row from the interwiki table
	 * @return Interwiki|bool Interwiki object or false if $mc['iw_url'] is not set
	 */
	protected static function loadFromArray( $mc ) {
		if ( isset( $mc['iw_url'] ) ) {
			$iw = new Interwiki();
			$iw->mURL = $mc['iw_url'];
			$iw->mLocal = isset( $mc['iw_local'] ) ? $mc['iw_local'] : 0;
			$iw->mTrans = isset( $mc['iw_trans'] ) ? $mc['iw_trans'] : 0;
			$iw->mAPI = isset( $mc['iw_api'] ) ? $mc['iw_api'] : '';
			$iw->mWikiID = isset( $mc['iw_wikiid'] ) ? $mc['iw_wikiid'] : '';

			return $iw;
		}

		return false;
	}

	/**
	 * Fetch all interwiki prefixes from interwiki cache
	 *
	 * @param null|string $local If not null, limits output to local/non-local interwikis
	 * @return array List of prefixes
	 * @since 1.19
	 */
	protected static function getAllPrefixesCached( $local ) {
		global $wgInterwikiCache, $wgInterwikiScopes, $wgInterwikiFallbackSite;
		static $db, $site;

		wfDebug( __METHOD__ . "()\n" );
		$data = array();
		try {
			if ( !$db ) {
				$db = CdbReader::open( $wgInterwikiCache );
			}
			/* Resolve site name */
			if ( $wgInterwikiScopes >= 3 && !$site ) {
				$site = $db->get( '__sites:' . wfWikiID() );
				if ( $site == '' ) {
					$site = $wgInterwikiFallbackSite;
				}
			}

			// List of interwiki sources
			$sources = array();
			// Global Level
			if ( $wgInterwikiScopes >= 2 ) {
				$sources[] = '__global';
			}
			// Site level
			if ( $wgInterwikiScopes >= 3 ) {
				$sources[] = '_' . $site;
			}
			$sources[] = wfWikiID();

			foreach ( $sources as $source ) {
				$list = $db->get( "__list:{$source}" );
				foreach ( explode( ' ', $list ) as $iw_prefix ) {
					$row = $db->get( "{$source}:{$iw_prefix}" );
					if ( !$row ) {
						continue;
					}

					list( $iw_local, $iw_url ) = explode( ' ', $row );

					if ( $local !== null && $local != $iw_local ) {
						continue;
					}

					$data[$iw_prefix] = array(
						'iw_prefix' => $iw_prefix,
						'iw_url' => $iw_url,
						'iw_local' => $iw_local,
					);
				}
			}
		} catch ( CdbException $e ) {
			wfDebug( __METHOD__ . ": CdbException caught, error message was "
				. $e->getMessage() );
		}

		ksort( $data );

		return array_values( $data );
	}

	/**
	 * Fetch all interwiki prefixes from DB
	 *
	 * @param string|null $local If not null, limits output to local/non-local interwikis
	 * @return array List of prefixes
	 * @since 1.19
	 */
	protected static function getAllPrefixesDB( $local ) {
		$db = wfGetDB( DB_SLAVE );

		$where = array();

		if ( $local !== null ) {
			if ( $local == 1 ) {
				$where['iw_local'] = 1;
			} elseif ( $local == 0 ) {
				$where['iw_local'] = 0;
			}
		}

		$res = $db->select( 'interwiki',
			self::selectFields(),
			$where, __METHOD__, array( 'ORDER BY' => 'iw_prefix' )
		);

		$retval = array();
		foreach ( $res as $row ) {
			$retval[] = (array)$row;
		}

		return $retval;
	}

	/**
	 * Returns all interwiki prefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return array List of prefixes
	 * @since 1.19
	 */
	public static function getAllPrefixes( $local = null ) {
		global $wgInterwikiCache;

		if ( $wgInterwikiCache ) {
			return self::getAllPrefixesCached( $local );
		}

		return self::getAllPrefixesDB( $local );
	}

	/**
	 * Get the URL for a particular title (or with $1 if no title given)
	 *
	 * @param string $title What text to put for the article name
	 * @return string The URL
	 * @note Prior to 1.19 The getURL with an argument was broken.
	 *       If you if you use this arg in an extension that supports MW earlier
	 *       than 1.19 please wfUrlencode and substitute $1 on your own.
	 */
	public function getURL( $title = null ) {
		$url = $this->mURL;
		if ( $title !== null ) {
			$url = str_replace( "$1", wfUrlencode( $title ), $url );
		}

		return $url;
	}

	/**
	 * Get the API URL for this wiki
	 *
	 * @return string The URL
	 */
	public function getAPI() {
		return $this->mAPI;
	}

	/**
	 * Get the DB name for this wiki
	 *
	 * @return string The DB name
	 */
	public function getWikiID() {
		return $this->mWikiID;
	}

	/**
	 * Is this a local link from a sister project, or is
	 * it something outside, like Google
	 *
	 * @return bool
	 */
	public function isLocal() {
		return $this->mLocal;
	}

	/**
	 * Can pages from this wiki be transcluded?
	 * Still requires $wgEnableScaryTransclusion
	 *
	 * @return bool
	 */
	public function isTranscludable() {
		return $this->mTrans;
	}

	/**
	 * Get the name for the interwiki site
	 *
	 * @return string
	 */
	public function getName() {
		$msg = wfMessage( 'interwiki-name-' . $this->mPrefix )->inContentLanguage();

		return !$msg->exists() ? '' : $msg;
	}

	/**
	 * Get a description for this interwiki
	 *
	 * @return string
	 */
	public function getDescription() {
		$msg = wfMessage( 'interwiki-desc-' . $this->mPrefix )->inContentLanguage();

		return !$msg->exists() ? '' : $msg;
	}

	/**
	 * Return the list of interwiki fields that should be selected to create
	 * a new Interwiki object.
	 * @return string[]
	 */
	public static function selectFields() {
		return array(
			'iw_prefix',
			'iw_url',
			'iw_api',
			'iw_wikiid',
			'iw_local',
			'iw_trans'
		);
	}
}
