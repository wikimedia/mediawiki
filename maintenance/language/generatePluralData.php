<?php
/**
 * Generates pluralizations data files
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
 * Generates plural data files.
 *
 * @ingroup MaintenanceLanguage
 */
class GeneratePluralData extends Maintenance {
	public $pluralRules = array();
	public $pluralRuleTypes = array();

	public function __construct() {
		parent::__construct();
		$this->mDescription = "(Re)Generate plural files from CLDR and plurals-mediawiki.xml";
		$this->addOption( "regen", "Regnerate the file even if it already exists" );
	}

	public function execute() {
		global $IP;

		$cache = new LocalisationCache( array( 'store' => 'file' ) );
		$ret = $cache->savePluralFiles( $this->hasOption( "regen" ) ? "regenerate" : null );
		if ( $ret === true ) {
			$this->output( "Successfully generated plurals.ser!\n" );
		} else {
			$this->error( $ret );
			exit(1);
		}
	}
}

$maintClass = 'GeneratePluralData';
require_once RUN_MAINTENANCE_IF_MAIN;
