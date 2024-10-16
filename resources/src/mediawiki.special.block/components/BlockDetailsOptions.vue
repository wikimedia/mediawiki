<template>
	<cdx-field :is-fieldset="true">
		<cdx-checkbox
			v-for="checkbox in checkboxes"
			:key="'checkbox-' + checkbox.value"
			v-model="wrappedModel"
			:input-value="checkbox.value"
			:disabled="disabledState( checkbox )"
			:class="checkbox.class"
		>
			{{ checkbox.label }}
		</cdx-checkbox>
		<template #label>
			{{ label }}
			<span class="cdx-label__label__optional-flag">
				{{ $i18n( 'htmlform-optional-flag' ).text() }}
			</span>
		</template>
		<template #description>
			{{ description }}
		</template>
	</cdx-field>
</template>

<script>
const { defineComponent, toRef } = require( 'vue' );
const { storeToRefs } = require( 'pinia' );
const { CdxCheckbox, CdxField, useModelWrapper } = require( '@wikimedia/codex' );
const useBlockStore = require( '../stores/block.js' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'BlockDetailsField',
	components: { CdxCheckbox, CdxField },
	props: {
		/**
		 * The label displaying for the list of checkboxes
		 */
		label: {
			type: String,
			required: true
		},
		/**
		 * The description displaying for list of checkboxes
		 */
		description: {
			type: String,
			required: true
		},
		/**
		 * The list of checkboxes to display
		 */
		checkboxes: {
			type: Array,
			required: true
		},
		/**
		 * The current checkboxes selected
		 */
		// eslint-disable-next-line vue/no-unused-properties
		modelValue: {
			type: Array,
			required: true
		}
	},
	emits: [
		'update:modelValue'
	],
	setup( props, { emit } ) {
		const wrappedModel = useModelWrapper(
			toRef( props, 'modelValue' ),
			emit
		);
		const { hideNameDisabled } = storeToRefs( useBlockStore() );

		/**
		 * We want the disabled state of the 'Hide username' option to be reactive,
		 * as it should be disabled when the block type is not 'sitewide'.
		 *
		 * @param {Object} checkbox
		 * @return {boolean}
		 */
		function disabledState( checkbox ) {
			return checkbox.value === 'wpHideName' ? hideNameDisabled.value : checkbox.disabled;
		}

		return {
			wrappedModel,
			disabledState
		};
	}
} );
</script>
