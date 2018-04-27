'use strict';
const merge = require( 'deepmerge' ),
	wdioConf = require( './wdio.conf.js' );

function overwriteMerge( destinationArray, sourceArray ) {
	return sourceArray;
}

// Overwrite default settings
exports.config = merge(
	wdioConf.config,
	{ exclude: [] },
	{ arrayMerge: overwriteMerge }
);
