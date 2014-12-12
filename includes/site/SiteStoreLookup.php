<?php

/**
 * Implements SiteLookup interface on top of and using a SiteStore,
 * allowing to get a Site, all sites or an array of sites by global id.
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
 * @since 1.25
 *
 * @file
 *
 * @license GNU GPL v2+
 */
class SiteStoreLookup implements SiteLookup {

	/**
	 * @var SiteStore
	 */
	private $siteStore;

	/**
	 * @var Site[]
	 */
	private $sitesArray = null;

	/**
	 * @param SiteStore $siteStore
	 */
	public function __construct( $siteStore ) {
		$this->siteStore = $siteStore;
	}

	/**
	 * @see SiteLookup::getSites
	 *
	 * @since 1.25
	 *
	 * @param string[]|null $globalIds
	 *
	 * @return Site[]
	 */
	public function getSites( array $globalIds = null ) {
		$sites = $this->getSitesArray();

		if ( $globalIds !== null ) {
			return array_intersect_key( $sites, array_flip( $globalIds ) );
		}

		return $sites;
	}

	/**
	 * @see SiteLookup::getSite
	 *
	 * @since 1.25
	 *
	 * @param string $globalId
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		$sites = $this->getSitesArray();

		return array_key_exists( $globalId, $sites ) ? $sites[$globalId] : null;
	}

	/**
	 * @return Site[]
	 */
	private function getSitesArray() {
		if ( $this->sitesArray === null ) {
			$this->sitesArray = $this->siteStore->getSites()->getArrayCopy();
		}

		return $this->sitesArray;
	}

}
