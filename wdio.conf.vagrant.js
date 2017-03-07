/* eslint no-undef: "error"*/
/* eslint-env node*/
'use strict';
var merge = require( 'deepmerge' ),
	wdioConf = require( './wdio.conf.default.js' );
exports.config = merge( wdioConf.config, {
	username: 'Admin',
	password: 'vagrant',
	screenshotPath: './log/',
	baseUrl: 'http://127.0.0.1:8080/w'
} );
