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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

/**
 * @newable
 * @ingroup Database
 * @since 1.34
 */
class DBQueryDisconnectedError extends DBQueryError {

	/**
	 * @stable to call
	 * @param IDatabase $db
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 * @param string|null $message Optional message, intended for subclases (optional)
	 */
	public function __construct( IDatabase $db, $error, $errno, $sql, $fname, $message = null ) {
		if ( $message === null ) {
			$message = "A connection error occurred during a query. \n" .
				"Query: $sql\n" .
				"Function: $fname\n" .
				"Error: $errno $error\n";
		}

		parent::__construct( $db, $error, $errno, $sql, $fname, $message );
	}
}
