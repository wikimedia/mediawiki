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

use Content;

/**
 * Mutable version of RevisionSlots, for constructing a new revision.
 *
 * @since 1.31
 */
class MutableRevisionSlots extends RevisionSlots {

	/**
	 * Constructs a MutableRevisionSlots that inherits from the given
	 * list of slots.
	 *
	 * @param SlotRecord[] $slots
	 *
	 * @return MutableRevisionSlots
	 */
	public static function newFromParentRevisionSlots( array $slots ) {
		$inherited = [];
		foreach ( $slots as $slot ) {
			$role = $slot->getRole();
			$inherited[$role] = SlotRecord::newInherited( $slot );
		}

		return new MutableRevisionSlots( $inherited );
	}

	/**
	 * @param SlotRecord[] $slots An array of SlotRecords.
	 */
	public function __construct( array $slots = [] ) {
		parent::__construct( $slots );
	}

	/**
	 * Sets the given slot.
	 * If a slot with the same role is already present, it is replaced.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param SlotRecord $slot
	 */
	public function setSlot( SlotRecord $slot ) {
		if ( !is_array( $this->slots ) ) {
			$this->getSlots(); // initialize $this->slots
		}

		$role = $slot->getRole();
		$this->slots[$role] = $slot;
	}

	/**
	 * Sets the content for the slot with the given role.
	 * If a slot with the same role is already present, it is replaced.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param string $role
	 * @param Content $content
	 */
	public function setContent( $role, Content $content ) {
		$slot = SlotRecord::newUnsaved( $role, $content );
		$this->setSlot( $slot );
	}

	/**
	 * Remove the slot for the given role, discontinue the corresponding stream.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param string $role
	 */
	public function removeSlot( $role ) {
		if ( !is_array( $this->slots ) ) {
			$this->getSlots();  // initialize $this->slots
		}

		unset( $this->slots[$role] );
	}

	/**
	 * Return all slots that are not inherited.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @return SlotRecord[]
	 */
	public function getTouchedSlots() {
		return array_filter(
			$this->getSlots(),
			function ( SlotRecord $slot ) {
				return !$slot->isInherited();
			}
		);
	}

	/**
	 * Return all slots that are inherited.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @return SlotRecord[]
	 */
	public function getInheritedSlots() {
		return array_filter(
			$this->getSlots(),
			function ( SlotRecord $slot ) {
				return $slot->isInherited();
			}
		);
	}

}
