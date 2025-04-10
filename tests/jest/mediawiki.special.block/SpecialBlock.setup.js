'use strict';

const { mount, VueWrapper } = require( '@vue/test-utils' );
const { createTestingPinia } = require( '@pinia/testing' );

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
	mw.confirmCloseWindow = jest.fn();

	// Mock calls to mw.config.get() and mw.Api.prototype.get().
	mockMwConfigGet( config );
	mockMwApiGet( apiMocks );

	// Create a form element and append it to the document body.
	const form = document.createElement( 'form' );
	document.body.appendChild( form );

	// Mount the SpecialBlock component inside the form element.
	return mount( SpecialBlock, {
		global: {
			plugins: [ createTestingPinia( { stubActions: false } ) ]
		},
		attachTo: form
	} );
}

/**
 * Mock calls to mw.config.get().
 * The default implementation correlates to the SpecialBlock::codexFormData property in PHP.
 *
 * @param {Object} [config] Will be merged with the defaults.
 */
function mockMwConfigGet( config = {} ) {
	const mockConfig = Object.assign( {
		blockEnableMultiblocks: true,
		blockId: null,
		wgNamespaceIds: {
			'': '(Main)',
			talk: 'Talk',
			user: 'User',
			// eslint-disable-next-line camelcase
			user_talk: 3
		},
		wgFormattedNamespaces: {
			0: '',
			1: 'Talk',
			2: 'User',
			3: 'User talk'
		},
		wgUserLanguage: 'en',
		blockAlreadyBlocked: false,
		blockTargetUser: null,
		blockTargetUserInput: null,
		blockTargetExists: null,
		blockAdditionalDetailsPreset: [ 'wpAutoBlock' ],
		blockAllowsEmailBan: true,
		blockAllowsUTEdit: true,
		blockAutoblockExpiry: '1 day',
		blockDetailsPreset: [ 'wpCreateAccount' ],
		blockExpiryDefault: '',
		blockExpiryPreset: null,
		blockHideUser: true,
		blockExpiryOptions: {
			infinite: 'infinite',
			'31 horas': '31 hours'
		},
		blockNamespaceRestrictions: '',
		blockPageRestrictions: '',
		blockPreErrors: [],
		blockReasonMaxLength: 500,
		blockReasonOptions: [
			{ label: 'Other', value: 'other' },
			{
				label: 'Common block reasons',
				items: [
					{ label: 'Vandalism', value: 'Vandalism' },
					{ label: 'Spam', value: 'Spam' },
					{ label: 'Disruptive editing', value: 'Disruptive editing' }
				]
			},
			{
				label: 'Templated reasons',
				items: [
					{ label: '{{anonblock}}', value: '{{anonblock}}' },
					{ label: '{{schoolblock}}', value: '{{schoolblock}}' },
					{ label: '{{sockpuppet}}', value: '{{sockpuppet}}' }
				]
			}
		],
		blockReasonPreset: '',
		blockSuccessMsg: '[[Special:Contributions/ExampleUser|ExampleUser]] has been blocked.',
		blockTypePreset: 'sitewide'
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
		// Used in BlockLog
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:ExampleUser'
			},
			response: {
				query: {
					logevents: [
						{
							logid: 980,
							title: 'User:ExampleUser',
							params: {
								duration: '1 year',
								'duration-l10n': '1 year',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-17T14:30:51Z'
							},
							type: 'block',
							user: 'Admin',
							timestamp: '2024-09-17T14:30:51Z',
							parsedcomment: 'A reason'
						}
					],
					blocks: []
				}
			}
		},
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:ActiveBlockedUser',
				bkusers: 'ActiveBlockedUser'
			},
			response: {
				query: {
					logevents: [
						{
							logid: 980,
							title: 'User:ActiveBlockedUser',
							params: {
								duration: '100 years',
								'duration-l10n': '100 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2124-09-17T14:30:51Z'
							},
							type: 'block',
							user: 'Admin',
							timestamp: '2024-09-17T14:30:51Z',
							parsedcomment: 'A reason'
						}
					],
					blocks: [
						{
							id: 1110,
							user: 'ActiveBlockedUser',
							timestamp: '2024-09-17T14:30:51Z',
							expiry: '2124-09-17T14:30:51Z',
							'duration-l10n': '100 years',
							by: 'Admin',
							anononly: false,
							nocreate: false,
							autoblock: false,
							noemail: true,
							allowusertalk: false,
							hidden: true,
							reason: 'Spamming talk page',
							parsedreason: 'Spamming talk page',
							restrictions: []
						},
						{
							id: 1116,
							user: 'ActiveBlockedUser',
							timestamp: '2024-09-17T14:30:51Z',
							expiry: '2029-09-17T14:30:51Z',
							'duration-l10n': '5 years',
							by: 'Admin',
							anononly: false,
							nocreate: false,
							autoblock: false,
							noemail: true,
							allowusertalk: false,
							hidden: true,
							reason: 'Spamming talk page',
							parsedreason: 'Spamming talk page',
							restrictions: []
						},
						{
							id: 1120,
							user: 'ActiveBlockedUser',
							timestamp: '2024-09-17T14:30:51Z',
							expiry: '2029-09-17T14:30:51Z',
							'duration-l10n': '5 years',
							by: 'Admin',
							anononly: false,
							nocreate: false,
							autoblock: false,
							noemail: true,
							allowusertalk: false,
							hidden: true,
							reason: 'Spamming talk page',
							parsedreason: 'Spamming talk page',
							restrictions: []
						}
					]
				}
			}
		},
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:NeverBlocked'
			},
			response: {
				query: {
					logevents: [],
					blocks: []
				}
			}
		},
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:BlockedALot'
			},
			response: {
				continue: {
					lecontinue: '20240909144407|979',
					continue: '-||blocks'
				},
				query: {
					logevents: [
						// 5-year block
						{
							logid: 980,
							title: 'User:BlockedALot',
							params: {
								duration: '5 years',
								'duration-l10n': '5 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-17T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-17T14:30:51Z',
							parsedcomment: 'A reason'
						},
						// Unblock event
						{
							logid: 981,
							title: 'User:BlockedALot',
							params: { blockId: 999 },
							type: 'block',
							action: 'unblock',
							user: 'Admin',
							timestamp: '2024-09-18T14:30:51Z',
							parsedcomment: 'A reason'
						},
						// Indefinite block
						{
							logid: 982,
							title: 'User:BlockedALot',
							params: {
								duration: 'infinity',
								'duration-l10n': 'infinite',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-19T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-19T14:30:51Z',
							parsedcomment: 'A reason'
						},
						// Block with datetime duration
						{
							logid: 983,
							title: 'User:BlockedALot',
							params: {
								duration: '2029-09-20T14:31:51Z',
								'duration-l10n': '5 years and 1 minute',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-20T14:31:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-20T14:30:51Z',
							parsedcomment: 'A reason'
						},
						// Reblock
						{
							logid: 984,
							title: 'User:BlockedALot',
							params: {
								duration: '5 years',
								'duration-l10n': '5 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-21T14:30:51Z'
							},
							type: 'block',
							action: 'reblock',
							user: 'Admin',
							timestamp: '2024-09-21T14:30:51Z',
							parsedcomment: 'A reason'
						},
						// Other mock blocks
						{
							logid: 985,
							title: 'User:BlockedALot',
							params: {
								duration: '5 years',
								'duration-l10n': '5 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-22T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-22T14:30:51Z',
							parsedcomment: 'A reason'
						},
						{
							logid: 986,
							title: 'User:BlockedALot',
							params: {
								duration: '5 years',
								'duration-l10n': '5 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-23T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-23T14:30:51Z',
							parsedcomment: 'A reason'
						},
						{
							logid: 987,
							title: 'User:BlockedALot',
							params: {
								duration: '5 years',
								'duration-l10n': '5 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-24T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-24T14:30:51Z',
							parsedcomment: 'A reason'
						},
						{
							logid: 988,
							title: 'User:BlockedALot',
							params: {
								duration: '5 years',
								'duration-l10n': '5 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-25T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-25T14:30:51Z',
							parsedcomment: 'A reason'
						},
						{
							logid: 989,
							title: 'User:BlockedALot',
							params: {
								duration: '5 years',
								'duration-l10n': '5 years',
								flags: [
									'noautoblock'
								],
								sitewide: true,
								expiry: '2029-09-26T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-26T14:30:51Z',
							parsedcomment: 'A reason'
						}
					],
					blocks: []
				}
			}
		},
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:BadNameBlocked'
			},
			response: {
				query: {
					logevents: [],
					blocks: []
				}
			}
		},
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:NonexistentUser'
			},
			response: {
				query: {
					logevents: [],
					blocks: []
				}
			}
		},
		{
			params: {
				list: 'logevents',
				leaction: 'suppress/block',
				letitle: 'User:BadNameBlocked'
			},
			response: {
				query: {
					logevents: [
						{
							logid: 1030,
							ns: 2,
							title: 'User:BadNameBlocked',
							pageid: 583,
							logpage: 583,
							params: {
								duration: 'infinity',
								'duration-l10n': 'infinite',
								flags: [ 'hiddenname' ],
								sitewide: true
							},
							type: 'suppress',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-11-14T07:30:00Z',
							comment: ''
						}
					]
				}
			}
		},
		{
			params: {
				list: 'logevents',
				leaction: 'suppress/reblock',
				letitle: 'User:BadNameBlocked'
			},
			response: {
				query: {
					logevents: [
						{
							logid: 1029,
							ns: 2,
							title: 'User:BadNameBlocked',
							pageid: 583,
							logpage: 583,
							params: {
								duration: 'infinity',
								'duration-l10n': 'infinite',
								flags: [ 'hiddenname' ],
								sitewide: true
							},
							type: 'suppress',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-11-14T07:29:00Z',
							comment: ''
						},
						{
							logid: 1031,
							ns: 2,
							title: 'User:BadNameBlocked',
							pageid: 583,
							logpage: 583,
							params: {
								duration: 'infinity',
								'duration-l10n': 'infinite',
								flags: [ 'hiddenname' ],
								sitewide: true
							},
							type: 'suppress',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-11-14T07:31:00Z',
							comment: ''
						}
					]
				}
			}
		},
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:PartiallyBlockedUser'
			},
			response: {
				query: {
					blocks: [
						{
							id: 1111,
							user: 'PartiallyBlockedUser',
							by: 'Admin',
							timestamp: '2024-09-17T14:30:51Z',
							expiry: '2094-09-17T14:30:51Z',
							'duration-l10n': '50 years',
							reason: 'Vandalizing on [[Project:Foobar]]',
							anononly: false,
							nocreate: false,
							autoblock: true,
							noemail: false,
							hidden: false,
							allowusertalk: true,
							partial: true,
							restrictions: {
								pages: [ {
									id: 50,
									ns: 4,
									title: 'Foobar'
								} ],
								actions: [ 'upload', 'create' ]
							}
						}
					],
					logevents: [
						{
							logid: 990,
							title: 'User:PartiallyBlockedUser',
							params: {
								duration: '50 years',
								'duration-l10n': '50 years',
								flags: [],
								restrictions: {
									pages: [ {
										// eslint-disable-next-line camelcase
										page_ns: 4,
										// eslint-disable-next-line camelcase
										page_title: 'Foobar'
									} ],
									actions: [ 'upload', 'create' ]
								},
								blockId: 1111,
								sitewide: false,
								expiry: '2094-09-17T14:30:51Z'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2024-09-17T14:30:51Z',
							parsedcomment: 'Vandalizing on [[Foobar]]'
						}
					]
				}
			}
		},
		// IP with range blocks (T389987)
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:1.2.3.20'
			},
			response: {
				query: {
					blocks: [
						{
							id: 2000,
							user: '1.2.3.20',
							by: 'Admin',
							timestamp: '2025-03-31T23:40:04Z',
							expiry: '2025-04-03T23:40:04Z',
							'duration-l10n': '3 days',
							reason: 'Vandalism',
							parsedreason: 'Vandalism',
							automatic: false,
							anononly: true,
							nocreate: true,
							autoblock: false,
							noemail: false,
							hidden: false,
							allowusertalk: false,
							partial: false,
							restrictions: []
						}, {
							id: 2001,
							user: '1.2.0.0/18',
							by: 'Admin',
							timestamp: '2025-03-31T23:24:54Z',
							expiry: '2025-10-01T23:24:54Z',
							'duration-l10n': '6 months',
							reason: 'Spamming links to external sites',
							parsedreason: 'Spamming links to external sites',
							rangestart: '1.2.0.0',
							rangeend: '1.2.63.255',
							automatic: false,
							anononly: true,
							nocreate: true,
							autoblock: false,
							noemail: false,
							hidden: false,
							allowusertalk: true,
							partial: false,
							restrictions: []
						}, {
							id: 2002,
							user: '1.2.3.20/30',
							by: 'Admin',
							timestamp: '2025-03-31T23:22:16Z',
							expiry: '2027-03-01T23:22:16Z',
							'duration-l10n': '1 year, 10 months and 29 days',
							reason: 'Inserting false information',
							parsedreason: 'Inserting false information',
							rangestart: '1.2.3.20',
							rangeend: '1.2.3.23',
							automatic: false,
							anononly: true,
							nocreate: true,
							autoblock: false,
							noemail: false,
							hidden: false,
							allowusertalk: true,
							partial: false,
							restrictions: []
						}
					],
					logevents: [
						{
							logid: 3000,
							ns: 2,
							title: 'User:1.2.3.20',
							pageid: 0,
							logpage: 0,
							params: {
								duration: '3 days',
								flags: [
									'anononly',
									'nocreate',
									'nousertalk'
								],
								blockId: 827,
								sitewide: true,
								expiry: '2025-04-03T23:40:04Z',
								'duration-l10n': '3 days'
							},
							type: 'block',
							action: 'block',
							user: 'Admin',
							timestamp: '2025-03-31T23:40:04Z',
							parsedcomment: 'Unacceptable username'
						}
					]
				}
			}
		},
		{
			params: {
				list: 'logevents|blocks',
				letype: 'block',
				letitle: 'User:192.168.0.1'
			},
			response: {
				query: {
					blocks: [],
					logevents: []
				}
			}
		},
		// Used in UserLookup
		{
			params: {
				list: 'allusers',
				auprefix: 'ExampleUser'
			},
			response: {
				query: {
					allusers: [
						{ name: 'ExampleUser' },
						{ name: 'ExampleUser2' }
					]
				}
			}
		},
		{
			params: {
				list: 'allusers',
				auprefix: 'NonexistentUser'
			},
			response: {
				query: {
					allusers: []
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

mw.Title.makeTitle = jest.fn().mockReturnValue( {
	getUrl: jest.fn()
} );

module.exports = {
	getSpecialBlock,
	mockMwApiGet,
	mockMwConfigGet
};
