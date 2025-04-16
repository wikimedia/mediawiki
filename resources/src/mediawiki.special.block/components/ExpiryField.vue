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
					<cdx-select
						v-model:selected="datetimeZone"
						:menu-items="datetimeZoneOptions"
					></cdx-select>
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
const DateFormatter = require( 'mediawiki.DateFormatter' );

module.exports = exports = defineComponent( {
	name: 'ExpiryField',
	components: {
		CdxField,
		CdxRadio,
		CdxSelect,
		ValidatingTextInput
	},
	setup() {
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

		const now = new Date();
		const dateFormatters = {
			user: DateFormatter.forUser(),
			site: DateFormatter.forSiteZone(),
			utc: DateFormatter.forUtc()
		};
		const zoneLabels = {};
		for ( const f in dateFormatters ) {
			zoneLabels[ f ] = dateFormatters[ f ].getShortZoneName( now );
		}
		const datetimeZoneOptions = [ { value: 'user', label: zoneLabels.user } ];
		if ( zoneLabels.site !== zoneLabels.user && zoneLabels.site !== zoneLabels.utc ) {
			datetimeZoneOptions.push( { value: 'site', label: zoneLabels.site } );
		}
		if ( zoneLabels.utc !== zoneLabels.user ) {
			datetimeZoneOptions.push( { value: 'utc', label: zoneLabels.utc } );
		}

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
		const datetimeZone = ref( 'user' );
		const expiryType = ref( 'preset-duration' );

		/*
		 * Convert a local date to a Date object, implicitly in the UTC time zone.
		 * Note that this may not have a single solution! At the end of daylight
		 * savings time, an hour is repeated in the local time zone. Hopefully
		 * we will at least find one of the two possible solutions.
		 */
		function parseLocalDate( dateString, zone ) {
			// Interpret the date as UTC, giving an answer which is off by up to 14 hours
			let date = new Date( dateString + 'Z' );
			const timestamp = date.valueOf();
			const df = dateFormatters[ zone ];
			const offset1 = df.getZoneOffsetMinutes( date );
			if ( offset1 ) {
				// Subtract the zone offset, giving a time which is usually right,
				// except if the reference date was the day of DST switch-over.
				date = new Date( timestamp - offset1 * 60000 );
				// Find the offset for this new pretty close reference date
				const offset2 = df.getZoneOffsetMinutes( date );
				if ( offset1 !== offset2 ) {
					// Subtract the offset again
					date = new Date( timestamp - offset2 * 60000 );
				}
			}
			return date;
		}

		const computedModelValue = computed( () => {
			if ( expiryType.value === 'preset-duration' ) {
				return presetDuration.value;
			} else if ( expiryType.value === 'custom-duration' ) {
				return `${ Number( customDurationNumber.value ) } ${ customDurationUnit.value }`;
			} else if ( datetime.value ) {
				return dateFormatters.utc.formatIso(
					parseLocalDate( datetime.value, datetimeZone.value )
				);
			} else {
				return '';
			}
		} );

		/**
		 * Set the form fields according to the given expiry.
		 *
		 * @param {string} given
		 */
		function setDurationFromGiven( given ) {
			const optionsContainsValue = ( opts, v ) => opts.some( ( option ) => option.value === v );
			if ( mw.util.isInfinity( given ) ) {
				expiryType.value = 'preset-duration';
				// Set the "infinite" option that exists.
				presetDuration.value = presetDurationOptions.find(
					( option ) => mw.util.isInfinity( option.value )
				).value;
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
				}
			} else if ( /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/.test( given ) ) {
				expiryType.value = 'datetime';
				// Truncate longer datetime strings to be compatible with input type=datetime-local.
				// This is also done in SpecialBlock.php
				datetimeZone.value = 'user';
				datetime.value = dateFormatters.user.formatForDateTimeInput( new Date( given ) );
			} else {
				// Unsupported format; Reset to defaults.
				expiryType.value = 'preset-duration';
				customDurationNumber.value = 1;
				customDurationUnit.value = 'hours';
				datetime.value = '';
			}
		}

		watch( datetimeZone, ( newValue, oldValue ) => {
			datetime.value = dateFormatters[ newValue ].formatForDateTimeInput(
				parseLocalDate( datetime.value, oldValue ) );
		} );

		const { expiry } = storeToRefs( store );

		// Update the store's expiry value when the computed value changes.
		watch( computedModelValue, ( newValue ) => {
			if ( newValue !== expiry.value ) {
				// Remove browser-specific milliseconds from datetime for consistency.
				if ( expiryType.value === 'datetime' ) {
					newValue = newValue.replace( /\.000$/, '' );
				}
				expiry.value = newValue;
			}
		} );

		// Update the form fields when the store's expiry value changes.
		watch( expiry, ( newValue ) => {
			if ( newValue !== computedModelValue.value ) {
				setDurationFromGiven( newValue );
			}
		}, { immediate: true } );

		/**
		 * The preset duration field is a dropdown that requires custom validation.
		 * We simply need to assert something is selected, but only do so after form submission.
		 */
		watch( () => store.formSubmitted, ( submitted ) => {
			if ( submitted && expiryType.value === 'preset-duration' && !presetDuration.value ) {
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
			datetimeZone,
			datetimeZoneOptions,
			expiryType
		};
	}
} );
</script>

<style lang="less">
/* stylelint-disable plugin/no-unsupported-browser-features */
@import 'mediawiki.skin.variables.less';

.mw-block-expiry-field {
	.cdx-radio__custom-input .cdx-label {
		display: none;
	}

	.cdx-select-vue {
		margin-bottom: 0;
	}

	&__custom-duration .cdx-field__control,
	&__datetime .cdx-field__control {
		display: flex;
		gap: @spacing-50;

		&__number,
		&__unit {
			flex-grow: 1;
		}
	}
}
</style>
