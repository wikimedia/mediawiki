var path = require( 'path' );
var webpack = require( 'webpack' );
var PUBLIC_PATH = '';

module.exports = {
	output: {
		// The absolute path to the output directory.
		path: path.resolve( __dirname, 'resources/src/' ),
		devtoolModuleFilenameTemplate: PUBLIC_PATH + '/[resource-path]',

		// Write each chunk (entries, here) to a file named after the entry, e.g.
		// the "index" entry gets written to index.js.
		filename: '/[name].js'
	},
	entry: {
		'mediawiki/mediawiki': './build/mediawiki/index.js'
	},
	devtool: 'source-map'
};
