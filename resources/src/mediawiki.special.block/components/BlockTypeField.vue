<template>
	<cdx-field :is-fieldset="true">
		<template #label>
			{{ $i18n( 'block-actions' ).text() }}
		</template>
		<cdx-radio
			v-for="radio in typeCheckboxes"
			:key="'radio-' + radio.value"
			v-model="type"
			name="wpEditingRestriction"
			:input-value="radio.value"
		>
			{{ radio.label }}
			<template #description>
				<span v-i18n-html="radio.descriptionMsg"></span>
			</template>
		</cdx-radio>
		<div
			v-if="type === 'partial'"
			class="mw-block-partial-options"
		>
			<pages-field></pages-field>
			<namespaces-field></namespaces-field>

			<cdx-field :is-fieldset="true">
				<cdx-checkbox
					v-for="checkbox in partialBlockCheckboxes"
					:key="'checkbox-' + checkbox.value"
					v-model="partialOptions"
					:input-value="checkbox.value"
				>
					{{ checkbox.label }}
				</cdx-checkbox>
			</cdx-field>
		</div>
	</cdx-field>
</template>

<script>
const { defineComponent } = require( 'vue' );
const { CdxCheckbox, CdxRadio, CdxField } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const useBlockStore = require( '../stores/block.js' );
const PagesField = require( './PagesField.vue' );
const NamespacesField = require( './NamespacesField.vue' );

module.exports = exports = defineComponent( {
	name: 'BlockTypeField',
	components: {
		CdxCheckbox,
		CdxRadio,
		CdxField,
		NamespacesField,
		PagesField
	},
	setup() {
		const { type, partialOptions } = storeToRefs( useBlockStore() );

		const typeCheckboxes = [
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

		const partialBlockCheckboxes = mw.config.get( 'partialBlockActionOptions' ) ?
			Object.keys( mw.config.get( 'partialBlockActionOptions' ) ).map(
				// Messages that can be used here:
				// * ipb-action-upload
				// * ipb-action-move
				// * ipb-action-create
				( key ) => Object( { label: mw.message( key ).text(), value: key } ) ) :
			[];

		return {
			type,
			typeCheckboxes,
			partialOptions,
			partialBlockCheckboxes
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

	.cdx-label__label__text {
		font-weight: @font-weight-normal;
	}
}
</style>
