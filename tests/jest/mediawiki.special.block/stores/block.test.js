'use strict';

const { nextTick } = require( 'vue' );
const { setActivePinia, createPinia } = require( 'pinia' );
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
		const store = useBlockStore();
		mw.util.isInfinity.mockReturnValue( true );
		mockMwConfigGet( { wgUserName: 'ExampleUser' } );
		store.targetUser = 'ExampleUserOther';
		expect( store.confirmationMessage ).toStrictEqual( '' );
		store.targetUser = 'ExampleUser';
		await nextTick();
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-blockingself' );
	} );

	it( 'should require confirmation for hide user', async () => {
		const store = useBlockStore();
		mw.util.isInfinity.mockReturnValue( true );
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

	it( 'should set hardBlockVisible when blocking an IP address', () => {
		const store = useBlockStore();
		// A username should not have the hardBlock option shown.
		store.targetUser = 'ExampleUser';
		mw.util.isIPAddress.mockReturnValue( false );
		expect( store.hardBlockVisible ).toStrictEqual( false );
		// An IP address should have hardBlock shown.
		store.targetUser = '192.0.2.34';
		mw.util.isIPAddress.mockReturnValue( true );
		expect( store.hardBlockVisible ).toStrictEqual( true );
	} );

	it( 'show the wpDisableUTEdit field for partial blocks, unless the block is against the User_talk namespace', () => {
		mockMwConfigGet( { blockDisableUTEditVisible: true } );
		const store = useBlockStore();
		// Visible for sitewide blocks.
		store.type = 'sitewide';
		expect( store.disableUTEditVisible ).toStrictEqual( true );
		// But not visible for partial.
		store.type = 'partial';
		expect( store.disableUTEditVisible ).toStrictEqual( false );
		// Including if they block a different namespace (the Talk NS in this case, ID 1).
		store.namespaces.push( 1 );
		expect( store.disableUTEditVisible ).toStrictEqual( false );
		// But if it's the User_talk NS (ID 3), then it is visible.
		store.namespaces.push( 3 );
		expect( store.disableUTEditVisible ).toStrictEqual( true );
	} );

	it( 'should only pass the reblock param to the API if there was an "already blocked" error', () => {
		mockMwConfigGet( { blockAlreadyBlocked: false } );
		const store = useBlockStore();
		store.$reset();
		store.doBlock();
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const expected = {
			action: 'block',
			allowusertalk: 1,
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
} );
