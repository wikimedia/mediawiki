<?php

/**
 * Get a SiteLookup instance, based on configuration, with in-process caching.
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
 * @ingroup Site
 *
 * @license GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SiteLookupFactory {

	/**
	 * @var string|bool
	 */
	private $cacheFile;

	/**
	 * @var SiteLookup|null
	 */
	private $siteLookup = null;

	/**
	 * @var SiteLookupFactory|null
	 */
	private static $instance = null;

	/**
	 * @return SiteLookupFactory
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
			$config = RequestContext::getMain()->getConfig();
			self::$instance = new self( $config->get( 'SitesCacheFile' ) );
		}

		return self::$instance;
	}

	/**
	 * @param string|bool $cacheFile
	 */
	public function __construct( $cacheFile ) {
		if ( !is_string( $cacheFile ) && $cacheFile !== false ) {
			throw new MWException( '$sitesCacheFile must be a string, file path to the'
				. ' cache file or false to disable sites file cache.' );
		}

		$this->cacheFile = $cacheFile;
	}

	public function getSiteLookup() {
		if ( $this->siteLookup === null ) {
			$this->siteLookup = $this->newSiteLookup();
		}

		return $this->siteLookup;
	}

	private function newSiteLookup() {
		if ( is_string( $this->cacheFile ) ) {
			return new SiteFileCacheLookup( $this->cacheFile );
		}

		return new SiteStoreLookup( SiteSQLStore::newInstance() );
	}

}
