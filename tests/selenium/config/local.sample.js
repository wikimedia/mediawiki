module.exports = {
	// URL to your wiki
	baseUrl: 'http://localhost/wiki/',
	// A browser to use
	browser: 'chrome',

	// options for both grunt-mocha-test and mocha
	// References:
	// https://github.com/pghalliday/grunt-mocha-test#options
	// https://mochajs.org/#usage
	mochaTestOptions: {
		reporter: 'spec',
		timeout: 20000
	},

	// A wiki user
	// Must be able to create accounts
	username: 'WikiAdmin',
	password: 'xxxx'
};

