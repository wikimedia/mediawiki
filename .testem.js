
if ( !process.env.MW_SERVER ) {
	throw new Error(
		'Environment variable MW_SERVER must be set.\n' +
		'Set this like $wgServer, e.g. "http://localhost"'
	);
}
if ( !process.env.MW_SCRIPT_PATH ) {
	throw new Error(
		'Environment variable MW_SCRIPT_PATH must be set.\n' +
		'Set this like $wgScriptPath, e.g. "/w"'
	);
}

var wgServer = process.env.MW_SERVER,
	wgScriptPath = process.env.MW_SCRIPT_PATH,
	proxies = {};

proxies[ wgScriptPath ] = {
	target: wgServer,
	changeOrigin: true
};

module.exports = {
	// Testem uses https://github.com/nodejitsu/node-http-proxy#options
	proxies: proxies,
	// These are watched for automatic rerun
	src_files: [
		__filename
	],
	// Use a 'custom' test_page instead of built-in qunit, so that
	// we control the version in package.json and loaded from disk.
	// The built-in handler loads a fixed version from code.jquery.com.
	framework: 'custom',
	test_page: 'tests/qunit/testem.mustache',
	serve_files: [
		wgServer + wgScriptPath + '/index.php?title=Special:JavaScriptTest/qunit/export'
	],
	launch_in_dev: [
		'Chrome'
	]
};
/*
karma: {
  options: {
    reporters: [ 'mocha' ],
    browserNoActivityTimeout: 60 * 1000,
    // Karma requires Same-Origin (or CORS) by default since v1.1.1
    // for better stacktraces. But we load the first request from wgServer
    crossOriginAttribute: false
  }
},
*/
