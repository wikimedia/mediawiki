<?php
/**
 * Revision/log/file deletion backend
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
 * @ingroup RevisionDelete
 */

/**
 * General controller for RevDel, used by both SpecialRevisiondelete and
 * ApiRevisionDelete.
 * @ingroup RevisionDelete
 */
class RevisionDeleter {
	/** List of known revdel types, with their corresponding list classes */
	private static $allowedTypes = array(
		'revision' => 'RevDelRevisionList',
		'archive' => 'RevDelArchiveList',
		'oldimage' => 'RevDelFileList',
		'filearchive' => 'RevDelArchivedFileList',
		'logging' => 'RevDelLogList',
	);

	/** Type map to support old log entries */
	private static $deprecatedTypeMap = array(
		'oldid' => 'revision',
		'artimestamp' => 'archive',
		'oldimage' => 'oldimage',
		'fileid' => 'filearchive',
		'logid' => 'logging',
	);

	/**
	 * Lists the valid possible types for revision deletion.
	 *
	 * @since 1.22
	 * @return array
	 */
	public static function getTypes() {
		return array_keys( self::$allowedTypes );
	}

	/**
	 * Gets the canonical type name, if any.
	 *
	 * @since 1.22
	 * @param string $typeName
	 * @return string|null
	 */
	public static function getCanonicalTypeName( $typeName ) {
		if ( isset( self::$deprecatedTypeMap[$typeName] ) ) {
			$typeName = self::$deprecatedTypeMap[$typeName];
		}
		return isset( self::$allowedTypes[$typeName] ) ? $typeName : null;
	}

	/**
	 * Instantiate the appropriate list class for a given list of IDs.
	 *
	 * @since 1.22
	 * @param string $typeName RevDel type, see RevisionDeleter::getTypes()
	 * @param IContextSource $context
	 * @param Title $title
	 * @param array $ids
	 * @return RevDelList
	 * @throws MWException
	 */
	public static function createList( $typeName, IContextSource $context, Title $title, array $ids ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			throw new MWException( __METHOD__ . ": Unknown RevDel type '$typeName'" );
		}
		return new self::$allowedTypes[$typeName]( $context, $title, $ids );
	}

	/**
	 * Checks for a change in the bitfield for a certain option and updates the
	 * provided array accordingly.
	 *
	 * @param string $desc Description to add to the array if the option was
	 * enabled / disabled.
	 * @param int $field The bitmask describing the single option.
	 * @param int $diff The xor of the old and new bitfields.
	 * @param int $new The new bitfield
	 * @param array $arr The array to update.
	 */
	protected static function checkItem( $desc, $field, $diff, $new, &$arr ) {
		if ( $diff & $field ) {
			$arr[( $new & $field ) ? 0 : 1][] = $desc;
		}
	}

	/**
	 * Gets an array of message keys describing the changes made to the
	 * visibility of the revision.
	 *
	 * If the resulting array is $arr, then $arr[0] will contain an array of
	 * keys describing the items that were hidden, $arr[1] will contain
	 * an array of keys describing the items that were unhidden, and $arr[2]
	 * will contain an array with a single message key, which can be one of
	 * "revdelete-restricted", "revdelete-unrestricted" indicating (un)suppression
	 * or null to indicate nothing in particular.
	 * You can turn the keys in $arr[0] and $arr[1] into message keys by
	 * appending -hid and -unhid to the keys respectively.
	 *
	 * @param int $n The new bitfield.
	 * @param int $o The old bitfield.
	 * @return array An array as described above.
	 * @since 1.19 public
	 */
	public static function getChanges( $n, $o ) {
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
		if ( $diff & Revision::DELETED_RESTRICTED ) {
			if ( $n & Revision::DELETED_RESTRICTED ) {
				$ret[2][] = 'revdelete-restricted';
			} else {
				$ret[2][] = 'revdelete-unrestricted';
			}
		}
		return $ret;
	}

	/** Get DB field name for URL param...
	 * Future code for other things may also track
	 * other types of revision-specific changes.
	 * @param string $typeName
	 * @return string One of log_id/rev_id/fa_id/ar_timestamp/oi_archive_name
	 */
	public static function getRelationType( $typeName ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return null;
		}
		return call_user_func( array( self::$allowedTypes[$typeName], 'getRelationType' ) );
	}

	/**
	 * Get the user right required for the RevDel type
	 * @since 1.22
	 * @param string $typeName
	 * @return string User right
	 */
	public static function getRestriction( $typeName ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return null;
		}
		return call_user_func( array( self::$allowedTypes[$typeName], 'getRestriction' ) );
	}

	/**
	 * Get the revision deletion constant for the RevDel type
	 * @since 1.22
	 * @param string $typeName
	 * @return int RevDel constant
	 */
	public static function getRevdelConstant( $typeName ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return null;
		}
		return call_user_func( array( self::$allowedTypes[$typeName], 'getRevdelConstant' ) );
	}

	/**
	 * Suggest a target for the revision deletion
	 * @since 1.22
	 * @param string $typeName
	 * @param Title|null $target User-supplied target
	 * @param array $ids
	 * @return Title|null
	 */
	public static function suggestTarget( $typeName, $target, array $ids ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return $target;
		}
		return call_user_func( array( self::$allowedTypes[$typeName], 'suggestTarget' ), $target, $ids );
	}

	/**
	 * Checks if a revision still exists in the revision table.
	 * If it doesn't, returns the corresponding ar_timestamp field
	 * so that this key can be used instead.
	 *
	 * @param Title $title
	 * @param int $revid
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
	 * Put together a rev_deleted bitfield
	 * @since 1.22
	 * @param array $bitPars ExtractBitParams() params
	 * @param int $oldfield Current bitfield
	 * @return integer
	 */
	public static function extractBitfield( array $bitPars, $oldfield ) {
		// Build the actual new rev_deleted bitfield
		$newBits = 0;
		foreach ( $bitPars as $const => $val ) {
			if ( $val == 1 ) {
				$newBits |= $const; // $const is the *_deleted const
			} elseif ( $val == -1 ) {
				$newBits |= ( $oldfield & $const ); // use existing
			}
		}
		return $newBits;
	}
}
