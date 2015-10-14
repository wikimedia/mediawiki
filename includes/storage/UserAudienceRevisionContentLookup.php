<?php

namespace MediaWiki\Storage;

use Content;
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
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
 * @since 1.27
 *
 * @file
 * @ingroup Storage
 *
 * @author Daniel Kinzler
 */

/**
 * Implementation of RevisionContentLookup that enforces access control based on
 * user permissions.
 */
class UserAudienceRevisionContentLookup extends RestrictedRevisionContentLookup {

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @param User $user
	 * @param string[] $permissions list of sufficient permissions for viewing non-suppressed content
	 * @param string[] $permissionsForSuppressed list of sufficient permissions for viewing suppressed content
	 */
	public function __construct( User $user ) {
		$this->user = $user;
	}

	/**
	 * @param RevisionSlot $slotRecord
	 *
	 * @return bool
	 */
	protected function canAccess( RevisionSlot $slotRecord ) {
		$restrictions = $slotRecord->getReadRestrictions();

		if ( $restrictions === null ) {
			return true;
		}

		$permissionlist = implode( ',', $restrictions );

		$title = $slotRecord->getTitle(); //FIXME

		if ( $title === null ) {
			wfDebug( "Checking for $permissionlist\n" );
			return call_user_func_array( array( $this->user, 'isAllowedAny' ), $restrictions );
		} else {
			$text = $title->getPrefixedText();
			wfDebug( "Checking for $permissionlist on $text\n" );
			foreach ( $restrictions as $perm ) {
				if ( $title->userCan( $perm, $this->user ) ) {
					return true;
				}
			}
			return false;
		}
	}

}
