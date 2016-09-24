<?php
namespace MediaWiki\Interwiki;

/**
 * InterwikiLookupAdapter on top of SiteLookup
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
 *
 * @since 1.28
 * @ingroup InterwikiLookup
 *
 * @license GNU GPL v2+
 */

use Interwiki;
use SiteLookup;

class InterwikiLookupAdapter implements InterwikiLookup {

	/**
	 * @var SiteLookup
	 */
	private $siteLookup;

	/**
	 * @var array
	 */
	protected $interwikiMap;

	function __construct(
		SiteLookup $siteLookup,
		array $interwikiMap = null
	) {
		$this->siteLookup = $siteLookup;
		if ( $interwikiMap === null ) {
			$interwikiMap = self::loadInterwikiMap( $siteLookup );
		}
		$this->interwikiMap = $interwikiMap;
	}

	/**
	 * See InterwikiLookup::isValidInterwiki
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public function isValidInterwiki($prefix) {
		return array_key_exists( $this->interwikiMap[$prefix] );
	}

	/**
	 * See InterwikiLookup::Fetch
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	public function fetch( $prefix ) {
		if ( $this->isValidInterwiki( $prefix ) === false ) {
			return null;
		}
		return $this->interwikiMap[$prefix];
	}

	/**
	 * See InterwikiLookup::getAllPrefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return string[] List of prefixes
	 */
	public function getAllPrefixes( $local = null ) {
		if ( $local === null ) {
			return array_keys( $this->interwikiMap );
		}
		$res = [];
		foreach ( $this->interwikiMap as $interwiki => $site ) {
			if ( $site->getSource() === $local ) {
				$res[] = $interwiki;
			}
		}
		return $res;
	}

	/**
	 * See InterwikiLookup::invalidateCache
	 *
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix ) {
		unset( $this->interwikiMap[$prefix] );
	}

	/**
	 * Load interwiki map to use as cache
	 *
	 * @param SiteLookup $siteLookup
	 * @return Interwiki[]
	 */
	public static function loadInterwikiMap( SiteLookup $siteLookup ) {
		$interwikiMap = [];
		$siteList = $siteLookup->getSites();
		$globals = $siteList->getGlobalIdentifiers();
		foreach ( $globals as $global ) {
			$site = $siteList->getSite( $global );
			foreach ( $site->getInterwikiIds() as $interwiki ) {
				$interwikiMap[$interwiki] = $site;
			}
		}
		return $interwikiMap;
	}
}
