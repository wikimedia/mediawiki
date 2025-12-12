const { ref, Ref, computed, onBeforeUnmount } = require( 'vue' );
const supportedLanguages = require( './supportedLanguages.json' );
const languageSearchClient = require( './languageSearch.js' );
const debounce = require( './debounce.js' );

/**
 * Composable for language selection logic
 *
 * @param {Ref<Object>} selectableLanguages
 * @param {Ref<string|string[]>} selected
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

	const languages = computed( () => selectableLanguages.value || supportedLanguages );
	const selection = computed( () => {
		if ( isMultiple ) {
			return selected.value.map( ( langCode ) => ( {
				value: langCode,
				label: languages.value[ langCode ] || langCode
			} ) );
		} else {
			return {
				value: selected.value,
				label: languages.value[ selected.value ] || selected.value
			};
		}
	} );
	const selectedValues = computed( () => {
		if ( isMultiple ) {
			return selection.value.map( ( item ) => item.value );
		}
		return selection.value.value;
	} );

	let languageClient = null;
	const fetchLanguages = async ( query ) => {
		if ( !languageClient ) {
			languageClient = languageSearchClient( searchApiUrl );
		}
		const searchRequest = languageClient.searchLanguages( query );

		try {
			const response = await searchRequest;
			const responseLanguageCodes = Object.keys( response.languagesearch || {} );
			searchResults.value = responseLanguageCodes.filter(
				( code ) => Object.keys( languages.value ).includes( code )
			);
		} catch ( error ) {
			mw.log.error( 'Language search failed:', error );
			throw new Error( 'Language search failed' + error );
		}
	};

	const search = ( query ) => {
		searchQuery.value = query;
		if ( !query || query.trim().length === 0 ) {
			searchResults.value = [];
			return;
		}

		fetchLanguages( query );
	};

	const debouncedSearch = debounce( search, debounceDelayMs );

	const clearSearchQuery = () => {
		searchQuery.value = null;
	};

	const isSelectionUpdated = ( newValue ) => {
		if ( isMultiple ) {
			return newValue.length !== selectedValues.value.length ||
				newValue.some( ( val, idx ) => val !== selectedValues.value[ idx ] );
		}

		return selection.value.value !== newValue;
	};

	onBeforeUnmount( () => {
		debouncedSearch.cancel();
	} );

	return {
		clearSearchQuery,
		languages,
		searchQuery,
		searchResults,
		selection,
		selectedValues,
		search: debouncedSearch,
		isSelectionUpdated
	};
}

module.exports = useLanguageSelector;
