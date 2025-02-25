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

namespace MediaWiki\User;

use stdClass;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @internal Call and type against UserArray instead.
 */
class UserArrayFromResult extends UserArray {

	private IResultWrapper $res;
	private int $key = 0;
	private ?User $current = null;

	public function __construct( IResultWrapper $res ) {
		$this->res = $res;
		$this->rewind();
	}

	/**
	 * @param stdClass|null|false $row
	 * @return void
	 */
	protected function setCurrent( $row ) {
		$this->current = $row instanceof stdClass ? User::newFromRow( $row ) : null;
	}

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
		$this->setCurrent( $this->res->fetchObject() );
		$this->key++;
	}

	public function rewind(): void {
		$this->res->rewind();
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	public function valid(): bool {
		return (bool)$this->current;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( UserArrayFromResult::class, 'UserArrayFromResult' );
