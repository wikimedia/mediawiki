<template>
	<cdx-field :status="status" :messages="statusMessages">
		<cdx-multiselect-lookup
			:id="inputId"
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
			@blur="onBlur"
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
	</cdx-field>
</template>

<script>
const { defineComponent, toRefs } = require( 'vue' );
const { CdxField, CdxMultiselectLookup } = require( './codex.js' );
const { useLanguageSelector } = require( 'mediawiki.languageselector.core' );
const useLanguageLookup = require( './useLanguageLookup.js' );

module.exports = exports = defineComponent( {
	name: 'MultiselectLookupLanguageSelector',
	components: {
		CdxField,
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
		inputId: {
			type: String,
			default: ''
		},
		placeholder: {
			type: String,
			default: ''
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

		const {
			inputValue,
			status,
			statusMessages,
			menuItems,
			onUpdateInputValue,
			onUpdateSelected,
			onBlur
		} = useLanguageLookup( {
			selection,
			selectedValues,
			languages,
			searchQuery,
			searchResults,
			search,
			clearSearchQuery,
			isSelectionUpdated,
			emit,
			isMultiple: true
		} );

		const onUpdateInputChips = ( chips ) => {
			const chipValues = chips.map( ( c ) => c.value );
			onUpdateSelected( chipValues );
		};

		return {
			searchQuery,
			inputValue,
			status,
			statusMessages,
			search,
			selection,
			selectedValues,
			menuItems,
			onBlur,
			onUpdateInputValue,
			onUpdateSelected,
			onUpdateInputChips
		};
	}
} );
</script>
