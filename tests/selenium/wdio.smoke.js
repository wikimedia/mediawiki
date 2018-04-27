const mainConfig = require( './wdio.conf.js' );

var config = Object.create( mainConfig );
// Overwrite main settings
config.exclude = [];

exports.config = config;
