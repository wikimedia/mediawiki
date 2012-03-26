<?php

/**
 * Result of a DBTable::select, which returns DBDataObjects.
 *
 * @since 1.20
 *
 * @file DBResult.php
 *
 * @licence GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DBResult implements Iterator {

	/**
	 * @var ResultWrapper
	 */
	protected $res;

	/**
	 * @var integer
	 */
	protected  $key;

	/**
	 * @var DBDataObject
	 */
	protected $current;

	/**
	 * @var DBTable
	 */
	protected $table;

	/**
	 * @param DBTable $table
	 * @param ResultWrapper $res
	 */
	public function __construct( DBTable $table, ResultWrapper $res ) {
		$this->table = $table;
		$this->res = $res;
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @param $row
	 */
	protected function setCurrent( $row ) {
		if ( $row === false ) {
			$this->current = false;
		} else {
			$this->current = $this->table->newFromDBResult( $row );
		}
	}

	/**
	 * @return integer
	 */
	public function count() {
		return $this->res->numRows();
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->res->numRows() === 0;
	}

	/**
	 * @return DBDataObject
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * @return integer
	 */
	public function key() {
		return $this->key;
	}

	public function next() {
		$row = $this->res->next();
		$this->setCurrent( $row );
		$this->key++;
	}

	public function rewind() {
		$this->res->rewind();
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @return boolean
	 */
	public function valid() {
		return $this->current !== false;
	}

}
