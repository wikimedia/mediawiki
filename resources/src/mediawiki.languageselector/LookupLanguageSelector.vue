<template>
	<cdx-lookup
		v-model:input-value="inputValue"
		:selected="selection.value"
		:menu-items="menuItems"
		:menu-config="menuConfig"
		:placeholder="placeholder"
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
const { defineComponent, ref, toRefs, watch } = require( 'vue' );
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
		},
		placeholder: {
			type: String,
			default: null
		}
	},
	emits: [ 'update:selected' ],
	setup( props, { emit } ) {
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

		const inputValue = ref( selection.value && selection.value.label || '' );
		const menuItems = ref( computeMenuItems( languages.value ) );

		const onUpdateInputValue = ( val ) => {
			if ( val === '' ) {
				menuItems.value = computeMenuItems( languages.value );
				return;
			}

			if ( val !== selection.value.label ) {
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

		watch( searchResults, () => {
			if ( inputValue.value === '' ) {
				menuItems.value = computeMenuItems( languages.value );
			} else {
				menuItems.value = computeMenuItems( languages.value, searchResults.value );
			}
		} );

		return {
			inputValue,
			searchQuery,
			selection,
			menuItems,
			onUpdateInputValue,
			onUpdateSelected
		};
	}
} );
</script>
