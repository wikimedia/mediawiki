<?php
/**
 * Trait for finding SQL patch files.
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
 */

namespace Wikimedia\Rdbms;

use RuntimeException;

/**
 * Trait for finding SQL patch files.
 *
 * @since 1.32
 */
trait PatchFileLocation {

	/**
	 * Utility function for finding the appropriate SQL patch file for the currently
	 * used database type.
	 *
	 * The file will be searched for in the following locations, in order of preference:
	 *   "$patchDir/$name.$dbType.sql",
	 *   "$patchDir/$dbType/archives/$name.sql",
	 *   "$patchDir/$name.sql",
	 *   "$patchDir/archives/$name.sql"
	 *
	 * @param IDatabase $db
	 * @param string $name The script name (relative to $patchDir, without the '.sql' suffix)
	 * @param string $patchDir The directory to find the script in. Use __DIR__ to search in the
	 *        directory the calling code is located in. If omitted, the "maintenance"
	 *        directory will be used, where the scripts used by the updater are located.
	 *
	 * @return string
	 * @throws RuntimeException if no matching patch file could be found.
	 */
	protected function getSqlPatchPath( IDatabase $db, $name, $patchDir = null ) {
		$dbType = $db->getType();

		if ( $patchDir === null ) {
			$patchDir = $GLOBALS['IP'] . '/maintenance/archives';
		}

		$pathes = [
			"$patchDir/$name.$dbType.sql",
			"$patchDir/$dbType/archives/$name.sql",
			"$patchDir/$name.sql",
			"$patchDir/archives/$name.sql"
		];

		foreach ( $pathes as $p ) {
			if ( file_exists( $p ) ) {
				return $p;
			}
		}

		throw new RuntimeException( "No SQL script matching $name could be found in $patchDir" );
	}

}
