<?php

namespace Wikimedia\Rdbms;

use mysqli_result;

class MysqliResultWrapper extends ResultWrapper {
	/** @var DatabaseMySQL */
	private $db;

	/** @var mysqli_result|null */
	private $result;

	/**
	 * @internal
	 * @param DatabaseMySQL $db
	 * @param mysqli_result $result
	 */
	public function __construct( DatabaseMySQL $db, mysqli_result $result ) {
		$this->db = $db;
		$this->result = $result;
	}

	/** @inheritDoc */
	protected function doNumRows() {
		// We are not checking for any errors here, since
		// there are no errors mysql_num_rows can cause.
		// See https://dev.mysql.com/doc/refman/5.7/en/mysql-fetch-row.html.
		// See https://phabricator.wikimedia.org/T44430
		return $this->result->num_rows;
	}

	private function checkFetchError() {
		$errno = $this->db->lastErrno();
		// Unfortunately, mysql_fetch_array does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_array can cause.
		// See https://dev.mysql.com/doc/refman/5.7/en/mysql-fetch-row.html.
		if ( $errno == 2000 || $errno == 2013 ) {
			throw new DBUnexpectedError(
				$this->db,
				'Error in fetchRow(): ' . htmlspecialchars( $this->db->lastError() )
			);
		}
	}

	/** @inheritDoc */
	protected function doFetchObject() {
		$object = $this->result->fetch_object();
		$this->checkFetchError();
		if ( $object === null ) {
			return false;
		}
		return $object;
	}

	/** @inheritDoc */
	protected function doFetchRow() {
		$array = $this->result->fetch_array();
		$this->checkFetchError();
		if ( $array === null ) {
			return false;
		}
		return $array;
	}

	/** @inheritDoc */
	protected function doSeek( $pos ) {
		$this->result->data_seek( $pos );
	}

	/** @inheritDoc */
	protected function doFree() {
		$this->result = null;
	}

	/** @inheritDoc */
	protected function doGetFieldNames() {
		$names = [];
		foreach ( $this->result->fetch_fields() as $fieldInfo ) {
			$names[] = $fieldInfo->name;
		}
		return $names;
	}

	/**
	 * Get information about a field in the result set
	 *
	 * @param string $fieldName
	 * @return bool|MySQLField
	 * @internal For DatabaseMySQL::fieldInfo() only
	 *
	 */
	public function getInternalFieldInfo( $fieldName ) {
		for ( $i = 0; $i < $this->result->field_count; $i++ ) {
			$meta = $this->result->fetch_field_direct( $i );
			if ( $fieldName == $meta->name ) {
				// Add missing properties to result (using flags property)
				// which will be part of function mysql-fetch-field for backward compatibility
				$meta->not_null = $meta->flags & MYSQLI_NOT_NULL_FLAG;
				$meta->primary_key = $meta->flags & MYSQLI_PRI_KEY_FLAG;
				$meta->unique_key = $meta->flags & MYSQLI_UNIQUE_KEY_FLAG;
				$meta->multiple_key = $meta->flags & MYSQLI_MULTIPLE_KEY_FLAG;
				$meta->binary = $meta->flags & MYSQLI_BINARY_FLAG;
				$meta->numeric = $meta->flags & MYSQLI_NUM_FLAG;
				$meta->blob = $meta->flags & MYSQLI_BLOB_FLAG;
				$meta->unsigned = $meta->flags & MYSQLI_UNSIGNED_FLAG;
				$meta->zerofill = $meta->flags & MYSQLI_ZEROFILL_FLAG;
				return new MySQLField( $meta );
			}
		}
		return false;
	}
}
