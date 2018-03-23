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

use RuntimeException;

/**
 * Database error base class
 * @ingroup Database
 */
class DBError extends RuntimeException {
	/** @var IDatabase|null */
	public $db;

	/**
	 * Construct a database error
	 * @param IDatabase $db Object which threw the error
	 * @param string $error A simple error message to be used for debugging
	 * @param \Exception|\Throwable|null $prev Previous exception
	 */
	public function __construct( IDatabase $db = null, $error, $prev = null ) {
		parent::__construct( $error, 0, $prev );
		$this->db = $db;
	}
}

class_alias( DBError::class, 'DBError' );
