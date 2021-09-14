/*!
 * The mediawiki.inspect module.
 *
 * @author Ori Livneh
 * @since 1.22
 */

/* eslint-disable no-console */

( function () {

	// mw.inspect is a singleton class with static methods
	// that itself can also be invoked as a function (mediawiki.base/mw#inspect).
	// In JavaScript, that is implemented by starting with a function,
	// and subsequently setting additional properties on the function object.

	/**
	 * Tools for inspecting page composition and performance.
	 *
	 * @class mw.inspect
	 * @singleton
	 */

	var inspect = mw.inspect,
		byteLength = require( 'mediawiki.String' ).byteLength,
		hasOwn = Object.prototype.hasOwnProperty;

	function sortByProperty( array, prop, descending ) {
		var order = descending ? -1 : 1;
		return array.sort( function ( a, b ) {
			if ( a[ prop ] === undefined || b[ prop ] === undefined ) {
				// Sort undefined to the end, regardless of direction
				return a[ prop ] !== undefined ? -1 : b[ prop ] !== undefined ? 1 : 0;
			}
			return a[ prop ] > b[ prop ] ? order : a[ prop ] < b[ prop ] ? -order : 0;
		} );
	}

	function humanSize( bytesInput ) {
		var i,
			bytes = +bytesInput,
			units = [ '', ' KiB', ' MiB', ' GiB', ' TiB', ' PiB' ];

		if ( bytes === 0 || isNaN( bytes ) ) {
			return bytesInput;
		}

		for ( i = 0; bytes >= 1024; bytes /= 1024 ) {
			i++;
		}
		// Maintain one decimal for KiB and above, but don't
		// add ".0" for bytes.
		return bytes.toFixed( i > 0 ? 1 : 0 ) + units[ i ];
	}

	function serializeModuleScript( script ) {
		// Based on mw.loader.store.set in startup/mediawiki.js
		if ( typeof script === 'function' ) {
			// Classic script
			return String( script );
		}
		if ( $.isPlainObject( script ) ) {
			// Package files object
			return '{' +
				'main:' + JSON.stringify( script.main ) + ',' +
				'files:{' +
				Object.keys( script.files ).map( function ( file ) {
					var value = script.files[ file ];
					return JSON.stringify( file ) + ':' +
						( typeof value === 'function' ? value : JSON.stringify( value ) );
				} ).join( ',' ) +
				'}}';
		}
		// Array of urls, or null.
		return JSON.stringify( script );
	}

	/**
	 * Return a map of all dependency relationships between loaded modules.
	 *
	 * @return {Object} Maps module names to objects. Each sub-object has
	 *  two properties, 'requires' and 'requiredBy'.
	 */
	inspect.getDependencyGraph = function () {
		var modules = inspect.getLoadedModules(),
			graph = {};

		modules.forEach( function ( moduleName ) {
			var dependencies = mw.loader.moduleRegistry[ moduleName ].dependencies || [];

			if ( !hasOwn.call( graph, moduleName ) ) {
				graph[ moduleName ] = { requiredBy: [] };
			}
			graph[ moduleName ].requires = dependencies;

			dependencies.forEach( function ( depName ) {
				if ( !hasOwn.call( graph, depName ) ) {
					graph[ depName ] = { requiredBy: [] };
				}
				graph[ depName ].requiredBy.push( moduleName );
			} );
		} );
		return graph;
	};

	/**
	 * Calculate the byte size of a ResourceLoader module.
	 *
	 * @param {string} moduleName The name of the module
	 * @return {number|null} Module size in bytes or null
	 */
	inspect.getModuleSize = function ( moduleName ) {
		// Approximate the size of this module as originally received from the server.
		//
		// We typically receive them from the server through batches from load.php,
		// or embedded as inline scripts (handled in PHP by ResourceLoader::makeModuleResponse
		// and ResourceLoaderClientHtml respectively).
		//
		// Each module is bundled by ResourceLoader::makeLoaderImplementScript in PHP,
		// and might look as follows:
		//
		//     mw.loader.implement("example",function(){},{"css":[".x{color:red}"]});
		//
		// These parameters are stored by mw.loader.implement in the registry,
		// and below we'll measure the size of each.
		var module = mw.loader.moduleRegistry[ moduleName ];

		if ( module.state !== 'ready' ) {
			return null;
		}
		if ( !module.style && !module.script ) {
			return 0;
		}

		var size = 0;
		size += byteLength( JSON.stringify( moduleName ) );
		size += byteLength( serializeModuleScript( module.script ) );

		// The last three parameters are optional. The server omits these when they
		// are empty (handled via ResourceLoader::trimArray), which is reflected
		// in the registry as a default null value. Count such nulls as zero instead
		// of as an actual `null` argument, since they were not actually in the bundle.
		size += module.style ? byteLength( JSON.stringify( module.style ) ) : 0;
		size += module.messages ? byteLength( JSON.stringify( module.messages ) ) : 0;
		size += module.templates ? byteLength( JSON.stringify( module.templates ) ) : 0;
		return size;
	};

	/**
	 * Given CSS source, count both the total number of selectors it
	 * contains and the number which match some element in the current
	 * document.
	 *
	 * @param {string} css CSS source
	 * @return {Object} Selector counts
	 * @return {number} return.selectors Total number of selectors
	 * @return {number} return.matched Number of matched selectors
	 */
	inspect.auditSelectors = function ( css ) {
		var selectors = { total: 0, matched: 0 },
			style = document.createElement( 'style' );

		style.textContent = css;
		document.body.appendChild( style );
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( style.sheet.cssRules, function ( index, rule ) {
			selectors.total++;
			// document.querySelector() on prefixed pseudo-elements can throw exceptions
			// in Firefox and Safari. Ignore these exceptions.
			// https://bugs.webkit.org/show_bug.cgi?id=149160
			// https://bugzilla.mozilla.org/show_bug.cgi?id=1204880
			try {
				if ( document.querySelector( rule.selectorText ) !== null ) {
					selectors.matched++;
				}
			} catch ( e ) {}
		} );
		document.body.removeChild( style );
		return selectors;
	};

	/**
	 * Get a list of all loaded ResourceLoader modules.
	 *
	 * @return {Array} List of module names
	 */
	inspect.getLoadedModules = function () {
		return mw.loader.getModuleNames().filter( function ( module ) {
			return mw.loader.getState( module ) === 'ready';
		} );
	};

	/**
	 * Print tabular data to the console, using console.table, console.log,
	 * or mw.log (in declining order of preference).
	 *
	 * @param {Array} data Tabular data represented as an array of objects
	 *  with common properties.
	 */
	inspect.dumpTable = function ( data ) {
		try {
			// Use Function.prototype#call to force an exception on Firefox,
			// which doesn't define console#table but doesn't complain if you
			// try to invoke it.
			// eslint-disable-next-line no-useless-call, compat/compat
			console.table.call( console, data );
			return;
		} catch ( e ) {}
		try {
			console.log( JSON.stringify( data, null, 2 ) );
		} catch ( e ) {}
	};

	/**
	 * Generate and print reports.
	 *
	 * When invoked without arguments, prints all available reports.
	 *
	 * @param {...string} [reports] One or more of "size", "css", "store", or "time".
	 */
	inspect.runReports = function () {
		var reports = arguments.length > 0 ?
			Array.prototype.slice.call( arguments ) :
			Object.keys( inspect.reports );

		reports.forEach( function ( name ) {
			if ( console.group ) {
				console.group( 'mw.inspect ' + name + ' report' );
			} else {
				console.log( 'mw.inspect ' + name + ' report' );
			}
			inspect.dumpTable( inspect.reports[ name ]() );
			if ( console.group ) {
				console.groupEnd( 'mw.inspect ' + name + ' report' );
			}
		} );
	};

	/**
	 * Perform a string search across the JavaScript and CSS source code
	 * of all loaded modules and return an array of the names of the
	 * modules that matched.
	 *
	 * @param {string|RegExp} pattern String or regexp to match.
	 * @return {Array} Array of the names of modules that matched.
	 */
	inspect.grep = function ( pattern ) {
		if ( typeof pattern.test !== 'function' ) {
			pattern = new RegExp( mw.util.escapeRegExp( pattern ), 'g' );
		}

		return inspect.getLoadedModules().filter( function ( moduleName ) {
			var module = mw.loader.moduleRegistry[ moduleName ];

			// Grep module's JavaScript
			if ( typeof module.script === 'function' && pattern.test( module.script.toString() ) ) {
				return true;
			}

			// Grep module's CSS
			if (
				$.isPlainObject( module.style ) && Array.isArray( module.style.css ) &&
				pattern.test( module.style.css.join( '' ) )
			) {
				// Module's CSS source matches
				return true;
			}

			return false;
		} );
	};

	/**
	 * @private
	 * @class mw.inspect.reports
	 * @singleton
	 */
	inspect.reports = {
		/**
		 * Generate a breakdown of all loaded modules and their size in
		 * kibibytes. Modules are ordered from largest to smallest.
		 *
		 * @return {Object[]} Size reports
		 */
		size: function () {
			// Map each module to a descriptor object.
			var modules = inspect.getLoadedModules().map( function ( module ) {
				return {
					name: module,
					size: inspect.getModuleSize( module )
				};
			} );

			// Sort module descriptors by size, largest first.
			sortByProperty( modules, 'size', true );

			// Convert size to human-readable string.
			modules.forEach( function ( module ) {
				module.sizeInBytes = module.size;
				module.size = humanSize( module.size );
			} );

			return modules;
		},

		/**
		 * For each module with styles, count the number of selectors, and
		 * count how many match against some element currently in the DOM.
		 *
		 * @return {Object[]} CSS reports
		 */
		css: function () {
			var modules = [];

			inspect.getLoadedModules().forEach( function ( name ) {
				var css, stats, module = mw.loader.moduleRegistry[ name ];

				try {
					css = module.style.css.join();
				} catch ( e ) { return; } // skip

				stats = inspect.auditSelectors( css );
				modules.push( {
					module: name,
					allSelectors: stats.total,
					matchedSelectors: stats.matched,
					percentMatched: stats.total !== 0 ?
						( stats.matched / stats.total * 100 ).toFixed( 2 ) + '%' : null
				} );
			} );
			sortByProperty( modules, 'allSelectors', true );
			return modules;
		},

		/**
		 * Report stats on mw.loader.store: the number of localStorage
		 * cache hits and misses, the number of items purged from the
		 * cache, and the total size of the module blob in localStorage.
		 *
		 * @return {Object[]} Store stats
		 */
		store: function () {
			var raw, stats = { enabled: mw.loader.store.enabled };
			if ( stats.enabled ) {
				$.extend( stats, mw.loader.store.stats );
				try {
					raw = localStorage.getItem( mw.loader.store.key );
					stats.totalSizeInBytes = byteLength( raw );
					stats.totalSize = humanSize( byteLength( raw ) );
				} catch ( e ) {}
			}
			return [ stats ];
		},

		/**
		 * Generate a breakdown of all loaded modules and their time
		 * spent during initialisation (measured in milliseconds).
		 *
		 * This timing data is collected by mw.loader.profiler.
		 *
		 * @return {Object[]} Table rows
		 */
		time: function () {
			var modules;

			if ( !mw.loader.profiler ) {
				mw.log.warn( 'mw.inspect: The time report requires $wgResourceLoaderEnableJSProfiler.' );
				return [];
			}

			modules = inspect.getLoadedModules()
				.map( function ( moduleName ) {
					return mw.loader.profiler.getProfile( moduleName );
				} )
				.filter( function ( perf ) {
					// Exclude modules that reached "ready" state without involvement from mw.loader.
					// This is primarily styles-only as loaded via <link rel="stylesheet">.
					return perf !== null;
				} );

			// Sort by total time spent, highest first.
			sortByProperty( modules, 'total', true );

			// Add human-readable strings
			modules.forEach( function ( module ) {
				module.totalInMs = module.total;
				module.total = module.totalInMs.toLocaleString() + ' ms';
			} );

			return modules;
		}
	};

	if ( mw.config.get( 'debug' ) ) {
		mw.log( 'mw.inspect: reports are not available in debug mode.' );
	}

}() );
