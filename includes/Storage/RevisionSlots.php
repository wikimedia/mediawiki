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
use OutOfBoundsException;
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
	 * @param SlotRecord[]|callable $slots SlotRecords with the slot roles as keys,
	 *        or a callback that returns such a structure.
	 */
	public function __construct( $slots ) {
		Assert::parameterType( 'array|callable', $slots, '$slots' );

		$this->slots = $slots;
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
	 * @return Content
	 * @throws RevisionLookupException
	 */
	public function getContent( $role ) {
		return $this->getSlot( $role )->getContent()->copy();
	}

	/**
	 * Returns the SlotRecord of the given slot.
	 * Call getSlotNames() to get a list of available slots.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @return SlotRecord
	 * @throws RevisionLookupException
	 */
	public function getSlot( $role ) {
		$slots = $this->getSlots();

		if ( isset( $slots[$role] ) ) {
			// Return a copy to be safe. Immutable content objects return $this from copy().
			return $slots[$role];
		} else {
			throw new RevisionLookupException( 'No such slot: ' . $role );
		}
	}

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 * getContent() will succeed only for the names returned by this method.
	 *
	 * @return string[]
	 */
	public function getSlotRoles() { // FIXME: rename to getSlotRoles!
		$slots = $this->getSlots();
		return array_keys( $slots );
	}

	/**
	 * @return SlotRecord[] revision slot/content rows
	 */
	public function getSlots() {
		if ( is_callable( $this->slots ) ) {
			$this->slots = call_user_func( $this->slots );
			Assert::postcondition(
				is_array( $this->slots ),
				'Slots info callback should return an array of objects'
			);
		}

		return $this->slots;
	}

	/**
	 * Computes the total size of the revision's slots.
	 *
	 * @return int
	 */
	public function computeSize() {
		return array_reduce( $this->getSlots(), function ( $accu, SlotRecord $slot ) {
			return $accu + $slot->getSize();
		}, 0 );
	}

	/**
	 * Computes the combined hash of the revisions's slots.
	 *
	 * For backwards compatibility, the combined hash of a single slot
	 * is that slot's hash.
	 *
	 * @return string
	 */
	public function computeSha1() {
		$slots = $this->getSlots();
		ksort( $slots );

		return array_reduce( $slots, function ( $accu, SlotRecord $slot ) {
			return $accu === null
				?  $slot->getSha1()
				: SlotRecord::base36Sha1( $accu . $slot->getSha1() );
		}, null );
	}

}