'use strict';

const { config } = require( '@vue/test-utils' );

// Load jQuery
const jquery = require( '../../resources/lib/jquery/jquery.js' );
global.$ = jquery;

/**
 * Mock for the calls to Core's $i18n plugin which returns a mw.Message object.
 *
 * @param {string} key The key of the message to parse.
 * @param {...*} args Arbitrary number of arguments to be parsed.
 * @return {Object} mw.Message-like object with .text() and .parse() methods.
 */
function $i18nMock( key, ...args ) {
	function serializeArgs() {
		return args.length ? `${ key }:[${ args.join( ',' ) }]` : key;
	}
	return {
		text: () => serializeArgs(),
		parse: () => serializeArgs()
	};
}
// Mock Vue plugins in test suites.
config.global.provide = {
	i18n: $i18nMock
};
config.global.mocks = {
	$i18n: $i18nMock
};
config.global.directives = {
	'i18n-html': ( el, binding ) => {
		el.innerHTML = `${ binding.arg } (${ binding.value })`;
	}
};

function ApiMock() {}
ApiMock.prototype.abort = jest.fn();
ApiMock.prototype.get = jest.fn();
ApiMock.prototype.post = jest.fn();
ApiMock.prototype.postWithEditToken = jest.fn();
ApiMock.prototype.postWithToken = jest.fn();

function RestMock() {}
RestMock.prototype.get = jest.fn();

function TitleMock() {}
TitleMock.prototype.getMainText = jest.fn();
TitleMock.prototype.getNameText = jest.fn();
TitleMock.prototype.getUrl = jest.fn();

// Mock the mw global object.
const mw = {
	Api: ApiMock,
	log: {
		error: jest.fn(),
		warn: jest.fn()
	},
	config: {
		get: jest.fn()
	},
	hook: jest.fn().mockReturnValue( {
		add: jest.fn(),
		fire: jest.fn()
	} ),
	message: jest.fn( ( key ) => ( {
		text: jest.fn( () => key ),
		parse: jest.fn( () => key )
	} ) ),
	msg: jest.fn( ( key ) => key ),
	user: {
		getId: jest.fn(),
		getName: jest.fn(),
		isAnon: jest.fn().mockReturnValue( true ),
		options: {
			get: jest.fn()
		}
	},
	language: {
		convertNumber: jest.fn( ( x ) => x ),
		getFallbackLanguageChain: () => [ 'en' ]
	},
	Title: TitleMock,
	util: {
		debounce: jest.fn( ( fn ) => fn() ),
		getUrl: jest.fn( ( pageName ) => '/wiki/' + pageName ),
		isIPAddress: jest.fn(),
		isInfinity: jest.fn(),
		sanitizeIP: jest.fn(),
		getParamValue: jest.fn().mockReturnValue( null )
	},
	Rest: RestMock
	// Add more mw properties as needed…
};

// Assign things to "global" here if you want them to be globally available during Jest tests.
global.mw = mw;
