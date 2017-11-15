/* eslint no-undef: "error" */
/* eslint-env node */
'use strict';
var merge = require( 'deepmerge' ),
	wdioConf = require( './wdio.conf.js' );

// Overwrite default settings
exports.config = merge( wdioConf.config, {
	username: 'WikiAdmin',
	password: 'testpass',
	screenshotPath: '../log/',
	baseUrl: process.env.MW_SERVER + process.env.MW_SCRIPT_PATH,

	reporters: [ 'spec', 'junit' ],
	reporterOptions: {
		junit: {
			outputDir: '../log/'
		}
	}
} );
