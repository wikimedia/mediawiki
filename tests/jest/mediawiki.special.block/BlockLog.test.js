'use strict';

const { mount, flushPromises } = require( '@vue/test-utils' );

const { createTestingPinia } = require( '@pinia/testing' );
const { mockMwApiGet, mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const BlockLog = require( '../../../resources/src/mediawiki.special.block/components/BlockLog.vue' );

beforeAll( () => mockMwApiGet() );

describe( 'BlockLog', () => {
	it( 'should show a table with one row when given a user with one block', async () => {
		mockMwConfigGet( { blockTargetUser: 'ExampleUser' } );
		const wrapper = mount( BlockLog, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		// Test: The table should exist
		expect( wrapper.find( '.cdx-table__table' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( '.cdx-table__table tbody tr' );
		// Test: The table tbody should have one row
		expect( rows ).toHaveLength( 1 );
	} );

	it( 'should show a table with the no-previous-blocks message when given a user with no blocks', async () => {
		mockMwConfigGet( { blockTargetUser: 'NeverBlocked' } );
		const wrapper = mount( BlockLog, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		// Test: The table should exist
		expect( wrapper.find( '.cdx-table__table' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( '.cdx-table__table tbody tr' );
		// Test: The table tbody should have one row
		expect( rows ).toHaveLength( 1 );
		// Test: The row should contain the no-previous-blocks message
		expect( rows[ 0 ].text() ).toContain( 'block-user-no-previous-blocks' );
	} );

	it( 'should show a table with ten rows, and a show more link, when given a user with more than ten blocks', async () => {
		mockMwConfigGet( { blockTargetUser: 'BlockedALot' } );
		const wrapper = mount( BlockLog, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		// Test: The table should exist
		expect( wrapper.find( '.cdx-table__table' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( '.cdx-table__table tbody tr' );
		// Test: The table tbody should have ten rows
		expect( rows ).toHaveLength( 10 );
		// Test: The show more link should exist
		expect( wrapper.find( '.mw-block-log-fulllog' ).exists() ).toBeTruthy();
	} );

	it( 'should show the suppress log with block and reblock entries', async () => {
		mockMwConfigGet( { blockTargetUser: 'BadNameBlocked' } );
		const wrapper = mount( BlockLog, {
			propsData: { blockLogType: 'suppress' },
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-suppress' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( '.cdx-table__table tbody tr' );
		expect( rows ).toHaveLength( 3 );
	} );

	it( 'should show the active hightlighted selected row', async () => {
		mockMwConfigGet( { blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true, blockId: 1116 } );
		const wrapper = mount( BlockLog, {
			propsData: { blockLogType: 'active' },
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( 'table.cdx-table__table tbody tr' );
		expect( rows ).toHaveLength( 3 );
		expect( rows[ 1 ].classes() ).toContain( 'cdx-selected-block-row' );
	} );

	it( 'should show a table with the block-user-no-active-blocks message when given a user with no active blocks', async () => {
		mockMwConfigGet( { blockTargetUser: 'NeverBlocked' } );
		const wrapper = mount( BlockLog, {
			propsData: { blockLogType: 'active' },
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		// Test: The table should exist
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( 'table.cdx-table__table tbody tr' );
		// Test: The table tbody should have one row
		expect( rows ).toHaveLength( 1 );
		// Test: The row should contain the block-user-no-active-blocks message
		expect( rows[ 0 ].text() ).toContain( 'block-user-no-active-blocks' );
	} );

	it( 'should show a list of block parameters', async () => {
		mockMwConfigGet( { blockTargetUser: 'PartiallyBlockedUser' } );
		const wrapper = mount( BlockLog, {
			propsData: { blockLogType: 'active' },
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-active' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( 'table.cdx-table__table tbody tr' );
		expect( rows ).toHaveLength( 1 );
		expect( rows[ 0 ].find( 'ul' ).text() ).toContain( 'blocklist-editing blocklist-editing-page Foobar' );
	} );

	it( 'should show relative expiries where appropriate', async () => {
		mockMwConfigGet( { blockTargetUser: 'BlockedALot' } );
		const wrapper = mount( BlockLog, {
			propsData: { blockLogType: 'recent' },
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		await flushPromises();
		// First expiry (5 years)
		expect( wrapper.find(
			'.mw-block-log__type-recent tr:first-child .mw-block-log__parameters li:first-child'
		).text() ).toStrictEqual( '5 years' );
		// Second is an unblock event, so no expiry
		expect(
			wrapper.find( '.mw-block-log__type-recent tr:nth-child(2) .mw-block-log__parameters' ).text()
		).toStrictEqual( '' );
		// Third row is an indefinite block
		expect( wrapper.find(
			'.mw-block-log__type-recent tr:nth-child(3) .mw-block-log__parameters li:first-child'
		).text() ).toStrictEqual( 'infinite' );
		// Fourth is a block entered with an exact datetime, and so should not show a relative expiry.
		expect( wrapper.find(
			'.mw-block-log__type-recent tr:nth-child(4) .mw-block-log__parameters li:first-child'
		).text() ).toStrictEqual( '2029-09-20T14:31:51.000Z' );
	} );
} );
