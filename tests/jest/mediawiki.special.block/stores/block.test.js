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
		expect( store.confirmationRequired ).toBe( false );
		mockMwConfigGet( { wgUserName: 'ExampleUser' } );
		store.targetUser = 'ExampleUser';
		expect( store.confirmationRequired ).toBe( true );
	} );

	it( 'should require confirmation for hide user', () => {
		const store = useBlockStore();
		expect( store.confirmationRequired ).toBe( false );
		store.type = 'sitewide';
		store.additionalDetails = [ 'wpHideName' ];
		mw.util.isInfinity = jest.fn().mockReturnValue( true );
		expect( store.confirmationRequired ).toBe( true );
	} );

	it( 'should set hideNameDisabled for blocks that aren\'t sitewide with an infinite expiry', () => {
		const store = useBlockStore();
		expect( store.hideNameDisabled ).toBe( false );
		store.type = 'partial';
		store.expiry = 'infinite';
		mw.util.isInfinity = jest.fn().mockReturnValue( true );
		expect( store.hideNameDisabled ).toBe( true );
		store.type = 'sitewide';
		store.expiry = '3 hours';
		mw.util.isInfinity = jest.fn().mockReturnValue( false );
		expect( store.hideNameDisabled ).toBe( true );
		store.type = 'sitewide';
		store.expiry = 'infinite';
		mw.util.isInfinity = jest.fn().mockReturnValue( true );
		expect( store.hideNameDisabled ).toBe( false );
	} );
} );
