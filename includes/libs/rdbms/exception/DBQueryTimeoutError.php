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
 * Error thrown when a query times out
 *
 * @newable
 * @ingroup Database
 */
class DBQueryTimeoutError extends DBQueryError {

	/**
	 * @stable to call
	 *
	 * @param IDatabase $db
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 */
	public function __construct( IDatabase $db, $error, $errno, $sql, $fname ) {
		$message = "A database query timeout has occurred. \n" .
			 "Query: $sql\n" .
			 "Function: $fname\n" .
			 "Error: $errno $error\n";

		parent::__construct( $db, $error, $errno, $sql, $fname, $message );
	}
}
