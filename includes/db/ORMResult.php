<?php
/**
 * ORMIterator that takes a ResultWrapper object returned from
 * a select operation returning IORMRow objects (ie IORMTable::select).
 *
 * Documentation inline and at https://www.mediawiki.org/wiki/Manual:ORMTable
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
 * @since 1.20
 *
 * @file ORMResult.php
 * @ingroup ORM
 *
 * @license GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

class ORMResult implements ORMIterator {
	/**
	 * @var ResultWrapper
	 */
	protected $res;

	/**
	 * @var integer
	 */
	protected $key;

	/**
	 * @var IORMRow
	 */
	protected $current;

	/**
	 * @var IORMTable
	 */
	protected $table;

	/**
	 * @param IORMTable $table
	 * @param ResultWrapper $res
	 */
	public function __construct( IORMTable $table, ResultWrapper $res ) {
		$this->table = $table;
		$this->res = $res;
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @param $row
	 */
	protected function setCurrent( $row ) {
		if ( $row === false ) {
			$this->current = false;
		} else {
			$this->current = $this->table->newRowFromDBResult( $row );
		}
	}

	/**
	 * @return integer
	 */
	public function count() {
		return $this->res->numRows();
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->res->numRows() === 0;
	}

	/**
	 * @return IORMRow
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * @return integer
	 */
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
	 * @return boolean
	 */
	public function valid() {
		return $this->current !== false;
	}
}
