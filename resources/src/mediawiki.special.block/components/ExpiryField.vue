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
			<template v-if="expiryType === 'preset-duration'" #custom-input>
				<cdx-field
					class="mw-block-expiry-field__preset-duration"
					:status="presetDurationStatus"
					:messages="presetDurationMessages"
				>
					<cdx-select
						v-if="expiryType === 'preset-duration'"
						v-model:selected="presetDuration"
						:menu-items="presetDurationOptions"
						:default-label="$i18n( 'block-expiry-preset-placeholder' ).text()"
						@update:selected="() => {
							presetDurationStatus = 'default';
							presetDurationMessages = {};
						}"
					></cdx-select>
				</cdx-field>
			</template>
		</cdx-radio>

		<cdx-radio
			v-model="expiryType"
			name="expiryType"
			input-value="custom-duration"
		>
			{{ $i18n( 'block-expiry-custom' ).text() }}
			<template v-if="expiryType === 'custom-duration'" #custom-input>
				<cdx-field
					class="mw-block-expiry-field__custom-duration"
					:status="customDurationStatus"
					:messages="customDurationMessages"
				>
					<validating-text-input
						v-model="customDurationNumber"
						input-type="number"
						min="1"
						required
						@update:status="( status, message ) => {
							customDurationMessages = message;
							customDurationStatus = status;
						}"
					></validating-text-input>
					<cdx-select
						v-model:selected="customDurationUnit"
						:menu-items="customDurationOptions"
					></cdx-select>
				</cdx-field>
			</template>
		</cdx-radio>

		<cdx-radio
			v-model="expiryType"
			name="expiryType"
			input-value="datetime"
		>
			{{ $i18n( 'block-expiry-datetime' ).text() }}
			<template v-if="expiryType === 'datetime'" #custom-input>
				<cdx-field
					class="mw-block-expiry-field__datetime"
					:status="datetimeStatus"
					:messages="datetimeMessages"
				>
					<validating-text-input
						v-if="expiryType === 'datetime'"
						v-model="datetime"
						input-type="datetime-local"
						name="wpExpiry-other"
						:min="new Date().toISOString().slice( 0, 16 )"
						required
						@update:status="( status, message ) => {
							datetimeMessages = message;
							datetimeStatus = status;
						}"
					></validating-text-input>
				</cdx-field>
			</template>
		</cdx-radio>
	</cdx-field>
</template>

<script>
const { defineComponent, ref, computed, watch } = require( 'vue' );
const { CdxField, CdxRadio, CdxSelect } = require( '@wikimedia/codex' );
const ValidatingTextInput = require( './ValidatingTextInput.js' );

module.exports = exports = defineComponent( {
	name: 'ExpiryField',
	components: {
		CdxField,
		CdxRadio,
		CdxSelect,
		ValidatingTextInput
	},
	props: {
		// This is an object of the form { value: String, type: String }
		modelValue: {
			type: Object,
			required: true
		},
		/**
		 * Whether the form has been submitted yet. This is used to show
		 * validation messages only after the form has been submitted.
		 */
		formSubmitted: {
			type: Boolean,
			default: false
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
		const presetDurationStatus = ref( 'default' );
		const presetDurationMessages = ref( {} );
		const customDurationNumber = ref( 1 );
		const customDurationUnit = ref( 'hours' );
		const customDurationStatus = ref( 'default' );
		const customDurationMessages = ref( {} );
		const datetime = ref( '' );
		const datetimeStatus = ref( 'default' );
		const datetimeMessages = ref( {} );
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

		/**
		 * The preset duration field is a dropdown that requires custom validation.
		 * We simply need to assert something is selected, but only do so after form submission.
		 */
		watch( () => props.formSubmitted, () => {
			if ( expiryType.value === 'preset-duration' && !presetDuration.value ) {
				presetDurationStatus.value = 'error';
				presetDurationMessages.value = { error: mw.msg( 'ipb_expiry_invalid' ) };
			}
		} );

		return {
			presetDurationOptions,
			presetDurationStatus,
			presetDurationMessages,
			customDurationStatus,
			customDurationMessages,
			customDurationOptions,
			presetDuration,
			customDurationNumber,
			customDurationUnit,
			datetime,
			datetimeStatus,
			datetimeMessages,
			expiryType
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-expiry-field {
	.cdx-radio__custom-input .cdx-label {
		display: none;
	}

	.cdx-select-vue {
		margin-bottom: 0;
	}

	&__custom-duration .cdx-field__control {
		display: flex;
		gap: @spacing-50;

		&__number,
		&__unit {
			flex-grow: 1;
		}
	}
}
</style>
