/**
 * The value of the `inputLocation` property of any and all SearchSatisfaction events sent by the
 * corresponding instrumentation.
 *
 * @see https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/skins/Vector/+/refs/heads/master/includes/Constants.php
 */
const INPUT_LOCATION_MOVED = 'header-moved',
	// T251544: Collect search performance metrics to compare Vue search with
	// mediawiki.searchSuggest performance. Marks and Measures will only be
	// recorded on the Vector skin and only if browser supported.
	shouldTestSearchPerformance = !!( window.performance &&
		!!window.requestAnimationFrame &&
		!!performance.mark &&
		!!performance.measure &&
		!!performance.getEntriesByName &&
		performance.clearMarks ),
	loadStartMark = 'mwVectorVueSearchLoadStart',
	queryMark = 'mwVectorVueSearchQuery',
	renderMark = 'mwVectorVueSearchRender',
	queryToRenderMeasure = 'mwVectorVueSearchQueryToRender',
	loadStartToFirstRenderMeasure = 'mwVectorVueSearchLoadStartToFirstRender';

function onFetchStart() {
	if ( !shouldTestSearchPerformance ) {
		return;
	}

	// Clear past marks that are no longer relevant. This likely means that the
	// search request failed or was cancelled. Whatever the reason, the mark
	// is no longer needed since we are only interested in collecting the time
	// from query to render.
	if ( performance.getEntriesByName( queryMark ).length ) {
		performance.clearMarks( queryMark );
	}

	performance.mark( queryMark );
}

/**
 * @param {FetchEndEvent} event
 */
function onFetchEnd( event ) {
	mw.track( 'mediawiki.searchSuggest', {
		action: 'impression-results',
		numberOfResults: event.numberOfResults,
		// resultSetType: '',
		// searchId: '',
		query: event.query,
		inputLocation: INPUT_LOCATION_MOVED
	} );

	if ( shouldTestSearchPerformance ) {
		// Schedule the mark after the search results have rendered and are
		// visible to the user. Two rAF's are needed for this since rAF will
		// execute before the rendering steps happen (e.g. layout and paint). A
		// nested rAF will execute after these rendering steps have completed
		// and ensure the search results are visible to the user.
		requestAnimationFrame( () => {
			requestAnimationFrame( () => {
				if ( !performance.getEntriesByName( queryMark ).length ) {
					return;
				}

				performance.mark( renderMark );
				performance.measure( queryToRenderMeasure, queryMark, renderMark );

				// Measure from the start of the lazy load to the first render if we
				// haven't already captured that info.
				if ( performance.getEntriesByName( loadStartMark ).length &&
					!performance.getEntriesByName( loadStartToFirstRenderMeasure ).length ) {
					performance.measure( loadStartToFirstRenderMeasure, loadStartMark, renderMark );
				}

				// The measures are the most meaningful info so we remove the marks
				// after we have the measure.
				performance.clearMarks( queryMark );
				performance.clearMarks( renderMark );
			} );
		} );
	}
}

/**
 * @param {SuggestionClickEvent|SearchSubmitEvent} event
 */
function onSuggestionClick( event ) {
	mw.track( 'mediawiki.searchSuggest', {
		action: 'click-result',
		numberOfResults: event.numberOfResults,
		index: event.index
	} );
}

/**
 * Generates the value of the `wprov` parameter to be used in the URL of a search result and the
 * `wprov` hidden input.
 *
 * See https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/extensions/WikimediaEvents/+/refs/heads/master/modules/ext.wikimediaEvents/searchSatisfaction.js
 * and also the top of that file for additional detail about the shape of the parameter.
 *
 * @param {number} index
 * @return {string}
 */
function getWprovFromResultIndex( index ) {
	// result looks like: acrw1_0, acrw1_1, acrw1_2, etc.;
	// or acrw1_-1 for index -1 (user did not highlight an autocomplete result)
	return 'acrw1_' + index;
}

/**
 * @typedef {Object} SearchResultPartial
 * @property {string} title
 * @property {string} [url]
 */

/**
 * Return a new list of search results,
 * with the `wprov` parameter added to each result's url (if any).
 *
 * @param {SearchResultPartial[]} results Not modified.
 * @param {number} offset Offset to add to the index of each result.
 * @return {SearchResultPartial[]}
 */
function addWprovToSearchResultUrls( results, offset ) {
	return results.map( ( result, index ) => {
		if ( result.url ) {
			const uri = new mw.Uri( result.url );
			uri.query.wprov = getWprovFromResultIndex( index + offset );
			result = Object.assign( {}, result, { url: uri.toString() } );
		}
		return result;
	} );
}

/**
 * @typedef {Object} Instrumentation
 * @property {Object} listeners
 * @property {Function} getWprovFromResultIndex
 * @property {Function} addWprovToSearchResultUrls
 */

/**
 * @type {Instrumentation}
 */
module.exports = {
	listeners: {
		onFetchStart,
		onFetchEnd,
		onSuggestionClick,

		// As of writing (2020/12/08), both the "click-result" and "submit-form" kind of
		// mediawiki.searchSuggestion events result in a "click" SearchSatisfaction event being
		// logged [0]. However, when processing the "submit-form" kind of mediawiki.searchSuggestion
		// event, the SearchSatisfaction instrument will modify the DOM, adding a hidden input
		// element, in order to set the appropriate provenance parameter (see [1] for additional
		// detail).
		//
		// In this implementation of the mediawiki.searchSuggestion protocol, we don't want to
		// trigger the above behavior as we're using Vue.js, which doesn't expect the DOM to be
		// modified underneath it.
		//
		// [0] https://gerrit.wikimedia.org/g/mediawiki/extensions/WikimediaEvents/+/df97aa9c9407507e8c48827666beeab492fd56a8/modules/ext.wikimediaEvents/searchSatisfaction.js#735
		// [1] https://phabricator.wikimedia.org/T257698#6416826
		onSubmit: onSuggestionClick
	},
	getWprovFromResultIndex,
	addWprovToSearchResultUrls
};
