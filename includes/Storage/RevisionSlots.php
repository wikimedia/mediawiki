<?php

namespace MediaWiki\Storage;

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

use Content;
use OutOfBoundsException;
use Wikimedia\Assert\Assert;

/**
 * Value object representing the set of slots belonging to a revision.
 */
class RevisionSlots {

	/** @var SlotRecord[]|callable */
	protected $slots;

	/** @var array */
	private $slotsCallbackArgs;

	/**
	 * @param SlotRecord[]|callable $slots SlotRecords with the slot roles as keys,
	 *        or a callback that returns such a structure.
	 * @param array $slotsCallbackArgs Any arguments to be passed to the $slots callback.
	 *        If $slots is not a callback, this is ignored.
	 */
	public function __construct( $slots, array $slotsCallbackArgs = [] ) {
		Assert::parameterType( 'array|callable', $slots, '$slots' );

		$this->slots = $slots;
		$this->slotsCallbackArgs = $slotsCallbackArgs;
	}

	/**
	 * Returns the Content of the given slot of this revision.
	 * Call getSlotNames() to get a list of available slots.
	 * This method does not perform audience filtering.
	 *
	 * Note that for mutable Content objects, each call to this method will return a
	 * fresh clone.
	 *
	 * @param string $slot The role name of the desired slot
	 *
	 * @return Content
	 * @throws RevisionLookupException
	 */
	public function getContent( $slot ) {
		$slots = $this->getSlots();

		if ( isset( $slots[$slot] ) ) {
			// Return a copy to be safe. Immutable content objects return $this from copy().
			return $slots[$slot]->getContent()->copy();
		} else {
			throw new RevisionLookupException( 'No such slot: ' . $slot );
		}
	}

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 * getContent() will succeed only for the names returned by this method.
	 *
	 * @return string[]
	 */
	public function getSlotNames() {
		$slots = $this->getSlots();
		return array_keys( $slots );
	}

	/**
	 * @return SlotRecord[] revision slot/content rows
	 */
	public function getSlots() {
		if ( is_callable( $this->slots ) ) {
			$this->slots = call_user_func_array( $this->slots, $this->slotsCallbackArgs );
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