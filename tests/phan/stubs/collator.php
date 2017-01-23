<?php

class Collator {

	const PRIMARY = 0;
	const ON = 17;
	const NUMERIC_COLLATION = 7;

	/**
	 * @param string $locale
	 *
	 * @return Collator
	 */
	public static function create( $locale ) {
	}

	/**
	 * @param int $strength
	 *
	 * @return bool
	 */
	public function setStrength( $strength ) {
	}

	/**
	 * @param int $attr
	 * @param int $val
	 *
	 * @return bool
	 */
	public function setAttribute( $attr, $val ) {
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	public function getSortKey( $str ) {
	}

	/**
	 * @param string $str1
	 * @param string $str2
	 *
	 * @return int Return comparison result
	 */
	public function compare( $str1, $str2 ) {
	}

}
