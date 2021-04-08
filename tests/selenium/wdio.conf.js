'use strict';

require( 'dotenv' ).config();
const fs = require( 'fs' );
const path = require( 'path' );
const video = require( 'wdio-video-reporter' );
const logPath = process.env.LOG_DIR || path.join( __dirname, '/log' );

// get current test title and clean it, to use it as file name
function fileName( title ) {
	return encodeURIComponent( title.replace( /\s+/g, '-' ) );
}

// build file path
function filePath( test, screenshotPath, extension ) {
	return path.join( screenshotPath, `${fileName( test.parent )}-${fileName( test.title )}.${extension}` );
}

/**
 * For more details documentation and available options,
 * see <https://webdriver.io/docs/configurationfile.html>
 * and <https://webdriver.io/docs/options.html>.
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
	// Defaults are for MediaWiki-Vagrant
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
		'./tests/selenium/wdio-mediawiki/specs/*.js',
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
	// Stop after this many failures, or 0 to run all tests before reporting failures.
	bail: 0,
	// Base for browser.url() and wdio-mediawiki/Page#openTitle()
	baseUrl: ( process.env.MW_SERVER ) + (
		process.env.MW_SCRIPT_PATH
	),
	// See also: https://webdriver.io/docs/frameworks.html
	framework: 'mocha',
	// See also: https://webdriver.io/docs/dot-reporter.html
	reporters: [
		'dot',
		// See also: https://webdriver.io/docs/junit-reporter.html#configuration
		[ 'junit', {
			outputDir: logPath
		} ],
		[
			video, {
				saveAllVideos: true,
				outputDir: logPath
			}
		]
	],
	// See also: http://mochajs.org/
	mochaOpts: {
		ui: 'bdd',
		timeout: 60 * 1000
	},

	/**
	 * Executed after a Mocha test ends.
	 *
	 * @param {Object} test Mocha Test object
	 */
	afterTest: function ( test ) {
		// if test passed, ignore, else take and save screenshot
		if ( test.passed ) {
			return;
		}
		// save screenshot
		const screenshotfile = filePath( test, logPath, 'png' );
		browser.saveScreenshot( screenshotfile );
		console.log( '\n\tScreenshot location:', screenshotfile, '\n' );
	}
};
