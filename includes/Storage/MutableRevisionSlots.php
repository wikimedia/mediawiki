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
use Wikimedia\Assert\Assert;

/**
 * Mutable version of RevisionSlots, for constructing a new revision.
 *
 * This implements RevisionSlotsUpdate by tracking calls to removeSlot() and setSlot()/setContent().
 *
 * @since 1.31
 */
class MutableRevisionSlots extends RevisionSlots implements RevisionSlotsUpdate {

	/**
	 * @var bool[] modified roles, stored in the keys of the array.
	 */
	private $modifiedRoles = [];

	/**
	 * @var bool[] removed roles, stored in the keys of the array.
	 */
	private $removedRoles = [];

	/**
	 * Constructs a MutableRevisionSlots that inherits from the given
	 * list of slots.
	 *
	 * @param SlotRecord[] $parentSlots
	 *
	 * @return MutableRevisionSlots
	 */
	public static function newFromParentRevisionSlots( array $parentSlots ) {
		Assert::parameterElementType( SlotRecord::class, $parentSlots, '$slots' );

		$slots = new MutableRevisionSlots();
		foreach ( $parentSlots as $slot ) {
			$slots->inheritSlot( $slot );
		}

		return $slots;
	}

	/**
	 * @param Content[] $newContent Associative array, mapping role names to Content objects
	 * @param string[] $removedRoles the names of slots to remove.
	 *
	 * @return MutableRevisionSlots
	 */
	public static function newAsUpdate( array $newContent, array $removedRoles = [] ) {
		$slots = new MutableRevisionSlots();

		foreach ( $newContent as $role => $content ) {
			Assert::precondition( is_string( $role ), '$newContent must use role names as keys' );
			$slots->setContent( $role, $content );
		}

		foreach ( $removedRoles as $role ) {
			Assert::precondition( is_string( $role ), '$removedRoles must contain role names' );
			$slots->removeSlot( $role );
		}

		return $slots;
	}

	/**
	 * @param SlotRecord[] $inheritedSlots Inherited SlotRecords.
	 */
	public function __construct( array $inheritedSlots = [] ) {
		parent::__construct( $inheritedSlots );
		// XXX: we could check here that these slots are actually complete, and not unsaved.
		// Unsaved slots should be added using addSlot() or addContent().
	}

	/**
	 * Sets the given slot.
	 * If a slot with the same role is already present, it is replaced.
	 *
	 * The roles used with setSlot() will be returned from getModifiedRoles(),
	 * unless overwritten with removeSlot() or inheritSlot().
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param SlotRecord $slot
	 */
	public function setSlot( SlotRecord $slot ) {
		$role = $slot->getRole();

		// XXX: We should perhaps require this to be an unsaved slot!
		$this->slots[$role] = $slot;
		unset( $this->removedRoles[$role] );
		$this->modifiedRoles[$role] = true;
	}

	/**
	 * Inherits the given slot.
	 * If a slot with the same role is already present, it is replaced.
	 *
	 * This will cause the slot's role to not be returned from getModifiedRoles() or
	 * getRemovedRoles().
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param SlotRecord $slot
	 * @return SlotRecord The inherited version of $slot.
	 */
	public function inheritSlot( SlotRecord $slot ) {
		$inherited = SlotRecord::newInherited( $slot );
		$role = $inherited->getRole();

		$this->slots[$role] = $inherited;
		unset( $this->removedRoles[$role] );
		unset( $this->modifiedRoles[$role] );

		return $inherited;
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
	 * The roles used with removeSlot() will be returned from getRemovedSlots(),
	 * unless overwritten with setSlot().
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param string $role
	 */
	public function removeSlot( $role ) {
		unset( $this->slots[$role] );
		unset( $this->modifiedRoles[$role] );
		$this->removedRoles[$role] = true;
	}

	/**
	 * @param RevisionSlotsUpdate $update
	 */
	public function applyUpdate( RevisionSlotsUpdate $update ) {
		foreach ( $update->getModifiedRoles() as $role ) {
			$slot = $update->getSlot( $role );
			$this->setSlot( $slot );
		}

		foreach ( $update->getRemovedRoles() as $role ) {
			$this->removeSlot( $role );
		}
	}

	/**
	 * Returns a list of modified slot roles.
	 *
	 * @return string[]
	 */
	public function getModifiedRoles() {
		return array_keys( $this->modifiedRoles );
	}

	/**
	 * Returns a list of removed slot roles.
	 *
	 * @return string[]
	 */
	public function getRemovedRoles() {
		return array_keys( $this->removedRoles );
	}

	/**
	 * Returns true if $other represents the same update.
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
			$s = $this->getSlot( $role );
			$t = $other->getSlot( $role );

			if ( $s->getModel() !== $t->getModel() ) {
				return false;
			}

			if ( $s->getSize() !== $t->getSize() ) {
				return false;
			}

			if ( $s->getSha1() !== $t->getSha1() ) {
				return false;
			}
		}

		return true;
	}

}
