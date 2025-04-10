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
			v-model:selected="selected"
			:menu-items="reasonOptions"
			name="wpReason"
		></cdx-select>

		<cdx-text-input
			v-model="other"
			:placeholder="$i18n( 'block-reason-other' ).text()"
			:maxlength="reasonMaxLength"
			name="wpReason-other"
		></cdx-text-input>
	</cdx-field>
</template>

<script>
const { computed, defineComponent, ref, Ref, watch } = require( 'vue' );
const { CdxSelect, CdxField, CdxTextInput } = require( '@wikimedia/codex' );

module.exports = exports = defineComponent( {
	name: 'ReasonField',
	components: { CdxSelect, CdxField, CdxTextInput },
	props: {
		modelValue: { type: String, default: '' }
	},
	emits: [ 'update:modelValue' ],
	setup( props, { emit } ) {
		/**
		 * @type {Ref<string>}
		 */
		const selected = ref( String );
		/**
		 * @type {Ref<string>}
		 */
		const other = ref( String );

		const reasonOptions = mw.config.get( 'blockReasonOptions' );
		const reasonEditUrl = mw.util.getUrl( 'MediaWiki:Ipbreason-dropdown', { action: 'edit' } );

		/**
		 * Set `selected` and `other` values from a concatenated reason string.
		 *
		 * The reason is a single string that possibly starts with one of the predefined reasons,
		 * and can have an 'other' value separated by a colon.
		 * Here we replicate what's done in PHP in HTMLSelectAndOtherField at https://w.wiki/CPMs
		 *
		 * @param {string} given
		 */
		function setFieldsFromGiven( given ) {
			// TODO: Use Array.prototype.flat() when we have ES2019
			const flattenedOptions = [];
			for ( const opt of reasonOptions ) {
				if ( opt.items ) {
					flattenedOptions.push( ...opt.items );
				} else {
					flattenedOptions.push( opt );
				}
			}
			for ( const opt of flattenedOptions ) {
				const possPrefix = opt.value + mw.msg( 'colon-separator' );
				if ( given.startsWith( possPrefix ) ) {
					selected.value = opt.value;
					other.value = given.slice( possPrefix.length );
					return;
				} else if ( given === opt.value ) {
					selected.value = opt.value;
					other.value = '';
					return;
				}
			}
			selected.value = 'other';
			other.value = given;
		}

		/**
		 * Emit the reason as a single concatenated string
		 * of the selected value and the 'other' value.
		 */
		function emitReason() {
			if ( selected.value === 'other' ) {
				emit( 'update:modelValue', other.value );
			} else {
				emit( 'update:modelValue', selected.value + (
					other.value ? mw.msg( 'colon-separator' ) + other.value : ''
				) );
			}
		}

		watch( () => props.modelValue, ( newVal ) => {
			if ( newVal !== undefined ) {
				setFieldsFromGiven( newVal );
			}
		}, { immediate: true } );

		watch(
			computed( () => [ selected.value, other.value ] ),
			emitReason
		);

		return {
			reasonOptions,
			reasonMaxLength: mw.config.get( 'blockReasonMaxLength' ),
			selected,
			other,
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
