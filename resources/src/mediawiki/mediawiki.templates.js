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
		 * @param {String} templateName Name of template to add including file extension
		 * @return {Function} compiler
		 */
		getCompilerFromName: function ( templateName ) {
			var templateParts = templateName.split( '.' ), compiler,
				ext;

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
		 * @param {String} moduleName Name of RL module to get template from
		 * @param {String} templateName Name of template to add including file extension
		 * @param {String} markup Associated markup (html)
		 * @return {Function} compiled template
		 */
		add: function ( moduleName, templateName, markup ) {
			var compiledTemplate,
				compiler = this.getCompilerFromName( templateName );

			// check module has a compiled template cache
			compiledTemplates[moduleName] = compiledTemplates[moduleName] || {};

			compiledTemplate = compiler.compile( markup );
			compiledTemplates[moduleName][ templateName ] = compiledTemplate;
			return compiledTemplate;
		},
		/**
		 * Retrieve defined template
		 *
		 * @method
		 * @param {string} name Name of template to be retrieved
		 * @return {Object} template compiler
		 * accepts template data object as its argument.
		 */
		get: function ( moduleName, templateName ) {
			var moduleTemplates;

			// check if the template has already been compiled, compile it if not
			if ( !compiledTemplates[ moduleName ] || !compiledTemplates[ moduleName ][ templateName ] ) {
				moduleTemplates = mw.templates.get( moduleName );
				if ( !moduleTemplates ) {
					throw new Error( 'No templates associated with module: ' + moduleName );
				}

				if ( moduleTemplates[ templateName ] ) {
					// add compiled version
					return this.add( moduleName, templateName, moduleTemplates[ templateName ] );
				} else {
					throw new Error( 'Template in module ' + moduleName + ' not found: ' + templateName );
				}
			} else {
				return compiledTemplates[ moduleName ][ templateName ];
			}
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
