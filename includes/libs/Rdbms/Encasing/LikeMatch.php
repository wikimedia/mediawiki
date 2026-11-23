<?php

namespace Wikimedia\Rdbms;

/**
 * Used by Database::buildLike() to represent characters that have special
 * meaning in SQL LIKE clauses and thus need no escaping.
 *
 * @internal Please use {@link IDatabase::anyChar} and {@link IDatabase::anyString()}
 */
class LikeMatch {

	private string $str;

	/**
	 * Store a string into a LikeMatch marker object.
	 *
	 * @param string $s Typically "%" or "_"
	 */
	public function __construct( string $s ) {
		$this->str = $s;
	}

	/**
	 * Return the original stored string, typically "%" or "_".
	 */
	public function toString(): string {
		return $this->str;
	}
}
