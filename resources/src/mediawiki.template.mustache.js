/* global Mustache */
( function () {
	// Register mustache compiler
	mw.template.registerCompiler( 'mustache', {
		compile: function ( src ) {
			return {
				/**
				 * @ignore
				 * @return {string} The raw source code of the template
				 */
				getSource: function () {
					return src;
				},
				/**
				 * @ignore
				 * @param {Object} data Data to render
				 * @param {Object} partialTemplates Map partial names to Mustache template objects
				 *  returned by mw.template.get()
				 * @return {jQuery} Rendered HTML
				 */
				render: function ( data, partialTemplates ) {
					const partials = {};
					if ( partialTemplates ) {
						for ( const name in partialTemplates ) {
							const template = partialTemplates[ name ];
							partials[ name ] = template.getSource();
						}
					}
					return $( $.parseHTML( Mustache.render( src, data, partials ) ) );
				}
			};
		}
	} );

}() );
