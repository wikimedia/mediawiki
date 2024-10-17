<template>
	<cdx-field :is-fieldset="true">
		<template #label>
			{{ $i18n( 'block-actions' ).text() }}
		</template>
		<cdx-radio
			v-for="radio in typeCheckboxes"
			:key="'radio-' + radio.value"
			v-model="type"
			name="radio-group-descriptions"
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
			<div>
				Pages Placeholder
			</div>
			<div>
				Namespaces Placeholder
			</div>
			<cdx-checkbox
				v-for="checkbox in partialBlockCheckboxes"
				:key="'checkbox-' + checkbox.value"
				v-model="partialOptions"
				:input-value="checkbox.value"
			>
				{{ checkbox.label }}
			</cdx-checkbox>
		</div>
	</cdx-field>
</template>

<script>
const { defineComponent } = require( 'vue' );
const { CdxCheckbox, CdxRadio, CdxField } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const useBlockStore = require( '../stores/block.js' );

module.exports = exports = defineComponent( {
	name: 'BlockTypeField',
	components: { CdxCheckbox, CdxRadio, CdxField },
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
}
</style>
