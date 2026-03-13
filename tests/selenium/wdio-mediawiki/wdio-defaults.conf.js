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
import path from 'path';
import { getChromeOptions } from './chromeOptions.js';
import { setupProcessHandlers } from './processHandlers.js';
import { PrometheusFileReporter, writeAllProjectMetrics } from './PrometheusFileReporter.js';
const logPath = process.env.LOG_DIR || path.join( process.cwd(), 'tests/selenium/log' );

import { logBrowserInformation, logSystemInformation, makeFilenameDate, saveScreenshot, setDisplay, startVideo, stopVideo, startXvfb, stopXvfb } from 'wdio-mediawiki';

let xvfbProcesses = [];

if ( !process.env.MW_SERVER || !process.env.MW_SCRIPT_PATH ) {
	throw new Error( 'MW_SERVER or MW_SCRIPT_PATH not defined.\nSee https://www.mediawiki.org/wiki/Selenium/How-to/Set_environment_variables\n' );
}

setupProcessHandlers();

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

	maxInstances: process.env.CI ? 6 : 1,
	// Make sure wdio do not try to start XVFB (we do that ourselves when needed)
	autoXvfb: false,
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

		// Browser width and height
		'mw:width': 1280,
		'mw:height': 1024,
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
		'goog:chromeOptions': getChromeOptions( Boolean( process.env.CI ) )
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
	// By default we do not record videos and you can turn it on in CI
	// Make sure to add it to true and change useBrowserHeadless to false
	recordVideo: false,
	// Always use headless in CI (if you do not override it),
	// DISPLAY= forces headless and DISPLAY=<anything> forces non headless.
	// When DISPLAY is not set locally, defaults to headless.
	useBrowserHeadless: Boolean( process.env.CI ) ||
		!process.env.DISPLAY,
	// Only take screenshots on test failures. Setting this to false will take screenshots
	// independently if a test works or fail
	screenshotsOnFailureOnly: true,
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
	 * Gets executed once before all workers get launched.
	 *
	 * @param {Object} wdioConfig wdio configuration object
	 * @param {Object[]} capabilities
	 */
	onPrepare: function ( wdioConfig, capabilities ) {
		console.log( `Run test targeting ${ wdioConfig.baseUrl }` );
		logSystemInformation();
		console.log( `[Configuration] maxInstances ${ wdioConfig.maxInstances } (max tests running in parallel) ` );

		const { maxInstances, useBrowserHeadless, recordVideo } = wdioConfig;

		// When we pass on as CLI the parameters will be strings
		const isHeadless = useBrowserHeadless === true || useBrowserHeadless === 'true';
		const isRecording = recordVideo === true || recordVideo === 'true';

		if ( !isHeadless && isRecording ) {
			xvfbProcesses = startXvfb( maxInstances, capabilities[ 0 ][ 'mw:width' ], capabilities[ 0 ][ 'mw:height' ] );
		}
	},

	/**
	 * Gets executed just before initializing the webdriver session and test framework.
	 * It allows you to manipulate configurations depending on the capability or spec.
	 *
	 * @param {Object} configuration wdio configuration object
	 * @param {Array.<Object>} capabilities list of capabilities details
	 */
	beforeSession: function ( configuration, capabilities ) {
		const { useBrowserHeadless, recordVideo } = configuration;
		// When we pass on as CLI the parameters will be strings
		const isHeadless = useBrowserHeadless === true || useBrowserHeadless === 'true';
		const isRecording = recordVideo === true || recordVideo === 'true';
		if ( isHeadless === true ) {
			capabilities[ 'goog:chromeOptions' ].args.push( '--headless' );
		} else if ( isRecording === true ) {
			setDisplay( configuration.maxInstances );
		}
	},

	/**
	 * Gets executed before test execution begins. At this point you can access to all global
	 * variables like `browser`. It is the perfect place to define custom commands.
	 *
	 * @param {Array.<Object>} capabilities list of capabilities details
	 * @param {Array.<string>} specs        List of spec file paths that are to be run
	 * @param {Object}         browser      instance of created browser/device session
	 */
	before: async function ( capabilities, specs, browser ) {
		await browser.setWindowSize( browser.options.capabilities[ 'mw:width' ], browser.options.capabilities[ 'mw:height' ] );
		await logBrowserInformation( browser );
	},

	/**
	 * Executed before a Mocha test starts.
	 *
	 * @param {Object} test Mocha Test object
	 */
	beforeTest: async function ( test ) {
		if ( browser.options.recordVideo === true ) {
			ffmpeg = await startVideo( ffmpeg, `${ test.parent }-${ test.title }` );
		}
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
			const hasFailed = result?.passed === false || Boolean( result?.error );
			if ( browser.options.screenshotsOnFailureOnly !== true || hasFailed ) {
				await saveScreenshot( `${ test.parent }-${ test.title }${ hasFailed ? '-failed' : '' }` );
			}
		} finally {
			if ( browser.options.recordVideo === true ) {
				stopVideo( ffmpeg );
			}
		}
	},

	/**
	 * Executed after all runners are done.
	 */
	onComplete() {
		stopXvfb( xvfbProcesses );
		const random = Math.random().toString( 16 ).slice( 2, 10 );
		const fileName = `project-metrics-${ makeFilenameDate() }-${ random }`;
		writeAllProjectMetrics( logPath, fileName );
	}
};
