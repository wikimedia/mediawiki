var process = require( 'process' ),
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

module.exports = env;
