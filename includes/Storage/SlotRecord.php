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

use Closure;
use Content;
use LogicException;
use OutOfBoundsException;
use Wikimedia\Assert\Assert;

/**
 * Value object representing a content slot associated with a page revision.
 * SlotRecord is a low level entity and may expose details of the underlying database schema.
 * SlotRecord provides direct access to a Content object. That access may be implemented through a
 * callback.
 *
 * @since 1.30
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
	 * @param SlotRecord $slot
	 *
	 * @return SlotRecord
	 */
	public static function newInherited( SlotRecord $slot ) {
		$row = $slot->row;
		$row->slot_inherited = true;
		$row->slot_revision = null; // not yet known

		return new SlotRecord( $row, $slot->content );
	}

	/**
	 * @param string $role
	 * @param Content $content
	 *
	 * @return SlotRecord
	 */
	public static function newFromContent( $role, Content $content ) {
		$row = [
			'slot_id' => null, // not yet known
			'slot_address' => null, // not yet known. need setter?
			'slot_revision' => null, // not yet known
			'slot_page' => null, // not yet known
			'slot_inherited' => false,
			'slot_role' => $role, // FIXME: int or string?!
		];
		$row->slot_inherited = true;

		return new SlotRecord( (object)$row, $content );
	}

	/**
	 * SlotRecord constructor.
	 *
	 * @param object $row A database row composed of fields of the slot and content tables,
	 *        as a raw object. Any field value can be a callabck that produces the field value
	 *        given this SlotRecord as a parameter.
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
			$fields = get_object_vars( $this->row );

			// distinguish between unknown and uninitialized fields
			if ( array_key_exists( $name, $fields ) ) {
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
	 * @return string|null Returns the string value, or null if the field is NULL in the DB row.
	 */
	public function getStringField( $name ) {
		// FIXME: consider making this private, and exposing deprecated getters for blob_flags, etc.
		return strval( $this->getField( $name ) );
	}

	/**
	 * Returns the int value of a data field from the database row supplied to the constructor.
	 *
	 * @param string $name
	 *
	 * @throws OutOfBoundsException
	 * @return int|null Returns the int value, or null if the field is NULL in the DB row.
	 */
	public function getIntField( $name ) {
		// FIXME: consider making this private, and exposing deprecated getters for blob_flags, etc.
		return intval( $this->getField( $name ) );
	}

	/**
	 * @param string $name
	 * @return bool whether this record contains the given field
	 */
	public function hasField( $name ) {
		return isset( $this->row[$name] );
	}

	/**
	 * Returns the ID of the page this slot is associated with.
	 *
	 * @return int
	 */
	public function getPage() {
		return $this->getIntField( 'slot_page' );
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
		return $this->hasField( 'slot_address' );
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
		// TODO: resolve role ID to role name here!
		return $this->getStringField( 'slot_role' );
	}

	/**
	 * Returns the address of this slot's content.
	 * This address can be used with RevisionContentStore to load the Content object.
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
		} catch ( OutOfBoundsException $ex ) {
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
		} catch ( OutOfBoundsException $ex ) {
			$format = $this->getFormat();
			$data = $this->getContent()->serialize( $format );
			$sha1 = self::base36Sha1( $data );
			$this->setField( 'cont_sha1', $sha1 );
		}

		return $sha1;
	}

	/**
	 * Returns the content model
	 *
	 * @return string the content model of the content
	 */
	public function getModel() {
		try {
			$model = $this->getStringField( 'cont_model' ); // FIXME: will need mapping once it's an int!
		} catch ( OutOfBoundsException $ex ) {
			$model = $this->getContent()->getModel();
			$this->setField( 'cont_model', $model );
		}

		return $model;
	}

	/**
	 * Returns the content serialization format
	 *
	 * @return string the content model of the content
	 */
	private function getFormat() {
		try {
			$format = $this->getStringField( 'cont_format' );  // FIXME: will need mapping once it's an int!
		} catch ( OutOfBoundsException $ex ) {
			$format = $this->getContent()->getDefaultFormat();
			$this->setField( 'cont_format', $format );
		}

		return $format;
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