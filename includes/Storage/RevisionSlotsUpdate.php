<?php
/**
 * Value object representing a modification of revision slots.
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
 */

namespace MediaWiki\Storage;

use Content;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\SlotRecord;

/**
 * Value object representing a modification of revision slots.
 *
 * @since 1.32
 */
class RevisionSlotsUpdate {

	/**
	 * @var SlotRecord[] modified slots, using the slot role as the key.
	 */
	private $modifiedSlots = [];

	/**
	 * @var bool[] removed roles, stored in the keys of the array.
	 */
	private $removedRoles = [];

	/**
	 * Constructs a RevisionSlotsUpdate representing the update that turned $parentSlots
	 * into $newSlots. If $parentSlots is not given, $newSlots is assumed to come from a
	 * page's first revision.
	 *
	 * @param RevisionSlots $newSlots
	 * @param RevisionSlots|null $parentSlots
	 *
	 * @return RevisionSlotsUpdate
	 */
	public static function newFromRevisionSlots(
		RevisionSlots $newSlots,
		RevisionSlots $parentSlots = null
	) {
		$modified = $newSlots->getSlots();
		$removed = [];

		if ( $parentSlots ) {
			foreach ( $parentSlots->getSlots() as $role => $slot ) {
				if ( !isset( $modified[$role] ) ) {
					$removed[] = $role;
				} elseif ( $slot->hasSameContent( $modified[$role] ) ) {
					// Unset slots that had the same content in the parent revision from $modified.
					unset( $modified[$role] );
				}
			}
		}

		return new RevisionSlotsUpdate( $modified, $removed );
	}

	/**
	 * Constructs a RevisionSlotsUpdate representing the update of $parentSlots
	 * when changing $newContent. If a slot has the same content in $newContent
	 * as in $parentSlots, that slot is considered inherited and thus omitted from
	 * the resulting RevisionSlotsUpdate.
	 *
	 * In contrast to newFromRevisionSlots(), slots in $parentSlots that are not present
	 * in $newContent are not considered removed. They are instead assumed to be inherited.
	 *
	 * @param Content[] $newContent The new content, using slot roles as array keys.
	 *
	 * @return RevisionSlotsUpdate
	 */
	public static function newFromContent( array $newContent, RevisionSlots $parentSlots = null ) {
		$modified = [];

		foreach ( $newContent as $role => $content ) {
			$slot = SlotRecord::newUnsaved( $role, $content );

			if ( $parentSlots
				&& $parentSlots->hasSlot( $role )
				&& $slot->hasSameContent( $parentSlots->getSlot( $role ) )
			) {
				// Skip slots that had the same content in the parent revision from $modified.
				continue;
			}

			$modified[$role] = $slot;
		}

		return new RevisionSlotsUpdate( $modified );
	}

	/**
	 * @param SlotRecord[] $modifiedSlots
	 * @param string[] $removedRoles
	 */
	public function __construct( array $modifiedSlots = [], array $removedRoles = [] ) {
		foreach ( $modifiedSlots as $slot ) {
			$this->modifySlot( $slot );
		}

		foreach ( $removedRoles as $role ) {
			$this->removeSlot( $role );
		}
	}

	/**
	 * Returns a list of modified slot roles, that is, roles modified by calling modifySlot(),
	 * and not later removed by calling removeSlot().
	 *
	 * Note that slots in modified roles may still be inherited slots. This is for instance
	 * the case when the RevisionSlotsUpdate objects represents some kind of rollback
	 * operation, in which slots that existed in an earlier revision are restored in
	 * a new revision.
	 *
	 * @return string[]
	 */
	public function getModifiedRoles() {
		return array_keys( $this->modifiedSlots );
	}

	/**
	 * Returns a list of removed slot roles, that is, roles removed by calling removeSlot(),
	 * and not later re-introduced by calling modifySlot().
	 *
	 * @return string[]
	 */
	public function getRemovedRoles() {
		return array_keys( $this->removedRoles );
	}

	/**
	 * Returns a list of all slot roles that modified or removed.
	 *
	 * @return string[]
	 */
	public function getTouchedRoles() {
		return array_merge( $this->getModifiedRoles(), $this->getRemovedRoles() );
	}

	/**
	 * Sets the given slot to be modified.
	 * If a slot with the same role is already present, it is replaced.
	 *
	 * The roles used with modifySlot() will be returned from getModifiedRoles(),
	 * unless overwritten with removeSlot().
	 *
	 * @param SlotRecord $slot
	 */
	public function modifySlot( SlotRecord $slot ) {
		$role = $slot->getRole();

		// XXX: We should perhaps require this to be an unsaved slot!
		unset( $this->removedRoles[$role] );
		$this->modifiedSlots[$role] = $slot;
	}

	/**
	 * Sets the content for the slot with the given role to be modified.
	 * If a slot with the same role is already present, it is replaced.
	 *
	 * @param string $role
	 * @param Content $content
	 */
	public function modifyContent( $role, Content $content ) {
		$slot = SlotRecord::newUnsaved( $role, $content );
		$this->modifySlot( $slot );
	}

	/**
	 * Remove the slot for the given role, discontinue the corresponding stream.
	 *
	 * The roles used with removeSlot() will be returned from getRemovedSlots(),
	 * unless overwritten with modifySlot().
	 *
	 * @param string $role
	 */
	public function removeSlot( $role ) {
		unset( $this->modifiedSlots[$role] );
		$this->removedRoles[$role] = true;
	}

	/**
	 * Returns the SlotRecord associated with the given role, if the slot with that role
	 * was modified (and not again removed).
	 *
	 * @note If the SlotRecord returned by this method returns a non-inherited slot,
	 *       the content of that slot may or may not already have PST applied. Methods
	 *       that take a RevisionSlotsUpdate as a parameter should specify whether they
	 *       expect PST to already have been applied to all slots. Inherited slots
	 *       should never have PST applied again.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @throws RevisionAccessException if the slot does not exist or was removed.
	 * @return SlotRecord
	 */
	public function getModifiedSlot( $role ) {
		if ( isset( $this->modifiedSlots[$role] ) ) {
			return $this->modifiedSlots[$role];
		} else {
			throw new RevisionAccessException( 'No such slot: ' . $role );
		}
	}

	/**
	 * Returns whether getModifiedSlot() will return a SlotRecord for the given role.
	 *
	 * Will return true for the role names returned by getModifiedRoles(), false otherwise.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @return bool
	 */
	public function isModifiedSlot( $role ) {
		return isset( $this->modifiedSlots[$role] );
	}

	/**
	 * Returns whether the given role is to be removed from the page.
	 *
	 * Will return true for the role names returned by getRemovedRoles(), false otherwise.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @return bool
	 */
	public function isRemovedSlot( $role ) {
		return isset( $this->removedRoles[$role] );
	}

	/**
	 * Returns true if $other represents the same update - that is,
	 * if all methods defined by RevisionSlotsUpdate when called on $this or $other
	 * will yield the same result when called with the same parameters.
	 *
	 * SlotRecords for the same role are compared based on their model and content.
	 *
	 * @param RevisionSlotsUpdate $other
	 * @return bool
	 */
	public function hasSameUpdates( RevisionSlotsUpdate $other ) {
		// NOTE: use != not !==, since the order of entries is not significant!

		if ( $this->getModifiedRoles() != $other->getModifiedRoles() ) {
			return false;
		}

		if ( $this->getRemovedRoles() != $other->getRemovedRoles() ) {
			return false;
		}

		foreach ( $this->getModifiedRoles() as $role ) {
			$s = $this->getModifiedSlot( $role );
			$t = $other->getModifiedSlot( $role );

			if ( !$s->hasSameContent( $t ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Applies this update to the given MutableRevisionSlots, setting all modified slots,
	 * and removing all removed roles.
	 *
	 * @param MutableRevisionSlots $slots
	 */
	public function apply( MutableRevisionSlots $slots ) {
		foreach ( $this->getModifiedRoles() as $role ) {
			$slots->setSlot( $this->getModifiedSlot( $role ) );
		}

		foreach ( $this->getRemovedRoles() as $role ) {
			$slots->removeSlot( $role );
		}
	}

}
