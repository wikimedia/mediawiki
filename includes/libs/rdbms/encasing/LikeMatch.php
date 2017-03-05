<?php

namespace Wikimedia\Rdbms;

/**
 * Used by Database::buildLike() to represent characters that have special
 * meaning in SQL LIKE clauses and thus need no escaping. Don't instantiate it
 * manually, use Database::anyChar() and anyString() instead.
 */
class LikeMatch {
	/** @var string */
	private $str;

	/**
	 * Store a string into a LikeMatch marker object.
	 *
	 * @param string $s
	 */
	public function __construct( $s ) {
		$this->str = $s;
	}

	/**
	 * Return the original stored string.
	 *
	 * @return string
	 */
	public function toString() {
		return $this->str;
	}
}
