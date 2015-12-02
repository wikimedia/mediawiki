var process = require( 'process' ),
    webdriver = require( 'selenium-webdriver' ),
    env = {};

env.define = function ( config ) {
	var envs = {},
			k;

	for ( k in config ) {
		if ( k !== 'default' ) {
			envs[ k ] = new env.Environment( config[ k ] );
		}

		envs.current = envs[ process.env.ENVIRONMENT || config.default ];
	}

	return envs;
};

env.Environment = function ( config ) {
	this.config = config;
};

env.Environment.prototype.pageURL = function ( page ) {
	return this.config.url + page.path;
};

env.Environment.prototype.startBrowser = function () {
	return new webdriver.Builder().forBrowser( this.config.browser ).build();
};

module.exports = env;
