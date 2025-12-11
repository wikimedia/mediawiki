<template>
	<language-selector
		v-slot="{
			languages,
			searchQuery,
			searchResults,
			search,
			selection,
			selectedValues,
			onSelection,
			clearSearchQuery
		}"
		:search-api-url="searchApiUrl"
		:selected="selected"
		:is-multiple="true"
		@update:selected="$emit( 'update:selected', $event )"
	>
		<cdx-multiselect-lookup
			:input-value="inputValue"
			:input-chips="selection"
			:selected="selectedValues"
			:menu-items="computeMenuItems( searchQuery, searchResults, languages )"
			:menu-config="menuConfig"
			@input="search"
			@update:input-value="( val ) => {
				inputValue = val;
				search( val );
			}"
			@update:selected="( values ) => {
				inputValue = '';
				onSelection( values );
				clearSearchQuery();
			}"
			@update:input-chips="( chips ) => {
				inputValue = '';
				onSelection( chips.map( c => c.value ) );
				clearSearchQuery();
			}"
		>
		</cdx-multiselect-lookup>
	</language-selector>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxMultiselectLookup } = require( './codex.js' );
const LanguageSelector = require( './LanguageSelector.vue' );
const { computeMenuItems } = require( './menuHelper.js' );

module.exports = exports = defineComponent( {
	name: 'MultiselectLookupLanguageSelector',
	components: {
		CdxMultiselectLookup,
		LanguageSelector
	},
	props: Object.assign( {}, LanguageSelector.props, {
		selected: {
			type: Array,
			default: () => []
		},
		menuConfig: {
			type: Object,
			default: () => ( {} )
		}
	} ),
	emits: [
		'update:selected'
	],
	setup() {
		const inputValue = ref( '' );

		return {
			computeMenuItems,
			inputValue
		};
	}
} );
</script>
