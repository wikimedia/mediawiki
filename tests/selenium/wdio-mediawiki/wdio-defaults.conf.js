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
import { makeFilenameDate, saveScreenshot, startVideo, stopVideo, logSystemInformation, logBrowserInformation } from 'wdio-mediawiki';

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

		// Setting that enables video recording of the test.
		// Recording videos is currently supported only on Linux,
		// and is triggered by the DISPLAY value starting with a colon.
		// https://www.mediawiki.org/wiki/Selenium/How-to/Record_videos_of_test_runs
		'mw:recordVideo': true,

		// Browser width and height
		'mw:width': 1280,
		'mw:height': 1024,
		// If DISPLAY is setup, default usage is to not use browser headless
		'mw:useBrowserHeadless': !process.env.DISPLAY,
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
				// Chrome sandbox does not work in Docker. Disable GPU to prevent crashes (T389536#10677201)
				// For disable-dev-shm-usage: We map /tmp to tmpfs for the container in CI
				...( fs.existsSync( '/.dockerenv' ) ? [ '--no-sandbox', '--disable-gpu', '--disable-dev-shm-usage' ] : [] ),
				// Disable as much as possible to make Chrome clean
				// https://github.com/GoogleChrome/chrome-launcher/blob/main/docs/chrome-flags-for-tools.md
				// https://peter.sh/experiments/chromium-command-line-switches/
				'--ash-no-nudges',
				'--disable-background-networking',
				'--disable-background-timer-throttling',
				'--disable-backgrounding-occluded-windows',
				'--disable-breakpad',
				'--disable-client-side-phishing-detection',
				'--disable-component-extensions-with-background-page',
				'--disable-component-update',
				'--disable-default-apps',
				'--disable-domain-reliability',
				'--disable-features=InterestFeedContentSuggestions',
				'--disable-features=Translate',
				'--disable-fetching-hints-at-navigation-start',
				'--disable-hang-monitor',
				'--disable-infobars',
				'--disable-ipc-flooding-protection',
				'--disable-prompt-on-repost',
				'--disable-renderer-backgrounding',
				'--disable-sync',
				'--disable-search-engine-choice-screen',
				'--disable-site-isolation-trials',
				'--mute-audio',
				'--no-default-browser-check',
				'--no-first-run',
				'--propagate-iph-for-testing',
				// Workaround inputs not working consistently post-navigation on Chrome 90
				// https://issuetracker.google.com/issues/42322798
				'--allow-pre-commit-input',
				// To disable save password popup together with prefs
				'--password-store=basic'
			],
			prefs: {
				// These setting disable the password save popup together
				// with --password-store=basic.
				// eslint-disable-next-line camelcase
				credentials_enable_service: false,
				'profile.password_manager_enabled': false
			},
			excludeSwitches: [
				'enable-automation'
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
	 * Gets executed once before all workers get launched.
	 *
	 * @param {Object} wdioConfig wdio configuration object
	 */
	onPrepare: function ( wdioConfig ) {
		console.log( `Run test targeting ${ wdioConfig.baseUrl }` );
		logSystemInformation();
	},
	/**
	 * Gets executed just before initializing the webdriver session and test framework.
	 * It allows you to manipulate configurations depending on the capability or spec.
	 *
	 * @param {Object} configuration wdio configuration object
	 * @param {Array.<Object>} capabilities list of capabilities details
	 */
	beforeSession: function ( configuration, capabilities ) {
		const useBrowserHeadless = capabilities[ 'mw:useBrowserHeadless' ];
		if ( useBrowserHeadless === true ) {
			capabilities[ 'goog:chromeOptions' ].args.push( '--headless' );
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
		if ( browser.options.capabilities[ 'mw:recordVideo' ] === true ) {
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
			await saveScreenshot( `${ test.parent }-${ test.title }${ result.passed ? '' : '-failed' }` );
		} finally {
			if ( browser.options.capabilities[ 'mw:recordVideo' ] === true ) {
				stopVideo( ffmpeg );
			}
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
