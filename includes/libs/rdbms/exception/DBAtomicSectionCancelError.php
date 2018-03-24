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

use Exception;

/**
 * @ingroup Database
 */
class DBAtomicSectionCancelError extends DBExpectedError {
	public function __construct(
		IDatabase $db, Exception $origError, Exception $cancelError, $fname
	) {
		$message = "An unrecoverable error occurred in an atomic section. \n" .
			"Section: $fname\n" .
			"Original error: {$origError->getMessage()}\n" .
			"Section cancel error: {$cancelError->getMessage()}\n";

		parent::__construct( $db, $message, [], $origError );
	}
}
