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
	 * @param $language Language object to use
	 * @param $isForLog Boolean
	 */
	public static function getLogMessage( $count, $nbitfield, $obitfield, $language, $isForLog = false ) {
		$changes = self::getChanges( $nbitfield, $obitfield );
		array_walk( $changes, array( __CLASS__, 'expandMessageArray' ), $language );

		$changesText = array();

		if( count( $changes[0] ) ) {
			$changesText[] = wfMsgExt( 'revdelete-hid', array( 'parsemag', 'language' => $language ), $language->commaList( $changes[0] ) );
		}
		if( count( $changes[1] ) ) {
			$changesText[] = wfMsgExt( 'revdelete-unhid', array( 'parsemag', 'language' => $language ), $language->commaList( $changes[1] ) );
		}

		$s = $language->semicolonList( $changesText );
		if( count( $changes[2] ) ) {
			$s .= $s ? ' (' . $changes[2][0] . ')' : ' ' . $changes[2][0];
		}

		$msg = $isForLog ? 'logdelete-log-message' : 'revdelete-log-message';
		return wfMsgExt( $msg, array( 'parsemag', 'language' => $language ), $s, $language->formatNum($count) );
	}

	private static function expandMessageArray( &$msg, $key, $language ) {
		if ( is_array ( $msg ) ) {
			array_walk( $msg, array( __CLASS__, 'expandMessageArray' ), $language );
		} else {
			$msg = wfMsgExt( $msg, array( 'parsemag', 'language' => $language ) );
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
			return call_user_func( array( $class, 'getRelationType' ) );
		} else {
			return null;
		}
	}

	/**
	 * Checks if a revision still exists in the revision table.
	 * If it doesn't, returns the corresponding ar_timestamp field
	 * so that this key can be used instead.
	 *
	 * @param $title Title
	 * @param  $revid
	 * @return bool|mixed
	 */
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

	/**
	 * Creates utility links for log entries.
	 *
	 * @param $title Title
	 * @param $paramArray Array
	 * @param $skin Skin
	 * @param $messages
	 * @return String
	 */
	public static function getLogLinks( $title, $paramArray, $skin, $messages ) {
		global $wgLang;

		if ( count( $paramArray ) >= 2 ) {
			// Different revision types use different URL params...
			$originalKey = $key = $paramArray[0];
			// $paramArray[1] is a CSV of the IDs
			$Ids = explode( ',', $paramArray[1] );

			$revert = array();

			// Diff link for single rev deletions
			if ( count( $Ids ) == 1 ) {
				// Live revision diffs...
				if ( in_array( $key, array( 'oldid', 'revision' ) ) ) {
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
				} elseif ( in_array( $key, array( 'artimestamp','archive' ) ) ) {
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

			// Pipe links
			return wfMsg( 'parentheses', $wgLang->pipeList( $revert ) );
		}
		return '';
	}
}
