<?php
/**
 * Generate a json file containing an array of
 *   utf8_lowercase => utf8_uppercase
 * for all of the utf-8 range. This provides the input for generateUcfirstOverrides.php
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

require_once __DIR__ . '/../Maintenance.php';

class GenerateUpperCharTable extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Generates the lowercase => uppercase json table' );
		$this->addOption( 'outfile', 'Output file', true, true, 'o' );
	}

	public function execute() {
		$outfile = $this->getOption( 'outfile', 'upperchar.json' );
		$toUpperTable = [];
		for ( $i = 0; $i <= 0x10ffff; $i++ ) {
			// skip all surrogate codepoints or json_encode would fail.
			if ( $i >= 0xd800 && $i <= 0xdfff ) {
				continue;
			}
			$char = UtfNormal\Utils::codepointToUtf8( $i );
			$upper = mb_strtoupper( $char );
			$toUpperTable[$char] = $upper;
		}
		file_put_contents( $outfile, json_encode( $toUpperTable ) );
	}
}

$maintClass = GenerateUpperCharTable::class;
require_once RUN_MAINTENANCE_IF_MAIN;
