/**
 * @class mw.template
 * @singleton
 */
( function ( mw, $ ) {
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
		 * Work out the name of the compiler that is associated with the template based on its suffix
		 * @method
		 * @param {String} templateName Name of template to add including file extension
		 * @return {String} name of compiler
		 */
		getCompilerName: function ( templateName ) {
			var templateParts = templateName.split( '.' );

			if ( templateParts.length > 1 ) {
				return templateParts[ templateParts.length - 1 ];
			} else {
				throw new Error( 'Template has no suffix. Unable to identify compiler.' );
			}
		},
		/**
		 * Get the compiler for the given compiler name.
		 * @method
		 * @param {String} compilerName Name of the compiler
		 * @return {Object} the compiler associated with that name
		 */
		getCompiler: function ( compilerName ) {
			var compiler = compilers[ compilerName ];
			if ( !compiler ) {
				throw new Error( 'Unknown compiler ' + compilerName );
			} else {
				return compiler;
			}
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
				compilerName = this.getCompilerName( templateName );

			// check module has a compiled template cache
			compiledTemplates[moduleName] = compiledTemplates[moduleName] || {};
			compiledTemplate = this.compile( markup, compilerName );
			compiledTemplates[moduleName][ templateName ] = compiledTemplate;
			return compiledTemplate;
		},
		/**
		 * Retrieve defined template
		 *
		 * @method
		 * @param {string} moduleName Name of the module to retrieve the template from
		 * @param {string} templateName Name of template to be retrieved
		 * @return {Object} compiled template
		 * accepts template data object as its argument.
		 */
		get: function ( moduleName, templateName ) {
			var moduleTemplates, compiledTemplate;

			// check if the template has already been compiled, compile it if not
			if ( !compiledTemplates[ moduleName ] || !compiledTemplates[ moduleName ][ templateName ] ) {
				moduleTemplates = mw.templates.get( moduleName );
				if ( !moduleTemplates ) {
					throw new Error( 'No templates associated with module: ' + moduleName );
				} else if ( !moduleTemplates[ templateName ] ) {
					throw new Error( 'Template source in module ' + moduleName + ' not found: ' + templateName );
				}

				// add compiled version
				compiledTemplate = this.add( moduleName, templateName, moduleTemplates[ templateName ] );
			} else {
				compiledTemplate = compiledTemplates[ moduleName ][ templateName ];
			}
			return compiledTemplate;
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
			return this.getCompiler( compilerName ).compile( templateBody );
		}
	};

	// Register basic html compiler
	mw.template.registerCompiler( 'html', {
		compile: function ( src ) {
			return {
				render: function () {
					return $( $.parseHTML( $.trim( src ) ) );
				}
			};
		}
	} );

}( mediaWiki, jQuery ) );
