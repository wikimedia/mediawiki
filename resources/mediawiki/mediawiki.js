/*
 * Core MediaWiki JavaScript Library
 */

var mw = ( function ( $, undefined ) {
	'use strict';

	/* Private Members */

	var hasOwn = Object.prototype.hasOwnProperty,
		slice = Array.prototype.slice;

	/* Object constructors */

	/**
	 * Creates an object that can be read from or written to from prototype functions
	 * that allow both single and multiple variables at once.
	 * @class mw.Map
	 *
	 * @constructor
	 * @param {boolean} global Whether to store the values in the global window
	 *  object or a exclusively in the object property 'values'.
	 */
	function Map( global ) {
		this.values = global === true ? window : {};
		return this;
	}

	Map.prototype = {
		/**
		 * Get the value of one or multiple a keys.
		 *
		 * If called with no arguments, all values will be returned.
		 *
		 * @param selection mixed String key or array of keys to get values for.
		 * @param fallback mixed Value to use in case key(s) do not exist (optional).
		 * @return mixed If selection was a string returns the value or null,
		 *  If selection was an array, returns an object of key/values (value is null if not found),
		 *  If selection was not passed or invalid, will return the 'values' object member (be careful as
		 *  objects are always passed by reference in JavaScript!).
		 * @return {string|Object|null} Values as a string or object, null if invalid/inexistant.
		 */
		get: function ( selection, fallback ) {
			var results, i;
			// If we only do this in the `return` block, it'll fail for the
			// call to get() from the mutli-selection block.
			fallback = arguments.length > 1 ? fallback : null;

			if ( $.isArray( selection ) ) {
				selection = slice.call( selection );
				results = {};
				for ( i = 0; i < selection.length; i++ ) {
					results[selection[i]] = this.get( selection[i], fallback );
				}
				return results;
			}

			if ( typeof selection === 'string' ) {
				if ( !hasOwn.call( this.values, selection ) ) {
					return fallback;
				}
				return this.values[selection];
			}

			if ( selection === undefined ) {
				return this.values;
			}

			// invalid selection key
			return null;
		},

		/**
		 * Sets one or multiple key/value pairs.
		 *
		 * @param selection {mixed} String key or array of keys to set values for.
		 * @param value {mixed} Value to set (optional, only in use when key is a string)
		 * @return {Boolean} This returns true on success, false on failure.
		 */
		set: function ( selection, value ) {
			var s;

			if ( $.isPlainObject( selection ) ) {
				for ( s in selection ) {
					this.values[s] = selection[s];
				}
				return true;
			}
			if ( typeof selection === 'string' && arguments.length > 1 ) {
				this.values[selection] = value;
				return true;
			}
			return false;
		},

		/**
		 * Checks if one or multiple keys exist.
		 *
		 * @param selection {mixed} String key or array of keys to check
		 * @return {boolean} Existence of key(s)
		 */
		exists: function ( selection ) {
			var s;

			if ( $.isArray( selection ) ) {
				for ( s = 0; s < selection.length; s++ ) {
					if ( typeof selection[s] !== 'string' || !hasOwn.call( this.values, selection[s] ) ) {
						return false;
					}
				}
				return true;
			}
			return typeof selection === 'string' && hasOwn.call( this.values, selection );
		}
	};

	/**
	 * Object constructor for messages,
	 * similar to the Message class in MediaWiki PHP.
	 * @class mw.Message
	 *
	 * @constructor
	 * @param {mw.Map} map Message storage
	 * @param {string} key
	 * @param {Array} [parameters]
	 */
	function Message( map, key, parameters ) {
		this.format = 'text';
		this.map = map;
		this.key = key;
		this.parameters = parameters === undefined ? [] : slice.call( parameters );
		return this;
	}

	Message.prototype = {
		/**
		 * Simple message parser, does $N replacement, HTML-escaping (only for
		 * 'escaped' format), and nothing else.
		 *
		 * This may be overridden to provide a more complex message parser.
		 *
		 * The primary override is in mediawiki.jqueryMsg.
		 *
		 * This function will not be called for nonexistent messages.
		 */
		parser: function () {
			var parameters = this.parameters;
			return this.map.get( this.key ).replace( /\$(\d+)/g, function ( str, match ) {
				var index = parseInt( match, 10 ) - 1;
				return parameters[index] !== undefined ? parameters[index] : '$' + match;
			} );
		},

		/**
		 * Appends (does not replace) parameters for replacement to the .parameters property.
		 *
		 * @param {Array} parameters
		 * @chainable
		 */
		params: function ( parameters ) {
			var i;
			for ( i = 0; i < parameters.length; i += 1 ) {
				this.parameters.push( parameters[i] );
			}
			return this;
		},

		/**
		 * Converts message object to it's string form based on the state of format.
		 *
		 * @return {string} Message as a string in the current form or `<key>` if key does not exist.
		 */
		toString: function () {
			var text;

			if ( !this.exists() ) {
				// Use <key> as text if key does not exist
				if ( this.format === 'escaped' || this.format === 'parse' ) {
					// format 'escaped' and 'parse' need to have the brackets and key html escaped
					return mw.html.escape( '<' + this.key + '>' );
				}
				return '<' + this.key + '>';
			}

			if ( this.format === 'plain' || this.format === 'text' || this.format === 'parse' ) {
				text = this.parser();
			}

			if ( this.format === 'escaped' ) {
				text = this.parser();
				text = mw.html.escape( text );
			}

			return text;
		},

		/**
		 * Changes format to 'parse' and converts message to string
		 *
		 * If jqueryMsg is loaded, this parses the message text from wikitext
		 * (where supported) to HTML
		 *
		 * Otherwise, it is equivalent to plain.
		 *
		 * @return {string} String form of parsed message
		 */
		parse: function () {
			this.format = 'parse';
			return this.toString();
		},

		/**
		 * Changes format to 'plain' and converts message to string
		 *
		 * This substitutes parameters, but otherwise does not change the
		 * message text.
		 *
		 * @return {string} String form of plain message
		 */
		plain: function () {
			this.format = 'plain';
			return this.toString();
		},

		/**
		 * Changes format to 'text' and converts message to string
		 *
		 * If jqueryMsg is loaded, {{-transformation is done where supported
		 * (such as {{plural:}}, {{gender:}}, {{int:}}).
		 *
		 * Otherwise, it is equivalent to plain.
		 */
		text: function () {
			this.format = 'text';
			return this.toString();
		},

		/**
		 * Changes the format to 'escaped' and converts message to string
		 *
		 * This is equivalent to using the 'text' format (see text method), then
		 * HTML-escaping the output.
		 *
		 * @return {string} String form of html escaped message
		 */
		escaped: function () {
			this.format = 'escaped';
			return this.toString();
		},

		/**
		 * Checks if message exists
		 *
		 * @see mw.Map#exists
		 * @return {boolean}
		 */
		exists: function () {
			return this.map.exists( this.key );
		}
	};

	/**
	 * @class mw
	 * @alternateClassName mediaWiki
	 * @singleton
	 */
	return {
		/* Public Members */

		/**
		 * Dummy function which in debug mode can be replaced with a function that
		 * emulates console.log in console-less environments.
		 */
		log: function () { },

		// Make the Map constructor publicly available.
		Map: Map,

		// Make the Message constructor publicly available.
		Message: Message,

		/**
		 * List of configuration values
		 *
		 * Dummy placeholder. Initiated in startUp module as a new instance of mw.Map().
		 * If `$wgLegacyJavaScriptGlobals` is true, this Map will have its values
		 * in the global window object.
		 * @property
		 */
		config: null,

		/**
		 * Empty object that plugins can be installed in.
		 * @property
		 */
		libs: {},

		/* Extension points */

		/**
		 * @property
		 */
		legacy: {},

		/**
		 * Localization system
		 * @property {mw.Map}
		 */
		messages: new Map(),

		/* Public Methods */

		/**
		 * Gets a message object, similar to wfMessage().
		 *
		 * @param {string} key Key of message to get
		 * @param {Mixed...} parameters Parameters for the $N replacements in messages.
		 * @return {mw.Message}
		 */
		message: function ( key ) {
			// Variadic arguments
			var parameters = slice.call( arguments, 1 );
			return new Message( mw.messages, key, parameters );
		},

		/**
		 * Gets a message string, similar to wfMessage()
		 *
		 * @see mw.Message#toString
		 * @param {string} key Key of message to get
		 * @param {Mixed...} parameters Parameters for the $N replacements in messages.
		 * @return {string}
		 */
		msg: function ( /* key, parameters... */ ) {
			return mw.message.apply( mw.message, arguments ).toString();
		},

		/**
		 * Client-side module loader which integrates with the MediaWiki ResourceLoader
		 * @class mw.loader
		 * @singleton
		 */
		loader: ( function () {

			/* Private Members */

			/**
			 * Mapping of registered modules
			 *
			 * The jquery module is pre-registered, because it must have already
			 * been provided for this object to have been built, and in debug mode
			 * jquery would have been provided through a unique loader request,
			 * making it impossible to hold back registration of jquery until after
			 * mediawiki.
			 *
			 * For exact details on support for script, style and messages, look at
			 * mw.loader.implement.
			 *
			 * Format:
			 *     {
			 *         'moduleName': {
			 *             'version': ############## (unix timestamp),
			 *             'dependencies': ['required.foo', 'bar.also', ...], (or) function () {}
			 *             'group': 'somegroup', (or) null,
			 *             'source': 'local', 'someforeignwiki', (or) null
			 *             'state': 'registered', 'loaded', 'loading', 'ready', 'error' or 'missing'
			 *             'script': ...,
			 *             'style': ...,
			 *             'messages': { 'key': 'value' },
			 *         }
			 *     }
			 *
			 * @property
			 * @private
			 */
			var registry = {},
				//
				// Mapping of sources, keyed by source-id, values are objects.
				// Format:
				//	{
				//		'sourceId': {
				//			'loadScript': 'http://foo.bar/w/load.php'
				//		}
				//	}
				//
				sources = {},
				// List of modules which will be loaded as when ready
				batch = [],
				// List of modules to be loaded
				queue = [],
				// List of callback functions waiting for modules to be ready to be called
				jobs = [],
				// Selector cache for the marker element. Use getMarker() to get/use the marker!
				$marker = null,
				// Buffer for addEmbeddedCSS.
				cssBuffer = '',
				// Callbacks for addEmbeddedCSS.
				cssCallbacks = $.Callbacks();

			/* Private methods */

			function getMarker() {
				// Cached ?
				if ( $marker ) {
					return $marker;
				}

				$marker = $( 'meta[name="ResourceLoaderDynamicStyles"]' );
				if ( $marker.length ) {
					return $marker;
				}
				mw.log( 'getMarker> No <meta name="ResourceLoaderDynamicStyles"> found, inserting dynamically.' );
				$marker = $( '<meta>' ).attr( 'name', 'ResourceLoaderDynamicStyles' ).appendTo( 'head' );

				return $marker;
			}

			/**
			 * Create a new style tag and add it to the DOM.
			 *
			 * @private
			 * @param {string} text CSS text
			 * @param {Mixed} [nextnode] An Element or jQuery object for an element where
			 * the style tag should be inserted before. Otherwise appended to the `<head>`.
			 * @return {HTMLElement} Node reference to the created `<style>` tag.
			 */
			function addStyleTag( text, nextnode ) {
				var s = document.createElement( 'style' );
				// Insert into document before setting cssText (bug 33305)
				if ( nextnode ) {
					// Must be inserted with native insertBefore, not $.fn.before.
					// When using jQuery to insert it, like $nextnode.before( s ),
					// then IE6 will throw "Access is denied" when trying to append
					// to .cssText later. Some kind of weird security measure.
					// http://stackoverflow.com/q/12586482/319266
					// Works: jsfiddle.net/zJzMy/1
					// Fails: jsfiddle.net/uJTQz
					// Works again: http://jsfiddle.net/Azr4w/ (diff: the next 3 lines)
					if ( nextnode.jquery ) {
						nextnode = nextnode.get( 0 );
					}
					nextnode.parentNode.insertBefore( s, nextnode );
				} else {
					document.getElementsByTagName( 'head' )[0].appendChild( s );
				}
				if ( s.styleSheet ) {
					// IE
					s.styleSheet.cssText = text;
				} else {
					// Other browsers.
					// (Safari sometimes borks on non-string values,
					// play safe by casting to a string, just in case.)
					s.appendChild( document.createTextNode( String( text ) ) );
				}
				return s;
			}

			/**
			 * Checks whether it is safe to add this css to a stylesheet.
                         * 
			 * @private
			 * @param {string} cssText
			 * @return {boolean} False if a new one must be created.
			 */
			function canExpandStylesheetWith( cssText ) {
				// Makes sure that cssText containing `@import`
				// rules will end up in a new stylesheet (as those only work when
				// placed at the start of a stylesheet; bug 35562).
				return cssText.indexOf( '@import' ) === -1;
			}

			/**
			 * @param {string} [cssText=cssBuffer] If called without cssText,
			 * the internal buffer will be inserted instead.
			 * @param {Function} [callback]
			 */
			function addEmbeddedCSS( cssText, callback ) {
				var $style, styleEl;

				if ( callback ) {
					cssCallbacks.add( callback );
				}

				// Yield once before inserting the <style> tag. There are likely
				// more calls coming up which we can combine this way.
				// Appending a stylesheet and waiting for the browser to repaint
				// is fairly expensive, this reduces it (bug 45810)
				if ( cssText ) {
					// Be careful not to extend the buffer with css that needs a new stylesheet
					if ( !cssBuffer || canExpandStylesheetWith( cssText ) ) {
						// Linebreak for somewhat distinguishable sections
						// (the rl-cachekey comment separating each)
						cssBuffer += '\n' + cssText;
						// TODO: Use requestAnimationFrame in the future which will
						// perform even better by not injecting styles while the browser
						// is paiting.
						setTimeout( function () {
							// Can't pass addEmbeddedCSS to setTimeout directly because Firefox
							// (below version 13) has the non-standard behaviour of passing a
							// numerical "lateness" value as first argument to this callback
							// http://benalman.com/news/2009/07/the-mysterious-firefox-settime/
							addEmbeddedCSS();
						} );
						return;
					}

				// This is a delayed call and we got a buffer still
				} else if ( cssBuffer ) {
					cssText = cssBuffer;
					cssBuffer = '';
				} else {
					// This is a delayed call, but buffer is already cleared by
					// another delayed call.
					return;
				}

				// By default, always create a new <style>. Appending text
				// to a <style> tag means the contents have to be re-parsed (bug 45810).
				// Except, of course, in IE below 9, in there we default to
				// re-using and appending to a <style> tag due to the
				// IE stylesheet limit (bug 31676).
				if ( 'documentMode' in document && document.documentMode <= 9 ) {

					$style = getMarker().prev();
					// Verify that the the element before Marker actually is a
					// <style> tag and one that came from ResourceLoader
					// (not some other style tag or even a `<meta>` or `<script>`).
					if ( $style.data( 'ResourceLoaderDynamicStyleTag' ) === true ) {
						// There's already a dynamic <style> tag present and
						// canExpandStylesheetWith() gave a green light to append more to it.
						styleEl = $style.get( 0 );
						if ( styleEl.styleSheet ) {
							try {
								styleEl.styleSheet.cssText += cssText; // IE
							} catch ( e ) {
								log( 'addEmbeddedCSS fail\ne.message: ' + e.message, e );
							}
						} else {
							styleEl.appendChild( document.createTextNode( String( cssText ) ) );
						}
						cssCallbacks.fire().empty();
						return;
					}
				}

				$( addStyleTag( cssText, getMarker() ) ).data( 'ResourceLoaderDynamicStyleTag', true );

				cssCallbacks.fire().empty();
			}

			/**
			 * Generates an ISO8601 "basic" string from a UNIX timestamp
			 * @private
			 */
			function formatVersionNumber( timestamp ) {
				var	d = new Date();
				function pad( a, b, c ) {
					return [a < 10 ? '0' + a : a, b < 10 ? '0' + b : b, c < 10 ? '0' + c : c].join( '' );
				}
				d.setTime( timestamp * 1000 );
				return [
					pad( d.getUTCFullYear(), d.getUTCMonth() + 1, d.getUTCDate() ), 'T',
					pad( d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds() ), 'Z'
				].join( '' );
			}

			/**
			 * Resolves dependencies and detects circular references.
			 *
			 * @private
			 * @param {string} module Name of the top-level module whose dependencies shall be
			 *   resolved and sorted.
			 * @param {Array} resolved Returns a topological sort of the given module and its
			 *   dependencies, such that later modules depend on earlier modules. The array
			 *   contains the module names. If the array contains already some module names,
			 *   this function appends its result to the pre-existing array.
			 * @param {Object} [unresolved] Hash used to track the current dependency
			 *   chain; used to report loops in the dependency graph.
			 * @throws {Error} If any unregistered module or a dependency loop is encountered
			 */
			function sortDependencies( module, resolved, unresolved ) {
				var n, deps, len;

				if ( registry[module] === undefined ) {
					throw new Error( 'Unknown dependency: ' + module );
				}
				// Resolves dynamic loader function and replaces it with its own results
				if ( $.isFunction( registry[module].dependencies ) ) {
					registry[module].dependencies = registry[module].dependencies();
					// Ensures the module's dependencies are always in an array
					if ( typeof registry[module].dependencies !== 'object' ) {
						registry[module].dependencies = [registry[module].dependencies];
					}
				}
				if ( $.inArray( module, resolved ) !== -1 ) {
					// Module already resolved; nothing to do.
					return;
				}
				// unresolved is optional, supply it if not passed in
				if ( !unresolved ) {
					unresolved = {};
				}
				// Tracks down dependencies
				deps = registry[module].dependencies;
				len = deps.length;
				for ( n = 0; n < len; n += 1 ) {
					if ( $.inArray( deps[n], resolved ) === -1 ) {
						if ( unresolved[deps[n]] ) {
							throw new Error(
								'Circular reference detected: ' + module +
								' -> ' + deps[n]
							);
						}

						// Add to unresolved
						unresolved[module] = true;
						sortDependencies( deps[n], resolved, unresolved );
						delete unresolved[module];
					}
				}
				resolved[resolved.length] = module;
			}

			/**
			 * Gets a list of module names that a module depends on in their proper dependency
			 * order.
			 *
			 * @private
			 * @param {string} module Module name or array of string module names
			 * @return {Array} list of dependencies, including 'module'.
			 * @throws {Error} If circular reference is detected
			 */
			function resolve( module ) {
				var m, resolved;

				// Allow calling with an array of module names
				if ( $.isArray( module ) ) {
					resolved = [];
					for ( m = 0; m < module.length; m += 1 ) {
						sortDependencies( module[m], resolved );
					}
					return resolved;
				}

				if ( typeof module === 'string' ) {
					resolved = [];
					sortDependencies( module, resolved );
					return resolved;
				}

				throw new Error( 'Invalid module argument: ' + module );
			}

			/**
			 * Narrows a list of module names down to those matching a specific
			 * state (see comment on top of this scope for a list of valid states).
			 * One can also filter for 'unregistered', which will return the
			 * modules names that don't have a registry entry.
			 *
			 * @private
			 * @param {string|string[]} states Module states to filter by
			 * @param {Array} modules List of module names to filter (optional, by default the entire
			 * registry is used)
			 * @return {Array} List of filtered module names
			 */
			function filter( states, modules ) {
				var list, module, s, m;

				// Allow states to be given as a string
				if ( typeof states === 'string' ) {
					states = [states];
				}
				// If called without a list of modules, build and use a list of all modules
				list = [];
				if ( modules === undefined ) {
					modules = [];
					for ( module in registry ) {
						modules[modules.length] = module;
					}
				}
				// Build a list of modules which are in one of the specified states
				for ( s = 0; s < states.length; s += 1 ) {
					for ( m = 0; m < modules.length; m += 1 ) {
						if ( registry[modules[m]] === undefined ) {
							// Module does not exist
							if ( states[s] === 'unregistered' ) {
								// OK, undefined
								list[list.length] = modules[m];
							}
						} else {
							// Module exists, check state
							if ( registry[modules[m]].state === states[s] ) {
								// OK, correct state
								list[list.length] = modules[m];
							}
						}
					}
				}
				return list;
			}

			/**
			 * Determine whether all dependencies are in state 'ready', which means we may
			 * execute the module or job now.
			 *
			 * @private
			 * @param {Array} dependencies Dependencies (module names) to be checked.
			 * @return {boolean} True if all dependencies are in state 'ready', false otherwise
			 */
			function allReady( dependencies ) {
				return filter( 'ready', dependencies ).length === dependencies.length;
			}

			/**
			 * Log a message to window.console, if possible. Useful to force logging of some
			 * errors that are otherwise hard to detect (I.e., this logs also in production mode).
			 * Gets console references in each invocation, so that delayed debugging tools work
			 * fine. No need for optimization here, which would only result in losing logs.
			 *
			 * @private
			 * @param {string} msg text for the log entry.
			 * @param {Error} [e]
			 */
			function log( msg, e ) {
				var console = window.console;
				if ( console && console.log ) {
					console.log( msg );
					// If we have an exception object, log it through .error() to trigger
					// proper stacktraces in browsers that support it. There are no (known)
					// browsers that don't support .error(), that do support .log() and
					// have useful exception handling through .log().
					if ( e && console.error ) {
						console.error( e );
					}
				}
			}

			/**
			 * A module has entered state 'ready', 'error', or 'missing'. Automatically update pending jobs
			 * and modules that depend upon this module. if the given module failed, propagate the 'error'
			 * state up the dependency tree; otherwise, execute all jobs/modules that now have all their
			 * dependencies satisfied. On jobs depending on a failed module, run the error callback, if any.
			 *
			 * @private
			 * @param {string} module Name of module that entered one of the states 'ready', 'error', or 'missing'.
			 */
			function handlePending( module ) {
				var j, job, hasErrors, m, stateChange;

				// Modules.
				if ( $.inArray( registry[module].state, ['error', 'missing'] ) !== -1 ) {
					// If the current module failed, mark all dependent modules also as failed.
					// Iterate until steady-state to propagate the error state upwards in the
					// dependency tree.
					do {
						stateChange = false;
						for ( m in registry ) {
							if ( $.inArray( registry[m].state, ['error', 'missing'] ) === -1 ) {
								if ( filter( ['error', 'missing'], registry[m].dependencies ).length > 0 ) {
									registry[m].state = 'error';
									stateChange = true;
								}
							}
						}
					} while ( stateChange );
				}

				// Execute all jobs whose dependencies are either all satisfied or contain at least one failed module.
				for ( j = 0; j < jobs.length; j += 1 ) {
					hasErrors = filter( ['error', 'missing'], jobs[j].dependencies ).length > 0;
					if ( hasErrors || allReady( jobs[j].dependencies ) ) {
						// All dependencies satisfied, or some have errors
						job = jobs[j];
						jobs.splice( j, 1 );
						j -= 1;
						try {
							if ( hasErrors ) {
								throw new Error( 'Module ' + module + ' failed.');
							} else {
								if ( $.isFunction( job.ready ) ) {
									job.ready();
								}
							}
						} catch ( e ) {
							if ( $.isFunction( job.error ) ) {
								try {
									job.error( e, [module] );
								} catch ( ex ) {
									// A user-defined operation raised an exception. Swallow to protect
									// our state machine!
									log( 'Exception thrown by job.error()', ex );
								}
							}
						}
					}
				}

				if ( registry[module].state === 'ready' ) {
					// The current module became 'ready'. Recursively execute all dependent modules that are loaded
					// and now have all dependencies satisfied.
					for ( m in registry ) {
						if ( registry[m].state === 'loaded' && allReady( registry[m].dependencies ) ) {
							execute( m );
						}
					}
				}
			}

			/**
			 * Adds a script tag to the DOM, either using document.write or low-level DOM manipulation,
			 * depending on whether document-ready has occurred yet and whether we are in async mode.
			 *
			 * @private
			 * @param {string} src URL to script, will be used as the src attribute in the script tag
			 * @param {Function} [callback] Callback which will be run when the script is done
			 */
			function addScript( src, callback, async ) {
				/*jshint evil:true */
				var script, head,
					done = false;

				// Using isReady directly instead of storing it locally from
				// a $.fn.ready callback (bug 31895).
				if ( $.isReady || async ) {
					// Can't use jQuery.getScript because that only uses <script> for cross-domain,
					// it uses XHR and eval for same-domain scripts, which we don't want because it
					// messes up line numbers.
					// The below is based on jQuery ([jquery@1.8.2]/src/ajax/script.js)

					// IE-safe way of getting the <head>. document.head isn't supported
					// in old IE, and doesn't work when in the <head>.
					head = document.getElementsByTagName( 'head' )[0] || document.body;

					script = document.createElement( 'script' );
					script.async = true;
					script.src = src;
					if ( $.isFunction( callback ) ) {
						script.onload = script.onreadystatechange = function () {
							if (
								!done
								&& (
									!script.readyState
									|| /loaded|complete/.test( script.readyState )
								)
							) {
								done = true;

								// Handle memory leak in IE
								script.onload = script.onreadystatechange = null;

								// Remove the script
								if ( script.parentNode ) {
									script.parentNode.removeChild( script );
								}

								// Dereference the script
								script = undefined;

								callback();
							}
						};
					}

					if ( window.opera ) {
						// Appending to the <head> blocks rendering completely in Opera,
						// so append to the <body> after document ready. This means the
						// scripts only start loading after the document has been rendered,
						// but so be it. Opera users don't deserve faster web pages if their
						// browser makes it impossible.
						$( function () {
							document.body.appendChild( script );
						} );
					} else {
						head.appendChild( script );
					}
				} else {
					document.write( mw.html.element( 'script', { 'src': src }, '' ) );
					if ( $.isFunction( callback ) ) {
						// Document.write is synchronous, so this is called when it's done
						// FIXME: that's a lie. doc.write isn't actually synchronous
						callback();
					}
				}
			}

			/**
			 * Executes a loaded module, making it ready to use
			 *
			 * @private
			 * @param {string} module Module name to execute
			 */
			function execute( module ) {
				var key, value, media, i, urls, cssHandle, checkCssHandles,
					cssHandlesRegistered = false;

				if ( registry[module] === undefined ) {
					throw new Error( 'Module has not been registered yet: ' + module );
				} else if ( registry[module].state === 'registered' ) {
					throw new Error( 'Module has not been requested from the server yet: ' + module );
				} else if ( registry[module].state === 'loading' ) {
					throw new Error( 'Module has not completed loading yet: ' + module );
				} else if ( registry[module].state === 'ready' ) {
					throw new Error( 'Module has already been executed: ' + module );
				}

				/**
				 * Define loop-function here for efficiency
				 * and to avoid re-using badly scoped variables.
				 * @ignore
				 */
				function addLink( media, url ) {
					var el = document.createElement( 'link' );
					getMarker().before( el ); // IE: Insert in dom before setting href
					el.rel = 'stylesheet';
					if ( media && media !== 'all' ) {
						el.media = media;
					}
					el.href = url;
				}

				function runScript() {
					var script, markModuleReady, nestedAddScript;
					try {
						script = registry[module].script;
						markModuleReady = function () {
							registry[module].state = 'ready';
							handlePending( module );
						};
						nestedAddScript = function ( arr, callback, async, i ) {
							// Recursively call addScript() in its own callback
							// for each element of arr.
							if ( i >= arr.length ) {
								// We're at the end of the array
								callback();
								return;
							}

							addScript( arr[i], function () {
								nestedAddScript( arr, callback, async, i + 1 );
							}, async );
						};

						if ( $.isArray( script ) ) {
							nestedAddScript( script, markModuleReady, registry[module].async, 0 );
						} else if ( $.isFunction( script ) ) {
							registry[module].state = 'ready';
							script( $ );
							handlePending( module );
						}
					} catch ( e ) {
						// This needs to NOT use mw.log because these errors are common in production mode
						// and not in debug mode, such as when a symbol that should be global isn't exported
						log( 'Exception thrown by ' + module + ': ' + e.message, e );
						registry[module].state = 'error';
						handlePending( module );
					}
				}

				// This used to be inside runScript, but since that is now fired asychronously
				// (after CSS is loaded) we need to set it here right away. It is crucial that
				// when execute() is called this is set synchronously, otherwise modules will get
				// executed multiple times as the registry will state that it isn't loading yet.
				registry[module].state = 'loading';

				// Add localizations to message system
				if ( $.isPlainObject( registry[module].messages ) ) {
					mw.messages.set( registry[module].messages );
				}

				// Make sure we don't run the scripts until all (potentially asynchronous)
				// stylesheet insertions have completed.
				( function () {
					var pending = 0;
					checkCssHandles = function () {
						// cssHandlesRegistered ensures we don't take off too soon, e.g. when
						// one of the cssHandles is fired while we're still creating more handles.
						if ( cssHandlesRegistered && pending === 0 && runScript ) {
							runScript();
							runScript = undefined; // Revoke
						}
					};
					cssHandle = function () {
						var check = checkCssHandles;
						pending++;
						return function () {
							if (check) {
								pending--;
								check();
								check = undefined; // Revoke
							}
						};
					};
				}() );

				// Process styles (see also mw.loader.implement)
				// * back-compat: { <media>: css }
				// * back-compat: { <media>: [url, ..] }
				// * { "css": [css, ..] }
				// * { "url": { <media>: [url, ..] } }
				if ( $.isPlainObject( registry[module].style ) ) {
					for ( key in registry[module].style ) {
						value = registry[module].style[key];
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
						if ( $.isArray( value ) ) {
							for ( i = 0; i < value.length; i += 1 ) {
								if ( key === 'bc-url' ) {
									// back-compat: { <media>: [url, ..] }
									addLink( media, value[i] );
								} else if ( key === 'css' ) {
									// { "css": [css, ..] }
									addEmbeddedCSS( value[i], cssHandle() );
								}
							}
						// Not an array, but a regular object
						// Array of urls inside media-type key
						} else if ( typeof value === 'object' ) {
							// { "url": { <media>: [url, ..] } }
							for ( media in value ) {
								urls = value[media];
								for ( i = 0; i < urls.length; i += 1 ) {
									addLink( media, urls[i] );
								}
							}
						}
					}
				}

				// Kick off.
				cssHandlesRegistered = true;
				checkCssHandles();
			}

			/**
			 * Adds a dependencies to the queue with optional callbacks to be run
			 * when the dependencies are ready or fail
			 *
			 * @private
			 * @param {string|string[]} dependencies Module name or array of string module names
			 * @param {Function} [ready] Callback to execute when all dependencies are ready
			 * @param {Function} [error] Callback to execute when any dependency fails
			 * @param {boolean} [async] If true, load modules asynchronously even if
			 *  document ready has not yet occurred.
			 */
			function request( dependencies, ready, error, async ) {
				var n;

				// Allow calling by single module name
				if ( typeof dependencies === 'string' ) {
					dependencies = [dependencies];
				}

				// Add ready and error callbacks if they were given
				if ( ready !== undefined || error !== undefined ) {
					jobs[jobs.length] = {
						'dependencies': filter(
							['registered', 'loading', 'loaded'],
							dependencies
						),
						'ready': ready,
						'error': error
					};
				}

				// Queue up any dependencies that are registered
				dependencies = filter( ['registered'], dependencies );
				for ( n = 0; n < dependencies.length; n += 1 ) {
					if ( $.inArray( dependencies[n], queue ) === -1 ) {
						queue[queue.length] = dependencies[n];
						if ( async ) {
							// Mark this module as async in the registry
							registry[dependencies[n]].async = true;
						}
					}
				}

				// Work the queue
				mw.loader.work();
			}

			function sortQuery(o) {
				var sorted = {}, key, a = [];
				for ( key in o ) {
					if ( hasOwn.call( o, key ) ) {
						a.push( key );
					}
				}
				a.sort();
				for ( key = 0; key < a.length; key += 1 ) {
					sorted[a[key]] = o[a[key]];
				}
				return sorted;
			}

			/**
			 * Converts a module map of the form { foo: [ 'bar', 'baz' ], bar: [ 'baz, 'quux' ] }
			 * to a query string of the form foo.bar,baz|bar.baz,quux
			 * @private
			 */
			function buildModulesString( moduleMap ) {
				var arr = [], p, prefix;
				for ( prefix in moduleMap ) {
					p = prefix === '' ? '' : prefix + '.';
					arr.push( p + moduleMap[prefix].join( ',' ) );
				}
				return arr.join( '|' );
			}

			/**
			 * Asynchronously append a script tag to the end of the body
			 * that invokes load.php
			 * @private
			 * @param {Object} moduleMap Module map, see #buildModulesString
			 * @param {Object} currReqBase Object with other parameters (other than 'modules') to use in the request
			 * @param {string} sourceLoadScript URL of load.php
			 * @param {boolean} async If true, use an asynchrounous request even if document ready has not yet occurred
			 */
			function doRequest( moduleMap, currReqBase, sourceLoadScript, async ) {
				var request = $.extend(
					{ modules: buildModulesString( moduleMap ) },
					currReqBase
				);
				request = sortQuery( request );
				// Asynchronously append a script tag to the end of the body
				// Append &* to avoid triggering the IE6 extension check
				addScript( sourceLoadScript + '?' + $.param( request ) + '&*', null, async );
			}

			/* Public Methods */
			return {
				addStyleTag: addStyleTag,

				/**
				 * Requests dependencies from server, loading and executing when things when ready.
				 */
				work: function () {
					var	reqBase, splits, maxQueryLength, q, b, bSource, bGroup, bSourceGroup,
						source, group, g, i, modules, maxVersion, sourceLoadScript,
						currReqBase, currReqBaseLength, moduleMap, l,
						lastDotIndex, prefix, suffix, bytesAdded, async;

					// Build a list of request parameters common to all requests.
					reqBase = {
						skin: mw.config.get( 'skin' ),
						lang: mw.config.get( 'wgUserLanguage' ),
						debug: mw.config.get( 'debug' )
					};
					// Split module batch by source and by group.
					splits = {};
					maxQueryLength = mw.config.get( 'wgResourceLoaderMaxQueryLength', -1 );

					// Appends a list of modules from the queue to the batch
					for ( q = 0; q < queue.length; q += 1 ) {
						// Only request modules which are registered
						if ( registry[queue[q]] !== undefined && registry[queue[q]].state === 'registered' ) {
							// Prevent duplicate entries
							if ( $.inArray( queue[q], batch ) === -1 ) {
								batch[batch.length] = queue[q];
								// Mark registered modules as loading
								registry[queue[q]].state = 'loading';
							}
						}
					}
					// Early exit if there's nothing to load...
					if ( !batch.length ) {
						return;
					}

					// The queue has been processed into the batch, clear up the queue.
					queue = [];

					// Always order modules alphabetically to help reduce cache
					// misses for otherwise identical content.
					batch.sort();

					// Split batch by source and by group.
					for ( b = 0; b < batch.length; b += 1 ) {
						bSource = registry[batch[b]].source;
						bGroup = registry[batch[b]].group;
						if ( splits[bSource] === undefined ) {
							splits[bSource] = {};
						}
						if ( splits[bSource][bGroup] === undefined ) {
							splits[bSource][bGroup] = [];
						}
						bSourceGroup = splits[bSource][bGroup];
						bSourceGroup[bSourceGroup.length] = batch[b];
					}

					// Clear the batch - this MUST happen before we append any
					// script elements to the body or it's possible that a script
					// will be locally cached, instantly load, and work the batch
					// again, all before we've cleared it causing each request to
					// include modules which are already loaded.
					batch = [];

					for ( source in splits ) {

						sourceLoadScript = sources[source].loadScript;

						for ( group in splits[source] ) {

							// Cache access to currently selected list of
							// modules for this group from this source.
							modules = splits[source][group];

							// Calculate the highest timestamp
							maxVersion = 0;
							for ( g = 0; g < modules.length; g += 1 ) {
								if ( registry[modules[g]].version > maxVersion ) {
									maxVersion = registry[modules[g]].version;
								}
							}

							currReqBase = $.extend( { version: formatVersionNumber( maxVersion ) }, reqBase );
							// For user modules append a user name to the request.
							if ( group === 'user' && mw.config.get( 'wgUserName' ) !== null ) {
								currReqBase.user = mw.config.get( 'wgUserName' );
							}
							currReqBaseLength = $.param( currReqBase ).length;
							async = true;
							// We may need to split up the request to honor the query string length limit,
							// so build it piece by piece.
							l = currReqBaseLength + 9; // '&modules='.length == 9

							moduleMap = {}; // { prefix: [ suffixes ] }

							for ( i = 0; i < modules.length; i += 1 ) {
								// Determine how many bytes this module would add to the query string
								lastDotIndex = modules[i].lastIndexOf( '.' );
								// Note that these substr() calls work even if lastDotIndex == -1
								prefix = modules[i].substr( 0, lastDotIndex );
								suffix = modules[i].substr( lastDotIndex + 1 );
								bytesAdded = moduleMap[prefix] !== undefined
									? suffix.length + 3 // '%2C'.length == 3
									: modules[i].length + 3; // '%7C'.length == 3

								// If the request would become too long, create a new one,
								// but don't create empty requests
								if ( maxQueryLength > 0 && !$.isEmptyObject( moduleMap ) && l + bytesAdded > maxQueryLength ) {
									// This request would become too long, create a new one
									// and fire off the old one
									doRequest( moduleMap, currReqBase, sourceLoadScript, async );
									moduleMap = {};
									async = true;
									l = currReqBaseLength + 9;
								}
								if ( moduleMap[prefix] === undefined ) {
									moduleMap[prefix] = [];
								}
								moduleMap[prefix].push( suffix );
								if ( !registry[modules[i]].async ) {
									// If this module is blocking, make the entire request blocking
									// This is slightly suboptimal, but in practice mixing of blocking
									// and async modules will only occur in debug mode.
									async = false;
								}
								l += bytesAdded;
							}
							// If there's anything left in moduleMap, request that too
							if ( !$.isEmptyObject( moduleMap ) ) {
								doRequest( moduleMap, currReqBase, sourceLoadScript, async );
							}
						}
					}
				},

				/**
				 * Register a source.
				 *
				 * @param {string} id Short lowercase a-Z string representing a source, only used internally.
				 * @param {Object} props Object containing only the loadScript property which is a url to
				 *  the load.php location of the source.
				 * @return {boolean}
				 */
				addSource: function ( id, props ) {
					var source;
					// Allow multiple additions
					if ( typeof id === 'object' ) {
						for ( source in id ) {
							mw.loader.addSource( source, id[source] );
						}
						return true;
					}

					if ( sources[id] !== undefined ) {
						throw new Error( 'source already registered: ' + id );
					}

					sources[id] = props;

					return true;
				},

				/**
				 * Registers a module, letting the system know about it and its
				 * properties. Startup modules contain calls to this function.
				 *
				 * @param module {String}: Module name
				 * @param version {Number}: Module version number as a timestamp (falls backs to 0)
				 * @param dependencies {String|Array|Function}: One string or array of strings of module
				 *  names on which this module depends, or a function that returns that array.
				 * @param group {String}: Group which the module is in (optional, defaults to null)
				 * @param source {String}: Name of the source. Defaults to local.
				 */
				register: function ( module, version, dependencies, group, source ) {
					var m;
					// Allow multiple registration
					if ( typeof module === 'object' ) {
						for ( m = 0; m < module.length; m += 1 ) {
							// module is an array of module names
							if ( typeof module[m] === 'string' ) {
								mw.loader.register( module[m] );
							// module is an array of arrays
							} else if ( typeof module[m] === 'object' ) {
								mw.loader.register.apply( mw.loader, module[m] );
							}
						}
						return;
					}
					// Validate input
					if ( typeof module !== 'string' ) {
						throw new Error( 'module must be a string, not a ' + typeof module );
					}
					if ( registry[module] !== undefined ) {
						throw new Error( 'module already registered: ' + module );
					}
					// List the module as registered
					registry[module] = {
						version: version !== undefined ? parseInt( version, 10 ) : 0,
						dependencies: [],
						group: typeof group === 'string' ? group : null,
						source: typeof source === 'string' ? source: 'local',
						state: 'registered'
					};
					if ( typeof dependencies === 'string' ) {
						// Allow dependencies to be given as a single module name
						registry[module].dependencies = [ dependencies ];
					} else if ( typeof dependencies === 'object' || $.isFunction( dependencies ) ) {
						// Allow dependencies to be given as an array of module names
						// or a function which returns an array
						registry[module].dependencies = dependencies;
					}
				},

				/**
				 * Implements a module, giving the system a course of action to take
				 * upon loading. Results of a request for one or more modules contain
				 * calls to this function.
				 *
				 * All arguments are required.
				 *
				 * @param {string} module Name of module
				 * @param {Function|Array} script Function with module code or Array of URLs to
				 *  be used as the src attribute of a new `<script>` tag.
				 * @param {Object} style Should follow one of the following patterns:
				 *     { "css": [css, ..] }
				 *     { "url": { <media>: [url, ..] } }
				 * And for backwards compatibility (needs to be supported forever due to caching):
				 *     { <media>: css }
				 *     { <media>: [url, ..] }
				 *
				 * The reason css strings are not concatenated anymore is bug 31676. We now check
				 * whether it's safe to extend the stylesheet (see #canExpandStylesheetWith).
				 *
				 * @param {Object} msgs List of key/value pairs to be added to {@link mw#messages}.
				 */
				implement: function ( module, script, style, msgs ) {
					// Validate input
					if ( typeof module !== 'string' ) {
						throw new Error( 'module must be a string, not a ' + typeof module );
					}
					if ( !$.isFunction( script ) && !$.isArray( script ) ) {
						throw new Error( 'script must be a function or an array, not a ' + typeof script );
					}
					if ( !$.isPlainObject( style ) ) {
						throw new Error( 'style must be an object, not a ' + typeof style );
					}
					if ( !$.isPlainObject( msgs ) ) {
						throw new Error( 'msgs must be an object, not a ' + typeof msgs );
					}
					// Automatically register module
					if ( registry[module] === undefined ) {
						mw.loader.register( module );
					}
					// Check for duplicate implementation
					if ( registry[module] !== undefined && registry[module].script !== undefined ) {
						throw new Error( 'module already implemented: ' + module );
					}
					// Attach components
					registry[module].script = script;
					registry[module].style = style;
					registry[module].messages = msgs;
					// The module may already have been marked as erroneous
					if ( $.inArray( registry[module].state, ['error', 'missing'] ) === -1 ) {
						registry[module].state = 'loaded';
						if ( allReady( registry[module].dependencies ) ) {
							execute( module );
						}
					}
				},

				/**
				 * Executes a function as soon as one or more required modules are ready
				 *
				 * @param dependencies {String|Array} Module name or array of modules names the callback
				 *  dependends on to be ready before executing
				 * @param ready {Function} callback to execute when all dependencies are ready (optional)
				 * @param error {Function} callback to execute when if dependencies have a errors (optional)
				 */
				using: function ( dependencies, ready, error ) {
					var tod = typeof dependencies;
					// Validate input
					if ( tod !== 'object' && tod !== 'string' ) {
						throw new Error( 'dependencies must be a string or an array, not a ' + tod );
					}
					// Allow calling with a single dependency as a string
					if ( tod === 'string' ) {
						dependencies = [ dependencies ];
					}
					// Resolve entire dependency map
					dependencies = resolve( dependencies );
					if ( allReady( dependencies ) ) {
						// Run ready immediately
						if ( $.isFunction( ready ) ) {
							ready();
						}
					} else if ( filter( ['error', 'missing'], dependencies ).length ) {
						// Execute error immediately if any dependencies have errors
						if ( $.isFunction( error ) ) {
							error( new Error( 'one or more dependencies have state "error" or "missing"' ),
								dependencies );
						}
					} else {
						// Not all dependencies are ready: queue up a request
						request( dependencies, ready, error );
					}
				},

				/**
				 * Loads an external script or one or more modules for future use
				 *
				 * @param modules {mixed} Either the name of a module, array of modules,
				 *  or a URL of an external script or style
				 * @param type {String} mime-type to use if calling with a URL of an
				 *  external script or style; acceptable values are "text/css" and
				 *  "text/javascript"; if no type is provided, text/javascript is assumed.
				 * @param async {Boolean} (optional) If true, load modules asynchronously
				 *  even if document ready has not yet occurred. If false (default),
				 *  block before document ready and load async after. If not set, true will
				 *  be assumed if loading a URL, and false will be assumed otherwise.
				 */
				load: function ( modules, type, async ) {
					var filtered, m, module, l;

					// Validate input
					if ( typeof modules !== 'object' && typeof modules !== 'string' ) {
						throw new Error( 'modules must be a string or an array, not a ' + typeof modules );
					}
					// Allow calling with an external url or single dependency as a string
					if ( typeof modules === 'string' ) {
						// Support adding arbitrary external scripts
						if ( /^(https?:)?\/\//.test( modules ) ) {
							if ( async === undefined ) {
								// Assume async for bug 34542
								async = true;
							}
							if ( type === 'text/css' ) {
								// IE7-8 throws security warnings when inserting a <link> tag
								// with a protocol-relative URL set though attributes (instead of
								// properties) - when on HTTPS. See also bug #.
								l = document.createElement( 'link' );
								l.rel = 'stylesheet';
								l.href = modules;
								$( 'head' ).append( l );
								return;
							}
							if ( type === 'text/javascript' || type === undefined ) {
								addScript( modules, null, async );
								return;
							}
							// Unknown type
							throw new Error( 'invalid type for external url, must be text/css or text/javascript. not ' + type );
						}
						// Called with single module
						modules = [ modules ];
					}

					// Filter out undefined modules, otherwise resolve() will throw
					// an exception for trying to load an undefined module.
					// Undefined modules are acceptable here in load(), because load() takes
					// an array of unrelated modules, whereas the modules passed to
					// using() are related and must all be loaded.
					for ( filtered = [], m = 0; m < modules.length; m += 1 ) {
						module = registry[modules[m]];
						if ( module !== undefined ) {
							if ( $.inArray( module.state, ['error', 'missing'] ) === -1 ) {
								filtered[filtered.length] = modules[m];
							}
						}
					}

					if ( filtered.length === 0 ) {
						return;
					}
					// Resolve entire dependency map
					filtered = resolve( filtered );
					// If all modules are ready, nothing to be done
					if ( allReady( filtered ) ) {
						return;
					}
					// If any modules have errors: also quit.
					if ( filter( ['error', 'missing'], filtered ).length ) {
						return;
					}
					// Since some modules are not yet ready, queue up a request.
					request( filtered, undefined, undefined, async );
				},

				/**
				 * Changes the state of a module
				 *
				 * @param module {String|Object} module name or object of module name/state pairs
				 * @param state {String} state name
				 */
				state: function ( module, state ) {
					var m;

					if ( typeof module === 'object' ) {
						for ( m in module ) {
							mw.loader.state( m, module[m] );
						}
						return;
					}
					if ( registry[module] === undefined ) {
						mw.loader.register( module );
					}
					if ( $.inArray( state, ['ready', 'error', 'missing'] ) !== -1
						&& registry[module].state !== state ) {
						// Make sure pending modules depending on this one get executed if their
						// dependencies are now fulfilled!
						registry[module].state = state;
						handlePending( module );
					} else {
						registry[module].state = state;
					}
				},

				/**
				 * Gets the version of a module
				 *
				 * @param module string name of module to get version for
				 */
				getVersion: function ( module ) {
					if ( registry[module] !== undefined && registry[module].version !== undefined ) {
						return formatVersionNumber( registry[module].version );
					}
					return null;
				},

				/**
				 * @deprecated since 1.18 use mw.loader.getVersion() instead
				 */
				version: function () {
					return mw.loader.getVersion.apply( mw.loader, arguments );
				},

				/**
				 * Gets the state of a module
				 *
				 * @param module string name of module to get state for
				 */
				getState: function ( module ) {
					if ( registry[module] !== undefined && registry[module].state !== undefined ) {
						return registry[module].state;
					}
					return null;
				},

				/**
				 * Get names of all registered modules.
				 *
				 * @return {Array}
				 */
				getModuleNames: function () {
					return $.map( registry, function ( i, key ) {
						return key;
					} );
				},

				/**
				 * For backwards-compatibility with Squid-cached pages. Loads mw.user
				 */
				go: function () {
					mw.loader.load( 'mediawiki.user' );
				}
			};
		}() ),

		/**
		 * HTML construction helper functions
		 * @class mw.html
		 * @singleton
		 */
		html: ( function () {
			function escapeCallback( s ) {
				switch ( s ) {
					case '\'':
						return '&#039;';
					case '"':
						return '&quot;';
					case '<':
						return '&lt;';
					case '>':
						return '&gt;';
					case '&':
						return '&amp;';
				}
			}

			return {
				/**
				 * Escape a string for HTML. Converts special characters to HTML entities.
				 * @param {string} s The string to escape
				 */
				escape: function ( s ) {
					return s.replace( /['"<>&]/g, escapeCallback );
				},

				/**
				 * Wrapper object for raw HTML passed to mw.html.element().
				 * @class mw.html.Raw
				 */
				Raw: function ( value ) {
					this.value = value;
				},

				/**
				 * Wrapper object for CDATA element contents passed to mw.html.element()
				 * @class mw.html.Cdata
				 */
				Cdata: function ( value ) {
					this.value = value;
				},

				/**
				 * Create an HTML element string, with safe escaping.
				 *
				 * @param name The tag name.
				 * @param attrs An object with members mapping element names to values
				 * @param contents The contents of the element. May be either:
				 *  - string: The string is escaped.
				 *  - null or undefined: The short closing form is used, e.g. <br/>.
				 *  - this.Raw: The value attribute is included without escaping.
				 *  - this.Cdata: The value attribute is included, and an exception is
				 *   thrown if it contains an illegal ETAGO delimiter.
				 *   See http://www.w3.org/TR/1999/REC-html401-19991224/appendix/notes.html#h-B.3.2
				 *
				 * Example:
				 *	var h = mw.html;
				 *	return h.element( 'div', {},
				 *		new h.Raw( h.element( 'img', {src: '<'} ) ) );
				 * Returns <div><img src="&lt;"/></div>
				 */
				element: function ( name, attrs, contents ) {
					var v, attrName, s = '<' + name;

					for ( attrName in attrs ) {
						v = attrs[attrName];
						// Convert name=true, to name=name
						if ( v === true ) {
							v = attrName;
						// Skip name=false
						} else if ( v === false ) {
							continue;
						}
						s += ' ' + attrName + '="' + this.escape( String( v ) ) + '"';
					}
					if ( contents === undefined || contents === null ) {
						// Self close tag
						s += '/>';
						return s;
					}
					// Regular open tag
					s += '>';
					switch ( typeof contents ) {
						case 'string':
							// Escaped
							s += this.escape( contents );
							break;
						case 'number':
						case 'boolean':
							// Convert to string
							s += String( contents );
							break;
						default:
							if ( contents instanceof this.Raw ) {
								// Raw HTML inclusion
								s += contents.value;
							} else if ( contents instanceof this.Cdata ) {
								// CDATA
								if ( /<\/[a-zA-z]/.test( contents.value ) ) {
									throw new Error( 'mw.html.element: Illegal end tag found in CDATA' );
								}
								s += contents.value;
							} else {
								throw new Error( 'mw.html.element: Invalid type of contents' );
							}
					}
					s += '</' + name + '>';
					return s;
				}
			};
		}() ),

		// Skeleton user object. mediawiki.user.js extends this
		user: {
			options: new Map(),
			tokens: new Map()
		}
	};

}( jQuery ) );

// Alias $j to jQuery for backwards compatibility
window.$j = jQuery;

// Attach to window and globally alias
window.mw = window.mediaWiki = mw;

// Auto-register from pre-loaded startup scripts
if ( jQuery.isFunction( window.startUp ) ) {
	window.startUp();
	window.startUp = undefined;
}
