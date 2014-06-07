<?php
/**
 * Generic operation result for FileRepo-related operations.
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
 * @ingroup FileRepo
 */

/**
 * Generic operation result class for FileRepo-related operations
 * @ingroup FileRepo
 */
class FileRepoStatus extends Status {
	/**
	 * Factory function for fatal errors
	 *
	 * @param FileRepo $repo
	 * @return FileRepoStatus
	 */
	static function newFatal( $repo /*, parameters...*/ ) {
		$params = array_slice( func_get_args(), 1 );
		$result = new self( $repo );
		call_user_func_array( array( &$result, 'error' ), $params );
		$result->ok = false;

		return $result;
	}

	/**
	 * @param FileRepo|bool $repo Default: false
	 * @param $value
	 * @return FileRepoStatus
	 */
	static function newGood( $repo = false, $value = null ) {
		$result = new self( $repo );
		$result->value = $value;

		return $result;
	}

	/**
	 * @param bool|FileRepo $repo
	 */
	function __construct( $repo = false ) {
		if ( $repo ) {
			$this->cleanCallback = $repo->getErrorCleanupFunction();
		}
	}
}
