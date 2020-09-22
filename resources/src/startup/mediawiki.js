/**
 * Base library for MediaWiki.
 *
 * Exposed globally as `mw`, with `mediaWiki` as alias.
 *
 * @class mw
 * @singleton
 */
/* global $VARS, $CODE */

( function () {
	'use strict';

	var mw, StringSet, log,
		hasOwn = Object.hasOwnProperty,
		console = window.console;

	/**
	 * FNV132 hash function
	 *
	 * This function implements the 32-bit version of FNV-1.
	 * It is equivalent to hash( 'fnv132', ... ) in PHP, except
	 * its output is base 36 rather than hex.
	 * See <https://en.wikipedia.org/wiki/Fowler–Noll–Vo_hash_function>
	 *
	 * @private
	 * @param {string} str String to hash
	 * @return {string} hash as an seven-character base 36 string
	 */
	function fnv132( str ) {
		var hash = 0x811C9DC5,
			i = 0;

		/* eslint-disable no-bitwise */
		for ( ; i < str.length; i++ ) {
			hash += ( hash << 1 ) + ( hash << 4 ) + ( hash << 7 ) + ( hash << 8 ) + ( hash << 24 );
			hash ^= str.charCodeAt( i );
		}

		hash = ( hash >>> 0 ).toString( 36 ).slice( 0, 5 );
		while ( hash.length < 5 ) {
			hash = '0' + hash;
		}
		/* eslint-enable no-bitwise */

		return hash;
	}

	function defineFallbacks() {
		// <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set>
		/**
		 * @private
		 * @class StringSet
		 */
		StringSet = window.Set || function () {
			var set = Object.create( null );
			return {
				add: function ( value ) {
					set[ value ] = true;
				},
				has: function ( value ) {
					return value in set;
				}
			};
		};
	}

	/**
	 * Alias property to the global object.
	 *
	 * @private
	 * @static
	 * @member mw.Map
	 * @param {mw.Map} map
	 * @param {string} key
	 * @param {Mixed} value
	 */
	function setGlobalMapValue( map, key, value ) {
		map.values[ key ] = value;
		log.deprecate(
			window,
			key,
			value,
			// Deprecation notice for mw.config globals (T58550, T72470)
			map === mw.config && 'Use mw.config instead.'
		);
	}

	/**
	 * Log a message to window.console, if possible.
	 *
	 * Useful to force logging of some errors that are otherwise hard to detect (i.e., this logs
	 * also in production mode). Gets console references in each invocation instead of caching the
	 * reference, so that debugging tools loaded later are supported (e.g. Firebug Lite in IE).
	 *
	 * @private
	 * @param {string} topic Stream name passed by mw.track
	 * @param {Object} data Data passed by mw.track
	 * @param {Error} [data.exception]
	 * @param {string} data.source Error source
	 * @param {string} [data.module] Name of module which caused the error
	 */
	function logError( topic, data ) {
		var msg,
			e = data.exception;

		if ( console && console.log ) {
			msg = ( e ? 'Exception' : 'Error' ) +
				' in ' + data.source +
				( data.module ? ' in module ' + data.module : '' ) +
				( e ? ':' : '.' );

			console.log( msg );

			// If we have an exception object, log it to the warning channel to trigger
			// proper stacktraces in browsers that support it.
			if ( e && console.warn ) {
				console.warn( e );
			}
		}
	}

	/**
	 * Create an object that can be read from or written to via methods that allow
	 * interaction both with single and multiple properties at once.
	 *
	 * @private
	 * @class mw.Map
	 *
	 * @constructor
	 * @param {boolean} [global=false] Whether to synchronise =values to the global
	 *  window object (for backwards-compatibility with mw.config; T72470). Values are
	 *  copied in one direction only. Changes to globals do not reflect in the map.
	 */
	function Map( global ) {
		this.values = Object.create( null );
		if ( global === true ) {
			// Override #set to also set the global variable
			this.set = function ( selection, value ) {
				var s;
				if ( arguments.length > 1 ) {
					if ( typeof selection === 'string' ) {
						setGlobalMapValue( this, selection, value );
						return true;
					}
				} else if ( typeof selection === 'object' ) {
					for ( s in selection ) {
						setGlobalMapValue( this, s, selection[ s ] );
					}
					return true;
				}
				return false;
			};
		}
	}

	Map.prototype = {
		constructor: Map,

		/**
		 * Get the value of one or more keys.
		 *
		 * If called with no arguments, all values are returned.
		 *
		 * @param {string|Array} [selection] Key or array of keys to retrieve values for.
		 * @param {Mixed} [fallback=null] Value for keys that don't exist.
		 * @return {Mixed|Object|null} If selection was a string, returns the value,
		 *  If selection was an array, returns an object of key/values.
		 *  If no selection is passed, a new object with all key/values is returned.
		 */
		get: function ( selection, fallback ) {
			var results, i;
			fallback = arguments.length > 1 ? fallback : null;

			if ( Array.isArray( selection ) ) {
				results = {};
				for ( i = 0; i < selection.length; i++ ) {
					if ( typeof selection[ i ] === 'string' ) {
						results[ selection[ i ] ] = selection[ i ] in this.values ?
							this.values[ selection[ i ] ] :
							fallback;
					}
				}
				return results;
			}

			if ( typeof selection === 'string' ) {
				return selection in this.values ?
					this.values[ selection ] :
					fallback;
			}

			if ( selection === undefined ) {
				results = {};
				for ( i in this.values ) {
					results[ i ] = this.values[ i ];
				}
				return results;
			}

			// Invalid selection key
			return fallback;
		},

		/**
		 * Set one or more key/value pairs.
		 *
		 * @param {string|Object} selection Key to set value for, or object mapping keys to values
		 * @param {Mixed} [value] Value to set (optional, only in use when key is a string)
		 * @return {boolean} True on success, false on failure
		 */
		set: function ( selection, value ) {
			var s;
			// Use `arguments.length` because `undefined` is also a valid value.
			if ( arguments.length > 1 ) {
				// Set one key
				if ( typeof selection === 'string' ) {
					this.values[ selection ] = value;
					return true;
				}
			} else if ( typeof selection === 'object' ) {
				// Set multiple keys
				for ( s in selection ) {
					this.values[ s ] = selection[ s ];
				}
				return true;
			}
			return false;
		},

		/**
		 * Check if a given key exists in the map.
		 *
		 * @param {string} selection Key to check
		 * @return {boolean} True if the key exists
		 */
		exists: function ( selection ) {
			return typeof selection === 'string' && selection in this.values;
		}
	};

	defineFallbacks();

	/**
	 * Write a verbose message to the browser's console in debug mode.
	 *
	 * This method is mainly intended for verbose logging. It is a no-op in production mode.
	 * In ResourceLoader debug mode, it will use the browser's console if available.
	 *
	 * See {@link mw.log} for other logging methods.
	 *
	 * @member mw
	 * @param {...string} msg Messages to output to console.
	 */
	log = function () {};

	// Note: Keep list of log methods in sync with restoration in mediawiki.log.js!

	/**
	 * Collection of methods to help log messages to the console.
	 *
	 * @class mw.log
	 * @singleton
	 */

	/**
	 * Write a message to the browser console's warning channel.
	 *
	 * This method is a no-op in browsers that don't implement the Console API.
	 *
	 * @param {...string} msg Messages to output to console
	 */
	log.warn = console && console.warn ?
		Function.prototype.bind.call( console.warn, console ) :
		function () {};

	/**
	 * Write a message to the browser console's error channel.
	 *
	 * Most browsers also print a stacktrace when calling this method if the
	 * argument is an Error object.
	 *
	 * This method is a no-op in browsers that don't implement the Console API.
	 *
	 * @since 1.26
	 * @param {...Mixed} msg Messages to output to console
	 */
	log.error = console && console.error ?
		Function.prototype.bind.call( console.error, console ) :
		function () {};

	/**
	 * Create a property on a host object that, when accessed, will produce
	 * a deprecation warning in the console.
	 *
	 * @param {Object} obj Host object of deprecated property
	 * @param {string} key Name of property to create in `obj`
	 * @param {Mixed} val The value this property should return when accessed
	 * @param {string} [msg] Optional text to include in the deprecation message
	 * @param {string} [logName] Name for the feature for logging and tracking
	 *  purposes. Except for properties of the window object, tracking is only
	 *  enabled if logName is set.
	 */
	log.deprecate = function ( obj, key, val, msg, logName ) {
		var stacks;
		function maybeLog() {
			var name = logName || key,
				trace = new Error().stack;
			if ( !stacks ) {
				stacks = new StringSet();
			}
			if ( !stacks.has( trace ) ) {
				stacks.add( trace );
				if ( logName || obj === window ) {
					mw.track( 'mw.deprecate', name );
				}
				mw.log.warn(
					'Use of "' + name + '" is deprecated.' + ( msg ? ' ' + msg : '' )
				);
			}
		}
		// Support: Safari 5.0
		// Throws "not supported on DOM Objects" for Node or Element objects (incl. document)
		// Safari 4.0 doesn't have this method, and it was fixed in Safari 5.1.
		try {
			Object.defineProperty( obj, key, {
				configurable: true,
				enumerable: true,
				get: function () {
					maybeLog();
					return val;
				},
				set: function ( newVal ) {
					maybeLog();
					val = newVal;
				}
			} );
		} catch ( err ) {
			obj[ key ] = val;
		}
	};

	/**
	 * @class mw
	 */
	mw = {
		redefineFallbacksForTest: window.QUnit && defineFallbacks,

		/**
		 * Get the current time, measured in milliseconds since January 1, 1970 (UTC).
		 *
		 * On browsers that implement the Navigation Timing API, this function will produce
		 * floating-point values with microsecond precision that are guaranteed to be monotonic.
		 * On all other browsers, it will fall back to using `Date`.
		 *
		 * @return {number} Current time
		 */
		now: function () {
			// Optimisation: Make startup initialisation faster by defining the
			// shortcut on first call, not at module definition.
			var perf = window.performance,
				navStart = perf && perf.timing && perf.timing.navigationStart;

			// Define the relevant shortcut
			mw.now = navStart && perf.now ?
				function () { return navStart + perf.now(); } :
				Date.now;

			return mw.now();
		},

		/**
		 * List of all analytic events emitted so far.
		 *
		 * Exposed only for use by mediawiki.base.
		 *
		 * @private
		 * @property {Array}
		 */
		trackQueue: [],

		track: function ( topic, data ) {
			mw.trackQueue.push( { topic: topic, data: data } );
			// This method is extended by mediawiki.base to also fire events.
		},

		/**
		 * Track an early error event via mw.track and send it to the window console.
		 *
		 * @private
		 * @param {string} topic Topic name
		 * @param {Object} data Data describing the event, encoded as an object; see mw#logError
		 */
		trackError: function ( topic, data ) {
			mw.track( topic, data );
			logError( topic, data );
		},

		// Expose Map constructor
		Map: Map,

		/**
		 * Map of configuration values.
		 *
		 * Check out [the complete list of configuration values](https://www.mediawiki.org/wiki/Manual:Interface/JavaScript#mw.config)
		 * on mediawiki.org.
		 *
		 * If `$wgLegacyJavaScriptGlobals` is true, this Map will add its values to the
		 * global `window` object.
		 *
		 * @property {mw.Map} config
		 */
		config: new Map( $VARS.wgLegacyJavaScriptGlobals ),

		/**
		 * Store for messages.
		 *
		 * @property {mw.Map}
		 */
		messages: new Map(),

		/**
		 * Store for templates associated with a module.
		 *
		 * @property {mw.Map}
		 */
		templates: new Map(),

		// Expose mw.log
		log: log,

		/**
		 * Client for ResourceLoader server end point.
		 *
		 * This client is in charge of maintaining the module registry and state
		 * machine, initiating network (batch) requests for loading modules, as
		 * well as dependency resolution and execution of source code.
		 *
		 * For more information, refer to
		 * <https://www.mediawiki.org/wiki/ResourceLoader/Features>
		 *
		 * @class mw.loader
		 * @singleton
		 */
		loader: ( function () {

			/**
			 * Fired via mw.track on various resource loading errors.
			 *
			 * @event resourceloader_exception
			 * @param {Error|Mixed} e The error that was thrown. Almost always an Error
			 *   object, but in theory module code could manually throw something else, and that
			 *   might also end up here.
			 * @param {string} [module] Name of the module which caused the error. Omitted if the
			 *   error is not module-related or the module cannot be easily identified due to
			 *   batched handling.
			 * @param {string} source Source of the error. Possible values:
			 *
			 *   - style: stylesheet error (only affects old IE where a special style loading method
			 *     is used)
			 *   - load-callback: exception thrown by user callback
			 *   - module-execute: exception thrown by module code
			 *   - resolve: failed to sort dependencies for a module in mw.loader.load
			 *   - store-eval: could not evaluate module code cached in localStorage
			 *   - store-localstorage-init: localStorage or JSON parse error in mw.loader.store.init
			 *   - store-localstorage-json: JSON conversion error in mw.loader.store
			 *   - store-localstorage-update: localStorage conversion error in mw.loader.store.
			 */

			/**
			 * Fired via mw.track on resource loading error conditions.
			 *
			 * @event resourceloader_assert
			 * @param {string} source Source of the error. Possible values:
			 *
			 *   - bug-T59567: failed to cache script due to an Opera function -> string conversion
			 *     bug; see <https://phabricator.wikimedia.org/T59567> for details
			 */

			/**
			 * Mapping of registered modules.
			 *
			 * See #implement and #execute for exact details on support for script, style and messages.
			 *
			 *     @example Format:
			 *
			 *     {
			 *         'moduleName': {
			 *             // From mw.loader.register()
			 *             'version': '########' (hash)
			 *             'dependencies': ['required.foo', 'bar.also', ...]
			 *             'group': string, integer, (or) null
			 *             'source': 'local', (or) 'anotherwiki'
			 *             'skip': 'return !!window.Example', (or) null, (or) boolean result of skip
			 *             'module': export Object
			 *
			 *             // Set from execute() or mw.loader.state()
			 *             'state': 'registered', 'loaded', 'loading', 'ready', 'error', or 'missing'
			 *
			 *             // Optionally added at run-time by mw.loader.implement()
			 *             'script': closure, array of urls, or string
			 *             'style': { ... } (see #execute)
			 *             'messages': { 'key': 'value', ... }
			 *         }
			 *     }
			 *
			 * State machine:
			 *
			 * - `registered`:
			 *    The module is known to the system but not yet required.
			 *    Meta data is registered via mw.loader#register. Calls to that method are
			 *    generated server-side by the startup module.
			 * - `loading`:
			 *    The module was required through mw.loader (either directly or as dependency of
			 *    another module). The client will fetch module contents from the server.
			 *    The contents are then stashed in the registry via mw.loader#implement.
			 * - `loaded`:
			 *    The module has been loaded from the server and stashed via mw.loader#implement.
			 *    Once the module has no more dependencies in-flight, the module will be executed,
			 *    controlled via #requestPropagation and #doPropagation.
			 * - `executing`:
			 *    The module is being executed.
			 * - `ready`:
			 *    The module has been successfully executed.
			 * - `error`:
			 *    The module (or one of its dependencies) produced an error during execution.
			 * - `missing`:
			 *    The module was registered client-side and requested, but the server denied knowledge
			 *    of the module's existence.
			 *
			 * @property {Object}
			 * @private
			 */
			var registry = Object.create( null ),
				// Mapping of sources, keyed by source-id, values are strings.
				//
				// Format:
				//
				//     {
				//         'sourceId': 'http://example.org/w/load.php'
				//     }
				//
				sources = Object.create( null ),

				// For queueModuleScript()
				handlingPendingRequests = false,
				pendingRequests = [],

				// List of modules to be loaded
				queue = [],

				/**
				 * List of callback jobs waiting for modules to be ready.
				 *
				 * Jobs are created by #enqueue() and run by #doPropagation().
				 * Typically when a job is created for a module, the job's dependencies contain
				 * both the required module and all its recursive dependencies.
				 *
				 *     @example Format:
				 *
				 *     {
				 *         'dependencies': [ module names ],
				 *         'ready': Function callback
				 *         'error': Function callback
				 *     }
				 *
				 * @property {Object[]} jobs
				 * @private
				 */
				jobs = [],

				// For #requestPropagation() and #doPropagation()
				willPropagate = false,
				errorModules = [],

				/**
				 * @private
				 * @property {Array} baseModules
				 */
				baseModules = $VARS.baseModules,

				/**
				 * For #addEmbeddedCSS() and #addLink()
				 *
				 * @private
				 * @property {HTMLElement|null} marker
				 */
				marker = document.querySelector( 'meta[name="ResourceLoaderDynamicStyles"]' ),

				// For #addEmbeddedCSS()
				lastCssBuffer,
				rAF = window.requestAnimationFrame || setTimeout;

			/**
			 * Create a new style element and add it to the DOM.
			 *
			 * @private
			 * @param {string} text CSS text
			 * @param {Node|null} [nextNode] The element where the style tag
			 *  should be inserted before
			 * @return {HTMLElement} Reference to the created style element
			 */
			function newStyleTag( text, nextNode ) {
				var el = document.createElement( 'style' );
				el.appendChild( document.createTextNode( text ) );
				if ( nextNode && nextNode.parentNode ) {
					nextNode.parentNode.insertBefore( el, nextNode );
				} else {
					document.head.appendChild( el );
				}
				return el;
			}

			/**
			 * @private
			 * @param {Object} cssBuffer
			 */
			function flushCssBuffer( cssBuffer ) {
				var i;
				// Make sure the next call to addEmbeddedCSS() starts a new buffer.
				// This must be done before we run the callbacks, as those may end up
				// queueing new chunks which would be lost otherwise (T105973).
				//
				// There can be more than one buffer in-flight (given "@import", and
				// generally due to race conditions). Only tell addEmbeddedCSS() to
				// start a new buffer if we're currently flushing the last one that it
				// started. If we're flushing an older buffer, keep the last one open.
				if ( cssBuffer === lastCssBuffer ) {
					lastCssBuffer = null;
				}
				newStyleTag( cssBuffer.cssText, marker );
				for ( i = 0; i < cssBuffer.callbacks.length; i++ ) {
					cssBuffer.callbacks[ i ]();
				}
			}

			/**
			 * Add a bit of CSS text to the current browser page.
			 *
			 * The creation and insertion of the `<style>` element is debounced for two reasons:
			 *
			 * - Performing the insertion before the next paint round via requestAnimationFrame
			 *   avoids forced or wasted style recomputations, which are expensive in browsers.
			 * - Reduce how often new stylesheets are inserted by letting additional calls to this
			 *   function accumulate into a buffer for at least one JavaScript tick. Modules are
			 *   received from the server in batches, which means there is likely going to be many
			 *   calls to this function in a row within the same tick / the same call stack.
			 *   See also T47810.
			 *
			 * @private
			 * @param {string} cssText CSS text to be added in a `<style>` tag.
			 * @param {Function} callback Called after the insertion has occurred.
			 */
			function addEmbeddedCSS( cssText, callback ) {
				// Start a new buffer if one of the following is true:
				// - We've never started a buffer before, this will be our first.
				// - The last buffer we created was flushed meanwhile, so start a new one.
				// - The next CSS chunk syntactically needs to be at the start of a stylesheet (T37562).
				if ( !lastCssBuffer || cssText.slice( 0, '@import'.length ) === '@import' ) {
					lastCssBuffer = {
						cssText: '',
						callbacks: []
					};
					rAF( flushCssBuffer.bind( null, lastCssBuffer ) );
				}

				// Linebreak for somewhat distinguishable sections
				lastCssBuffer.cssText += '\n' + cssText;
				lastCssBuffer.callbacks.push( callback );
			}

			/**
			 * @private
			 * @param {string[]} modules List of module names
			 * @return {string} Hash of concatenated version hashes.
			 */
			function getCombinedVersion( modules ) {
				var hashes = modules.reduce( function ( result, module ) {
					return result + registry[ module ].version;
				}, '' );
				return fnv132( hashes );
			}

			/**
			 * Determine whether all dependencies are in state 'ready', which means we may
			 * execute the module or job now.
			 *
			 * @private
			 * @param {string[]} modules Names of modules to be checked
			 * @return {boolean} True if all modules are in state 'ready', false otherwise
			 */
			function allReady( modules ) {
				var i = 0;
				for ( ; i < modules.length; i++ ) {
					if ( mw.loader.getState( modules[ i ] ) !== 'ready' ) {
						return false;
					}
				}
				return true;
			}

			/**
			 * Determine whether all direct and base dependencies are in state 'ready'
			 *
			 * @private
			 * @param {string} module Name of the module to be checked
			 * @return {boolean} True if all direct/base dependencies are in state 'ready'; false otherwise
			 */
			function allWithImplicitReady( module ) {
				return allReady( registry[ module ].dependencies ) &&
					( baseModules.indexOf( module ) !== -1 || allReady( baseModules ) );
			}

			/**
			 * Determine whether all dependencies are in state 'ready', which means we may
			 * execute the module or job now.
			 *
			 * @private
			 * @param {Array} modules Names of modules to be checked
			 * @return {boolean} True if no modules are in state 'error' or 'missing', false otherwise
			 */
			function anyFailed( modules ) {
				var state,
					i = 0;
				for ( ; i < modules.length; i++ ) {
					state = mw.loader.getState( modules[ i ] );
					if ( state === 'error' || state === 'missing' ) {
						return true;
					}
				}
				return false;
			}

			/**
			 * Handle propagation of module state changes and reactions to them.
			 *
			 * - When a module reaches a failure state, this should be propagated to
			 *   modules that depend on the failed module.
			 * - When a module reaches a final state, pending job callbacks for the
			 *   module from mw.loader.using() should be called.
			 * - When a module reaches the 'ready' state from #execute(), consider
			 *   executing dependant modules now having their dependencies satisfied.
			 * - When a module reaches the 'loaded' state from mw.loader.implement,
			 *   consider executing it, if it has no unsatisfied dependencies.
			 *
			 * @private
			 */
			function doPropagation() {
				var errorModule, baseModuleError, module, i, failed, job,
					didPropagate = true;

				// Keep going until the last iteration performed no actions.
				do {
					didPropagate = false;

					// Stage 1: Propagate failures
					while ( errorModules.length ) {
						errorModule = errorModules.shift();
						baseModuleError = baseModules.indexOf( errorModule ) !== -1;
						for ( module in registry ) {
							if ( registry[ module ].state !== 'error' && registry[ module ].state !== 'missing' ) {
								if ( baseModuleError && baseModules.indexOf( module ) === -1 ) {
									// Propate error from base module to all regular (non-base) modules
									registry[ module ].state = 'error';
									didPropagate = true;
								} else if ( registry[ module ].dependencies.indexOf( errorModule ) !== -1 ) {
									// Propagate error from dependency to depending module
									registry[ module ].state = 'error';
									// .. and propagate it further
									errorModules.push( module );
									didPropagate = true;
								}
							}
						}
					}

					// Stage 2: Execute 'loaded' modules with no unsatisfied dependencies
					for ( module in registry ) {
						if ( registry[ module ].state === 'loaded' && allWithImplicitReady( module ) ) {
							// Recursively execute all dependent modules that were already loaded
							// (waiting for execution) and no longer have unsatisfied dependencies.
							// Base modules may have dependencies amongst eachother to ensure correct
							// execution order. Regular modules wait for all base modules.
							// eslint-disable-next-line no-use-before-define
							execute( module );
							didPropagate = true;
						}
					}

					// Stage 3: Invoke job callbacks that are no longer blocked
					for ( i = 0; i < jobs.length; i++ ) {
						job = jobs[ i ];
						failed = anyFailed( job.dependencies );
						if ( failed || allReady( job.dependencies ) ) {
							jobs.splice( i, 1 );
							i -= 1;
							try {
								if ( failed && job.error ) {
									job.error( new Error( 'Failed dependencies' ), job.dependencies );
								} else if ( !failed && job.ready ) {
									job.ready();
								}
							} catch ( e ) {
								// A user-defined callback raised an exception.
								// Swallow it to protect our state machine!
								mw.trackError( 'resourceloader.exception', {
									exception: e,
									source: 'load-callback'
								} );
							}
							didPropagate = true;
						}
					}
				} while ( didPropagate );

				willPropagate = false;
			}

			/**
			 * Request a (debounced) call to doPropagation().
			 *
			 * @private
			 */
			function requestPropagation() {
				if ( willPropagate ) {
					// Already scheduled, or, we're already in a doPropagation stack.
					return;
				}
				willPropagate = true;
				// Yield for two reasons:
				// * Allow successive calls to mw.loader.implement() from the same
				//   load.php response, or from the same asyncEval() to be in the
				//   propagation batch.
				// * Allow the browser to breathe between the reception of
				//   module source code and the execution of it.
				//
				// Use a high priority because the user may be waiting for interactions
				// to start being possible. But, first provide a moment (up to 'timeout')
				// for native input event handling (e.g. scrolling/typing/clicking).
				mw.requestIdleCallback( doPropagation, { timeout: 1 } );
			}

			/**
			 * Update a module's state in the registry and make sure any neccesary
			 * propagation will occur. See #doPropagation for more about propagation.
			 * See #registry for more about how states are used.
			 *
			 * @private
			 * @param {string} module
			 * @param {string} state
			 */
			function setAndPropagate( module, state ) {
				registry[ module ].state = state;
				if ( state === 'loaded' || state === 'ready' || state === 'error' || state === 'missing' ) {
					if ( state === 'ready' ) {
						// Queue to later be synced to the local module store.
						mw.loader.store.add( module );
					} else if ( state === 'error' || state === 'missing' ) {
						errorModules.push( module );
					}
					requestPropagation();
				}
			}

			/**
			 * Resolve dependencies and detect circular references.
			 *
			 * @private
			 * @param {string} module Name of the top-level module whose dependencies shall be
			 *  resolved and sorted.
			 * @param {Array} resolved Returns a topological sort of the given module and its
			 *  dependencies, such that later modules depend on earlier modules. The array
			 *  contains the module names. If the array contains already some module names,
			 *  this function appends its result to the pre-existing array.
			 * @param {StringSet} [unresolved] Used to detect loops in the dependency graph.
			 * @throws {Error} If an unknown module or a circular dependency is encountered
			 */
			function sortDependencies( module, resolved, unresolved ) {
				var i, skip, deps;

				if ( !( module in registry ) ) {
					throw new Error( 'Unknown module: ' + module );
				}

				if ( typeof registry[ module ].skip === 'string' ) {
					// eslint-disable-next-line no-new-func
					skip = ( new Function( registry[ module ].skip )() );
					registry[ module ].skip = !!skip;
					if ( skip ) {
						registry[ module ].dependencies = [];
						setAndPropagate( module, 'ready' );
						return;
					}
				}

				// Create unresolved if not passed in
				if ( !unresolved ) {
					unresolved = new StringSet();
				}

				// Track down dependencies
				deps = registry[ module ].dependencies;
				unresolved.add( module );
				for ( i = 0; i < deps.length; i++ ) {
					if ( resolved.indexOf( deps[ i ] ) === -1 ) {
						if ( unresolved.has( deps[ i ] ) ) {
							throw new Error(
								'Circular reference detected: ' + module + ' -> ' + deps[ i ]
							);
						}

						sortDependencies( deps[ i ], resolved, unresolved );
					}
				}

				resolved.push( module );
			}

			/**
			 * Get names of module that a module depends on, in their proper dependency order.
			 *
			 * @private
			 * @param {string[]} modules Array of string module names
			 * @return {Array} List of dependencies, including 'module'.
			 * @throws {Error} If an unregistered module or a dependency loop is encountered
			 */
			function resolve( modules ) {
				// Always load base modules
				var resolved = baseModules.slice(),
					i = 0;
				for ( ; i < modules.length; i++ ) {
					sortDependencies( modules[ i ], resolved );
				}
				return resolved;
			}

			/**
			 * Like #resolve(), except it will silently ignore modules that
			 * are missing or have missing dependencies.
			 *
			 * @private
			 * @param {string[]} modules Array of string module names
			 * @return {Array} List of dependencies.
			 */
			function resolveStubbornly( modules ) {
				var saved,
					// Always load base modules
					resolved = baseModules.slice(),
					i = 0;
				for ( ; i < modules.length; i++ ) {
					saved = resolved.slice();
					try {
						sortDependencies( modules[ i ], resolved );
					} catch ( err ) {
						// This module is not currently known, or has invalid dependencies.
						// Most likely due to a cached reference after the module was
						// removed, otherwise made redundant, or omitted from the registry
						// by the ResourceLoader "target" system.
						resolved = saved;
						mw.log.warn( 'Skipped unresolvable module ' + modules[ i ] );
						if ( modules[ i ] in registry ) {
							// If the module was known but had unknown or circular dependencies,
							// also track it as an error.
							mw.trackError( 'resourceloader.exception', {
								exception: err,
								source: 'resolve'
							} );
						}
					}
				}
				return resolved;
			}

			/**
			 * Resolve a relative file path.
			 *
			 * For example, resolveRelativePath( '../foo.js', 'resources/src/bar/bar.js' )
			 * returns 'resources/src/foo.js'.
			 *
			 * @private
			 * @param {string} relativePath Relative file path, starting with ./ or ../
			 * @param {string} basePath Path of the file (not directory) relativePath is relative to
			 * @return {string|null} Resolved path, or null if relativePath does not start with ./ or ../
			 */
			function resolveRelativePath( relativePath, basePath ) {
				var prefixes, prefix, baseDirParts,
					relParts = relativePath.match( /^((?:\.\.?\/)+)(.*)$/ );

				if ( !relParts ) {
					return null;
				}

				baseDirParts = basePath.split( '/' );
				// basePath looks like 'foo/bar/baz.js', so baseDirParts looks like [ 'foo', 'bar, 'baz.js' ]
				// Remove the file component at the end, so that we are left with only the directory path
				baseDirParts.pop();

				prefixes = relParts[ 1 ].split( '/' );
				// relParts[ 1 ] looks like '../../', so prefixes looks like [ '..', '..', '' ]
				// Remove the empty element at the end
				prefixes.pop();

				// For every ../ in the path prefix, remove one directory level from baseDirParts
				while ( ( prefix = prefixes.pop() ) !== undefined ) {
					if ( prefix === '..' ) {
						baseDirParts.pop();
					}
				}

				// If there's anything left of the base path, prepend it to the file path
				return ( baseDirParts.length ? baseDirParts.join( '/' ) + '/' : '' ) + relParts[ 2 ];
			}

			/**
			 * Make a require() function scoped to a package file
			 *
			 * @private
			 * @param {Object} moduleObj Module object from the registry
			 * @param {string} basePath Path of the file this is scoped to. Used for relative paths.
			 * @return {Function}
			 */
			function makeRequireFunction( moduleObj, basePath ) {
				return function require( moduleName ) {
					var fileName, fileContent, result, moduleParam,
						scriptFiles = moduleObj.script.files;
					fileName = resolveRelativePath( moduleName, basePath );
					if ( fileName === null ) {
						// Not a relative path, so it's a module name
						return mw.loader.require( moduleName );
					}

					if ( !hasOwn.call( scriptFiles, fileName ) ) {
						throw new Error( 'Cannot require undefined file ' + fileName );
					}
					if ( hasOwn.call( moduleObj.packageExports, fileName ) ) {
						// File has already been executed, return the cached result
						return moduleObj.packageExports[ fileName ];
					}

					fileContent = scriptFiles[ fileName ];
					if ( typeof fileContent === 'function' ) {
						moduleParam = { exports: {} };
						fileContent( makeRequireFunction( moduleObj, fileName ), moduleParam );
						result = moduleParam.exports;
					} else {
						// fileContent is raw data, just pass it through
						result = fileContent;
					}
					moduleObj.packageExports[ fileName ] = result;
					return result;
				};
			}

			/**
			 * Load and execute a script.
			 *
			 * @private
			 * @param {string} src URL to script, will be used as the src attribute in the script tag
			 * @param {Function} [callback] Callback to run after request resolution
			 */
			function addScript( src, callback ) {
				// Use a <script> element rather than XHR. Using XHR changes the request
				// headers (potentially missing a cache hit), and reduces caching in general
				// since browsers cache XHR much less (if at all). And XHR means we retrieve
				// text, so we'd need to eval, which then messes up line numbers.
				// The drawback is that <script> does not offer progress events, feedback is
				// only given after downloading, parsing, and execution have completed.
				var script = document.createElement( 'script' );
				script.src = src;
				script.onload = script.onerror = function () {
					if ( script.parentNode ) {
						script.parentNode.removeChild( script );
					}
					if ( callback ) {
						callback();
						callback = null;
					}
				};
				document.head.appendChild( script );
			}

			/**
			 * Queue the loading and execution of a script for a particular module.
			 *
			 * This does for debug mode what runScript() does for production.
			 *
			 * @private
			 * @param {string} src URL of the script
			 * @param {string} moduleName Name of currently executing module
			 * @param {Function} callback Callback to run after addScript() resolution
			 */
			function queueModuleScript( src, moduleName, callback ) {
				pendingRequests.push( function () {
					// Keep in sync with execute()/runScript().
					if ( moduleName !== 'jquery' ) {
						window.require = mw.loader.require;
						window.module = registry[ moduleName ].module;
					}
					addScript( src, function () {
						// 'module.exports' should not persist after the file is executed to
						// avoid leakage to unrelated code. 'require' should be kept, however,
						// as asynchronous access to 'require' is allowed and expected. (T144879)
						delete window.module;
						callback();
						// Start the next one (if any)
						if ( pendingRequests[ 0 ] ) {
							pendingRequests.shift()();
						} else {
							handlingPendingRequests = false;
						}
					} );
				} );
				if ( !handlingPendingRequests && pendingRequests[ 0 ] ) {
					handlingPendingRequests = true;
					pendingRequests.shift()();
				}
			}

			/**
			 * Utility function for execute()
			 *
			 * @ignore
			 * @param {string} url URL
			 * @param {string} [media] Media attribute
			 * @param {Node|null} [nextNode]
			 */
			function addLink( url, media, nextNode ) {
				var el = document.createElement( 'link' );

				el.rel = 'stylesheet';
				if ( media ) {
					el.media = media;
				}
				// If you end up here from an IE exception "SCRIPT: Invalid property value.",
				// see #addEmbeddedCSS, T33676, T43331, and T49277 for details.
				el.href = url;

				if ( nextNode && nextNode.parentNode ) {
					nextNode.parentNode.insertBefore( el, nextNode );
				} else {
					document.head.appendChild( el );
				}
			}

			/**
			 * @private
			 * @param {string} code JavaScript code
			 */
			function domEval( code ) {
				var script = document.createElement( 'script' );
				if ( mw.config.get( 'wgCSPNonce' ) !== false ) {
					script.nonce = mw.config.get( 'wgCSPNonce' );
				}
				script.text = code;
				document.head.appendChild( script );
				script.parentNode.removeChild( script );
			}

			/**
			 * Add one or more modules to the module load queue.
			 *
			 * See also #work().
			 *
			 * @private
			 * @param {string[]} dependencies Array of module names in the registry
			 * @param {Function} [ready] Callback to execute when all dependencies are ready
			 * @param {Function} [error] Callback to execute when any dependency fails
			 */
			function enqueue( dependencies, ready, error ) {
				if ( allReady( dependencies ) ) {
					// Run ready immediately
					if ( ready !== undefined ) {
						ready();
					}
					return;
				}

				if ( anyFailed( dependencies ) ) {
					if ( error !== undefined ) {
						// Execute error immediately if any dependencies have errors
						error(
							new Error( 'One or more dependencies failed to load' ),
							dependencies
						);
					}
					return;
				}

				// Not all dependencies are ready, add to the load queue...

				// Add ready and error callbacks if they were given
				if ( ready !== undefined || error !== undefined ) {
					jobs.push( {
						// Narrow down the list to modules that are worth waiting for
						dependencies: dependencies.filter( function ( module ) {
							var state = registry[ module ].state;
							return state === 'registered' || state === 'loaded' || state === 'loading' || state === 'executing';
						} ),
						ready: ready,
						error: error
					} );
				}

				dependencies.forEach( function ( module ) {
					// Only queue modules that are still in the initial 'registered' state
					// (e.g. not ones already loading or loaded etc.).
					if ( registry[ module ].state === 'registered' && queue.indexOf( module ) === -1 ) {
						queue.push( module );
					}
				} );

				mw.loader.work();
			}

			/**
			 * Executes a loaded module, making it ready to use
			 *
			 * @private
			 * @param {string} module Module name to execute
			 */
			function execute( module ) {
				var key, value, media, i, urls, cssHandle, siteDeps, siteDepErr, runScript,
					cssPending = 0;

				if ( registry[ module ].state !== 'loaded' ) {
					throw new Error( 'Module in state "' + registry[ module ].state + '" may not execute: ' + module );
				}

				registry[ module ].state = 'executing';
				$CODE.profileExecuteStart();

				runScript = function () {
					var script, markModuleReady, nestedAddScript, mainScript;

					$CODE.profileScriptStart();
					script = registry[ module ].script;
					markModuleReady = function () {
						$CODE.profileScriptEnd();
						setAndPropagate( module, 'ready' );
					};
					nestedAddScript = function ( arr, callback, j ) {
						// Recursively call queueModuleScript() in its own callback
						// for each element of arr.
						if ( j >= arr.length ) {
							// We're at the end of the array
							callback();
							return;
						}

						queueModuleScript( arr[ j ], module, function () {
							nestedAddScript( arr, callback, j + 1 );
						} );
					};

					try {
						if ( Array.isArray( script ) ) {
							nestedAddScript( script, markModuleReady, 0 );
						} else if (
							typeof script === 'function' || (
								typeof script === 'object' &&
								script !== null
							)
						) {
							if ( typeof script === 'function' ) {
								// Keep in sync with queueModuleScript() for debug mode
								if ( module === 'jquery' ) {
									// This is a special case for when 'jquery' itself is being loaded.
									// - The standard jquery.js distribution does not set `window.jQuery`
									//   in CommonJS-compatible environments (Node.js, AMD, RequireJS, etc.).
									// - MediaWiki's 'jquery' module also bundles jquery.migrate.js, which
									//   in a CommonJS-compatible environment, will use require('jquery'),
									//   but that can't work when we're still inside that module.
									script();
								} else {
									// Pass jQuery twice so that the signature of the closure which wraps
									// the script can bind both '$' and 'jQuery'.
									script( window.$, window.$, mw.loader.require, registry[ module ].module );
								}
							} else {
								mainScript = script.files[ script.main ];
								if ( typeof mainScript !== 'function' ) {
									throw new Error( 'Main file in module ' + module + ' must be a function' );
								}
								// jQuery parameters are not passed for multi-file modules
								mainScript(
									makeRequireFunction( registry[ module ], script.main ),
									registry[ module ].module
								);
							}
							markModuleReady();
						} else if ( typeof script === 'string' ) {
							// Site and user modules are legacy scripts that run in the global scope.
							// This is transported as a string instead of a function to avoid needing
							// to use string manipulation to undo the function wrapper.
							domEval( script );
							markModuleReady();

						} else {
							// Module without script
							markModuleReady();
						}
					} catch ( e ) {
						// Use mw.track instead of mw.log because these errors are common in production mode
						// (e.g. undefined variable), and mw.log is only enabled in debug mode.
						setAndPropagate( module, 'error' );
						$CODE.profileScriptEnd();
						mw.trackError( 'resourceloader.exception', {
							exception: e,
							module: module,
							source: 'module-execute'
						} );
					}
				};

				// Add localizations to message system
				if ( registry[ module ].messages ) {
					mw.messages.set( registry[ module ].messages );
				}

				// Initialise templates
				if ( registry[ module ].templates ) {
					mw.templates.set( module, registry[ module ].templates );
				}

				// Adding of stylesheets is asynchronous via addEmbeddedCSS().
				// The below function uses a counting semaphore to make sure we don't call
				// runScript() until after this module's stylesheets have been inserted
				// into the DOM.
				cssHandle = function () {
					// Increase semaphore, when creating a callback for addEmbeddedCSS.
					cssPending++;
					return function () {
						var runScriptCopy;
						// Decrease semaphore, when said callback is invoked.
						cssPending--;
						if ( cssPending === 0 ) {
							// Paranoia:
							// This callback is exposed to addEmbeddedCSS, which is outside the execute()
							// function and is not concerned with state-machine integrity. In turn,
							// addEmbeddedCSS() actually exposes stuff further into the browser (rAF).
							// If increment and decrement callbacks happen in the wrong order, or start
							// again afterwards, then this branch could be reached multiple times.
							// To protect the integrity of the state-machine, prevent that from happening
							// by making runScript() cannot be called more than once.  We store a private
							// reference when we first reach this branch, then deference the original, and
							// call our reference to it.
							runScriptCopy = runScript;
							runScript = undefined;
							runScriptCopy();
						}
					};
				};

				// Process styles (see also mw.loader.implement)
				// * back-compat: { <media>: css }
				// * back-compat: { <media>: [url, ..] }
				// * { "css": [css, ..] }
				// * { "url": { <media>: [url, ..] } }
				if ( registry[ module ].style ) {
					for ( key in registry[ module ].style ) {
						value = registry[ module ].style[ key ];
						media = undefined;

						if ( key !== 'url' && key !== 'css' ) {
							// Backwards compatibility, key is a media-type
							if ( typeof value === 'string' ) {
								// back-compat: { <media>: css }
								// Ignore 'media' because it isn't supported (nor was it used).
								// Strings are pre-wrapped in "@media". The media-type was just ""
								// (because it had to be set to something).
								// This is one of the reasons why this format is no longer used.
								addEmbeddedCSS( value, cssHandle() );
							} else {
								// back-compat: { <media>: [url, ..] }
								media = key;
								key = 'bc-url';
							}
						}

						// Array of css strings in key 'css',
						// or back-compat array of urls from media-type
						if ( Array.isArray( value ) ) {
							for ( i = 0; i < value.length; i++ ) {
								if ( key === 'bc-url' ) {
									// back-compat: { <media>: [url, ..] }
									addLink( value[ i ], media, marker );
								} else if ( key === 'css' ) {
									// { "css": [css, ..] }
									addEmbeddedCSS( value[ i ], cssHandle() );
								}
							}
						// Not an array, but a regular object
						// Array of urls inside media-type key
						} else if ( typeof value === 'object' ) {
							// { "url": { <media>: [url, ..] } }
							for ( media in value ) {
								urls = value[ media ];
								for ( i = 0; i < urls.length; i++ ) {
									addLink( urls[ i ], media, marker );
								}
							}
						}
					}
				}

				// End profiling of execute()-self before we call runScript(),
				// which we want to measure separately without overlap.
				$CODE.profileExecuteEnd();

				if ( module === 'user' ) {
					// Implicit dependency on the site module. Not a real dependency because it should
					// run after 'site' regardless of whether it succeeds or fails.
					// Note: This is a simplified version of mw.loader.using(), inlined here because
					// mw.loader.using() is part of mediawiki.base (depends on jQuery; T192623).
					try {
						siteDeps = resolve( [ 'site' ] );
					} catch ( e ) {
						siteDepErr = e;
						runScript();
					}
					if ( siteDepErr === undefined ) {
						enqueue( siteDeps, runScript, runScript );
					}
				} else if ( cssPending === 0 ) {
					// Regular module without styles
					runScript();
				}
				// else: runScript will get called via cssHandle()
			}

			function sortQuery( o ) {
				var key,
					sorted = {},
					a = [];

				for ( key in o ) {
					a.push( key );
				}
				a.sort();
				for ( key = 0; key < a.length; key++ ) {
					sorted[ a[ key ] ] = o[ a[ key ] ];
				}
				return sorted;
			}

			/**
			 * Converts a module map of the form `{ foo: [ 'bar', 'baz' ], bar: [ 'baz, 'quux' ] }`
			 * to a query string of the form `foo.bar,baz|bar.baz,quux`.
			 *
			 * See `ResourceLoader::makePackedModulesString()` in PHP, of which this is a port.
			 * On the server, unpacking is done by `ResourceLoader::expandModuleNames()`.
			 *
			 * Note: This is only half of the logic, the other half has to be in #batchRequest(),
			 * because its implementation needs to keep track of potential string size in order
			 * to decide when to split the requests due to url size.
			 *
			 * @private
			 * @param {Object} moduleMap Module map
			 * @return {Object}
			 * @return {string} return.str Module query string
			 * @return {Array} return.list List of module names in matching order
			 */
			function buildModulesString( moduleMap ) {
				var p, prefix,
					str = [],
					list = [];

				function restore( suffix ) {
					return p + suffix;
				}

				for ( prefix in moduleMap ) {
					p = prefix === '' ? '' : prefix + '.';
					str.push( p + moduleMap[ prefix ].join( ',' ) );
					list.push.apply( list, moduleMap[ prefix ].map( restore ) );
				}
				return {
					str: str.join( '|' ),
					list: list
				};
			}

			/**
			 * Resolve indexed dependencies.
			 *
			 * ResourceLoader uses an optimisation to save space which replaces module names in
			 * dependency lists with the index of that module within the array of module
			 * registration data if it exists. The benefit is a significant reduction in the data
			 * size of the startup module. This function changes those dependency lists back to
			 * arrays of strings.
			 *
			 * @private
			 * @param {Array} modules Modules array
			 */
			function resolveIndexedDependencies( modules ) {
				var i, j, deps;
				function resolveIndex( dep ) {
					return typeof dep === 'number' ? modules[ dep ][ 0 ] : dep;
				}
				for ( i = 0; i < modules.length; i++ ) {
					deps = modules[ i ][ 2 ];
					if ( deps ) {
						for ( j = 0; j < deps.length; j++ ) {
							deps[ j ] = resolveIndex( deps[ j ] );
						}
					}
				}
			}

			/**
			 * @private
			 * @param {Object} params Map of parameter names to values
			 * @return {string}
			 */
			function makeQueryString( params ) {
				return Object.keys( params ).map( function ( key ) {
					return encodeURIComponent( key ) + '=' + encodeURIComponent( params[ key ] );
				} ).join( '&' );
			}

			/**
			 * Create network requests for a batch of modules.
			 *
			 * This is an internal method for #work(). This must not be called directly
			 * unless the modules are already registered, and no request is in progress,
			 * and the module state has already been set to `loading`.
			 *
			 * @private
			 * @param {string[]} batch
			 */
			function batchRequest( batch ) {
				var reqBase, splits, b, bSource, bGroup,
					source, group, i, modules, sourceLoadScript,
					currReqBase, currReqBaseLength, moduleMap, currReqModules, l,
					lastDotIndex, prefix, suffix, bytesAdded;

				/**
				 * Start the currently drafted request to the server.
				 *
				 * @ignore
				 */
				function doRequest() {
					// Optimisation: Inherit (Object.create), not copy ($.extend)
					var query = Object.create( currReqBase ),
						packed = buildModulesString( moduleMap );
					query.modules = packed.str;
					// The packing logic can change the effective order, even if the input was
					// sorted. As such, the call to getCombinedVersion() must use this
					// effective order, instead of currReqModules, as otherwise the combined
					// version will not match the hash expected by the server based on
					// combining versions from the module query string in-order. (T188076)
					query.version = getCombinedVersion( packed.list );
					query = sortQuery( query );
					addScript( sourceLoadScript + '?' + makeQueryString( query ) );
				}

				if ( !batch.length ) {
					return;
				}

				// Always order modules alphabetically to help reduce cache
				// misses for otherwise identical content.
				batch.sort();

				// Query parameters common to all requests
				reqBase = $VARS.reqBase;

				// Split module list by source and by group.
				splits = Object.create( null );
				for ( b = 0; b < batch.length; b++ ) {
					bSource = registry[ batch[ b ] ].source;
					bGroup = registry[ batch[ b ] ].group;
					if ( !splits[ bSource ] ) {
						splits[ bSource ] = Object.create( null );
					}
					if ( !splits[ bSource ][ bGroup ] ) {
						splits[ bSource ][ bGroup ] = [];
					}
					splits[ bSource ][ bGroup ].push( batch[ b ] );
				}

				for ( source in splits ) {
					sourceLoadScript = sources[ source ];

					for ( group in splits[ source ] ) {

						// Cache access to currently selected list of
						// modules for this group from this source.
						modules = splits[ source ][ group ];

						// Query parameters common to requests for this module group
						// Optimisation: Inherit (Object.create), not copy ($.extend)
						currReqBase = Object.create( reqBase );
						// User modules require a user name in the query string.
						if ( group === $VARS.groupUser && mw.config.get( 'wgUserName' ) !== null ) {
							currReqBase.user = mw.config.get( 'wgUserName' );
						}

						// In addition to currReqBase, doRequest() will also add 'modules' and 'version'.
						// > '&modules='.length === 9
						// > '&version=12345'.length === 14
						// > 9 + 14 = 23
						currReqBaseLength = makeQueryString( currReqBase ).length + 23;

						// We may need to split up the request to honor the query string length limit,
						// so build it piece by piece.
						l = currReqBaseLength;
						moduleMap = Object.create( null ); // { prefix: [ suffixes ] }
						currReqModules = [];

						for ( i = 0; i < modules.length; i++ ) {
							// Determine how many bytes this module would add to the query string
							lastDotIndex = modules[ i ].lastIndexOf( '.' );
							// If lastDotIndex is -1, substr() returns an empty string
							prefix = modules[ i ].substr( 0, lastDotIndex );
							suffix = modules[ i ].slice( lastDotIndex + 1 );
							bytesAdded = moduleMap[ prefix ] ?
								suffix.length + 3 : // '%2C'.length == 3
								modules[ i ].length + 3; // '%7C'.length == 3

							// If the url would become too long, create a new one, but don't create empty requests
							if ( currReqModules.length && l + bytesAdded > mw.loader.maxQueryLength ) {
								// Dispatch what we've got...
								doRequest();
								// .. and start again.
								l = currReqBaseLength;
								moduleMap = Object.create( null );
								currReqModules = [];

								mw.track( 'resourceloader.splitRequest', { maxQueryLength: mw.loader.maxQueryLength } );
							}
							if ( !moduleMap[ prefix ] ) {
								moduleMap[ prefix ] = [];
							}
							l += bytesAdded;
							moduleMap[ prefix ].push( suffix );
							currReqModules.push( modules[ i ] );
						}
						// If there's anything left in moduleMap, request that too
						if ( currReqModules.length ) {
							doRequest();
						}
					}
				}
			}

			/**
			 * @private
			 * @param {string[]} implementations Array containing pieces of JavaScript code in the
			 *  form of calls to mw.loader#implement().
			 * @param {Function} cb Callback in case of failure
			 * @param {Error} cb.err
			 */
			function asyncEval( implementations, cb ) {
				if ( !implementations.length ) {
					return;
				}
				mw.requestIdleCallback( function () {
					try {
						domEval( implementations.join( ';' ) );
					} catch ( err ) {
						cb( err );
					}
				} );
			}

			/**
			 * Make a versioned key for a specific module.
			 *
			 * @private
			 * @param {string} module Module name
			 * @return {string|null} Module key in format '`[name]@[version]`',
			 *  or null if the module does not exist
			 */
			function getModuleKey( module ) {
				return module in registry ? ( module + '@' + registry[ module ].version ) : null;
			}

			/**
			 * @private
			 * @param {string} key Module name or '`[name]@[version]`'
			 * @return {Object}
			 */
			function splitModuleKey( key ) {
				var index = key.indexOf( '@' );
				if ( index === -1 ) {
					return {
						name: key,
						version: ''
					};
				}
				return {
					name: key.slice( 0, index ),
					version: key.slice( index + 1 )
				};
			}

			/**
			 * @private
			 * @param {string} module
			 * @param {string|number} [version]
			 * @param {string[]} [dependencies]
			 * @param {string} [group]
			 * @param {string} [source]
			 * @param {string} [skip]
			 */
			function registerOne( module, version, dependencies, group, source, skip ) {
				if ( module in registry ) {
					throw new Error( 'module already registered: ' + module );
				}
				registry[ module ] = {
					// Exposed to execute() for mw.loader.implement() closures.
					// Import happens via require().
					module: {
						exports: {}
					},
					// module.export objects for each package file inside this module
					packageExports: {},
					version: String( version || '' ),
					dependencies: dependencies || [],
					group: typeof group === 'undefined' ? null : group,
					source: typeof source === 'string' ? source : 'local',
					state: 'registered',
					skip: typeof skip === 'string' ? skip : null
				};
			}

			/* Public Members */
			return {
				/**
				 * The module registry is exposed as an aid for debugging and inspecting page
				 * state; it is not a public interface for modifying the registry.
				 *
				 * @see #registry
				 * @property {Object}
				 * @private
				 */
				moduleRegistry: registry,

				/**
				 * Exposed for testing and debugging only.
				 *
				 * @see #batchRequest
				 * @property {number}
				 * @private
				 */
				maxQueryLength: $VARS.maxQueryLength,

				/**
				 * @inheritdoc #newStyleTag
				 * @method
				 */
				addStyleTag: newStyleTag,

				enqueue: enqueue,

				resolve: resolve,

				/**
				 * Start loading of all queued module dependencies.
				 *
				 * @private
				 */
				work: function () {
					var q, module, implementation,
						storedImplementations = [],
						storedNames = [],
						requestNames = [],
						batch = new StringSet();

					mw.loader.store.init();

					// Iterate the list of requested modules, and do one of three things:
					// - 1) Nothing (if already loaded or being loaded).
					// - 2) Eval the cached implementation from the module store.
					// - 3) Request from network.
					q = queue.length;
					while ( q-- ) {
						module = queue[ q ];
						// Only consider modules which are the initial 'registered' state
						if ( module in registry && registry[ module ].state === 'registered' ) {
							// Ignore duplicates
							if ( !batch.has( module ) ) {
								// Progress the state machine
								registry[ module ].state = 'loading';
								batch.add( module );

								implementation = mw.loader.store.get( module );
								if ( implementation ) {
									// Module store enabled and contains this module/version
									storedImplementations.push( implementation );
									storedNames.push( module );
								} else {
									// Module store disabled or doesn't have this module/version
									requestNames.push( module );
								}
							}
						}
					}

					// Now that the queue has been processed into a batch, clear the queue.
					// This MUST happen before we initiate any eval or network request. Otherwise,
					// it is possible for a cached script to instantly trigger the same work queue
					// again; all before we've cleared it causing each request to include modules
					// which are already loaded.
					queue = [];

					asyncEval( storedImplementations, function ( err ) {
						var failed;
						// Not good, the cached mw.loader.implement calls failed! This should
						// never happen, barring ResourceLoader bugs, browser bugs and PEBKACs.
						// Depending on how corrupt the string is, it is likely that some
						// modules' implement() succeeded while the ones after the error will
						// never run and leave their modules in the 'loading' state forever.
						mw.loader.store.stats.failed++;

						// Since this is an error not caused by an individual module but by
						// something that infected the implement call itself, don't take any
						// risks and clear everything in this cache.
						mw.loader.store.clear();

						mw.trackError( 'resourceloader.exception', {
							exception: err,
							source: 'store-eval'
						} );
						// For any failed ones, fallback to requesting from network
						failed = storedNames.filter( function ( name ) {
							return registry[ name ].state === 'loading';
						} );
						batchRequest( failed );
					} );

					batchRequest( requestNames );
				},

				/**
				 * Register a source.
				 *
				 * The #work() method will use this information to split up requests by source.
				 *
				 *     @example
				 *     mw.loader.addSource( { mediawikiwiki: 'https://www.mediawiki.org/w/load.php' } );
				 *
				 * @private
				 * @param {Object} ids An object mapping ids to load.php end point urls
				 * @throws {Error} If source id is already registered
				 */
				addSource: function ( ids ) {
					var id;
					for ( id in ids ) {
						if ( id in sources ) {
							throw new Error( 'source already registered: ' + id );
						}
						sources[ id ] = ids[ id ];
					}
				},

				/**
				 * Register a module, letting the system know about it and its properties.
				 *
				 * The startup module calls this method.
				 *
				 * When using multiple module registration by passing an array, dependencies that
				 * are specified as references to modules within the array will be resolved before
				 * the modules are registered.
				 *
				 * @param {string|Array} modules Module name or array of arrays, each containing
				 *  a list of arguments compatible with this method
				 * @param {string|number} [version] Module version hash (falls backs to empty string)
				 *  Can also be a number (timestamp) for compatibility with MediaWiki 1.25 and earlier.
				 * @param {string[]} [dependencies] Array of module names on which this module depends.
				 * @param {string} [group=null] Group which the module is in
				 * @param {string} [source='local'] Name of the source
				 * @param {string} [skip=null] Script body of the skip function
				 */
				register: function ( modules ) {
					var i;
					if ( typeof modules === 'object' ) {
						resolveIndexedDependencies( modules );
						// Optimisation: Up to 55% faster.
						// Typically called only once, and with a batch.
						// See <https://gist.github.com/Krinkle/f06fdb3de62824c6c16f02a0e6ce0e66>
						// Benchmarks taught us that the code for adding an object to `registry`
						// should actually be inline, or in a simple function that does no
						// arguments manipulation, and isn't also the caller itself.
						// JS semantics make it hard to optimise recursion to a different
						// signature of itself.
						for ( i = 0; i < modules.length; i++ ) {
							registerOne.apply( null, modules[ i ] );
						}
					} else {
						registerOne.apply( null, arguments );
					}
				},

				/**
				 * Implement a module given the components that make up the module.
				 *
				 * When #load() or #using() requests one or more modules, the server
				 * response contain calls to this function.
				 *
				 * @param {string} module Name of module and current module version. Formatted
				 *  as '`[name]@[version]`". This version should match the requested version
				 *  (from #batchRequest and #registry). This avoids race conditions (T117587).
				 *  For back-compat with MediaWiki 1.27 and earlier, the version may be omitted.
				 * @param {Function|Array|string|Object} [script] Module code. This can be a function,
				 *  a list of URLs to load via `<script src>`, a string for `$.globalEval()`, or an
				 *  object like {"files": {"foo.js":function, "bar.js": function, ...}, "main": "foo.js"}.
				 *  If an object is provided, the main file will be executed immediately, and the other
				 *  files will only be executed if loaded via require(). If a function or string is
				 *  provided, it will be executed/evaluated immediately. If an array is provided, all
				 *  URLs in the array will be loaded immediately, and executed as soon as they arrive.
				 * @param {Object} [style] Should follow one of the following patterns:
				 *
				 *     { "css": [css, ..] }
				 *     { "url": { <media>: [url, ..] } }
				 *
				 * And for backwards compatibility (needs to be supported forever due to caching):
				 *
				 *     { <media>: css }
				 *     { <media>: [url, ..] }
				 *
				 * The reason css strings are not concatenated anymore is T33676. We now check
				 * whether it's safe to extend the stylesheet.
				 *
				 * @private
				 * @param {Object} [messages] List of key/value pairs to be added to mw#messages.
				 * @param {Object} [templates] List of key/value pairs to be added to mw#templates.
				 */
				implement: function ( module, script, style, messages, templates ) {
					var split = splitModuleKey( module ),
						name = split.name,
						version = split.version;
					// Automatically register module
					if ( !( name in registry ) ) {
						mw.loader.register( name );
					}
					// Check for duplicate implementation
					if ( registry[ name ].script !== undefined ) {
						throw new Error( 'module already implemented: ' + name );
					}
					if ( version ) {
						// Without this reset, if there is a version mismatch between the
						// requested and received module version, then mw.loader.store would
						// cache the response under the requested key. Thus poisoning the cache
						// indefinitely with a stale value. (T117587)
						registry[ name ].version = version;
					}
					// Attach components
					registry[ name ].script = script || null;
					registry[ name ].style = style || null;
					registry[ name ].messages = messages || null;
					registry[ name ].templates = templates || null;
					// The module may already have been marked as erroneous
					if ( registry[ name ].state !== 'error' && registry[ name ].state !== 'missing' ) {
						setAndPropagate( name, 'loaded' );
					}
				},

				/**
				 * Load an external script or one or more modules.
				 *
				 * This method takes a list of unrelated modules. Use cases:
				 *
				 * - A web page will be composed of many different widgets. These widgets independently
				 *   queue their ResourceLoader modules (`OutputPage::addModules()`). If any of them
				 *   have problems, or are no longer known (e.g. cached HTML), the other modules
				 *   should still be loaded.
				 * - This method is used for preloading, which must not throw. Later code that
				 *   calls #using() will handle the error.
				 *
				 * @param {string|Array} modules Either the name of a module, array of modules,
				 *  or a URL of an external script or style
				 * @param {string} [type='text/javascript'] MIME type to use if calling with a URL of an
				 *  external script or style; acceptable values are "text/css" and
				 *  "text/javascript"; if no type is provided, text/javascript is assumed.
				 * @throws {Error} If type is invalid
				 */
				load: function ( modules, type ) {
					if ( typeof modules === 'string' && /^(https?:)?\/?\//.test( modules ) ) {
						// Called with a url like so:
						// - "https://example.org/x.js"
						// - "http://example.org/x.js"
						// - "//example.org/x.js"
						// - "/x.js"
						if ( type === 'text/css' ) {
							addLink( modules );
						} else if ( type === 'text/javascript' || type === undefined ) {
							addScript( modules );
						} else {
							// Unknown type
							throw new Error( 'Invalid type ' + type );
						}
					} else {
						// One or more modules
						modules = typeof modules === 'string' ? [ modules ] : modules;
						// Resolve modules into flat list for internal queuing.
						// This also filters out unknown modules and modules with
						// unknown dependencies, allowing the rest to continue. (T36853)
						enqueue( resolveStubbornly( modules ), undefined, undefined );
					}
				},

				/**
				 * Change the state of one or more modules.
				 *
				 * @param {Object} states Object of module name/state pairs
				 */
				state: function ( states ) {
					var module, state;
					for ( module in states ) {
						state = states[ module ];
						if ( !( module in registry ) ) {
							mw.loader.register( module );
						}
						setAndPropagate( module, state );
					}
				},

				/**
				 * Get the state of a module.
				 *
				 * @param {string} module Name of module
				 * @return {string|null} The state, or null if the module (or its state) is not
				 *  in the registry.
				 */
				getState: function ( module ) {
					return module in registry ? registry[ module ].state : null;
				},

				/**
				 * Get the names of all registered modules.
				 *
				 * @return {Array}
				 */
				getModuleNames: function () {
					return Object.keys( registry );
				},

				/**
				 * Get the exported value of a module.
				 *
				 * This static method is publicly exposed for debugging purposes
				 * only and must not be used in production code. In production code,
				 * please use the dynamically provided `require()` function instead.
				 *
				 * In case of lazy-loaded modules via mw.loader#using(), the returned
				 * Promise provides the function, see #using() for examples.
				 *
				 * @private
				 * @since 1.27
				 * @param {string} moduleName Module name
				 * @return {Mixed} Exported value
				 */
				require: function ( moduleName ) {
					var state = mw.loader.getState( moduleName );

					// Only ready modules can be required
					if ( state !== 'ready' ) {
						// Module may've forgotten to declare a dependency
						throw new Error( 'Module "' + moduleName + '" is not loaded' );
					}

					return registry[ moduleName ].module.exports;
				},

				/**
				 * On browsers that implement the localStorage API, the module store serves as a
				 * smart complement to the browser cache. Unlike the browser cache, the module store
				 * can slice a concatenated response from ResourceLoader into its constituent
				 * modules and cache each of them separately, using each module's versioning scheme
				 * to determine when the cache should be invalidated.
				 *
				 * @private
				 * @singleton
				 * @class mw.loader.store
				 */
				store: {
					// Whether the store is in use on this page.
					enabled: null,

					// Modules whose serialised form exceeds 100 kB won't be stored (T66721).
					MODULE_SIZE_MAX: 1e5,

					// The contents of the store, mapping '[name]@[version]' keys
					// to module implementations.
					items: {},

					// Names of modules to be stored during the next update.
					// See add() and update().
					queue: [],

					// Cache hit stats
					stats: { hits: 0, misses: 0, expired: 0, failed: 0 },

					/**
					 * Construct a JSON-serializable object representing the content of the store.
					 *
					 * @return {Object} Module store contents.
					 */
					toJSON: function () {
						return {
							items: mw.loader.store.items,
							vary: mw.loader.store.vary,
							// Store with 1e7 ms accuracy (1e4 seconds, or ~ 2.7 hours),
							// which is enough for the purpose of expiring after ~ 30 days.
							asOf: Math.ceil( Date.now() / 1e7 )
						};
					},

					/**
					 * The localStorage key for the entire module store. The key references
					 * $wgDBname to prevent clashes between wikis which share a common host.
					 *
					 * @property {string}
					 */
					key: $VARS.storeKey,

					/**
					 * A string containing various factors on which to the module cache should vary.
					 *
					 * @property {string}
					 */
					vary: $VARS.storeVary,

					/**
					 * Initialize the store.
					 *
					 * Retrieves store from localStorage and (if successfully retrieved) decoding
					 * the stored JSON value to a plain object.
					 *
					 * The try / catch block is used for JSON & localStorage feature detection.
					 * See the in-line documentation for Modernizr's localStorage feature detection
					 * code for a full account of why we need a try / catch:
					 * <https://github.com/Modernizr/Modernizr/blob/v2.7.1/modernizr.js#L771-L796>.
					 */
					init: function () {
						var raw, data;

						if ( this.enabled !== null ) {
							// Init already ran
							return;
						}

						if (
							!$VARS.storeEnabled ||

							// Disabled because localStorage quotas are tight and (in Firefox's case)
							// shared by multiple origins.
							// See T66721, and <https://bugzilla.mozilla.org/show_bug.cgi?id=1064466>.
							/Firefox/.test( navigator.userAgent )
						) {
							// Clear any previous store to free up space. (T66721)
							this.clear();
							this.enabled = false;
							return;
						}

						try {
							// This a string we stored, or `null` if the key does not (yet) exist.
							raw = localStorage.getItem( this.key );
							// If we get here, localStorage is available; mark enabled
							this.enabled = true;
							// If null, JSON.parse() will cast to string and re-parse, still null.
							data = JSON.parse( raw );
							if ( data &&
								typeof data.items === 'object' &&
								data.vary === this.vary &&
								// Only use if it's been less than 30 days since the data was written
								// 30 days = 2,592,000 s = 2,592,000,000 ms = ± 259e7 ms
								Date.now() < ( data.asOf * 1e7 ) + 259e7
							) {
								// The data is not corrupt, matches our vary context, and has not expired.
								this.items = data.items;
								return;
							}
						} catch ( e ) {
							// Perhaps localStorage was disabled by the user, or got corrupted.
							// See point 3 and 4 below. (T195647)
						}

						// If we get here, one of four things happened:
						//
						// 1. localStorage did not contain our store key.
						//    This means `raw` is `null`, and we're on a fresh page view (cold cache).
						//    The store was enabled, and `items` starts fresh.
						//
						// 2. localStorage contained parseable data under our store key,
						//    but it's not applicable to our current context (see #vary).
						//    The store was enabled, and `items` starts fresh.
						//
						// 3. JSON.parse threw (localStorage contained corrupt data).
						//    This means `raw` contains a string.
						//    The store was enabled, and `items` starts fresh.
						//
						// 4. localStorage threw (disabled or otherwise unavailable).
						//    This means `raw` was never assigned.
						//    We will disable the store below.
						if ( raw === undefined ) {
							// localStorage failed; disable store
							this.enabled = false;
						}
					},

					/**
					 * Retrieve a module from the store and update cache hit stats.
					 *
					 * @param {string} module Module name
					 * @return {string|boolean} Module implementation or false if unavailable
					 */
					get: function ( module ) {
						var key;

						if ( this.enabled ) {
							key = getModuleKey( module );
							if ( key in this.items ) {
								this.stats.hits++;
								return this.items[ key ];
							}

							this.stats.misses++;
						}

						return false;
					},

					/**
					 * Queue the name of a module that the next update should consider storing.
					 *
					 * @since 1.32
					 * @param {string} module Module name
					 */
					add: function ( module ) {
						if ( this.enabled ) {
							this.queue.push( module );
							this.requestUpdate();
						}
					},

					/**
					 * Add the contents of the named module to the in-memory store.
					 *
					 * This method does not guarantee that the module will be stored.
					 * Inspection of the module's meta data and size will ultimately decide that.
					 *
					 * This method is considered internal to mw.loader.store and must only
					 * be called if the store is enabled.
					 *
					 * @private
					 * @param {string} module Module name
					 */
					set: function ( module ) {
						var key, args, src,
							encodedScript,
							descriptor = mw.loader.moduleRegistry[ module ];

						key = getModuleKey( module );

						if (
							// Already stored a copy of this exact version
							key in this.items ||
							// Module failed to load
							!descriptor ||
							descriptor.state !== 'ready' ||
							// Unversioned, private, or site-/user-specific
							!descriptor.version ||
							descriptor.group === $VARS.groupPrivate ||
							descriptor.group === $VARS.groupUser ||
							// Partial descriptor
							// (e.g. skipped module, or style module with state=ready)
							[ descriptor.script, descriptor.style, descriptor.messages,
								descriptor.templates ].indexOf( undefined ) !== -1
						) {
							// Decline to store
							return;
						}

						try {
							if ( typeof descriptor.script === 'function' ) {
								// Function literal: cast to string
								encodedScript = String( descriptor.script );
							} else if (
								// Plain object: serialise as object literal (not JSON),
								// making sure to preserve the functions.
								typeof descriptor.script === 'object' &&
								descriptor.script &&
								!Array.isArray( descriptor.script )
							) {
								encodedScript = '{' +
									'main:' + JSON.stringify( descriptor.script.main ) + ',' +
									'files:{' +
									Object.keys( descriptor.script.files ).map( function ( file ) {
										var value = descriptor.script.files[ file ];
										return JSON.stringify( file ) + ':' +
											( typeof value === 'function' ? value : JSON.stringify( value ) );
									} ).join( ',' ) +
									'}}';
							} else {
								// Array of urls, or null.
								encodedScript = JSON.stringify( descriptor.script );
							}
							args = [
								JSON.stringify( key ),
								encodedScript,
								JSON.stringify( descriptor.style ),
								JSON.stringify( descriptor.messages ),
								JSON.stringify( descriptor.templates )
							];
						} catch ( e ) {
							mw.trackError( 'resourceloader.exception', {
								exception: e,
								source: 'store-localstorage-json'
							} );
							return;
						}

						src = 'mw.loader.implement(' + args.join( ',' ) + ');';
						if ( src.length > this.MODULE_SIZE_MAX ) {
							return;
						}
						this.items[ key ] = src;
					},

					/**
					 * Iterate through the module store, removing any item that does not correspond
					 * (in name and version) to an item in the module registry.
					 */
					prune: function () {
						var key, module;

						for ( key in this.items ) {
							module = key.slice( 0, key.indexOf( '@' ) );
							if ( getModuleKey( module ) !== key ) {
								this.stats.expired++;
								delete this.items[ key ];
							} else if ( this.items[ key ].length > this.MODULE_SIZE_MAX ) {
								// This value predates the enforcement of a size limit on cached modules.
								delete this.items[ key ];
							}
						}
					},

					/**
					 * Clear the entire module store right now.
					 */
					clear: function () {
						this.items = {};
						try {
							localStorage.removeItem( this.key );
						} catch ( e ) {}
					},

					/**
					 * Request a sync of the in-memory store back to persisted localStorage.
					 *
					 * This function debounces updates. The debouncing logic should account
					 * for the following factors:
					 *
					 * - Writing to localStorage is an expensive operation that must not happen
					 *   during the critical path of initialising and executing module code.
					 *   Instead, it should happen at a later time after modules have been given
					 *   time and priority to do their thing first.
					 *
					 * - This method is called from mw.loader.store.add(), which will be called
					 *   hundreds of times on a typical page, including within the same call-stack
					 *   and eventloop-tick. This is because responses from load.php happen in
					 *   batches. As such, we want to allow all modules from the same load.php
					 *   response to be written to disk with a single flush, not many.
					 *
					 * - Repeatedly deleting and creating timers is non-trivial.
					 *
					 * - localStorage is shared by all pages from the same origin, if multiple
					 *   pages are loaded with different module sets, the possibility exists that
					 *   modules saved by one page will be clobbered by another. The impact of
					 *   this is minor, it merely causes a less efficient cache use, and the
					 *   problem would be corrected by subsequent page views.
					 *
					 * This method is considered internal to mw.loader.store and must only
					 * be called if the store is enabled.
					 *
					 * @private
					 * @method
					 */
					requestUpdate: ( function () {
						var hasPendingWrites = false;

						function flushWrites() {
							var data, key;

							// Remove anything from the in-memory store that came from previous page
							// loads that no longer corresponds with current module names and versions.
							mw.loader.store.prune();
							// Process queued module names, serialise their contents to the in-memory store.
							while ( mw.loader.store.queue.length ) {
								mw.loader.store.set( mw.loader.store.queue.shift() );
							}

							key = mw.loader.store.key;
							try {
								// Replacing the content of the module store might fail if the new
								// contents would exceed the browser's localStorage size limit. To
								// avoid clogging the browser with stale data, always remove the old
								// value before attempting to set the new one.
								localStorage.removeItem( key );
								data = JSON.stringify( mw.loader.store );
								localStorage.setItem( key, data );
							} catch ( e ) {
								mw.trackError( 'resourceloader.exception', {
									exception: e,
									source: 'store-localstorage-update'
								} );
							}

							// Let the next call to requestUpdate() create a new timer.
							hasPendingWrites = false;
						}

						function onTimeout() {
							// Defer the actual write via requestIdleCallback
							mw.requestIdleCallback( flushWrites );
						}

						return function () {
							// On the first call to requestUpdate(), create a timer that
							// waits at least two seconds, then calls onTimeout.
							// The main purpose is to allow the current batch of load.php
							// responses to complete before we do anything. This batch can
							// trigger many hundreds of calls to requestUpdate().
							if ( !hasPendingWrites ) {
								hasPendingWrites = true;
								setTimeout( onTimeout, 2000 );
							}
						};
					}() )
				}
			};
		}() )
	};

	// Attach to window and globally alias
	window.mw = window.mediaWiki = mw;
}() );
