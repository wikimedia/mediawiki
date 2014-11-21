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
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
class SiteStoreFactory {

	/**
	 * @var string
	 */
	private $cacheFile;

	/**
	 * @var boolean
	 */
	private $manualRecache;

	/**
	 * @var SiteStore
	 */
	private $siteStore;

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->cacheFile = $config->get( 'SitesCacheFile' );
		$this->manualRecache = $config->get( 'SitesCacheManualRecache' );
	}

	/**
	 * @return SiteStore
	 */
	public function getSiteStore() {
		if ( !isset( $this->siteStore ) ) {
			$this->siteStore = $this->newSiteStore();
		}

		return $this->siteStore;
	}

	/**
	 * @return SiteStore
	 */
	private function newSiteStore() {
		$siteSQLStore = SiteSQLStore::newInstance();

		if ( $this->cacheFile !== false ) {
			return new CachingFileSiteStore(
				$siteSQLStore,
				$this->cacheFile,
				$this->manualRecache
			);
		}

		return $siteSQLStore;
	}

	/**
	 * @var SiteStoreFactory
	 */
	private static $instance;

	/**
	 * @return SiteStoreFactory
	 */
	public static function getDefaultInstance() {
		if ( !isset( self::$instance ) ) {
			$config = RequestContext::getMain()->getConfig();
			self::$instance = new self( $config );
		}

		return self::$instance;
	}

}
