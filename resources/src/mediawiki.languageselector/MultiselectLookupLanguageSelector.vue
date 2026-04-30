<template>
	<language-selector
		:is-multiple="true"
		:selectable-languages="selectableLanguages"
		:search-api-url="searchApiUrl"
		:debounce-delay-ms="debounceDelayMs"
		:selected="selected"
		:menu-config="menuConfig"
		:input-id="inputId"
		:placeholder="placeholder"
		@update:selected="$emit( 'update:selected', $event )"
	>
		<template #menu-item="slotProps">
			<slot name="menu-item" v-bind="slotProps"></slot>
		</template>
		<template #no-results="slotProps">
			<slot name="no-results" v-bind="slotProps"></slot>
		</template>
	</language-selector>
</template>

<script>
const { defineComponent } = require( 'vue' );
const LanguageSelector = require( './LanguageSelector.vue' );

/**
 * Multi-select language lookup.
 *
 * Thin backwards-compatibility wrapper around the unified LanguageSelector
 * component with `isMultiple` fixed to `true`.
 *
 * @deprecated Use LanguageSelector with `is-multiple="true"` instead.
 */
// @vue/component
module.exports = exports = defineComponent( {
	name: 'MultiselectLookupLanguageSelector',
	components: {
		LanguageSelector
	},
	props: {
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
	emits: [ 'update:selected' ]
} );
</script>
