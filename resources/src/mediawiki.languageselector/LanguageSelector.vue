<template>
	<slot
		:languages="languages"
		:search-results="searchResults"
		:search-query="searchQuery"
		:selection="selection"
		:search="debouncedSearch"
		:clear-search-query="clearSearchQuery"
		:on-selection="onSelection"
	></slot>
</template>

<script>
const { defineComponent, ref, computed, onMounted, onBeforeUnmount } = require( 'vue' );
const supportedLanguages = require( './supportedLanguages.json' );
const languageSearchClient = require( './languageSearch.js' );
const debounce = require( './debounce.js' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'LanguageSelector',
	props: {
		/** List of languages in language code : name format */
		selectableLanguages: {
			type: Object,
			default: () => null
		},
		searchApiUrl: {
			type: String,
			required: true
		},
		/** Debounce delay in milliseconds for search input */
		debounceDelayMs: {
			type: Number,
			default: 300
		},
		/** Currently selected language code */
		selected: {
			type: String,
			default: null
		}
	},
	emits: [ 'update:selected' ],
	setup( props, { emit } ) {
		const searchQuery = ref( '' );
		const searchResults = ref( [] );
		let languageClient = null;

		const languages = computed( () => props.selectableLanguages || supportedLanguages );
		const selection = computed( () => ( {
			value: props.selected,
			label: languages.value[ props.selected ] || props.selected
		} ) );

		const fetchLanguages = async ( query ) => {
			const searchRequest = languageClient.searchLanguages( query );

			try {
				const response = await searchRequest;
				const responseLanguageCodes = Object.keys( response.languagesearch || {} );
				const filteredCodes = responseLanguageCodes.filter(
					( code ) => Object.keys( languages.value ).includes( code )
				);

				searchResults.value = filteredCodes;
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

		const debouncedSearch = debounce( search, props.debounceDelayMs );

		const clearSearchQuery = () => {
			searchQuery.value = null;
		};

		const onSelection = ( newValue ) => {
			if ( selection.value.value !== newValue ) {
				emit( 'update:selected', newValue );
			}
		};

		onMounted( () => {
			languageClient = languageSearchClient( props.searchApiUrl );
		} );

		onBeforeUnmount( () => {
			debouncedSearch.cancel();
		} );

		return {
			clearSearchQuery,
			languages,
			searchQuery,
			searchResults,
			selection,
			debouncedSearch,
			onSelection
		};
	}
} );
</script>
