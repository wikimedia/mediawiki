<?php
/**
 * Definition of a mapping for the search index field.
 * @unstable for implementation, extensions should subclass the SearchIndexFieldDefinition.
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
	public const INDEX_TYPE_TEXT = 'text';
	/**
	 * KEYWORD fields are indexed without any processing, so are appropriate
	 * for e.g. URLs.  The content will often consist of a single token.
	 */
	public const INDEX_TYPE_KEYWORD = 'keyword';
	public const INDEX_TYPE_INTEGER = 'integer';
	public const INDEX_TYPE_NUMBER = 'number';
	public const INDEX_TYPE_DATETIME = 'datetime';
	public const INDEX_TYPE_NESTED = 'nested';
	public const INDEX_TYPE_BOOL = 'bool';

	/**
	 * SHORT_TEXT is meant to be used with short text made of mostly ascii
	 * technical information. Generally a language agnostic analysis chain
	 * is used and aggressive splitting to increase recall.
	 * E.g suited for mime/type
	 */
	public const INDEX_TYPE_SHORT_TEXT = 'short_text';

	/**
	 * Generic field flags.
	 */
	/**
	 * This field is case-insensitive.
	 */
	public const FLAG_CASEFOLD = 1;

	/**
	 * This field contains secondary information, which is
	 * already present in other fields, but can be used for
	 * scoring.
	 */
	public const FLAG_SCORING = 2;

	/**
	 * This field does not need highlight handling.
	 */
	public const FLAG_NO_HIGHLIGHT = 4;

	/**
	 * Do not index this field, just store it.
	 */
	public const FLAG_NO_INDEX = 8;

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
