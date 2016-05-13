<?php

/**
 * Null index field - means search engine does not implement this field.
 */
class NullIndexField implements SearchIndexField {

	/**
	 * Get mapping for specific search engine
	 * @param SearchEngine $engine
	 * @return array|null Null means this field does not map to anything
	 */
	public function getMapping( SearchEngine $engine ) {
		return null;
	}

	/**
	 * Set global flag for this field.
	 *
	 * @param int  $flag Bit flag to set/unset
	 * @param bool $unset True if flag should be unset, false by default
	 * @return $this
	 */
	public function setFlag( $flag, $unset = false ) {
	}

	/**
	 * Check if flag is set.
	 * @param $flag
	 * @return int 0 if unset, !=0 if set
	 */
	public function checkFlag( $flag ) {
		return 0;
	}

	/**
	 * Merge two field definitions if possible.
	 *
	 * @param SearchIndexField $that
	 * @return SearchIndexField|false New definition or false if not mergeable.
	 */
	public function merge( SearchIndexField $that ) {
		return $that;
	}
}
