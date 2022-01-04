<?php

namespace Wikimedia\Rdbms;

use Wikimedia\AtEase\AtEase;

class PostgresResultWrapper extends ResultWrapper {
	/** @var DatabasePostgres */
	private $db;
	/** @var resource */
	private $handle;
	/** @var resource */
	private $result;

	/**
	 * @internal
	 * @param DatabasePostgres $db
	 * @param resource $handle
	 * @param resource $result
	 */
	public function __construct( DatabasePostgres $db, $handle, $result ) {
		$this->db = $db;
		$this->handle = $handle;
		$this->result = $result;
	}

	protected function doNumRows() {
		return pg_num_rows( $this->result );
	}

	protected function doFetchObject() {
		AtEase::suppressWarnings();
		$row = pg_fetch_object( $this->result );
		AtEase::restoreWarnings();
		return $row;
	}

	protected function doFetchRow() {
		AtEase::suppressWarnings();
		$row = pg_fetch_array( $this->result );
		AtEase::restoreWarnings();
		return $row;
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
