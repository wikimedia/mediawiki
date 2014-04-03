/*!
 * Tools for inspecting page composition and performance.
 *
 * @author Ori Livneh
 * @since 1.22
 */
/*jshint devel:true */
( function ( mw, $ ) {

	function sortByProperty( array, prop, descending ) {
		var order = descending ? -1 : 1;
		return array.sort( function ( a, b ) {
			return a[prop] > b[prop] ? order : a[prop] < b[prop] ? -order : 0;
		} );
	}

	function humanSize( bytes ) {
		if ( !$.isNumeric( bytes ) || bytes === 0 ) { return bytes; }
		var i = 0, units = [ '', ' kB', ' MB', ' GB', ' TB', ' PB' ];
		for ( ; bytes >= 1024; bytes /= 1024 ) { i++; }
		return bytes.toFixed( 1 ) + units[i];
	}

	/**
	 * @class mw.inspect
	 * @singleton
	 */
	var inspect = {

		/**
		 * Return a map of all dependency relationships between loaded modules.
		 *
		 * @return {Object} Maps module names to objects. Each sub-object has
		 *  two properties, 'requires' and 'requiredBy'.
		 */
		getDependencyGraph: function () {
			var modules = inspect.getLoadedModules(), graph = {};

			$.each( modules, function ( moduleIndex, moduleName ) {
				var dependencies = mw.loader.moduleRegistry[moduleName].dependencies || [];

				graph[moduleName] = graph[moduleName] || { requiredBy: [] };
				graph[moduleName].requires = dependencies;

				$.each( dependencies, function ( depIndex, depName ) {
					graph[depName] = graph[depName] || { requiredBy: [] };
					graph[depName].requiredBy.push( moduleName );
				} );
			} );
			return graph;
		},

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
		 * Given CSS source, count both the total number of selectors it
		 * contains and the number which match some element in the current
		 * document.
		 *
		 * @param {string} css CSS source
		 * @return Selector counts
		 * @return {number} return.selectors Total number of selectors
		 * @return {number} return.matched Number of matched selectors
		 */
		auditSelectors: function ( css ) {
			var selectors = { total: 0, matched: 0 },
				style = document.createElement( 'style' ),
				sheet, rules;

			style.textContent = css;
			document.body.appendChild( style );
			// Standards-compliant browsers use .sheet.cssRules, IE8 uses .styleSheet.rules…
			sheet = style.sheet || style.styleSheet;
			rules = sheet.cssRules || sheet.rules;
			$.each( rules, function ( index, rule ) {
				selectors.total++;
				if ( document.querySelector( rule.selectorText ) !== null ) {
					selectors.matched++;
				}
			} );
			document.body.removeChild( style );
			return selectors;
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
		 * Print tabular data to the console, using console.table, console.log,
		 * or mw.log (in declining order of preference).
		 *
		 * @param {Array} data Tabular data represented as an array of objects
		 *  with common properties.
		 */
		dumpTable: function ( data ) {
			try {
				// Bartosz made me put this here.
				if ( window.opera ) { throw window.opera; }
				// Use Function.prototype#call to force an exception on Firefox,
				// which doesn't define console#table but doesn't complain if you
				// try to invoke it.
				console.table.call( console, data );
				return;
			} catch ( e ) {}
			try {
				console.log( $.toJSON( data, null, 2 ) );
				return;
			} catch ( e ) {}
			mw.log( data );
		},

		/**
		 * Generate and print one more reports. When invoked with no arguments,
		 * print all reports.
		 *
		 * @param {string...} [reports] Report names to run, or unset to print
		 *  all available reports.
		 */
		runReports: function () {
			var reports = arguments.length > 0 ?
				Array.prototype.slice.call( arguments ) :
				$.map( inspect.reports, function ( v, k ) { return k; } );

			$.each( reports, function ( index, name ) {
				inspect.dumpTable( inspect.reports[name]() );
			} );
		},

		/**
		 * @class mw.inspect.reports
		 * @singleton
		 */
		reports: {
			/**
			 * Generate a breakdown of all loaded modules and their size in
			 * kilobytes. Modules are ordered from largest to smallest.
			 */
			size: function () {
				// Map each module to a descriptor object.
				var modules = $.map( inspect.getLoadedModules(), function ( module ) {
					return {
						name: module,
						size: inspect.getModuleSize( module )
					};
				} );

				// Sort module descriptors by size, largest first.
				sortByProperty( modules, 'size', true );

				// Convert size to human-readable string.
				$.each( modules, function ( i, module ) {
					module.size = humanSize( module.size );
				} );

				return modules;
			},

			/**
			 * For each module with styles, count the number of selectors, and
			 * count how many match against some element currently in the DOM.
			 */
			css: function () {
				var modules = [];

				$.each( inspect.getLoadedModules(), function ( index, name ) {
					var css, stats, module = mw.loader.moduleRegistry[name];

					try {
						css = module.style.css.join();
					} catch ( e ) { return; } // skip

					stats = inspect.auditSelectors( css );
					modules.push( {
						module: name,
						allSelectors: stats.total,
						matchedSelectors: stats.matched,
						percentMatched: stats.total !== 0 ?
							( stats.matched / stats.total * 100 ).toFixed( 2 )  + '%' : null
					} );
				} );
				sortByProperty( modules, 'allSelectors', true );
				return modules;
			},

			/**
			 * Report stats on mw.loader.store: the number of localStorage
			 * cache hits and misses, the number of items purged from the
			 * cache, and the total size of the module blob in localStorage.
			 */
			store: function () {
				var raw, stats = { enabled: mw.loader.store.enabled };
				if ( stats.enabled ) {
					$.extend( stats, mw.loader.store.stats );
					try {
						raw = localStorage.getItem( mw.loader.store.getStoreKey() );
						stats.totalSize = humanSize( $.byteLength( raw ) );
					} catch ( e ) {}
				}
				return [stats];
			}
		},

		/**
		 * Perform a substring search across the JavaScript and CSS source code
		 * of all loaded modules and return an array of the names of the
		 * modules that matched.
		 *
		 * @param {string|RegExp} pattern String or regexp to match.
		 * @return {Array} Array of the names of modules that matched.
		 */
		grep: function ( pattern ) {
			if ( typeof pattern.test !== 'function' ) {
				// Based on Y.Escape.regex from YUI v3.15.0
				pattern = new RegExp( pattern.replace( /[\-$\^*()+\[\]{}|\\,.?\s]/g, '\\$&' ), 'g' );
			}

			return $.grep( inspect.getLoadedModules(), function ( moduleName ) {
				var module = mw.loader.moduleRegistry[moduleName];

				// Grep module's JavaScript
				if ( $.isFunction( module.script ) && pattern.test( module.script.toString() ) ) {
					return true;
				}

				// Grep module's CSS
				if (
					$.isPlainObject( module.style ) && $.isArray( module.style.css )
					&& pattern.test( module.style.css.join( '' ) )
				) {
					// Module's CSS source matches
					return true;
				}

				return false;
			} );
		}
	};

	if ( mw.config.get( 'debug' ) ) {
		mw.log( 'mw.inspect: reports are not available in debug mode.' );
	}

	mw.inspect = inspect;

}( mediaWiki, jQuery ) );
