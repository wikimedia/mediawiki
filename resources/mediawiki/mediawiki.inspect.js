/*!
 * Tools for inspecting page composition and performance.
 *
 * @author Ori Livneh
 * @since 1.22
 */
/*global console*/
( function ( mw, $ ) {

	/**
	 * Sort an array of objects by common attribute value.
	 *
	 * @param {Array} collection Collection to sort
	 * @param {string} prop Sort on the value of this property
	 * @param {boolean} [ascending=false] Sort in ascending order
	 * @return {Array} A sorted copy of the original array.
	 */
	function sort( collection, prop, ascending ) {
		var order = ascending ? 1 : -1;
		return collection.slice().sort( function ( a, b ) {
			return a[prop] > b[prop] ? order : a[prop] < b[prop] ? -order : 0;
		} );
	}

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
		 * Parse CSS source into an array of rules.
		 *
		 * @param {string} css CSS source to parse
		 * @return {Array} Array of objects implementing the CSSRule DOM interface
		 */
		parseCSS: function ( css ) {
			var doc = document.implementation.createHTMLDocument( '' ),
				style = doc.createElement( 'style' );
			style.textContent = css;
			doc.body.appendChild( style );
			return $.makeArray( style.sheet.rules );
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
			var count = { total: 0, matched: 0 };

			$.each( inspect.parseCSS( css ), function ( index, rule ) {
				var selectors = rule.selectorText.split( ',' );
				$.each( selectors, function ( index, selector ) {
					count.total++;
					if ( $( selector ).length !== 0 ) {
						count.matched++;
					}
				} );
			} );
			return count;
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
		 * @param {array} data Tabular data represented as an array of objects
		 *  with common properties.
		 */
		dumpTable: function ( data ) {
			try {
				console.table( data );
				return;
			} catch (e) {}
			try {
				console.log( JSON.stringify( data, null, 2 ) );
				return;
			} catch (e) {}
			mw.log( data );
		},

		/**
		 * Generate and print one more reports. When invoked with no arguments,
		 * print all reports.
		 *
		 * @param {string} [reports] Space-separated report names to run, or
		 *  unset to print all available reports.
		 */
		report: function ( reports ) {
			reports = reports ?
				reports.split( ' ' )
				: $.map( inspect.reports, function ( v, k ) { return k; } );

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
				modules = sort( modules, 'size' );

				// Convert size to human-readable string.
				$.each( modules, function ( i, module ) {
					module.size = module.size > 1024 ?
						( module.size / 1024 ).toFixed( 2 ) + ' KB' :
						( module.size !== null ? module.size + ' B' : null );
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
					} catch (e) { return; } // skip

					stats = inspect.auditSelectors( css );
					modules.push( {
						module: name,
						allSelectors: stats.total,
						matchedSelectors: stats.matched,
						percentMatched: stats.total !== 0 ?
							( stats.matched / stats.total * 100 ).toFixed( 2 )  + '%'
							: null
					} );
				} );
				return sort( modules, 'allSelectors' );
			},
		}
	};

	if ( mw.config.get( 'debug' ) ) {
		mw.log( 'mw.inspect: reports are not available in debug mode.' );
	}

	mw.inspect = inspect;

}( mediaWiki, jQuery ) );
