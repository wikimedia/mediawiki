<?php
/**
 * Dumb program that tries to get the memory usage for each language file.
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
 * @ingroup MaintenanceLanguage
 */

/** This is a command line script */
require_once __DIR__ . '/../Maintenance.php';
require_once __DIR__ . '/languages.inc';

/**
 * Maintenance script that tries to get the memory usage for each language file.
 *
 * @ingroup MaintenanceLanguage
 */
class LangMemUsage extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( "Dumb program that tries to get the memory usage\n" .
			"for each language file" );
	}

	public function execute() {
		if ( !function_exists( 'memory_get_usage' ) ) {
			$this->error( "You must compile PHP with --enable-memory-limit", true );
		}

		$langtool = new Languages();
		$memlast = $memstart = memory_get_usage();

		$this->output( "Base memory usage: $memstart\n" );

		foreach ( $langtool->getLanguages() as $langcode ) {
			Language::factory( $langcode );
			$memstep = memory_get_usage();
			$this->output( sprintf( "%12s: %d\n", $langcode, ( $memstep - $memlast ) ) );
			$memlast = $memstep;
		}

		$memend = memory_get_usage();

		$this->output( ' Total Usage: ' . ( $memend - $memstart ) . "\n" );
	}
}

$maintClass = "LangMemUsage";
require_once RUN_MAINTENANCE_IF_MAIN;
