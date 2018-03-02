<?php
/**
 * Value object representing the set of slots belonging to a revision.
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
use LogicException;
use Wikimedia\Assert\Assert;

/**
 * Value object representing the set of slots belonging to a revision.
 *
 * @since 1.31
 */
class RevisionSlots {

	/** @var SlotRecord[]|callable */
	protected $slots;

	/**
	 * @param SlotRecord[]|callable $slots SlotRecords,
	 *        or a callback that returns such a structure.
	 */
	public function __construct( $slots ) {
		Assert::parameterType( 'array|callable', $slots, '$slots' );

		if ( is_callable( $slots ) ) {
			$this->slots = $slots;
		} else {
			$this->setSlotsInternal( $slots );
		}
	}

	/**
	 * @param SlotRecord[] $slots
	 */
	private function setSlotsInternal( array $slots ) {
		$this->slots = [];

		// re-key the slot array
		foreach ( $slots as $slot ) {
			$role = $slot->getRole();
			$this->slots[$role] = $slot;
		}
	}

	/**
	 * Implemented to defy serialization.
	 *
	 * @throws LogicException always
	 */
	public function __sleep() {
		throw new LogicException( __CLASS__ . ' is not serializable.' );
	}

	/**
	 * Returns the Content of the given slot.
	 * Call getSlotNames() to get a list of available slots.
	 *
	 * Note that for mutable Content objects, each call to this method will return a
	 * fresh clone.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @throws RevisionAccessException if the slot does not exist or slot data
	 *        could not be lazy-loaded.
	 * @return Content
	 */
	public function getContent( $role ) {
		// Return a copy to be safe. Immutable content objects return $this from copy().
		return $this->getSlot( $role )->getContent()->copy();
	}

	/**
	 * Returns the SlotRecord of the given slot.
	 * Call getSlotNames() to get a list of available slots.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @throws RevisionAccessException if the slot does not exist or slot data
	 *        could not be lazy-loaded.
	 * @return SlotRecord
	 */
	public function getSlot( $role ) {
		$slots = $this->getSlots();

		if ( isset( $slots[$role] ) ) {
			return $slots[$role];
		} else {
			throw new RevisionAccessException( 'No such slot: ' . $role );
		}
	}

	/**
	 * Returns whether the given slot is set.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @return bool
	 */
	public function hasSlot( $role ) {
		$slots = $this->getSlots();

		return isset( $slots[$role] );
	}

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 * getContent() will succeed only for the names returned by this method.
	 *
	 * @return string[]
	 */
	public function getSlotRoles() {
		$slots = $this->getSlots();
		return array_keys( $slots );
	}

	/**
	 * Computes the total nominal size of the revision's slots, in bogo-bytes.
	 *
	 * @warn This is potentially expensive! It may cause all slot's content to be loaded
	 * and deserialized.
	 *
	 * @return int
	 */
	public function computeSize() {
		return array_reduce( $this->getSlots(), function ( $accu, SlotRecord $slot ) {
			return $accu + $slot->getSize();
		}, 0 );
	}

	/**
	 * Returns an associative array that maps role names to SlotRecords. Each SlotRecord
	 * represents the content meta-data of a slot, together they define the content of
	 * a revision.
	 *
	 * @note This may cause the content meta-data for the revision to be lazy-loaded.
	 *
	 * @return SlotRecord[] revision slot/content rows, keyed by slot role name.
	 */
	public function getSlots() {
		if ( is_callable( $this->slots ) ) {
			$slots = call_user_func( $this->slots );

			Assert::postcondition(
				is_array( $slots ),
				'Slots info callback should return an array of objects'
			);

			$this->setSlotsInternal( $slots );
		}

		return $this->slots;
	}

	/**
	 * Computes the combined hash of the revisions's slots.
	 *
	 * @note For backwards compatibility, the combined hash of a single slot
	 * is that slot's hash. For consistency, the combined hash of an empty set of slots
	 * is the hash of the empty string.
	 *
	 * @warn This is potentially expensive! It may cause all slot's content to be loaded
	 * and deserialized, then re-serialized and hashed.
	 *
	 * @return string
	 */
	public function computeSha1() {
		$slots = $this->getSlots();
		ksort( $slots );

		if ( empty( $slots ) ) {
			return SlotRecord::base36Sha1( '' );
		}

		return array_reduce( $slots, function ( $accu, SlotRecord $slot ) {
			return $accu === null
				? $slot->getSha1()
				: SlotRecord::base36Sha1( $accu . $slot->getSha1() );
		}, null );
	}

}
