<?php
/**
 * @package MediaWiki
 * @todo document
 */

/** */
require_once( 'Database.php' );
require_once( 'Article.php' );

/**
 * @package MediaWiki
 * @todo document
 */
class Revision {
	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * @param int $id
	 * @static
	 * @access public
	 */
	function newFromId( $id ) {
		return Revision::newFromConds(
			array( 'page_id=rev_page',
			       'rev_id' => IntVal( $id ) ) );
	}
	
	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given title. If not attached
	 * to that title, will return null.
	 *
	 * @param Title $title
	 * @param int $id
	 * @return Revision
	 * @access public
	 * @static
	 */
	function newFromTitle( &$title, $id = 0 ) {
		if( $id ) {
			$matchId = IntVal( $id );
		} else {
			$matchId = 'page_latest';
		}
		return Revision::newFromConds(
			array( "rev_id=$matchId",
			       'page_id=rev_page',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey() ) );
	}
	
	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param Database $db
	 * @param int $pageid
	 * @param int $id
	 * @return Revision
	 * @access public
	 */
	function loadFromPageId( &$db, $pageid, $id = 0 ) {
		$conds=array('page_id=rev_page','rev_page'=>intval( $pageid ), 'page_id'=>intval( $pageid ));
		if( $id ) {
			$conds['rev_id']=intval($id);
		} else {
			$conds[]='rev_id=page_latest';
		}
		return Revision::loadFromConds( $db, $conds );
	}
	
	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param Database $db
	 * @param Title $title
	 * @param int $id
	 * @return Revision
	 * @access public
	 */
	function loadFromTitle( &$db, $title, $id = 0 ) {
		if( $id ) {
			$matchId = IntVal( $id );
		} else {
			$matchId = 'page_latest';
		}
		return Revision::loadFromConds(
			$db,
			array( "rev_id=$matchId",
			       'page_id=rev_page',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey() ) );
	}
	
	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * @param Database $db
	 * @param Title $title
	 * @param string $timestamp
	 * @return Revision
	 * @access public
	 * @static
	 */
	function loadFromTimestamp( &$db, &$title, $timestamp ) {
		return Revision::loadFromConds(
			$db,
			array( 'rev_timestamp'  => $db->timestamp( $timestamp ),
			       'page_id=rev_page',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey() ) );
	}
	
	/**
	 * Given a set of conditions, fetch a revision.
	 *
	 * @param array $conditions
	 * @return Revision
	 * @static
	 * @access private
	 */
	function newFromConds( $conditions ) {
		$db =& wfGetDB( DB_SLAVE );
		$row = Revision::loadFromConds( $db, $conditions );
		if( is_null( $row ) ) {
			$dbw =& wfGetDB( DB_MASTER );
			$row = Revision::loadFromConds( $dbw, $conditions );
		}
		return $row;
	}
	
	/**
	 * Given a set of conditions, fetch a revision from
	 * the given database connection.
	 *
	 * @param Database $db
	 * @param array $conditions
	 * @return Revision
	 * @static
	 * @access private
	 */
	function loadFromConds( &$db, $conditions ) {
		$res = Revision::fetchFromConds( $db, $conditions );
		if( $res ) {
			$row = $res->fetchObject();
			$res->free();
			if( $row ) {
				return new Revision( $row );
			}
		}
		return null;
	}
	
	/**
	 * Return a wrapper for a series of database rows to
	 * fetch all of a given page's revisions in turn.
	 * Each row can be fed to the constructor to get objects.
	 *
	 * @param Title $title
	 * @return ResultWrapper
	 * @static
	 * @access public
	 */
	function fetchAllRevisions( &$title ) {
		return Revision::fetchFromConds(
			wfGetDB( DB_SLAVE ),
			array( 'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey(),
			       'page_id=rev_page' ) );		
	}
	
	/**
	 * Return a wrapper for a series of database rows to
	 * fetch all of a given page's revisions in turn.
	 * Each row can be fed to the constructor to get objects.
	 *
	 * @param Title $title
	 * @return ResultWrapper
	 * @static
	 * @access public
	 */
	function fetchRevision( &$title ) {
		return Revision::fetchFromConds(
			wfGetDB( DB_SLAVE ),
			array( 'rev_id=page_latest',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey(),
			       'page_id=rev_page' ) );		
	}
	
	/**
	 * Given a set of conditions, return a ResultWrapper
	 * which will return matching database rows with the
	 * fields necessary to build Revision objects.
	 *
	 * @param Database $db
	 * @param array $conditions
	 * @return ResultWrapper
	 * @static
	 * @access private
	 */
	function fetchFromConds( &$db, $conditions ) {
		$res = $db->select(
			array( 'page', 'revision' ),
			array( 'page_namespace',
			       'page_title',
			       'page_latest',
			       'rev_id',
			       'rev_page',
			       'rev_text_id',
			       'rev_comment',
			       'rev_user_text',
			       'rev_user',
			       'rev_minor_edit',
			       'rev_timestamp',
			       'rev_deleted' ),
			$conditions,
			'Revision::fetchRow',
			array( 'LIMIT' => 1 ) );
		return $db->resultObject( $res );
	}
	
	/**
	 * @param object $row
	 * @access private
	 */
	function Revision( $row ) {
		if( is_object( $row ) ) {
			$this->mId        = IntVal( $row->rev_id );
			$this->mPage      = IntVal( $row->rev_page );
			$this->mTextId    = IntVal( $row->rev_text_id );
			$this->mComment   =         $row->rev_comment;
			$this->mUserText  =         $row->rev_user_text;
			$this->mUser      = IntVal( $row->rev_user );
			$this->mMinorEdit = IntVal( $row->rev_minor_edit );
			$this->mTimestamp =         $row->rev_timestamp;
			$this->mDeleted   = IntVal( $row->rev_deleted );
		
			$this->mCurrent   = ( $row->rev_id == $row->page_latest );
			$this->mTitle     = Title::makeTitle( $row->page_namespace,
			                                      $row->page_title );
			
			if( isset( $row->old_text ) ) {
				$this->mText  = $this->getRevisionText( $row );
			} else {
				$this->mText  = null;
			}
		} elseif( is_array( $row ) ) {
			// Build a new revision to be saved...
			global $wgUser;
			
			$this->mId        = isset( $row['id']         ) ? IntVal( $row['id']         ) : null;
			$this->mPage      = isset( $row['page']       ) ? IntVal( $row['page']       ) : null;
			$this->mTextId    = isset( $row['text_id']    ) ? IntVal( $row['text_id']    ) : null;
			$this->mUserText  = isset( $row['user_text']  ) ? StrVal( $row['user_text']  ) : $wgUser->getName();
			$this->mUser      = isset( $row['user']       ) ? IntVal( $row['user']       ) : $wgUser->getId();
			$this->mMinorEdit = isset( $row['minor_edit'] ) ? IntVal( $row['minor_edit'] ) : 0;
			$this->mTimestamp = isset( $row['timestamp']  ) ? StrVal( $row['timestamp']  ) : wfTimestamp( TS_MW );
			$this->mDeleted   = isset( $row['deleted']    ) ? IntVal( $row['deleted']    ) : 0;
			
			// Enforce spacing trimming on supplied text
			$this->mComment   = isset( $row['comment']    ) ?  trim( StrVal( $row['comment'] ) ) : null;
			$this->mText      = isset( $row['text']       ) ? rtrim( StrVal( $row['text']    ) ) : null;
			
			$this->mTitle     = null; # Load on demand if needed
			$this->mCurrent   = false;
		} else {
			wfDebugDieBacktrace( 'Revision constructor passed invalid row format.' );
		}
	}
	
	/**#@+
	 * @access public
	 */
	
	/**
	 * @return int
	 */
	function getId() {
		return $this->mId;
	}
	
	/**
	 * @return int
	 */
	function getTextId() {
		return $this->mTextId;
	}
	
	/**
	 * Returns the title of the page associated with this entry.
	 * @return Title
	 */
	function getTitle() {
		if( isset( $this->mTitle ) ) {
			return $this->mTitle;
		}
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow(
			array( 'page', 'revision' ),
			array( 'page_namespace', 'page_title' ),
			array( 'page_id=rev_page',
			       'rev_id' => $this->mId ),
			'Revision::getTItle' );
		if( $row ) {
			$this->mTitle = Title::makeTitle( $row->page_namespace,
			                                   $row->page_title );
		}
		return $this->mTitle;
	}
	
	/**
	 * @return int
	 */
	function getPage() {
		return $this->mPage;
	}
	
	/**
	 * @return int
	 */
	function getUser() {
		return $this->mUser;
	}
	
	/**
	 * @return string
	 */
	function getUserText() {
		return $this->mUserText;
	}
	
	/**
	 * @return string
	 */
	function getComment() {
		return $this->mComment;
	}
	
	/**
	 * @return bool
	 */
	function isMinor() {
		return (bool)$this->mMinorEdit;
	}
	
	/**
	 * @return bool
	 */
	function isDeleted() {
		return (bool)$this->mDeleted;
	}
	
	/**
	 * @return string
	 */
	function getText() {
		if( is_null( $this->mText ) ) {
			// Revision text is immutable. Load on demand:
			$this->mText = $this->loadText();
		}
		return $this->mText;
	}
	
	/**
	 * @return string
	 */
	function getTimestamp() {
		return $this->mTimestamp;
	}
	
	/**
	 * @return bool
	 */
	function isCurrent() {
		return $this->mCurrent;
	}
	
	/**
	 * @return Revision
	 */
	function getPrevious() {
		$prev = $this->mTitle->getPreviousRevisionID( $this->mId );
		return Revision::newFromTitle( $this->mTitle, $prev );
	}

	/**
	 * @return Revision
	 */
	function getNext() {
		$next = $this->mTitle->getNextRevisionID( $this->mId );
		return Revision::newFromTitle( $this->mTitle, $next );
	}
	/**#@-*/

	/**
	  * Get revision text associated with an old or archive row
	  * $row is usually an object from wfFetchRow(), both the flags and the text
	  * field must be included
	  * @static
	  * @param integer $row Id of a row
	  * @param string $prefix table prefix (default 'old_')
	  * @return string $text|false the text requested
	  */
	function getRevisionText( $row, $prefix = 'old_' ) {
		$fname = 'Revision::getRevisionText';
		wfProfileIn( $fname );
		
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
			wfProfileOut( $fname );
			return false;
		}

		# Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url=$text;
			@list($proto,$path)=explode('://',$url,2);
			if ($path=="") {
				wfProfileOut( $fname );
				return false;
			}
			require_once('ExternalStore.php');
			$text=ExternalStore::fetchFromURL($url);
		}

		if( in_array( 'gzip', $flags ) ) {
			# Deal with optional compression of archived pages.
			# This can be done periodically via maintenance/compressOld.php, and
			# as pages are saved if $wgCompressRevisions is set.
			$text = gzinflate( $text );
		}
			
		if( in_array( 'object', $flags ) ) {
			# Generic compressed storage
			$obj = unserialize( $text );

			# Bugger, corrupted my test database by double-serializing
			if ( !is_object( $obj ) ) {
				$obj = unserialize( $obj );
			}

			$text = $obj->getText();
		}
	
		global $wgLegacyEncoding;
		if( $wgLegacyEncoding && !in_array( 'utf-8', $flags ) ) {
			# Old revisions kept around in a legacy encoding?
			# Upconvert on demand.
			global $wgInputEncoding, $wgContLang;
			$text = $wgContLang->iconv( $wgLegacyEncoding, $wgInputEncoding, $text );
		}
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @static
	 * @param mixed $text reference to a text
	 * @return string
	 */
	function compressRevisionText( &$text ) {
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
	 * @param Database $dbw
	 * @return int
	 */
	function insertOn( &$dbw ) {
		$fname = 'Revision::insertOn';
		wfProfileIn( $fname );
		
		$mungedText = $this->mText;
		$flags = Revision::compressRevisionText( $mungedText );
		
		# Record the text to the text table
		if( !isset( $this->mTextId ) ) {
			$old_id = $dbw->nextSequenceValue( 'text_old_id_val' );
			$dbw->insert( 'text',
				array(
					'old_id'    => $old_id,
					'old_text'  => $mungedText,
					'old_flags' => $flags,
				), $fname
			);
			$this->mTextId = $dbw->insertId();
		}
		
		# Record the edit in revisions
		$rev_id = isset( $this->mId )
			? $this->mId
			: $dbw->nextSequenceValue( 'rev_rev_id_val' );
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
			), $fname
		);
		
		$this->mId = $dbw->insertId();
		
		wfProfileOut( $fname );
		return $this->mId;
	}
	
	/**
	 * Lazy-load the revision's text.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * @return string
	 * @access private
	 */
	function loadText() {
		$fname = 'Revision::loadText';
		wfProfileIn( $fname );
		
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'text',
			array( 'old_text', 'old_flags' ),
			array( 'old_id' => $this->getTextId() ),
			$fname);
		
		if( !$row ) {
			$dbw =& wfGetDB( DB_MASTER );
			$row = $dbw->selectRow( 'text',
				array( 'old_text', 'old_flags' ),
				array( 'old_id' => $this->getTextId() ),
				$fname);
		}
		
		$text = Revision::getRevisionText( $row );
		wfProfileOut( $fname );
		
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
	 * @param Database $dbw
	 * @param int      $pageId ID number of the page to read from
	 * @param string   $summary
	 * @param bool     $minor
	 * @return Revision
	 */
	function newNullRevision( &$dbw, $pageId, $summary, $minor ) {
		$fname = 'Revision::newNullRevision';
		wfProfileIn( $fname );
		
		$current = $dbw->selectRow(
			array( 'page', 'revision' ),
			array( 'page_latest', 'rev_text_id' ),
			array(
				'page_id' => $pageId,
				'page_latest=rev_id',
				),
			$fname );
		
		if( $current ) {
			$revision = new Revision( array(
				'page'       => $pageId,
				'comment'    => $summary,
				'minor_edit' => $minor,
				'text_id'    => $current->rev_text_id,
				) );
		} else {
			$revision = null;
		}
		
		wfProfileOut( $fname );
		return $revision;
	}
	
}
?>
