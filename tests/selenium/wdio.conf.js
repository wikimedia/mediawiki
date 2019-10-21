/* eslint-disable no-console */
const fs = require( 'fs' );
const path = require( 'path' );
const startChromedriver = !process.argv.includes( '--skip-chromedriver' );
const logPath = process.env.LOG_DIR || path.join( __dirname, '/log' );

let ffmpeg;

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
	// ======
	// Custom conf keys for MediaWiki
	//
	// Access via `browser.config.<key>`.
	// Defaults are for MediaWiki-Vagrant
	// ======
	mwUser: process.env.MEDIAWIKI_USER || 'Admin',
	mwPwd: process.env.MEDIAWIKI_PASSWORD || 'vagrant',

	// ==================
	// Runner Configuration
	// ==================
	runner: 'local',
	// The standalone chromedriver (also used by WMF CI) uses "/wd/hub".
	// The one provided by wdio uses "/".
	path: startChromedriver ? '/' : '/wd/hub',

	// ======
	// Sauce Labs
	// ======
	// See http://webdriver.io/guide/services/sauce.html
	// and https://github.com/bermi/sauce-connect-launcher#advanced-usage
	user: process.env.SAUCE_USERNAME,
	key: process.env.SAUCE_ACCESS_KEY,
	sauceConnect: true,

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
	baseUrl: ( process.env.MW_SERVER || 'http://127.0.0.1:8080' ) + (
		process.env.MW_SCRIPT_PATH || '/w'
	),
	services: [
		...( startChromedriver ? [ 'chromedriver' ] : [] ),
		...( process.env.SAUCE_ACCESS_KEY ? [ 'sauce' ] : [] )
	],
	// See also: https://webdriver.io/docs/frameworks.html
	framework: 'mocha',
	// See also: https://webdriver.io/docs/dot-reporter.html
	reporters: [
		'dot',
		// See also: https://webdriver.io/docs/junit-reporter.html#configuration
		[ 'junit', {
			outputDir: logPath
		} ]
	],
	// See also: http://mochajs.org/
	mochaOpts: {
		ui: 'bdd',
		timeout: 60 * 1000
	},

	// =====
	// Hooks
	// =====
	/**
	 * Executed before a Mocha test starts.
	 * @param {Object} test Mocha Test object
	 */
	beforeTest: function ( test ) {
		if ( process.env.DISPLAY && process.env.DISPLAY.startsWith( ':' ) ) {
			const videoPath = filePath( test, logPath, 'mp4' );
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

			const logBuffer = function ( buffer, prefix ) {
				const lines = buffer.toString().trim().split( '\n' );
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
	 * Executed after a Mocha test ends.
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
		const screenshotfile = filePath( test, logPath, 'png' );
		browser.saveScreenshot( screenshotfile );
		console.log( '\n\tScreenshot location:', screenshotfile, '\n' );
	}
};
