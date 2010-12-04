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