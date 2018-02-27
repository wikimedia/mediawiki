<?php
/**
 * Generates the normalizer data file for Malayalam.
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

/**
 * Generates the normalizer data file for Malayalam.
 *
 * This data file is used after normalizing to NFC.
 *
 * @ingroup MaintenanceLanguage
 */
class GenerateLanguageMetaData extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Generate meta data for languages for cheap lookup.' );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		$isRTL = [];

		$langs = Language::fetchLanguageNames();
		foreach( $langs as $langCode => $langName ) {
			$lang = Language::factory( $langCode );
			echo "Processing $langCode\n";
			if ( $lang->isRTL() ) {
				print "* is RTL\n";
				$isRTL[] = $langCode;
			}
		}

		global $IP;
		$constants = [
			'isRTL' => $isRTL,
		];
		file_put_contents( "$IP/languages/metadata.php", serialize( $constants ) );
		echo "Generated language metadata\n";
	}
}

$maintClass = GenerateLanguageMetaData::class;
require_once RUN_MAINTENANCE_IF_MAIN;
