'use strict';

const { shallowMount } = require( '@vue/test-utils' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const NamespacesField = require( '../../../resources/src/mediawiki.special.block/components/NamespacesField.vue' );

const defaultProps = {
	modelValue: [],
	lookupClass: 'mw-block-namespaces',
	labelMessage: 'ipb-namespaces-label',
	placeholderMessage: 'block-namespaces-placeholder'
};

function mountField( props = {} ) {
	return shallowMount( NamespacesField, {
		props: { ...defaultProps, ...props }
	} );
}

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
		const wrapper = mountField();
		expect( wrapper.vm.menuItems ).toStrictEqual( [
			{ value: 0, label: 'blanknamespace' },
			{ value: 2, label: 'User' },
			{ value: 3, label: 'User talk' }
		] );
	} );

	it( 'should set the initial selections from the model value', () => {
		mockMwConfigGet();
		const wrapper = mountField( { modelValue: [ 1, 2 ] } );
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

	it( 'should update the model with new selections', () => {
		mockMwConfigGet();
		const wrapper = mountField();
		wrapper.vm.onUpdateChips( [
			{
				label: 'Talk',
				value: 1
			}, {
				label: 'User',
				value: 2
			}
		] );
		expect( wrapper.emitted( 'update:modelValue' ) ).toStrictEqual( [ [ [ 1, 2 ] ] ] );
	} );

	it( 'should filter namespace options using the input', () => {
		mockMwConfigGet( {
			wgFormattedNamespaces: {
				0: '',
				2: 'User',
				3: 'User talk'
			}
		} );
		const wrapper = mountField();
		wrapper.vm.onInput( 'talk' );
		expect( wrapper.vm.menuItems ).toStrictEqual( [
			{ value: 3, label: 'User talk' }
		] );
		wrapper.vm.onInput( '' );
		expect( wrapper.vm.menuItems ).toHaveLength( 3 );
	} );
} );
