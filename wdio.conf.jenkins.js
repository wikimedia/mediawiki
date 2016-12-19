/* eslint no-undef: "error"*/
/* eslint-env node*/
'use strict';
var merge = require( 'deepmerge' ),
	wdioConf = require( './wdio.conf.vagrant.js' );
// have main config file as default but overwrite environment specific information
exports.config = merge( wdioConf.config, {
	// Define any custom variables.
	username: 'WikiAdmin',
	password: 'testpass',
	//
	// Saves a screenshot to a given path if a command fails.
	screenshotPath: '../log/',
	//
	// Set a base URL in order to shorten url command calls. If your url parameter starts
	// with "/", then the base url gets prepended.
	baseUrl: process.env.MW_SERVER + process.env.MW_SCRIPT_PATH,
	//
	// Test reporter for stdout.
	// The only one supported by default is 'dot'
	// see also: http://webdriver.io/guide/testrunner/reporters.html
	reporters: [ 'spec', 'junit' ],
	reporterOptions: {
		junit: {
			outputDir: '../log/'
		}
	}
} );
