/*!
 * Grunt file
 */

/*jshint node:true*/

module.exports = function( grunt ) {
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		jshint: {
			options: {
				jshint: true
			},
			dev: [ 'styleguide-template/public/*.js' ]
		}
	} );


	grunt.registerTask( 'default', [ 'jshint' ] );
};
