<template>
	<cdx-field
		class="mw-block-expiry-field"
		:is-fieldset="true"
		:disabled="disabled"
	>
		<template #label>
			{{ $i18n( 'block-expiry' ).text() }}
		</template>
		<cdx-radio
			v-model="expiryType"
			name="expiryType"
			input-value="preset-duration"
		>
			{{ $i18n( 'block-expiry-preset' ).text() }}
		</cdx-radio>
		<cdx-select
			v-if="expiryType === 'preset-duration'"
			v-model:selected="presetDuration"
			class="mw-block-expiry-field__preset-duration"
			:menu-items="presetDurationOptions"
			:default-label="$i18n( 'block-expiry-preset-placeholder' ).text()"
		></cdx-select>
		<cdx-radio
			v-model="expiryType"
			name="expiryType"
			input-value="custom-duration"
		>
			{{ $i18n( 'block-expiry-custom' ).text() }}
		</cdx-radio>
		<div
			v-if="expiryType === 'custom-duration'"
			class="mw-block-expiry-field__custom-duration"
		>
			<cdx-text-input
				v-model="customDurationNumber"
				class="mw-block-expiry-field__custom-duration__number"
				input-type="number"
				min="1"
			></cdx-text-input>
			<cdx-select
				v-model:selected="customDurationUnit"
				:menu-items="customDurationOptions"
				class="mw-block-expiry-field__custom-duration__unit"
			></cdx-select>
		</div>
		<cdx-radio
			v-model="expiryType"
			name="expiryType"
			input-value="datetime"
		>
			{{ $i18n( 'block-expiry-datetime' ).text() }}
		</cdx-radio>
		<cdx-text-input
			v-if="expiryType === 'datetime'"
			v-model="datetime"
			class="mw-block-expiry-field__datetime"
			input-type="datetime-local"
			name="wpExpiry-other"
			:min="new Date().toISOString().slice( 0, 16 )"
		></cdx-text-input>
	</cdx-field>
</template>

<script>
const { defineComponent, ref, computed, watch } = require( 'vue' );
const { CdxField, CdxRadio, CdxSelect, CdxTextInput } = require( '@wikimedia/codex' );

module.exports = exports = defineComponent( {
	name: 'ExpiryField',
	components: {
		CdxField,
		CdxRadio,
		CdxSelect,
		CdxTextInput
	},
	props: {
		// This is an object of the form { value: String, type: String }
		modelValue: {
			type: Object,
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
		'update:modelValue'
	],
	setup( props, { emit } ) {
		const blockExpiryOptions = mw.config.get( 'blockExpiryOptions' );
		const presetDurationOptions = Object.keys( blockExpiryOptions )
			.map( ( key ) => ( { label: key, value: blockExpiryOptions[ key ] } ) )
			// Don't include "other" in the preset options as it's handled separately in the new UI
			.filter( ( option ) => option.value !== 'other' );

		const customDurationOptions = [
			{ value: 'minutes', label: mw.msg( 'block-expiry-custom-minutes' ) },
			{ value: 'hours', label: mw.msg( 'block-expiry-custom-hours' ) },
			{ value: 'days', label: mw.msg( 'block-expiry-custom-days' ) },
			{ value: 'weeks', label: mw.msg( 'block-expiry-custom-weeks' ) },
			{ value: 'months', label: mw.msg( 'block-expiry-custom-months' ) },
			{ value: 'years', label: mw.msg( 'block-expiry-custom-years' ) }
		];

		const presetDuration = ref( null );
		const customDurationNumber = ref( 1 );
		const customDurationUnit = ref( 'hours' );
		const datetime = ref( '' );
		const expiryType = ref( 'preset-duration' );

		const computedModelValue = computed( () => {
			let value;
			if ( expiryType.value === 'preset-duration' ) {
				value = presetDuration.value;
			} else if ( expiryType.value === 'custom-duration' ) {
				value = `${ Number( customDurationNumber.value ) } ${ customDurationUnit.value }`;
			} else {
				value = datetime.value;
			}
			return {
				type: expiryType.value,
				value
			};
		} );

		watch( computedModelValue, ( newValue ) => {
			emit( 'update:modelValue', newValue );
		} );

		watch( () => props.modelValue, ( newValue ) => {
			expiryType.value = newValue.type || 'preset-duration';
			if ( newValue.type === 'preset-duration' ) {
				presetDuration.value = newValue.value;
			} else if ( newValue.type === 'custom-duration' ) {
				const [ num, unit ] = newValue.value.split( ' ' );
				customDurationNumber.value = num;
				customDurationUnit.value = unit;
			} else if ( newValue.type === 'datetime' ) {
				datetime.value = newValue.value;
			}
		}, { deep: true, immediate: true } );

		// Attempt to match up the default expiry (MediaWiki:Ipb-default-expiry) with one of the
		// preset or default or custom duration options, or with "infinite" if it's infinite.
		const givenPresetDuration = mw.config.get( 'blockExpiryPreset' );
		const givenDefaultDuration = mw.config.get( 'blockDefaultExpiry' );
		if ( mw.util.isInfinity( givenDefaultDuration ) ) {
			presetDuration.value = 'infinite';
			expiryType.value = 'preset-duration';
		} else if ( givenPresetDuration && presetDurationOptions.some( ( option ) => option.value === givenPresetDuration ) ) {
			presetDuration.value = givenPresetDuration;
			expiryType.value = 'preset-duration';
		} else if ( presetDurationOptions.some( ( option ) => option.value === givenDefaultDuration ) ) {
			presetDuration.value = givenDefaultDuration;
			expiryType.value = 'preset-duration';
		} else if ( givenPresetDuration && /^\d+ [a-z]+$/.test( givenPresetDuration ) ) {
			const [ presetDurationNumber, presetDurationUnit ] = givenPresetDuration.split( ' ' );
			if ( presetDurationNumber && customDurationOptions.some( ( option ) => option.value === presetDurationUnit ) ) {
				customDurationNumber.value = Number( presetDurationNumber );
				customDurationUnit.value = presetDurationUnit;
				expiryType.value = 'custom-duration';
			}
		} else if ( /^\d+ [a-z]+$/.test( givenDefaultDuration ) ) {
			const [ defaultDurationNumber, defaultDurationUnit ] = givenDefaultDuration.split( ' ' );
			if ( defaultDurationNumber && customDurationOptions.some( ( option ) => option.value === defaultDurationUnit ) ) {
				customDurationNumber.value = Number( defaultDurationNumber );
				customDurationUnit.value = defaultDurationUnit;
				expiryType.value = 'custom-duration';
			}
		}

		return {
			presetDurationOptions,
			customDurationOptions,
			presetDuration,
			customDurationNumber,
			customDurationUnit,
			datetime,
			expiryType
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-expiry-field {
	&__preset-duration,
	&__custom-duration,
	&__datetime {
		// HACK: We want @spacing-35 between the radio and the input, and @spacing-75 between the
		// input and the next radio. But the input is outside the radio, and the radio sets a
		// margin-bottom of spacing-75. So cancel out part of that using a negative margin-top on
		// the input, then reapply spacing-75 as the margin-bottom on the input.
		// This won't be needed once the Radio component has a slot to put an input in.
		margin-top: @spacing-35 - @spacing-75;
		margin-bottom: @spacing-75;
		// HACK: Apply the same padding to the input that Radio applies to the label, so that they
		// line up.
		padding-left: calc( @size-125 + @spacing-50 );
		// Make the inputs the same width as the other form fields, so that their ends line up.
		// But use border-box so that the padding-left is taken into account.
		min-width: @size-4000;
		box-sizing: @box-sizing-base;
	}

	&__custom-duration {
		display: flex;
		gap: @spacing-50;

		&__number,
		&__unit {
			flex-grow: 1;
		}
	}
}
</style>
