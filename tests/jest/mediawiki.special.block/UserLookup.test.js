'use strict';

const { nextTick } = require( 'vue' );
const { mount, flushPromises } = require( '@vue/test-utils' );
const { createTestingPinia } = require( '@pinia/testing' );
const { getSpecialBlock, mockMwApiGet, mockMwConfigGet } = require( './SpecialBlock.setup.js' );
const UserLookup = require( '../../../resources/src/mediawiki.special.block/components/UserLookup.vue' );
const useBlockStore = require( '../../../resources/src/mediawiki.special.block/stores/block.js' );

describe( 'UserLookup', () => {
	afterEach( () => {
		document.body.innerHTML = '';
	} );

	const getWrapper = ( propsData, apiMocks = [] ) => {
		mockMwApiGet( apiMocks );
		return mount( UserLookup, {
			props: propsData,
			global: {
				plugins: [ createTestingPinia( { stubActions: false } ) ]
			},
			attachTo: document.body
		} );
	};

	it( 'should update menu items based on the API response', async () => {
		mockMwConfigGet();
		const wrapper = getWrapper( { modelValue: 'ExampleUser' } );
		// Ensure that the initial search string matches the initial prop value.
		const input = wrapper.find( '.cdx-text-input__input' );
		expect( input.element.value ).toBe( 'ExampleUser' );
		// "No results"
		const listBox = wrapper.find( '.cdx-menu__listbox' );
		expect( listBox.element.children ).toHaveLength( 1 );
		await input.trigger( 'input' );
		// Ensure that the Lookup menu is populated with API response data
		// We need to use "flushPromises" from Vue test utils
		// to ensure the fake API request can complete
		await flushPromises();
		expect( listBox.element.children ).toHaveLength( 2 );
		expect( listBox.element.children[ 0 ].textContent ).toBe( 'ExampleUser' );
		expect( listBox.element.children[ 1 ].textContent ).toBe( 'ExampleUser2' );

		// Select a target and then focus on the field to ensure an API request is made
		// and a new set of results is listed.
		await listBox.element.children[ 1 ].click();
		await input.trigger( 'focus' );
		await flushPromises();
		expect( listBox.element.children ).toHaveLength( 1 );
	} );

	it( 'should show an error if the pre-supplied target is missing', async () => {
		const wrapper = getSpecialBlock( {
			blockTargetUser: 'NonexistentUser',
			blockTargetExists: false
		} );
		await flushPromises();
		expect( wrapper.find( '.cdx-message__content' ).text() ).toBe( 'nosuchusershort' );
	} );

	it( 'should show an error if the target is changed to a missing user', async () => {
		const wrapper = getWrapper( { modelValue: '' } );
		await wrapper.find( '.cdx-text-input__input' ).setValue( 'NonexistentUser' );
		await flushPromises();
		expect( wrapper.find( '.cdx-message__content' ).text() ).toBe( 'nosuchusershort' );
	} );

	it( 'should not show an error when selecting a target from the menu items', async () => {
		mockMwConfigGet( { blockTargetUser: 'ExampleUser', blockTargetExists: true } );
		const wrapper = getWrapper(
			{ modelValue: 'ExampleUser' },
			[ {
				params: {
					list: 'allusers',
					auprefix: 'Example'
				},
				response: {
					query: {
						allusers: [
							{ name: 'ExampleUser' },
							{ name: 'ExampleUser2' }
						]
					}
				}
			} ]
		);
		await wrapper.find( '.cdx-text-input__input' ).setValue( 'Example' );
		await flushPromises();
		const listBox = wrapper.find( '.cdx-menu__listbox' );
		expect( listBox.element.children ).toHaveLength( 2 );
		await listBox.element.children[ 0 ].click();
		expect( wrapper.vm.messages ).toStrictEqual( {} );
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
	} );

	it( 'should reset the form when the clear button is clicked', async () => {
		mockMwConfigGet( {
			blockTargetUser: 'NonexistentUser',
			blockTargetExists: false
		} );
		const wrapper = getWrapper( { modelValue: 'NonexistentUser' } );
		const store = useBlockStore();
		await flushPromises();
		expect( wrapper.vm.messages ).toStrictEqual( { error: 'nosuchusershort' } );
		await wrapper.find( '.cdx-text-input__clear-icon' ).trigger( 'click' );
		await nextTick();
		expect( wrapper.vm.messages ).toStrictEqual( {} );
		expect( store.targetExists ).toBeFalsy();
		expect( document.activeElement.name ).toStrictEqual( 'wpTarget' );
	} );

	it( 'should sanitize IPv6 or Range after target is selected', async () => {
		mockMwConfigGet();
		const target = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';
		const expected = '2001:DB8:85A3:0:0:8A2E:370:7334';

		mw.util.isIPAddress = jest.fn().mockReturnValue( true );
		mw.util.isIPv4Address = jest.fn().mockReturnValue( false );
		mw.util.isIPv6Address = jest.fn().mockReturnValue( true );
		mw.util.sanitizeIP = jest.fn().mockReturnValue( expected );

		const wrapper = getWrapper( { modelValue: target }, [ {
			params: {
				list: 'allusers',
				auprefix: target
			},
			response: {
				query: {
					allusers: []
				}
			}
		} ] );
		const store = useBlockStore();

		await wrapper.vm.onChange();
		expect( store.targetUser ).toBe( expected );
	} );

	it( 'should show an error for invalid IP Range', async () => {
		const wrapper = getSpecialBlock( {
			blockTargetUser: '127.0.0.0/11',
			blockTargetExists: false
		} );
		await flushPromises();
		expect( wrapper.find( '.cdx-message__content' ).text() )
			.toStrictEqual( 'ip_range_toolarge' );
	} );
} );
