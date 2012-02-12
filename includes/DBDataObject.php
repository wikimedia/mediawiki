<?php

/**
 * Abstract base class for representing objects that are stored in some DB table.
 * This is basically an ORM-like wrapper around rows in database tables that
 * aims to be both simple and very flexible. It is centered around an associative
 * array of fields and various methods to do common interaction with the database.
 *
 * These methods must be implemented in deriving classes:
 * * getFieldTypes
 *
 * These methods are likely candidates for overriding:
 * * getDefaults
 * * remove
 * * insert
 * * saveExisting
 * * loadSummaryFields
 * * getSummaryFields
 *
 * Deriving classes must register their table and field prefix in $wgDBDataObjects.
 * Syntax: $wgDBDataObjects['DrivingClassName'] = array( 'table' => 'table_name', 'prefix' => 'fieldprefix_' );
 * Example: $wgDBDataObjects['EPOrg'] = array( 'table' => 'ep_orgs', 'prefix' => 'org_' );
 *
 * Main instance methods:
 * * getField(s)
 * * setField(s)
 * * save
 * * remove
 * 
 * Main static methods:
 * * select
 * * update
 * * delete
 * * count
 * * has
 * * selectRow
 * * selectFields
 * * selectFieldsRow
 *
 * @since 1.20
 *
 * @file DBDataObject.php
 *
 * @licence GNU GPL v3 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class DBDataObject {

	/**
	 * The fields of the object.
	 * field name (w/o prefix) => value
	 *
	 * @since 1.20
	 * @var array
	 */
	protected $fields = array( 'id' => null );

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
	 * The database connection to use for read operations.
	 * Can be changed via @see setReadDb.
	 *
	 * @since 0.2
	 * @var integer DB_ enum
	 */
	protected static $readDb = DB_SLAVE;

	/**
	 * Returns the name of the database table objects of this type are stored in.
	 *
	 * @since 1.20
	 *
	 * @throws MWException
	 * @return string
	 */
	public static function getDBTable() {
		global $wgDBDataObjects;
		if ( array_key_exists( get_called_class(), $wgDBDataObjects ) ) {
			return $wgDBDataObjects[get_called_class()]['table'];
		}
		else {
			throw new MWException( 'Class "' . get_called_class() . '" not found in $wgDBDataObjects' );
		}
	}

	/**
	 * Gets the db field prefix.
	 *
	 * @since 1.20
	 *
	 * @throws MWException
	 * @return string
	 */
	protected static function getFieldPrefix() {
		global $wgDBDataObjects;
		if ( array_key_exists( get_called_class(), $wgDBDataObjects ) ) {
			return $wgDBDataObjects[get_called_class()]['prefix'];
		}
		else {
			throw new MWException( 'Class "' . get_called_class() . '" not found in $wgDBDataObjects' );
		}
	}

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
	 * @throws MWException
	 * @return array
	 */
	protected static function getFieldTypes() {
		throw new MWException( 'Class did not implement getFieldTypes' );
	}

	/**
	 * Returns a list of default field values.
	 * field name => field value
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public static function getDefaults() {
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
	public static function getSummaryFields() {
		return array();
	}

	/**
	 * Constructor.
	 *
	 * @since 1.20
	 *
	 * @param array|null $fields
	 * @param boolean $loadDefaults
	 */
	public function __construct( $fields = null, $loadDefaults = false ) {
		if ( !is_array( $fields ) ) {
			$fields = array();
		}

		if ( $loadDefaults ) {
			$fields = array_merge( $this->getDefaults(), $fields );
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
	 * @return Success indicator
	 */
	public function loadFields( $fields = null, $override = true, $skipLoaded = false ) {
		if ( is_null( $this->getId() ) ) {
			return false;
		}

		if ( is_null( $fields ) ) {
			$fields = array_keys( $this->getFieldTypes() );
		}
		
		if ( $skipLoaded ) {
			$fields = array_diff( $fields, array_keys( $this->fields ) );
		}
		
		if ( count( $fields ) > 0 ) {
			$results = $this->rawSelect(
				$this->getPrefixedFields( $fields ),
				array( $this->getPrefixedField( 'id' ) => $this->getId() ),
				array( 'LIMIT' => 1 )
			);
	
			foreach ( $results as $result ) {
				$this->setFields( $this->getFieldsFromDBResult( $result ), $override );
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
		return $this->setField( 'id', $id );
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

		foreach ( $this->getFieldTypes() as $name => $type ) {
			if ( array_key_exists( $name, $this->fields ) ) {
				$value = $this->fields[$name];

				switch ( $type ) {
					case 'array':
						$value = (array)$value;
					case 'blob':
						$value = serialize( $value );
				}

				$values[$this->getFieldPrefix() . $name] = $value;
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
	 *  @since 1.20
	 *
	 * @param boolean $override
	 */
	public function loadDefaults( $override = true ) {
		$this->setFields( $this->getDefaults(), $override );
	}

	/**
	 * Writes the answer to the database, either updating it
	 * when it already exists, or inserting it when it doesn't.
	 *
	 * @since 1.20
	 *
	 * @return boolean Success indicator
	 */
	public function save() {
		if ( $this->hasIdField() ) {
			return $this->saveExisting();
		} else {
			return $this->insert();
		}
	}

	/**
	 * Updates the object in the database.
	 *
	 * @since 1.20
	 *
	 * @return boolean Success indicator
	 */
	protected function saveExisting() {
		$dbw = wfGetDB( DB_MASTER );

		$success = $dbw->update(
			$this->getDBTable(),
			$this->getWriteValues(),
			array( $this->getFieldPrefix() . 'id' => $this->getId() ),
			__METHOD__
		);

		return $success;
	}

	/**
	 * Inserts the object into the database.
	 *
	 * @since 1.20
	 *
	 * @return boolean Success indicator
	 */
	protected function insert() {
		$dbw = wfGetDB( DB_MASTER );

		$result = $dbw->insert(
			$this->getDBTable(),
			$this->getWriteValues(),
			__METHOD__,
			array( 'IGNORE' )
		);

		if ( $result ) {
			$this->setField( 'id', $dbw->insertId() );
		}

		return $result;
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
		
		$success = static::delete( array( 'id' => $this->getId() ) );

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
		$fields = $this->getFieldTypes();

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
	 * Get a new instance of the class from an array.
	 *
	 * @since 1.20
	 *
	 * @param array $data
	 * @param boolean $loadDefaults
	 *
	 * @return DBDataObject
	 */
	public static function newFromArray( array $data, $loadDefaults = false ) {
		return new static( $data, $loadDefaults );
	}

	/**
	 * Get the database type used for read operations.
	 *
	 * @since 0.2
	 * @return integer DB_ enum
	 */
	public static function getReadDb() {
		return self::$readDb;
	}

	/**
	 * Set the database type to use for read operations.
	 *
	 * @param integer $db
	 *
	 * @since 0.2
	 */
	public static function setReadDb( $db ) {
		self::$readDb = $db;
	}

	/**
	 * Gets if the object can take a certain field.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public static function canHasField( $name ) {
		return array_key_exists( $name, static::getFieldTypes() );
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
	public static function getPrefixedFields( array $fields ) {
		foreach ( $fields as &$field ) {
			$field = static::getPrefixedField( $field );
		}

		return $fields;
	}

	/**
	 * Takes in a field and returns an it's prefixed version, ready for db usage.
	 * If the field needs to be prefixed for another table, provide an array in the form
	 * array( 'tablename', 'fieldname' )
	 * Where table name is registered in $wgDBDataObjects.
	 *
	 * @since 1.20
	 *
	 * @param string|array $field
	 *
	 * @return string
	 * @throws MWException
	 */
	public static function getPrefixedField( $field ) {
		static $prefixes = false;

		if ( $prefixes === false ) {
			foreach ( $GLOBALS['wgDBDataObjects'] as $classInfo ) {
				$prefixes[$classInfo['table']] = $classInfo['prefix'];
			}
		}

		if ( is_array( $field ) && count( $field ) > 1 ) {
			if ( array_key_exists( $field[0], $prefixes ) ) {
				$prefix = $prefixes[$field[0]];
				$field = $field[1];
			}
			else {
				throw new MWException( 'Tried to prefix field with unknown table "' . $field[0] . '"' );
			}
		}
		else {
			$prefix = static::getFieldPrefix();
		}

		return $prefix . $field;
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
	public static function getPrefixedValues( array $values ) {
		$prefixedValues = array();

		foreach ( $values as $field => $value ) {
			if ( is_integer( $field ) ) {
				if ( is_array( $value ) ) {
					$field = $value[0];
					$value = $value[1];
				}
				else {
					$value = explode( ' ', $value, 2 );
					$value[0] = static::getPrefixedField( $value[0] );
					$prefixedValues[] = implode( ' ', $value );
					continue;
				}
			}

			$prefixedValues[static::getPrefixedField( $field )] = $value;
		}

		return $prefixedValues;
	}

	/**
	 * Get an array with fields from a database result,
	 * that can be fed directly to the constructor or
	 * to setFields.
	 *
	 * @since 1.20
	 *
	 * @param object $result
	 *
	 * @return array
	 */
	public static function getFieldsFromDBResult( $result ) {
		$result = (array)$result;
		return array_combine(
			static::unprefixFieldNames( array_keys( $result ) ),
			array_values( $result )
		);
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
	public static function unprefixFieldName( $fieldName ) {
		return substr( $fieldName, strlen( static::getFieldPrefix() ) );
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
	public static function unprefixFieldNames( array $fieldNames ) {
		return array_map( 'static::unprefixFieldName', $fieldNames );
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
	public static function newFromDBResult( stdClass $result ) {
		return static::newFromArray( static::getFieldsFromDBResult( $result ) );
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
	public static function delete( array $conditions ) {
		return wfGetDB( DB_MASTER )->delete(
			static::getDBTable(),
			static::getPrefixedValues( $conditions )
		);
	}

	/**
	 * Add an amount (can be negative) to the specified field (needs to be numeric).
	 *
	 * @since 1.20
	 *
	 * @param string $field
	 * @param integer $amount
	 *
	 * @return boolean Success indicator
	 */
	public static function addToField( $field, $amount ) {
		if ( $amount == 0 ) {
			return true;
		}

		if ( !static::hasIdField() ) {
			return false;
		}

		$absoluteAmount = abs( $amount );
		$isNegative = $amount < 0;

		$dbw = wfGetDB( DB_MASTER );

		$fullField = static::getPrefixedField( $field );

		$success = $dbw->update(
			static::getDBTable(),
			array( "$fullField=$fullField" . ( $isNegative ? '-' : '+' ) . $absoluteAmount ),
			array( static::getPrefixedField( 'id' ) => static::getId() ),
			__METHOD__
		);

		if ( $success && static::hasField( $field ) ) {
			static::setField( $field, static::getField( $field ) + $amount );
		}

		return $success;
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
	 * @param array $joinConds
	 *
	 * @return array of self
	 */
	public static function select( $fields = null, array $conditions = array(), array $options = array(), array $joinConds = array() ) {
		$result = static::selectFields( $fields, $conditions, $options, $joinConds, false );

		$objects = array();

		foreach ( $result as $record ) {
			$objects[] = static::newFromArray( $record );
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
	 * @param array $joinConds
	 * @param boolean $collapse Set to false to always return each result row as associative array.
	 *
	 * @return array of array
	 */
	public static function selectFields( $fields = null, array $conditions = array(), array $options = array(), array $joinConds = array(), $collapse = true ) {
		if ( is_null( $fields ) ) {
			$fields = array_keys( static::getFieldTypes() );
		}
		else {
			$fields = (array)$fields;
		}

		$tables = array( static::getDBTable() );
		$joinConds = static::getProcessedJoinConds( $joinConds, $tables );

		$result = static::rawSelect(
			static::getPrefixedFields( $fields ),
			static::getPrefixedValues( $conditions ),
			$options,
			$joinConds,
			$tables
		);

		$objects = array();

		foreach ( $result as $record ) {
			$objects[] = static::getFieldsFromDBResult( $record );
		}

		if ( $collapse ) {
			if ( count( $fields ) === 1 ) {
				$objects = array_map( function( $object ) { return array_shift( $object ); } , $objects );
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
	 * Process the join conditions. This includes prefixing table and field names,
	 * and adding of needed tables.
	 *
	 * @since 1.20
	 *
	 * @param array $joinConds Join conditions without prefixes and fields in array rather then string with equals sign.
	 * @param array $tables List of tables to which the extra needed ones get added.
	 *
	 * @return array Join conditions ready to be fed to MediaWikis native select function.
	 */
	protected static function getProcessedJoinConds( array $joinConds, array &$tables ) {
		$conds = array();

		foreach ( $joinConds as $table => $joinCond ) {
			if ( !in_array( $table, $tables ) ) {
				$tables[] = $table;
			}

			$cond = array( $joinCond[0], array() );

			foreach ( $joinCond[1] as $joinCondPart ) {
				$parts = array(
					static::getPrefixedField( $joinCondPart[0] ),
					static::getPrefixedField( $joinCondPart[1] ),
				);

				if ( !in_array( $joinCondPart[0][0], $tables ) ) {
					$tables[] = $joinCondPart[0][0];
				}

				if ( !in_array( $joinCondPart[1][0], $tables ) ) {
					$tables[] = $joinCondPart[1][0];
				}

				$cond[1][] = implode( '=', $parts );
			}

			$conds[$table] = $cond;
		}

		return $conds;
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
	 * @param array $joinConds
	 *
	 * @return EPBObject|false
	 */
	public static function selectRow( $fields = null, array $conditions = array(), array $options = array(), array $joinConds = array() ) {
		$options['LIMIT'] = 1;

		$objects = static::select( $fields, $conditions, $options, $joinConds );

		return count( $objects ) > 0 ? $objects[0] : false;
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
	 * @param array $joinConds
	 * @param boolean $collapse Set to false to always return each result row as associative array.
	 *
	 * @return mixed|array|false
	 */
	public static function selectFieldsRow( $fields = null, array $conditions = array(), array $options = array(), array $joinConds = array(), $collapse = true ) {
		$options['LIMIT'] = 1;

		$objects = static::selectFields( $fields, $conditions, $options, $joinConds, $collapse );

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
	public static function has( array $conditions = array() ) {
		return static::selectRow( array( 'id' ), $conditions ) !== false;
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
	public static function count( array $conditions = array(), array $options = array() ) {
		$res = static::rawSelect(
			array( 'COUNT(*) AS rowcount' ),
			static::getPrefixedValues( $conditions ),
			$options
		)->fetchObject();

		return $res->rowcount;
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
	 * @param array $joinConds
	 * @param array $tables
	 *
	 * @return ResultWrapper
	 */
	public static function rawSelect( array $fields, array $conditions = array(), array $options = array(), array $joinConds = array(), array $tables = null ) {
		if ( is_null( $tables ) ) {
			$tables = static::getDBTable();
		}

		$dbr = wfGetDB( static::getReadDb() );

		return $dbr->select(
			$tables,
			$fields,
			count( $conditions ) == 0 ? '' : $conditions,
			__METHOD__,
			$options,
			$joinConds
		);
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
	public static function update( array $values, array $conditions = array() ) {
		$dbw = wfGetDB( DB_MASTER );

		return $dbw->update(
			static::getDBTable(),
			static::getPrefixedValues( $values ),
			static::getPrefixedValues( $conditions ),
			__METHOD__
		);
	}

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public static function getFieldNames() {
		return array_keys( static::getFieldTypes() );
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
	public static function getFieldDescriptions() {
		return array();
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
	public static function getAPIParams( $requireParams = false, $setDefaults = false ) {
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
		$defaults = static::getDefaults();

		foreach ( static::getFieldTypes() as $field => $type ) {
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
	 * Computes and updates the values of the summary fields.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $summaryFields
	 */
	public function loadSummaryFields( $summaryFields = null ) {

	}

	/**
	 * Computes the values of the summary fields of the objects matching the provided conditions.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $summaryFields
	 * @param array $conditions
	 */
	public static function updateSummaryFields( $summaryFields = null, array $conditions = array() ) {
		self::setReadDb( DB_MASTER );

		foreach ( self::select( null, $conditions ) as /* DBDataObject */ $item ) {
			$item->loadSummaryFields( $summaryFields );
			$item->setSummaryMode( true );
			$item->saveExisting();
		}

		self::setReadDb( DB_SLAVE );
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
	 * @param boolean $update
	 */
	public function setSummaryMode( $summaryMode ) {
		$this->inSummaryMode = $summaryMode;
	}

	/**
	 * Return if any fields got changed.
	 *
	 * @since 1.20
	 *
	 * @param DBDataObject $object
	 * @param boolean $excludeSummaryFields When set to true, summary field changes are ignored.
	 *
	 * @return boolean
	 */
	protected function fieldsChanged( DBDataObject $object, $excludeSummaryFields = false ) {
		foreach ( $this->fields as $name => $value ) {
			$excluded = $excludeSummaryFields && in_array( $name, $this->getSummaryFields() );

			if ( !$excluded && $object->getField( $name ) !== $value ) {
				return true;
			}
		}

		return false;
	}

}
