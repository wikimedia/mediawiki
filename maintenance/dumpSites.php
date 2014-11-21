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
 * Maintenance script to dump the SiteStore as a static json file.
 *
 * @ingroup Maintenance
 */
class DumpSites extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->mDescription = "Dumps site store as json";
		$this->addArg( 'file', 'File to output the json to', false );
	}

	public function execute() {
		$jsonFile = $this->getArg( 0 );

		$siteList = SiteSQLStore::newInstance()->getSites();
		$sites = array();

		foreach( $siteList as $site ) {
			$sites[] = unserialize( $site->serialize() );
		}

		file_put_contents( $jsonFile, json_encode( array( 'sites' => $sites ) ) );
	}

}

$maintClass = "DumpSites";
require_once RUN_MAINTENANCE_IF_MAIN;
