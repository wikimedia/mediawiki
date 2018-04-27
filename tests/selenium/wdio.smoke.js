const mainConfig = require( './wdio.conf.js' );

var smokeConfig = Object.create( mainConfig.config );
// Overwrite main settings
smokeConfig.exclude = [];

exports.config = smokeConfig;
