<template>
	<cdx-field :status="status" :messages="statusMessages" :disabled="disabled">
		<component
			:is="isMultiple ? 'cdx-multiselect-lookup' : 'cdx-lookup'"
			:id="inputId"
			v-model:input-value="inputValue"
			:input-chips="isMultiple ? selection : undefined"
			:selected="selectedValues"
			:menu-items="menuItems"
			:menu-config="menuConfig"
			:placeholder="placeholder"
			:disabled="disabled"
			:required="required"
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
		</component>
	</cdx-field>
</template>

<script>
const { defineComponent, toRefs } = require( 'vue' );
const { CdxField, CdxLookup, CdxMultiselectLookup } = require( './codex.js' );
const { useLanguageSelector } = require( 'mediawiki.languageselector.core' );
const useLanguageLookup = require( './useLanguageLookup.js' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'LanguageSelector',
	components: {
		CdxField,
		CdxLookup,
		CdxMultiselectLookup
	},
	props: {
		isMultiple: {
			type: Boolean,
			default: false
		},
		// eslint-disable-next-line vue/no-unused-properties
		selectableLanguages: {
			type: Object,
			default: () => null
		},
		searchApiUrl: {
			type: String,
			default: () => mw.util.wikiScript( 'api' )
		},
		debounceDelayMs: {
			type: Number,
			default: 300
		},
		// eslint-disable-next-line vue/no-unused-properties
		selected: {
			type: [ String, Array ],
			default: null
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
		},
		disabled: {
			type: Boolean,
			default: false
		},
		required: {
			type: Boolean,
			default: false
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
		} = useLanguageSelector( selectableLanguages, selected, props.searchApiUrl, props.debounceDelayMs, props.isMultiple );

		const {
			inputValue,
			status,
			statusMessages,
			menuItems,
			onUpdateInputValue,
			onUpdateSelected,
			onUpdateInputChips,
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
			isMultiple: props.isMultiple
		} );

		return {
			inputValue,
			status,
			statusMessages,
			searchQuery,
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
