<?php
/**
 * Value object representing a content slot associated with a page revision.
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
use InvalidArgumentException;
use LogicException;
use OutOfBoundsException;
use Wikimedia\Assert\Assert;

/**
 * Value object representing a content slot associated with a page revision.
 * SlotRecord provides direct access to a Content object.
 * That access may be implemented through a callback.
 *
 * @since 1.31
 */
class SlotRecord {

	/**
	 * @var object database result row, as a raw object
	 */
	private $row;

	/**
	 * @var Content|callable
	 */
	private $content;

	/**
	 * Returns a new SlotRecord just like the given $slot, except that calling getContent()
	 * will fail with an exception.
	 *
	 * @param SlotRecord $slot
	 *
	 * @return SlotRecord
	 */
	public static function newWithSuppressedContent( SlotRecord $slot ) {
		$row = $slot->row;

		return new SlotRecord( $row, function () {
			throw new SuppressedDataException( 'Content suppressed!' );
		} );
	}

	/**
	 * Constructs a new SlotRecord from an existing SlotRecord, overriding some fields.
	 * The slot's content cannot be overwritten.
	 *
	 * @param SlotRecord $slot
	 * @param array $overrides
	 *
	 * @return SlotRecord
	 */
	private static function newDerived( SlotRecord $slot, array $overrides = [] ) {
		$row = clone $slot->row;
		$row->slot_id = null; // never copy the row ID!

		foreach ( $overrides as $key => $value ) {
			$row->$key = $value;
		}

		return new SlotRecord( $row, $slot->content );
	}

	/**
	 * Constructs a new SlotRecord for a new revision, inheriting the content of the given SlotRecord
	 * of a previous revision.
	 *
	 * Note that a SlotRecord constructed this way are intended as prototypes,
	 * to be used wit newSaved(). They are incomplete, so some getters such as
	 * getRevision() will fail.
	 *
	 * @param SlotRecord $slot
	 *
	 * @return SlotRecord
	 */
	public static function newInherited( SlotRecord $slot ) {
		// Sanity check - we can't inherit from a Slot that's not attached to a revision.
		$slot->getRevision();
		$slot->getOrigin();
		$slot->getAddress();

		// NOTE: slot_origin and content_address are copied from $slot.
		return self::newDerived( $slot, [
			'slot_revision_id' => null,
		] );
	}

	/**
	 * Constructs a new Slot from a Content object for a new revision.
	 * This is the preferred way to construct a slot for storing Content that
	 * resulted from a user edit. The slot is assumed to be not inherited.
	 *
	 * Note that a SlotRecord constructed this way are intended as prototypes,
	 * to be used wit newSaved(). They are incomplete, so some getters such as
	 * getAddress() will fail.
	 *
	 * @param string $role
	 * @param Content $content
	 *
	 * @return SlotRecord An incomplete proto-slot object, to be used with newSaved() later.
	 */
	public static function newUnsaved( $role, Content $content ) {
		Assert::parameterType( 'string', $role, '$role' );

		$row = [
			'slot_id' => null, // not yet known
			'slot_revision_id' => null, // not yet known
			'slot_origin' => null, // not yet known, will be set in newSaved()
			'content_size' => null, // compute later
			'content_sha1' => null, // compute later
			'slot_content_id' => null, // not yet known, will be set in newSaved()
			'content_address' => null, // not yet known, will be set in newSaved()
			'role_name' => $role,
			'model_name' => $content->getModel(),
		];

		return new SlotRecord( (object)$row, $content );
	}

	/**
	 * Constructs a complete SlotRecord for a newly saved revision, based on the incomplete
	 * proto-slot. This adds information that has only become available during saving,
	 * particularly the revision ID and content address.
	 *
	 * @param int $revisionId the revision the slot is to be associated with (field slot_revision_id).
	 *        If $protoSlot already has a revision, it must be the same.
	 * @param int $contentId the ID of the row in the content table describing the content
	 *        referenced by $contentAddress (field slot_content_id).
	 *        If $protoSlot already has a content ID, it must be the same.
	 * @param string $contentAddress the slot's content address (field content_address).
	 *        If $protoSlot already has an address, it must be the same.
	 * @param SlotRecord $protoSlot The proto-slot that was provided as input for creating a new
	 *        revision. $protoSlot must have a content address if inherited.
	 *
	 * @return SlotRecord If the state of $protoSlot is inappropriate for saving a new revision.
	 */
	public static function newSaved(
		$revisionId,
		$contentId,
		$contentAddress,
		SlotRecord $protoSlot
	) {
		Assert::parameterType( 'integer', $revisionId, '$revisionId' );
		Assert::parameterType( 'integer', $contentId, '$contentId' );
		Assert::parameterType( 'string', $contentAddress, '$contentAddress' );

		if ( $protoSlot->hasRevision() && $protoSlot->getRevision() !== $revisionId ) {
			throw new LogicException(
				"Mismatching revision ID $revisionId: "
				. "The slot already belongs to revision {$protoSlot->getRevision()}. "
				. "Use SlotRecord::newInherited() to re-use content between revisions."
			);
		}

		if ( $protoSlot->hasAddress() && $protoSlot->getAddress() !== $contentAddress ) {
			throw new LogicException(
				"Mismatching blob address $contentAddress: "
				. "The slot already has content at {$protoSlot->getAddress()}."
			);
		}

		if ( $protoSlot->hasAddress() && $protoSlot->getContentId() !== $contentId ) {
			throw new LogicException(
				"Mismatching content ID $contentId: "
				. "The slot already has content row {$protoSlot->getContentId()} associated."
			);
		}

		if ( $protoSlot->isInherited() ) {
			if ( !$protoSlot->hasAddress() ) {
				throw new InvalidArgumentException(
					"An inherited blob should have a content address!"
				);
			}
			if ( !$protoSlot->hasField( 'slot_origin' ) ) {
				throw new InvalidArgumentException(
					"A saved inherited slot should have an origin set!"
				);
			}
			$origin = $protoSlot->getOrigin();
		} else {
			$origin = $revisionId;
		}

		return self::newDerived( $protoSlot, [
			'slot_revision_id' => $revisionId,
			'slot_content_id' => $contentId,
			'slot_origin' => $origin,
			'content_address' => $contentAddress,
		] );
	}

	/**
	 * SlotRecord constructor.
	 *
	 * The following fields are supported by the $row parameter:
	 *
	 *   $row->blob_data
	 *   $row->blob_address
	 *
	 * @param object $row A database row composed of fields of the slot and content tables,
	 *        as a raw object. Any field value can be a callback that produces the field value
	 *        given this SlotRecord as a parameter. However, plain strings cannot be used as
	 *        callbacks here, for security reasons.
	 * @param Content|callable $content The content object associated with the slot, or a
	 *        callback that will return that Content object, given this SlotRecord as a parameter.
	 */
	public function __construct( $row, $content ) {
		Assert::parameterType( 'object', $row, '$row' );
		Assert::parameterType( 'Content|callable', $content, '$content' );

		Assert::parameter(
			property_exists( $row, 'slot_id' ),
			'$row->slot_id',
			'must exist'
		);
		Assert::parameter(
			property_exists( $row, 'slot_revision_id' ),
			'$row->slot_revision_id',
			'must exist'
		);
		Assert::parameter(
			property_exists( $row, 'slot_content_id' ),
			'$row->slot_content_id',
			'must exist'
		);
		Assert::parameter(
			property_exists( $row, 'content_address' ),
			'$row->content_address',
			'must exist'
		);
		Assert::parameter(
			property_exists( $row, 'model_name' ),
			'$row->model_name',
			'must exist'
		);
		Assert::parameter(
			property_exists( $row, 'slot_origin' ),
			'$row->slot_origin',
			'must exist'
		);
		Assert::parameter(
			!property_exists( $row, 'slot_inherited' ),
			'$row->slot_inherited',
			'must not exist'
		);
		Assert::parameter(
			!property_exists( $row, 'slot_revision' ),
			'$row->slot_revision',
			'must not exist'
		);

		$this->row = $row;
		$this->content = $content;
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
	 *
	 * @note This is free to load Content from whatever subsystem is necessary,
	 * performing potentially expensive operations and triggering I/O-related
	 * failure modes.
	 *
	 * @note This method does not apply audience filtering.
	 *
	 * @throws SuppressedDataException if access to the content is not allowed according
	 * to the audience check performed by RevisionRecord::getSlot().
	 *
	 * @return Content The slot's content. This is a direct reference to the internal instance,
	 * copy before exposing to application logic!
	 */
	public function getContent() {
		if ( $this->content instanceof Content ) {
			return $this->content;
		}

		$obj = call_user_func( $this->content, $this );

		Assert::postcondition(
			$obj instanceof Content,
			'Slot content callback should return a Content object'
		);

		$this->content = $obj;

		return $this->content;
	}

	/**
	 * Returns the string value of a data field from the database row supplied to the constructor.
	 * If the field was set to a callback, that callback is invoked and the result returned.
	 *
	 * @param string $name
	 *
	 * @throws OutOfBoundsException
	 * @throws IncompleteRevisionException
	 * @return mixed Returns the field's value, never null.
	 */
	private function getField( $name ) {
		if ( !isset( $this->row->$name ) ) {
			// distinguish between unknown and uninitialized fields
			if ( property_exists( $this->row, $name ) ) {
				throw new IncompleteRevisionException( 'Uninitialized field: ' . $name );
			} else {
				throw new OutOfBoundsException( 'No such field: ' . $name );
			}
		}

		$value = $this->row->$name;

		// NOTE: allow callbacks, but don't trust plain string callables from the database!
		if ( !is_string( $value ) && is_callable( $value ) ) {
			$value = call_user_func( $value, $this );
			$this->setField( $name, $value );
		}

		return $value;
	}

	/**
	 * Returns the string value of a data field from the database row supplied to the constructor.
	 *
	 * @param string $name
	 *
	 * @throws OutOfBoundsException
	 * @throws IncompleteRevisionException
	 * @return string Returns the string value
	 */
	private function getStringField( $name ) {
		return strval( $this->getField( $name ) );
	}

	/**
	 * Returns the int value of a data field from the database row supplied to the constructor.
	 *
	 * @param string $name
	 *
	 * @throws OutOfBoundsException
	 * @throws IncompleteRevisionException
	 * @return int Returns the int value
	 */
	private function getIntField( $name ) {
		return intval( $this->getField( $name ) );
	}

	/**
	 * @param string $name
	 * @return bool whether this record contains the given field
	 */
	private function hasField( $name ) {
		return isset( $this->row->$name );
	}

	/**
	 * Returns the ID of the revision this slot is associated with.
	 *
	 * @return int
	 */
	public function getRevision() {
		return $this->getIntField( 'slot_revision_id' );
	}

	/**
	 * Returns the revision ID of the revision that originated the slot's content.
	 *
	 * @return int
	 */
	public function getOrigin() {
		return $this->getIntField( 'slot_origin' );
	}

	/**
	 * Whether this slot was inherited from an older revision.
	 *
	 * If this SlotRecord is already attached to a revision, this returns true
	 * if the slot's revision of origin is the same as the revision it belongs to.
	 *
	 * If this SlotRecord is not yet attached to a revision, this returns true
	 * if the slot already has an address.
	 *
	 * @return bool
	 */
	public function isInherited() {
		if ( $this->hasRevision() ) {
			return $this->getRevision() !== $this->getOrigin();
		} else {
			return $this->hasAddress();
		}
	}

	/**
	 * Whether this slot has an address. Slots will have an address if their
	 * content has been stored. While building a new revision,
	 * SlotRecords will not have an address associated.
	 *
	 * @return bool
	 */
	public function hasAddress() {
		return $this->hasField( 'content_address' );
	}

	/**
	 * Whether this slot has revision ID associated. Slots will have a revision ID associated
	 * only if they were loaded as part of an existing revision. While building a new revision,
	 * Slotrecords will not have a revision ID associated.
	 *
	 * @return bool
	 */
	public function hasRevision() {
		return $this->hasField( 'slot_revision_id' );
	}

	/**
	 * Returns the role of the slot.
	 *
	 * @return string
	 */
	public function getRole() {
		return $this->getStringField( 'role_name' );
	}

	/**
	 * Returns the address of this slot's content.
	 * This address can be used with BlobStore to load the Content object.
	 *
	 * @return string
	 */
	public function getAddress() {
		return $this->getStringField( 'content_address' );
	}

	/**
	 * Returns the ID of the content meta data row associated with the slot.
	 * This information should be irrelevant to application logic, it is here to allow
	 * the construction of a full row for the revision table.
	 *
	 * @return int
	 */
	public function getContentId() {
		return $this->getIntField( 'slot_content_id' );
	}

	/**
	 * Returns the content size
	 *
	 * @return int size of the content, in bogo-bytes, as reported by Content::getSize.
	 */
	public function getSize() {
		try {
			$size = $this->getIntField( 'content_size' );
		} catch ( IncompleteRevisionException $ex ) {
			$size = $this->getContent()->getSize();
			$this->setField( 'content_size', $size );
		}

		return $size;
	}

	/**
	 * Returns the content size
	 *
	 * @return string hash of the content.
	 */
	public function getSha1() {
		try {
			$sha1 = $this->getStringField( 'content_sha1' );
		} catch ( IncompleteRevisionException $ex ) {
			$format = $this->hasField( 'format_name' )
				? $this->getStringField( 'format_name' )
				: null;

			$data = $this->getContent()->serialize( $format );
			$sha1 = self::base36Sha1( $data );
			$this->setField( 'content_sha1', $sha1 );
		}

		return $sha1;
	}

	/**
	 * Returns the content model. This is the model name that decides
	 * which ContentHandler is appropriate for interpreting the
	 * data of the blob referenced by the address returned by getAddress().
	 *
	 * @return string the content model of the content
	 */
	public function getModel() {
		try {
			$model = $this->getStringField( 'model_name' );
		} catch ( IncompleteRevisionException $ex ) {
			$model = $this->getContent()->getModel();
			$this->setField( 'model_name', $model );
		}

		return $model;
	}

	/**
	 * Returns the blob serialization format as a MIME type.
	 *
	 * @note When this method returns null, the caller is expected
	 * to auto-detect the serialization format, or to rely on
	 * the default format associated with the content model.
	 *
	 * @return string|null
	 */
	public function getFormat() {
		// XXX: we currently do not plan to store the format for each slot!

		if ( $this->hasField( 'format_name' ) ) {
			return $this->getStringField( 'format_name' );
		}

		return null;
	}

	/**
	 * @param string $name
	 * @param string|int|null $value
	 */
	private function setField( $name, $value ) {
		$this->row->$name = $value;
	}

	/**
	 * Get the base 36 SHA-1 value for a string of text
	 *
	 * MCR migration note: this replaces Revision::base36Sha1
	 *
	 * @param string $blob
	 * @return string
	 */
	public static function base36Sha1( $blob ) {
		return \Wikimedia\base_convert( sha1( $blob ), 16, 36, 31 );
	}

	/**
	 * Returns true if $other has the same content as this slot.
	 * The check is performed based on the model, address size, and hash.
	 * Two slots can have the same content if they use different content addresses,
	 * but if they have the same address and the same model, they have the same content.
	 * Two slots can have the same content if they belong to different
	 * revisions or pages.
	 *
	 * Note that hasSameContent() may return false even if Content::equals returns true for
	 * the content of two slots. This may happen if the two slots have different serializations
	 * representing equivalent Content. Such false negatives are considered acceptable. Code
	 * that has to be absolutely sure the Content is really not the same if hasSameContent()
	 * returns false should call getContent() and compare the Content objects directly.
	 *
	 * @since 1.32
	 *
	 * @param SlotRecord $other
	 * @return bool
	 */
	public function hasSameContent( SlotRecord $other ) {
		if ( $other === $this ) {
			return true;
		}

		if ( $this->getModel() !== $other->getModel() ) {
			return false;
		}

		if ( $this->hasAddress()
			&& $other->hasAddress()
			&& $this->getAddress() == $other->getAddress()
		) {
			return true;
		}

		if ( $this->getSize() !== $other->getSize() ) {
			return false;
		}

		if ( $this->getSha1() !== $other->getSha1() ) {
			return false;
		}

		return true;
	}

}
