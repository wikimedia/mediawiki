/*!
 * Grunt file
 */

/*jshint node:true */
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-jscs-checker' );

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			all: [
				'*.js',
				'{includes,languages,resources,skins,tests}/**/*.js'
			]
		},
		jscs: {
			all: [
				'<%= jshint.all %>',
				// Auto-generated to include JSON (double quotes)
				'!tests/qunit/data/mediawiki.jqueryMsg.data.js'

			// Exclude all files ignored by jshint
			].concat( grunt.file.read( '.jshintignore' ).split( '\n' ).reduce( function ( patterns, pattern ) {
				// Filter out empty lines
				if ( pattern.length && pattern[0] !== '#' ) {
					patterns.push( '!' + pattern );
				}
				return patterns;
			}, [] ) )
		},
		watch: {
			files: [
				'.{jshintrc,jscs.json,jshintignore,csslintrc}',
				'<%= jshint.all %>'
			],
			tasks: ['test']
		}
	} );

	grunt.registerTask( 'lint', ['jshint', 'jscs'] );
	grunt.registerTask( 'test', ['lint'] );
	grunt.registerTask( 'default', ['test'] );
};
