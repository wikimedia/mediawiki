/*!
 * Tools for inspecting page composition and performance.
 *
 * @author Ori Livneh
 * @since 1.22
 */
( function ( mw, $ ) {

	/**
	 * @class mw.inspect
	 * @singleton
	 */
	var inspect = {

		/**
		 * Calculate the byte size of a ResourceLoader module.
		 *
		 * @param {string} moduleName The name of the module
		 * @return {number|null} Module size in bytes or null
		 */
		getModuleSize: function ( moduleName ) {
			var module = mw.loader.moduleRegistry[ moduleName ],
				payload = 0;

			if ( mw.loader.getState( moduleName ) !== 'ready' ) {
				return null;
			}

			if ( !module.style && !module.script ) {
				return null;
			}

			// Tally CSS
			if ( module.style && $.isArray( module.style.css ) ) {
				$.each( module.style.css, function ( i, stylesheet ) {
					payload += $.byteLength( stylesheet );
				} );
			}

			// Tally JavaScript
			if ( $.isFunction( module.script ) ) {
				payload += $.byteLength( module.script.toString() );
			}

			return payload;
		},

		/**
		 * Get a list of all loaded ResourceLoader modules.
		 *
		 * @return {Array} List of module names
		 */
		getLoadedModules: function () {
			return $.grep( mw.loader.getModuleNames(), function ( module ) {
				return mw.loader.getState( module ) === 'ready';
			} );
		},

		/**
		 * Print a breakdown of all loaded modules and their size in kilobytes
		 * to the debug console. Modules are ordered from largest to smallest.
		 */
		inspectModules: function () {
			var console = window.console;

			$( function () {
				// Map each module to a descriptor object.
				var modules = $.map( inspect.getLoadedModules(), function ( module ) {
					return {
						name: module,
						size: inspect.getModuleSize( module )
					};
				} );

				// Sort module descriptors by size, largest first.
				modules.sort( function ( a, b ) {
					return b.size - a.size;
				} );

				// Convert size to human-readable string.
				$.each( modules, function ( i, module ) {
					module.size = module.size > 1024 ?
						( module.size / 1024 ).toFixed( 2 ) + ' KB' :
						( module.size !== null ? module.size + ' B' : null );
				} );

				if ( console ) {
					if ( console.table ) {
						console.table( modules );
					} else {
						$.each( modules, function ( i, module ) {
							console.log( [ module.name, module.size ].join( '\t' ) );
						} );
					}
				}
			} );
		}
	};

	if ( mw.config.get( 'debug' ) ) {
		inspect.getModuleSize = function () { return null; };
		mw.log( 'mw.inspect: Module sizes are not available in debug mode.' );
	}

	mw.inspect = inspect;

}( mediaWiki, jQuery ) );
