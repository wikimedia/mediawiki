<?php
/**
 * Exception representing a failure to look up a row from a name table.
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

namespace MediaWiki\Storage;

use RuntimeException;

/**
 * Exception representing a failure to look up a row from a name table.
 *
 * @newable
 * @since 1.31
 */
class NameTableAccessException extends RuntimeException {

	/**
	 * @param string $tableName
	 * @param string $accessType
	 * @param string|int $accessValue
	 * @return NameTableAccessException
	 */
	public static function newFromDetails( $tableName, $accessType, $accessValue ) {
		$message = "Failed to access name from ${tableName} using ${accessType} = ${accessValue}";
		return new self( $message );
	}

}
