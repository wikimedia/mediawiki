const fs = require( 'fs' ),
	path = require( 'path' ),
	logPath = process.env.LOG_DIR || path.join( __dirname, '/log' );

let ffmpeg;

// get current test title and clean it, to use it as file name
function fileName( title ) {
	return encodeURIComponent( title.replace( /\s+/g, '-' ) );
}

// build file path
function filePath( test, screenshotPath, extension ) {
	return path.join( screenshotPath, `${fileName( test.parent )}-${fileName( test.title )}.${extension}` );
}

// relative path
function relPath( foo ) {
	return path.resolve( __dirname, '../..', foo );
}

exports.config = {
	// ======
	// Custom WDIO config specific to MediaWiki
	// ======
	// Use in a test as `browser.options.<key>`.
	// Defaults are for convenience with MediaWiki-Vagrant

	// Wiki admin
	username: process.env.MEDIAWIKI_USER || 'Admin',
	password: process.env.MEDIAWIKI_PASSWORD || 'vagrant',

	// Base for browser.url() and Page#openTitle()
	baseUrl: ( process.env.MW_SERVER || 'http://127.0.0.1:8080' ) + (
		process.env.MW_SCRIPT_PATH || '/w'
	),

	// ======
	// Sauce Labs
	// ======
	// See http://webdriver.io/guide/services/sauce.html
	// and https://docs.saucelabs.com/reference/platforms-configurator
	services: [ 'sauce' ],
	user: process.env.SAUCE_USERNAME,
	key: process.env.SAUCE_ACCESS_KEY,

	// Default timeout in milliseconds for Selenium Grid requests
	connectionRetryTimeout: 90 * 1000,

	// Default request retries count
	connectionRetryCount: 3,

	// ==================
	// Test Files
	// ==================
	specs: [
		relPath( './tests/selenium/wdio-mediawiki/specs/*.js' ),
		relPath( './tests/selenium/specs/**/*.js' ),
		relPath( './extensions/*/tests/selenium/specs/**/*.js' ),
		relPath( './extensions/VisualEditor/modules/ve-mw/tests/selenium/specs/**/*.js' ),
		relPath( './extensions/Wikibase/repo/tests/selenium/specs/**/*.js' ),
		relPath( './skins/*/tests/selenium/specs/**/*.js' )
	],
	// Patterns to exclude
	exclude: [
		relPath( './extensions/CirrusSearch/tests/selenium/specs/**/*.js' )
	],

	// ============
	// Capabilities
	// ============

	// How many instances of the same capability (browser) may be started at the same time.
	maxInstances: 1,

	capabilities: [ {
		// For Chrome/Chromium https://sites.google.com/a/chromium.org/chromedriver/capabilities
		browserName: 'chrome',
		maxInstances: 1,
		chromeOptions: {
			// If DISPLAY is set, assume developer asked non-headless or CI with Xvfb.
			// Otherwise, use --headless (added in Chrome 59)
			// https://chromium.googlesource.com/chromium/src/+/59.0.3030.0/headless/README.md
			args: [
				...( process.env.DISPLAY ? [] : [ '--headless' ] ),
				// Chrome sandbox does not work in Docker
				...( fs.existsSync( '/.dockerenv' ) ? [ '--no-sandbox' ] : [] )
			]
		}
	} ],

	// ===================
	// Test Configurations
	// ===================

	// Enabling synchronous mode (via the wdio-sync package), means specs don't have to
	// use Promise#then() or await for browser commands, such as like `brower.element()`.
	// Instead, it will automatically pause JavaScript execution until th command finishes.
	//
	// For non-browser commands (such as MWBot and other promises), this means you
	// have to use `browser.call()` to make sure WDIO waits for it before the next
	// browser command.
	sync: true,

	// Level of logging verbosity: silent | verbose | command | data | result | error
	logLevel: 'error',

	// Enables colors for log output.
	coloredLogs: true,

	// Warns when a deprecated command is used
	deprecationWarnings: true,

	// Stop the tests once a certain number of failed tests have been recorded.
	// Default is 0 - don't bail, run all tests.
	bail: 0,

	// Setting this enables automatic screenshots for when a browser command fails
	// It is also used by afterTest for capturig failed assertions.
	// We disable it since we have our screenshot handler in the afterTest hook.
	screenshotPath: null,

	// Default timeout for each waitFor* command.
	waitforTimeout: 10 * 1000,

	// Framework you want to run your specs with.
	// See also: http://webdriver.io/guide/testrunner/frameworks.html
	framework: 'mocha',

	// Test reporter for stdout.
	// See also: http://webdriver.io/guide/testrunner/reporters.html
	reporters: [ 'spec', 'junit' ],
	reporterOptions: {
		junit: {
			outputDir: logPath
		}
	},

	// Options to be passed to Mocha.
	// See the full list at http://mochajs.org/
	mochaOpts: {
		ui: 'bdd',
		timeout: 60 * 1000
	},

	// =====
	// Hooks
	// =====
	// See also: http://webdriver.io/guide/testrunner/configurationfile.html

	/**
	* Function to be executed before a test (in Mocha/Jasmine) or a step (in Cucumber) starts.
	* @param {Object} test test details
	*/
	beforeTest: function ( test ) {
		if ( process.env.DISPLAY && process.env.DISPLAY.startsWith( ':' ) ) {
			var logBuffer;
			let videoPath = filePath( test, logPath, 'mp4' );
			const { spawn } = require( 'child_process' );
			ffmpeg = spawn( 'ffmpeg', [
				'-f', 'x11grab', //  grab the X11 display
				'-video_size', '1280x1024', // video size
				'-i', process.env.DISPLAY, // input file url
				'-loglevel', 'error', // log only errors
				'-y', // overwrite output files without asking
				'-pix_fmt', 'yuv420p', // QuickTime Player support, "Use -pix_fmt yuv420p for compatibility with outdated media players"
				videoPath // output file
			] );

			logBuffer = function ( buffer, prefix ) {
				let lines = buffer.toString().trim().split( '\n' );
				lines.forEach( function ( line ) {
					console.log( prefix + line );
				} );
			};

			ffmpeg.stdout.on( 'data', ( data ) => {
				logBuffer( data, 'ffmpeg stdout: ' );
			} );

			ffmpeg.stderr.on( 'data', ( data ) => {
				logBuffer( data, 'ffmpeg stderr: ' );
			} );

			ffmpeg.on( 'close', ( code, signal ) => {
				console.log( '\n\tVideo location:', videoPath, '\n' );
				if ( code !== null ) {
					console.log( `\tffmpeg exited with code ${code} ${videoPath}` );
				}
				if ( signal !== null ) {
					console.log( `\tffmpeg received signal ${signal} ${videoPath}` );
				}
			} );
		}
	},

	/**
	 * Save a screenshot when test fails.
	 *
	 * @param {Object} test Mocha Test object
	 */
	afterTest: function ( test ) {
		if ( ffmpeg ) {
			// stop video recording
			ffmpeg.kill( 'SIGINT' );
		}

		// if test passed, ignore, else take and save screenshot
		if ( test.passed ) {
			return;
		}
		// save screenshot
		let screenshotfile = filePath( test, logPath, 'png' );
		browser.saveScreenshot( screenshotfile );
		console.log( '\n\tScreenshot location:', screenshotfile, '\n' );
	}
};
