'use strict';

const { mount } = require( '@vue/test-utils' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const ExpiryField = require( '../../../resources/src/mediawiki.special.block/components/ExpiryField.vue' );

describe( 'ExpiryField', () => {
	it( 'should show an error message if no expiry is provided after form submission', async () => {
		mockMwConfigGet();
		const wrapper = mount( ExpiryField, {
			propsData: { modelValue: {} }
		} );
		await wrapper.setProps( { formSubmitted: true } );
		expect( wrapper.find( '.cdx-message--error' ).text() ).toStrictEqual( 'ipb_expiry_invalid' );
	} );
} );
