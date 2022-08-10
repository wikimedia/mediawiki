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
 * Class used for token representing identifiers for atomic transactions from IDatabase instances
 *
 * @ingroup Database
 * @internal
 */
class TransactionIdentifier {
	/** @var string Application-side ID of the active transaction or an empty string otherwise */
	private $id = '';

	public function __construct() {
		static $nextId;
		$nextId = ( $nextId !== null ? $nextId++ : mt_rand() ) % 0xffff;
		$this->id = sprintf( '%06x', mt_rand( 0, 0xffffff ) ) . sprintf( '%04x', $nextId );
	}

	public function __toString() {
		return $this->id;
	}
}
