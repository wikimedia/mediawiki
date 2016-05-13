<?php
/**
 * Created by PhpStorm.
 * User: smalyshev
 * Date: 5/23/16
 * Time: 2:10 PM
 */

/**
 * Definition of a mapping for the search index field
 */
interface SearchIndexField {
	/**
	 * Field types
	 */
	const INDEX_TYPE_TEXT = 0;
	const INDEX_TYPE_KEYWORD = 1;
	const INDEX_TYPE_INTEGER = 2;
	const INDEX_TYPE_NUMBER = 3;
	const INDEX_TYPE_DATETIME = 4;
	const INDEX_TYPE_NESTED = 5;
	/**
	 * Generic field flags.
	 */
	/**
	 * This field is case-insensitive.
	 */
	const FLAG_CASEFOLD = 1;
	/**
	 * This field is for scoring only.
	 */
	const FLAG_SCORING = 2;
	/**
	 * This field does not need highlight handling.
	 */
	const FLAG_NO_HIGHLIGHT = 4;
	/**
	 * Do not index this field.
	 */
	const FLAG_NO_INDEX = 8;
	/**
	 * Get mapping for specific search engine
	 * @param SearchEngine $engine
	 * @return array|null Null means this field does not map to anything
	 */
	public function getMapping(SearchEngine $engine);
	/**
	 * Set global flag for this field.
	 *
	 * @param int  $flag Bit flag to set/unset
	 * @param bool $unset True if flag should be unset, false by default
	 * @return $this
	 */
	public function setFlag( $flag, $unset = false );
	/**
	 * Check if flag is set.
	 * @param $flag
	 * @return int 0 if unset, !=0 if set
	 */
	public function checkFlag( $flag );
	/**
	 * Merge two field definitions if possible.
	 *
	 * @param SearchIndexField $that
	 * @return SearchIndexField|false New definition or false if not mergeable.
	 */
	public function merge( SearchIndexField $that );
}