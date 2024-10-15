'use strict';

const { mount, flushPromises } = require( '@vue/test-utils' );
const { mockMwApiGet } = require( './SpecialBlock.setup.js' );
const { createTestingPinia } = require( '@pinia/testing' );
const UserLookup = require( '../../../resources/src/mediawiki.special.block/components/UserLookup.vue' );

beforeAll( () => mockMwApiGet() );

describe( 'UserLookup', () => {
	it( 'should update menu items based on the API response', async () => {
		const wrapper = mount( UserLookup, {
			props: { modelValue: 'UserLookup' },
			global: {
				plugins: [ createTestingPinia( { stubActions: false } ) ]
			}
		} );
		// Ensure that the initial search string matches the initial prop value.
		const input = wrapper.find( '.cdx-text-input__input' );
		expect( input.element.value ).toBe( 'UserLookup' );
		// "No results"
		const listBox = wrapper.find( '.cdx-menu__listbox' );
		expect( listBox.element.children ).toHaveLength( 1 );
		await input.trigger( 'input' );
		// Ensure that the Lookup menu is populated with API response data
		// We need to use "flushPromises" from Vue test utils
		// to ensure the fake API request can complete
		await flushPromises();
		expect( listBox.element.children ).toHaveLength( 2 );
		expect( listBox.element.children[ 0 ].textContent ).toBe( 'UserLookup1' );
		expect( listBox.element.children[ 1 ].textContent ).toBe( 'UserLookup2' );
	} );
} );
