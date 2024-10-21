'use strict';

const { nextTick } = require( 'vue' );
const { getSpecialBlock } = require( './SpecialBlock.setup.js' );

describe( 'SpecialBlock', () => {
	let wrapper;

	/**
	 * Mock postWithEditToken to return an actual Deferred object,
	 * so that jQuery promise chain methods (e.g. always()) will execute in the test.
	 *
	 * @param {Object} [config] Configuration to override the defaults.
	 */
	const withSubmission = ( config ) => {
		const jQuery = jest.requireActual( '../../../resources/lib/jquery/jquery.js' );
		mw.Api.prototype.postWithEditToken = jest.fn( () => jQuery.Deferred().resolve().promise() );
		HTMLFormElement.prototype.checkValidity = jest.fn().mockReturnValue( true );
		wrapper = getSpecialBlock( config );
	};

	beforeEach( () => {
		wrapper = getSpecialBlock();
	} );

	it( 'should show no banner and "Block this user" button on page load', () => {
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'ipbsubmit' );
	} );

	it( 'should show a banner and a submit button with text based on if user is already blocked', () => {
		expect( wrapper.find( '.mw-block-error' ).exists() ).toBeFalsy();
		wrapper = getSpecialBlock( {
			blockAlreadyBlocked: true,
			blockTargetUser: 'ExampleUser',
			blockPreErrors: [ 'ExampleUser is already blocked.' ]
		} );
		expect( wrapper.find( '.mw-block-error' ).exists() ).toBeTruthy();
		expect( wrapper.find( 'button.cdx-button' ).text() ).toStrictEqual( 'ipb-change-block' );
	} );

	it( 'should submit an API request to block the user', async () => {
		withSubmission();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '2999-01-23T12:34:56' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const submitButton = wrapper.find( '.mw-block-submit' );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		await submitButton.trigger( 'click' );
		expect( spy ).toHaveBeenCalledWith( {
			action: 'block',
			user: 'ExampleUser',
			expiry: '2999-01-23T12:34:56',
			reason: 'This is a test',
			reblock: 0,
			autoblock: 1,
			allowusertalk: 1,
			errorlang: 'en',
			errorsuselocal: true,
			uselang: 'en',
			format: 'json'
		} );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
	} );

	it( 'should add an error state to invalid fields on submission', async () => {
		withSubmission();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		// Add invalid date
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '0000-01-23T12:34:56' );
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await nextTick();
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-expiry-field__datetime .cdx-text-input' ).attributes().class )
			.toContain( 'cdx-text-input--status-error' );
		expect( wrapper.find( '.mw-block-expiry-field__datetime .cdx-message--error' ).exists() )
			.toBeTruthy();
	} );

	it( 'should require confirmation for the hide-user option', async () => {
		withSubmission( { blockHideUser: true } );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		// Assert 'hide username' is not yet clickable.
		expect( wrapper.find( '.mw-block-hideuser input' ).attributes().disabled ).toStrictEqual( '' );
		// Set the expiry to 'infinite' to enable the hide-user option.
		wrapper.vm.store.expiry = 'infinite';
		mw.util.isInfinity = jest.fn().mockReturnValue( true );
		await nextTick();
		// Assert confirmation checkbox is not shown.
		expect( wrapper.find( '.mw-block-confirm' ).exists() ).toBeFalsy();
		// Assert 'hide username' is now clickable.
		expect( wrapper.find( '.mw-block-hideuser input' ).attributes().disabled ).toBeUndefined();
		await wrapper.find( '.mw-block-hideuser input' ).trigger( 'click' );
		// Assert confirmation checkbox is now shown.
		expect( wrapper.find( '.mw-block-confirm' ).exists() ).toBeTruthy();
		// Once clicked, assert the submit button is disabled.
		expect( wrapper.find( '.mw-block-submit' ).attributes().disabled ).toStrictEqual( '' );
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		// Assert submission is not possible.
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		await wrapper.find( '.mw-block-confirm input' ).trigger( 'click' );
		expect( wrapper.find( '.mw-block-submit' ).attributes().disabled ).toBeUndefined();
	} );

	it( 'should require confirmation for self-blocking', async () => {
		withSubmission( { wgUserName: 'ExampleUser' } );
		expect( wrapper.find( '.mw-block-error' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-submit' ).attributes().disabled ).toBeUndefined();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		expect( wrapper.find( '.mw-block-submit' ).attributes().disabled ).toStrictEqual( '' );
		expect( wrapper.find( '.mw-block-error' ).text() ).toStrictEqual( 'ipb-blockingself' );
	} );
} );
