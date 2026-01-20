<template>
	<cdx-multiselect-lookup
		v-model:input-value="inputValue"
		:input-chips="selection"
		:selected="selectedValues"
		:menu-items="menuItems"
		:menu-config="menuConfig"
		:placeholder="placeholder"
		@input="search"
		@update:input-value="onUpdateInputValue"
		@update:selected="onUpdateSelected"
		@update:input-chips="onUpdateInputChips"
	>
		<template #menu-item="{ menuItem }">
			<slot
				name="menu-item"
				:menu-item="menuItem"
				:language-code="menuItem.value"
				:language-name="menuItem.label">
				{{ menuItem.label }}
			</slot>
		</template>
		<template #no-results>
			<slot name="no-results" :search-query="searchQuery">
				{{ $i18n( 'languageselector-no-results' ).text() }}
			</slot>
		</template>
	</cdx-multiselect-lookup>
</template>

<script>
const { defineComponent, ref, toRefs, watch } = require( 'vue' );
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
		},
		placeholder: {
			type: String,
			default: null
		}
	},
	emits: [
		'update:selected'
	],
	setup( props, { emit } ) {
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

		const inputValue = ref( '' );
		const menuItems = ref( computeMenuItems( languages.value ) );

		const onUpdateInputValue = ( val ) => {
			if ( val === '' ) {
				menuItems.value = computeMenuItems( languages.value );
				return;
			}

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

		watch( searchResults, () => {
			if ( inputValue.value === '' ) {
				menuItems.value = computeMenuItems( languages.value );
			} else {
				menuItems.value = computeMenuItems( languages.value, searchResults.value );
			}
		} );

		return {
			searchQuery,
			inputValue,
			search,
			selection,
			selectedValues,
			menuItems,
			onUpdateInputValue,
			onUpdateSelected,
			onUpdateInputChips
		};
	}
} );
</script>
