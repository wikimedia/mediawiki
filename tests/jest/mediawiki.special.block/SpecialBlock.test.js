'use strict';

const { mount } = require( '@vue/test-utils' );
const { mockMwApiGet, mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const SpecialBlock = require( '../../../resources/src/mediawiki.special.block/SpecialBlock.vue' );

describe( 'SpecialBlock', () => {
	let wrapper;

	beforeEach( () => {
		mockMwConfigGet();
		mockMwApiGet();
		const form = document.createElement( 'form' );
		document.body.appendChild( form );
		wrapper = mount( SpecialBlock, { attachTo: form } );
	} );

	it( 'should show no banner and "Block this user" button on page load', () => {
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'ipbsubmit' );
	} );

	it( 'should show a banner and a submit button with text based on if user is already blocked', () => {
		expect( wrapper.find( '.mw-block-error' ).exists() ).toBeFalsy();
		mockMwConfigGet( {
			blockAlreadyBlocked: true,
			blockTargetUser: 'ExampleUser',
			blockPreErrors: [ 'ExampleUser is already blocked.' ]
		} );
		wrapper = mount( SpecialBlock );
		expect( wrapper.find( '.mw-block-error' ).exists() ).toBeTruthy();
		expect( wrapper.find( 'button.cdx-button' ).text() ).toStrictEqual( 'ipb-change-block' );
	} );

	it( 'should submit an API request to block the user', async () => {
		window.HTMLElement.prototype.scrollIntoView = jest.fn();
		// Mock postWithEditToken to return an actual Deferred object,
		// so that jQuery promise chain methods (e.g. .always()) will execute in the test.
		const jQuery = jest.requireActual( '../../../resources/lib/jquery/jquery.js' );
		mw.Api.prototype.postWithEditToken = jest.fn( () => jQuery.Deferred().resolve().promise() );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '2030-01-23T12:34:56' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const submitButton = wrapper.find( '.mw-block-submit' );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		await submitButton.trigger( 'click' );
		expect( spy ).toHaveBeenCalledWith( {
			action: 'block',
			user: 'ExampleUser',
			expiry: '2030-01-23T12:34:56',
			reason: 'This is a test',
			reblock: 0,
			autoblock: 1,
			errorlang: 'en',
			errorsuselocal: true,
			uselang: 'en',
			format: 'json'
		} );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
	} );
} );
