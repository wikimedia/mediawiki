/*!
 * Grunt file
 */

/*jshint node:true */
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-jscs-checker' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );

	grunt.file.setBase(  __dirname + '/../..' );

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
				'!resources/src/es5-skip.js'

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
				'{languages,languages,maintenance,resources}/**/*.json',
				'tests/frontend/package.json'
			]
		},
		banana: {
			core: 'languages/i18n/',
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
		}
	} );

	grunt.registerTask( 'lint', ['jshint', 'jscs', 'jsonlint', 'banana'] );
	grunt.registerTask( 'test', ['lint'] );
	grunt.registerTask( 'default', ['test'] );
};
