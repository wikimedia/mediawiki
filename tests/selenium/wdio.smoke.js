'use strict';
const merge = require( 'deepmerge' ),
	// Use copy merge instead of plain inherit because webdriverio's
	// config parser only reads *own* properties.
	smokeConfig = merge( {}, require( './wdio.conf.js' ).config );

// Overwrite default settings
smokeConfig.exclude = [];

exports.config = smokeConfig;