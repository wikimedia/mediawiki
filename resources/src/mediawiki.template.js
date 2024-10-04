/**
 * An extensible library for rendering templates in different template languages.
 * By default only the `html` template library is provided.
 * The Mustache library is also provided in mediawiki core via the mediawiki.template.mustache library.
 *
 * @example
 * // returns $( '<div>hello world</div>' );
 * const $node = mw.template.compile( '<div>hello world</div>', 'html' ).render();
 *
 * // also returns $( '<div>hello world</div>' );
 * mw.loader.using( 'mediawiki.template.mustache' ).then( () => {
 *   const $node = mw.template.compile( '<div>{{ >Foo }}</div>', 'mustache' ).render( {
 *     text: 'Hello world'
 *   }, {
 *     Foo: mw.template.compile( '{{text}}', 'mustache' )
 *   } );
 * } );
 * @namespace mw.template
 */

/**
 * Compiles a template for rendering.
 *
 * @typedef {Function} mw.template~TemplateCompileFunction
 * @param {string} src source of the template
 * @return {TemplateRenderer} for rendering
 */

/**
 * Renders the template to create a jQuery object.
 *
 * @typedef {Function} mw.template~TemplateRenderFunction
 * @param {Object} [data] for the template
 * @param {Object} [partials] additional partial templates
 * @return {jQuery}
 */

/**
 * @typedef {Object} mw.template~TemplateRenderer
 * @property {TemplateRenderFunction} render
 */

/**
 * @typedef {Object} mw.template~TemplateCompiler
 * @property {TemplateCompileFunction} compile
 */
( function () {
	const compiledTemplates = {},
		compilers = {};

	mw.template = {
		/**
		 * Register a new compiler.
		 *
		 * A compiler is any object that implements a {@link mw.template.compile} method. The compile() method must
		 * return a Template interface with a method render() that returns HTML.
		 *
		 * The compiler name must correspond with the name suffix of templates that use this compiler.
		 *
		 * @param {string} name Compiler name
		 * @param {TemplateCompiler} compiler
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
			const nameParts = templateName.split( '.' );
			if ( nameParts.length < 2 ) {
				throw new Error( 'Template name must have a suffix' );
			}
			return nameParts[ nameParts.length - 1 ];
		},

		/**
		 * Get a compiler via its name.
		 *
		 * @param {string} name Name of a compiler
		 * @return {TemplateCompiler} The compiler
		 * @throws {Error} when unknown compiler provided
		 */
		getCompiler: function ( name ) {
			const compiler = compilers[ name ];
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
		 * @return {TemplateRenderer} Compiled template
		 */
		add: function ( moduleName, templateName, templateBody ) {
			// Precompile and add to cache
			const compiled = this.compile( templateBody, this.getCompilerName( templateName ) );
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
		 * @return {TemplateRenderer} Compiled template
		 */
		get: function ( moduleName, templateName ) {
			// Try cache first
			if ( compiledTemplates[ moduleName ] && compiledTemplates[ moduleName ][ templateName ] ) {
				return compiledTemplates[ moduleName ][ templateName ];
			}

			const moduleTemplates = mw.templates.get( moduleName );
			if ( !moduleTemplates || moduleTemplates[ templateName ] === undefined ) {
				throw new Error( 'Template ' + templateName + ' not found in module ' + moduleName );
			}

			// Compiled and add to cache
			return this.add( moduleName, templateName, moduleTemplates[ templateName ] );
		},

		/**
		 * Compile a string of template markup with an engine of choice.
		 *
		 * @param {string} templateBody Template body
		 * @param {string} compilerName The name of a registered compiler.
		 * @return {TemplateRenderer} Compiled template
		 * @throws {Error} when unknown compiler name provided.
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
					return $( $.parseHTML( src.trim() ) );
				}
			};
		}
	} );

}() );
