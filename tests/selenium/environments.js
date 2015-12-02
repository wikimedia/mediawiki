var env = require( './lib/environment.js' ),
		environments;

environments = env.define( {
	'default': 'mw-vagrant-chrome',
	'mw-vagrant-chrome': {
		url: 'http://127.0.0.1:8080/wiki/',
		brower: 'chrome'
	},
	'mw-vagrant-firefox': {
		url: 'http://127.0.0.1:8080/wiki/',
		brower: 'firefox'
	},
	'beta-chrome': {
		url: 'http://en.wikipedia.beta.wmflabs.org/wiki/',
		brower: 'chrome'
	},
	'beta-firefox': {
		url: 'http://en.wikipedia.beta.wmflabs.org/wiki/',
		brower: 'firefox'
	}
} );

module.exports = environments;
