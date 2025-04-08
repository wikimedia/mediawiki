'use strict';

const { nextTick } = require( 'vue' );
const { setActivePinia, createPinia } = require( 'pinia' );
const { flushPromises } = require( '@vue/test-utils' );
const { mockMwConfigGet } = require( '../SpecialBlock.setup.js' );
const useBlockStore = require( '../../../../resources/src/mediawiki.special.block/stores/block.js' );
const jQuery = jest.requireActual( '../../../../resources/lib/jquery/jquery.js' );

beforeEach( () => {
	// creates a fresh pinia and makes it active
	// so it's automatically picked up by any useStore() call
	// without having to pass it to it: `useStore(pinia)`
	setActivePinia( createPinia() );
} );

describe( 'Block store', () => {
	it( 'should require confirmation if the target user is the current user', async () => {
		mockMwConfigGet( { wgUserName: 'ExampleUser' } );
		const store = useBlockStore();
		store.targetUser = 'ExampleUserOther';
		// Trigger the watchers.
		await nextTick();
		expect( store.confirmationMessage ).toStrictEqual( '' );
		store.targetUser = 'ExampleUser';
		await nextTick();
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-blockingself' );
	} );

	it( 'should require confirmation for hide user', async () => {
		mw.util.isInfinity.mockReturnValue( true );
		const store = useBlockStore();
		expect( store.confirmationMessage ).toStrictEqual( '' );
		store.type = 'sitewide';
		store.hideUser = true;
		await nextTick();
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-confirmhideuser' );
	} );

	it( 'should set hideUserVisible only for blocks that are sitewide and with an infinite expiry', () => {
		mockMwConfigGet( { blockHideUser: false } );
		const store = useBlockStore();
		// Don't have the right.
		expect( store.hideUserVisible ).toStrictEqual( false );
		mockMwConfigGet( { blockHideUser: true } );
		// Partial infinite.
		store.type = 'partial';
		store.expiry = 'infinite';
		mw.util.isInfinity.mockReturnValue( true );
		expect( store.hideUserVisible ).toStrictEqual( false );
		// Sitewide time-limited.
		store.type = 'sitewide';
		store.expiry = '3 hours';
		mw.util.isInfinity.mockReturnValue( false );
		expect( store.hideUserVisible ).toStrictEqual( false );
		// Sitewide infinite.
		store.type = 'sitewide';
		store.expiry = 'infinite';
		mw.util.isInfinity.mockReturnValue( true );
		expect( store.hideUserVisible ).toStrictEqual( true );
	} );

	it( 'resetForm', () => {
		const store = useBlockStore();
		store.targetUser = 'ExampleUser';
		store.targetExists = true;
		store.type = 'partial';
		store.expiry = 'infinite';
		store.reason = 'This is a test';
		store.resetForm();
		expect( store.targetUser ).toStrictEqual( 'ExampleUser' );
		expect( store.targetExists ).toStrictEqual( true );
		expect( store.type ).toStrictEqual( 'sitewide' );
		expect( store.expiry ).toStrictEqual( '' );
		expect( store.reason ).toStrictEqual( '' );
		store.resetForm( true );
		expect( store.targetUser ).toStrictEqual( '' );
		expect( store.targetExists ).toStrictEqual( false );
	} );

	it( 'should reset form refs when the target user changes (T389056)', async () => {
		const store = useBlockStore();
		store.targetUser = 'ExampleUser';
		store.blockId = 1234;
		store.formVisible = true;
		// Change the target user.
		store.targetUser = 'ExampleUserOther';
		await nextTick();
		// The form should be reset.
		expect( store.targetUser ).toStrictEqual( 'ExampleUserOther' );
		expect( store.blockId ).toBeNull();
		expect( store.formVisible ).toBeFalsy();
	} );
} );

describe( 'Block API', () => {
	beforeEach( () => {
		mw.Api.prototype.postWithEditToken.mockReturnValue( jQuery.Deferred().resolve().promise() );
	} );

	it( 'should only pass the reblock param to the API if there was an "already blocked" error (Multiblocks OFF)', () => {
		mockMwConfigGet( { blockEnableMultiblocks: false, blockAlreadyBlocked: false } );
		const store = useBlockStore();
		store.doBlock();
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const expected = {
			action: 'block',
			allowusertalk: 1,
			nocreate: 1,
			autoblock: 1,
			errorformat: 'html',
			errorlang: 'en',
			errorsuselocal: true,
			expiry: '',
			format: 'json',
			formatversion: 2,
			reason: '',
			uselang: 'en',
			user: ''
		};
		expect( spy ).toHaveBeenCalledWith( expected );
		store.alreadyBlocked = true;
		store.doBlock();
		expected.reblock = 1;
		expect( spy ).toHaveBeenCalledWith( expected );
	} );

	it( 'should pass the newblock param and not the reblock param (Multiblocks ON)', () => {
		mockMwConfigGet( { blockAlreadyBlocked: false, blockEnableMultiblocks: true } );
		const store = useBlockStore();
		store.doBlock();
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const expected = {
			action: 'block',
			allowusertalk: 1,
			nocreate: 1,
			autoblock: 1,
			errorformat: 'html',
			errorlang: 'en',
			errorsuselocal: true,
			expiry: '',
			format: 'json',
			formatversion: 2,
			newblock: 1,
			reason: '',
			uselang: 'en',
			user: ''
		};
		expect( spy ).toHaveBeenCalledWith( expected );
		store.alreadyBlocked = true;
		store.doBlock();
		expect( spy ).toHaveBeenCalledWith( expected );
	} );

	it( 'should make one API request for the block log and active blocks', async () => {
		mw.Api.prototype.get = jest.fn().mockReturnValue( jQuery.Deferred().resolve( { query: { blocks: [] } } ).promise() );
		const spy = jest.spyOn( mw.Api.prototype, 'get' );
		const store = useBlockStore();
		store.getBlockLogData( 'recent' );
		store.getBlockLogData( 'active' );
		expect( store.formDisabled ).toBeTruthy();
		expect( spy ).toHaveBeenCalledTimes( 1 );
		// Flushes the promise created in getBlockLogData()
		await flushPromises();
		// Flushes the promise returned by getBlockLogData()
		await flushPromises();
		expect( store.formDisabled ).toBeFalsy();
	} );

	it( 'should not send the allowusertalk API param when the disableUTEdit field is hidden (Multiblocks OFF)', () => {
		mockMwConfigGet( { blockEnableMultiblocks: false, blockDisableUTEditVisible: true } );
		const store = useBlockStore();
		// Sitewide block can disable user talk page editing.
		store.type = 'sitewide';
		store.disableUTEdit = true;
		store.doBlock();
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const expected = {
			action: 'block',
			nocreate: 1,
			autoblock: 1,
			errorformat: 'html',
			errorlang: 'en',
			errorsuselocal: true,
			expiry: '',
			format: 'json',
			formatversion: 2,
			reason: '',
			uselang: 'en',
			user: ''
		};
		expect( spy ).toHaveBeenCalledWith( expected );

		// But a partial block cannot.
		store.type = 'partial';
		store.doBlock();
		expected.partial = 1;
		expected.actionrestrictions = '';
		expected.allowusertalk = 1;
		expect( spy ).toHaveBeenCalledWith( expected );
	} );

	it( 'should not send the allowusertalk API param when the disableUTEdit field is hidden (Multiblocks ON)', () => {
		mockMwConfigGet( { blockDisableUTEditVisible: true, blockId: 1116 } );
		const store = useBlockStore();
		// Sitewide block can disable user talk page editing.
		store.type = 'sitewide';
		store.disableUTEdit = true;
		store.doBlock();
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const expected = {
			id: 1116,
			action: 'block',
			nocreate: 1,
			autoblock: 1,
			errorformat: 'html',
			errorlang: 'en',
			errorsuselocal: true,
			expiry: '',
			format: 'json',
			formatversion: 2,
			reason: '',
			uselang: 'en'
		};
		expect( spy ).toHaveBeenCalledWith( expected );

		// But a partial block cannot.
		store.type = 'partial';
		store.doBlock();
		expected.partial = 1;
		expected.actionrestrictions = '';
		expected.allowusertalk = 1;
		expect( spy ).toHaveBeenCalledWith( expected );
	} );
} );

afterEach( () => {
	jest.clearAllMocks();
} );
