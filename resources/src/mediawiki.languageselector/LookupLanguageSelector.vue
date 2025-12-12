<template>
	<cdx-lookup
		:input-value="inputValue !== null ? inputValue : ( selection.label || '' )"
		:selected="selection.value"
		:menu-items="computeMenuItems( searchQuery, searchResults, languages )"
		:menu-config="menuConfig"
		@update:input-value="onUpdateInputValue"
		@update:selected="onUpdateSelected"
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
	</cdx-lookup>
</template>

<script>
const { defineComponent, ref, toRefs } = require( 'vue' );
const { CdxLookup } = require( './codex.js' );
const useLanguageSelector = require( './useLanguageSelector.js' );
const { computeMenuItems } = require( './menuHelper.js' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'LookupLanguageSelector',
	components: {
		CdxLookup
	},
	props: {
		// eslint-disable-next-line vue/no-unused-properties
		selectableLanguages: {
			type: Object,
			default: () => null
		},
		debounceDelayMs: {
			type: Number,
			default: 300
		},
		// eslint-disable-next-line vue/no-unused-properties
		selected: {
			type: String,
			default: null
		},
		searchApiUrl: {
			type: String,
			required: true
		},
		menuConfig: {
			type: Object,
			default: () => ( {} )
		}
	},
	emits: [ 'update:selected' ],
	setup( props, { emit } ) {
		const inputValue = ref( null );

		const { selectableLanguages, selected } = toRefs( props );
		const {
			languages,
			searchQuery,
			searchResults,
			search,
			clearSearchQuery,
			isSelectionUpdated,
			selection
		} = useLanguageSelector( selectableLanguages, selected, props.searchApiUrl, props.debounceDelayMs );

		const onUpdateInputValue = ( val ) => {
			inputValue.value = val;
			if ( inputValue.value !== selection.value.label ) {
				search( val );
			}
		};

		const onUpdateSelected = ( val ) => {
			if ( isSelectionUpdated( val ) ) {
				emit( 'update:selected', val );
			}
			if ( val ) {
				clearSearchQuery();
			}
		};

		return {
			computeMenuItems,
			inputValue,
			languages,
			searchQuery,
			searchResults,
			selection,
			onUpdateInputValue,
			onUpdateSelected
		};
	}
} );
</script>
