'use strict';

const { nextTick, VueWrapper } = require( 'vue' );
const { flushPromises } = require( '@vue/test-utils' );
const { getSpecialBlock } = require( './SpecialBlock.setup.js' );
const useBlockStore = require( '../../../resources/src/mediawiki.special.block/stores/block.js' );

/**
 * Mock postWithEditToken to return an actual Deferred object,
 * so that jQuery promise chain methods (e.g. always()) will execute in the test.
 *
 * @param {Object} [config] Configuration to override the defaults.
 * @param {Object} [postResponse] Response to return from the postWithEditToken call.
 * @return {VueWrapper}
 */
const withSubmission = ( config, postResponse ) => {
	const jQuery = jest.requireActual( '../../../resources/lib/jquery/jquery.js' );
	mw.Api.prototype.postWithEditToken = jest.fn( () => jQuery.Deferred().resolve( postResponse ).promise() );
	HTMLFormElement.prototype.checkValidity = jest.fn().mockReturnValue( true );
	return getSpecialBlock( config );
};

describe( 'SpecialBlock', () => {
	let wrapper;

	it( 'should show no banner and no "Add block" button on page load', async () => {
		wrapper = getSpecialBlock();
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-submit' ).exists() ).toBeFalsy();
	} );

	it( 'should show no banner and block form after selecting a valid target with no active blocks', async () => {
		wrapper = getSpecialBlock( { blockId: null } );
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'block-submit' );
	} );

	it( 'should show a banner and "Add block" button based on if user is already blocked', () => {
		expect( wrapper.find( '.mw-block-messages .cdx-message--error' ).exists() ).toBeFalsy();
		wrapper = getSpecialBlock( {
			blockAlreadyBlocked: true,
			blockTargetUser: 'ExampleUser',
			blockTargetExists: true,
			blockPreErrors: [ 'ExampleUser is already blocked.' ]
		} );
		// Server-generated message, hence why it's in English.
		expect( wrapper.find( '.mw-block-messages .cdx-message--error' ).text() )
			.toStrictEqual( 'ExampleUser is already blocked.' );
		expect( wrapper.find( '.mw-block__create-button' ).exists() ).toBeTruthy();
	} );

	it( 'should submit an API request to add a new block for a user (Multiblocks OFF)', async () => {
		wrapper = withSubmission( { blockId: null, blockEnableMultiblocks: false }, { block: { user: 'ExampleUser' } } );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await flushPromises();
		expect( wrapper.find( '.mw-block__block-form h2' ).text() ).toStrictEqual( 'block-create' );
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '2999-01-23T12:34' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const submitButton = wrapper.find( '.mw-block-submit' );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		await submitButton.trigger( 'click' );
		expect( spy ).toHaveBeenCalledWith( {
			action: 'block',
			user: 'ExampleUser',
			expiry: '2999-01-23T12:34:00Z',
			reason: 'This is a test',
			nocreate: 1,
			allowusertalk: 1,
			autoblock: 1,
			errorformat: 'html',
			errorlang: 'en',
			errorsuselocal: true,
			uselang: 'en',
			format: 'json',
			formatversion: 2
		} );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-success .cdx-message__content' ).text() ).toContain( 'block-added-message' );
	} );

	it( 'should submit an API request to block the user', async () => {
		wrapper = withSubmission( undefined, { block: { user: 'ExampleUser' } } );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await flushPromises();
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '2999-01-23T12:34' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const submitButton = wrapper.find( '.mw-block-submit' );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		await submitButton.trigger( 'click' );
		expect( spy ).toHaveBeenCalledWith( {
			action: 'block',
			user: 'ExampleUser',
			newblock: 1,
			expiry: '2999-01-23T12:34:00Z',
			reason: 'This is a test',
			nocreate: 1,
			allowusertalk: 1,
			autoblock: 1,
			errorformat: 'html',
			errorlang: 'en',
			errorsuselocal: true,
			uselang: 'en',
			format: 'json',
			formatversion: 2
		} );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
	} );

	it( 'should add an error state to invalid fields on submission', async () => {
		wrapper = withSubmission();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await flushPromises();
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
		wrapper = getSpecialBlock( { blockHideUser: true } );
		const store = useBlockStore();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		// Assert 'hide username' is not yet visible.
		expect( wrapper.find( '.mw-block-hideuser input' ).exists() ).toBeFalsy();
		expect( store.hideUserVisible ).toBeFalsy();
		expect( store.confirmationMessage ).toStrictEqual( '' );
		// Set the expiry to 'infinite' to enable the hide-user option.
		store.expiry = 'infinite';
		mw.util.isInfinity = jest.fn().mockReturnValue( true );
		await nextTick();
		// Assert 'hide username' is now clickable.
		expect( wrapper.find( '.mw-block-hideuser input' ).attributes().disabled ).toBeUndefined();
		expect( store.hideUserVisible ).toBeTruthy();
		expect( store.hideUser ).toBeFalsy();
		await wrapper.find( '.mw-block-hideuser input' ).trigger( 'click' );
		expect( store.hideUser ).toBeTruthy();
		// Assert confirmation is required.
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-confirmhideuser' );
		expect( store.confirmationNeeded ).toBeTruthy();
		expect( store.formSubmitted ).toBeFalsy();
		expect( wrapper.vm.confirmationOpen ).toBeFalsy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeFalsy();
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		expect( store.formSubmitted ).toBeTruthy();
		expect( wrapper.vm.confirmationOpen ).toBeTruthy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeTruthy();
		mw.util.isInfinity = jest.fn().mockReturnValue( false );
	} );

	it( 'should require confirmation for self-blocking', async () => {
		wrapper = getSpecialBlock( {
			wgUserName: 'ExampleUser'
		} );
		const store = useBlockStore();
		expect( wrapper.find( '.cdx-message--error' ).exists() ).toBeFalsy();
		expect( store.confirmationNeeded ).toBeFalsy();
		expect( store.confirmationMessage ).toStrictEqual( '' );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		store.expiry = '3 days';
		expect( store.confirmationNeeded ).toBeTruthy();
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-blockingself' );
		expect( store.formSubmitted ).toBeFalsy();
		expect( wrapper.vm.confirmationOpen ).toBeFalsy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeFalsy();
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await nextTick();
		expect( store.formSubmitted ).toBeTruthy();
		expect( wrapper.vm.confirmationOpen ).toBeTruthy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeTruthy();
	} );

	it( 'should reset form refs after blocking', async () => {
		wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true },
			{ block: { user: 'ActiveBlockedUser' } }
		);
		const store = useBlockStore();
		await flushPromises();
		expect( wrapper.find( '[data-test=edit-block-button]' ).exists() ).toBeTruthy();
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		expect( store.reason ).toStrictEqual( 'Spamming talk page' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block__block-form h2' ).text() ).toStrictEqual( 'block-update' );
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'block-submit' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		expect( store.reason ).toStrictEqual( 'This is a test' );
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await flushPromises();
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-success .cdx-message__content' ).text() ).toContain( 'block-updated-message' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeFalsy();
		expect( store.reason ).toStrictEqual( '' );
		expect( store.blockId ).toStrictEqual( null );
	} );

	it( 'should reset the form after changing the target while editing a block (T389056)', async () => {
		wrapper = getSpecialBlock( {
			blockTargetUser: 'ActiveBlockedUser',
			blockTargetExists: true,
			blockAlreadyBlocked: true
		} );
		const store = useBlockStore();
		await flushPromises();
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		expect( store.blockId ).toStrictEqual( 1110 );
		expect( store.formVisible ).toBeTruthy();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		expect( store.targetUser ).toStrictEqual( 'ExampleUser' );
		expect( store.blockId ).toBeNull();
	} );

	it( 'should use pre-set values when creating a new block', async () => {
		wrapper = getSpecialBlock( {
			blockTargetUser: 'ExampleUser',
			blockTargetExists: true,
			blockTypePreset: 'partial',
			blockPageRestrictions: 'Foo\nBar',
			blockExpiryPreset: '99 hours'
		} );
		const store = useBlockStore();
		await flushPromises();
		expect( store.type ).toStrictEqual( 'partial' );
		expect( store.pages ).toStrictEqual( [ 'Foo', 'Bar' ] );
		expect( store.expiry ).toStrictEqual( '99 hours' );
		expect( wrapper.find( '[name=expiryType]:checked' ).element.value ).toStrictEqual( 'custom-duration' );
		expect( wrapper.find( 'input[type=number]' ).element.value ).toStrictEqual( '99' );
		expect( wrapper.find( '.mw-block-pages' ).text() ).toStrictEqual( 'FooBar' );
	} );

	it( 'should show an "Add block" button in the page', async () => {
		wrapper = getSpecialBlock( { blockTargetUser: 'ExampleUser', blockTargetExists: true } );
		expect( wrapper.find( '.mw-block__create-button' ).exists() ).toBeTruthy();
	} );

	it( 'should not show an "Add block" button (multiblocks OFF)', async () => {
		wrapper = withSubmission( {
			blockTargetUser: 'ActiveBlockedUser',
			blockTargetExists: true,
			blockEnableMultiblocks: false,
			blockAlreadyBlocked: true
		}, { block: { user: 'ActiveBlockedUser' } } );
		const store = useBlockStore();
		await flushPromises();
		expect( wrapper.find( '.mw-block__create-button' ).exists() ).toBeFalsy();
		// Edit a block.
		expect( wrapper.find( '[data-test=edit-block-button]' ).exists() ).toBeTruthy();
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		// Cancel the edit.
		await wrapper.find( '[data-test="cancel-edit-button"]' ).trigger( 'click' );
		// "Add block" button still shouldn't be shown.
		expect( wrapper.find( '.mw-block__create-button' ).exists() ).toBeFalsy();
		expect( store.alreadyBlocked ).toBeTruthy();
	} );

	it( 'should reset the form to the initial state for subsequent blocks (T384822)', async () => {
		wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true },
			{ block: { user: 'ActiveBlockedUser' } }
		);
		const store = useBlockStore();
		await flushPromises();
		// Edit a block.
		expect( wrapper.find( '[data-test=edit-block-button]' ).exists() ).toBeTruthy();
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block__block-form h2' ).text() ).toStrictEqual( 'block-update' );
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'block-submit' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		expect( store.reason ).toStrictEqual( 'This is a test' );
		// Submit.
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await flushPromises();
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeFalsy();
		expect( store.reason ).toStrictEqual( '' );
		// Add a new block.
		await wrapper.find( '.mw-block__create-button' ).trigger( 'click' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeTruthy();
		expect( store.blockId ).toBeNull();
		expect( store.reason ).toStrictEqual( '' );
		expect( wrapper.find( '[name=wpReason-other]' ).element.value ).toStrictEqual( '' );
	} );

	it( 'should reset the form to the initial state for new blocks when a block is edited, canceled, and then create block', async () => {
		wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true },
			{ block: { user: 'ActiveBlockedUser' } }
		);
		const store = useBlockStore();
		await flushPromises();
		// Edit a block.
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		expect( store.reason ).toStrictEqual( 'This is a test' );
		// Cancel the edit.
		await wrapper.find( '[data-test="cancel-edit-button"]' ).trigger( 'click' );
		// Create a new block.
		await wrapper.find( '.mw-block__create-button' ).trigger( 'click' );
		expect( store.blockId ).toBeNull();
		expect( store.reason ).toStrictEqual( '' );
		expect( wrapper.find( '[name=wpReason-other]' ).element.value ).toStrictEqual( '' );
	} );

	it( 'should show no block logs and no "Add block" button when the page is loaded with an invalid target', async () => {
		wrapper = getSpecialBlock( {
			blockTargetUser: 'NonexistentUser',
			blockTargetExists: false,
			blockId: null
		} );

		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-log__type-recent' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block__create-button' ).exists() ).toBeFalsy();
	} );

	it( 'should show no block logs and no "Add block" button when the target is changed from a valid to an invalid target', async () => {
		wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true },
			{ block: { user: 'ActiveBlockedUser' } }
		);

		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-log__type-recent' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block__create-button' ).exists() ).toBeTruthy();

		wrapper.find( '#mw-bi-target' ).setValue( 'NonexistentUser' );
		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-log__type-recent' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block__create-button' ).exists() ).toBeFalsy();
	} );

	it( 'should emit "submit" event when Enter key is pressed', async () => {
		wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true },
			{ block: { user: 'ActiveBlockedUser' } }
		);
		await flushPromises();
		// Create a new block.
		await wrapper.find( '.mw-block__create-button' ).trigger( 'click' );
		// Set expiry date
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '2999-01-23T12:34' );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		// Simulate a press enter
		await wrapper.find( '[name="wpReason-other"]' ).trigger( 'keypress', { key: 'Enter' } );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
	} );

	it( 'should show "Active blocks" and "Active range blocks" on the given IP', async () => {
		mw.util.isIPAddress = jest.fn().mockReturnValue( true );
		wrapper = getSpecialBlock( { blockTargetUser: '1.2.3.20', blockTargetExists: true } );
		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeTruthy();
		expect( wrapper.findAll( '.mw-block-log__type-active tbody tr' ) ).toHaveLength( 1 );
		expect( wrapper.find( '.mw-block-log__type-active-ranges' ).exists() ).toBeTruthy();
		expect( wrapper.findAll( '.mw-block-log__type-active-ranges tbody tr' ) ).toHaveLength( 2 );
	} );

	it( 'should show an empty "Active range blocks" for an IP with no range blocks', async () => {
		mw.util.isIPAddress = jest.fn().mockReturnValue( true );
		wrapper = getSpecialBlock( { blockTargetUser: '192.168.0.1', blockTargetExists: true } );
		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-log__type-active tbody' ).text() )
			.toStrictEqual( 'block-user-no-active-blocks' );
		expect( wrapper.find( '.mw-block-log__type-active-ranges' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-log__type-active-ranges tbody' ).text() )
			.toStrictEqual( 'block-user-no-active-range-blocks' );
	} );

	afterEach( () => {
		wrapper.unmount();
	} );
} );
