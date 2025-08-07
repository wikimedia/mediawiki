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

namespace MediaWiki\Revision;

use MediaWiki\Content\Content;

/**
 * Mutable version of RevisionSlots, for constructing a new revision.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\MutableRevisionSlots
 */
class MutableRevisionSlots extends RevisionSlots {
	/**
	 * @var callable|null
	 */
	private $resetCallback;

	/**
	 * Constructs a MutableRevisionSlots that inherits from the given
	 * list of slots.
	 *
	 * @param SlotRecord[] $slots
	 * @param callable|null $resetCallback Callback to be triggered whenever slots change.
	 *        Signature: function ( MutableRevisionSlots ): void.
	 *
	 * @return MutableRevisionSlots
	 */
	public static function newFromParentRevisionSlots(
		array $slots,
		?callable $resetCallback = null
	) {
		$inherited = [];
		foreach ( $slots as $slot ) {
			$role = $slot->getRole();
			$inherited[$role] = SlotRecord::newInherited( $slot );
		}

		return new MutableRevisionSlots( $inherited, $resetCallback );
	}

	/**
	 * @param SlotRecord[] $slots An array of SlotRecords.
	 * @param callable|null $resetCallback Callback to be triggered whenever slots change.
	 *        Signature: function ( MutableRevisionSlots ): void.
	 */
	public function __construct( array $slots = [], ?callable $resetCallback = null ) {
		parent::__construct( $slots );
		$this->resetCallback = $resetCallback;
	}

	/**
	 * Sets the given slot.
	 * If a slot with the same role is already present, it is replaced.
	 */
	public function setSlot( SlotRecord $slot ) {
		if ( !is_array( $this->slots ) ) {
			$this->getSlots(); // initialize $this->slots
		}

		$role = $slot->getRole();
		$this->slots[$role] = $slot;
		$this->triggerResetCallback();
	}

	/**
	 * Sets the given slot to an inherited version of $slot.
	 * If a slot with the same role is already present, it is replaced.
	 */
	public function inheritSlot( SlotRecord $slot ) {
		$this->setSlot( SlotRecord::newInherited( $slot ) );
	}

	/**
	 * Sets the content for the slot with the given role.
	 * If a slot with the same role is already present, it is replaced.
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
	 * @param string $role
	 */
	public function removeSlot( $role ) {
		if ( !is_array( $this->slots ) ) {
			$this->getSlots();  // initialize $this->slots
		}

		unset( $this->slots[$role] );
		$this->triggerResetCallback();
	}

	/**
	 * Trigger the reset callback supplied to the constructor, if any.
	 */
	private function triggerResetCallback() {
		if ( $this->resetCallback ) {
			( $this->resetCallback )( $this );
		}
	}

}
