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
	 * @param  $name String username
	 * @param  $userId Int user id
	 * @param  $op String operator '|' or '&'
	 * @param  $dbw null|DatabaseBase, if you happen to have one lying around
	 * @return bool
	 */
	private static function setUsernameBitfields( $name, $userId, $op, $dbw ) {
		if ( !$userId || ( $op !== '|' && $op !== '&' ) ) {
			return false; // sanity check
		}
		if ( !$dbw instanceof DatabaseBase ) {
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
			$delUser = "~{$delUser}";
			$delAction = "~{$delAction}";
		}

		# Normalize user name
		$userTitle = Title::makeTitleSafe( NS_USER, $name );
		$userDbKey = $userTitle->getDBkey();

		# Hide name from live edits
		$dbw->update(
			'revision',
			array( "rev_deleted = rev_deleted $op $delUser" ),
			array( 'rev_user' => $userId ),
			__METHOD__ );

		# Hide name from deleted edits
		$dbw->update(
			'archive',
			array( "ar_deleted = ar_deleted $op $delUser" ),
			array( 'ar_user_text' => $name ),
			__METHOD__
		);

		# Hide name from logs
		$dbw->update(
			'logging',
			array( "log_deleted = log_deleted $op $delUser" ),
			array( 'log_user' => $userId, "log_type != 'suppress'" ),
			__METHOD__
		);
		$dbw->update(
			'logging',
			array( "log_deleted = log_deleted $op $delAction" ),
			array( 'log_namespace' => NS_USER, 'log_title' => $userDbKey,
				"log_type != 'suppress'" ),
			__METHOD__
		);

		# Hide name from RC
		$dbw->update(
			'recentchanges',
			array( "rc_deleted = rc_deleted $op $delUser" ),
			array( 'rc_user_text' => $name ),
			__METHOD__
		);
		$dbw->update(
			'recentchanges',
			array( "rc_deleted = rc_deleted $op $delAction" ),
			array( 'rc_namespace' => NS_USER, 'rc_title' => $userDbKey, 'rc_logid > 0' ),
			__METHOD__
		);

		# Hide name from live images
		$dbw->update(
			'oldimage',
			array( "oi_deleted = oi_deleted $op $delUser" ),
			array( 'oi_user_text' => $name ),
			__METHOD__
		);

		# Hide name from deleted images
		$dbw->update(
			'filearchive',
			array( "fa_deleted = fa_deleted $op $delUser" ),
			array( 'fa_user_text' => $name ),
			__METHOD__
		);
		# Done!
		return true;
	}

	public static function suppressUserName( $name, $userId, $dbw = null ) {
		return self::setUsernameBitfields( $name, $userId, '|', $dbw );
	}

	public static function unsuppressUserName( $name, $userId, $dbw = null ) {
		return self::setUsernameBitfields( $name, $userId, '&', $dbw );
	}
}
