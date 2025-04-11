'use strict';

const { shallowMount } = require( '@vue/test-utils' );
const { createTestingPinia } = require( '@pinia/testing' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const AdditionalDetailsField = require( '../../../resources/src/mediawiki.special.block/components/AdditionalDetailsField.vue' );
const useBlockStore = require( '../../../resources/src/mediawiki.special.block/stores/block.js' );

describe( 'AdditionalDetailsField', () => {
	it( 'should set hardBlockVisible when blocking an IP address', () => {
		mockMwConfigGet();
		const wrapper = shallowMount( AdditionalDetailsField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		const store = useBlockStore();
		// A username should not have the hardBlock option shown.
		store.targetUser = 'ExampleUser';
		mw.util.isIPAddress.mockReturnValue( false );
		expect( wrapper.vm.hardBlockVisible ).toStrictEqual( false );
		// An IP address should have hardBlock shown.
		store.targetUser = '192.0.2.34';
		mw.util.isIPAddress.mockReturnValue( true );
		expect( wrapper.vm.hardBlockVisible ).toStrictEqual( true );
	} );
} );
