'use strict';

const { nextTick } = require( 'vue' );
const { mount } = require( '@vue/test-utils' );
const UserLookup = require( '../../../resources/src/mediawiki.special.block/components/UserLookup.vue' );

describe( 'UserLookup', () => {
	it( 'should update menu items based on the API response', async () => {
		mw.Api.prototype.get = jest.fn().mockResolvedValue( {
			query: {
				allusers: [
					{ name: 'UserLookup1' },
					{ name: 'UserLookup2' }
				]
			}
		} );
		const wrapper = mount( UserLookup, {
			props: { modelValue: 'UserLookup' }
		} );
		const input = wrapper.find( '.cdx-text-input__input' );
		expect( input.element.value ).toBe( 'UserLookup' );
		const listBox = wrapper.find( '.cdx-menu__listbox' );
		// "No results"
		expect( listBox.element.children ).toHaveLength( 1 );
		await input.trigger( 'input' );
		await nextTick();
		expect( listBox.element.children ).toHaveLength( 2 );
		expect( listBox.element.children[ 0 ].textContent ).toBe( 'UserLookup1' );
		expect( listBox.element.children[ 1 ].textContent ).toBe( 'UserLookup2' );
	} );
} );
