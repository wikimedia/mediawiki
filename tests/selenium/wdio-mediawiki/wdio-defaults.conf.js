/**
 * Base WebdriverIO configuration, meant to be imported from skins and extensions like so:
 *
 *   const { config } = require( 'wdio-mediawiki/wdio-defaults.conf.js' );
 *
 *   exports.config = { ...config,
 *     logLevel: 'info'
 *   };
 */

'use strict';

let ffmpeg;
const fs = require( 'fs' );
const path = require( 'path' );
const logPath = process.env.LOG_DIR || path.join( process.cwd(), 'tests/selenium/log' );
const { makeFilenameDate, saveScreenshot, startVideo, stopVideo } = require( 'wdio-mediawiki' );
// T355556: remove when T324766 is resolved
const dns = require( 'dns' );

if ( !process.env.MW_SERVER || !process.env.MW_SCRIPT_PATH ) {
	throw new Error( 'MW_SERVER or MW_SCRIPT_PATH not defined.\nSee https://www.mediawiki.org/wiki/Selenium/How-to/Set_environment_variables\n' );
}

process.on( 'uncaughtException', ( error ) => {
	console.error( 'Caught uncaughtException: ', error );
	// eslint-disable-next-line n/no-process-exit
	process.exit( 1 );
} );

process.on( 'unhandledRejection', ( reason, promise ) => {
	console.log( 'Unhandled Rejection at:', promise, 'reason:', reason );
} );

[ 'SIGINT', 'SIGTERM' ].forEach( ( signal ) => process.on( signal, () => {
	// eslint-disable-next-line no-underscore-dangle
	console.log( `Received ${ signal }. Active handles:`, process._getActiveHandles() );
} )
);

/**
 * For more details documentation and available options:
 * - https://webdriver.io/docs/configurationfile
 * - https://webdriver.io/docs/configuration
 */
exports.config = {
	// ======
	// Custom conf keys for MediaWiki
	//
	// Access via `browser.config.<key>`.
	// Defaults are for MediaWiki-Docker
	// ======
	mwUser: process.env.MEDIAWIKI_USER,
	mwPwd: process.env.MEDIAWIKI_PASSWORD,

	// ==================
	// Runner Configuration
	// ==================
	runner: 'local',

	// ==================
	// Test Files
	// ==================
	specs: [
		'./tests/selenium/specs/**/*.js'
	],

	// ============
	// Capabilities
	// Define the different browser configurations to use ("capabilities") here.
	// ============

	maxInstances: 1,
	capabilities: [ {
		// For Chrome/Chromium https://www.w3.org/TR/webdriver
		browserName: 'chrome',
		'goog:chromeOptions': {
			// If DISPLAY is set, assume developer asked non-headless or CI with Xvfb.
			// Otherwise, use --headless.
			args: [
				// Dismissed Chrome's `Save password?` popup
				'--enable-automation',
				...( process.env.DISPLAY ? [] : [ '--headless' ] ),
				// Chrome sandbox does not work in Docker. Disable GPU to prevent crashes (T389536#10677201)
				...( fs.existsSync( '/.dockerenv' ) ? [ '--no-sandbox', '--disable-gpu' ] : [] ),
				// Workaround inputs not working consistently post-navigation on Chrome 90
				// https://issuetracker.google.com/issues/42322798
				'--allow-pre-commit-input'
			]
		}
	} ],

	// ===================
	// Test Configurations
	// Define all options that are relevant for the WebdriverIO instance here
	// ===================

	// Level of logging verbosity: trace | debug | info | warn | error | silent
	logLevel: 'error',
	// Setting this enables automatic screenshots for when a browser command fails
	// It is also used by afterTest for capturing screenshots.
	screenshotPath: logPath,
	// Stop after this many failures, or 0 to run all tests before reporting failures.
	bail: 0,
	// Base for browser.url() and wdio-mediawiki/Page#openTitle()
	baseUrl: process.env.MW_SERVER + process.env.MW_SCRIPT_PATH,
	// See also: https://webdriver.io/docs/frameworks
	framework: 'mocha',
	// See also: https://mochajs.org
	// The number of times to retry the entire specfile when it fails as a whole
	specFileRetries: 1,
	// Delay in seconds between the spec file retry attempts
	specFileRetriesDelay: 0,
	// Whether or not retried specfiles should be retried immediately or deferred to the end of the
	// queue
	specFileRetriesDeferred: false,
	mochaOpts: {
		ui: 'bdd',
		timeout: process.env.DEBUG ? ( 60 * 60 * 1000 ) : ( 60 * 1000 )
	},
	// See also: https://webdriver.io/docs/dot-reporter
	reporters: [
		// See also: https://webdriver.io/docs/spec-reporter
		'spec',
		// See also: https://webdriver.io/docs/junit-reporter
		[ 'junit', {
			outputDir: logPath,
			outputFileFormat: function () {
				const random = Math.random().toString( 16 ).slice( 2, 10 );
				return `WDIO.xunit-${ makeFilenameDate() }-${ random }.xml`;
			}
		} ]
	],

	// =====
	// Hooks
	// =====

	/**
	 * Gets executed just before initializing the webdriver session and test framework.
	 * It allows you to manipulate configurations depending on the capability or spec.
	 *
	 * @param {Object} config wdio configuration object
	 * @param {Array.<Object>} capabilities list of capabilities details
	 * @param {Array.<string>} specs List of spec file paths that are to be run
	 */
	// T355556: remove when T324766 is resolved
	beforeSession: function () {
		// eslint-disable-next-line n/no-unsupported-features/node-builtins
		dns.setDefaultResultOrder( 'ipv4first' );
	},

	/**
	 * Executed before a Mocha test starts.
	 *
	 * @param {Object} test Mocha Test object
	 */
	beforeTest: function ( test ) {
		ffmpeg = startVideo( ffmpeg, `${ test.parent }-${ test.title }` );
	},

	/**
	 * Executed after a Mocha test ends.
	 *
	 * @param {Object} test Mocha Test object
	 * @param {Object} context scope object the test was executed with
	 * @param {Object} result hook result
	 */
	afterTest: async function ( test, context, result ) {
		try {
			await saveScreenshot( `${ test.parent }-${ test.title }${ result.passed ? '' : '-failed' }` );
		} finally {
			stopVideo( ffmpeg );
		}
	}
};
