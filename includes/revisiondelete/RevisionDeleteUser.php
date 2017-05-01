<?php
/**
 * Backend functions for suppressing and unsuppressing all references to a given user.
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
 * Backend functions for suppressing and unsuppressing all references to a given user,
 * used when blocking with HideUser enabled.  This was spun out of SpecialBlockip.php
 * in 1.18; at some point it needs to be rewritten to either use RevisionDelete abstraction,
 * or at least schema abstraction.
 *
 * @ingroup RevisionDelete
 */
class RevisionDeleteUser {

	/**
	 * Update *_deleted bitfields in various tables to hide or unhide usernames
	 * @param string $name Username
	 * @param int $userId User id
	 * @param string $op Operator '|' or '&'
	 * @param null|IDatabase $dbw If you happen to have one lying around
	 * @return bool
	 */
	private static function setUsernameBitfields( $name, $userId, $op, $dbw ) {
		if ( !$userId || ( $op !== '|' && $op !== '&' ) ) {
			return false; // sanity check
		}
		if ( !$dbw instanceof IDatabase ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		# To suppress, we OR the current bitfields with Revision::DELETED_USER
		# to put a 1 in the username *_deleted bit. To unsuppress we AND the
		# current bitfields with the inverse of Revision::DELETED_USER. The
		# username bit is made to 0 (x & 0 = 0), while others are unchanged (x & 1 = x).
		# The same goes for the sysop-restricted *_deleted bit.
		$delUser = Revision::DELETED_USER | Revision::DELETED_RESTRICTED;
		$delAction = LogPage::DELETED_ACTION | Revision::DELETED_RESTRICTED;
		if ( $op == '&' ) {
			$delUser = $dbw->bitNot( $delUser );
			$delAction = $dbw->bitNot( $delAction );
		}

		# Normalize user name
		$userTitle = Title::makeTitleSafe( NS_USER, $name );
		$userDbKey = $userTitle->getDBkey();

		# Hide name from live edits
		$dbw->update(
			'revision',
			[ self::buildSetBitDeletedField( 'rev_deleted', $op, $delUser, $dbw ) ],
			[ 'rev_user' => $userId ],
			__METHOD__ );

		# Hide name from deleted edits
		$dbw->update(
			'archive',
			[ self::buildSetBitDeletedField( 'ar_deleted', $op, $delUser, $dbw ) ],
			[ 'ar_user_text' => $name ],
			__METHOD__
		);

		# Hide name from logs
		$dbw->update(
			'logging',
			[ self::buildSetBitDeletedField( 'log_deleted', $op, $delUser, $dbw ) ],
			[ 'log_user' => $userId, 'log_type != ' . $dbw->addQuotes( 'suppress' ) ],
			__METHOD__
		);
		$dbw->update(
			'logging',
			[ self::buildSetBitDeletedField( 'log_deleted', $op, $delAction, $dbw ) ],
			[ 'log_namespace' => NS_USER, 'log_title' => $userDbKey,
				'log_type != ' . $dbw->addQuotes( 'suppress' ) ],
			__METHOD__
		);

		# Hide name from RC
		$dbw->update(
			'recentchanges',
			[ self::buildSetBitDeletedField( 'rc_deleted', $op, $delUser, $dbw ) ],
			[ 'rc_user_text' => $name ],
			__METHOD__
		);
		$dbw->update(
			'recentchanges',
			[ self::buildSetBitDeletedField( 'rc_deleted', $op, $delAction, $dbw ) ],
			[ 'rc_namespace' => NS_USER, 'rc_title' => $userDbKey, 'rc_logid > 0' ],
			__METHOD__
		);

		# Hide name from live images
		$dbw->update(
			'oldimage',
			[ self::buildSetBitDeletedField( 'oi_deleted', $op, $delUser, $dbw ) ],
			[ 'oi_user_text' => $name ],
			__METHOD__
		);

		# Hide name from deleted images
		$dbw->update(
			'filearchive',
			[ self::buildSetBitDeletedField( 'fa_deleted', $op, $delUser, $dbw ) ],
			[ 'fa_user_text' => $name ],
			__METHOD__
		);
		# Done!
		return true;
	}

	private static function buildSetBitDeletedField( $field, $op, $value, $dbw ) {
		return $field . ' = ' . ( $op === '&'
			? $dbw->bitAnd( $field, $value )
			: $dbw->bitOr( $field, $value ) );
	}

	public static function suppressUserName( $name, $userId, $dbw = null ) {
		return self::setUsernameBitfields( $name, $userId, '|', $dbw );
	}

	public static function unsuppressUserName( $name, $userId, $dbw = null ) {
		return self::setUsernameBitfields( $name, $userId, '&', $dbw );
	}
}
