'use strict';

const { mount } = require( '@vue/test-utils' );

// Mock calls to mw.config.get.
// This correlates to the SpecialBlock::codexFormData property in PHP.
const mockConfig = {
	blockAllowsEmailBan: true,
	blockAllowsUTEdit: true,
	blockAutoblockExpiry: '1 day',
	blockDefaultExpiry: 'infinite',
	blockHideUser: true,
	blockExpiryOptions: {
		infinite: 'infinite',
		'Other time:': 'other'
	},
	blockReasonOptions: [
		{ label: 'block-reason-1', value: 'block-reason-1' },
		{ label: 'block-reason-2', value: 'block-reason-2' }
	]
};
mw.config.get.mockImplementation( ( key ) => mockConfig[ key ] );

const SpecialBlock = require( '../../../resources/src/mediawiki.special.block/SpecialBlock.vue' );

describe( 'SpecialBlock', () => {
	it( 'should show a submit button with the correct text', () => {
		const wrapper = mount( SpecialBlock );
		expect( wrapper.find( 'button.cdx-button' ).text() ).toBe( 'block-save' );
	} );
} );
