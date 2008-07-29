<?php
/**
 * ObjectArray is a class that stores arrays of objects in an efficient manner.
 * It stores a minimum of information about each object, and then constructs
 * them on the fly for iteration in foreach() loops.  Currently this can only
 * be done by storing the info in the form of a database result, which is a
 * good choice because 1) it tends to be readily available and 2) it uses less
 * memory than a PHP array.  Other storage methods may be developed in the fu-
 * ture.
 *
 * Currently you can get TitleArrays and UserArrays.  Any other class could be
 * easily added: it just needs a newFromRow() method that will accept a data-
 * base row as its sole argument, and return an object.  For the fields that
 * need to be provided in the result you pass to the newFromResult() construc-
 * tor, consult the appropriate object class' newFromRow() documentation.
 *
 * In addition to the usual Iterator methods, there's a count() method that
 * will return the number of objects in the array.  When later versions of PHP
 * are supported, we may be able to avoid this by implementing the Countable
 * interface, making this act more like a real array.
 *
 * Sample usage:
 *   $users = UserArray::newFromResult( $dbr->select(
 *       'user', '*', $conds, $opts, __METHOD__
 *   ) );
 *   foreach( $users as $user ) {
 *       ...use $user's methods here, it's a User object!...
 *   }
 */
abstract class ObjectArray implements Iterator {
	static function newFromClassAndResult( $class, $res ) {
		$array = null;
		if ( !wfRunHooks( 'ObjectArrayFromResult', array( $class, &$array, $res ) ) ) {
			return null;
		}
		if ( $array === null ) {
			$array = self::newFromResult_internal( $class, $res );
		}
		return $array;
	}

	protected static function newFromResult_internal( $class, $res ) {
		return new ObjectArrayFromResult( $class, $res );
	}
}

class ObjectArrayFromResult extends ObjectArray {
	var $res, $class;
	var $key = 0, $current = false;

	function __construct( $class, $res ) {
		$this->class = $class;
		$this->res = $res;
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	protected function setCurrent( $row ) {
		if ( $row === false ) {
			$this->current = false;
		} else {
			$this->current = call_user_func(
				array( $this->class, 'newFromRow' ), $row
			);
		}
	}

	public function count() {
		return $this->res->numRows();
	}

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

	function valid() {
		return $this->current !== false;
	}
}

abstract class UserArray extends ObjectArray {
	static function newFromResult( $res ) {
		return parent::newFromClassAndResult( 'User', $res );
	}
}

abstract class TitleArray extends ObjectArray {
	static function newFromResult( $res ) {
		return parent::newFromClassAndResult( 'Title', $res );
	}
}
