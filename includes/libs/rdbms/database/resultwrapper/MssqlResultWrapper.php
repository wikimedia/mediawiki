<?php

namespace Wikimedia\Rdbms;

use stdClass;

class MssqlResultWrapper extends ResultWrapper {
	/** @var int|null */
	private $seekTo = null;

	/**
	 * @return stdClass|bool
	 */
	public function fetchObject() {
		$res = $this->result;

		if ( $this->seekTo !== null ) {
			$result = sqlsrv_fetch_object( $res, stdClass::class, [],
				SQLSRV_SCROLL_ABSOLUTE, $this->seekTo );
			$this->seekTo = null;
		} else {
			$result = sqlsrv_fetch_object( $res );
		}

		// Return boolean false when there are no more rows instead of null
		if ( $result === null ) {
			return false;
		}

		return $result;
	}

	/**
	 * @return array|bool
	 */
	public function fetchRow() {
		$res = $this->result;

		if ( $this->seekTo !== null ) {
			$result = sqlsrv_fetch_array( $res, SQLSRV_FETCH_BOTH,
				SQLSRV_SCROLL_ABSOLUTE, $this->seekTo );
			$this->seekTo = null;
		} else {
			$result = sqlsrv_fetch_array( $res );
		}

		// Return boolean false when there are no more rows instead of null
		if ( $result === null ) {
			return false;
		}

		return $result;
	}

	/**
	 * @param int $row
	 * @return bool
	 */
	public function seek( $row ) {
		$res = $this->result;

		// check bounds
		$numRows = $this->db->numRows( $res );
		$row = intval( $row );

		if ( $numRows === 0 ) {
			return false;
		} elseif ( $row < 0 || $row > $numRows - 1 ) {
			return false;
		}

		// Unlike MySQL, the seek actually happens on the next access
		$this->seekTo = $row;
		return true;
	}
}
