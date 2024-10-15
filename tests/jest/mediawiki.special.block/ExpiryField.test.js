'use strict';

const { mount } = require( '@vue/test-utils' );
const { createTestingPinia } = require( '@pinia/testing' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const ExpiryField = require( '../../../resources/src/mediawiki.special.block/components/ExpiryField.vue' );

const presetTestCases = [
	{
		title: 'no default, wpExpiry=indefinite [preset]',
		config: {
			isInfinity: true,
			blockExpiryPreset: 'indefinite'
		},
		expected: {
			expiryType: 'preset-duration',
			presetDuration: 'infinite'
		}
	},
	{
		title: 'default 9 weeks, wpExpiry=1 week [custom]',
		config: {
			blockExpiryDefault: '9 weeks',
			blockExpiryPreset: '1 week'
		},
		expected: {
			expiryType: 'custom-duration',
			customDurationNumber: 1,
			customDurationUnit: 'weeks'
		}
	},
	{
		title: 'default 31 hours, no wpExpiry [preset]',
		config: {
			blockExpiryDefault: '31 hours',
			blockExpiryPreset: null
		},
		expected: {
			expiryType: 'preset-duration',
			presetDuration: '31 hours'
		}
	},
	{
		title: 'no default, wpExpiry=2100-01-01T12:59 [datetime]',
		config: {
			blockExpiryPreset: '2100-01-01T12:59'
		},
		expected: {
			expiryType: 'datetime',
			datetime: '2100-01-01T12:59'
		}
	},
	{
		title: 'no default, wpExpiry=invalid [preset]',
		config: {
			blockExpiryPreset: 'invalid'
		},
		expected: {
			expiryType: 'preset-duration',
			presetDuration: null
		}
	}
];

describe( 'ExpiryField', () => {
	it( 'should show an error message if no expiry is provided after form submission', async () => {
		mockMwConfigGet();
		const wrapper = mount( ExpiryField, {
			propsData: { modelValue: {} },
			global: { plugins: [ createTestingPinia() ] }
		} );
		await wrapper.setProps( { formSubmitted: true } );
		expect( wrapper.find( '.cdx-message--error' ).text() )
			.toStrictEqual( 'ipb_expiry_invalid' );
	} );

	it.each( presetTestCases )(
		'should preselect from fields and set values ($title)', ( { config, expected } ) => {
			mockMwConfigGet( config );
			mw.util.isInfinity = jest.fn().mockReturnValue( !!config.isInfinity );
			const wrapper = mount( ExpiryField, {
				propsData: { modelValue: {} },
				global: { plugins: [ createTestingPinia() ] }
			} );
			Object.keys( expected ).forEach( ( key ) => {
				// Test against the app instance
				expect( wrapper.vm[ key ] ).toStrictEqual( expected[ key ] );

				// Test against the rendered DOM
				switch ( key ) {
					case 'expiryType':
						expect( wrapper.find( '[name=expiryType]:checked' ).element.value )
							.toStrictEqual( expected[ key ] );
						break;
					case 'presetDuration':
						// No input; Only testable on wrapper.vm (see above)
						break;
					case 'customDurationNumber':
						expect( wrapper.find( 'input[type=number]' ).element.value )
							.toStrictEqual( expected[ key ].toString() );
						break;
					case 'customDurationUnit':
						// No input.
						break;
					case 'datetime':
						expect( wrapper.find( 'input[type=datetime-local]' ).element.value )
							.toStrictEqual( expected[ key ] );
						break;
				}
			} );
		}
	);
} );
