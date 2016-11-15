<?php
namespace MediaWiki\Interwiki;

/**
 * Wikimedia InterwikiLookup implementation based on a precomputed array
 * built by WikimediaMaintenance/dumpInterwiki.php
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

use Interwiki;
use Language;

/**
 * Uses a precomputed hash to lookup interwiki prefixes.
 * This class is tightly coupled with dumpInterwiki.php in WikimediaMaintenance
 * and the format of the backing array is non trivial.
 * The fact that dumpInterwiki.php is highly dependent on WMF context makes
 * this class highly WMF specific as well.
 */
class HashInterwikiLookup extends BaseInterwikiLookup {
	/** @var int */
	private $interwikiScopes;

	/** @var string */
	private $fallbackSite;

	/** @var string|null */
	private $thisSite = null;

	/** @var array[] */
	private $data;

	/**
	 * @param Language $language
	 * @param array[] $data an array resembling the contents of a CDB file,
	 *        or false to use the database.
	 * @param int $interwikiScopes Specify number of domains to check for messages:
	 *    - 1: Just local wiki level
	 *    - 2: wiki and global levels
	 *    - 3: site level as well as wiki and global levels
	 * @param string $fallbackSite The code to assume for the local site,
	 */
	function __construct(
		Language $language,
		array $data,
		$interwikiScopes,
		$fallbackSite
	) {
		parent::__construct( $language );
		$this->data = $data;
		$this->interwikiScopes = $interwikiScopes;
		$this->fallbackSite = $fallbackSite;
	}

	/**
	 * Fetch all interwiki prefixes from interwiki cache
	 *
	 * @param null|string $local If not null, limits output to local/non-local interwikis
	 * @return array List of prefixes, where each row is an associative array
	 */
	public function getAllPrefixes( $local = null ) {
		wfDebug( __METHOD__ . "()\n" );
		$data = [];
		/* Resolve site name */
		if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
			$site = $this->getCacheValue( '__sites:' . wfWikiID() );

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
		$sources[] = wfWikiID();

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

		ksort( $data );

		return array_values( $data );
	}

	/**
	 * Get entry from interwiki cache
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param string $prefix Database key
	 * @return bool|string The interwiki entry or false if not found
	 */
	protected function internalFetch( $prefix ) {
		wfDebug( __METHOD__ . "( $prefix )\n" );
		$value = false;
		// Resolve site name
		if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
			$this->thisSite = $this->getCacheValue( '__sites:' . wfWikiID() );
			if ( $this->thisSite == '' ) {
				$this->thisSite = $this->fallbackSite;
			}
		}

		$value = $this->getCacheValue( wfMemcKey( $prefix ) );
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

		if ( $value ) {
			list( $local, $url ) = explode( ' ', $value, 2 );
			return new Interwiki( $prefix, $url, '', '', (int)$local );
		}
		return false;
	}

	/**
	 * @param string $prefix
	 */
	protected function internalInvalidateCache( $prefix ) {}

	/**
	 * NOTE: protected only to allow specialization by CdbInterwikiLookup
	 * @param string $key
	 * @return string
	 */
	protected function getCacheValue( $key ) {
		return isset( $this->data[$key] ) ? $this->data[$key] : false;
	}
}
