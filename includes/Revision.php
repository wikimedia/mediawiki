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
	function &newFromId( $id ) {
		return Revision::newFromConds(
			array( 'page_id=rev_page',
			       'rev_id' => IntVal( $id ),
			       'rev_id=old_id' ) );
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
	 */
	function &newFromTitle( &$title, $id = 0 ) {
		if( $id ) {
			$matchId = IntVal( $id );
		} else {
			$matchId = 'page_latest';
		}
		return Revision::newFromConds(
			array( "rev_id=$matchId",
			       'page_id=rev_page',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey(),
			       'rev_id=old_id' ) );
	}
	
	/**
	 * Given a set of conditions, fetch a revision.
	 *
	 * @param array $conditions
	 * @return Revision
	 * @static
	 * @access private
	 */
	function &newFromConds( $conditions ) {
		$res =& Revision::fetchFromConds( $conditions );
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
	function &fetchAllRevisions( &$title ) {
		return Revision::fetchFromConds(
			array( 'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey(),
			       'page_id=rev_page',
			       'rev_id=old_id' ) );		
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
	function &fetchRevision( &$title ) {
		return Revision::fetchFromConds(
			array( 'rev_id=page_latest',
			       'page_namespace' => $title->getNamespace(),
			       'page_title'     => $title->getDbkey(),
			       'page_id=rev_page',
			       'rev_id=old_id' ) );		
	}
		/**
	 * Given a set of conditions, return a ResultWrapper
	 * which will return matching database rows with the
	 * fields necessary to build Revision objects.
	 *
	 * @param array $conditions
	 * @return ResultWrapper
	 * @static
	 * @access private
	 */
	function &fetchFromConds( $conditions ) {
		$dbr =& wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array( 'page', 'revision', 'text' ),
			array( 'page_namespace',
			       'page_title',
			       'page_latest',
			       'rev_id',
			       'rev_page',
			       'rev_comment',
			       'rev_user_text',
			       'rev_user',
			       'rev_minor_edit',
			       'rev_timestamp',
			       'old_flags',
			       'old_text' ),
			$conditions,
			'Revision::fetchRow' );
		return $dbr->resultObject( $res );
	}
	
	/**
	 * @param object $row
	 * @access private
	 */
	function Revision( $row ) {
		if( is_object( $row ) ) {
			$this->mId        = IntVal( $row->rev_id );
			$this->mPage      = IntVal( $row->rev_page );
			$this->mComment   =         $row->rev_comment;
			$this->mUserText  =         $row->rev_user_text;
			$this->mUser      = IntVal( $row->rev_user );
			$this->mMinorEdit = IntVal( $row->rev_minor_edit );
			$this->mTimestamp =         $row->rev_timestamp;
		
			$this->mCurrent   = ( $row->rev_id == $row->page_latest );
			$this->mTitle     = Title::makeTitle( $row->page_namespace,
			                                      $row->page_title );
			$this->mText      = $this->getRevisionText( $row );
		} elseif( is_array( $row ) ) {
			// Build a new revision to be saved...
			global $wgUser;
			
			$this->mId        = isset( $row['id']         ) ? IntVal( $row['id']         ) : null;
			$this->mPage      = isset( $row['page']       ) ? IntVal( $row['page']       ) : null;
			$this->mComment   = isset( $row['comment']    ) ? StrVal( $row['comment']    ) : null;
			$this->mUserText  = isset( $row['user_text']  ) ? StrVal( $row['user_text']  ) : $wgUser->getName();
			$this->mUser      = isset( $row['user']       ) ? IntVal( $row['user']       ) : $wgUser->getId();
			$this->mMinorEdit = isset( $row['minor_edit'] ) ? IntVal( $row['minor_edit'] ) : 0;
			$this->mTimestamp = isset( $row['timestamp']  ) ? StrVal( $row['timestamp']  ) : wfTimestamp( TS_MW );
			$this->mText      = isset( $row['text']       ) ? StrVal( $row['text']       ) : '';
			
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
	 * Returns the title of the page associated with this entry.
	 * @return Title
	 */
	function &getTitle() {
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
			$this->mTitle =& Title::makeTitle( $row->page_namespace,
			                                   $row->page_title );
		}
		return $this->mTitle;
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
	 * @return string
	 */
	function getText() {
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
	function &getPrevious() {
		$prev = $this->mTitle->getPreviousRevisionID( $this->mId );
		return Revision::newFromTitle( $this->mTitle, $prev );
	}

	/**
	 * @return Revision
	 */
	function &getNext() {
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
		global $wgCompressRevisions, $wgUseLatin1;
		$flags = array();
		if( !$wgUseLatin1 ) {
			# Revisions not marked this way will be converted
			# on load if $wgLegacyCharset is set in the future.
			$flags[] = 'utf-8';
		}
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
		$old_id = isset( $this->mId )
			? $this->mId
			: $dbw->nextSequenceValue( 'text_old_id_val' );
		$dbw->insert( 'text',
			array(
				'old_id' => $old_id,
				'old_text' => $mungedText,
				'old_flags' => $flags,
			), $fname
		);
		$revisionId = $dbw->insertId();
		
		# Record the edit in revisions
		$dbw->insert( 'revision',
			array(
				'rev_id'         => $revisionId,
				'rev_page'       => $this->mPage,
				'rev_comment'    => $this->mComment,
				'rev_minor_edit' => $this->mMinorEdit ? 1 : 0,
				'rev_user'       => $this->mUser,
				'rev_user_text'  => $this->mUserText,
				'rev_timestamp'  => $dbw->timestamp( $this->mTimestamp ),
			), $fname
		);
		
		wfProfileOut( $fname );
		return $revisionId;
	}
}
?>