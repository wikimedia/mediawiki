'use strict';

const { mount } = require( '@vue/test-utils' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const SpecialBlock = require( '../../../resources/src/mediawiki.special.block/SpecialBlock.vue' );

describe( 'SpecialBlock', () => {
	it( 'should show a banner and a submit button with text based on if user is already blocked', () => {
		mockMwConfigGet();
		let wrapper = mount( SpecialBlock );
		expect( wrapper.find( 'button.cdx-button' ).text() ).toStrictEqual( 'ipbsubmit' );
		mockMwConfigGet( {
			blockAlreadyBlocked: true,
			blockTargetUser: 'ExampleUser'
		} );
		wrapper = mount( SpecialBlock );
		expect( wrapper.find( '.cdx-message__content' ).text() )
			.toStrictEqual( 'ipb-needreblock:[ExampleUser]' );
		expect( wrapper.find( 'button.cdx-button' ).text() ).toStrictEqual( 'ipb-change-block' );
	} );
} );
