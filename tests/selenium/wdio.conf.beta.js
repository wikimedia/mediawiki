'use strict';
const merge = require( 'deepmerge' ),
	username = 'Selenium user',
	wdioConf = require( './wdio.conf.js' );

// Overwrite default settings
exports.config = merge( wdioConf.config, {
	username: process.env.MEDIAWIKI_USER === undefined ?
		username :
		process.env.MEDIAWIKI_USER,
	password: process.env.MEDIAWIKI_PASSWORD,
	baseUrl: (
		process.env.MW_SERVER === undefined ?
			'https://en.wikipedia.beta.wmflabs.org:443' :
			process.env.MW_SERVER
	) + (
		process.env.MW_SCRIPT_PATH === undefined ?
			'/w' :
			process.env.MW_SCRIPT_PATH
	)
} );
