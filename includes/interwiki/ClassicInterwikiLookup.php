<?php
namespace MediaWiki\Interwiki;

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
use Language;
use WANObjectCache;

/**
 * InterwikiLookup implementing the "classic" interwiki storage (hardcoded up to MW 1.26).
 *
 * This class is a wrapper to the following InterwikiLookup implementations:
 * - DatabaseInterwikiLookup
 * - HashInterwikiLookup
 * - CdbInterwikiLookup
 *
 * The wrapper does nothing except deciding which implementation to use (based
 * on the config state, see getPreferredImpl()).
 *
 * @deprecated Use the InterwikiLookup service from MediaWikiServices instead
 *
 * @see DatabaseInterwikiLookup
 * @see HashInterwikiLookup
 * @see CdbInterwikiLookup
 * @see BaseInterwikiLookup
 * @since 1.28
 */
class ClassicInterwikiLookup implements InterwikiLookup {
	/**
	 * @param Language $contentLanguage Language object used to convert prefixes to lower case
	 * @param WANObjectCache $objectCache Cache for interwiki info
	 *        retrieved from the database
	 * @param int $objectCacheExpiry Expiry time for $objectCache, in seconds
	 * @param string $fallbackSite The code to assume for the local site,
	 * @param bool|array|string $cdbData The path of a CDB file, or
	 *        an array resembling the contents of a CDB file,
	 *        or false to use the database.
	 * @param int $interwikiScopes Specify number of domains to check for messages:
	 *    - 1: Just local wiki level
	 *    - 2: wiki and global levels
	 *    - 3: site level as well as wiki and global levels
	 * @deprecated Use MediaWikiServices instead
	 * @see \MediaWiki\MediaWikiServices::getInterwikiLookup()
	 */
	function __construct(
		Language $contentLanguage,
		WANObjectCache $objectCache,
		$objectCacheExpiry,
		$cdbData,
		$interwikiScopes,
		$fallbackSite
	) {
		$this->wrapped = self::getPreferredImpl(
			$contentLanguage,
			$objectCache,
			$objectCacheExpiry,
			$cdbData,
			$interwikiScopes,
			$fallbackSite
		);
	}

	/**
	 * Decide which InterwikiLookup implementation to use.
	 * - if $cdbData is a string: CdbInterwikiLookup
	 * - if $cdbData is an array: HashInterwikiLookup
	 * - everything else: DatabaseInterwikiLookup
	 *
	 * @param Language $language Language object used to convert prefixes to lower case
	 * @param WANObjectCache $objectCache Cache for interwiki info
	 *        retrieved from the database
	 * @param int $objectCacheExpiry Expiry time for $objectCache, in seconds
	 * @param bool|array|string $cdbData The path of a CDB file, or
	 *        an array resembling the contents of a CDB file,
	 *        or false to use the database.
	 * @param int $interwikiScopes Specify number of domains to check for messages:
	 *    - 1: Just local wiki level
	 *    - 2: wiki and global levels
	 *    - 3: site level as well as wiki and global levels
	 * @param string $fallbackSite The code to assume for the local site,
	 * @return InterwikiLookup
	 */
	private static function getPreferredImpl(
		Language $language,
		WANObjectCache $objectCache,
		$objectCacheExpiry,
		$cdbData,
		$interwikiScopes,
		$fallbackSite
	) {
		if ( is_array( $cdbData ) ) {
			return new HashInterwikiLookup(
				$language,
				$cdbData,
				$interwikiScopes,
				$fallbackSite
			);
		} else if( is_string( $cdbData ) ) {
			return new CdbInterwikiLookup(
				$language,
				$cdbData,
				$interwikiScopes,
				$fallbackSite
			);
		} else {
			return new DatabaseInterwikiLookup(
				$language,
				$objectCache,
				$objectCacheExpiry
			);
		}
	}

	/**
	 * Check whether an interwiki prefix exists
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public function isValidInterwiki( $prefix ) {
		$this->wrapped->isValidInterwiki( $prefix );
	}

	/**
	 * Fetch an Interwiki object
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	public function fetch( $prefix ) {
		return $this->wrapped->fetch( $prefix );
	}

	/**
	 * Returns all interwiki prefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return string[] List of prefixes
	 */
	public function getAllPrefixes( $local = null ) {
		return $this->wrapped->getAllPrefixes( $local );
	}

	/**
	 * Purge the in-process and persistent object cache for an interwiki prefix
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix ) {
		$this->wrapped->getAllPrefixes( $local );
	}

	/**
	 * @return InterwikiLookup the wrapped InterwikiLookup
	 */
	public function getWrapped() {
		return $this->wrapped;
	}
}
