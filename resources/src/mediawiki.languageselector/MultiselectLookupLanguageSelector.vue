<template>
	<cdx-multiselect-lookup
		:input-value="inputValue"
		:input-chips="selection"
		:selected="selectedValues"
		:menu-items="computeMenuItems( searchQuery, searchResults, languages )"
		:menu-config="menuConfig"
		@input="search"
		@update:input-value="onUpdateInputValue"
		@update:selected="onUpdateSelected"
		@update:input-chips="onUpdateInputChips"
	>
	</cdx-multiselect-lookup>
</template>

<script>
const { defineComponent, ref, toRefs } = require( 'vue' );
const { CdxMultiselectLookup } = require( './codex.js' );
const useLanguageSelector = require( './useLanguageSelector.js' );
const { computeMenuItems } = require( './menuHelper.js' );

module.exports = exports = defineComponent( {
	name: 'MultiselectLookupLanguageSelector',
	components: {
		CdxMultiselectLookup
	},
	props: {
		// eslint-disable-next-line vue/no-unused-properties
		selectableLanguages: {
			type: Object,
			default: () => null
		},
		searchApiUrl: {
			type: String,
			required: true
		},
		debounceDelayMs: {
			type: Number,
			default: 300
		},
		// eslint-disable-next-line vue/no-unused-properties
		selected: {
			type: Array,
			default: () => []
		},
		menuConfig: {
			type: Object,
			default: () => ( {} )
		}
	},
	emits: [
		'update:selected'
	],
	setup( props, { emit } ) {
		const inputValue = ref( '' );
		const { selectableLanguages, selected } = toRefs( props );

		const {
			languages,
			searchQuery,
			searchResults,
			search,
			selection,
			selectedValues,
			isSelectionUpdated,
			clearSearchQuery
		} = useLanguageSelector( selectableLanguages, selected, props.searchApiUrl, props.debounceDelayMs, true );

		const onUpdateInputValue = ( val ) => {
			inputValue.value = val;
			search( val );
		};

		const onUpdateSelected = ( values ) => {
			inputValue.value = '';
			if ( isSelectionUpdated( values ) ) {
				emit( 'update:selected', values );
			}

			clearSearchQuery();
		};

		const onUpdateInputChips = ( chips ) => {
			inputValue.value = '';
			const chipValues = chips.map( ( c ) => c.value );
			if ( isSelectionUpdated( chipValues ) ) {
				emit( 'update:selected', chipValues );
			}

			clearSearchQuery();
		};

		return {
			computeMenuItems,
			inputValue,
			languages,
			searchQuery,
			searchResults,
			search,
			selection,
			selectedValues,
			onUpdateInputValue,
			onUpdateSelected,
			onUpdateInputChips
		};
	}
} );
</script>
