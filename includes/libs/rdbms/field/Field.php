<?php

namespace Wikimedia\Rdbms;

/**
 * Base for all database-specific classes representing information about database fields
 * @ingroup Database
 */
interface Field {
	/**
	 * Field name
	 * @return string
	 */
	function name();

	/**
	 * Name of table this field belongs to
	 * @return string
	 */
	function tableName();

	/**
	 * Database type
	 * @return string
	 */
	function type();

	/**
	 * Whether this field can store NULL values
	 * @return bool
	 */
	function isNullable();
}

/**
 * @deprecated since 1.29
 */
class_alias( Field::class, 'Field' );
