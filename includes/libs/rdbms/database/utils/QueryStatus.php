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
 * @since 1.39
 * @ingroup Database
 */
class QueryStatus {
	/** @var ResultWrapper|bool|null Result set */
	public $res;
	/** @var int Returned row count */
	public $rowsReturned;
	/** @var int Affected row count */
	public $rowsAffected;
	/** @var string Error message or empty string */
	public $message;
	/** @var int|string Error code or zero */
	public $code;
	/** @var int Error flag bit field of Database::ERR_* constants */
	public $flags;

	/**
	 * @internal Should only be constructed by Database
	 *
	 * @param ResultWrapper|bool|null $res
	 * @param int $affected
	 * @param string $error
	 * @param int|string $errno
	 */
	public function __construct( $res, int $affected, string $error, $errno ) {
		if ( !( $res instanceof IResultWrapper ) && !is_bool( $res ) && $res !== null ) {
			throw new DBUnexpectedError(
				null,
				'Got ' . gettype( $res ) . ' instead of IResultWrapper|bool'
			);
		}

		$this->res = $res;
		$this->rowsReturned = ( $res instanceof IResultWrapper ) ? $res->numRows() : 0;
		$this->rowsAffected = $affected;
		$this->message = $error;
		$this->code = $errno;
		$this->flags = 0;
	}
}
