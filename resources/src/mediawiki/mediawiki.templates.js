/**
 * @class mw.template
 * @singleton
 */
( function ( mw ) {
	var compiledTemplates = {},
		compilers = {};

	mw.template = {
		/**
		 * Register a new compiler and template
		 * @method
		 * @param {String} name of compiler. Should also match with any file extensions of templates that want to use it.
		 * @param {Function} compiler which must implement a compile function
		 */
		registerCompiler: function ( name, compiler ) {
			if ( compiler.compile ) {
				compilers[name] = compiler;
			} else {
				throw new Error( 'Template compiler must implement compile function.' );
			}
		},
		/**
		 * Work out which compiler is associated with the template based on its suffix
		 * @method
		 * @param {String} name Name of template to add including file extension
		 * @return {Function} compiler
		 */
		getCompilerFromName: function ( name ) {
			var templateParts = name.split( '.' ), ext;
			if ( templateParts.length > 1 ) {
				ext = templateParts[ templateParts.length - 1 ];
				if ( compilers[ ext ] ) {
					compiler = compilers[ ext ];
				} else {
					throw new Error( 'Template compiler not found for: ' + ext );
				}
			} else {
				throw new Error( 'Template has no suffix. Unable to identify compiler.' );
			}
			return compiler;
		},
		/**
		 * Define a template. Compiles newly added templates based on
		 * the file extension of name and the available compilers.
		 * @method
		 * @param {String} name Name of template to add including file extension
		 * @param {String} markup Associated markup (html)
		 * @return {Function} compiled template
		 */
		add: function ( name, markup ) {
			var compiler = this.getCompilerFromName( name );
			compiledTemplates[ name ] = compiler.compile( markup );
			return compiledTemplates[ name ];
		},
		/**
		 * Retrieve defined template
		 *
		 * @method
		 * @param {string} name Name of template to be retrieved
		 * @return {Object} template compiler
		 * accepts template data object as its argument.
		 */
		get: function ( name ) {
			var markup;

			if ( !compiledTemplates[ name ] ) {
				markup = mw.templates.get( name );
				if ( markup ) {
					// add compiled version
					this.add( name, markup );
				} else {
					throw new Error( 'Template not found: ' + name );
				}
			}
			return compiledTemplates[ name ];
		},
		/**
		 * Wraps our template engine of choice
		 * @method
		 * @param {string} templateBody Template body.
		 * @param {string} compilerName The name of a registered compiler
		 * @return {Object} template interface
		 * accepts template data object as its argument.
		 */
		compile: function ( templateBody, compilerName ) {
			var compiler = compilers[ compilerName ];
			if ( !compiler ) {
				throw new Error( 'Unknown compiler ' + compilerName );
			}
			return compiler.compile( templateBody );
		}
	};

	// Register basic html compiler
	mw.template.registerCompiler( 'html', {
		compile: function ( src ) {
			return {
				render: function () {
					return src;
				}
			};
		}
	} );

}( mediaWiki ) );
