"use strict";

module.exports = function( grunt ) {
	grunt.registerTask( "testswarm", function( commit, configFile, destName ) {
		var jobName,
			testswarm = require( "testswarm" ),
			runs = {},
			done = this.async(),
			pull = /PR-(\d+)/.exec( commit ),
			config = grunt.file.readJSON( configFile ).jquerymigrate,
			tests = grunt.config( "tests" )[ destName ],
			browserSets = destName || config.browserSets;

		if ( browserSets[ 0 ] === "[" ) {

			// We got an array, parse it
			browserSets = JSON.parse( browserSets );
		}

		if ( pull ) {
			jobName = "Pull <a href='https://github.com/jquery/jquery-migrate/pull/" +
				pull[ 1 ] + "'>#" + pull[ 1 ] + "</a>";
		} else {
			jobName = "Commit <a href='https://github.com/jquery/jquery-migrate/commit/" +
				commit + "'>" + commit.substr( 0, 10 ) + "</a>";
		}

		tests.forEach( function( test ) {
			var pluginjQuery = test.split( "+" );
			runs[ test ] = config.testUrl + commit + "/test/index.html?plugin=" +
				pluginjQuery[ 0 ] + "&jquery=" + pluginjQuery[ 1 ];
		} );

		// TODO: create separate job for git so we can do different browsersets
		testswarm.createClient( {
			url: config.swarmUrl
		} )
		.addReporter( testswarm.reporters.cli )
		.auth( {
			id: config.authUsername,
			token: config.authToken
		} )
		.addjob(
			{
				name: jobName,
				runs: runs,
				runMax: config.runMax,
				browserSets: browserSets,
				timeout: 1000 * 60 * 30
			}, function( err, passed ) {
				if ( err ) {
					grunt.log.error( err );
				}
				done( passed );
			}
		);
	} );
};
