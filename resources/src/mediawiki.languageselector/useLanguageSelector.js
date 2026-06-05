const { ref, computed, onBeforeUnmount, unref } = require( 'vue' );
const languageSearchClient = require( './languageSearch.js' );
const debounce = require( './debounce.js' );

/**
 * Composable for language selection logic
 *
 * @param {import('vue').Ref<Object>|Object} [selectableLanguages]
 * @param {import('vue').Ref<string|string[]>} selected
 * @param {string} searchApiUrl
 * @param {number} debounceDelayMs
 * @param {boolean} isMultiple Whether multiple selection is allowed
 * @return {Object} Language selector state and methods
 */
function useLanguageSelector(
	selectableLanguages,
	selected,
	searchApiUrl,
	debounceDelayMs,
	isMultiple = false
) {
	const searchQuery = ref( '' );
	const searchResults = ref( [] );
	const searchQueryHits = ref( {} );
	const isSearching = ref( false );

	const languages = computed( () => unref( selectableLanguages ) || {} );

	const selection = computed( () => {
		const currentSelected = unref( selected );

		if ( isMultiple ) {
			return ( currentSelected || [] ).map( ( langCode ) => ( {
				value: langCode,
				label: languages.value[ langCode ] || langCode
			} ) );
		} else {
			return {
				value: currentSelected,
				label: languages.value[ currentSelected ] || currentSelected
			};
		}
	} );

	const selectedValues = computed( () => {
		if ( isMultiple ) {
			return selection.value.map( ( item ) => item.value );
		}
		return selection.value.value;
	} );

	const languageClient = languageSearchClient( searchApiUrl );
	const fetchLanguages = async ( query ) => {
		const searchRequest = languageClient.searchLanguages( query );
		isSearching.value = true;

		try {
			const response = await searchRequest;
			searchQueryHits.value = response.languagesearch || {};
			const responseLanguageCodes = Object.keys( searchQueryHits.value );

			// Only filter results if the caller explicitly provided a subset of languages.
			if ( unref( selectableLanguages ) ) {
				const languagesSet = new Set( Object.keys( languages.value ) );
				searchResults.value = responseLanguageCodes.filter(
					( code ) => languagesSet.has( code )
				);
			} else {
				searchResults.value = [];
			}
		} catch ( error ) {
			searchQueryHits.value = {};
			mw.log.error( 'Language search failed:', error );
		} finally {
			isSearching.value = false;
		}
	};

	// Only the network request is debounced; query and loading state update synchronously.
	const debouncedFetch = debounce( fetchLanguages, debounceDelayMs );

	// Reset search results and cancel any pending searches.
	const resetSearch = () => {
		debouncedFetch.cancel();
		searchResults.value = [];
		searchQueryHits.value = {};
		isSearching.value = false;
	};

	const search = ( query ) => {
		searchQuery.value = query;

		if ( !query || query.trim().length === 0 ) {
			resetSearch();
			return;
		}

		if ( unref( selectableLanguages ) ) {
			// Enter loading state now so "no results" isn't shown during the debounce window.
			isSearching.value = true;
			debouncedFetch( query );
		}
	};

	const clearSearchQuery = () => {
		resetSearch();
		searchQuery.value = '';
	};

	const isSelectionUpdated = ( newValue ) => {
		if ( isMultiple ) {
			const current = new Set( selectedValues.value || [] );
			return newValue.length !== current.size ||
				newValue.some( ( val ) => !current.has( val ) );
		}

		return selectedValues.value !== newValue;
	};

	onBeforeUnmount( () => {
		debouncedFetch.cancel();
	} );

	return {
		clearSearchQuery,
		languages,
		searchQuery,
		searchQueryHits,
		searchResults,
		selection,
		selectedValues,
		search,
		isSearching,
		isSelectionUpdated
	};
}

module.exports = useLanguageSelector;
