<?php

namespace MediaWiki\Storage;

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

use Content;
use OutOfBoundsException;
use Wikimedia\Assert\Assert;

/**
 * Value object representing a content slot associated with a page revision.
 * SlotRecord is a low level entity any may expose details of the underlying database schema.
 * SlotRecord provides direct access to a Content object. That access may be implemented through a
 * callback.-
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
			throw new OutOfBoundsException( 'No such field: ' . $name );
		}

		$value = $this->row->$name;

		if ( is_callable( $value ) ) {
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
	 * This address can be used with RevisionContentStore to load the COntent object.
	 *
	 * @return string
	 */
	public function getAddress() {
		return $this->getStringField( 'cont_address' );
	}

	/**
	 * Returns the content size
	 *
	 * @return int size of the content, in bogo-bytes, as repurted by Content::getSize.
	 */
	public function getSize() {
		$size = $this->getIntField( 'cont_size' );
		if ( $size === null ) {
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
		$sha1 = $this->getStringField( 'cont_sha1' );
		if ( $sha1 === null ) {
			$format = $this->getFormat();
			$data = $this->getContent()->serialize( $format );
			$sha1 = RevisionContentStore::base36Sha1( $data );
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
		$model = $this->getIntField( 'cont_model' );
		if ( $model === null ) {
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
		$format = $this->hasField( 'cont_format ' ) ? $this->getIntField( 'cont_format' ) : null;
		if ( $format === null ) {
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

}