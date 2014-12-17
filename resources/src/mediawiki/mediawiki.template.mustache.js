/*global Mustache */
( function ( mw, $ ) {
	// Register mustache compiler
	mw.template.registerCompiler( 'mustache', {
		compile: function ( src ) {
			return {
				render: function ( data ) {
					return $( Mustache.render( src, data ) );
				}
			};
		}
	} );

}( mediaWiki, jQuery ) );
