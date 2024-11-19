'use strict';

const { mount, flushPromises } = require( '@vue/test-utils' );

const { createTestingPinia } = require( '@pinia/testing' );
const { mockMwConfigGet, mockMwApiGet } = require( './SpecialBlock.setup.js' );
const BlockLog = require( '../../../resources/src/mediawiki.special.block/components/BlockLog.vue' );

beforeAll( () => mockMwApiGet() );

describe( 'BlockLog', () => {
	it( 'should show a table with one row when given a user with one block', async () => {
		mockMwConfigGet( { blockTargetUser: 'ExampleUser' } );
		const wrapper = mount( BlockLog, {
			global: { plugins: [ createTestingPinia() ] }
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
			global: { plugins: [ createTestingPinia() ] }
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
			global: { plugins: [ createTestingPinia() ] }
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
			global: { plugins: [ createTestingPinia() ] }
		} );
		await flushPromises();
		expect( wrapper.find( '.mw-block-log__type-suppress' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( '.cdx-table__table tbody tr' );
		expect( rows ).toHaveLength( 3 );
	} );
} );
