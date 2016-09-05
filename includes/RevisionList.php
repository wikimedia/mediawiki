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

	/** @var bool|object */
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
		return $this->res ? $this->res->key(): 0;
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

/**
 * Abstract base class for revision items
 */
abstract class RevisionItemBase {
	/** @var RevisionListBase The parent */
	protected $list;

	/** The database result row */
	protected $row;

	/**
	 * @param RevisionListBase $list
	 * @param object $row DB result row
	 */
	public function __construct( $list, $row ) {
		$this->list = $list;
		$this->row = $row;
	}

	/**
	 * Get the DB field name associated with the ID list.
	 * Override this function.
	 * @return null
	 */
	public function getIdField() {
		return null;
	}

	/**
	 * Get the DB field name storing timestamps.
	 * Override this function.
	 * @return bool
	 */
	public function getTimestampField() {
		return false;
	}

	/**
	 * Get the DB field name storing user ids.
	 * Override this function.
	 * @return bool
	 */
	public function getAuthorIdField() {
		return false;
	}

	/**
	 * Get the DB field name storing user names.
	 * Override this function.
	 * @return bool
	 */
	public function getAuthorNameField() {
		return false;
	}

	/**
	 * Get the ID, as it would appear in the ids URL parameter
	 * @return int
	 */
	public function getId() {
		$field = $this->getIdField();
		return $this->row->$field;
	}

	/**
	 * Get the date, formatted in user's language
	 * @return string
	 */
	public function formatDate() {
		return $this->list->getLanguage()->userDate( $this->getTimestamp(),
			$this->list->getUser() );
	}

	/**
	 * Get the time, formatted in user's language
	 * @return string
	 */
	public function formatTime() {
		return $this->list->getLanguage()->userTime( $this->getTimestamp(),
			$this->list->getUser() );
	}

	/**
	 * Get the timestamp in MW 14-char form
	 * @return mixed
	 */
	public function getTimestamp() {
		$field = $this->getTimestampField();
		return wfTimestamp( TS_MW, $this->row->$field );
	}

	/**
	 * Get the author user ID
	 * @return int
	 */
	public function getAuthorId() {
		$field = $this->getAuthorIdField();
		return intval( $this->row->$field );
	}

	/**
	 * Get the author user name
	 * @return string
	 */
	public function getAuthorName() {
		$field = $this->getAuthorNameField();
		return strval( $this->row->$field );
	}

	/**
	 * Returns true if the current user can view the item
	 */
	abstract public function canView();

	/**
	 * Returns true if the current user can view the item text/file
	 */
	abstract public function canViewContent();

	/**
	 * Get the HTML of the list item. Should be include "<li></li>" tags.
	 * This is used to show the list in HTML form, by the special page.
	 */
	abstract public function getHTML();
}

class RevisionList extends RevisionListBase {
	public function getType() {
		return 'revision';
	}

	/**
	 * @param IDatabase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$conds = [ 'rev_page' => $this->title->getArticleID() ];
		if ( $this->ids !== null ) {
			$conds['rev_id'] = array_map( 'intval', $this->ids );
		}
		return $db->select(
			[ 'revision', 'page', 'user' ],
			array_merge( Revision::selectFields(), Revision::selectUserFields() ),
			$conds,
			__METHOD__,
			[ 'ORDER BY' => 'rev_id DESC' ],
			[
				'page' => Revision::pageJoinCond(),
				'user' => Revision::userJoinCond() ]
		);
	}

	public function newItem( $row ) {
		return new RevisionItem( $this, $row );
	}
}

/**
 * Item class for a live revision table row
 */
class RevisionItem extends RevisionItemBase {
	/** @var Revision */
	protected $revision;

	/** @var RequestContext */
	protected $context;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->revision = new Revision( $row );
		$this->context = $list->getContext();
	}

	public function getIdField() {
		return 'rev_id';
	}

	public function getTimestampField() {
		return 'rev_timestamp';
	}

	public function getAuthorIdField() {
		return 'rev_user';
	}

	public function getAuthorNameField() {
		return 'rev_user_text';
	}

	public function canView() {
		return $this->revision->userCan( Revision::DELETED_RESTRICTED, $this->context->getUser() );
	}

	public function canViewContent() {
		return $this->revision->userCan( Revision::DELETED_TEXT, $this->context->getUser() );
	}

	public function isDeleted() {
		return $this->revision->isDeleted( Revision::DELETED_TEXT );
	}

	/**
	 * Get the HTML link to the revision text.
	 * @todo Essentially a copy of RevDelRevisionItem::getRevisionLink. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string
	 */
	protected function getRevisionLink() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->revision->getTimestamp(), $this->list->getUser() ) );

		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}
		return Linker::linkKnown(
			$this->list->title,
			$date,
			[],
			[
				'oldid' => $this->revision->getId(),
				'unhide' => 1
			]
		);
	}

	/**
	 * Get the HTML link to the diff.
	 * @todo Essentially a copy of RevDelRevisionItem::getDiffLink. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string
	 */
	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $this->context->msg( 'diff' )->escaped();
		} else {
			return Linker::linkKnown(
					$this->list->title,
					$this->list->msg( 'diff' )->escaped(),
					[],
					[
						'diff' => $this->revision->getId(),
						'oldid' => 'prev',
						'unhide' => 1
					]
				);
		}
	}

	/**
	 * @todo Essentially a copy of RevDelRevisionItem::getHTML. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string
	 */
	public function getHTML() {
		$difflink = $this->context->msg( 'parentheses' )
			->rawParams( $this->getDiffLink() )->escaped();
		$revlink = $this->getRevisionLink();
		$userlink = Linker::revUserLink( $this->revision );
		$comment = Linker::revComment( $this->revision );
		if ( $this->isDeleted() ) {
			$revlink = "<span class=\"history-deleted\">$revlink</span>";
		}
		return "<li>$difflink $revlink $userlink $comment</li>";
	}
}
