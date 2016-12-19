/* eslint no-undef: "error"*/
/* eslint-env node*/
var merge = require( 'deepmerge' ),
	wdioConf = require( './wdio.conf.jenkins.js' );
// have main config file as default but overwrite environment specific information
exports.config = merge( wdioConf.config, {
	// Set a base URL in order to shorten url command calls. If your url parameter starts
	// with "/", then the base url gets prepended.
	baseUrl: 'http://127.0.0.1:8080/w'
} );
