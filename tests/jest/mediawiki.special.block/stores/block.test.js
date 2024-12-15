'use strict';

const { nextTick } = require( 'vue' );
const { setActivePinia, createPinia } = require( 'pinia' );
const { flushPromises } = require( '@vue/test-utils' );
const { mockMwConfigGet } = require( '../SpecialBlock.setup.js' );
const useBlockStore = require( '../../../../resources/src/mediawiki.special.block/stores/block.js' );

describe( 'Block store', () => {
	beforeEach( () => {
		// creates a fresh pinia and makes it active
		// so it's automatically picked up by any useStore() call
		// without having to pass it to it: `useStore(pinia)`
		setActivePinia( createPinia() );
	} );

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
		store.hideName = true;
		await nextTick();
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-confirmhideuser' );
	} );

	it( 'should set hideNameVisible only for blocks that are sitewide and with an infinite expiry', () => {
		mockMwConfigGet( { blockHideUser: false } );
		const store = useBlockStore();
		// Don't have the right.
		expect( store.hideNameVisible ).toStrictEqual( false );
		mockMwConfigGet( { blockHideUser: true } );
		// Partial infinite.
		store.type = 'partial';
		store.expiry = 'infinite';
		mw.util.isInfinity.mockReturnValue( true );
		expect( store.hideNameVisible ).toStrictEqual( false );
		// Sitewide time-limited.
		store.type = 'sitewide';
		store.expiry = '3 hours';
		mw.util.isInfinity.mockReturnValue( false );
		expect( store.hideNameVisible ).toStrictEqual( false );
		// Sitewide infinite.
		store.type = 'sitewide';
		store.expiry = 'infinite';
		mw.util.isInfinity.mockReturnValue( true );
		expect( store.hideNameVisible ).toStrictEqual( true );
	} );

	it( 'should only pass the reblock param to the API if there was an "already blocked" error', () => {
		const jQuery = jest.requireActual( '../../../../resources/lib/jquery/jquery.js' );
		mw.Api.prototype.postWithEditToken.mockReturnValue( jQuery.Deferred().resolve().promise() );
		mockMwConfigGet( { blockAlreadyBlocked: false } );
		const store = useBlockStore();
		store.doBlock();
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const expected = {
			action: 'block',
			allowusertalk: 1,
			nocreate: 1,
			autoblock: 1,
			errorlang: 'en',
			errorsuselocal: true,
			expiry: '',
			format: 'json',
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

	it( 'should make one API request for the block log and active blocks', async () => {
		const jQuery = jest.requireActual( '../../../../resources/lib/jquery/jquery.js' );
		mw.Api.prototype.postWithEditToken.mockReturnValue( jQuery.Deferred().resolve().promise() );
		const store = useBlockStore();
		mw.Api.prototype.get = jest.fn().mockReturnValue( jQuery.Deferred().resolve( { query: { blocks: [] } } ).promise() );
		const spy = jest.spyOn( mw.Api.prototype, 'get' );
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

	afterEach( () => {
		jest.clearAllMocks();
	} );
} );
