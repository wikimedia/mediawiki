<?php
/**
 * Abstract base class for representing objects that are stored in some DB table.
 * This is basically an ORM-like wrapper around rows in database tables that
 * aims to be both simple and very flexible. It is centered around an associative
 * array of fields and various methods to do common interaction with the database.
 *
 * Documentation inline and at https://www.mediawiki.org/wiki/Manual:ORMTable
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
 * @since 1.20
 *
 * @file ORMRow.php
 * @ingroup ORM
 *
 * @licence GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

abstract class ORMRow implements IORMRow {

	/**
	 * The fields of the object.
	 * field name (w/o prefix) => value
	 *
	 * @since 1.20
	 * @var array
	 */
	protected $fields = array( 'id' => null );

	/**
	 * @since 1.20
	 * @var ORMTable
	 */
	protected $table;

	/**
	 * If the object should update summaries of linked items when changed.
	 * For example, update the course_count field in universities when a course in courses is deleted.
	 * Settings this to false can prevent needless updating work in situations
	 * such as deleting a university, which will then delete all it's courses.
	 *
	 * @since 1.20
	 * @var bool
	 */
	protected $updateSummaries = true;

	/**
	 * Indicates if the object is in summary mode.
	 * This mode indicates that only summary fields got updated,
	 * which allows for optimizations.
	 *
	 * @since 1.20
	 * @var bool
	 */
	protected $inSummaryMode = false;

	/**
	 * Constructor.
	 *
	 * @since 1.20
	 *
	 * @param IORMTable $table
	 * @param array|null $fields
	 * @param boolean $loadDefaults
	 */
	public function __construct( IORMTable $table, $fields = null, $loadDefaults = false ) {
		$this->table = $table;

		if ( !is_array( $fields ) ) {
			$fields = array();
		}

		if ( $loadDefaults ) {
			$fields = array_merge( $this->table->getDefaults(), $fields );
		}

		$this->setFields( $fields );
	}

	/**
	 * Load the specified fields from the database.
	 *
	 * @since 1.20
	 *
	 * @param array|null $fields
	 * @param boolean $override
	 * @param boolean $skipLoaded
	 *
	 * @return bool Success indicator
	 */
	public function loadFields( $fields = null, $override = true, $skipLoaded = false ) {
		if ( is_null( $this->getId() ) ) {
			return false;
		}

		if ( is_null( $fields ) ) {
			$fields = array_keys( $this->table->getFields() );
		}

		if ( $skipLoaded ) {
			$fields = array_diff( $fields, array_keys( $this->fields ) );
		}

		if ( !empty( $fields ) ) {
			$result = $this->table->rawSelectRow(
				$this->table->getPrefixedFields( $fields ),
				array( $this->table->getPrefixedField( 'id' ) => $this->getId() ),
				array( 'LIMIT' => 1 )
			);

			if ( $result !== false ) {
				$this->setFields( $this->table->getFieldsFromDBResult( $result ), $override );
				return true;
			}
			return false;
		}

		return true;
	}

	/**
	 * Gets the value of a field.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function getField( $name, $default = null ) {
		if ( $this->hasField( $name ) ) {
			return $this->fields[$name];
		} elseif ( !is_null( $default ) ) {
			return $default;
		} else {
			throw new MWException( 'Attempted to get not-set field ' . $name );
		}
	}

	/**
	 * Gets the value of a field but first loads it if not done so already.
	 *
	 * @since 1.20
	 *
	 * @param string$name
	 *
	 * @return mixed
	 */
	public function loadAndGetField( $name ) {
		if ( !$this->hasField( $name ) ) {
			$this->loadFields( array( $name ) );
		}

		return $this->getField( $name );
	}

	/**
	 * Remove a field.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 */
	public function removeField( $name ) {
		unset( $this->fields[$name] );
	}

	/**
	 * Returns the objects database id.
	 *
	 * @since 1.20
	 *
	 * @return integer|null
	 */
	public function getId() {
		return $this->getField( 'id' );
	}

	/**
	 * Sets the objects database id.
	 *
	 * @since 1.20
	 *
	 * @param integer|null $id
	 */
	public function setId( $id ) {
		$this->setField( 'id', $id );
	}

	/**
	 * Gets if a certain field is set.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function hasField( $name ) {
		return array_key_exists( $name, $this->fields );
	}

	/**
	 * Gets if the id field is set.
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function hasIdField() {
		return $this->hasField( 'id' )
			&& !is_null( $this->getField( 'id' ) );
	}

	/**
	 * Sets multiple fields.
	 *
	 * @since 1.20
	 *
	 * @param array $fields The fields to set
	 * @param boolean $override Override already set fields with the provided values?
	 */
	public function setFields( array $fields, $override = true ) {
		foreach ( $fields as $name => $value ) {
			if ( $override || !$this->hasField( $name ) ) {
				$this->setField( $name, $value );
			}
		}
	}

	/**
	 * Gets the fields => values to write to the table.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	protected function getWriteValues() {
		$values = array();

		foreach ( $this->table->getFields() as $name => $type ) {
			if ( array_key_exists( $name, $this->fields ) ) {
				$value = $this->fields[$name];

				switch ( $type ) {
					case 'array':
						$value = (array)$value;
					case 'blob':
						$value = serialize( $value );
				}

				$values[$this->table->getPrefixedField( $name )] = $value;
			}
		}

		return $values;
	}

	/**
	 * Serializes the object to an associative array which
	 * can then easily be converted into JSON or similar.
	 *
	 * @since 1.20
	 *
	 * @param null|array $fields
	 * @param boolean $incNullId
	 *
	 * @return array
	 */
	public function toArray( $fields = null, $incNullId = false ) {
		$data = array();
		$setFields = array();

		if ( !is_array( $fields ) ) {
			$setFields = $this->getSetFieldNames();
		} else {
			foreach ( $fields as $field ) {
				if ( $this->hasField( $field ) ) {
					$setFields[] = $field;
				}
			}
		}

		foreach ( $setFields as $field ) {
			if ( $incNullId || $field != 'id' || $this->hasIdField() ) {
				$data[$field] = $this->getField( $field );
			}
		}

		return $data;
	}

	/**
	 * Load the default values, via getDefaults.
	 *
	 * @since 1.20
	 *
	 * @param boolean $override
	 */
	public function loadDefaults( $override = true ) {
		$this->setFields( $this->table->getDefaults(), $override );
	}

	/**
	 * Writes the answer to the database, either updating it
	 * when it already exists, or inserting it when it doesn't.
	 *
	 * @since 1.20
	 *
	 * @param string|null $functionName
	 *
	 * @return boolean Success indicator
	 */
	public function save( $functionName = null ) {
		if ( $this->hasIdField() ) {
			return $this->saveExisting( $functionName );
		} else {
			return $this->insert( $functionName );
		}
	}

	/**
	 * Updates the object in the database.
	 *
	 * @since 1.20
	 *
	 * @param string|null $functionName
	 *
	 * @return boolean Success indicator
	 */
	protected function saveExisting( $functionName = null ) {
		$dbw = wfGetDB( DB_MASTER );

		$success = $dbw->update(
			$this->table->getName(),
			$this->getWriteValues(),
			$this->table->getPrefixedValues( $this->getUpdateConditions() ),
			is_null( $functionName ) ? __METHOD__ : $functionName
		);

		// DatabaseBase::update does not always return true for success as documented...
		return $success !== false;
	}

	/**
	 * Returns the WHERE considtions needed to identify this object so
	 * it can be updated.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	protected function getUpdateConditions() {
		return array( 'id' => $this->getId() );
	}

	/**
	 * Inserts the object into the database.
	 *
	 * @since 1.20
	 *
	 * @param string|null $functionName
	 * @param array|null $options
	 *
	 * @return boolean Success indicator
	 */
	protected function insert( $functionName = null, array $options = null ) {
		$dbw = wfGetDB( DB_MASTER );

		$success = $dbw->insert(
			$this->table->getName(),
			$this->getWriteValues(),
			is_null( $functionName ) ? __METHOD__ : $functionName,
			is_null( $options ) ? array( 'IGNORE' ) : $options
		);

		// DatabaseBase::insert does not always return true for success as documented...
		$success = $success !== false;

		if ( $success ) {
			$this->setField( 'id', $dbw->insertId() );
		}

		return $success;
	}

	/**
	 * Removes the object from the database.
	 *
	 * @since 1.20
	 *
	 * @return boolean Success indicator
	 */
	public function remove() {
		$this->beforeRemove();

		$success = $this->table->delete( array( 'id' => $this->getId() ) );

		// DatabaseBase::delete does not always return true for success as documented...
		$success = $success !== false;

		if ( $success ) {
			$this->onRemoved();
		}

		return $success;
	}

	/**
	 * Gets called before an object is removed from the database.
	 *
	 * @since 1.20
	 */
	protected function beforeRemove() {
		$this->loadFields( $this->getBeforeRemoveFields(), false, true );
	}

	/**
	 * Before removal of an object happens, @see beforeRemove gets called.
	 * This method loads the fields of which the names have been returned by this one (or all fields if null is returned).
	 * This allows for loading info needed after removal to get rid of linked data and the like.
	 *
	 * @since 1.20
	 *
	 * @return array|null
	 */
	protected function getBeforeRemoveFields() {
		return array();
	}

	/**
	 * Gets called after successfull removal.
	 * Can be overriden to get rid of linked data.
	 *
	 * @since 1.20
	 */
	protected function onRemoved() {
		$this->setField( 'id', null );
	}

	/**
	 * Return the names and values of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getSetFieldNames() {
		return array_keys( $this->fields );
	}

	/**
	 * Sets the value of a field.
	 * Strings can be provided for other types,
	 * so this method can be called from unserialization handlers.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @throws MWException
	 */
	public function setField( $name, $value ) {
		$fields = $this->table->getFields();

		if ( array_key_exists( $name, $fields ) ) {
			switch ( $fields[$name] ) {
				case 'int':
					$value = (int)$value;
					break;
				case 'float':
					$value = (float)$value;
					break;
				case 'bool':
					if ( is_string( $value ) ) {
						$value = $value !== '0';
					} elseif ( is_int( $value ) ) {
						$value = $value !== 0;
					}
					break;
				case 'array':
					if ( is_string( $value ) ) {
						$value = unserialize( $value );
					}

					if ( !is_array( $value ) ) {
						$value = array();
					}
					break;
				case 'blob':
					if ( is_string( $value ) ) {
						$value = unserialize( $value );
					}
					break;
				case 'id':
					if ( is_string( $value ) ) {
						$value = (int)$value;
					}
					break;
			}

			$this->fields[$name] = $value;
		} else {
			throw new MWException( 'Attempted to set unknown field ' . $name );
		}
	}

	/**
	 * Add an amount (can be negative) to the specified field (needs to be numeric).
	 * TODO: most off this stuff makes more sense in the table class
	 *
	 * @since 1.20
	 *
	 * @param string $field
	 * @param integer $amount
	 *
	 * @return boolean Success indicator
	 */
	public function addToField( $field, $amount ) {
		if ( $amount == 0 ) {
			return true;
		}

		if ( !$this->hasIdField() ) {
			return false;
		}

		$absoluteAmount = abs( $amount );
		$isNegative = $amount < 0;

		$dbw = wfGetDB( DB_MASTER );

		$fullField = $this->table->getPrefixedField( $field );

		$success = $dbw->update(
			$this->table->getName(),
			array( "$fullField=$fullField" . ( $isNegative ? '-' : '+' ) . $absoluteAmount ),
			array( $this->table->getPrefixedField( 'id' ) => $this->getId() ),
			__METHOD__
		);

		if ( $success && $this->hasField( $field ) ) {
			$this->setField( $field, $this->getField( $field ) + $amount );
		}

		return $success;
	}

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFieldNames() {
		return array_keys( $this->table->getFields() );
	}

	/**
	 * Computes and updates the values of the summary fields.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $summaryFields
	 */
	public function loadSummaryFields( $summaryFields = null ) {

	}

	/**
	 * Sets the value for the @see $updateSummaries field.
	 *
	 * @since 1.20
	 *
	 * @param boolean $update
	 */
	public function setUpdateSummaries( $update ) {
		$this->updateSummaries = $update;
	}

	/**
	 * Sets the value for the @see $inSummaryMode field.
	 *
	 * @since 1.20
	 *
	 * @param boolean $summaryMode
	 */
	public function setSummaryMode( $summaryMode ) {
		$this->inSummaryMode = $summaryMode;
	}

	/**
	 * Return if any fields got changed.
	 *
	 * @since 1.20
	 *
	 * @param IORMRow $object
	 * @param boolean|array $excludeSummaryFields
	 *  When set to true, summary field changes are ignored.
	 *  Can also be an array of fields to ignore.
	 *
	 * @return boolean
	 */
	protected function fieldsChanged( IORMRow $object, $excludeSummaryFields = false ) {
		$exclusionFields = array();

		if ( $excludeSummaryFields !== false ) {
			$exclusionFields = is_array( $excludeSummaryFields ) ? $excludeSummaryFields : $this->table->getSummaryFields();
		}

		foreach ( $this->fields as $name => $value ) {
			$excluded = $excludeSummaryFields && in_array( $name, $exclusionFields );

			if ( !$excluded && $object->getField( $name ) !== $value ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the table this IORMRow is a row in.
	 *
	 * @since 1.20
	 *
	 * @return IORMTable
	 */
	public function getTable() {
		return $this->table;
	}

}
