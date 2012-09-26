<?php
/**
 * Abstract base class for representing a single database table.
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
 * Non-abstract since 1.21
 *
 * @file ORMTable.php
 * @ingroup ORM
 *
 * @license GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

class ORMTable extends DBAccessBase implements IORMTable {

	/**
	 * Cache for instances, used by the singleton method.
	 *
	 * @since 1.20
	 * @deprecated since 1.21
	 *
	 * @var ORMTable[]
	 */
	protected static $instanceCache = array();

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 * @since 1.21
	 *
	 * @var string[]
	 */
	protected $fields = array();

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $fieldPrefix = '';

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $rowClass = 'ORMRow';

	/**
	 * @since 1.21
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * ID of the database connection to use for read operations.
	 * Can be changed via @see setReadDb.
	 *
	 * @since 1.20
	 *
	 * @var integer DB_ enum
	 */
	protected $readDb = DB_SLAVE;

	/**
	 * Constructor.
	 *
	 * @since 1.21
	 *
	 * @param string $tableName
	 * @param string[] $fields
	 * @param array $defaults
	 * @param string|null $rowClass
	 * @param string $fieldPrefix
	 */
	public function __construct( $tableName = '', array $fields = array(), array $defaults = array(), $rowClass = null, $fieldPrefix = '' ) {
		$this->tableName = $tableName;
		$this->fields = $fields;
		$this->defaults = $defaults;

		if ( is_string( $rowClass ) ) {
			$this->rowClass = $rowClass;
		}

		$this->fieldPrefix = $fieldPrefix;
	}

	/**
	 * @see IORMTable::getName
	 *
	 * @since 1.21
	 *
	 * @return string
	 * @throws MWException
	 */
	public function getName() {
		if ( $this->tableName === '' ) {
			throw new MWException( 'The table name needs to be set' );
		}

		return $this->tableName;
	}

	/**
	 * Gets the db field prefix.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	protected function getFieldPrefix() {
		return $this->fieldPrefix;
	}

	/**
	 * @see IORMTable::getRowClass
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getRowClass() {
		return $this->rowClass;
	}

	/**
	 * @see ORMTable::getFields
	 *
	 * @since 1.21
	 *
	 * @return array
	 * @throws MWException
	 */
	public function getFields() {
		if ( $this->fields === array() ) {
			throw new MWException( 'The table needs to have one or more fields' );
		}

		return $this->fields;
	}

	/**
	 * Returns a list of default field values.
	 * field name => field value
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getDefaults() {
		return $this->defaults;
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
	 * @param string|null $functionName
	 *
	 * @return ORMResult
	 */
	public function select( $fields = null, array $conditions = array(),
							array $options = array(), $functionName = null ) {
		$res = $this->rawSelect( $fields, $conditions, $options, $functionName );
		return new ORMResult( $this, $res );
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
	 * @param string|null $functionName
	 *
	 * @return array of row objects
	 * @throws DBQueryError if the query failed (even if the database was in ignoreErrors mode).
	 */
	public function selectObjects( $fields = null, array $conditions = array(),
								   array $options = array(), $functionName = null ) {
		$result = $this->selectFields( $fields, $conditions, $options, false, $functionName );

		$objects = array();

		foreach ( $result as $record ) {
			$objects[] = $this->newRow( $record );
		}

		return $objects;
	}

	/**
	 * Do the actual select.
	 *
	 * @since 1.20
	 *
	 * @param null|string|array $fields
	 * @param array             $conditions
	 * @param array             $options
	 * @param null|string       $functionName
	 *
	 * @return ResultWrapper
	 * @throws DBQueryError if the quey failed (even if the database was in ignoreErrors mode).
	 */
	public function rawSelect( $fields = null, array $conditions = array(),
							   array $options = array(), $functionName = null ) {
		if ( is_null( $fields ) ) {
			$fields = array_keys( $this->getFields() );
		}
		else {
			$fields = (array)$fields;
		}

		$dbr = $this->getReadDbConnection();
		$result = $dbr->select(
			$this->getName(),
			$this->getPrefixedFields( $fields ),
			$this->getPrefixedValues( $conditions ),
			is_null( $functionName ) ? __METHOD__ : $functionName,
			$options
		);

		/* @var Exception $error */
		$error = null;

		if ( $result === false ) {
			// Database connection was in "ignoreErrors" mode. We don't like that.
			// So, we emulate the DBQueryError that should have been thrown.
			$error = new DBQueryError(
				$dbr,
				$dbr->lastError(),
				$dbr->lastErrno(),
				$dbr->lastQuery(),
				is_null( $functionName ) ? __METHOD__ : $functionName
			);
		}

		$this->releaseConnection( $dbr );

		if ( $error ) {
			// Note: construct the error before releasing the connection,
			// but throw it after.
			throw $error;
		}

		return $result;
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
	 * @param string|null $functionName
	 *
	 * @return array of array
	 */
	public function selectFields( $fields = null, array $conditions = array(),
								  array $options = array(), $collapse = true, $functionName = null ) {
		$objects = array();

		$result = $this->rawSelect( $fields, $conditions, $options, $functionName );

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
	 * @param string|null $functionName
	 *
	 * @return IORMRow|bool False on failure
	 */
	public function selectRow( $fields = null, array $conditions = array(),
							   array $options = array(), $functionName = null ) {
		$options['LIMIT'] = 1;

		$objects = $this->select( $fields, $conditions, $options, $functionName );

		return ( !$objects || $objects->isEmpty() ) ? false : $objects->current();
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
	 * @param string|null $functionName
	 *
	 * @return ResultWrapper
	 */
	public function rawSelectRow( array $fields, array $conditions = array(),
								  array $options = array(), $functionName = null ) {
		$dbr = $this->getReadDbConnection();

		$result = $dbr->selectRow(
			$this->getName(),
			$fields,
			$conditions,
			is_null( $functionName ) ? __METHOD__ : $functionName,
			$options
		);

		$this->releaseConnection( $dbr );
		return $result;
	}

	/**
	 * Selects the the specified fields of the first record matching the provided
	 * conditions and returns it as an associative array, or false when nothing matches.
	 * This method makes use of selectFields and expects the same parameters and
	 * returns the same results (if there are any, if there are none, this method returns false).
	 * @see ORMTable::selectFields
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $fields
	 * @param array $conditions
	 * @param array $options
	 * @param boolean $collapse Set to false to always return each result row as associative array.
	 * @param string|null $functionName
	 *
	 * @return mixed|array|bool False on failure
	 */
	public function selectFieldsRow( $fields = null, array $conditions = array(),
									 array $options = array(), $collapse = true, $functionName = null ) {
		$options['LIMIT'] = 1;

		$objects = $this->selectFields( $fields, $conditions, $options, $collapse, $functionName );

		return empty( $objects ) ? false : $objects[0];
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
	 * Checks if the table exists
	 *
	 * @since 1.21
	 *
	 * @return boolean
	 */
	public function exists() {
		$dbr = $this->getReadDbConnection();
		$exists = $dbr->tableExists( $this->getName() );
		$this->releaseConnection( $dbr );

		return $exists;
	}

	/**
	 * Returns the amount of matching records.
	 * Condition field names get prefixed.
	 *
	 * Note that this can be expensive on large tables.
	 * In such cases you might want to use DatabaseBase::estimateRowCount instead.
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
			array( 'rowcount' => 'COUNT(*)' ),
			$this->getPrefixedValues( $conditions ),
			$options,
			__METHOD__
		);

		return $res->rowcount;
	}

	/**
	 * Removes the object from the database.
	 *
	 * @since 1.20
	 *
	 * @param array $conditions
	 * @param string|null $functionName
	 *
	 * @return boolean Success indicator
	 */
	public function delete( array $conditions, $functionName = null ) {
		$dbw = $this->getWriteDbConnection();

		$result = $dbw->delete(
			$this->getName(),
			$conditions === array() ? '*' : $this->getPrefixedValues( $conditions ),
			is_null( $functionName ) ? __METHOD__ : $functionName
		) !== false; // DatabaseBase::delete does not always return true for success as documented...

		$this->releaseConnection( $dbw );
		return $result;
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

		foreach ( $this->getFields() as $field => $type ) {
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
	 * Get the database ID used for read operations.
	 *
	 * @since 1.20
	 *
	 * @return integer DB_ enum
	 */
	public function getReadDb() {
		return $this->readDb;
	}

	/**
	 * Set the database ID to use for read operations, use DB_XXX constants or an index to the load balancer setup.
	 *
	 * @param integer $db
	 *
	 * @since 1.20
	 */
	public function setReadDb( $db ) {
		$this->readDb = $db;
	}

	/**
	 * Get the ID of the any foreign wiki to use as a target for database operations
	 *
	 * @since 1.20
	 *
	 * @return String|bool The target wiki, in a form that  LBFactory understands (or false if the local wiki is used)
	 */
	public function getTargetWiki() {
		return $this->wiki;
	}

	/**
	 * Set the ID of the any foreign wiki to use as a target for database operations
	 *
	 * @param string|bool $wiki The target wiki, in a form that  LBFactory understands (or false if the local wiki shall be used)
	 *
	 * @since 1.20
	 */
	public function setTargetWiki( $wiki ) {
		$this->wiki = $wiki;
	}

	/**
	 * Get the database type used for read operations.
	 * This is to be used instead of wfGetDB.
	 *
	 * @see LoadBalancer::getConnection
	 *
	 * @since 1.20
	 *
	 * @return DatabaseBase The database object
	 */
	public function getReadDbConnection() {
		return $this->getConnection( $this->getReadDb(), array() );
	}

	/**
	 * Get the database type used for read operations.
	 * This is to be used instead of wfGetDB.
	 *
	 * @see LoadBalancer::getConnection
	 *
	 * @since 1.20
	 *
	 * @return DatabaseBase The database object
	 */
	public function getWriteDbConnection() {
		return $this->getConnection( DB_MASTER, array() );
	}

	/**
	 * Releases the lease on the given database connection. This is useful mainly
	 * for connections to a foreign wiki. It does nothing for connections to the local wiki.
	 *
	 * @see LoadBalancer::reuseConnection
	 *
	 * @param DatabaseBase $db the database
	 *
	 * @since 1.20
	 */
	public function releaseConnection( DatabaseBase $db ) {
		parent::releaseConnection( $db ); // just make it public
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
		$dbw = $this->getWriteDbConnection();

		$result = $dbw->update(
			$this->getName(),
			$this->getPrefixedValues( $values ),
			$this->getPrefixedValues( $conditions ),
			__METHOD__
		) !== false; // DatabaseBase::update does not always return true for success as documented...

		$this->releaseConnection( $dbw );
		return $result;
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
		$slave = $this->getReadDb();
		$this->setReadDb( DB_MASTER );

		/**
		 * @var IORMRow $item
		 */
		foreach ( $this->select( null, $conditions ) as $item ) {
			$item->loadSummaryFields( $summaryFields );
			$item->setSummaryMode( true );
			$item->save();
		}

		$this->setReadDb( $slave );
	}

	/**
	 * Takes in an associative array with field names as keys and
	 * their values as value. The field names are prefixed with the
	 * db field prefix.
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

	/**
	 * Get an instance of this class.
	 *
	 * @since 1.20
	 * @deprecated since 1.21
	 *
	 * @return IORMTable
	 */
	public static function singleton() {
		$class = get_called_class();

		if ( !array_key_exists( $class, self::$instanceCache ) ) {
			self::$instanceCache[$class] = new $class;
		}

		return self::$instanceCache[$class];
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

		$rawFields = array_combine(
			$this->unprefixFieldNames( array_keys( $result ) ),
			array_values( $result )
		);

		$fieldDefinitions = $this->getFields();
		$fields = array();

		foreach ( $rawFields as $name => $value ) {
			if ( array_key_exists( $name, $fieldDefinitions ) ) {
				switch ( $fieldDefinitions[$name] ) {
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

				$fields[$name] = $value;
			} else {
				throw new MWException( 'Attempted to set unknown field ' . $name );
			}
		}

		return $fields;
	}

	/**
	 * @see ORMTable::newRowFromFromDBResult
	 *
	 * @deprecated use newRowFromDBResult instead
	 * @since 1.20
	 *
	 * @param stdClass $result
	 *
	 * @return IORMRow
	 */
	public function newFromDBResult( stdClass $result ) {
		return self::newRowFromDBResult( $result );
	}

	/**
	 * Get a new instance of the class from a database result.
	 *
	 * @since 1.20
	 *
	 * @param stdClass $result
	 *
	 * @return IORMRow
	 */
	public function newRowFromDBResult( stdClass $result ) {
		return $this->newRow( $this->getFieldsFromDBResult( $result ) );
	}

	/**
	 * @see ORMTable::newRow
	 *
	 * @deprecated use newRow instead
	 * @since 1.20
	 *
	 * @param array $data
	 * @param boolean $loadDefaults
	 *
	 * @return IORMRow
	 */
	public function newFromArray( array $data, $loadDefaults = false ) {
		return static::newRow( $data, $loadDefaults );
	}

	/**
	 * Get a new instance of the class from an array.
	 *
	 * @since 1.20
	 *
	 * @param array $fields
	 * @param boolean $loadDefaults
	 *
	 * @return IORMRow
	 */
	public function newRow( array $fields, $loadDefaults = false ) {
		$class = $this->getRowClass();

		return new $class( $this, $fields, $loadDefaults );
	}

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFieldNames() {
		return array_keys( $this->getFields() );
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
	public function canHaveField( $name ) {
		return array_key_exists( $name, $this->getFields() );
	}

	/**
	 * Updates the provided row in the database.
	 *
	 * @since 1.22
	 *
	 * @param IORMRow $row The row to save
	 * @param string|null $functionName
	 *
	 * @return boolean Success indicator
	 */
	public function updateRow( IORMRow $row, $functionName = null ) {
		$dbw = $this->getWriteDbConnection();

		$success = $dbw->update(
			$this->getName(),
			$this->getWriteValues( $row ),
			$this->getPrefixedValues( array( 'id' => $row->getId() ) ),
			is_null( $functionName ) ? __METHOD__ : $functionName
		);

		$this->releaseConnection( $dbw );

		// DatabaseBase::update does not always return true for success as documented...
		return $success !== false;
	}

	/**
	 * Inserts the provided row into the database.
	 *
	 * @since 1.22
	 *
	 * @param IORMRow $row
	 * @param string|null $functionName
	 * @param array|null $options
	 *
	 * @return boolean Success indicator
	 */
	public function insertRow( IORMRow $row, $functionName = null, array $options = null ) {
		$dbw = $this->getWriteDbConnection();

		$success = $dbw->insert(
			$this->getName(),
			$this->getWriteValues( $row ),
			is_null( $functionName ) ? __METHOD__ : $functionName,
			$options
		);

		// DatabaseBase::insert does not always return true for success as documented...
		$success = $success !== false;

		if ( $success ) {
			$row->setField( 'id', $dbw->insertId() );
		}

		$this->releaseConnection( $dbw );

		return $success;
	}

	/**
	 * Gets the fields => values to write to the table.
	 *
	 * @since 1.22
	 *
	 * @param IORMRow $row
	 *
	 * @return array
	 */
	protected function getWriteValues( IORMRow $row ) {
		$values = array();

		$rowFields = $row->getFields();

		foreach ( $this->getFields() as $name => $type ) {
			if ( array_key_exists( $name, $rowFields ) ) {
				$value = $rowFields[$name];

				switch ( $type ) {
					case 'array':
						$value = (array)$value;
					// fall-through!
					case 'blob':
						$value = serialize( $value );
					// fall-through!
				}

				$values[$this->getPrefixedField( $name )] = $value;
			}
		}

		return $values;
	}

	/**
	 * Removes the provided row from the database.
	 *
	 * @since 1.22
	 *
	 * @param IORMRow $row
	 * @param string|null $functionName
	 *
	 * @return boolean Success indicator
	 */
	public function removeRow( IORMRow $row, $functionName = null ) {
		$success = $this->delete(
			array( 'id' => $row->getId() ),
			is_null( $functionName ) ? __METHOD__ : $functionName
		);

		// DatabaseBase::delete does not always return true for success as documented...
		return $success !== false;
	}

	/**
	 * Add an amount (can be negative) to the specified field (needs to be numeric).
	 *
	 * @since 1.22
	 *
	 * @param array $conditions
	 * @param string $field
	 * @param integer $amount
	 *
	 * @return boolean Success indicator
	 * @throws MWException
	 */
	public function addToField( array $conditions, $field, $amount ) {
		if ( !array_key_exists( $field, $this->fields ) ) {
			throw new MWException( 'Unknown field "' . $field . '" provided' );
		}

		if ( $amount == 0 ) {
			return true;
		}

		$absoluteAmount = abs( $amount );
		$isNegative = $amount < 0;

		$fullField = $this->getPrefixedField( $field );

		$dbw = $this->getWriteDbConnection();

		$success = $dbw->update(
			$this->getName(),
			array( "$fullField=$fullField" . ( $isNegative ? '-' : '+' ) . $absoluteAmount ),
			$this->getPrefixedValues( $conditions ),
			__METHOD__
		) !== false; // DatabaseBase::update does not always return true for success as documented...

		$this->releaseConnection( $dbw );

		return $success;
	}

}
