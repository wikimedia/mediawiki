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
 * @license GPL-2.0-or-later
 */
class SitesCacheFileBuilder {

	/**
	 * @var SiteLookup
	 */
	private $siteLookup;

	/**
	 * @var string
	 */
	private $cacheFile;

	/**
	 * @param SiteLookup $siteLookup
	 * @param string $cacheFile
	 */
	public function __construct( SiteLookup $siteLookup, $cacheFile ) {
		$this->siteLookup = $siteLookup;
		$this->cacheFile = $cacheFile;
	}

	public function build() {
		$this->sites = $this->siteLookup->getSites();
		$this->cacheSites( $this->sites->getArrayCopy() );
	}

	/**
	 * @param Site[] $sites
	 *
	 * @throws MWException if in manualRecache mode
	 * @return bool
	 */
	private function cacheSites( array $sites ) {
		$sitesArray = [];

		foreach ( $sites as $site ) {
			$globalId = $site->getGlobalId();
			$sitesArray[$globalId] = $this->getSiteAsArray( $site );
		}

		$json = json_encode( [
			'sites' => $sitesArray
		] );

		$result = file_put_contents( $this->cacheFile, $json );

		return $result !== false;
	}

	/**
	 * @param Site $site
	 *
	 * @return array
	 */
	private function getSiteAsArray( Site $site ) {
		$siteEntry = unserialize( $site->serialize() );
		$siteIdentifiers = $this->buildLocalIdentifiers( $site );
		$identifiersArray = [];

		foreach ( $siteIdentifiers as $identifier ) {
			$identifiersArray[] = $identifier;
		}

		$siteEntry['identifiers'] = $identifiersArray;

		return $siteEntry;
	}

	/**
	 * @param Site $site
	 *
	 * @return array Site local identifiers
	 */
	private function buildLocalIdentifiers( Site $site ) {
		$localIds = [];

		foreach ( $site->getLocalIds() as $idType => $ids ) {
			foreach ( $ids as $id ) {
				$localIds[] = [
					'type' => $idType,
					'key' => $id
				];
			}
		}

		return $localIds;
	}

}
