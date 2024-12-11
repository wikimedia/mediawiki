<template>
	<cdx-field :is-fieldset="true">
		<template #label>
			{{ $i18n( 'block-reason' ).text() }}
		</template>
		<template #help-text>
			{{ $i18n( 'block-reason-help' ).text() }}
			<div class="mw-block-reason-edit">
				<a :href="reasonEditUrl">
					{{ $i18n( 'ipb-edit-dropdown' ) }}
				</a>
			</div>
		</template>

		<cdx-select
			v-model:selected="wrappedSelected"
			:menu-items="reasonOptions"
			name="wpReason"
		></cdx-select>

		<cdx-text-input
			v-model="wrappedOther"
			:placeholder="$i18n( 'block-reason-other' ).text()"
			:maxlength="reasonMaxLength"
			name="wpReason-other"
		></cdx-text-input>
	</cdx-field>
</template>

<script>
const { defineComponent, toRef } = require( 'vue' );
const { CdxSelect, CdxField, CdxTextInput, useModelWrapper } = require( '@wikimedia/codex' );

module.exports = exports = defineComponent( {
	name: 'ReasonField',
	components: { CdxSelect, CdxField, CdxTextInput },
	props: {
		// eslint-disable-next-line vue/no-unused-properties
		selected: { type: [ String, Number, null ], default: 'other' },
		// eslint-disable-next-line vue/no-unused-properties
		other: { type: String, default: '' }
	},
	emits: [
		'update:selected',
		'update:other'
	],
	setup( props, { emit } ) {
		const reasonOptions = mw.config.get( 'blockReasonOptions' );
		const wrappedSelected = useModelWrapper( toRef( props, 'selected' ), emit, 'update:selected' );
		const wrappedOther = useModelWrapper( toRef( props, 'other' ), emit, 'update:other' );
		const reasonPreset = mw.config.get( 'blockReasonPreset' );
		const reasonEditUrl = mw.util.getUrl( 'MediaWiki:Blockreason-dropdown', { action: 'edit' } );

		if ( reasonPreset !== 'other' && reasonOptions.some( ( option ) => option.value === reasonPreset ) ) {
			emit( 'update:selected', reasonPreset );
		}

		return {
			reasonOptions,
			reasonMaxLength: mw.config.get( 'blockReasonMaxLength' ),
			wrappedSelected,
			wrappedOther,
			reasonEditUrl
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.cdx-select-vue {
	margin-bottom: @spacing-50;
	width: 100%;
}

.mw-block-reason-edit {
	a {
		font-size: 90%;
	}
}
</style>
