<?php

namespace Wikimedia\Rdbms;

use PgSql\Connection;
use PgSql\Result;

/**
 * Result wrapper for PostgreSQL database results.
 *
 * @since 1.37
 */
class PostgresResultWrapper extends ResultWrapper {
	/** @var DatabasePostgres */
	private $db;
	private Connection $handle;
	private Result $result;

	/**
	 * @internal
	 */
	public function __construct( DatabasePostgres $db, Connection $handle, Result $result ) {
		$this->db = $db;
		$this->handle = $handle;
		$this->result = $result;
	}

	protected function doNumRows() {
		return pg_num_rows( $this->result );
	}

	protected function doFetchObject() {
		// pg_fetch_object may raise a warning after a seek to an invalid offset
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$row = @pg_fetch_object( $this->result );
		// Map boolean values (T352229)
		if ( is_object( $row ) ) {
			$numFields = pg_num_fields( $this->result );
			for ( $i = 0; $i < $numFields; $i++ ) {
				if ( pg_field_type( $this->result, $i ) === 'bool' ) {
					$name = pg_field_name( $this->result, $i );
					$row->$name = $this->convertBoolean( $row->$name );
				}
			}
		}
		return $row;
	}

	protected function doFetchRow() {
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$row = @pg_fetch_array( $this->result );
		// Map boolean values (T352229)
		if ( is_array( $row ) ) {
			$numFields = pg_num_fields( $this->result );
			for ( $i = 0; $i < $numFields; $i++ ) {
				if ( pg_field_type( $this->result, $i ) === 'bool' ) {
					$name = pg_field_name( $this->result, $i );
					$row[$i] = $this->convertBoolean( $row[$i] );
					$row[$name] = $this->convertBoolean( $row[$name] );
				}
			}
		}
		return $row;
	}

	/**
	 * Convert a boolean value from the database to the string '0' or '1' for
	 * compatibility with MySQL.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	private function convertBoolean( $value ) {
		if ( $value === 't' ) {
			return '1';
		} elseif ( $value === 'f' ) {
			return '0';
		} else {
			// Just pass through values that are not 't' or 'f'
			return $value;
		}
	}

	protected function doSeek( $pos ) {
		pg_result_seek( $this->result, $pos );
	}

	protected function doFree() {
		return pg_free_result( $this->result );
	}

	protected function doGetFieldNames() {
		$names = [];
		$n = pg_num_fields( $this->result );
		for ( $i = 0; $i < $n; $i++ ) {
			$names[] = pg_field_name( $this->result, $i );
		}
		return $names;
	}
}
