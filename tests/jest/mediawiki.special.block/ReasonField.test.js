'use strict';

const { shallowMount } = require( '@vue/test-utils' );
const ReasonField = require( '../../../resources/src/mediawiki.special.block/components/ReasonField.vue' );
const { mockMwConfigGet } = require( './SpecialBlock.setup.js' );

describe( 'ReasonField', () => {
	const testCases = [
		{
			modelValue: 'Vandalism: Test',
			selected: 'Vandalism',
			other: 'Test'
		}, {
			modelValue: 'VandalismTest',
			selected: 'other',
			other: 'VandalismTest'
		}, {
			modelValue: 'Test',
			selected: 'other',
			other: 'Test'
		}, {
			modelValue: 'Vandalism',
			selected: 'Vandalism',
			other: ''
		}, {
			modelValue: '',
			selected: 'other',
			other: ''
		}
	];
	it.each( testCases )( 'setFieldsFromGiven ($modelValue)', ( { modelValue, selected, other } ) => {
		mockMwConfigGet();
		// Mocks 'colon-separator' message, the only one used in the component.
		mw.msg = jest.fn().mockReturnValue( ': ' );
		const wrapper = shallowMount( ReasonField, { propsData: { modelValue } } );
		expect( wrapper.vm.selected ).toStrictEqual( selected );
		expect( wrapper.vm.other ).toStrictEqual( other );
	} );
} );
