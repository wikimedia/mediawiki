'use strict';

const { shallowMount } = require( '@vue/test-utils' );
const { createTestingPinia } = require( '@pinia/testing' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const NamespacesField = require( '../../../resources/src/mediawiki.special.block/components/NamespacesField.vue' );
const useBlockStore = require( '../../../resources/src/mediawiki.special.block/stores/block.js' );

describe( 'NamespacesField', () => {
	it( 'should populate the namespace options from wgFormattedNamespaces', () => {
		mockMwConfigGet( {
			wgFormattedNamespaces: {
				// Don't assume keys are in the correct order.
				3: 'User talk',
				'-1': 'Special',
				2: 'User',
				// Should get replaced with the 'blanknamespace' message.
				0: ''
			}
		} );
		const wrapper = shallowMount( NamespacesField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		expect( wrapper.vm.menuItems ).toStrictEqual( [
			{ value: 0, label: 'blanknamespace' },
			{ value: 2, label: 'User' },
			{ value: 3, label: 'User talk' }
		] );
	} );

	it( 'should set the initial selections from blockNamespaceRestrictions', () => {
		mockMwConfigGet( { blockNamespaceRestrictions: '1\n2' } );
		const wrapper = shallowMount( NamespacesField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		expect( wrapper.vm.chips ).toStrictEqual( [
			{
				label: 'Talk',
				value: 1
			}, {
				label: 'User',
				value: 2
			}
		] );
	} );

	it( 'should update the store with new selections', () => {
		mockMwConfigGet();
		const wrapper = shallowMount( NamespacesField, {
			global: { plugins: [ createTestingPinia( { stubActions: false } ) ] }
		} );
		expect( wrapper.vm.chips ).toStrictEqual( [] );
		wrapper.vm.onUpdateChips( [
			{
				label: 'Talk',
				value: 1
			}, {
				label: 'User',
				value: 2
			}
		] );
		expect( useBlockStore().namespaces ).toStrictEqual( [ 1, 2 ] );
	} );
} );
