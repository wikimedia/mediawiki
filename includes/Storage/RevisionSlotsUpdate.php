<?php
/**
 * Mutable version of RevisionSlots, for constructing a new revision.
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

/**
 * Interface for representing a modification of revision slots.
 *
 * @since 1.31
 */
interface RevisionSlotsUpdate {

	/**
	 * Returns a list of modified slot roles.
	 *
	 * @return string[]
	 */
	public function getModifiedRoles();

	/**
	 * Returns a list of removed slot roles.
	 *
	 * @return string[]
	 */
	public function getRemovedRoles();

	/**
	 * Returns the SlotRecord of the given slot.
	 * Call getSlotNames() to get a list of available slots.
	 *
	 * Note that implementations of getSlot() are free to return a SlotRecord
	 * for roles that were not returned by getModifiedRoles(). This may indicate that
	 * the slot exists but was unmodified, but this behavior is not required.
	 *
	 * Implementations of getSlots() must return a SlotRecord() for any role returned
	 * by getModifiedRoles(), and must throw a RevisionAccessException when called
	 * with a role returned by getRemovedRoles().
	 *
	 * @note Signature must match RevisionSlot::getSlot()
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @throws RevisionAccessException if the slot does not exist, was removed, or could not be
	 *         loaded.
	 * @return SlotRecord
	 */
	public function getSlot( $role );

	/**
	 * Returns whether getSlot() will return a SlotRecord for the given role.
	 *
	 * Implementations must return true for at least the role names returned by getModifiedRoles().
	 * Implementations must return false for the role names returned by getRemovedRoles().
	 *
	 * @note Signature must match RevisionSlot::hasSlot()
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @return bool
	 */
	public function hasSlot( $role );

	/**
	 * Returns true if $other represents the same update - that is,
	 * if all methods defined by RevisionSlotsUpdate when called on $this or $other
	 * will yield the same result when called with the same parameters.
	 *
	 * For calls to getSlot(), only calls with roles returned by getModifiedRoles() are considered.
	 * SlotRecords for the same role are compared based on their content.
	 *
	 * @param RevisionSlotsUpdate $other
	 * @return bool
	 */
	public function hasSameUpdates( RevisionSlotsUpdate $other );

}
