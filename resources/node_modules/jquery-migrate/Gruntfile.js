/*global module:false*/
module.exports = function( grunt ) {

	"use strict";

	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( "package.json" ),
		files: [
			"src/intro.js",
			"src/version.js",
			"src/migrate.js",
			"src/core.js",
			"src/ajax.js",
			"src/attributes.js",
			"src/css.js",
			"src/data.js",
			"src/effects.js",
			"src/event.js",
			"src/offset.js",
			"src/serialize.js",
			"src/traversing.js",
			"src/deferred.js",
			"src/outro.js"
		],
		tests: {
			"jquery": [
				"dev+git",
				"min+git.min",
				"dev+3.2.1",
				"dev+3.1.1",
				"dev+3.0.0"
			]
		},
		banners: {
			tiny: "/*! <%= pkg.name %> <%= pkg.version %> - <%= pkg.homepage %> */"
		},
		concat: {
			options: {
				banner: "/*!\n * <%= pkg.title || pkg.name %> - v<%= pkg.version %> - " +
					"<%= grunt.template.today('yyyy-mm-dd') %>\n" +
					" * Copyright <%= pkg.author.name %>\n */\n"
			},
			dist: {
				src: "<%= files %>",
				dest: "dist/<%= pkg.name %>.js"
			}
		},
		qunit: {
			options: {
				coverage: {
					disposeCollector: true,
					instrumentedFiles: "temp/",
					src: [ "src/!(intro.js|outro.js)" ],
					htmlReport: "coverage/html",
					lcovReport: "coverage/lcov",
					linesThresholdPct: 85
				}
			},
			files: [ "test/**/index.html" ]
		},
		coveralls: {
			src: "coverage/lcov/lcov.info",
			options: {

				// Should not fail if coveralls is down
				force: true
			}
		},
		eslint: {
			options: {

				// See https://github.com/sindresorhus/grunt-eslint/issues/119
				quiet: true
			},

			dist: {
				src: "dist/jquery-migrate.js"
			},
			dev: {
				src: [
					"Gruntfile.js",
					"build/**/*.js",
					"src/**/*.js",
					"test/**/*.js"
				]
			}
		},
		uglify: {
			all: {
				files: {
					"dist/jquery-migrate.min.js": [ "src/migratemute.js", "dist/jquery-migrate.js" ]
				}
			},
			options: {
				banner: "/*! jQuery Migrate v<%= pkg.version %>" +
					" | (c) <%= pkg.author.name %> | jquery.org/license */\n",
				beautify: {
					ascii_only: true
				}
			}
		},
		watch: {
			files: [ "src/*.js", "test/*.js" ],
			tasks: [ "build" ]
		}
	} );

	// Load grunt tasks from NPM packages
	require( "load-grunt-tasks" )( grunt );

	// Integrate jQuery migrate specific tasks
	grunt.loadTasks( "build/tasks" );

	// Just an alias
	grunt.registerTask( "test", [ "qunit" ] );

	grunt.registerTask( "lint", [

		// Running the full eslint task without breaking it down to targets
		// would run the dist target first which would point to errors in the built
		// file, making it harder to fix them. We want to check the built file only
		// if we already know the source files pass the linter.
		"eslint:dev",
		"eslint:dist"
	] );
	grunt.registerTask( "build", [ "concat", "uglify", "lint" ] );

	grunt.registerTask( "default", [ "build", "test" ] );

	// For CI
	grunt.registerTask( "ci", [ "build", "test", "coveralls" ] );
};
