/*jshint node:true */
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-jscs' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );
	grunt.loadNpmTasks( 'grunt-karma' );

	var wgServer = process.env.MW_SERVER,
		wgScriptPath = process.env.MW_SCRIPT_PATH,
		karmaProxy = {};

	karmaProxy[wgScriptPath] = wgServer + wgScriptPath;

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		jshint: {
			options: {
				jshintrc: true
			},
			all: [
				'*.js',
				'{includes,languages,resources,tests}/**/*.js'
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
				'package.json'
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
				proxies: karmaProxy,
				files: [ {
					pattern: wgServer + wgScriptPath + '/index.php?title=Special:JavaScriptTest/qunit/export',
					watched: false,
					included: true,
					served: false
				} ],
				frameworks: [ 'qunit' ],
				reporters: [ 'dots' ],
				singleRun: true,
				autoWatch: false,
				// Some tests in extensions don't yield for more than the default 10s (T89075)
				browserNoActivityTimeout: 60 * 1000
			},
			main: {
				browsers: [ 'Chrome' ]
			},
			more: {
				browsers: [ 'Chrome', 'Firefox' ]
			}
		},
		copy: {
			jsduck: {
				src: 'resources/**/*',
				dest: 'docs/js/modules',
				expand: true,
				rename: function ( dest, src ) {
					return require( 'path' ).join( dest, src.replace( 'resources/', '' ) );
				}
			}
		}
	} );

	grunt.registerTask( 'assert-mw-env', function () {
		if ( !process.env.MW_SERVER ) {
			grunt.log.error( 'Environment variable MW_SERVER must be set.\n' +
				'Set this like $wgServer, e.g. "http://localhost"'
			);
		}
		if ( !process.env.MW_SCRIPT_PATH ) {
			grunt.log.error( 'Environment variable MW_SCRIPT_PATH must be set.\n' +
				'Set this like $wgScriptPath, e.g. "/w"');
		}
		return !!( process.env.MW_SERVER && process.env.MW_SCRIPT_PATH );
	} );

	grunt.registerTask( 'lint', ['jshint', 'jscs', 'jsonlint', 'banana'] );
	grunt.registerTask( 'qunit', [ 'assert-mw-env', 'karma:main' ] );

	grunt.registerTask( 'test', ['lint'] );
	grunt.registerTask( 'default', 'test' );
};
