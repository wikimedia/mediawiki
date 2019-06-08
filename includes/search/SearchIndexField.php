<?php
/**
 * Definition of a mapping for the search index field.
 * @since 1.28
 */
interface SearchIndexField {
	/*
	 * Field types
	 */
	/**
	 * TEXT fields are suitable for natural language and may be subject to
	 * analysis such as stemming.
	 *
	 * Read more:
	 * https://wikimediafoundation.org/2018/08/07/anatomy-search-token-affection/
	 * https://wikimediafoundation.org/2018/09/13/anatomy-search-variation-under-nature/
	 */
	const INDEX_TYPE_TEXT = 0;
	/**
	 * KEYWORD fields are indexed without any processing, so are appropriate
	 * for e.g. URLs.  The content will often consist of a single token.
	 */
	const INDEX_TYPE_KEYWORD = 1;
	const INDEX_TYPE_INTEGER = 2;
	const INDEX_TYPE_NUMBER = 3;
	const INDEX_TYPE_DATETIME = 4;
	const INDEX_TYPE_NESTED = 5;
	const INDEX_TYPE_BOOL = 6;

	/**
	 * SHORT_TEXT is meant to be used with short text made of mostly ascii
	 * technical information. Generally a language agnostic analysis chain
	 * is used and aggressive splitting to increase recall.
	 * E.g suited for mime/type
	 */
	const INDEX_TYPE_SHORT_TEXT = 7;

	/**
	 * Generic field flags.
	 */
	/**
	 * This field is case-insensitive.
	 */
	const FLAG_CASEFOLD = 1;

	/**
	 * This field contains secondary information, which is
	 * already present in other fields, but can be used for
	 * scoring.
	 */
	const FLAG_SCORING = 2;

	/**
	 * This field does not need highlight handling.
	 */
	const FLAG_NO_HIGHLIGHT = 4;

	/**
	 * Do not index this field, just store it.
	 */
	const FLAG_NO_INDEX = 8;

	/**
	 * Get mapping for specific search engine
	 * @param SearchEngine $engine
	 * @return array|null Null means this field does not map to anything
	 */
	public function getMapping( SearchEngine $engine );

	/**
	 * Set global flag for this field.
	 *
	 * @param int $flag Bit flag to set/unset
	 * @param bool $unset True if flag should be unset, false by default
	 * @return $this
	 */
	public function setFlag( $flag, $unset = false );

	/**
	 * Check if flag is set.
	 * @param int $flag
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

	/**
	 * A list of search engine hints for this field.
	 * Hints are usually specific to a search engine implementation
	 * and allow to fine control how the search engine will handle this
	 * particular field.
	 *
	 * For example some search engine permits some optimizations
	 * at index time by ignoring an update if the updated value
	 * does not change by more than X% on a numeric value.
	 *
	 * @param SearchEngine $engine
	 * @return array an array of hints generally indexed by hint name. The type of
	 * values is search engine specific
	 * @since 1.30
	 */
	public function getEngineHints( SearchEngine $engine );
}
