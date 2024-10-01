'use strict';

const { mount } = require( '@vue/test-utils' );
const { mockMwApiGet, mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const SpecialBlock = require( '../../../resources/src/mediawiki.special.block/SpecialBlock.vue' );

describe( 'SpecialBlock', () => {
	let wrapper;

	beforeEach( () => {
		mockMwConfigGet();
		mockMwApiGet();
		wrapper = mount( SpecialBlock );
	} );

	it( 'should show no banner and "Block this user" button on page load', () => {
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
		expect( wrapper.find( 'button.cdx-button' ).text() ).toStrictEqual( 'ipbsubmit' );
	} );

	it( 'should show a banner and a submit button with text based on if user is already blocked', () => {
		mockMwConfigGet( {
			blockAlreadyBlocked: true,
			blockTargetUser: 'ExampleUser'
		} );
		wrapper = mount( SpecialBlock );
		expect( wrapper.find( '.cdx-message__content' ).text() )
			.toStrictEqual( 'ipb-needreblock:[ExampleUser]' );
		expect( wrapper.find( 'button.cdx-button' ).text() ).toStrictEqual( 'ipb-change-block' );
	} );

	it( 'should submit an API request to block the user', async () => {
		mw.Api.prototype.postWithEditToken = jest.fn().mockImplementation( () => ( {
			done: jest.fn().mockImplementation( () => ( { fail: jest.fn() } ) )
		} ) );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '2030-01-23T12:34:56' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		await wrapper.find( 'button.cdx-button' ).trigger( 'click' );
		expect( spy ).toHaveBeenCalledWith( {
			action: 'block',
			user: 'ExampleUser',
			expiry: '2030-01-23T12:34:56',
			reason: 'This is a test',
			autoblock: 1,
			format: 'json'
		} );
	} );
} );
