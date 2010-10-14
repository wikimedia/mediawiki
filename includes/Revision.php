<?php

/**
 * @todo document
 */
class Revision {
	const DELETED_TEXT = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;
	// Convenience field
	const SUPPRESSED_USER = 12;
	// Audience options for Revision::getText()
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;
	const RAW = 3;

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * @param $id Integer
	 * @return Revision or null
	 */
	public static function newFromId( $id ) {
		return Revision::newFromConds(
			array( 'page_id=rev_page',
			       'rev_id' => intval( $id ) ) );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given title. If not attached
	 * to that title, will return null.
	 *
	 * @param $title Title
	 * @param $id Integer
	 * @return Revision or null
	 */
	public static function newFromTitle( $title, $id = 0 ) {
		$conds = array( 
			'page_namespace' => $title->getNamespace(), 
			'page_title' => $title->getDBkey()
		);
		if ( $id ) {
			// Use the specified ID
			$conds['rev_id'] = $id;
		} elseif ( wfGetLB()->getServerCount() > 1 ) {
			// Get the latest revision ID from the master
			$dbw = wfGetDB( DB_MASTER );
			$latest = $dbw->selectField( 'page', 'page_latest', $conds, __METHOD__ );
			if ( $latest === false ) {
				// Page does not exist
				return null;
			}
			$conds['rev_id'] = $latest;
		} else {
			// Use a join to get the latest revision
			$conds[] = 'rev_id=page_latest';
		}
		$conds[] = 'page_id=rev_page';
		return Revision::newFromConds( $conds );
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 * @todo Fixme: should be a subclass for RevisionDelete. [TS]
	 */
	public static function newFromArchiveRow( $row, $overrides = array() ) {
		$attribs = $overrides + array(
			'page'       => isset( $row->page_id ) ? $row->page_id : null,
			'id'         => isset( $row->ar_rev_id ) ? $row->ar_rev_id : null,
			'comment'    => $row->ar_comment,
			'user'       => $row->ar_user,
			'user_text'  => $row->ar_user_text,
			'timestamp'  => $row->ar_timestamp,
			'minor_edit' => $row->ar_minor_edit,
			'text_id'    => isset( $row->ar_text_id ) ? $row->ar_text_id : null,
			'deleted'    => $row->ar_deleted,
			'len'        => $row->ar_len);
		if ( isset( $row->ar_text ) && !$row->ar_text_id ) {
			// Pre-1.5 ar_text row
			$attribs['text'] = self::getRevisionText( $row, 'ar_' );
		}
		return new self( $attribs );
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * @param $db DatabaseBase
	 * @param $id Integer
	 * @return Revision or null
	 */
	public static function loadFromId( $db, $id ) {
		return Revision::loadFromConds( $db,
			array( 'page_id=rev_page',
			       'rev_id' => intval( $id ) ) );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param $db DatabaseBase
	 * @param $pageid Integer
	 * @param $id Integer
	 * @return Revision or null
	 */
	public static function loadFromPageId( $db, $pageid, $id = 0 ) {
		$conds = array( 'page_id=rev_page','rev_page' => intval( $pageid ), 'page_id'=>intval( $pageid ) );
		if( $id ) {
			$conds['rev_id'] = intval( $id );
		} else {
			$conds[] = 'rev_id=page_latest';
		}
		return Revision::loadFromConds( $db, $conds );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param $db DatabaseBase
	 * @param $title Title
	 * @param $id Integer
	 * @return Revision or null
	 */
	public static function loadFromTitle( $db, $title, $id = 0 ) {
		if( $id ) {
			$matchId = intval( $id );
		} else {
			$matchId = 'page_latest';
		}
		return Revision::loadFromConds(
			$db,
			array( "rev_id=$matchId",
			       'page_id=rev_page',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDBkey() ) );
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * @param $db Database
	 * @param $title Title
	 * @param $timestamp String
	 * @return Revision or null
	 */
	public static function loadFromTimestamp( $db, $title, $timestamp ) {
		return Revision::loadFromConds(
			$db,
			array( 'rev_timestamp'  => $db->timestamp( $timestamp ),
			       'page_id=rev_page',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDBkey() ) );
	}

	/**
	 * Given a set of conditions, fetch a revision.
	 *
	 * @param $conditions Array
	 * @return Revision or null
	 */
	public static function newFromConds( $conditions ) {
		$db = wfGetDB( DB_SLAVE );
		$row = Revision::loadFromConds( $db, $conditions );
		if( is_null( $row ) && wfGetLB()->getServerCount() > 1 ) {
			$dbw = wfGetDB( DB_MASTER );
			$row = Revision::loadFromConds( $dbw, $conditions );
		}
		return $row;
	}

	/**
	 * Given a set of conditions, fetch a revision from
	 * the given database connection.
	 *
	 * @param $db Database
	 * @param $conditions Array
	 * @return Revision or null
	 */
	private static function loadFromConds( $db, $conditions ) {
		$res = Revision::fetchFromConds( $db, $conditions );
		if( $res ) {
			$row = $res->fetchObject();
			$res->free();
			if( $row ) {
				$ret = new Revision( $row );
				return $ret;
			}
		}
		$ret = null;
		return $ret;
	}

	/**
	 * Return a wrapper for a series of database rows to
	 * fetch all of a given page's revisions in turn.
	 * Each row can be fed to the constructor to get objects.
	 *
	 * @param $title Title
	 * @return ResultWrapper
	 */
	public static function fetchRevision( $title ) {
		return Revision::fetchFromConds(
			wfGetDB( DB_SLAVE ),
			array( 'rev_id=page_latest',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDBkey(),
			       'page_id=rev_page' ) );
	}

	/**
	 * Given a set of conditions, return a ResultWrapper
	 * which will return matching database rows with the
	 * fields necessary to build Revision objects.
	 *
	 * @param $db Database
	 * @param $conditions Array
	 * @return ResultWrapper
	 */
	private static function fetchFromConds( $db, $conditions ) {
		$fields = self::selectFields();
		$fields[] = 'page_namespace';
		$fields[] = 'page_title';
		$fields[] = 'page_latest';
		$res = $db->select(
			array( 'page', 'revision' ),
			$fields,
			$conditions,
			__METHOD__,
			array( 'LIMIT' => 1 ) );
		$ret = $db->resultObject( $res );
		return $ret;
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision.
	 */
	static function selectFields() {
		return array(
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_comment',
			'rev_user_text,'.
			'rev_user',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id'
		);
	}
	
	/**
	 * Return the list of text fields that should be selected to read the 
	 * revision text
	 */
	static function selectTextFields() {
		return array(
			'old_text',
			'old_flags'
		);
	}

	/**
	 * Return the list of page fields that should be selected from page table
	 */
	static function selectPageFields() {
		return array(
			'page_namespace',
			'page_title',
			'page_latest'
		);
	}

	/**
	 * Constructor
	 *
	 * @param $row Mixed: either a database row or an array
	 * @access private
	 */
	function __construct( $row ) {
		if( is_object( $row ) ) {
			$this->mId        = intval( $row->rev_id );
			$this->mPage      = intval( $row->rev_page );
			$this->mTextId    = intval( $row->rev_text_id );
			$this->mComment   =         $row->rev_comment;
			$this->mUserText  =         $row->rev_user_text;
			$this->mUser      = intval( $row->rev_user );
			$this->mMinorEdit = intval( $row->rev_minor_edit );
			$this->mTimestamp =         $row->rev_timestamp;
			$this->mDeleted   = intval( $row->rev_deleted );

			if( !isset( $row->rev_parent_id ) )
				$this->mParentId = is_null($row->rev_parent_id) ? null : 0;
			else
				$this->mParentId  = intval( $row->rev_parent_id );

			if( !isset( $row->rev_len ) || is_null( $row->rev_len ) )
				$this->mSize = null;
			else
				$this->mSize = intval( $row->rev_len );

			if( isset( $row->page_latest ) ) {
				$this->mCurrent = ( $row->rev_id == $row->page_latest );
				$this->mTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->mTitle->resetArticleID( $this->mPage );
			} else {
				$this->mCurrent = false;
				$this->mTitle = null;
			}

			// Lazy extraction...
			$this->mText      = null;
			if( isset( $row->old_text ) ) {
				$this->mTextRow = $row;
			} else {
				// 'text' table row entry will be lazy-loaded
				$this->mTextRow = null;
			}
		} elseif( is_array( $row ) ) {
			// Build a new revision to be saved...
			global $wgUser;

			$this->mId        = isset( $row['id']         ) ? intval( $row['id']         ) : null;
			$this->mPage      = isset( $row['page']       ) ? intval( $row['page']       ) : null;
			$this->mTextId    = isset( $row['text_id']    ) ? intval( $row['text_id']    ) : null;
			$this->mUserText  = isset( $row['user_text']  ) ? strval( $row['user_text']  ) : $wgUser->getName();
			$this->mUser      = isset( $row['user']       ) ? intval( $row['user']       ) : $wgUser->getId();
			$this->mMinorEdit = isset( $row['minor_edit'] ) ? intval( $row['minor_edit'] ) : 0;
			$this->mTimestamp = isset( $row['timestamp']  ) ? strval( $row['timestamp']  ) : wfTimestamp( TS_MW );
			$this->mDeleted   = isset( $row['deleted']    ) ? intval( $row['deleted']    ) : 0;
			$this->mSize      = isset( $row['len']        ) ? intval( $row['len']        ) : null;
			$this->mParentId  = isset( $row['parent_id']  ) ? intval( $row['parent_id']  ) : null;

			// Enforce spacing trimming on supplied text
			$this->mComment   = isset( $row['comment']    ) ?  trim( strval( $row['comment'] ) ) : null;
			$this->mText      = isset( $row['text']       ) ? rtrim( strval( $row['text']    ) ) : null;
			$this->mTextRow   = null;

			$this->mTitle     = null; # Load on demand if needed
			$this->mCurrent   = false;
			# If we still have no len_size, see it we have the text to figure it out
			if ( !$this->mSize )
				$this->mSize      = is_null( $this->mText ) ? null : strlen( $this->mText );
		} else {
			throw new MWException( 'Revision constructor passed invalid row format.' );
		}
		$this->mUnpatrolled = null;
	}

	/**
	 * Get revision ID
	 *
	 * @return Integer
	 */
	public function getId() {
		return $this->mId;
	}

	/**
	 * Get text row ID
	 *
	 * @return Integer
	 */
	public function getTextId() {
		return $this->mTextId;
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return Integer
	 */
	public function getParentId() {
		return $this->mParentId;
	}

	/**
	 * Returns the length of the text in this revision, or null if unknown.
	 *
	 * @return Integer
	 */
	public function getSize() {
		return $this->mSize;
	}

	/**
	 * Returns the title of the page associated with this entry.
	 *
	 * @return Title
	 */
	public function getTitle() {
		if( isset( $this->mTitle ) ) {
			return $this->mTitle;
		}
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow(
			array( 'page', 'revision' ),
			array( 'page_namespace', 'page_title' ),
			array( 'page_id=rev_page',
			       'rev_id' => $this->mId ),
			'Revision::getTitle' );
		if( $row ) {
			$this->mTitle = Title::makeTitle( $row->page_namespace,
			                                  $row->page_title );
		}
		return $this->mTitle;
	}

	/**
	 * Set the title of the revision
	 *
	 * @param $title Title
	 */
	public function setTitle( $title ) {
		$this->mTitle = $title;
	}

	/**
	 * Get the page ID
	 *
	 * @return Integer
	 */
	public function getPage() {
		return $this->mPage;
	}

	/**
	 * Fetch revision's user id if it's available to the specified audience.
	 * If the specified audience does not have access to it, zero will be 
	 * returned.
	 *
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the ID regardless of permissions
	 *
	 *
	 * @return Integer
	 */
	public function getUser( $audience = self::FOR_PUBLIC ) {
		if( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return 0;
		} elseif( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER ) ) {
			return 0;
		} else {
			return $this->mUser;
		}
	}

	/**
	 * Fetch revision's user id without regard for the current user's permissions
	 *
	 * @return String
	 */
	public function getRawUser() {
		return $this->mUser;
	}

	/**
	 * Fetch revision's username if it's available to the specified audience.
	 * If the specified audience does not have access to the username, an 
	 * empty string will be returned.
	 *
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 *
	 * @return string
	 */
	public function getUserText( $audience = self::FOR_PUBLIC ) {
		if( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return '';
		} elseif( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER ) ) {
			return '';
		} else {
			return $this->mUserText;
		}
	}

	/**
	 * Fetch revision's username without regard for view restrictions
	 *
	 * @return String
	 */
	public function getRawUserText() {
		return $this->mUserText;
	}

	/**
	 * Fetch revision comment if it's available to the specified audience.
	 * If the specified audience does not have access to the comment, an 
	 * empty string will be returned.
	 *
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 *
	 * @return String
	 */
	function getComment( $audience = self::FOR_PUBLIC ) {
		if( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_COMMENT ) ) {
			return '';
		} elseif( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_COMMENT ) ) {
			return '';
		} else {
			return $this->mComment;
		}
	}

	/**
	 * Fetch revision comment without regard for the current user's permissions
	 *
	 * @return String
	 */
	public function getRawComment() {
		return $this->mComment;
	}

	/**
	 * @return Boolean
	 */
	public function isMinor() {
		return (bool)$this->mMinorEdit;
	}
	
	/**
	 * @return Integer rcid of the unpatrolled row, zero if there isn't one
	 */
	public function isUnpatrolled() {
		if( $this->mUnpatrolled !== null ) {
			return $this->mUnpatrolled;
		}
		$dbr = wfGetDB( DB_SLAVE );
		$this->mUnpatrolled = $dbr->selectField( 'recentchanges',
			'rc_id',
			array( // Add redundant user,timestamp condition so we can use the existing index
				'rc_user_text'  => $this->getRawUserText(),
				'rc_timestamp'  => $dbr->timestamp( $this->getTimestamp() ),
				'rc_this_oldid' => $this->getId(),
				'rc_patrolled'  => 0
			),
			__METHOD__
		);
		return (int)$this->mUnpatrolled;
	}

	/**
	 * int $field one of DELETED_* bitfield constants
	 *
	 * @return Boolean
	 */
	public function isDeleted( $field ) {
		return ( $this->mDeleted & $field ) == $field;
	}

	/**
	 * Get the deletion bitfield of the revision
	 */
	public function getVisibility() {
		return (int)$this->mDeleted;
	}

	/**
	 * Fetch revision text if it's available to the specified audience.
	 * If the specified audience does not have the ability to view this 
	 * revision, an empty string will be returned.
	 *
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 *
	 *
	 * @return String
	 */
	public function getText( $audience = self::FOR_PUBLIC ) {
		if( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_TEXT ) ) {
			return '';
		} elseif( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_TEXT ) ) {
			return '';
		} else {
			return $this->getRawText();
		}
	}

	/**
	 * Alias for getText(Revision::FOR_THIS_USER)
	 *
	 * @deprecated
	 * @return String
	 */
	public function revText() {
		return $this->getText( self::FOR_THIS_USER );
	}

	/**
	 * Fetch revision text without regard for view restrictions
	 *
	 * @return String
	 */
	public function getRawText() {
		if( is_null( $this->mText ) ) {
			// Revision text is immutable. Load on demand:
			$this->mText = $this->loadText();
		}
		return $this->mText;
	}

	/**
	 * @return String
	 */
	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->mTimestamp );
	}

	/**
	 * @return Boolean
	 */
	public function isCurrent() {
		return $this->mCurrent;
	}

	/**
	 * Get previous revision for this title
	 *
	 * @return Revision or null
	 */
	public function getPrevious() {
		if( $this->getTitle() ) {
			$prev = $this->getTitle()->getPreviousRevisionID( $this->getId() );
			if( $prev ) {
				return Revision::newFromTitle( $this->getTitle(), $prev );
			}
		}
		return null;
	}

	/**
	 * Get next revision for this title
	 *
	 * @return Revision or null
	 */
	public function getNext() {
		if( $this->getTitle() ) {
			$next = $this->getTitle()->getNextRevisionID( $this->getId() );
			if ( $next ) {
				return Revision::newFromTitle( $this->getTitle(), $next );
			}
		}
		return null;
	}

	/**
	 * Get previous revision Id for this page_id
	 * This is used to populate rev_parent_id on save
	 *
	 * @param $db DatabaseBase
	 * @return Integer
	 */
	private function getPreviousRevisionId( $db ) {
		if( is_null( $this->mPage ) ) {
			return 0;
		}
		# Use page_latest if ID is not given
		if( !$this->mId ) {
			$prevId = $db->selectField( 'page', 'page_latest',
				array( 'page_id' => $this->mPage ),
				__METHOD__ );
		} else {
			$prevId = $db->selectField( 'revision', 'rev_id',
				array( 'rev_page' => $this->mPage, 'rev_id < ' . $this->mId ),
				__METHOD__,
				array( 'ORDER BY' => 'rev_id DESC' ) );
		}
		return intval( $prevId );
	}

	/**
	  * Get revision text associated with an old or archive row
	  * $row is usually an object from wfFetchRow(), both the flags and the text
	  * field must be included
	  *
	  * @param $row Object: the text data
	  * @param $prefix String: table prefix (default 'old_')
	  * @return String: text the text requested or false on failure
	  */
	public static function getRevisionText( $row, $prefix = 'old_' ) {
		wfProfileIn( __METHOD__ );

		# Get data
		$textField = $prefix . 'text';
		$flagsField = $prefix . 'flags';

		if( isset( $row->$flagsField ) ) {
			$flags = explode( ',', $row->$flagsField );
		} else {
			$flags = array();
		}

		if( isset( $row->$textField ) ) {
			$text = $row->$textField;
		} else {
			wfProfileOut( __METHOD__ );
			return false;
		}

		# Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url = $text;
			@list(/* $proto */, $path ) = explode( '://', $url, 2 );
			if( $path == '' ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
			$text = ExternalStore::fetchFromURL( $url );
		}

		// If the text was fetched without an error, convert it
		if ( $text !== false ) {
			if( in_array( 'gzip', $flags ) ) {
				# Deal with optional compression of archived pages.
				# This can be done periodically via maintenance/compressOld.php, and
				# as pages are saved if $wgCompressRevisions is set.
				$text = gzinflate( $text );
			}

			if( in_array( 'object', $flags ) ) {
				# Generic compressed storage
				$obj = unserialize( $text );
				if ( !is_object( $obj ) ) {
					// Invalid object
					wfProfileOut( __METHOD__ );
					return false;
				}
				$text = $obj->getText();
			}

			global $wgLegacyEncoding;
			if( $text !== false && $wgLegacyEncoding 
				&& !in_array( 'utf-8', $flags ) && !in_array( 'utf8', $flags ) ) 
			{
				# Old revisions kept around in a legacy encoding?
				# Upconvert on demand.
				# ("utf8" checked for compatibility with some broken
				#  conversion scripts 2008-12-30)
				global $wgInputEncoding, $wgContLang;
				$text = $wgContLang->iconv( $wgLegacyEncoding, $wgInputEncoding, $text );
			}
		}
		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @param $text Mixed: reference to a text
	 * @return String
	 */
	public static function compressRevisionText( &$text ) {
		global $wgCompressRevisions;
		$flags = array();

		# Revisions not marked this way will be converted
		# on load if $wgLegacyCharset is set in the future.
		$flags[] = 'utf-8';

		if( $wgCompressRevisions ) {
			if( function_exists( 'gzdeflate' ) ) {
				$text = gzdeflate( $text );
				$flags[] = 'gzip';
			} else {
				wfDebug( "Revision::compressRevisionText() -- no zlib support, not compressing\n" );
			}
		}
		return implode( ',', $flags );
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @param $dbw DatabaseBase (master connection)
	 * @return Integer
	 */
	public function insertOn( $dbw ) {
		global $wgDefaultExternalStore;

		wfProfileIn( __METHOD__ );

		$data = $this->mText;
		$flags = Revision::compressRevisionText( $data );

		# Write to external storage if required
		if( $wgDefaultExternalStore ) {
			// Store and get the URL
			$data = ExternalStore::insertToDefault( $data );
			if( !$data ) {
				throw new MWException( "Unable to store text to external storage" );
			}
			if( $flags ) {
				$flags .= ',';
			}
			$flags .= 'external';
		}

		# Record the text (or external storage URL) to the text table
		if( !isset( $this->mTextId ) ) {
			$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert( 'text',
				array(
					'old_id'    => $old_id,
					'old_text'  => $data,
					'old_flags' => $flags,
				), __METHOD__
			);
			$this->mTextId = $dbw->insertId();
		}

		if ( $this->mComment === null ) $this->mComment = "";

		# Record the edit in revisions
		$rev_id = isset( $this->mId )
			? $this->mId
			: $dbw->nextSequenceValue( 'revision_rev_id_seq' );
		$dbw->insert( 'revision',
			array(
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
				'rev_parent_id'  => is_null($this->mParentId) ?
					$this->getPreviousRevisionId( $dbw ) : $this->mParentId
			), __METHOD__
		);

		$this->mId = !is_null( $rev_id ) ? $rev_id : $dbw->insertId();

		wfRunHooks( 'RevisionInsertComplete', array( &$this, $data, $flags ) );

		wfProfileOut( __METHOD__ );
		return $this->mId;
	}

	/**
	 * Lazy-load the revision's text.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * @return String
	 */
	protected function loadText() {
		wfProfileIn( __METHOD__ );

		// Caching may be beneficial for massive use of external storage
		global $wgRevisionCacheExpiry, $wgMemc;
		$textId = $this->getTextId();
		$key = wfMemcKey( 'revisiontext', 'textid', $textId );
		if( $wgRevisionCacheExpiry ) {
			$text = $wgMemc->get( $key );
			if( is_string( $text ) ) {
				wfDebug( __METHOD__ . ": got id $textId from cache\n" );
				wfProfileOut( __METHOD__ );
				return $text;
			}
		}

		// If we kept data for lazy extraction, use it now...
		if ( isset( $this->mTextRow ) ) {
			$row = $this->mTextRow;
			$this->mTextRow = null;
		} else {
			$row = null;
		}

		if( !$row ) {
			// Text data is immutable; check slaves first.
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow( 'text',
				array( 'old_text', 'old_flags' ),
				array( 'old_id' => $this->getTextId() ),
				__METHOD__ );
		}

		if( !$row && wfGetLB()->getServerCount() > 1 ) {
			// Possible slave lag!
			$dbw = wfGetDB( DB_MASTER );
			$row = $dbw->selectRow( 'text',
				array( 'old_text', 'old_flags' ),
				array( 'old_id' => $this->getTextId() ),
				__METHOD__ );
		}

		$text = self::getRevisionText( $row );

		# No negative caching -- negative hits on text rows may be due to corrupted slave servers
		if( $wgRevisionCacheExpiry && $text !== false ) {
			$wgMemc->set( $key, $text, $wgRevisionCacheExpiry );
		}

		wfProfileOut( __METHOD__ );

		return $text;
	}

	/**
	 * Create a new null-revision for insertion into a page's
	 * history. This will not re-save the text, but simply refer
	 * to the text from the previous version.
	 *
	 * Such revisions can for instance identify page rename
	 * operations and other such meta-modifications.
	 *
	 * @param $dbw DatabaseBase
	 * @param $pageId Integer: ID number of the page to read from
	 * @param $summary String: revision's summary
	 * @param $minor Boolean: whether the revision should be considered as minor
	 * @return Mixed: Revision, or null on error
	 */
	public static function newNullRevision( $dbw, $pageId, $summary, $minor ) {
		wfProfileIn( __METHOD__ );

		$current = $dbw->selectRow(
			array( 'page', 'revision' ),
			array( 'page_latest', 'rev_text_id', 'rev_len' ),
			array(
				'page_id' => $pageId,
				'page_latest=rev_id',
				),
			__METHOD__ );

		if( $current ) {
			$revision = new Revision( array(
				'page'       => $pageId,
				'comment'    => $summary,
				'minor_edit' => $minor,
				'text_id'    => $current->rev_text_id,
				'parent_id'  => $current->page_latest,
				'len'        => $current->rev_len
				) );
		} else {
			$revision = null;
		}

		wfProfileOut( __METHOD__ );
		return $revision;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * @param $field Integer:one of self::DELETED_TEXT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @return Boolean
	 */
	public function userCan( $field ) {
		return self::userCanBitfield( $this->mDeleted, $field );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted. This is used
	 * by various classes to avoid duplication.
	 *
	 * @param $bitfield Integer: current field
	 * @param $field Integer: one of self::DELETED_TEXT = File::DELETED_FILE,
	 *                               self::DELETED_COMMENT = File::DELETED_COMMENT,
	 *                               self::DELETED_USER = File::DELETED_USER
	 * @return Boolean
	 */
	public static function userCanBitfield( $bitfield, $field ) {
		if( $bitfield & $field ) { // aspect is deleted
			global $wgUser;
			if ( $bitfield & self::DELETED_RESTRICTED ) {
				$permission = 'suppressrevision';
			} elseif ( $field & self::DELETED_TEXT ) {
				$permission = 'deletedtext';
			} else {
				$permission = 'deletedhistory';
			}
			wfDebug( "Checking for $permission due to $field match on $bitfield\n" );
			return $wgUser->isAllowed( $permission );
		} else {
			return true;
		}
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row
	 *
	 * @param $title Title
	 * @param $id Integer
	 * @return String
	 */
	static function getTimestampFromId( $title, $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		// Casting fix for DB2
		if ( $id == '' ) {
			$id = 0;
		}
		$conds = array( 'rev_id' => $id );
		$conds['rev_page'] = $title->getArticleId();
		$timestamp = $dbr->selectField( 'revision', 'rev_timestamp', $conds, __METHOD__ );
		if ( $timestamp === false && wfGetLB()->getServerCount() > 1 ) {
			# Not in slave, try master
			$dbw = wfGetDB( DB_MASTER );
			$timestamp = $dbw->selectField( 'revision', 'rev_timestamp', $conds, __METHOD__ );
		}
		return wfTimestamp( TS_MW, $timestamp );
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param $db DatabaseBase
	 * @param $id Integer: page id
	 * @return Integer
	 */
	static function countByPageId( $db, $id ) {
		$row = $db->selectRow( 'revision', 'COUNT(*) AS revCount',
			array( 'rev_page' => $id ), __METHOD__ );
		if( $row ) {
			return $row->revCount;
		}
		return 0;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param $db DatabaseBase
	 * @param $title Title
	 * @return Integer
	 */
	static function countByTitle( $db, $title ) {
		$id = $title->getArticleId();
		if( $id ) {
			return Revision::countByPageId( $db, $id );
		}
		return 0;
	}
}

/**
 * Aliases for backwards compatibility with 1.6
 */
define( 'MW_REV_DELETED_TEXT', Revision::DELETED_TEXT );
define( 'MW_REV_DELETED_COMMENT', Revision::DELETED_COMMENT );
define( 'MW_REV_DELETED_USER', Revision::DELETED_USER );
define( 'MW_REV_DELETED_RESTRICTED', Revision::DELETED_RESTRICTED );
