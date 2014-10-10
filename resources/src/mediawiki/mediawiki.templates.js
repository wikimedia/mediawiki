/**
 * @class mw.template
 * @singleton
 */
( function ( mw ) {
	var templates = {};

	mw.template = {
		_compilers: {},
		registerCompiler: function ( name, obj ) {
			if ( obj.compile ) {
				this._compilers[name] = obj;
			} else {
				throw new Error( 'Template compiler must implement compile function.' );
			}
		},
		/**
		 * Define a template. Compiles newly added templates based on
		 * the file extension of name and the available compilers.
		 * @method
		 * @param {String} name Name of template to add including file extension
		 * @param {String} markup Associated markup (html)
		 */
		add: function ( name, markup ) {
			var templateParts = name.split( '.' ), ext,
				compiler;

			if ( templateParts.length > 1 ) {
				ext = templateParts[ templateParts.length - 1 ];
				if ( this._compilers[ ext ] ) {
					compiler = this._compilers[ ext ];
				} else {
					throw new Error( 'Template compiler not found for: ' + ext );
				}
			} else {
				throw new Error( 'Template has no suffix. Unable to identify compiler.' );
			}
			templates[ name ] = compiler.compile( markup );
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
			if ( !templates[ name ] ) {
				throw new Error( 'Template not found: ' + name );
			}
			return templates[ name ];
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
			var compiler = this._compilers[ compilerName ];
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
