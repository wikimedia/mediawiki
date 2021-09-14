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
 * Maintenance script for finding the files that contain classes
 *
 * @ingroup Maintenance
 * @since 1.37
 */
class FindClasses extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Finds the files containing classes via the autoloader.' );
	}

	public function execute() {
		$input = file( 'php://stdin' );

		foreach ( $input as $line ) {
			$class = trim( $line );
			$filename = AutoLoader::find( $class );
			if ( $filename ) {
				print "$filename\n";
			} elseif ( $class ) {
				print "#$class\n";
			}
		}
	}
}

$maintClass = FindClasses::class;
require_once RUN_MAINTENANCE_IF_MAIN;
