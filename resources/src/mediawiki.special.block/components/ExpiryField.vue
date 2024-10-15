<template>
	<cdx-field
		class="mw-block-expiry-field"
		:is-fieldset="true"
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
const { storeToRefs } = require( 'pinia' );
const ValidatingTextInput = require( './ValidatingTextInput.js' );
const useBlockStore = require( '../stores/block.js' );

module.exports = exports = defineComponent( {
	name: 'ExpiryField',
	components: {
		CdxField,
		CdxRadio,
		CdxSelect,
		ValidatingTextInput
	},
	props: {
		/**
		 * Whether the form has been submitted yet. This is used to show
		 * validation messages only after the form has been submitted.
		 */
		formSubmitted: {
			type: Boolean,
			default: false
		}
	},
	setup( props ) {
		const store = useBlockStore();
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
			if ( expiryType.value === 'preset-duration' ) {
				return presetDuration.value;
			} else if ( expiryType.value === 'custom-duration' ) {
				return `${ Number( customDurationNumber.value ) } ${ customDurationUnit.value }`;
			} else {
				return datetime.value;
			}
		} );

		watch( computedModelValue, ( newValue ) => {
			const { expiry } = storeToRefs( store );
			expiry.value = newValue;
		} );

		/**
		 * Set the form fields according to the given expiry.
		 *
		 * @param {string} given
		 * @return {boolean} false if the given expiry is invalid or of an unsupported format.
		 */
		function setDurationFromGiven( given ) {
			const optionsContainsValue = ( opts, v ) => opts.some( ( option ) => option.value === v );
			if ( mw.util.isInfinity( given ) ) {
				expiryType.value = 'preset-duration';
				// FIXME: Assumes that the "infinite" option exists.
				// (It has to be for this form as there's no other way to specify infinite)
				presetDuration.value = 'infinite';
			} else if ( optionsContainsValue( presetDurationOptions, given ) ) {
				expiryType.value = 'preset-duration';
				presetDuration.value = given;
			} else if ( /^\d+ [a-z]+$/.test( given ) ) {
				const [ number, unit ] = given.split( ' ' );
				// Normalize the unit to plural form, as used by customDurationOptions.
				const unitPlural = unit.replace( /s?$/, 's' );
				if ( optionsContainsValue( customDurationOptions, unitPlural ) ) {
					expiryType.value = 'custom-duration';
					customDurationNumber.value = Number( number );
					customDurationUnit.value = unitPlural;
				} else {
					return false;
				}
			} else if ( /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test( given ) ) {
				expiryType.value = 'datetime';
				datetime.value = given;
			} else {
				// Unsupported format.
				return false;
			}

			return true;
		}

		// Set the form fields according to the preselected expiry (?wpExpiry=).
		if ( !setDurationFromGiven( mw.config.get( 'blockExpiryPreset' ) ) ) {
			// If no expiry is preselected, attempt to go by [[MediaWiki:Ipb-default-expiry]].
			setDurationFromGiven( mw.config.get( 'blockExpiryDefault' ) );
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
