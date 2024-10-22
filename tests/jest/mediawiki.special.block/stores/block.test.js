'use strict';

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

	it( 'should require confirmation if the target user is the current user', () => {
		const store = useBlockStore();
		mw.util.isInfinity.mockReturnValue( true );
		mockMwConfigGet( { wgUserName: 'ExampleUser' } );
		store.targetUser = 'ExampleUserOther';
		expect( store.confirmationRequired ).toBe( false );
		store.targetUser = 'ExampleUser';
		expect( store.confirmationRequired ).toBe( true );
	} );

	it( 'should require confirmation for hide user', () => {
		const store = useBlockStore();
		mw.util.isInfinity.mockReturnValue( true );
		expect( store.confirmationRequired ).toBe( false );
		store.type = 'sitewide';
		store.hideName = true;
		expect( store.confirmationRequired ).toBe( true );
	} );

	it( 'should set hideNameVisible only for blocks that are sitewide and with an infinite expiry', () => {
		mockMwConfigGet( { blockHideUser: false } );
		const store = useBlockStore();
		// Don't have the right.
		expect( store.hideNameVisible ).toBe( false );
		mockMwConfigGet( { blockHideUser: true } );
		// Partial infinite.
		store.type = 'partial';
		store.expiry = 'infinite';
		mw.util.isInfinity.mockReturnValue( true );
		expect( store.hideNameVisible ).toBe( false );
		// Sitewide time-limited.
		store.type = 'sitewide';
		store.expiry = '3 hours';
		mw.util.isInfinity.mockReturnValue( false );
		expect( store.hideNameVisible ).toBe( false );
		// Sitewide infinite.
		store.type = 'sitewide';
		store.expiry = 'infinite';
		mw.util.isInfinity.mockReturnValue( true );
		expect( store.hideNameVisible ).toBe( true );
	} );

	it( 'should set hardBlockVisible when blocking an IP address', () => {
		const store = useBlockStore();
		// A username should not have the hardBlock option shown.
		store.targetUser = 'ExampleUser';
		mw.util.isIPAddress.mockReturnValue( false );
		expect( store.hardBlockVisible ).toBe( false );
		// An IP address should have hardBlock shown.
		store.targetUser = '192.0.2.34';
		mw.util.isIPAddress.mockReturnValue( true );
		expect( store.hardBlockVisible ).toBe( true );
	} );
} );
