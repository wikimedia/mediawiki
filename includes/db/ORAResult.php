<?php

use Wikimedia\Rdbms\IDatabase;

/**
 * The oci8 extension is fairly weak and doesn't support oci_num_rows, among
 * other things. We use a wrapper class to handle that and other
 * Oracle-specific bits, like converting column names back to lowercase.
 * @ingroup Database
 */
class ORAResult {
	private $rows;
	private $cursor;
	private $nrows;

	private $columns = [];

	private function array_unique_md( $array_in ) {
		$array_out = [];
		$array_hashes = [];

		foreach ( $array_in as $item ) {
			$hash = md5( serialize( $item ) );
			if ( !isset( $array_hashes[$hash] ) ) {
				$array_hashes[$hash] = $hash;
				$array_out[] = $item;
			}
		}

		return $array_out;
	}

	/**
	 * @param IDatabase $db
	 * @param resource $stmt A valid OCI statement identifier
	 * @param bool $unique
	 */
	function __construct( &$db, $stmt, $unique = false ) {
		$this->db =& $db;

		$this->nrows = oci_fetch_all( $stmt, $this->rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW | OCI_NUM );
		if ( $this->nrows === false ) {
			$e = oci_error( $stmt );
			$db->reportQueryError( $e['message'], $e['code'], '', __METHOD__ );
			$this->free();

			return;
		}

		if ( $unique ) {
			$this->rows = $this->array_unique_md( $this->rows );
			$this->nrows = count( $this->rows );
		}

		if ( $this->nrows > 0 ) {
			foreach ( $this->rows[0] as $k => $v ) {
				$this->columns[$k] = strtolower( oci_field_name( $stmt, $k + 1 ) );
			}
		}

		$this->cursor = 0;
		oci_free_statement( $stmt );
	}

	public function free() {
		unset( $this->db );
	}

	public function seek( $row ) {
		$this->cursor = min( $row, $this->nrows );
	}

	public function numRows() {
		return $this->nrows;
	}

	public function numFields() {
		return count( $this->columns );
	}

	public function fetchObject() {
		if ( $this->cursor >= $this->nrows ) {
			return false;
		}
		$row = $this->rows[$this->cursor++];
		$ret = new stdClass();
		foreach ( $row as $k => $v ) {
			$lc = $this->columns[$k];
			$ret->$lc = $v;
		}

		return $ret;
	}

	public function fetchRow() {
		if ( $this->cursor >= $this->nrows ) {
			return false;
		}

		$row = $this->rows[$this->cursor++];
		$ret = [];
		foreach ( $row as $k => $v ) {
			$lc = $this->columns[$k];
			$ret[$lc] = $v;
			$ret[$k] = $v;
		}

		return $ret;
	}
}
