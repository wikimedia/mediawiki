'use strict';

const { mount } = require( '@vue/test-utils' );

// Mock calls to mw.config.get.
// This correlates to the SpecialBlock::codexFormData property in PHP.
const mockConfig = {
	blockAllowsEmailBan: true,
	blockAllowsUTEdit: true,
	blockExpiryOptions: {
		infinite: 'infinite',
		'Other time:': 'other'
	},
	blockDefaultExpiry: 'infinite',
	blockAutoblockExpiry: '1 day',
	hideUser: true
};
mw.config.get.mockImplementation( ( key ) => mockConfig[ key ] );

const SpecialBlock = require( '../../../resources/src/mediawiki.special.block/SpecialBlock.vue' );

describe( 'SpecialBlock', () => {
	it( 'should show a submit button with the correct text', () => {
		const wrapper = mount( SpecialBlock );
		expect( wrapper.find( 'button.cdx-button' ).text() ).toBe( 'block-save' );
	} );
} );
