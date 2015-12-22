/**
 * @class mw.template
 * @singleton
 */
( function ( mw, $ ) {
	var compiledTemplates = {},
		compilers = {};

	mw.template = {
		/**
		 * Register a new compiler.
		 *
		 * A compiler is any object that implements a compile() method. The compile() method must
		 * return a Template interface with a method render() that returns HTML.
		 *
		 * The compiler name must correspond with the name suffix of templates that use this compiler.
		 *
		 * @param {string} name Compiler name
		 * @param {Object} compiler
		 */
		registerCompiler: function ( name, compiler ) {
			if ( !compiler.compile ) {
				throw new Error( 'Compiler must implement a compile method' );
			}
			compilers[ name ] = compiler;
		},

		/**
		 * Get the name of the associated compiler based on a template name.
		 *
		 * @param {string} templateName Name of a template (including suffix)
		 * @return {string} Name of a compiler
		 */
		getCompilerName: function ( templateName ) {
			var nameParts = templateName.split( '.' );
			if ( nameParts.length < 2 ) {
				throw new Error( 'Template name must have a suffix' );
			}
			return nameParts[ nameParts.length - 1 ];
		},

		/**
		 * Get a compiler via its name.
		 *
		 * @param {string} name Name of a compiler
		 * @return {Object} The compiler
		 */
		getCompiler: function ( name ) {
			var compiler = compilers[ name ];
			if ( !compiler ) {
				throw new Error( 'Unknown compiler ' + name );
			}
			return compiler;
		},

		/**
		 * Register a template associated with a module.
		 *
		 * Precompiles the newly added template based on the suffix in its name.
		 *
		 * @param {string} moduleName Name of the ResourceLoader module the template is associated with
		 * @param {string} templateName Name of the template (including suffix)
		 * @param {string} templateBody Contents of the template (e.g. html markup)
		 * @return {Object} Compiled template
		 */
		add: function ( moduleName, templateName, templateBody ) {
			// Precompile and add to cache
			var compiled = this.compile( templateBody, this.getCompilerName( templateName ) );
			if ( !compiledTemplates[ moduleName ] ) {
				compiledTemplates[ moduleName ] = {};
			}
			compiledTemplates[ moduleName ][ templateName ] = compiled;

			return compiled;
		},

		/**
		 * Get a compiled template by module and template name.
		 *
		 * @param {string} moduleName Name of the module to retrieve the template from
		 * @param {string} templateName Name of template to be retrieved
		 * @return {Object} Compiled template
		 */
		get: function ( moduleName, templateName ) {
			var moduleTemplates;

			// Try cache first
			if ( compiledTemplates[ moduleName ] && compiledTemplates[ moduleName ][ templateName ] ) {
				return compiledTemplates[ moduleName ][ templateName ];
			}

			moduleTemplates = mw.templates.get( moduleName );
			if ( !moduleTemplates || !moduleTemplates[ templateName ] ) {
				throw new Error( 'Template ' + templateName + ' not found in module ' + moduleName );
			}

			// Compiled and add to cache
			return this.add( moduleName, templateName, moduleTemplates[ templateName ] );
		},

		/**
		 * Compile a string of template markup with an engine of choice.
		 *
		 * @param {string} templateBody Template body
		 * @param {string} compilerName The name of a registered compiler
		 * @return {Object} Compiled template
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
