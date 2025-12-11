<template>
	<language-selector
		v-slot="{
			languages,
			searchQuery,
			searchResults,
			search,
			clearSearchQuery,
			onSelection,
			selection
		}"
		:search-api-url="searchApiUrl"
		:selected="selected"
		@update:selected="$emit( 'update:selected', $event )"
	>
		<cdx-lookup
			:input-value="inputValue !== null ? inputValue : ( selection.label || '' )"
			:selected="selection.value"
			:menu-items="computeMenuItems( searchQuery, searchResults, languages )"
			:menu-config="menuConfig"
			@update:input-value="( val ) => {
				inputValue = val;
				if ( inputValue !== selection.label ) {
					search( val );
				}
			}"
			@update:selected="( val ) => {
				onSelection( val )
				if ( val ) {
					clearSearchQuery()
				}
			}"
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
	</language-selector>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxLookup } = require( './codex.js' );
const LanguageSelector = require( './LanguageSelector.vue' );
const { computeMenuItems } = require( './menuHelper.js' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'LookupLanguageSelector',
	components: {
		CdxLookup,
		LanguageSelector
	},
	props: {
		selected: {
			type: String,
			default: null
		},
		/** Search API URL */
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
	setup() {
		const inputValue = ref( null );

		return {
			computeMenuItems,
			inputValue
		};
	}
} );
</script>
