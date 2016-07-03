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
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to dump a SiteStore as a static json file.
 *
 * @ingroup Maintenance
 */
class RebuildSitesCache extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Cache sites as json for file-based lookup.' );
		$this->addOption( 'file', 'File to output the json to', false, true );
	}

	public function execute() {
		$sitesCacheFileBuilder = new SitesCacheFileBuilder(
			\MediaWiki\MediaWikiServices::getInstance()->getSiteLookup(),
			$this->getCacheFile()
		);

		$sitesCacheFileBuilder->build();
	}

	/**
	 * @return string
	 */
	private function getCacheFile() {
		if ( $this->hasOption( 'file' ) ) {
			$jsonFile = $this->getOption( 'file' );
		} else {
			$jsonFile = $this->getConfig()->get( 'SitesCacheFile' );

			if ( $jsonFile === false ) {
				$this->error( 'Error: No file set in configuration for SitesCacheFile.', 1 );
			}
		}

		return $jsonFile;
	}

}

$maintClass = "RebuildSitesCache";
require_once RUN_MAINTENANCE_IF_MAIN;
