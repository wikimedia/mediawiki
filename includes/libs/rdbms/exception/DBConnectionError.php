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
 */
namespace Wikimedia\Rdbms;

/**
 * @newable
 * @ingroup Database
 */
class DBConnectionError extends DBExpectedError {
	/**
	 * @stable to call
	 * @param IDatabase|null $db Object throwing the error
	 * @param string $error Error text
	 */
	public function __construct( IDatabase $db = null, $error = 'unknown error' ) {
		$msg = 'Cannot access the database';
		if ( trim( $error ) != '' ) {
			$msg .= ": $error";
		}

		parent::__construct( $db, $msg );
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DBConnectionError::class, 'DBConnectionError' );
