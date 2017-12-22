'use strict';
const merge = require( 'deepmerge' ),
	password = 'testpass',
	username = 'WikiAdmin',
	wdioConf = require( './wdio.conf.js' );

// Overwrite default settings
exports.config = merge( wdioConf.config, {
	username: process.env.MEDIAWIKI_USER === undefined ?
		username :
		process.env.MEDIAWIKI_USER,
	password: process.env.MEDIAWIKI_PASSWORD === undefined ?
		password :
		process.env.MEDIAWIKI_PASSWORD,
	screenshotPath: '../log/',
	baseUrl: process.env.MW_SERVER + process.env.MW_SCRIPT_PATH,

	reporters: [ 'spec', 'junit' ],
	reporterOptions: {
		junit: {
			outputDir: '../log/'
		}
	}
} );
