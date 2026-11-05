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

	it( 'should only show the deferred block option if the user can place deferred blocks', () => {
		mockMwConfigGet( { blockDeferredVisible: false } );
		let wrapper = shallowMount( AdditionalDetailsField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		expect( wrapper.vm.deferredVisible ).toStrictEqual( false );

		mockMwConfigGet( { blockDeferredVisible: true } );
		wrapper = shallowMount( AdditionalDetailsField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		expect( wrapper.vm.deferredVisible ).toStrictEqual( true );
	} );

	it( 'should disable the deferred block option when editing a block that is in effect', () => {
		mockMwConfigGet( { blockDeferredVisible: true } );
		const wrapper = shallowMount( AdditionalDetailsField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		const store = useBlockStore();
		expect( wrapper.vm.deferredDisabled ).toStrictEqual( false );

		store.loadFromData( { id: 1, restrictions: {}, deferred: false } );
		expect( wrapper.vm.deferredDisabled ).toStrictEqual( true );
		expect( wrapper.vm.undeferWarningVisible ).toStrictEqual( false );

		store.loadFromData( { id: 2, restrictions: {}, deferred: true } );
		expect( wrapper.vm.deferredDisabled ).toStrictEqual( false );
		expect( wrapper.vm.undeferWarningVisible ).toStrictEqual( false );

		store.deferred = false;
		expect( wrapper.vm.undeferWarningVisible ).toStrictEqual( true );
	} );
} );
