<?php
/**
 * Holders of revision list for a single page
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

use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * List for revision table items for a single page
 */
abstract class RevisionListBase extends ContextSource implements Iterator {
	/** @var Title */
	public $title;

	/** @var array */
	protected $ids;

	/** @var ResultWrapper|bool */
	protected $res;

	/** @var bool|Revision */
	protected $current;

	/**
	 * Construct a revision list for a given title
	 * @param IContextSource $context
	 * @param Title $title
	 */
	function __construct( IContextSource $context, Title $title ) {
		$this->setContext( $context );
		$this->title = $title;
	}

	/**
	 * Select items only where the ID is any of the specified values
	 * @param array $ids
	 */
	function filterByIds( array $ids ) {
		$this->ids = $ids;
	}

	/**
	 * Get the internal type name of this list. Equal to the table name.
	 * Override this function.
	 * @return null
	 */
	public function getType() {
		return null;
	}

	/**
	 * Initialise the current iteration pointer
	 */
	protected function initCurrent() {
		$row = $this->res->current();
		if ( $row ) {
			$this->current = $this->newItem( $row );
		} else {
			$this->current = false;
		}
	}

	/**
	 * Start iteration. This must be called before current() or next().
	 * @return Revision First list item
	 */
	public function reset() {
		if ( !$this->res ) {
			$this->res = $this->doQuery( wfGetDB( DB_REPLICA ) );
		} else {
			$this->res->rewind();
		}
		$this->initCurrent();
		return $this->current;
	}

	public function rewind() {
		$this->reset();
	}

	/**
	 * Get the current list item, or false if we are at the end
	 * @return Revision
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * Move the iteration pointer to the next list item, and return it.
	 * @return Revision
	 */
	public function next() {
		$this->res->next();
		$this->initCurrent();
		return $this->current;
	}

	public function key() {
		return $this->res ? $this->res->key() : 0;
	}

	public function valid() {
		return $this->res ? $this->res->valid() : false;
	}

	/**
	 * Get the number of items in the list.
	 * @return int
	 */
	public function length() {
		if ( !$this->res ) {
			return 0;
		} else {
			return $this->res->numRows();
		}
	}

	/**
	 * Do the DB query to iterate through the objects.
	 * @param IDatabase $db DB object to use for the query
	 */
	abstract public function doQuery( $db );

	/**
	 * Create an item object from a DB result row
	 * @param object $row
	 */
	abstract public function newItem( $row );
}
