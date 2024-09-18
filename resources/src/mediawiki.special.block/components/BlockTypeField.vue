<template>
	<cdx-field
		:is-fieldset="true"
		:disabled="disabled"
	>
		<template #label>
			{{ $i18n( 'block-actions' ).text() }}
		</template>
		<cdx-radio
			v-for="radio in blockTypeOptions"
			:key="'radio-' + radio.value"
			v-model="wrappedBlockTypeValue"
			name="radio-group-descriptions"
			:input-value="radio.value"
		>
			{{ radio.label }}
			<template #description>
				<span v-i18n-html="radio.descriptionMsg"></span>
			</template>
		</cdx-radio>
		<div
			v-if="wrappedBlockTypeValue === 'partial'"
			class="mw-block-partial-options"
		>
			<div>
				Pages Placeholder
			</div>
			<div>
				Namespaces Placeholder
			</div>
			<cdx-checkbox
				v-for="checkbox in partialBlockOptions"
				:key="'checkbox-' + checkbox.value"
				v-model="wrappedModel"
				:input-value="checkbox.value"
			>
				{{ checkbox.label }}
			</cdx-checkbox>
		</div>
	</cdx-field>
</template>

<script>
const { defineComponent, toRef } = require( 'vue' );
const { CdxCheckbox, CdxRadio, CdxField, useModelWrapper } = require( '@wikimedia/codex' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'BlockTypeField',
	components: { CdxCheckbox, CdxRadio, CdxField },
	props: {
		/**
		 * The list of checkboxes to display for partial block
		 */
		partialBlockOptions: {
			type: Array,
			required: true
		},
		// eslint-disable-next-line vue/no-unused-properties
		modelValue: {
			type: Array,
			required: true
		},
		// eslint-disable-next-line vue/no-unused-properties
		blockTypeValue: {
			type: String,
			required: true
		},
		/**
		 * Whether the field is disabled
		 */
		disabled: {
			type: Boolean,
			default: false
		}
	},
	emits: [
		'update:modelValue',
		'update:blockTypeValue'
	],
	setup( props, { emit } ) {
		const wrappedModel = useModelWrapper(
			toRef( props, 'modelValue' ),
			emit
		);
		const wrappedBlockTypeValue = useModelWrapper(
			toRef( props, 'blockTypeValue' ),
			emit,
			'update:blockTypeValue'
		);
		const blockTypeOptions = [
			{
				label: mw.message( 'blocklist-type-opt-sitewide' ),
				descriptionMsg: 'ipb-sitewide-help',
				value: 'sitewide'
			},
			{
				label: mw.message( 'blocklist-type-opt-partial' ),
				descriptionMsg: 'ipb-partial-help',
				value: 'partial'
			}
		];

		return {
			blockTypeOptions,
			wrappedModel,
			wrappedBlockTypeValue
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-form {
	margin-top: @spacing-100;
}

.mw-block-partial-options {
	padding-left: calc( @size-125 + @spacing-50 );
}
</style>
