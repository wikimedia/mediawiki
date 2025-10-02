<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia;

/**
 * Wrapper around strtr() that holds replacements
 */
class ReplacementArray {
	private array $data;

	/**
	 * Create an object with the specified replacement array
	 * The array should have the same form as the replacement array for strtr()
	 * @param array $data
	 */
	public function __construct( array $data = [] ) {
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function __sleep() {
		return [ 'data' ];
	}

	/**
	 * Set the whole replacement array at once
	 */
	public function setArray( array $data ) {
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getArray() {
		return $this->data;
	}

	/**
	 * Set an element of the replacement array
	 * @param string $from
	 * @param string $to
	 */
	public function setPair( $from, $to ) {
		$this->data[$from] = $to;
	}

	/**
	 * @param array $data
	 */
	public function mergeArray( $data ) {
		$this->data = $data + $this->data;
	}

	public function merge( ReplacementArray $other ) {
		$this->data = $other->data + $this->data;
	}

	/**
	 * @param string $from
	 */
	public function removePair( $from ) {
		unset( $this->data[$from] );
	}

	/**
	 * @param array $data
	 */
	public function removeArray( $data ) {
		foreach ( $data as $from => $to ) {
			$this->removePair( $from );
		}
	}

	/**
	 * @param string $subject
	 * @return string
	 */
	public function replace( $subject ) {
		return strtr( $subject, $this->data );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ReplacementArray::class, 'ReplacementArray' );
/** @deprecated class alias since 1.45 */
class_alias( ReplacementArray::class, 'MediaWiki\\Language\\ReplacementArray' );
