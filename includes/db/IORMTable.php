<?php
/**
 * Interface for objects representing a single database table.
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
 * @file
 * @ingroup ORM
 *
 * @licence GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

interface IORMTable {

	/**
	 * Returns the name of the database table objects of this type are stored in.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Returns the name of a IORMRow implementing class that
	 * represents single rows in this table.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getRowClass();

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
	 * * blob
	 *
	 * TODO: get rid of the id field. Every row instance needs to have
	 * one so this is just causing hassle at various locations by requiring an extra check for field name.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFields();

	/**
	 * Returns a list of default field values.
	 * field name => field value
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getDefaults();

	/**
	 * Returns a list of the summary fields.
	 * These are fields that cache computed values, such as the amount of linked objects of $type.
	 * This is relevant as one might not want to do actions such as log changes when these get updated.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getSummaryFields();

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
							array $options = array(), $functionName = null );

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
	 * @return array of self
	 */
	public function selectObjects( $fields = null, array $conditions = array(),
								   array $options = array(), $functionName = null );

	/**
	 * Do the actual select.
	 *
	 * @since 1.20
	 *
	 * @param null|string|array $fields
	 * @param array $conditions
	 * @param array $options
	 * @param null|string $functionName
	 *
	 * @return ResultWrapper
	 */
	public function rawSelect( $fields = null, array $conditions = array(),
							   array $options = array(), $functionName = null );

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
								  array $options = array(), $collapse = true, $functionName = null );

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
							   array $options = array(), $functionName = null );

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
								  array $options = array(), $functionName = null );

	/**
	 * Selects the the specified fields of the first record matching the provided
	 * conditions and returns it as an associative array, or false when nothing matches.
	 * This method makes use of selectFields and expects the same parameters and
	 * returns the same results (if there are any, if there are none, this method returns false).
	 * @see IORMTable::selectFields
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
									 array $options = array(), $collapse = true, $functionName = null );

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
	public function has( array $conditions = array() );

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
	public function count( array $conditions = array(), array $options = array() );

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
	public function delete( array $conditions, $functionName = null );

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
	public function getAPIParams( $requireParams = false, $setDefaults = false );

	/**
	 * Returns an array with the fields and their descriptions.
	 *
	 * field name => field description
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFieldDescriptions();

	/**
	 * Get the database type used for read operations.
	 *
	 * @since 1.20
	 *
	 * @return integer DB_ enum
	 */
	public function getReadDb();

	/**
	 * Set the database type to use for read operations.
	 *
	 * @param integer $db
	 *
	 * @since 1.20
	 */
	public function setReadDb( $db );

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
	public function update( array $values, array $conditions = array() );

	/**
	 * Computes the values of the summary fields of the objects matching the provided conditions.
	 *
	 * @since 1.20
	 *
	 * @param array|string|null $summaryFields
	 * @param array $conditions
	 */
	public function updateSummaryFields( $summaryFields = null, array $conditions = array() );

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
	public function getPrefixedValues( array $values );

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
	public function getPrefixedFields( array $fields );

	/**
	 * Takes in a field and returns an it's prefixed version, ready for db usage.
	 *
	 * @since 1.20
	 *
	 * @param string|array $field
	 *
	 * @return string
	 */
	public function getPrefixedField( $field );

	/**
	 * Takes an array of field names with prefix and returns the unprefixed equivalent.
	 *
	 * @since 1.20
	 *
	 * @param array $fieldNames
	 *
	 * @return array
	 */
	public function unprefixFieldNames( array $fieldNames );

	/**
	 * Takes a field name with prefix and returns the unprefixed equivalent.
	 *
	 * @since 1.20
	 *
	 * @param string $fieldName
	 *
	 * @return string
	 */
	public function unprefixFieldName( $fieldName );

	/**
	 * Get an instance of this class.
	 *
	 * @since 1.20
	 *
	 * @return IORMTable
	 */
	public static function singleton();

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
	public function getFieldsFromDBResult( stdClass $result );

	/**
	 * Get a new instance of the class from a database result.
	 *
	 * @since 1.20
	 *
	 * @param stdClass $result
	 *
	 * @return IORMRow
	 */
	public function newRowFromDBResult( stdClass $result );

	/**
	 * Get a new instance of the class from an array.
	 *
	 * @since 1.20
	 *
	 * @param array $data
	 * @param boolean $loadDefaults
	 *
	 * @return IORMRow
	 */
	public function newRow( array $data, $loadDefaults = false );

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFieldNames();

	/**
	 * Gets if the object can take a certain field.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function canHaveField( $name );

}
