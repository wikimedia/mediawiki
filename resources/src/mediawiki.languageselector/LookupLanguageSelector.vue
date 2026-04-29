<template>
	<cdx-field :status="status" :messages="statusMessages">
		<cdx-lookup
			:id="inputId"
			v-model:input-value="inputValue"
			:selected="selectedValues"
			:menu-items="menuItems"
			:menu-config="menuConfig"
			:placeholder="placeholder"
			@update:input-value="onUpdateInputValue"
			@update:selected="onUpdateSelected"
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
		</cdx-lookup>
	</cdx-field>
</template>

<script>
const { defineComponent, toRefs } = require( 'vue' );
const { CdxField, CdxLookup } = require( './codex.js' );
const { useLanguageSelector } = require( 'mediawiki.languageselector.core' );
const useLanguageLookup = require( './useLanguageLookup.js' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'LookupLanguageSelector',
	components: {
		CdxField,
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
		inputId: {
			type: String,
			default: ''
		},
		placeholder: {
			type: String,
			default: ''
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
			selection,
			selectedValues
		} = useLanguageSelector( selectableLanguages, selected, props.searchApiUrl, props.debounceDelayMs );

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
			isMultiple: false
		} );

		return {
			inputValue,
			status,
			statusMessages,
			searchQuery,
			selectedValues,
			menuItems,
			onBlur,
			onUpdateInputValue,
			onUpdateSelected
		};
	}
} );
</script>
