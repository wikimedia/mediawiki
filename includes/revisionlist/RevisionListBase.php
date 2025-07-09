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

namespace MediaWiki\RevisionList;

use Iterator;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List for revision table items for a single page
 */
abstract class RevisionListBase extends ContextSource implements Iterator {
	use DeprecationHelper;

	/** @var PageIdentity */
	protected $page;

	/** @var int[]|null */
	protected $ids;

	/** @var IResultWrapper|false */
	protected $res;

	/** @var RevisionItemBase|false */
	protected $current;

	/**
	 * Construct a revision list for a given page identity
	 * @param IContextSource $context
	 * @param PageIdentity $page
	 */
	public function __construct( IContextSource $context, PageIdentity $page ) {
		$this->setContext( $context );
		$this->page = $page;

		$this->deprecatePublicPropertyFallback(
			'title',
			'1.37',
			function (): Title {
				return Title::newFromPageIdentity( $this->page );
			},
			function ( PageIdentity $page ) {
				$this->page = $page;
			}
		);
	}

	public function getPage(): PageIdentity {
		return $this->page;
	}

	/**
	 * @internal for use by RevDelItems
	 * @return string
	 */
	public function getPageName(): string {
		return Title::newFromPageIdentity( $this->page )->getPrefixedText();
	}

	/**
	 * Select items only where the ID is any of the specified values
	 * @param int[] $ids
	 */
	public function filterByIds( array $ids ) {
		$this->ids = $ids;
	}

	/**
	 * Get the internal type name of this list. Equal to the table name.
	 * Override this function.
	 * @return string|null
	 */
	public function getType() {
		return null;
	}

	/**
	 * Initialise the current iteration pointer
	 */
	protected function initCurrent() {
		if ( $this->res->valid() ) {
			$this->current = $this->newItem( $this->res->current() );
		} else {
			$this->current = false;
		}
	}

	/**
	 * Start iteration. This must be called before current() or next().
	 * @return RevisionItemBase First list item
	 */
	public function reset() {
		if ( !$this->res ) {
			$this->res = $this->doQuery(
				MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase()
			);
		} else {
			$this->res->rewind();
		}
		$this->initCurrent();
		return $this->current;
	}

	public function rewind(): void {
		$this->reset();
	}

	/**
	 * Get the current list item, or false if we are at the end
	 * @return RevisionItemBase
	 */
	#[\ReturnTypeWillChange]
	public function current() {
		return $this->current;
	}

	/**
	 * Move the iteration pointer to the next list item, and return it.
	 * @return RevisionItemBase
	 * @suppress PhanParamSignatureMismatchInternal
	 */
	#[\ReturnTypeWillChange]
	public function next() {
		$this->res->next();
		$this->initCurrent();
		return $this->current;
	}

	public function key(): int {
		return $this->res ? $this->res->key() : 0;
	}

	public function valid(): bool {
		return $this->res && $this->res->valid();
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
	 * @param IReadableDatabase $db DB object to use for the query
	 * @return IResultWrapper
	 */
	abstract public function doQuery( $db );

	/**
	 * Create an item object from a DB result row
	 * @param stdClass $row
	 * @return RevisionItemBase
	 */
	abstract public function newItem( $row );
}
/** @deprecated class alias since 1.43 */
class_alias( RevisionListBase::class, 'RevisionListBase' );
