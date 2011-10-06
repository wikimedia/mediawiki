<?php

abstract class UserArray implements Iterator {
	/**
	 * @param $res ResultWrapper
	 * @return UserArrayFromResult
	 */
	static function newFromResult( $res ) {
		$userArray = null;
		if ( !wfRunHooks( 'UserArrayFromResult', array( &$userArray, $res ) ) ) {
			return null;
		}
		if ( $userArray === null ) {
			$userArray = self::newFromResult_internal( $res );
		}
		return $userArray;
	}

	/**
	 * @param $ids array
	 * @return UserArrayFromResult
	 */
	static function newFromIDs( $ids ) {
		$ids = array_map( 'intval', (array)$ids ); // paranoia
		if ( !$ids ) {
			// Database::select() doesn't like empty arrays
			return new ArrayIterator(array());
		}
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'user', '*', array( 'user_id' => $ids ),
			__METHOD__ );
		return self::newFromResult( $res );
	}

	/**
	 * @param $res
	 * @return UserArrayFromResult
	 */
	protected static function newFromResult_internal( $res ) {
		return new UserArrayFromResult( $res );
	}
}

class UserArrayFromResult extends UserArray {

	/**
	 * @var ResultWrapper
	 */
	var $res;
	var $key, $current;

	/**
	 * @param $res ResultWrapper
	 */
	function __construct( $res ) {
		$this->res = $res;
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @param  $row
	 * @return void
	 */
	protected function setCurrent( $row ) {
		if ( $row === false ) {
			$this->current = false;
		} else {
			$this->current = User::newFromRow( $row );
		}
	}

	/**
	 * @return int
	 */
	public function count() {
		return $this->res->numRows();
	}

	/**
	 * @return User
	 */
	function current() {
		return $this->current;
	}

	function key() {
		return $this->key;
	}

	function next() {
		$row = $this->res->next();
		$this->setCurrent( $row );
		$this->key++;
	}

	function rewind() {
		$this->res->rewind();
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @return bool
	 */
	function valid() {
		return $this->current !== false;
	}
}
