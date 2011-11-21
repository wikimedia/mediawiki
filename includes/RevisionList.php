<?php
/**
 * List for revision table items for a single page
 */
abstract class RevisionListBase extends ContextSource {
	/**
	 * @var Title
	 */
	var $title;

	var $ids, $res, $current;

	/**
	 * Construct a revision list for a given title
	 * @param $context IContextSource
	 * @param $title Title
	 */
	function __construct( IContextSource $context, Title $title ) {
		$this->setContext( $context );
		$this->title = $title;
	}

	/**
	 * Select items only where the ID is any of the specified values
	 * @param $ids Array
	 */
	function filterByIds( array $ids ) {
		$this->ids = $ids;
	}

	/**
	 * Get the internal type name of this list. Equal to the table name.
	 * Override this function.
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
	 * @return First list item
	 */
	public function reset() {
		if ( !$this->res ) {
			$this->res = $this->doQuery( wfGetDB( DB_SLAVE ) );
		} else {
			$this->res->rewind();
		}
		$this->initCurrent();
		return $this->current;
	}

	/**
	 * Get the current list item, or false if we are at the end
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * Move the iteration pointer to the next list item, and return it.
	 */
	public function next() {
		$this->res->next();
		$this->initCurrent();
		return $this->current;
	}

	/**
	 * Get the number of items in the list.
	 */
	public function length() {
		if( !$this->res ) {
			return 0;
		} else {
			return $this->res->numRows();
		}
	}

	/**
	 * Do the DB query to iterate through the objects.
	 * @param $db DatabaseBase object to use for the query
	 */
	abstract public function doQuery( $db );

	/**
	 * Create an item object from a DB result row
	 * @param $row stdclass
	 */
	abstract public function newItem( $row );
}

/**
 * Abstract base class for revision items
 */
abstract class RevisionItemBase {
	/** The parent RevisionListBase */
	var $list;

	/** The DB result row */
	var $row;

	/**
	 * @param $list RevisionListBase
	 * @param $row DB result row
	 */
	public function __construct( $list, $row ) {
		$this->list = $list;
		$this->row = $row;
	}

	/**
	 * Get the DB field name associated with the ID list.
	 * Override this function.
	 */
	public function getIdField() {
		return null;
	}

	/**
	 * Get the DB field name storing timestamps.
	 * Override this function.
	 */
	public function getTimestampField() {
		return false;
	}

	/**
	 * Get the DB field name storing user ids.
	 * Override this function.
	 */
	public function getAuthorIdField() {
		return false;
	}

	/**
	 * Get the DB field name storing user names.
	 * Override this function.
	 */
	public function getAuthorNameField() {
		return false;
	}

	/**
	 * Get the ID, as it would appear in the ids URL parameter
	 */
	public function getId() {
		$field = $this->getIdField();
		return $this->row->$field;
	}

	/**
	 * Get the date, formatted in user's languae
	 */
	public function formatDate() {
		return $this->list->getLanguage()->userDate( $this->getTimestamp(),
			$this->list->getUser() );
	}

	/**
	 * Get the time, formatted in user's languae
	 */
	public function formatTime() {
		return $this->list->getLanguage()->userTime( $this->getTimestamp(),
			$this->list->getUser() );
	}

	/**
	 * Get the timestamp in MW 14-char form
	 */
	public function getTimestamp() {
		$field = $this->getTimestampField();
		return wfTimestamp( TS_MW, $this->row->$field );
	}

	/**
	 * Get the author user ID
	 */
	public function getAuthorId() {
		$field = $this->getAuthorIdField();
		return intval( $this->row->$field );
	}

	/**
	 * Get the author user name
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
	 * Get the HTML of the list item. Should be include <li></li> tags.
	 * This is used to show the list in HTML form, by the special page.
	 */
	abstract public function getHTML();
}

class RevisionList extends RevisionListBase {
	public function getType() {
		return 'revision';
	}

	/**
	 * @param $db DatabaseBase
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$conds = array( 'rev_page' => $this->title->getArticleID() );
		if ( $this->ids !== null ) {
			$conds['rev_id'] = array_map( 'intval', $this->ids );
		}
		return $db->select(
			array( 'revision', 'page', 'user' ),
			array_merge( Revision::selectFields(), Revision::selectUserFields() ),
			$conds,
			__METHOD__,
			array( 'ORDER BY' => 'rev_id DESC' ),
			array(
				'page' => Revision::pageJoinCond(),
				'user' => Revision::userJoinCond() )
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
	var $revision, $context;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->revision = new Revision( $row );
		$this->context = $list->context;
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
		return 'user_name'; // see Revision::selectUserFields()
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
	 * Overridden by RevDel_ArchiveItem.
	 */
	protected function getRevisionLink() {
		$date = $this->list->getLanguage()->timeanddate( $this->revision->getTimestamp(), true );
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}
		return Linker::link(
			$this->list->title,
			$date,
			array(),
			array(
				'oldid' => $this->revision->getId(),
				'unhide' => 1
			)
		);
	}

	/**
	 * Get the HTML link to the diff.
	 * Overridden by RevDel_ArchiveItem
	 */
	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return wfMsgHtml('diff');
		} else {
			return
				Linker::link(
					$this->list->title,
					wfMsgHtml('diff'),
					array(),
					array(
						'diff' => $this->revision->getId(),
						'oldid' => 'prev',
						'unhide' => 1
					),
					array(
						'known',
						'noclasses'
					)
				);
		}
	}

	public function getHTML() {
		$difflink = $this->getDiffLink();
		$revlink = $this->getRevisionLink();
		$userlink = Linker::revUserLink( $this->revision );
		$comment = Linker::revComment( $this->revision );
		if ( $this->isDeleted() ) {
			$revlink = "<span class=\"history-deleted\">$revlink</span>";
		}
		return "<li>($difflink) $revlink $userlink $comment</li>";
	}
}
