'use strict';

const { mount, flushPromises } = require( '@vue/test-utils' );
const { mockMwApiGet } = require( './SpecialBlock.setup.js' );
const TargetBlockLog = require( '../../../resources/src/mediawiki.special.block/components/TargetBlockLog.vue' );

beforeAll( () => mockMwApiGet() );

describe( 'TargetBlockLog', () => {
	it( 'should show a table with one row when given a user with one block', async () => {
		const wrapper = mount( TargetBlockLog, {
			propsData: { targetUser: 'ExampleUser' }
		} );
		await flushPromises();
		// Test: The table should exist
		expect( wrapper.find( '.cdx-table__table' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( '.cdx-table__table tbody tr' );
		// Test: The table tbody should have one row
		expect( rows ).toHaveLength( 1 );
	} );
	it( 'should show a table with the no-previous-blocks message when given a user with no blocks', async () => {
		const wrapper = mount( TargetBlockLog, {
			propsData: { targetUser: 'NeverBlocked' }
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
		const wrapper = mount( TargetBlockLog, {
			propsData: { targetUser: 'BlockedALot' }
		} );
		await flushPromises();
		// Test: The table should exist
		expect( wrapper.find( '.cdx-table__table' ).exists() ).toBeTruthy();
		const rows = wrapper.findAll( '.cdx-table__table tbody tr' );
		// Test: The table tbody should have ten rows
		expect( rows ).toHaveLength( 10 );
		// Test: The show more link should exist
		expect( wrapper.find( '.mw-block-fulllog' ).exists() ).toBeTruthy();
	} );
} );
