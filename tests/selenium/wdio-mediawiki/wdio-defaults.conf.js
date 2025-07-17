/**
 * Base WebdriverIO configuration, meant to be imported from skins and extensions like so:
 *
 *   import { config as wdioDefaults } from 'wdio-mediawiki/wdio-defaults.conf.js';
 *
 *   export const config = { ...wdioDefaults,
 *     logLevel: 'info',
 *   }
 */

let ffmpeg;
import fs from 'fs';
import path from 'path';
import { PrometheusFileReporter, writeAllProjectMetrics } from './PrometheusFileReporter.js';
const logPath = process.env.LOG_DIR || path.join( process.cwd(), 'tests/selenium/log' );
import { makeFilenameDate, saveScreenshot, startVideo, stopVideo } from 'wdio-mediawiki';
// T355556: remove when T324766 is resolved
import dns from 'dns';

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
export const config = {
	// ==================
	// Runner Configuration
	// ==================
	runner: 'local',

	// ==================
	// Test Files
	// ==================
	specs: [
		'./specs/**/*.js'
	],
	// Set the waitForTimeout for all wait for commands
	// https://v8.webdriver.io/docs/timeouts/#waitfor-timeout
	waitforTimeout: 10000,
	// ============
	// Capabilities
	// Define the different browser configurations to use ("capabilities") here.
	// ============

	maxInstances: 1,
	capabilities: [ {
		// ======
		// Custom conf keys for MediaWiki
		//
		// Access via `browser.options.<key>`.
		// Defaults are for MediaWiki-Docker
		// ======
		'mw:user': process.env.MEDIAWIKI_USER,
		'mw:pwd': process.env.MEDIAWIKI_PASSWORD,

		// Setting this enables automatic screenshots for when a browser command fails
		// It is also used by afterTest for capturing screenshots.
		'mw:screenshotPath': logPath,

		// For Chrome/Chromium https://www.w3.org/TR/webdriver
		browserName: 'chrome',
		// Use correct browser and driver in CI
		...( process.env.CI && {
			'wdio:chromedriverOptions': {
				binary: '/usr/bin/chromedriver'
			}
		} ),
		// Can be changed when we update to newer browser versions
		// Bidi is still under development in Chrome/Firefox
		'wdio:enforceWebDriverClassic': true,
		'goog:chromeOptions': {
			...( process.env.CI && {
				binary: '/usr/bin/chromium'
			} ),
			// If DISPLAY is set, assume developer asked non-headless or CI with Xvfb.
			// Otherwise, use --headless.
			args: [
				// Dismissed Chrome's `Save password?` popup
				'--enable-automation',
				...( process.env.DISPLAY ? [] : [ '--headless' ] ),
				// Chrome sandbox does not work in Docker. Disable GPU to prevent crashes (T389536#10677201)
				...( fs.existsSync( '/.dockerenv' ) ? [ '--no-sandbox', '--disable-gpu', '--disable-dev-shm-usage' ] : [] ),
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
		} ],
		[ PrometheusFileReporter, {
			outputDir: logPath,
			outputFileName: function () {
				const random = Math.random().toString( 16 ).slice( 2, 10 );
				return `WDIO.prometheus-${ makeFilenameDate() }-${ random }.prom`;
			},
			tags: {
				project: process.env.npm_package_name || process.env.ZUUL_PROJECT || 'test',
				// eslint-disable-next-line camelcase
				wdio_target: process.env.WDIO_TARGET || 'ci'
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
	beforeTest: async function ( test ) {
		ffmpeg = await startVideo( ffmpeg, `${ test.parent }-${ test.title }` );
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
	},

	/**
	 * Executed after all runners are done.
	 */
	onComplete() {
		const random = Math.random().toString( 16 ).slice( 2, 10 );
		const fileName = `project-metrics-${ makeFilenameDate() }-${ random }`;
		writeAllProjectMetrics( logPath, fileName );
	}
};
