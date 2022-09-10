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

if ( !process.env.MW_SERVER || !process.env.MW_SCRIPT_PATH ) {
	throw new Error( 'MW_SERVER or MW_SCRIPT_PATH not defined.\nSee https://www.mediawiki.org/wiki/Selenium/How-to/Set_environment_variables\n' );
}

/**
 * For more details documentation and available options:
 * - https://webdriver.io/docs/configurationfile/
 * - https://webdriver.io/docs/options/
 */
exports.config = {
	// ==================
	// Automation Protocols
	// ==================

	// See https://webdriver.io/docs/automationProtocols/
	automationProtocol: 'devtools',

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
		// For Chrome/Chromium https://sites.google.com/a/chromium.org/chromedriver/capabilities
		browserName: 'chrome',
		'goog:chromeOptions': {
			// If DISPLAY is set, assume developer asked non-headless or CI with Xvfb.
			// Otherwise, use --headless.
			args: [
				// Default to reasonably common (https://gs.statcounter.com/screen-resolution-stats/desktop/worldwide)
				// larger window size to avoid quirks with tests in smaller viewports.
				'window-size=1920,1080',
				// Dismissed Chrome's `Save password?` popup
				'--enable-automation',
				...( process.env.DISPLAY ? [] : [ '--headless' ] ),
				// Chrome sandbox does not work in Docker
				...( fs.existsSync( '/.dockerenv' ) ? [ '--no-sandbox' ] : [] )
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
	// See also: https://webdriver.io/docs/frameworks/
	framework: 'mocha',
	// See also: https://mochajs.org/
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
	// See also: https://webdriver.io/docs/dot-reporter.html
	reporters: [
		// See also: https://webdriver.io/docs/spec-reporter/
		'spec',
		// See also: https://webdriver.io/docs/junit-reporter/
		[ 'junit', {
			outputDir: logPath,
			outputFileFormat: function () {
				return `WDIO.xunit-${makeFilenameDate()}.xml`;
			}
		} ]
	],

	// =====
	// Hooks
	// =====

	/**
	 * Executed before a Mocha test starts.
	 *
	 * @param {Object} test Mocha Test object
	 */
	beforeTest: function ( test ) {
		ffmpeg = startVideo( ffmpeg, `${test.parent}-${test.title}` );
	},

	/**
	 * Executed after a Mocha test ends.
	 *
	 * @param {Object} test Mocha Test object
	 */
	afterTest: function ( test ) {
		saveScreenshot( `${test.parent}-${test.title}` );
		stopVideo( ffmpeg );
	}
};
