<?php
/**
 * Class to walk into a list of User objects.
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

use Wikimedia\Rdbms\IResultWrapper;

class UserArrayFromResult extends UserArray implements Countable {
	/** @var IResultWrapper */
	public $res;

	/** @var int */
	public $key;

	/** @var User|false */
	public $current;

	/**
	 * @param IResultWrapper $res
	 */
	public function __construct( $res ) {
		$this->res = $res;
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @param stdClass|false $row
	 * @return void
	 */
	protected function setCurrent( $row ) {
		if ( $row === false ) {
			$this->current = false;
		} else {
			$this->current = User::newFromRow( $row );
		}
	}

	/**
	 * @return int
	 */
	public function count(): int {
		return $this->res->numRows();
	}

	public function current(): User {
		return $this->current;
	}

	public function key(): int {
		return $this->key;
	}

	public function next(): void {
		$row = $this->res->fetchObject();
		$this->setCurrent( $row );
		$this->key++;
	}

	public function rewind(): void {
		$this->res->rewind();
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @return bool
	 */
	public function valid(): bool {
		return $this->current !== false;
	}
}
