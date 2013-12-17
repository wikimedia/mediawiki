<?php

/**
 * Convert a PHP messages file to a set of JSON messages files.
 *
 * Usage:
 *    php generateJSONi18n.php --phpfile=ExtensionName.i18n.php --jsondir=json
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to rebuild the localisation cache.
 *
 * @ingroup Maintenance
 */
class GenerateJSONI18N extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Build JSON messages files from a PHP messages file";
		$this->addOption( 'phpfile', 'PHP file defining a $messages array', true, true );
		$this->addOption( 'jsondir', 'Directory to write JSON files to', true, true );
	}

	public function execute() {
		$phpfile = $this->getOption( 'phpfile' );
		$jsondir = $this->getOption( 'jsondir' );
		include $phpfile;
		foreach ( $messages as $langcode => $langmsgs ) {
			// TODO authorship
			$jsonfile = "$jsondir/$langcode.json";
			file_put_contents(
				$jsonfile,
				json_encode( $langmsgs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
			);
			$this->output( "$jsonfile\n" );
		}
		$this->output( "All done.\n" );
	}
}

$maintClass = "GenerateJSONI18N";
require_once RUN_MAINTENANCE_IF_MAIN;
