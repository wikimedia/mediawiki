<?php
/**
 * Class to walk into a list of Title objects.
 *
 * Note: this entire file is a byte-for-byte copy of UserArrayFromResult.php
 * with s/User/Title/.  If anyone can figure out how to do this nicely
 * with inheritance or something, please do so.
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

/**
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should probably become part of the TitleFactory service.
 */
class TitleArrayFromResult extends TitleArray implements Countable {
	/** @var IResultWrapper */
	public $res;

	public $key;

	public $current;

	/**
	 * @stable to call
	 *
	 * @param IResultWrapper $res
	 */
	public function __construct( $res ) {
		$this->res = $res;
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @param bool|stdClass $row
	 * @return void
	 */
	protected function setCurrent( $row ) {
		if ( $row === false ) {
			$this->current = false;
		} else {
			$this->current = Title::newFromRow( $row );
		}
	}

	/**
	 * @return int
	 */
	public function count() {
		return $this->res->numRows();
	}

	public function current() {
		return $this->current;
	}

	public function key() {
		return $this->key;
	}

	public function next() {
		$row = $this->res->next();
		$this->setCurrent( $row );
		$this->key++;
	}

	public function rewind() {
		$this->res->rewind();
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return $this->current !== false;
	}
}
