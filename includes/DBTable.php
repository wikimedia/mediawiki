<?php

/**
 * Abstract base class for representing a single database table.
 * 
 * @since 1.20
 *
 * @file DBTable.php
 *
 * @licence GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class DBTable {
	
	/**
	 * Returns the name of the database table objects of this type are stored in.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public abstract function getDBTable();

	/**
	 * Returns the name of a DBDataObject deriving class that
	 * represents single rows in this table.
	 * 
	 * @since 1.20
	 * 
	 * @return string
	 */
	public abstract function getDataObjectClass();
	
	/**
	 * Gets the db field prefix.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	protected abstract function getFieldPrefix();

	/**
	 * Returns an array with the fields and their types this object contains.
	 * This corresponds directly to the fields in the database, without prefix.
	 *
	 * field name => type
	 *
	 * Allowed types:
	 * * id
	 * * str
	 * * int
	 * * float
	 * * bool
	 * * array
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public abstract function getFieldTypes();

	/**
	 * The database connection to use for read operations.
	 * Can be changed via @see setReadDb.
	 *
	 * @since 1.20
	 * @var integer DB_ enum
	 */
	protected $readDb = DB_SLAVE;
	
	/**
	 * Returns a list of default field values.
	 * field name => field value
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getDefaults() {
		return array();
	}
	
	/**
	 * Returns a list of the summary fields.
	 * These are fields that cache computed values, such as the amount of linked objects of $type.
	 * This is relevant as one might not want to do actions such as log changes when these get updated.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getSummaryFields() {
		return array();
	}
	
	/**
	 * Selects the the specified fields of the records matching the provided
	 * conditions and returns them as DBDataObject. Field names get prefixed.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $fields
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return array of self
	 */
	public function select( $fields = null, array $conditions = array(), array $options = array() ) {
		$result = $this->selectFields( $fields, $conditions, $options, false );

		$objects = array();

		foreach ( $result as $record ) {
			$objects[] = $this->newFromArray( $record );
		}

		return $objects;
	}

	/**
	 * Selects the the specified fields of the records matching the provided
	 * conditions and returns them as associative arrays.
	 * Provided field names get prefixed.
	 * Returned field names will not have a prefix.
	 *
	 * When $collapse is true:
	 * If one field is selected, each item in the result array will be this field.
	 * If two fields are selected, each item in the result array will have as key
	 * the first field and as value the second field.
	 * If more then two fields are selected, each item will be an associative array.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $fields
	 * @param array $conditions
	 * @param array $options
	 * @param boolean $collapse Set to false to always return each result row as associative array.
	 *
	 * @return array of array
	 */
	public function selectFields( $fields = null, array $conditions = array(), array $options = array(), $collapse = true ) {
		if ( is_null( $fields ) ) {
			$fields = array_keys( $this->getFieldTypes() );
		}
		else {
			$fields = (array)$fields;
		}
		
		$dbr = wfGetDB( $this->getReadDb() );
		$result = $dbr->select(
			$this->getDBTable(),
			$this->getPrefixedFields( $fields ),
			$this->getPrefixedValues( $conditions ),
			__METHOD__,
			$options
		);

		$objects = array();

		foreach ( $result as $record ) {
			$objects[] = $this->getFieldsFromDBResult( $record );
		}

		if ( $collapse ) {
			if ( count( $fields ) === 1 ) {
				$objects = array_map( 'array_shift', $objects );
			}
			elseif ( count( $fields ) === 2 ) {
				$o = array();

				foreach ( $objects as $object ) {
					$o[array_shift( $object )] = array_shift( $object );
				}

				$objects = $o;
			}
		}

		return $objects;
	}

	/**
	 * Selects the the specified fields of the first matching record.
	 * Field names get prefixed.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $fields
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return DBObject|bool False on failure
	 */
	public function selectRow( $fields = null, array $conditions = array(), array $options = array() ) {
		$options['LIMIT'] = 1;

		$objects = $this->select( $fields, $conditions, $options );

		return count( $objects ) > 0 ? $objects[0] : false;
	}

	/**
	 * Selects the the specified fields of the records matching the provided
	 * conditions. Field names do NOT get prefixed.
	 *
	 * @since 1.20
	 *
	 * @param array $fields
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return ResultWrapper
	 */
	public function rawSelectRow( array $fields, array $conditions = array(), array $options = array() ) {
		$dbr = wfGetDB( $this->getReadDb() );

		return $dbr->selectRow(
			$this->getDBTable(),
			$fields,
			$conditions,
			__METHOD__,
			$options
		);
	}

	/**
	 * Selects the the specified fields of the first record matching the provided
	 * conditions and returns it as an associative array, or false when nothing matches.
	 * This method makes use of selectFields and expects the same parameters and
	 * returns the same results (if there are any, if there are none, this method returns false).
	 * @see DBDataObject::selectFields
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $fields
	 * @param array $conditions
	 * @param array $options
	 * @param boolean $collapse Set to false to always return each result row as associative array.
	 *
	 * @return mixed|array|bool False on failure
	 */
	public function selectFieldsRow( $fields = null, array $conditions = array(), array $options = array(), $collapse = true ) {
		$options['LIMIT'] = 1;

		$objects = $this->selectFields( $fields, $conditions, $options, $collapse );

		return count( $objects ) > 0 ? $objects[0] : false;
	}

	/**
	 * Returns if there is at least one record matching the provided conditions.
	 * Condition field names get prefixed.
	 *
	 * @since 1.20
	 *
	 * @param array $conditions
	 *
	 * @return boolean
	 */
	public function has( array $conditions = array() ) {
		return $this->selectRow( array( 'id' ), $conditions ) !== false;
	}

	/**
	 * Returns the amount of matching records.
	 * Condition field names get prefixed.
	 *
	 * @since 1.20
	 *
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return integer
	 */
	public function count( array $conditions = array(), array $options = array() ) {
		$res = $this->rawSelectRow(
			array( 'COUNT(*) AS rowcount' ),
			$this->getPrefixedValues( $conditions ),
			$options
		);

		return $res->rowcount;
	}

	/**
	 * Removes the object from the database.
	 *
	 * @since 1.20
	 *
	 * @param array $conditions
	 *
	 * @return boolean Success indicator
	 */
	public function delete( array $conditions ) {
		return wfGetDB( DB_MASTER )->delete(
			$this->getDBTable(),
			$this->getPrefixedValues( $conditions )
		);
	}
	
	/**
	 * Get API parameters for the fields supported by this object.
	 *
	 * @since 1.20
	 *
	 * @param boolean $requireParams
	 * @param boolean $setDefaults
	 *
	 * @return array
	 */
	public function getAPIParams( $requireParams = false, $setDefaults = false ) {
		$typeMap = array(
			'id' => 'integer',
			'int' => 'integer',
			'float' => 'NULL',
			'str' => 'string',
			'bool' => 'integer',
			'array' => 'string',
			'blob' => 'string',
		);

		$params = array();
		$defaults = $this->getDefaults();

		foreach ( $this->getFieldTypes() as $field => $type ) {
			if ( $field == 'id' ) {
				continue;
			}

			$hasDefault = array_key_exists( $field, $defaults );

			$params[$field] = array(
				ApiBase::PARAM_TYPE => $typeMap[$type],
				ApiBase::PARAM_REQUIRED => $requireParams && !$hasDefault
			);

			if ( $type == 'array' ) {
				$params[$field][ApiBase::PARAM_ISMULTI] = true;
			}

			if ( $setDefaults && $hasDefault ) {
				$default = is_array( $defaults[$field] ) ? implode( '|', $defaults[$field] ) : $defaults[$field];
				$params[$field][ApiBase::PARAM_DFLT] = $default;
			}
		}

		return $params;
	}
	
	/**
	 * Returns an array with the fields and their descriptions.
	 *
	 * field name => field description
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFieldDescriptions() {
		return array();
	}
	
	/**
	 * Get the database type used for read operations.
	 *
	 * @since 1.20
	 * 
	 * @return integer DB_ enum
	 */
	public function getReadDb() {
		return $this->readDb;
	}

	/**
	 * Set the database type to use for read operations.
	 *
	 * @param integer $db
	 *
	 * @since 1.20
	 */
	public function setReadDb( $db ) {
		$this->readDb = $db;
	}
	
	/**
	 * Update the records matching the provided conditions by
	 * setting the fields that are keys in the $values param to
	 * their corresponding values.
	 *
	 * @since 1.20
	 *
	 * @param array $values
	 * @param array $conditions
	 *
	 * @return boolean Success indicator
	 */
	public function update( array $values, array $conditions = array() ) {
		$dbw = wfGetDB( DB_MASTER );

		return $dbw->update(
			$this->getDBTable(),
			$this->getPrefixedValues( $values ),
			$this->getPrefixedValues( $conditions ),
			__METHOD__
		);
	}
	
	/**
	 * Computes the values of the summary fields of the objects matching the provided conditions.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $summaryFields
	 * @param array $conditions
	 */
	public function updateSummaryFields( $summaryFields = null, array $conditions = array() ) {
		$this->setReadDb( DB_MASTER );

		foreach ( $this->select( null, $conditions ) as /* DBDataObject */ $item ) {
			$item->loadSummaryFields( $summaryFields );
			$item->setSummaryMode( true );
			$item->saveExisting();
		}

		$this->setReadDb( DB_SLAVE );
	}
	
	/**
	 * Takes in an associative array with field names as keys and
	 * their values as value. The field names are prefixed with the
	 * db field prefix.
	 *
	 * Field names can also be provided as an array with as first element a table name, such as
	 * $conditions = array(
	 *	 array( array( 'tablename', 'fieldname' ), $value ),
	 * );
	 *
	 * @since 1.20
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public function getPrefixedValues( array $values ) {
		$prefixedValues = array();

		foreach ( $values as $field => $value ) {
			if ( is_integer( $field ) ) {
				if ( is_array( $value ) ) {
					$field = $value[0];
					$value = $value[1];
				}
				else {
					$value = explode( ' ', $value, 2 );
					$value[0] = $this->getPrefixedField( $value[0] );
					$prefixedValues[] = implode( ' ', $value );
					continue;
				}
			}

			$prefixedValues[$this->getPrefixedField( $field )] = $value;
		}

		return $prefixedValues;
	}
	
	/**
	 * Takes in a field or array of fields and returns an
	 * array with their prefixed versions, ready for db usage.
	 *
	 * @since 1.20
	 *
	 * @param array|string $fields
	 *
	 * @return array
	 */
	public function getPrefixedFields( array $fields ) {
		foreach ( $fields as &$field ) {
			$field = $this->getPrefixedField( $field );
		}

		return $fields;
	}

	/**
	 * Takes in a field and returns an it's prefixed version, ready for db usage.
	 *
	 * @since 1.20
	 *
	 * @param string|array $field
	 *
	 * @return string
	 */
	public function getPrefixedField( $field ) {
		return $this->getFieldPrefix() . $field;
	}
	
	/**
	 * Takes an array of field names with prefix and returns the unprefixed equivalent.
	 *
	 * @since 1.20
	 *
	 * @param array $fieldNames
	 *
	 * @return array
	 */
	public function unprefixFieldNames( array $fieldNames ) {
		return array_map( array( $this, 'unprefixFieldName' ), $fieldNames );
	}
	
	/**
	 * Takes a field name with prefix and returns the unprefixed equivalent.
	 *
	 * @since 1.20
	 *
	 * @param string $fieldName
	 *
	 * @return string
	 */
	public function unprefixFieldName( $fieldName ) {
		return substr( $fieldName, strlen( $this->getFieldPrefix() ) );
	}
	
	public function __construct() {
	
	}
	
	/**
	 * Get an instance of this class.
	 *
	 * @since 1.20
	 *
	 * @return DBtable
	 */
	public static function &singleton() {
		static $instance;
		
		if ( !isset( $instance ) ) {
			$instance = new static;
		}
		
		return $instance;
	}

	/**
	 * Get an array with fields from a database result,
	 * that can be fed directly to the constructor or
	 * to setFields.
	 *
	 * @since 1.20
	 *
	 * @param stdClass $result
	 *
	 * @return array
	 */
	public function getFieldsFromDBResult( stdClass $result ) {
		$result = (array)$result;
		return array_combine(
			$this->unprefixFieldNames( array_keys( $result ) ),
			array_values( $result )
		);
	}

	/**
	 * Get a new instance of the class from a database result.
	 *
	 * @since 1.20
	 *
	 * @param stdClass $result
	 *
	 * @return DBDataObject
	 */
	public function newFromDBResult( stdClass $result ) {
		return $this->newFromArray( $this->getFieldsFromDBResult( $result ) );
	}

	/**
	 * Get a new instance of the class from an array.
	 *
	 * @since 1.20
	 *
	 * @param array $data
	 * @param boolean $loadDefaults
	 *
	 * @return DBDataObject
	 */
	public function newFromArray( array $data, $loadDefaults = false ) {
		$class = $this->getDataObjectClass();
		return new $class( $this, $data, $loadDefaults );
	}

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFieldNames() {
		return array_keys( $this->getFieldTypes() );
	}
	
}
