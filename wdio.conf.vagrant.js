/* eslint no-undef: "error"*/
/* eslint-env node*/
var merge = require( 'deepmerge' ),
	wdioConf = require( './wdio.conf.jenkins.js' );
// have main config file as default but overwrite environment specific information
exports.config = merge( wdioConf.config, {
	// Saves a screenshot to a given path if a command fails.
	screenshotPath: './errorShots/',
	//
	// Set a base URL in order to shorten url command calls. If your url parameter starts
	// with "/", then the base url gets prepended.
	baseUrl: 'http://127.0.0.1:8080/w',
	//
	// Test reporter for stdout.
	// The only one supported by default is 'dot'
	// see also: http://webdriver.io/guide/testrunner/reporters.html
	reporters: [ 'spec' ]
} );
