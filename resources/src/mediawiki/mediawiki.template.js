/**
 * @class mw.template
 * @singleton
 */
( function ( mw, $ ) {
	var compiledTemplates = {},
		compilers = {};

	mw.template = {
		/**
		 * Register a new compiler and template.
		 *
		 * @param {string} name of compiler. Should also match with any file extensions of templates that want to use it.
		 * @param {Function} compiler which must implement a compile function
		 */
		registerCompiler: function ( name, compiler ) {
			if ( !compiler.compile ) {
				throw new Error( 'Compiler must implement compile method.' );
			}
			compilers[name] = compiler;
		},

		/**
		 * Get the name of the compiler associated with a template based on its name.
		 *
		 * @param {string} templateName Name of template (including file suffix)
		 * @return {String} Name of compiler
		 */
		getCompilerName: function ( templateName ) {
			var templateParts = templateName.split( '.' );

			if ( templateParts.length < 2 ) {
				throw new Error( 'Unable to identify compiler. Template name must have a suffix.' );
			}
			return templateParts[ templateParts.length - 1 ];
		},

		/**
		 * Get the compiler for a given compiler name.
		 *
		 * @param {string} compilerName Name of the compiler
		 * @return {Object} The compiler associated with that name
		 */
		getCompiler: function ( compilerName ) {
			var compiler = compilers[ compilerName ];
			if ( !compiler ) {
				throw new Error( 'Unknown compiler ' + compilerName );
			}
			return compiler;
		},

		/**
		 * Register a template associated with a module.
		 *
		 * Compiles the newly added template based on the suffix in its name.
		 *
		 * @param {string} moduleName Name of ResourceLoader module to get the template from
		 * @param {string} templateName Name of template to add including file extension
		 * @param {string} templateBody Contents of a template (e.g. html markup)
		 * @return {Function} Compiled template
		 */
		add: function ( moduleName, templateName, templateBody ) {
			var compiledTemplate,
				compilerName = this.getCompilerName( templateName );

			if ( !compiledTemplates[moduleName] ) {
				compiledTemplates[moduleName] = {};
			}

			compiledTemplate = this.compile( templateBody, compilerName );
			compiledTemplates[moduleName][ templateName ] = compiledTemplate;
			return compiledTemplate;
		},

		/**
		 * Retrieve a template by module and template name.
		 *
		 * @param {string} moduleName Name of the module to retrieve the template from
		 * @param {string} templateName Name of template to be retrieved
		 * @return {Object} Compiled template
		 */
		get: function ( moduleName, templateName ) {
			var moduleTemplates, compiledTemplate;

			// Check if the template has already been compiled, compile it if not
			if ( !compiledTemplates[ moduleName ] || !compiledTemplates[ moduleName ][ templateName ] ) {
				moduleTemplates = mw.templates.get( moduleName );
				if ( !moduleTemplates || !moduleTemplates[ templateName ] ) {
					throw new Error( 'Template ' + templateName + ' not found in module ' + moduleName );
				}

				// Add compiled version
				compiledTemplate = this.add( moduleName, templateName, moduleTemplates[ templateName ] );
			} else {
				compiledTemplate = compiledTemplates[ moduleName ][ templateName ];
			}
			return compiledTemplate;
		},

		/**
		 * Wrap our template engine of choice.
		 *
		 * @param {string} templateBody Template body
		 * @param {string} compilerName The name of a registered compiler
		 * @return {Object} Template interface
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
