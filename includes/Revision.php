<?php
/**
 * Representation of a page version.
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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;

/**
 * @todo document
 */
class Revision implements IDBAccessObject {
	/** @var int|null */
	protected $mId;
	/** @var int|null */
	protected $mPage;
	/** @var string */
	protected $mUserText;
	/** @var string */
	protected $mOrigUserText;
	/** @var int */
	protected $mUser;
	/** @var bool */
	protected $mMinorEdit;
	/** @var string */
	protected $mTimestamp;
	/** @var int */
	protected $mDeleted;
	/** @var int */
	protected $mSize;
	/** @var string */
	protected $mSha1;
	/** @var int */
	protected $mParentId;
	/** @var string */
	protected $mComment;
	/** @var string */
	protected $mText;
	/** @var int */
	protected $mTextId;
	/** @var int */
	protected $mUnpatrolled;

	/** @var stdClass|null */
	protected $mTextRow;

	/**  @var null|Title */
	protected $mTitle;
	/** @var bool */
	protected $mCurrent;
	/** @var string */
	protected $mContentModel;
	/** @var string */
	protected $mContentFormat;

	/** @var Content|null|bool */
	protected $mContent;
	/** @var null|ContentHandler */
	protected $mContentHandler;

	/** @var int */
	protected $mQueryFlags = 0;
	/** @var bool Used for cached values to reload user text and rev_deleted */
	protected $mRefreshMutableFields = false;
	/** @var string Wiki ID; false means the current wiki */
	protected $mWiki = false;

	// Revision deletion constants
	const DELETED_TEXT = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;
	const SUPPRESSED_USER = 12; // convenience

	// Audience options for accessors
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;
	const RAW = 3;

	const TEXT_CACHE_GROUP = 'revisiontext:10'; // process cache name and max key count

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master
	 *      Revision::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $id
	 * @param int $flags (optional)
	 * @return Revision|null
	 */
	public static function newFromId( $id, $flags = 0 ) {
		return self::newFromConds( [ 'rev_id' => intval( $id ) ], $flags );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master
	 *      Revision::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param LinkTarget $linkTarget
	 * @param int $id (optional)
	 * @param int $flags Bitfield (optional)
	 * @return Revision|null
	 */
	public static function newFromTitle( LinkTarget $linkTarget, $id = 0, $flags = 0 ) {
		$conds = [
			'page_namespace' => $linkTarget->getNamespace(),
			'page_title' => $linkTarget->getDBkey()
		];
		if ( $id ) {
			// Use the specified ID
			$conds['rev_id'] = $id;
			return self::newFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision
			$conds[] = 'rev_id=page_latest';
			$db = wfGetDB( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );
			return self::loadFromConds( $db, $conds, $flags );
		}
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master (since 1.20)
	 *      Revision::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return Revision|null
	 */
	public static function newFromPageId( $pageId, $revId = 0, $flags = 0 ) {
		$conds = [ 'page_id' => $pageId ];
		if ( $revId ) {
			$conds['rev_id'] = $revId;
			return self::newFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision
			$conds[] = 'rev_id = page_latest';
			$db = wfGetDB( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );
			return self::loadFromConds( $db, $conds, $flags );
		}
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 * @todo FIXME: Should be a subclass for RevisionDelete. [TS]
	 *
	 * @param object $row
	 * @param array $overrides
	 *
	 * @throws MWException
	 * @return Revision
	 */
	public static function newFromArchiveRow( $row, $overrides = [] ) {
		global $wgContentHandlerUseDB;

		$attribs = $overrides + [
			'page'       => isset( $row->ar_page_id ) ? $row->ar_page_id : null,
			'id'         => isset( $row->ar_rev_id ) ? $row->ar_rev_id : null,
			'comment'    => $row->ar_comment,
			'user'       => $row->ar_user,
			'user_text'  => $row->ar_user_text,
			'timestamp'  => $row->ar_timestamp,
			'minor_edit' => $row->ar_minor_edit,
			'text_id'    => isset( $row->ar_text_id ) ? $row->ar_text_id : null,
			'deleted'    => $row->ar_deleted,
			'len'        => $row->ar_len,
			'sha1'       => isset( $row->ar_sha1 ) ? $row->ar_sha1 : null,
			'content_model'   => isset( $row->ar_content_model ) ? $row->ar_content_model : null,
			'content_format'  => isset( $row->ar_content_format ) ? $row->ar_content_format : null,
		];

		if ( !$wgContentHandlerUseDB ) {
			unset( $attribs['content_model'] );
			unset( $attribs['content_format'] );
		}

		if ( !isset( $attribs['title'] )
			&& isset( $row->ar_namespace )
			&& isset( $row->ar_title )
		) {
			$attribs['title'] = Title::makeTitle( $row->ar_namespace, $row->ar_title );
		}

		if ( isset( $row->ar_text ) && !$row->ar_text_id ) {
			// Pre-1.5 ar_text row
			$attribs['text'] = self::getRevisionText( $row, 'ar_' );
			if ( $attribs['text'] === false ) {
				throw new MWException( 'Unable to load text from archive row (possibly bug 22624)' );
			}
		}
		return new self( $attribs );
	}

	/**
	 * @since 1.19
	 *
	 * @param object $row
	 * @return Revision
	 */
	public static function newFromRow( $row ) {
		return new self( $row );
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * @param IDatabase $db
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromId( $db, $id ) {
		return self::loadFromConds( $db, [ 'rev_id' => intval( $id ) ] );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param IDatabase $db
	 * @param int $pageid
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromPageId( $db, $pageid, $id = 0 ) {
		$conds = [ 'rev_page' => intval( $pageid ), 'page_id' => intval( $pageid ) ];
		if ( $id ) {
			$conds['rev_id'] = intval( $id );
		} else {
			$conds[] = 'rev_id=page_latest';
		}
		return self::loadFromConds( $db, $conds );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromTitle( $db, $title, $id = 0 ) {
		if ( $id ) {
			$matchId = intval( $id );
		} else {
			$matchId = 'page_latest';
		}
		return self::loadFromConds( $db,
			[
				"rev_id=$matchId",
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			]
		);
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param string $timestamp
	 * @return Revision|null
	 */
	public static function loadFromTimestamp( $db, $title, $timestamp ) {
		return self::loadFromConds( $db,
			[
				'rev_timestamp' => $db->timestamp( $timestamp ),
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			]
		);
	}

	/**
	 * Given a set of conditions, fetch a revision
	 *
	 * This method is used then a revision ID is qualified and
	 * will incorporate some basic replica DB/master fallback logic
	 *
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @return Revision|null
	 */
	private static function newFromConds( $conditions, $flags = 0 ) {
		$db = wfGetDB( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );

		$rev = self::loadFromConds( $db, $conditions, $flags );
		// Make sure new pending/committed revision are visibile later on
		// within web requests to certain avoid bugs like T93866 and T94407.
		if ( !$rev
			&& !( $flags & self::READ_LATEST )
			&& wfGetLB()->getServerCount() > 1
			&& wfGetLB()->hasOrMadeRecentMasterChanges()
		) {
			$flags = self::READ_LATEST;
			$db = wfGetDB( DB_MASTER );
			$rev = self::loadFromConds( $db, $conditions, $flags );
		}

		if ( $rev ) {
			$rev->mQueryFlags = $flags;
		}

		return $rev;
	}

	/**
	 * Given a set of conditions, fetch a revision from
	 * the given database connection.
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @return Revision|null
	 */
	private static function loadFromConds( $db, $conditions, $flags = 0 ) {
		$row = self::fetchFromConds( $db, $conditions, $flags );
		if ( $row ) {
			$rev = new Revision( $row );
			$rev->mWiki = $db->getWikiID();

			return $rev;
		}

		return null;
	}

	/**
	 * Return a wrapper for a series of database rows to
	 * fetch all of a given page's revisions in turn.
	 * Each row can be fed to the constructor to get objects.
	 *
	 * @param LinkTarget $title
	 * @return ResultWrapper
	 * @deprecated Since 1.28
	 */
	public static function fetchRevision( LinkTarget $title ) {
		$row = self::fetchFromConds(
			wfGetDB( DB_REPLICA ),
			[
				'rev_id=page_latest',
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			]
		);

		return new FakeResultWrapper( $row ? [ $row ] : [] );
	}

	/**
	 * Given a set of conditions, return a ResultWrapper
	 * which will return matching database rows with the
	 * fields necessary to build Revision objects.
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @return stdClass
	 */
	private static function fetchFromConds( $db, $conditions, $flags = 0 ) {
		$fields = array_merge(
			self::selectFields(),
			self::selectPageFields(),
			self::selectUserFields()
		);
		$options = [];
		if ( ( $flags & self::READ_LOCKING ) == self::READ_LOCKING ) {
			$options[] = 'FOR UPDATE';
		}
		return $db->selectRow(
			[ 'revision', 'page', 'user' ],
			$fields,
			$conditions,
			__METHOD__,
			$options,
			[ 'page' => self::pageJoinCond(), 'user' => self::userJoinCond() ]
		);
	}

	/**
	 * Return the value of a select() JOIN conds array for the user table.
	 * This will get user table rows for logged-in users.
	 * @since 1.19
	 * @return array
	 */
	public static function userJoinCond() {
		return [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ];
	}

	/**
	 * Return the value of a select() page conds array for the page table.
	 * This will assure that the revision(s) are not orphaned from live pages.
	 * @since 1.19
	 * @return array
	 */
	public static function pageJoinCond() {
		return [ 'INNER JOIN', [ 'page_id = rev_page' ] ];
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision.
	 * @return array
	 */
	public static function selectFields() {
		global $wgContentHandlerUseDB;

		$fields = [
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_comment',
			'rev_user_text',
			'rev_user',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'rev_content_format';
			$fields[] = 'rev_content_model';
		}

		return $fields;
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision from an archive row.
	 * @return array
	 */
	public static function selectArchiveFields() {
		global $wgContentHandlerUseDB;
		$fields = [
			'ar_id',
			'ar_page_id',
			'ar_rev_id',
			'ar_text',
			'ar_text_id',
			'ar_timestamp',
			'ar_comment',
			'ar_user_text',
			'ar_user',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
		];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'ar_content_format';
			$fields[] = 'ar_content_model';
		}
		return $fields;
	}

	/**
	 * Return the list of text fields that should be selected to read the
	 * revision text
	 * @return array
	 */
	public static function selectTextFields() {
		return [
			'old_text',
			'old_flags'
		];
	}

	/**
	 * Return the list of page fields that should be selected from page table
	 * @return array
	 */
	public static function selectPageFields() {
		return [
			'page_namespace',
			'page_title',
			'page_id',
			'page_latest',
			'page_is_redirect',
			'page_len',
		];
	}

	/**
	 * Return the list of user fields that should be selected from user table
	 * @return array
	 */
	public static function selectUserFields() {
		return [ 'user_name' ];
	}

	/**
	 * Do a batched query to get the parent revision lengths
	 * @param IDatabase $db
	 * @param array $revIds
	 * @return array
	 */
	public static function getParentLengths( $db, array $revIds ) {
		$revLens = [];
		if ( !$revIds ) {
			return $revLens; // empty
		}
		$res = $db->select( 'revision',
			[ 'rev_id', 'rev_len' ],
			[ 'rev_id' => $revIds ],
			__METHOD__ );
		foreach ( $res as $row ) {
			$revLens[$row->rev_id] = $row->rev_len;
		}
		return $revLens;
	}

	/**
	 * Constructor
	 *
	 * @param object|array $row Either a database row or an array
	 * @throws MWException
	 * @access private
	 */
	function __construct( $row ) {
		if ( is_object( $row ) ) {
			$this->mId = intval( $row->rev_id );
			$this->mPage = intval( $row->rev_page );
			$this->mTextId = intval( $row->rev_text_id );
			$this->mComment = $row->rev_comment;
			$this->mUser = intval( $row->rev_user );
			$this->mMinorEdit = intval( $row->rev_minor_edit );
			$this->mTimestamp = $row->rev_timestamp;
			$this->mDeleted = intval( $row->rev_deleted );

			if ( !isset( $row->rev_parent_id ) ) {
				$this->mParentId = null;
			} else {
				$this->mParentId = intval( $row->rev_parent_id );
			}

			if ( !isset( $row->rev_len ) ) {
				$this->mSize = null;
			} else {
				$this->mSize = intval( $row->rev_len );
			}

			if ( !isset( $row->rev_sha1 ) ) {
				$this->mSha1 = null;
			} else {
				$this->mSha1 = $row->rev_sha1;
			}

			if ( isset( $row->page_latest ) ) {
				$this->mCurrent = ( $row->rev_id == $row->page_latest );
				$this->mTitle = Title::newFromRow( $row );
			} else {
				$this->mCurrent = false;
				$this->mTitle = null;
			}

			if ( !isset( $row->rev_content_model ) ) {
				$this->mContentModel = null; # determine on demand if needed
			} else {
				$this->mContentModel = strval( $row->rev_content_model );
			}

			if ( !isset( $row->rev_content_format ) ) {
				$this->mContentFormat = null; # determine on demand if needed
			} else {
				$this->mContentFormat = strval( $row->rev_content_format );
			}

			// Lazy extraction...
			$this->mText = null;
			if ( isset( $row->old_text ) ) {
				$this->mTextRow = $row;
			} else {
				// 'text' table row entry will be lazy-loaded
				$this->mTextRow = null;
			}

			// Use user_name for users and rev_user_text for IPs...
			$this->mUserText = null; // lazy load if left null
			if ( $this->mUser == 0 ) {
				$this->mUserText = $row->rev_user_text; // IP user
			} elseif ( isset( $row->user_name ) ) {
				$this->mUserText = $row->user_name; // logged-in user
			}
			$this->mOrigUserText = $row->rev_user_text;
		} elseif ( is_array( $row ) ) {
			// Build a new revision to be saved...
			global $wgUser; // ugh

			# if we have a content object, use it to set the model and type
			if ( !empty( $row['content'] ) ) {
				// @todo when is that set? test with external store setup! check out insertOn() [dk]
				if ( !empty( $row['text_id'] ) ) {
					throw new MWException( "Text already stored in external store (id {$row['text_id']}), " .
						"can't serialize content object" );
				}

				$row['content_model'] = $row['content']->getModel();
				# note: mContentFormat is initializes later accordingly
				# note: content is serialized later in this method!
				# also set text to null?
			}

			$this->mId = isset( $row['id'] ) ? intval( $row['id'] ) : null;
			$this->mPage = isset( $row['page'] ) ? intval( $row['page'] ) : null;
			$this->mTextId = isset( $row['text_id'] ) ? intval( $row['text_id'] ) : null;
			$this->mUserText = isset( $row['user_text'] )
				? strval( $row['user_text'] ) : $wgUser->getName();
			$this->mUser = isset( $row['user'] ) ? intval( $row['user'] ) : $wgUser->getId();
			$this->mMinorEdit = isset( $row['minor_edit'] ) ? intval( $row['minor_edit'] ) : 0;
			$this->mTimestamp = isset( $row['timestamp'] )
				? strval( $row['timestamp'] ) : wfTimestampNow();
			$this->mDeleted = isset( $row['deleted'] ) ? intval( $row['deleted'] ) : 0;
			$this->mSize = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$this->mParentId = isset( $row['parent_id'] ) ? intval( $row['parent_id'] ) : null;
			$this->mSha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			$this->mContentModel = isset( $row['content_model'] )
				? strval( $row['content_model'] ) : null;
			$this->mContentFormat = isset( $row['content_format'] )
				? strval( $row['content_format'] ) : null;

			// Enforce spacing trimming on supplied text
			$this->mComment = isset( $row['comment'] ) ? trim( strval( $row['comment'] ) ) : null;
			$this->mText = isset( $row['text'] ) ? rtrim( strval( $row['text'] ) ) : null;
			$this->mTextRow = null;

			$this->mTitle = isset( $row['title'] ) ? $row['title'] : null;

			// if we have a Content object, override mText and mContentModel
			if ( !empty( $row['content'] ) ) {
				if ( !( $row['content'] instanceof Content ) ) {
					throw new MWException( '`content` field must contain a Content object.' );
				}

				$handler = $this->getContentHandler();
				$this->mContent = $row['content'];

				$this->mContentModel = $this->mContent->getModel();
				$this->mContentHandler = null;

				$this->mText = $handler->serializeContent( $row['content'], $this->getContentFormat() );
			} elseif ( $this->mText !== null ) {
				$handler = $this->getContentHandler();
				$this->mContent = $handler->unserializeContent( $this->mText );
			}

			// If we have a Title object, make sure it is consistent with mPage.
			if ( $this->mTitle && $this->mTitle->exists() ) {
				if ( $this->mPage === null ) {
					// if the page ID wasn't known, set it now
					$this->mPage = $this->mTitle->getArticleID();
				} elseif ( $this->mTitle->getArticleID() !== $this->mPage ) {
					// Got different page IDs. This may be legit (e.g. during undeletion),
					// but it seems worth mentioning it in the log.
					wfDebug( "Page ID " . $this->mPage . " mismatches the ID " .
						$this->mTitle->getArticleID() . " provided by the Title object." );
				}
			}

			$this->mCurrent = false;

			// If we still have no length, see it we have the text to figure it out
			if ( !$this->mSize && $this->mContent !== null ) {
				$this->mSize = $this->mContent->getSize();
			}

			// Same for sha1
			if ( $this->mSha1 === null ) {
				$this->mSha1 = $this->mText === null ? null : self::base36Sha1( $this->mText );
			}

			// force lazy init
			$this->getContentModel();
			$this->getContentFormat();
		} else {
			throw new MWException( 'Revision constructor passed invalid row format.' );
		}
		$this->mUnpatrolled = null;
	}

	/**
	 * Get revision ID
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->mId;
	}

	/**
	 * Set the revision ID
	 *
	 * This should only be used for proposed revisions that turn out to be null edits
	 *
	 * @since 1.19
	 * @param int $id
	 */
	public function setId( $id ) {
		$this->mId = (int)$id;
	}

	/**
	 * Set the user ID/name
	 *
	 * This should only be used for proposed revisions that turn out to be null edits
	 *
	 * @since 1.28
	 * @param integer $id User ID
	 * @param string $name User name
	 */
	public function setUserIdAndName( $id, $name ) {
		$this->mUser = (int)$id;
		$this->mUserText = $name;
		$this->mOrigUserText = $name;
	}

	/**
	 * Get text row ID
	 *
	 * @return int|null
	 */
	public function getTextId() {
		return $this->mTextId;
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return int|null
	 */
	public function getParentId() {
		return $this->mParentId;
	}

	/**
	 * Returns the length of the text in this revision, or null if unknown.
	 *
	 * @return int|null
	 */
	public function getSize() {
		return $this->mSize;
	}

	/**
	 * Returns the base36 sha1 of the text in this revision, or null if unknown.
	 *
	 * @return string|null
	 */
	public function getSha1() {
		return $this->mSha1;
	}

	/**
	 * Returns the title of the page associated with this entry or null.
	 *
	 * Will do a query, when title is not set and id is given.
	 *
	 * @return Title|null
	 */
	public function getTitle() {
		if ( $this->mTitle !== null ) {
			return $this->mTitle;
		}
		// rev_id is defined as NOT NULL, but this revision may not yet have been inserted.
		if ( $this->mId !== null ) {
			$dbr = wfGetLB( $this->mWiki )->getConnectionRef( DB_REPLICA, [], $this->mWiki );
			$row = $dbr->selectRow(
				[ 'page', 'revision' ],
				self::selectPageFields(),
				[ 'page_id=rev_page', 'rev_id' => $this->mId ],
				__METHOD__
			);
			if ( $row ) {
				// @TODO: better foreign title handling
				$this->mTitle = Title::newFromRow( $row );
			}
		}

		if ( $this->mWiki === false || $this->mWiki === wfWikiID() ) {
			// Loading by ID is best, though not possible for foreign titles
			if ( !$this->mTitle && $this->mPage !== null && $this->mPage > 0 ) {
				$this->mTitle = Title::newFromID( $this->mPage );
			}
		}

		return $this->mTitle;
	}

	/**
	 * Set the title of the revision
	 *
	 * @param Title $title
	 */
	public function setTitle( $title ) {
		$this->mTitle = $title;
	}

	/**
	 * Get the page ID
	 *
	 * @return int|null
	 */
	public function getPage() {
		return $this->mPage;
	}

	/**
	 * Fetch revision's user id if it's available to the specified audience.
	 * If the specified audience does not have access to it, zero will be
	 * returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the ID regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return int
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return 0;
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $user ) ) {
			return 0;
		} else {
			return $this->mUser;
		}
	}

	/**
	 * Fetch revision's user id without regard for the current user's permissions
	 *
	 * @return string
	 * @deprecated since 1.25, use getUser( Revision::RAW )
	 */
	public function getRawUser() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getUser( self::RAW );
	}

	/**
	 * Fetch revision's username if it's available to the specified audience.
	 * If the specified audience does not have access to the username, an
	 * empty string will be returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		$this->loadMutableFields();

		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return '';
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $user ) ) {
			return '';
		} else {
			if ( $this->mUserText === null ) {
				$this->mUserText = User::whoIs( $this->mUser ); // load on demand
				if ( $this->mUserText === false ) {
					# This shouldn't happen, but it can if the wiki was recovered
					# via importing revs and there is no user table entry yet.
					$this->mUserText = $this->mOrigUserText;
				}
			}
			return $this->mUserText;
		}
	}

	/**
	 * Fetch revision's username without regard for view restrictions
	 *
	 * @return string
	 * @deprecated since 1.25, use getUserText( Revision::RAW )
	 */
	public function getRawUserText() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getUserText( self::RAW );
	}

	/**
	 * Fetch revision comment if it's available to the specified audience.
	 * If the specified audience does not have access to the comment, an
	 * empty string will be returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string
	 */
	function getComment( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_COMMENT ) ) {
			return '';
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_COMMENT, $user ) ) {
			return '';
		} else {
			return $this->mComment;
		}
	}

	/**
	 * Fetch revision comment without regard for the current user's permissions
	 *
	 * @return string
	 * @deprecated since 1.25, use getComment( Revision::RAW )
	 */
	public function getRawComment() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getComment( self::RAW );
	}

	/**
	 * @return bool
	 */
	public function isMinor() {
		return (bool)$this->mMinorEdit;
	}

	/**
	 * @return int Rcid of the unpatrolled row, zero if there isn't one
	 */
	public function isUnpatrolled() {
		if ( $this->mUnpatrolled !== null ) {
			return $this->mUnpatrolled;
		}
		$rc = $this->getRecentChange();
		if ( $rc && $rc->getAttribute( 'rc_patrolled' ) == 0 ) {
			$this->mUnpatrolled = $rc->getAttribute( 'rc_id' );
		} else {
			$this->mUnpatrolled = 0;
		}
		return $this->mUnpatrolled;
	}

	/**
	 * Get the RC object belonging to the current revision, if there's one
	 *
	 * @param int $flags (optional) $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master
	 *
	 * @since 1.22
	 * @return RecentChange|null
	 */
	public function getRecentChange( $flags = 0 ) {
		$dbr = wfGetDB( DB_REPLICA );

		list( $dbType, ) = DBAccessObjectUtils::getDBOptions( $flags );

		return RecentChange::newFromConds(
			[
				'rc_user_text' => $this->getUserText( Revision::RAW ),
				'rc_timestamp' => $dbr->timestamp( $this->getTimestamp() ),
				'rc_this_oldid' => $this->getId()
			],
			__METHOD__,
			$dbType
		);
	}

	/**
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @return bool
	 */
	public function isDeleted( $field ) {
		if ( $this->isCurrent() && $field === self::DELETED_TEXT ) {
			// Current revisions of pages cannot have the content hidden. Skipping this
			// check is very useful for Parser as it fetches templates using newKnownCurrent().
			// Calling getVisibility() in that case triggers a verification database query.
			return false; // no need to check
		}

		return ( $this->getVisibility() & $field ) == $field;
	}

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * @return int
	 */
	public function getVisibility() {
		$this->loadMutableFields();

		return (int)$this->mDeleted;
	}

	/**
	 * Fetch revision text if it's available to the specified audience.
	 * If the specified audience does not have the ability to view this
	 * revision, an empty string will be returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 *
	 * @deprecated since 1.21, use getContent() instead
	 * @return string
	 */
	public function getText( $audience = self::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.21' );

		$content = $this->getContent( $audience, $user );
		return ContentHandler::getContentText( $content ); # returns the raw content text, if applicable
	}

	/**
	 * Fetch revision content if it's available to the specified audience.
	 * If the specified audience does not have the ability to view this
	 * revision, null will be returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @since 1.21
	 * @return Content|null
	 */
	public function getContent( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_TEXT ) ) {
			return null;
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_TEXT, $user ) ) {
			return null;
		} else {
			return $this->getContentInternal();
		}
	}

	/**
	 * Get original serialized data (without checking view restrictions)
	 *
	 * @since 1.21
	 * @return string
	 */
	public function getSerializedData() {
		if ( $this->mText === null ) {
			// Revision is immutable. Load on demand.
			$this->mText = $this->loadText();
		}

		return $this->mText;
	}

	/**
	 * Gets the content object for the revision (or null on failure).
	 *
	 * Note that for mutable Content objects, each call to this method will return a
	 * fresh clone.
	 *
	 * @since 1.21
	 * @return Content|null The Revision's content, or null on failure.
	 */
	protected function getContentInternal() {
		if ( $this->mContent === null ) {
			$text = $this->getSerializedData();

			if ( $text !== null && $text !== false ) {
				// Unserialize content
				$handler = $this->getContentHandler();
				$format = $this->getContentFormat();

				$this->mContent = $handler->unserializeContent( $text, $format );
			}
		}

		// NOTE: copy() will return $this for immutable content objects
		return $this->mContent ? $this->mContent->copy() : null;
	}

	/**
	 * Returns the content model for this revision.
	 *
	 * If no content model was stored in the database, the default content model for the title is
	 * used to determine the content model to use. If no title is know, CONTENT_MODEL_WIKITEXT
	 * is used as a last resort.
	 *
	 * @return string The content model id associated with this revision,
	 *     see the CONTENT_MODEL_XXX constants.
	 **/
	public function getContentModel() {
		if ( !$this->mContentModel ) {
			$title = $this->getTitle();
			if ( $title ) {
				$this->mContentModel = ContentHandler::getDefaultModelFor( $title );
			} else {
				$this->mContentModel = CONTENT_MODEL_WIKITEXT;
			}

			assert( !empty( $this->mContentModel ) );
		}

		return $this->mContentModel;
	}

	/**
	 * Returns the content format for this revision.
	 *
	 * If no content format was stored in the database, the default format for this
	 * revision's content model is returned.
	 *
	 * @return string The content format id associated with this revision,
	 *     see the CONTENT_FORMAT_XXX constants.
	 **/
	public function getContentFormat() {
		if ( !$this->mContentFormat ) {
			$handler = $this->getContentHandler();
			$this->mContentFormat = $handler->getDefaultFormat();

			assert( !empty( $this->mContentFormat ) );
		}

		return $this->mContentFormat;
	}

	/**
	 * Returns the content handler appropriate for this revision's content model.
	 *
	 * @throws MWException
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		if ( !$this->mContentHandler ) {
			$model = $this->getContentModel();
			$this->mContentHandler = ContentHandler::getForModelID( $model );

			$format = $this->getContentFormat();

			if ( !$this->mContentHandler->isSupportedFormat( $format ) ) {
				throw new MWException( "Oops, the content format $format is not supported for "
					. "this content model, $model" );
			}
		}

		return $this->mContentHandler;
	}

	/**
	 * @return string
	 */
	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->mTimestamp );
	}

	/**
	 * @return bool
	 */
	public function isCurrent() {
		return $this->mCurrent;
	}

	/**
	 * Get previous revision for this title
	 *
	 * @return Revision|null
	 */
	public function getPrevious() {
		if ( $this->getTitle() ) {
			$prev = $this->getTitle()->getPreviousRevisionID( $this->getId() );
			if ( $prev ) {
				return self::newFromTitle( $this->getTitle(), $prev );
			}
		}
		return null;
	}

	/**
	 * Get next revision for this title
	 *
	 * @return Revision|null
	 */
	public function getNext() {
		if ( $this->getTitle() ) {
			$next = $this->getTitle()->getNextRevisionID( $this->getId() );
			if ( $next ) {
				return self::newFromTitle( $this->getTitle(), $next );
			}
		}
		return null;
	}

	/**
	 * Get previous revision Id for this page_id
	 * This is used to populate rev_parent_id on save
	 *
	 * @param IDatabase $db
	 * @return int
	 */
	private function getPreviousRevisionId( $db ) {
		if ( $this->mPage === null ) {
			return 0;
		}
		# Use page_latest if ID is not given
		if ( !$this->mId ) {
			$prevId = $db->selectField( 'page', 'page_latest',
				[ 'page_id' => $this->mPage ],
				__METHOD__ );
		} else {
			$prevId = $db->selectField( 'revision', 'rev_id',
				[ 'rev_page' => $this->mPage, 'rev_id < ' . $this->mId ],
				__METHOD__,
				[ 'ORDER BY' => 'rev_id DESC' ] );
		}
		return intval( $prevId );
	}

	/**
	 * Get revision text associated with an old or archive row
	 * $row is usually an object from wfFetchRow(), both the flags and the text
	 * field must be included.
	 *
	 * @param stdClass $row The text data
	 * @param string $prefix Table prefix (default 'old_')
	 * @param string|bool $wiki The name of the wiki to load the revision text from
	 *   (same as the the wiki $row was loaded from) or false to indicate the local
	 *   wiki (this is the default). Otherwise, it must be a symbolic wiki database
	 *   identifier as understood by the LoadBalancer class.
	 * @return string Text the text requested or false on failure
	 */
	public static function getRevisionText( $row, $prefix = 'old_', $wiki = false ) {

		# Get data
		$textField = $prefix . 'text';
		$flagsField = $prefix . 'flags';

		if ( isset( $row->$flagsField ) ) {
			$flags = explode( ',', $row->$flagsField );
		} else {
			$flags = [];
		}

		if ( isset( $row->$textField ) ) {
			$text = $row->$textField;
		} else {
			return false;
		}

		# Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url = $text;
			$parts = explode( '://', $url, 2 );
			if ( count( $parts ) == 1 || $parts[1] == '' ) {
				return false;
			}
			$text = ExternalStore::fetchFromURL( $url, [ 'wiki' => $wiki ] );
		}

		// If the text was fetched without an error, convert it
		if ( $text !== false ) {
			$text = self::decompressRevisionText( $text, $flags );
		}
		return $text;
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @param mixed $text Reference to a text
	 * @return string
	 */
	public static function compressRevisionText( &$text ) {
		global $wgCompressRevisions;
		$flags = [];

		# Revisions not marked this way will be converted
		# on load if $wgLegacyCharset is set in the future.
		$flags[] = 'utf-8';

		if ( $wgCompressRevisions ) {
			if ( function_exists( 'gzdeflate' ) ) {
				$deflated = gzdeflate( $text );

				if ( $deflated === false ) {
					wfLogWarning( __METHOD__ . ': gzdeflate() failed' );
				} else {
					$text = $deflated;
					$flags[] = 'gzip';
				}
			} else {
				wfDebug( __METHOD__ . " -- no zlib support, not compressing\n" );
			}
		}
		return implode( ',', $flags );
	}

	/**
	 * Re-converts revision text according to it's flags.
	 *
	 * @param mixed $text Reference to a text
	 * @param array $flags Compression flags
	 * @return string|bool Decompressed text, or false on failure
	 */
	public static function decompressRevisionText( $text, $flags ) {
		if ( in_array( 'gzip', $flags ) ) {
			# Deal with optional compression of archived pages.
			# This can be done periodically via maintenance/compressOld.php, and
			# as pages are saved if $wgCompressRevisions is set.
			$text = gzinflate( $text );

			if ( $text === false ) {
				wfLogWarning( __METHOD__ . ': gzinflate() failed' );
				return false;
			}
		}

		if ( in_array( 'object', $flags ) ) {
			# Generic compressed storage
			$obj = unserialize( $text );
			if ( !is_object( $obj ) ) {
				// Invalid object
				return false;
			}
			$text = $obj->getText();
		}

		global $wgLegacyEncoding;
		if ( $text !== false && $wgLegacyEncoding
			&& !in_array( 'utf-8', $flags ) && !in_array( 'utf8', $flags )
		) {
			# Old revisions kept around in a legacy encoding?
			# Upconvert on demand.
			# ("utf8" checked for compatibility with some broken
			#  conversion scripts 2008-12-30)
			global $wgContLang;
			$text = $wgContLang->iconv( $wgLegacyEncoding, 'UTF-8', $text );
		}

		return $text;
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @param IDatabase $dbw (master connection)
	 * @throws MWException
	 * @return int
	 */
	public function insertOn( $dbw ) {
		global $wgDefaultExternalStore, $wgContentHandlerUseDB;

		// We're inserting a new revision, so we have to use master anyway.
		// If it's a null revision, it may have references to rows that
		// are not in the replica yet (the text row).
		$this->mQueryFlags |= self::READ_LATEST;

		// Not allowed to have rev_page equal to 0, false, etc.
		if ( !$this->mPage ) {
			$title = $this->getTitle();
			if ( $title instanceof Title ) {
				$titleText = ' for page ' . $title->getPrefixedText();
			} else {
				$titleText = '';
			}
			throw new MWException( "Cannot insert revision$titleText: page ID must be nonzero" );
		}

		$this->checkContentModel();

		$data = $this->mText;
		$flags = self::compressRevisionText( $data );

		# Write to external storage if required
		if ( $wgDefaultExternalStore ) {
			// Store and get the URL
			$data = ExternalStore::insertToDefault( $data );
			if ( !$data ) {
				throw new MWException( "Unable to store text to external storage" );
			}
			if ( $flags ) {
				$flags .= ',';
			}
			$flags .= 'external';
		}

		# Record the text (or external storage URL) to the text table
		if ( $this->mTextId === null ) {
			$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert( 'text',
				[
					'old_id' => $old_id,
					'old_text' => $data,
					'old_flags' => $flags,
				], __METHOD__
			);
			$this->mTextId = $dbw->insertId();
		}

		if ( $this->mComment === null ) {
			$this->mComment = "";
		}

		# Record the edit in revisions
		$rev_id = $this->mId !== null
			? $this->mId
			: $dbw->nextSequenceValue( 'revision_rev_id_seq' );
		$row = [
			'rev_id'         => $rev_id,
			'rev_page'       => $this->mPage,
			'rev_text_id'    => $this->mTextId,
			'rev_comment'    => $this->mComment,
			'rev_minor_edit' => $this->mMinorEdit ? 1 : 0,
			'rev_user'       => $this->mUser,
			'rev_user_text'  => $this->mUserText,
			'rev_timestamp'  => $dbw->timestamp( $this->mTimestamp ),
			'rev_deleted'    => $this->mDeleted,
			'rev_len'        => $this->mSize,
			'rev_parent_id'  => $this->mParentId === null
				? $this->getPreviousRevisionId( $dbw )
				: $this->mParentId,
			'rev_sha1'       => $this->mSha1 === null
				? Revision::base36Sha1( $this->mText )
				: $this->mSha1,
		];

		if ( $wgContentHandlerUseDB ) {
			// NOTE: Store null for the default model and format, to save space.
			// XXX: Makes the DB sensitive to changed defaults.
			// Make this behavior optional? Only in miser mode?

			$model = $this->getContentModel();
			$format = $this->getContentFormat();

			$title = $this->getTitle();

			if ( $title === null ) {
				throw new MWException( "Insufficient information to determine the title of the "
					. "revision's page!" );
			}

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultFormat = ContentHandler::getForModelID( $defaultModel )->getDefaultFormat();

			$row['rev_content_model'] = ( $model === $defaultModel ) ? null : $model;
			$row['rev_content_format'] = ( $format === $defaultFormat ) ? null : $format;
		}

		$dbw->insert( 'revision', $row, __METHOD__ );

		$this->mId = $rev_id !== null ? $rev_id : $dbw->insertId();

		// Assertion to try to catch T92046
		if ( (int)$this->mId === 0 ) {
			throw new UnexpectedValueException(
				'After insert, Revision mId is ' . var_export( $this->mId, 1 ) . ': ' .
					var_export( $row, 1 )
			);
		}

		Hooks::run( 'RevisionInsertComplete', [ &$this, $data, $flags ] );

		return $this->mId;
	}

	protected function checkContentModel() {
		global $wgContentHandlerUseDB;

		// Note: may return null for revisions that have not yet been inserted
		$title = $this->getTitle();

		$model = $this->getContentModel();
		$format = $this->getContentFormat();
		$handler = $this->getContentHandler();

		if ( !$handler->isSupportedFormat( $format ) ) {
			$t = $title->getPrefixedDBkey();

			throw new MWException( "Can't use format $format with content model $model on $t" );
		}

		if ( !$wgContentHandlerUseDB && $title ) {
			// if $wgContentHandlerUseDB is not set,
			// all revisions must use the default content model and format.

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultHandler = ContentHandler::getForModelID( $defaultModel );
			$defaultFormat = $defaultHandler->getDefaultFormat();

			if ( $this->getContentModel() != $defaultModel ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't save non-default content model with "
					. "\$wgContentHandlerUseDB disabled: model is $model, "
					. "default for $t is $defaultModel" );
			}

			if ( $this->getContentFormat() != $defaultFormat ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't use non-default content format with "
					. "\$wgContentHandlerUseDB disabled: format is $format, "
					. "default for $t is $defaultFormat" );
			}
		}

		$content = $this->getContent( Revision::RAW );
		$prefixedDBkey = $title->getPrefixedDBkey();
		$revId = $this->mId;

		if ( !$content ) {
			throw new MWException(
				"Content of revision $revId ($prefixedDBkey) could not be loaded for validation!"
			);
		}
		if ( !$content->isValid() ) {
			throw new MWException(
				"Content of revision $revId ($prefixedDBkey) is not valid! Content model is $model"
			);
		}
	}

	/**
	 * Get the base 36 SHA-1 value for a string of text
	 * @param string $text
	 * @return string
	 */
	public static function base36Sha1( $text ) {
		return Wikimedia\base_convert( sha1( $text ), 16, 36, 31 );
	}

	/**
	 * Lazy-load the revision's text.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * @return string|bool The revision's text, or false on failure
	 */
	private function loadText() {
		global $wgRevisionCacheExpiry;

		$cache = ObjectCache::getMainWANInstance();
		if ( $cache->getQoS( $cache::ATTR_EMULATION ) <= $cache::QOS_EMULATION_SQL ) {
			// Do not cache RDBMs blobs in...the RDBMs store
			$ttl = $cache::TTL_UNCACHEABLE;
		} else {
			$ttl = $wgRevisionCacheExpiry ?: $cache::TTL_UNCACHEABLE;
		}

		// No negative caching; negative hits on text rows may be due to corrupted replica DBs
		return $cache->getWithSetCallback(
			$cache->makeKey( 'revisiontext', 'textid', $this->getTextId() ),
			$ttl,
			function () {
				return $this->fetchText();
			},
			[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => $cache::TTL_PROC_LONG ]
		);
	}

	private function fetchText() {
		$textId = $this->getTextId();

		// If we kept data for lazy extraction, use it now...
		if ( $this->mTextRow !== null ) {
			$row = $this->mTextRow;
			$this->mTextRow = null;
		} else {
			$row = null;
		}

		// Callers doing updates will pass in READ_LATEST as usual. Since the text/blob tables
		// do not normally get rows changed around, set READ_LATEST_IMMUTABLE in those cases.
		$flags = $this->mQueryFlags;
		$flags |= DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST )
			? self::READ_LATEST_IMMUTABLE
			: 0;

		list( $index, $options, $fallbackIndex, $fallbackOptions ) =
			DBAccessObjectUtils::getDBOptions( $flags );

		if ( !$row ) {
			// Text data is immutable; check replica DBs first.
			$row = wfGetDB( $index )->selectRow(
				'text',
				[ 'old_text', 'old_flags' ],
				[ 'old_id' => $textId ],
				__METHOD__,
				$options
			);
		}

		// Fallback to DB_MASTER in some cases if the row was not found
		if ( !$row && $fallbackIndex !== null ) {
			// Use FOR UPDATE if it was used to fetch this revision. This avoids missing the row
			// due to REPEATABLE-READ. Also fallback to the master if READ_LATEST is provided.
			$row = wfGetDB( $fallbackIndex )->selectRow(
				'text',
				[ 'old_text', 'old_flags' ],
				[ 'old_id' => $textId ],
				__METHOD__,
				$fallbackOptions
			);
		}

		if ( !$row ) {
			wfDebugLog( 'Revision', "No text row with ID '$textId' (revision {$this->getId()})." );
		}

		$text = self::getRevisionText( $row );
		if ( $row && $text === false ) {
			wfDebugLog( 'Revision', "No blob for text row '$textId' (revision {$this->getId()})." );
		}

		return is_string( $text ) ? $text : false;
	}

	/**
	 * Create a new null-revision for insertion into a page's
	 * history. This will not re-save the text, but simply refer
	 * to the text from the previous version.
	 *
	 * Such revisions can for instance identify page rename
	 * operations and other such meta-modifications.
	 *
	 * @param IDatabase $dbw
	 * @param int $pageId ID number of the page to read from
	 * @param string $summary Revision's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User|null $user User object to use or null for $wgUser
	 * @return Revision|null Revision or null on error
	 */
	public static function newNullRevision( $dbw, $pageId, $summary, $minor, $user = null ) {
		global $wgContentHandlerUseDB, $wgContLang;

		$fields = [ 'page_latest', 'page_namespace', 'page_title',
						'rev_text_id', 'rev_len', 'rev_sha1' ];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'rev_content_model';
			$fields[] = 'rev_content_format';
		}

		$current = $dbw->selectRow(
			[ 'page', 'revision' ],
			$fields,
			[
				'page_id' => $pageId,
				'page_latest=rev_id',
			],
			__METHOD__,
			[ 'FOR UPDATE' ] // T51581
		);

		if ( $current ) {
			if ( !$user ) {
				global $wgUser;
				$user = $wgUser;
			}

			// Truncate for whole multibyte characters
			$summary = $wgContLang->truncate( $summary, 255 );

			$row = [
				'page'       => $pageId,
				'user_text'  => $user->getName(),
				'user'       => $user->getId(),
				'comment'    => $summary,
				'minor_edit' => $minor,
				'text_id'    => $current->rev_text_id,
				'parent_id'  => $current->page_latest,
				'len'        => $current->rev_len,
				'sha1'       => $current->rev_sha1
			];

			if ( $wgContentHandlerUseDB ) {
				$row['content_model'] = $current->rev_content_model;
				$row['content_format'] = $current->rev_content_format;
			}

			$row['title'] = Title::makeTitle( $current->page_namespace, $current->page_title );

			$revision = new Revision( $row );
		} else {
			$revision = null;
		}

		return $revision;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	public function userCan( $field, User $user = null ) {
		return self::userCanBitfield( $this->getVisibility(), $field, $user );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted. This is used
	 * by various classes to avoid duplication.
	 *
	 * @param int $bitfield Current field
	 * @param int $field One of self::DELETED_TEXT = File::DELETED_FILE,
	 *                               self::DELETED_COMMENT = File::DELETED_COMMENT,
	 *                               self::DELETED_USER = File::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @param Title|null $title A Title object to check for per-page restrictions on,
	 *                          instead of just plain userrights
	 * @return bool
	 */
	public static function userCanBitfield( $bitfield, $field, User $user = null,
		Title $title = null
	) {
		if ( $bitfield & $field ) { // aspect is deleted
			if ( $user === null ) {
				global $wgUser;
				$user = $wgUser;
			}
			if ( $bitfield & self::DELETED_RESTRICTED ) {
				$permissions = [ 'suppressrevision', 'viewsuppressed' ];
			} elseif ( $field & self::DELETED_TEXT ) {
				$permissions = [ 'deletedtext' ];
			} else {
				$permissions = [ 'deletedhistory' ];
			}
			$permissionlist = implode( ', ', $permissions );
			if ( $title === null ) {
				wfDebug( "Checking for $permissionlist due to $field match on $bitfield\n" );
				return call_user_func_array( [ $user, 'isAllowedAny' ], $permissions );
			} else {
				$text = $title->getPrefixedText();
				wfDebug( "Checking for $permissionlist on $text due to $field match on $bitfield\n" );
				foreach ( $permissions as $perm ) {
					if ( $title->userCan( $perm, $user ) ) {
						return true;
					}
				}
				return false;
			}
		} else {
			return true;
		}
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row
	 *
	 * @param Title $title
	 * @param int $id
	 * @return string|bool False if not found
	 */
	static function getTimestampFromId( $title, $id, $flags = 0 ) {
		$db = ( $flags & self::READ_LATEST )
			? wfGetDB( DB_MASTER )
			: wfGetDB( DB_REPLICA );
		// Casting fix for databases that can't take '' for rev_id
		if ( $id == '' ) {
			$id = 0;
		}
		$conds = [ 'rev_id' => $id ];
		$conds['rev_page'] = $title->getArticleID();
		$timestamp = $db->selectField( 'revision', 'rev_timestamp', $conds, __METHOD__ );

		return ( $timestamp !== false ) ? wfTimestamp( TS_MW, $timestamp ) : false;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param IDatabase $db
	 * @param int $id Page id
	 * @return int
	 */
	static function countByPageId( $db, $id ) {
		$row = $db->selectRow( 'revision', [ 'revCount' => 'COUNT(*)' ],
			[ 'rev_page' => $id ], __METHOD__ );
		if ( $row ) {
			return $row->revCount;
		}
		return 0;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @return int
	 */
	static function countByTitle( $db, $title ) {
		$id = $title->getArticleID();
		if ( $id ) {
			return self::countByPageId( $db, $id );
		}
		return 0;
	}

	/**
	 * Check if no edits were made by other users since
	 * the time a user started editing the page. Limit to
	 * 50 revisions for the sake of performance.
	 *
	 * @since 1.20
	 * @deprecated since 1.24
	 *
	 * @param IDatabase|int $db The Database to perform the check on. May be given as a
	 *        Database object or a database identifier usable with wfGetDB.
	 * @param int $pageId The ID of the page in question
	 * @param int $userId The ID of the user in question
	 * @param string $since Look at edits since this time
	 *
	 * @return bool True if the given user was the only one to edit since the given timestamp
	 */
	public static function userWasLastToEdit( $db, $pageId, $userId, $since ) {
		if ( !$userId ) {
			return false;
		}

		if ( is_int( $db ) ) {
			$db = wfGetDB( $db );
		}

		$res = $db->select( 'revision',
			'rev_user',
			[
				'rev_page' => $pageId,
				'rev_timestamp > ' . $db->addQuotes( $db->timestamp( $since ) )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 50 ] );
		foreach ( $res as $row ) {
			if ( $row->rev_user != $userId ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Load a revision based on a known page ID and current revision ID from the DB
	 *
	 * This method allows for the use of caching, though accessing anything that normally
	 * requires permission checks (aside from the text) will trigger a small DB lookup.
	 * The title will also be lazy loaded, though setTitle() can be used to preload it.
	 *
	 * @param IDatabase $db
	 * @param int $pageId Page ID
	 * @param int $revId Known current revision of this page
	 * @return Revision|bool Returns false if missing
	 * @since 1.28
	 */
	public static function newKnownCurrent( IDatabase $db, $pageId, $revId ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			// Page/rev IDs passed in from DB to reflect history merges
			$cache->makeGlobalKey( 'revision', $db->getWikiID(), $pageId, $revId ),
			$cache::TTL_WEEK,
			function ( $curValue, &$ttl, array &$setOpts ) use ( $db, $pageId, $revId ) {
				$setOpts += Database::getCacheSetOptions( $db );

				$rev = Revision::loadFromPageId( $db, $pageId, $revId );
				// Reflect revision deletion and user renames
				if ( $rev ) {
					$rev->mTitle = null; // mutable; lazy-load
					$rev->mRefreshMutableFields = true;
				}

				return $rev ?: false; // don't cache negatives
			}
		);
	}

	/**
	 * For cached revisions, make sure the user name and rev_deleted is up-to-date
	 */
	private function loadMutableFields() {
		if ( !$this->mRefreshMutableFields ) {
			return; // not needed
		}

		$this->mRefreshMutableFields = false;
		$dbr = wfGetLB( $this->mWiki )->getConnectionRef( DB_REPLICA, [], $this->mWiki );
		$row = $dbr->selectRow(
			[ 'revision', 'user' ],
			[ 'rev_deleted', 'user_name' ],
			[ 'rev_id' => $this->mId, 'user_id = rev_user' ],
			__METHOD__
		);
		if ( $row ) { // update values
			$this->mDeleted = (int)$row->rev_deleted;
			$this->mUserText = $row->user_name;
		}
	}
}
