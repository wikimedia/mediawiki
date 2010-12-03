<?php
/**
 * Revision/log/file deletion backend
 *
 * @file
 */

/**
 * Temporary b/c interface, collection of static functions.
 * @ingroup SpecialPage
 */
class RevisionDeleter {
	/**
	 * Checks for a change in the bitfield for a certain option and updates the
	 * provided array accordingly.
	 *
	 * @param $desc String: description to add to the array if the option was
	 * enabled / disabled.
	 * @param $field Integer: the bitmask describing the single option.
	 * @param $diff Integer: the xor of the old and new bitfields.
	 * @param $new Integer: the new bitfield 
	 * @param $arr Array: the array to update.
	 */
	protected static function checkItem( $desc, $field, $diff, $new, &$arr ) {
		if( $diff & $field ) {
			$arr[ ( $new & $field ) ? 0 : 1 ][] = $desc;
		}
	}

	/**
	 * Gets an array of message keys describing the changes made to the visibility
	 * of the revision. If the resulting array is $arr, then $arr[0] will contain an 
	 * array of strings describing the items that were hidden, $arr[2] will contain 
	 * an array of strings describing the items that were unhidden, and $arr[3] will 
	 * contain an array with a single string, which can be one of "applied 
	 * restrictions to sysops", "removed restrictions from sysops", or null.
	 *
	 * @param $n Integer: the new bitfield.
	 * @param $o Integer: the old bitfield.
	 * @return An array as described above.
	 */
	protected static function getChanges( $n, $o ) {
		$diff = $n ^ $o;
		$ret = array( 0 => array(), 1 => array(), 2 => array() );
		// Build bitfield changes in language
		self::checkItem( 'revdelete-content',
			Revision::DELETED_TEXT, $diff, $n, $ret );
		self::checkItem( 'revdelete-summary',
			Revision::DELETED_COMMENT, $diff, $n, $ret );
		self::checkItem( 'revdelete-uname',
			Revision::DELETED_USER, $diff, $n, $ret );
		// Restriction application to sysops
		if( $diff & Revision::DELETED_RESTRICTED ) {
			if( $n & Revision::DELETED_RESTRICTED )
				$ret[2][] = 'revdelete-restricted';
			else
				$ret[2][] = 'revdelete-unrestricted';
		}
		return $ret;
	}

	/**
	 * Gets a log message to describe the given revision visibility change. This
	 * message will be of the form "[hid {content, edit summary, username}];
	 * [unhid {...}][applied restrictions to sysops] for $count revisions: $comment".
	 *
	 * @param $count Integer: The number of effected revisions.
	 * @param $nbitfield Integer: The new bitfield for the revision.
	 * @param $obitfield Integer: The old bitfield for the revision.
	 * @param $isForLog Boolean
	 * @param $forContent Boolean
	 */
	public static function getLogMessage( $count, $nbitfield, $obitfield, $isForLog = false, $forContent = false ) {
		global $wgLang, $wgContLang;
		
		$lang = $forContent ? $wgContLang : $wgLang;
		$msgFunc = $forContent ? "wfMsgForContent" : "wfMsg";
		
		$changes = self::getChanges( $nbitfield, $obitfield );
		array_walk($changes, 'RevisionDeleter::expandMessageArray', $forContent);
		
		$changesText = array();
		
		if( count( $changes[0] ) ) {
			$changesText[] = $msgFunc( 'revdelete-hid', $lang->commaList( $changes[0] ) );
		}
		if( count( $changes[1] ) ) {
			$changesText[] = $msgFunc( 'revdelete-unhid', $lang->commaList( $changes[1] ) );
		}
		
		$s = $lang->semicolonList( $changesText );
		if( count( $changes[2] ) ) {
			$s .= $s ? ' (' . $changes[2][0] . ')' : ' ' . $changes[2][0];
		}
		
		$msg = $isForLog ? 'logdelete-log-message' : 'revdelete-log-message';
		return wfMsgExt( $msg, $forContent ? array( 'parsemag', 'content' ) : array( 'parsemag' ), $s, $lang->formatNum($count) );
	}
	
	private static function expandMessageArray(& $msg, $key, $forContent) {
		if ( is_array ($msg) ) {
			array_walk($msg, 'RevisionDeleter::expandMessageArray', $forContent);
		} else {
			if ( $forContent ) {
				$msg = wfMsgForContent($msg);
			} else {
				$msg = wfMsg($msg);
			}
		}
	}
	
	// Get DB field name for URL param...
	// Future code for other things may also track
	// other types of revision-specific changes.
	// @returns string One of log_id/rev_id/fa_id/ar_timestamp/oi_archive_name
	public static function getRelationType( $typeName ) {
		if ( isset( SpecialRevisionDelete::$deprecatedTypeMap[$typeName] ) ) {
			$typeName = SpecialRevisionDelete::$deprecatedTypeMap[$typeName];
		}
		if ( isset( SpecialRevisionDelete::$allowedTypes[$typeName] ) ) {
			$class = SpecialRevisionDelete::$allowedTypes[$typeName]['list-class'];
			$list = new $class( null, null, null );
			return $list->getIdField();
		} else {
			return null;
		}
	}
	
	// Checks if a revision still exists in the revision table.
	//  If it doesn't, returns the corresponding ar_timestamp field
	//  so that this key can be used instead.
	public static function checkRevisionExistence( $title, $revid ) {
		$dbr = wfGetDB( DB_SLAVE );
		$exists = $dbr->selectField( 'revision', '1',
				array( 'rev_id' => $revid ), __METHOD__ );
				
		if ( $exists ) {
			return true;
		}
		
		$timestamp = $dbr->selectField( 'archive', 'ar_timestamp',
				array( 'ar_namespace' => $title->getNamespace(),
					'ar_title' => $title->getDBkey(),
					'ar_rev_id' => $revid ), __METHOD__ );
		
		return $timestamp;
	}
	
	// Creates utility links for log entries.
	public static function getLogLinks( $title, $paramArray, $skin, $messages ) {
		global $wgLang;
		
		if( count($paramArray) >= 2 ) {
			// Different revision types use different URL params...
			$originalKey = $key = $paramArray[0];
			// $paramArray[1] is a CSV of the IDs
			$Ids = explode( ',', $paramArray[1] );

			$revert = array();
			
			// For if undeleted revisions are found amidst deleted ones.
			$undeletedRevisions = array();
			
			// This is not going to work if some revs are deleted and some
			//  aren't.
			if ($key == 'revision') {
				foreach( $Ids as $k => $id ) {
					$existResult =
						self::checkRevisionExistence( $title, $id );
					
					if ($existResult !== true) {
						$key = 'archive';
						$Ids[$k] = $existResult;
					} else {
						// Undeleted revision amidst deleted ones
						unset($Ids[$k]);
						$undeletedRevisions[] = $id;
					}
				}
				
				if ( $key == $originalKey ) {
					$Ids = $undeletedRevisions;
					$undeletedRevisions = array();
				}
			}
			
			// Diff link for single rev deletions
			if( count($Ids) == 1 && !count($undeletedRevisions) ) {
				// Live revision diffs...
				if( in_array( $key, array( 'oldid', 'revision' ) ) ) {
					$revert[] = $skin->link(
						$title,
						$messages['diff'],
						array(),
						array(
							'diff' => intval( $Ids[0] ),
							'unhide' => 1
						),
						array( 'known', 'noclasses' )
					);
				// Deleted revision diffs...
				} else if( in_array( $key, array( 'artimestamp','archive' ) ) ) {
					$revert[] = $skin->link(
						SpecialPage::getTitleFor( 'Undelete' ),
						$messages['diff'], 
						array(),
						array(
							'target'    => $title->getPrefixedDBKey(),
							'diff'      => 'prev',
							'timestamp' => $Ids[0]
						),
						array( 'known', 'noclasses' )
					);
				}
			}
			
			// View/modify link...
			if ( count($undeletedRevisions) ) {
				// FIXME THIS IS A HORRIBLE HORRIBLE HACK AND SHOULD DIE
				// It's not possible to pass a list of both deleted and
				// undeleted revisions to SpecialRevisionDelete, so we're
				// stuck with two links. See bug 23363.
				$restoreLinks = array();
				
				$restoreLinks[] = $skin->link(
					SpecialPage::getTitleFor( 'Revisiondelete' ),
					$messages['revdel-restore-visible'],
					array(),
					array(
						'target' => $title->getPrefixedText(),
						'type' => $originalKey,
						'ids' => implode(',', $undeletedRevisions),
					),
					array( 'known', 'noclasses' )
				);
				
				$restoreLinks[] = $skin->link(
					SpecialPage::getTitleFor( 'Revisiondelete' ),
					$messages['revdel-restore-deleted'],
					array(),
					array(
						'target' => $title->getPrefixedText(),
						'type' => $key,
						'ids' => implode(',', $Ids),
					),
					array( 'known', 'noclasses' )
				);
				
				$revert[] = $messages['revdel-restore'] . ' [' .
						$wgLang->pipeList( $restoreLinks ) . ']';
			} else {
				$revert[] = $skin->link(
					SpecialPage::getTitleFor( 'Revisiondelete' ),
					$messages['revdel-restore'],
					array(),
					array(
						'target' => $title->getPrefixedText(),
						'type' => $key,
						'ids' => implode(',', $Ids),
					),
					array( 'known', 'noclasses' )
				);
			}
			
			// Pipe links
			return wfMsg( 'parentheses', $wgLang->pipeList( $revert ) );
		}
		return '';
	}
}

/**
 * List for revision table items
 */
class RevDel_RevisionList extends RevDel_List {
	var $currentRevId;
	var $type = 'revision';
	var $idField = 'rev_id';
	var $dateField = 'rev_timestamp';
	var $authorIdField = 'rev_user';
	var $authorNameField = 'rev_user_text';

	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		return $db->select( array('revision','page'), '*',
			array(
				'rev_page' => $this->title->getArticleID(),
				'rev_id'   => $ids,
				'rev_page = page_id'
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_id DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_RevisionItem( $this, $row );
	}

	public function getCurrent() {
		if ( is_null( $this->currentRevId ) ) {
			$dbw = wfGetDB( DB_MASTER );
			$this->currentRevId = $dbw->selectField( 
				'page', 'page_latest', $this->title->pageCond(), __METHOD__ );
		}
		return $this->currentRevId;
	}

	public function getSuppressBit() {
		return Revision::DELETED_RESTRICTED;
	}

	public function doPreCommitUpdates() {
		$this->title->invalidateCache();
		return Status::newGood();
	}

	public function doPostCommitUpdates() {
		$this->title->purgeSquid();
		// Extensions that require referencing previous revisions may need this
		wfRunHooks( 'ArticleRevisionVisibilitySet', array( &$this->title ) );
		return Status::newGood();
	}
}

/**
 * Item class for a revision table row
 */
class RevDel_RevisionItem extends RevDel_Item {
	var $revision;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->revision = new Revision( $row );
	}

	public function canView() {
		return $this->revision->userCan( Revision::DELETED_RESTRICTED );
	}
	
	public function canViewContent() {
		return $this->revision->userCan( Revision::DELETED_TEXT );
	}

	public function getBits() {
		return $this->revision->mDeleted;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		// Update revision table
		$dbw->update( 'revision',
			array( 'rev_deleted' => $bits ),
			array( 
				'rev_id' => $this->revision->getId(), 
				'rev_page' => $this->revision->getPage(),
				'rev_deleted' => $this->getBits()
			),
			__METHOD__
		);
		if ( !$dbw->affectedRows() ) {
			// Concurrent fail!
			return false;
		}
		// Update recentchanges table
		$dbw->update( 'recentchanges',
			array( 
				'rc_deleted' => $bits,
				'rc_patrolled' => 1
			),
			array(
				'rc_this_oldid' => $this->revision->getId(), // condition
				// non-unique timestamp index
				'rc_timestamp' => $dbw->timestamp( $this->revision->getTimestamp() ),
			),
			__METHOD__
		);
		return true;
	}

	public function isDeleted() {
		return $this->revision->isDeleted( Revision::DELETED_TEXT );
	}

	public function isHideCurrentOp( $newBits ) {
		return ( $newBits & Revision::DELETED_TEXT ) 
			&& $this->list->getCurrent() == $this->getId();
	}

	/**
	 * Get the HTML link to the revision text.
	 * Overridden by RevDel_ArchiveItem.
	 */
	protected function getRevisionLink() {
		global $wgLang;
		$date = $wgLang->timeanddate( $this->revision->getTimestamp(), true );
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}
		return $this->special->skin->link(
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
				$this->special->skin->link( 
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
		$userlink = $this->special->skin->revUserLink( $this->revision );
		$comment = $this->special->skin->revComment( $this->revision );
		if ( $this->isDeleted() ) {
			$revlink = "<span class=\"history-deleted\">$revlink</span>";
		}
		return "<li>($difflink) $revlink $userlink $comment</li>";
	}
}

/**
 * List for archive table items, i.e. revisions deleted via action=delete
 */
class RevDel_ArchiveList extends RevDel_RevisionList {
	var $type = 'archive';
	var $idField = 'ar_timestamp';
	var $dateField = 'ar_timestamp';
	var $authorIdField = 'ar_user';
	var $authorNameField = 'ar_user_text';

	public function doQuery( $db ) {
		$timestamps = array();
		foreach ( $this->ids as $id ) {
			$timestamps[] = $db->timestamp( $id );
		}
		return $db->select( 'archive', '*',
				array(
					'ar_namespace' => $this->title->getNamespace(),
					'ar_title'     => $this->title->getDBkey(),
					'ar_timestamp' => $timestamps
				),
				__METHOD__,
				array( 'ORDER BY' => 'ar_timestamp DESC' )
			);
	}

	public function newItem( $row ) {
		return new RevDel_ArchiveItem( $this, $row );
	}

	public function doPreCommitUpdates() {
		return Status::newGood();
	}

	public function doPostCommitUpdates() {
		return Status::newGood();
	}
}

/**
 * Item class for a archive table row
 */
class RevDel_ArchiveItem extends RevDel_RevisionItem {
	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->revision = Revision::newFromArchiveRow( $row,
			array( 'page' => $this->list->title->getArticleId() ) );
	}

	public function getId() {
		# Convert DB timestamp to MW timestamp
		return $this->revision->getTimestamp();
	}
	
	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'archive',
			array( 'ar_deleted' => $bits ),
			array( 'ar_namespace' => $this->list->title->getNamespace(),
				'ar_title'     => $this->list->title->getDBkey(),
				// use timestamp for index
				'ar_timestamp' => $this->row->ar_timestamp,
				'ar_rev_id'    => $this->row->ar_rev_id,
				'ar_deleted' => $this->getBits()
			),
			__METHOD__ );
		return (bool)$dbw->affectedRows();
	}

	protected function getRevisionLink() {
		global $wgLang;
		$undelete = SpecialPage::getTitleFor( 'Undelete' );
		$date = $wgLang->timeanddate( $this->revision->getTimestamp(), true );
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}
		return $this->special->skin->link( $undelete, $date, array(),
			array(
				'target' => $this->list->title->getPrefixedText(),
				'timestamp' => $this->revision->getTimestamp()
			) );
	}

	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return wfMsgHtml( 'diff' );
		}
		$undelete = SpecialPage::getTitleFor( 'Undelete' );		
		return $this->special->skin->link( $undelete, wfMsgHtml('diff'), array(), 
			array(
				'target' => $this->list->title->getPrefixedText(),
				'diff' => 'prev',
				'timestamp' => $this->revision->getTimestamp()
			) );
	}
}

/**
 * List for oldimage table items
 */
class RevDel_FileList extends RevDel_List {
	var $type = 'oldimage';
	var $idField = 'oi_archive_name';
	var $dateField = 'oi_timestamp';
	var $authorIdField = 'oi_user';
	var $authorNameField = 'oi_user_text';
	var $storeBatch, $deleteBatch, $cleanupBatch;

	public function doQuery( $db ) {
		$archiveNames = array();
		foreach( $this->ids as $timestamp ) {
			$archiveNames[] = $timestamp . '!' . $this->title->getDBkey();
		}
		return $db->select( 'oldimage', '*',
			array(
				'oi_name'         => $this->title->getDBkey(),
				'oi_archive_name' => $archiveNames
			),
			__METHOD__,
			array( 'ORDER BY' => 'oi_timestamp DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_FileItem( $this, $row );
	}

	public function clearFileOps() {
		$this->deleteBatch = array();
		$this->storeBatch = array();
		$this->cleanupBatch = array();
	}

	public function doPreCommitUpdates() {
		$status = Status::newGood();
		$repo = RepoGroup::singleton()->getLocalRepo();
		if ( $this->storeBatch ) {
			$status->merge( $repo->storeBatch( $this->storeBatch, FileRepo::OVERWRITE_SAME ) );
		}
		if ( !$status->isOK() ) {
			return $status;
		}
		if ( $this->deleteBatch ) {
			$status->merge( $repo->deleteBatch( $this->deleteBatch ) );
		}
		if ( !$status->isOK() ) {
			// Running cleanupDeletedBatch() after a failed storeBatch() with the DB already
			// modified (but destined for rollback) causes data loss
			return $status;
		}
		if ( $this->cleanupBatch ) {
			$status->merge( $repo->cleanupDeletedBatch( $this->cleanupBatch ) );
		}
		return $status;
	}

	public function doPostCommitUpdates() {
		$file = wfLocalFile( $this->title );
		$file->purgeCache();
		$file->purgeDescription();
		return Status::newGood();
	}

	public function getSuppressBit() {
		return File::DELETED_RESTRICTED;
	}
}

/**
 * Item class for an oldimage table row
 */
class RevDel_FileItem extends RevDel_Item {
	var $file;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->file = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
	}

	public function getId() {
		$parts = explode( '!', $this->row->oi_archive_name );
		return $parts[0];
	}

	public function canView() {
		return $this->file->userCan( File::DELETED_RESTRICTED );
	}
	
	public function canViewContent() {
		return $this->file->userCan( File::DELETED_FILE );
	}

	public function getBits() {
		return $this->file->getVisibility();
	}

	public function setBits( $bits ) {
		# Queue the file op
		# FIXME: move to LocalFile.php
		if ( $this->isDeleted() ) {
			if ( $bits & File::DELETED_FILE ) {
				# Still deleted
			} else {
				# Newly undeleted
				$key = $this->file->getStorageKey();
				$srcRel = $this->file->repo->getDeletedHashPath( $key ) . $key;
				$this->list->storeBatch[] = array(
					$this->file->repo->getVirtualUrl( 'deleted' ) . '/' . $srcRel,
					'public',
					$this->file->getRel()
				);
				$this->list->cleanupBatch[] = $key;
			}
		} elseif ( $bits & File::DELETED_FILE ) {
			# Newly deleted
			$key = $this->file->getStorageKey();
			$dstRel = $this->file->repo->getDeletedHashPath( $key ) . $key;
			$this->list->deleteBatch[] = array( $this->file->getRel(), $dstRel );
		}
		
		# Do the database operations
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'oldimage',
			array( 'oi_deleted' => $bits ),
			array( 
				'oi_name' => $this->row->oi_name,
				'oi_timestamp' => $this->row->oi_timestamp,
				'oi_deleted' => $this->getBits()
			),
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	public function isDeleted() {
		return $this->file->isDeleted( File::DELETED_FILE );
	}

	/**
	 * Get the link to the file. 
	 * Overridden by RevDel_ArchivedFileItem.
	 */
	protected function getLink() {
		global $wgLang, $wgUser;
		$date = $wgLang->timeanddate( $this->file->getTimestamp(), true  );		
		if ( $this->isDeleted() ) {
			# Hidden files...
			if ( !$this->canViewContent() ) {
				$link = $date;
			} else {
				$link = $this->special->skin->link( 
					$this->special->getTitle(), 
					$date, array(), 
					array(
						'target' => $this->list->title->getPrefixedText(),
						'file'   => $this->file->getArchiveName(),
						'token'  => $wgUser->editToken( $this->file->getArchiveName() )
					)
				);
			}
			return '<span class="history-deleted">' . $link . '</span>';
		} else {
			# Regular files...
			return Xml::element( 'a', array( 'href' => $this->file->getUrl() ), $date );
		}
	}
	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 * @return string HTML
	 */
	protected function getUserTools() {
		if( $this->file->userCan( Revision::DELETED_USER ) ) {
			$link = $this->special->skin->userLink( $this->file->user, $this->file->user_text ) .
				$this->special->skin->userToolLinks( $this->file->user, $this->file->user_text );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if( $this->file->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * Wrap and format the file's comment block, if the current
	 * user is allowed to view it.
	 *
	 * @return string HTML
	 */
	protected function getComment() {
		if( $this->file->userCan( File::DELETED_COMMENT ) ) {
			$block = $this->special->skin->commentBlock( $this->file->description );
		} else {
			$block = ' ' . wfMsgHtml( 'rev-deleted-comment' );
		}
		if( $this->file->isDeleted( File::DELETED_COMMENT ) ) {
			return "<span class=\"history-deleted\">$block</span>";
		}
		return $block;
	}

	public function getHTML() {
		global $wgLang;
		$data = 
			wfMsg(
				'widthheight', 
				$wgLang->formatNum( $this->file->getWidth() ),
				$wgLang->formatNum( $this->file->getHeight() ) 
			) .
			' (' . 
			wfMsgExt( 'nbytes', 'parsemag', $wgLang->formatNum( $this->file->getSize() ) ) . 
			')';

		return '<li>' . $this->getLink() . ' ' . $this->getUserTools() . ' ' .
			$data . ' ' . $this->getComment(). '</li>';
	}
}

/**
 * List for filearchive table items
 */
class RevDel_ArchivedFileList extends RevDel_FileList {
	var $type = 'filearchive';
	var $idField = 'fa_id';
	var $dateField = 'fa_timestamp';
	var $authorIdField = 'fa_user';
	var $authorNameField = 'fa_user_text';
	
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		return $db->select( 'filearchive', '*',
			array(
				'fa_name' => $this->title->getDBkey(),
				'fa_id'   => $ids
			),
			__METHOD__,
			array( 'ORDER BY' => 'fa_id DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_ArchivedFileItem( $this, $row );
	}
}

/**
 * Item class for a filearchive table row
 */
class RevDel_ArchivedFileItem extends RevDel_FileItem {
	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->file = ArchivedFile::newFromRow( $row );
	}

	public function getId() {
		return $this->row->fa_id;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'filearchive',
			array( 'fa_deleted' => $bits ),
			array(
				'fa_id' => $this->row->fa_id,
				'fa_deleted' => $this->getBits(),
			),
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	protected function getLink() {
		global $wgLang, $wgUser;
		$date = $wgLang->timeanddate( $this->file->getTimestamp(), true  );
		$undelete = SpecialPage::getTitleFor( 'Undelete' );
		$key = $this->file->getKey();
		# Hidden files...
		if( !$this->canViewContent() ) {
			$link = $date;
		} else {
			$link = $this->special->skin->link( $undelete, $date, array(),
				array(
					'target' => $this->list->title->getPrefixedText(),
					'file' => $key,
					'token' => $wgUser->editToken( $key )
				)
			);
		}
		if( $this->isDeleted() ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}
}

/**
 * List for logging table items
 */
class RevDel_LogList extends RevDel_List {
	var $type = 'logging';
	var $idField = 'log_id';
	var $dateField = 'log_timestamp';
	var $authorIdField = 'log_user';
	var $authorNameField = 'log_user_text';

	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		return $db->select( 'logging', '*',
			array( 'log_id' => $ids ),
			__METHOD__,
			array( 'ORDER BY' => 'log_id DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_LogItem( $this, $row );
	}

	public function getSuppressBit() {
		return Revision::DELETED_RESTRICTED;
	}

	public function getLogAction() {
		return 'event';
	}

	public function getLogParams( $params ) {
		return array(
			implode( ',', $params['ids'] ),
			"ofield={$params['oldBits']}",
			"nfield={$params['newBits']}"
		);
	}
}

/**
 * Item class for a logging table row
 */
class RevDel_LogItem extends RevDel_Item {
	public function canView() {
		return LogEventsList::userCan( $this->row, Revision::DELETED_RESTRICTED );
	}
	
	public function canViewContent() {
		return true; // none
	}

	public function getBits() {
		return $this->row->log_deleted;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'recentchanges',
			array( 
				'rc_deleted' => $bits, 
				'rc_patrolled' => 1 
			),
			array(
				'rc_logid' => $this->row->log_id,
				'rc_timestamp' => $this->row->log_timestamp // index
			),
			__METHOD__
		);
		$dbw->update( 'logging',
			array( 'log_deleted' => $bits ),
			array(
				'log_id' => $this->row->log_id,
				'log_deleted' => $this->getBits()
			),
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	public function getHTML() {
		global $wgLang;

		$date = htmlspecialchars( $wgLang->timeanddate( $this->row->log_timestamp ) );
		$paramArray = LogPage::extractParams( $this->row->log_params );
		$title = Title::makeTitle( $this->row->log_namespace, $this->row->log_title );

		// Log link for this page
		$loglink = $this->special->skin->link(
			SpecialPage::getTitleFor( 'Log' ),
			wfMsgHtml( 'log' ),
			array(),
			array( 'page' => $title->getPrefixedText() )
		);
		// Action text
		if( !$this->canView() ) {
			$action = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';
		} else {
			$action = LogPage::actionText( $this->row->log_type, $this->row->log_action, $title,
				$this->special->skin, $paramArray, true, true );
			if( $this->row->log_deleted & LogPage::DELETED_ACTION )
				$action = '<span class="history-deleted">' . $action . '</span>';
		}
		// User links
		$userLink = $this->special->skin->userLink( $this->row->log_user,
			User::WhoIs( $this->row->log_user ) );
		if( LogEventsList::isDeleted($this->row,LogPage::DELETED_USER) ) {
			$userLink = '<span class="history-deleted">' . $userLink . '</span>';
		}
		// Comment
		$comment = $wgLang->getDirMark() . $this->special->skin->commentBlock( $this->row->log_comment );
		if( LogEventsList::isDeleted($this->row,LogPage::DELETED_COMMENT) ) {
			$comment = '<span class="history-deleted">' . $comment . '</span>';
		}
		return "<li>($loglink) $date $userLink $action $comment</li>";
	}
}
