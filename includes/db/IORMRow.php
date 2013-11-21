<?php
/**
 * Interface for representing objects that are stored in some DB table.
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
 * @file
 * @ingroup ORM
 *
 * @license GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

interface IORMRow {
	/**
	 * Load the specified fields from the database.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @param array|null $fields
	 * @param boolean $override
	 * @param boolean $skipLoaded
	 *
	 * @return bool Success indicator
	 */
	public function loadFields( $fields = null, $override = true, $skipLoaded = false );

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
	public function getField( $name, $default = null );

	/**
	 * Gets the value of a field but first loads it if not done so already.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function loadAndGetField( $name );

	/**
	 * Remove a field.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 */
	public function removeField( $name );

	/**
	 * Returns the objects database id.
	 *
	 * @since 1.20
	 *
	 * @return integer|null
	 */
	public function getId();

	/**
	 * Sets the objects database id.
	 *
	 * @since 1.20
	 *
	 * @param integer|null $id
	 */
	public function setId( $id );

	/**
	 * Gets if a certain field is set.
	 *
	 * @since 1.20
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function hasField( $name );

	/**
	 * Gets if the id field is set.
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function hasIdField();

	/**
	 * Sets multiple fields.
	 *
	 * @since 1.20
	 *
	 * @param array $fields The fields to set
	 * @param boolean $override Override already set fields with the provided values?
	 */
	public function setFields( array $fields, $override = true );

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
	public function toArray( $fields = null, $incNullId = false );

	/**
	 * Load the default values, via getDefaults.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @param boolean $override
	 */
	public function loadDefaults( $override = true );

	/**
	 * Writes the answer to the database, either updating it
	 * when it already exists, or inserting it when it doesn't.
	 *
	 * @since 1.20
	 *
	 * @param string|null $functionName
	 * @deprecated since 1.22
	 *
	 * @return boolean Success indicator
	 */
	public function save( $functionName = null );

	/**
	 * Removes the object from the database.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @return boolean Success indicator
	 */
	public function remove();

	/**
	 * Return the names and values of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFields();

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getSetFieldNames();

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
	public function setField( $name, $value );

	/**
	 * Add an amount (can be negative) to the specified field (needs to be numeric).
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @param string $field
	 * @param integer $amount
	 *
	 * @return boolean Success indicator
	 */
	public function addToField( $field, $amount );

	/**
	 * Return the names of the fields.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFieldNames();

	/**
	 * Computes and updates the values of the summary fields.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @param array|string|null $summaryFields
	 */
	public function loadSummaryFields( $summaryFields = null );

	/**
	 * Sets the value for the @see $updateSummaries field.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @param boolean $update
	 */
	public function setUpdateSummaries( $update );

	/**
	 * Sets the value for the @see $inSummaryMode field.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @param boolean $summaryMode
	 */
	public function setSummaryMode( $summaryMode );

	/**
	 * Returns the table this IORMRow is a row in.
	 *
	 * @since 1.20
	 * @deprecated since 1.22
	 *
	 * @return IORMTable
	 */
	public function getTable();
}
