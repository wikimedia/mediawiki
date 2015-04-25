/*global Mustache */
( function ( mw, $ ) {
	// Register mustache compiler
	mw.template.registerCompiler( 'mustache', {
		compile: function ( src ) {
			return {
				_getSource: function () {
					return src;
				},
				/**
				 * @ignore
				 * @param {Object} data to render
				 * @param {Object} partialTemplates mapping of partial names to templates
				 */
				render: function ( data, partialTemplates ) {
					var partials = {};
					if ( partialTemplates ) {
						$.each( partialTemplates, function ( name, template ) {
							partials[name] = template._getSource();
						} );
					}
					return $( $.parseHTML( Mustache.render( src, data, partials ) ) );
				}
			};
		}
	} );

}( mediaWiki, jQuery ) );
