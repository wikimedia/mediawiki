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
		$siteList = $this->siteStore->getSites();

		$sites = array();

		if ( $globalIds !== null ) {
			foreach( $globalIds as $globalId ) {
				if ( $siteList->hasSite( $globalId ) ) {
					$sites[$globalId] = $siteList->getSite( $globalId );
				}
			}
		} else {
			foreach( $siteList as $site ) {
				$globalId = $site->getGlobalId();
				$sites[$globalId] = $site;
			}
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
	 * @throws OutOfBoundsException
	 * @return Site
	 */
	public function getSite( $globalId ) {
		$site = $this->siteStore->getSite( $globalId );

		if ( $site === null ) {
			throw new OutOfBoundsException( 'Site with global id: ' . $globalId . ' not found.' );
		}

		return $site;
	}

}
