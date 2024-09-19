'use strict';

const { mount, VueWrapper } = require( '@vue/test-utils' );
const SpecialBlock = require( '../../../resources/src/mediawiki.special.block/SpecialBlock.vue' );

/**
 * Mount the SpecialBlock component with the default configuration,
 * wrapping it in a form element and appending it to the document body.
 * This is needed because the <form> element is created server-side.
 *
 * @param {Object} [config] Configuration to override the defaults.
 * @param {Array<Object>} [apiMocks] Additional API mocks to add to the default list.
 * @return {VueWrapper} The mounted component.
 */
function getSpecialBlock( config = {}, apiMocks = [] ) {
	// Other various mocks that may be needed across the test suite.
	HTMLElement.prototype.scrollIntoView = jest.fn();

	// Mock calls to mw.config.get() and mw.Api.prototype.get().
	mockMwConfigGet( config );
	mockMwApiGet( apiMocks );

	// Create a form element and append it to the document body.
	const form = document.createElement( 'form' );
	document.body.appendChild( form );

	// Mount the SpecialBlock component inside the form element.
	return mount( SpecialBlock, { attachTo: form } );
}

/**
 * Mock calls to mw.config.get().
 * The default implementation correlates to the SpecialBlock::codexFormData property in PHP.
 *
 * @param {Object} [config] Will be merged with the defaults.
 */
function mockMwConfigGet( config = {} ) {
	const mockConfig = Object.assign( {
		wgUserLanguage: 'en',
		blockAlreadyBlocked: false,
		blockTargetUser: null,
		blockAdditionalDetailsPreset: [ 'wpAutoBlock' ],
		blockAllowsEmailBan: true,
		blockAllowsUTEdit: true,
		blockAutoblockExpiry: '1 day',
		blockDefaultExpiry: '',
		blockDetailsPreset: [],
		blockExpiryPreset: null,
		blockHideUser: true,
		blockExpiryOptions: {
			infinite: 'infinite',
			'Other time:': 'other'
		},
		blockPreErrors: [],
		blockReasonOptions: [
			{ label: 'block-reason-1', value: 'block-reason-1' },
			{ label: 'block-reason-2', value: 'block-reason-2' }
		],
		blockSuccessMsg: '[[Special:Contributions/ExampleUser|ExampleUser]] has been blocked.'
	}, config );
	mw.config.get.mockImplementation( ( key ) => mockConfig[ key ] );
}

/**
 * Mock calls to mw.Api.prototype.get() based on given parameters and response.
 * The default implementation mocks API GET requests used across the test suite.
 *
 * @param {Array<Object>} [additionalMocks] Additional mocks to add to the default list.
 *   Each object should contain the keys `params` and `response`.
 *   `params` is an Object with sufficient set of parameters to identify the request
 *   (e.g. `{ list: 'logevents', letype: 'block' }`). The `response` is an Object
 *   with the expected response data (e.g. `{ query: { logevents: [ ... ] } }`).
 */
function mockMwApiGet( additionalMocks = [] ) {
	/**
	 * This is intended to encapsulate any API requests that
	 * consistently need to be mocked across the test suite.
	 *
	 * @type {Object}
	 */
	const mocks = [
		// Used in TargetBlockLog
		{
			params: {
				list: 'logevents',
				letype: 'block'
			},
			response: {
				query: {
					logevents: [
						{
							logid: 980,
							title: 'User:ExampleUser',
							params: {
								duration: '1 year',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-17T14:30:51Z'
							},
							type: 'block',
							user: 'Admin',
							timestamp: '2024-09-17T14:30:51Z',
							comment: 'A reason'
						}
					]
				}
			}
		},
		// Used in UserLookup
		{
			params: {
				list: 'allusers'
			},
			response: {
				query: {
					allusers: [
						{ name: 'UserLookup1' },
						{ name: 'UserLookup2' }
					]
				}
			}
		},
		// Add more mocks as needed above this line.
		...additionalMocks
	];
	mw.Api.prototype.get.mockImplementation( ( params ) => {
		if ( !params ) {
			// eslint-disable-next-line no-console
			console.warn( 'No params provided to mw.Api.get()' );
			return Promise.resolve( jest.fn() );
		}
		// Find the appropriate mock from the list based on the expected parameters.
		const mock = mocks.find( ( m ) => Object.entries( m.params )
			.every( ( [ key, value ] ) => params[ key ] === value )
		);
		if ( !mock ) {
			// eslint-disable-next-line no-console
			console.warn( 'No mock found for:', params );
			return Promise.resolve( jest.fn() );
		}
		return Promise.resolve( mock.response );
	} );
}

module.exports = {
	getSpecialBlock,
	mockMwApiGet,
	mockMwConfigGet
};
