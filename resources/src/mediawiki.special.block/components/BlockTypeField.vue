<template>
	<cdx-field :is-fieldset="true">
		<template #label>
			{{ $i18n( 'block-actions' ).text() }}
		</template>
		<cdx-radio
			v-for="radio in blockTypeOptions"
			:key="'radio-' + radio.value"
			v-model="blockTypeValue"
			name="radio-group-descriptions"
			:input-value="radio.value"
		>
			{{ radio.label }}
			<template #description>
				<span v-i18n-html="radio.descriptionMsg"></span>
			</template>
		</cdx-radio>
		<div id="partial-options" v-if="blockTypeValue === 'partial' ">
			<div>
				Pages Placeholder
			</div>
			<div>
				Namespaces Placeholder
			</div>
			<cdx-checkbox
				v-for="checkbox in partialBlockOptions"
				:key="'checkbox-' + checkbox.value"
				:input-value="checkbox.value"
				v-model="wrappedModel"
			>
				{{ checkbox.label }}
			</cdx-checkbox>
		</div>
	</cdx-field>
</template>

<script>
const { defineComponent, ref, toRef } = require( 'vue' );
const { CdxCheckbox, CdxRadio, CdxField, useModelWrapper } = require( '@wikimedia/codex' );

// @vue/component
module.exports = defineComponent( {
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
		const blockTypeValue = ref( 'partial' );
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
			blockTypeValue,
			blockTypeOptions,
			wrappedModel
		};
	}
} );
</script>

<style lang="less">
	#partial-options {
		margin-left: 30px;
	}
</style>
