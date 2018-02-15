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
		$row = $slot->row;

		foreach ( $overrides as $key => $value ) {
			$row->$key = $value;
		}

		return new SlotRecord( $row, $slot->content );
	}

	/**
	 * Constructs a new SlotRecord for a new revision, inheriting the content of the given SlotRecord
	 * of a previous revision.
	 *
	 * @param SlotRecord $slot
	 *
	 * @return SlotRecord
	 */
	public static function newInherited( SlotRecord $slot ) {
		return self::newDerived( $slot, [
			'slot_inherited' => true,
			'slot_revision' => null,
		] );
	}

	/**
	 * Constructs a new Slot from a Content object for a new revision.
	 * This is the preferred way to construct a slot for storing Content that
	 * resulted from a user edit.
	 *
	 * @param string $role
	 * @param Content $content
	 * @param bool $inherited
	 *
	 * @return SlotRecord
	 */
	public static function newUnsaved( $role, Content $content, $inherited = false ) {
		Assert::parameterType( 'boolean', $inherited, '$inherited' );
		Assert::parameterType( 'string', $role, '$role' );

		$row = [
			'slot_id' => null, // not yet known
			'slot_address' => null, // not yet known. need setter?
			'slot_revision' => null, // not yet known
			'slot_inherited' => $inherited,
			'cont_size' => null, // compute later
			'cont_sha1' => null, // compute later
			'role_name' => $role,
			'model_name' => $content->getModel(),
		];

		return new SlotRecord( (object)$row, $content );
	}

	/**
	 * Constructs a SlotRecord for a newly saved revision, based on the proto-slot that was
	 * supplied to the code that performed the save operation. This adds information that
	 * has only become available during saving, particularly the revision ID and blob address.
	 *
	 * @param int $revisionId
	 * @param string $blobAddress
	 * @param SlotRecord $protoSlot The proto-slot that was provided to the code that then
	 *
	 * @return SlotRecord
	 */
	public static function newSaved( $revisionId, $blobAddress, SlotRecord $protoSlot ) {
		Assert::parameterType( 'integer', $revisionId, '$revisionId' );
		Assert::parameterType( 'string', $blobAddress, '$blobAddress' );

		return self::newDerived( $protoSlot, [
			'slot_revision' => $revisionId,
			'cont_address' => $blobAddress,
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
	 * @return mixed Returns the field's value, or null if the field is NULL in the DB row.
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
		return $this->getIntField( 'slot_revision' );
	}

	/**
	 * Whether this slot was inherited from an older revision.
	 *
	 * @return bool
	 */
	public function isInherited() {
		return $this->getIntField( 'slot_inherited' ) !== 0;
	}

	/**
	 * Whether this slot has an address. Slots will have an address if their
	 * content has been stored. While building a new revision,
	 * SlotRecords will not have an address associated.
	 *
	 * @return bool
	 */
	public function hasAddress() {
		return $this->hasField( 'cont_address' );
	}

	/**
	 * Whether this slot has revision ID associated. Slots will have a revision ID associated
	 * only if they were loaded as part of an existing revision. While building a new revision,
	 * Slotrecords will not have a revision ID associated.
	 *
	 * @return bool
	 */
	public function hasRevision() {
		return $this->hasField( 'slot_revision' );
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
		return $this->getStringField( 'cont_address' );
	}

	/**
	 * Returns the content size
	 *
	 * @return int size of the content, in bogo-bytes, as reported by Content::getSize.
	 */
	public function getSize() {
		try {
			$size = $this->getIntField( 'cont_size' );
		} catch ( IncompleteRevisionException $ex ) {
			$size = $this->getContent()->getSize();
			$this->setField( 'cont_size', $size );
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
			$sha1 = $this->getStringField( 'cont_sha1' );
		} catch ( IncompleteRevisionException $ex ) {
			$format = $this->hasField( 'format_name' )
				? $this->getStringField( 'format_name' )
				: null;

			$data = $this->getContent()->serialize( $format );
			$sha1 = self::base36Sha1( $data );
			$this->setField( 'cont_sha1', $sha1 );
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

}
