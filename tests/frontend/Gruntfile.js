/*!
 * Grunt file
 */

/*jshint node:true */
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-jscs' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );
	grunt.loadNpmTasks( 'grunt-karma' );

	grunt.file.setBase(  __dirname + '/../..' );

	var wgServer = process.env.MW_SERVER,
		wgScriptPath = process.env.MW_SCRIPT_PATH;

	grunt.initConfig( {
		pkg: grunt.file.readJSON( __dirname + '/package.json' ),
		jshint: {
			options: {
				jshintrc: true
			},
			all: [
				'*.js',
				'{includes,languages,resources,skins,tests}/**/*.js'
			]
		},
		jscs: {
			all: [
				'<%= jshint.all %>',
				// Auto-generated file with JSON (double quotes)
				'!tests/qunit/data/mediawiki.jqueryMsg.data.js',
				// Skip functions are stored as script files but wrapped in a function when
				// executed. node-jscs trips on the would-be "Illegal return statement".
				'!resources/src/*-skip.js'

			// Exclude all files ignored by jshint
			].concat( grunt.file.read( '.jshintignore' ).split( '\n' ).reduce( function ( patterns, pattern ) {
				// Filter out empty lines
				if ( pattern.length && pattern[0] !== '#' ) {
					patterns.push( '!' + pattern );
				}
				return patterns;
			}, [] ) )
		},
		jsonlint: {
			all: [
				'.jscsrc',
				'{languages,maintenance,resources}/**/*.json',
				'tests/frontend/package.json'
			]
		},
		banana: {
			core: 'languages/i18n/',
			api: 'includes/api/i18n/',
			installer: 'includes/installer/i18n/'
		},
		watch: {
			files: [
				'<%= jscs.all %>',
				'<%= jsonlint.all %>',
				'.jshintignore',
				'.jshintrc'
			],
			tasks: 'test'
		},
		karma: {
			options: {
				// logLevel: 'debug',
				proxies: ( function () {
					var obj = {};
					Object.defineProperty( obj, wgScriptPath, {
						enumerable: true,
						get: function () {
							// XXX: These are only needed for karma. When running grunt-test
							// normally, there's no need to output these warnings.
							// Use property accessors to determine when they're used.
							if ( !wgServer ) {
								grunt.fail.fatal( 'MW_SERVER is not set' );
							}
							if ( !wgScriptPath ) {
								grunt.fail.fatal( 'MW_SCRIPT_PATH is not set' );
							}
							return wgServer + wgScriptPath;
						}
					} );
					return obj;
				}() ),
				files: [ {
					pattern: wgServer + wgScriptPath + '/index.php?title=Special:JavaScriptTest/qunit/export',
					watched: false,
					included: true,
					served: false
				} ],
				frameworks: [ 'qunit' ],
				reporters: [ 'dots' ],
				singleRun: true,
				autoWatch: false
			},
			main: {
				browsers: [ 'Chrome' ]
			},
			other: {
				browsers: [ 'Firefox' ]
			}
		}
	} );

	grunt.registerTask( 'lint', ['jshint', 'jscs', 'jsonlint', 'banana'] );
	grunt.registerTask( 'test', ['lint'] );
	grunt.registerTask( 'default', ['test'] );
};
