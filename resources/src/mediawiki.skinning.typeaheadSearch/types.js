/**
 * @typedef {Object} FetchEndEvent
 * @property {number} numberOfResults
 * @property {string} query
 */

/**
 * @typedef {Object} SuggestionClickEvent
 * @property {number} numberOfResults
 * @property {number} index
 */

/**
 * @typedef {SuggestionClickEvent} SearchSubmitEvent
 */

/**
 * @typedef {Object} RestResult
 * @property {number} id
 * @property {string} key
 * @property {string} title
 * @property {string} [description]
 * @property {RestThumbnail | null} [thumbnail]
 */

/**
 * @typedef {Object} RestThumbnail
 * @property {string} url
 * @property {number | null} [width]
 * @property {number | null} [height]
 */

/**
 * @typedef {Object} SearchResult
 * @property {number} id
 * @property {string} key
 * @property {string} title
 * @property {string} [description]
 * @property {SearchResultThumbnail} [thumbnail]
 */

/**
 * @typedef {Object} SearchResultThumbnail
 * @property {string} url
 * @property {number} [width]
 * @property {number} [height]
 */

/* exported SuggestionClickEvent, SearchSubmitEvent, FetchEndEvent, RestResult, SearchResult */
