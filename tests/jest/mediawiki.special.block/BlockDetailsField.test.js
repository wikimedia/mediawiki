'use strict';

const { shallowMount } = require( '@vue/test-utils' );
const { createTestingPinia } = require( '@pinia/testing' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const BlockDetailsField = require( '../../../resources/src/mediawiki.special.block/components/BlockDetailsField.vue' );
const useBlockStore = require( '../../../resources/src/mediawiki.special.block/stores/block.js' );

describe( 'AdditionalDetailsField', () => {
	it( 'show the wpDisableUTEdit field for partial blocks, unless the block is against the User_talk namespace', () => {
		mockMwConfigGet( { blockDisableUTEditVisible: true } );
		const wrapper = shallowMount( BlockDetailsField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		const store = useBlockStore();
		// Visible for sitewide blocks.
		store.type = 'sitewide';
		expect( wrapper.vm.disableUTEditVisible ).toStrictEqual( true );
		// But not visible for partial.
		store.type = 'partial';
		expect( wrapper.vm.disableUTEditVisible ).toStrictEqual( false );
		// Including if they block a different namespace (the Talk NS in this case, ID 1).
		store.namespaces.push( 1 );
		expect( wrapper.vm.disableUTEditVisible ).toStrictEqual( false );
		// But if it's the User_talk NS (ID 3), then it is visible.
		store.namespaces.push( 3 );
		expect( wrapper.vm.disableUTEditVisible ).toStrictEqual( true );
	} );
} );
