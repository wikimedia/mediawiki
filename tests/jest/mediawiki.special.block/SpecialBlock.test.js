'use strict';

const { mount } = require( '@vue/test-utils' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const SpecialBlock = require( '../../../resources/src/mediawiki.special.block/SpecialBlock.vue' );

describe( 'SpecialBlock', () => {
	it( 'should show a banner and a submit button with text based on if user is already blocked', () => {
		// Mock the API response for the "TargetBlockLog"
		mw.Api.prototype.get = jest.fn().mockResolvedValue( {
			query: {
				logevents: [
					{
						logid: 980,
						title: 'User:ExampleUser',
						params: {
							duration: '1 year',
							flags: [
								'noautoblock'
							],
							sitewide: true,
							expiry: '2029-09-17T14:30:51Z'
						},
						type: 'block',
						user: 'Admin',
						timestamp: '2024-09-17T14:30:51Z',
						comment: 'A reason'
					}
				]
			}
		} );
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
