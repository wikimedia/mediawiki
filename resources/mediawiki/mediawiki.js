/*
 * JavaScript backwards-compatibility and support
 */

// Make calling .indexOf() on an array work on older browsers
if ( typeof Array.prototype.indexOf === 'undefined' ) { 
	Array.prototype.indexOf = function( needle ) {
		for ( var i = 0; i < this.length; i++ ) {
			if ( this[i] === needle ) {
				return i;
			}
		}
		return -1;
	};
}
// Add array comparison functionality
if ( typeof Array.prototype.compare === 'undefined' ) { 
	Array.prototype.compare = function( against ) {
		if ( this.length != against.length ) {
			return false;
		}
		for ( var i = 0; i < against.length; i++ ) {
			if ( this[i].compare ) { 
				if ( !this[i].compare( against[i] ) ) {
					return false;
				}
			}
			if ( this[i] !== against[i] ) {
				return false;
			}
		}
		return true;
	};
}

/*
 * Core MediaWiki JavaScript Library
 */
// Attach to window
window.mediaWiki = new ( function( $ ) {
	
	/* Constants */
	
	// This will not change until we are 100% ready to turn off legacy globals
	var LEGACY_GLOBALS = true;
	
	/* Private Members */
	
	var that = this;
	
	/* Prototypes */
	
	this.prototypes = {
		/*
		 * An object which allows single and multiple get/set/exists functionality on a list of key / value pairs
		 * 
		 * @param {boolean} global whether to get/set/exists values on the window object or a private object
		 * @param {function} parser function to perform extra processing; in the form of function( value, options )
		 * where value is the data to be parsed and options is additional data passed through to the parser
		 */
		'configuration': function( global, parser ) {
			
			/* Private Members */
			
			var that = this;
			var values = global === true ? window : {};
			
			/* Public Methods */
			
			/**
			 * Gets one or more values
			 * 
			 * If called with no arguments, all values will be returned. If a parser is in use, no parsing will take
			 * place when calling with no arguments or calling with an array of names.
			 * 
			 * @param {mixed} selection string name of value to get, array of string names of values to get, or object
			 * of name/option pairs
			 * @param {object} options optional set of options which are also passed to a parser if in use; only used
			 * when selection is a string
			 * @format options
			 * 	{
			 * 		// Value to use if key does not exist
			 * 		'fallback': ''
			 * 	}
			 */
			this.get = function( selection, options ) {
				if ( typeof selection === 'object' ) {
					var results = {};
					for ( var s in selection ) {
						if ( selection.hasOwnProperty( s ) ) {
							if ( typeof s === 'string' ) {
								return that.get( values[s], selection[s] );
							} else {
								return that.get( selection[s] );
							}
						}
					}
					return results;
				} else if ( typeof selection === 'string' ) {
					if ( typeof values[selection] === 'undefined' ) {
						return typeof options === 'object' && 'fallback' in options ?
							options.fallback : '<' + selection + '>';
					} else {
						if ( typeof parser === 'function' ) {
							return parser( values[selection], options );
						} else {
							return values[selection];
						}
					}
				} else {
					return values;
				}
			};
			
			/**
			 * Sets one or multiple configuration values using a key and a value or an object of keys and values
			 * 
			 * @param {mixed} key string of name by which value will be made accessible, or object of name/value pairs
			 * @param {mixed} value optional value to set, only in use when key is a string
			 */
			this.set = function( selection, value ) {
				if ( typeof selection === 'object' ) {
					for ( var s in selection ) {
						values[s] = selection[s];
					}
				} else if ( typeof selection === 'string' && typeof value !== 'undefined' ) {
					values[selection] = value;
				}
			};
			
			/**
			 * Checks if one or multiple configuration fields exist
			 */
			this.exists = function( selection ) {
				if ( typeof keys === 'object' ) {
					for ( var s = 0; s < selection.length; s++ ) {
						if ( !( selection[s] in values ) ) {
							return false;
						}
					}
					return true;
				} else {
					return selection in values;
				}
			};
		}
	};
	
	/* Methods */
	
	/*
	 * Dummy function which in debug mode can be replaced with a function that does something clever
	 */
	this.log = function() { };
	
	/*
	 * List of configuration values
	 * 
	 * In legacy mode the values this object wraps will be in the global space
	 */
	this.config = new this.prototypes.configuration( LEGACY_GLOBALS );
	
	/*
	 * Information about the current user
	 */
	this.user = new ( function() {
		
		/* Public Members */
		
		this.options = new that.prototypes.configuration();
	} )();
	
	/*
	 * Basic parser, can be replaced with something more robust
	 */
	this.parser = function( text, options ) {
		if ( typeof options === 'object' && typeof options.parameters === 'object' ) {
			text = text.replace( /\$(\d+)/g, function( str, match ) {
				var index = parseInt( match, 10 ) - 1;
				return index in options.parameters ? options.parameters[index] : '$' + match;
			} );
		}
		return text;
	};
	
	/*
	 * Localization system
	 */
	this.msg = new that.prototypes.configuration( false, this.parser );
	
	/*
	 * Client-side module loader which integrates with the MediaWiki ResourceLoader
	 */
	this.loader = new ( function() {
		
		/* Private Members */
		
		var that = this;
		/*
		 * Mapping of registered modules
		 * 
		 * The jquery module is pre-registered, because it must have already been provided for this object to have
		 * been built, and in debug mode jquery would have been provided through a unique loader request, making it
		 * impossible to hold back registration of jquery until after mediawiki.
		 * 
		 * Format:
		 * 	{
		 * 		'moduleName': {
		 * 			'dependencies': ['required module', 'required module', ...], (or) function() {}
		 * 			'state': 'registered', 'loading', 'loaded', 'ready', or 'error'
		 * 			'script': function() {},
		 * 			'style': 'css code string',
		 * 			'messages': { 'key': 'value' },
		 * 			'version': ############## (unix timestamp)
		 * 		}
		 * 	}
		 */
		var registry = {};
		// List of modules which will be loaded as when ready
		var batch = [];
		// List of modules to be loaded
		var queue = [];
		// List of callback functions waiting for modules to be ready to be called
		var jobs = [];
		// Flag indicating that requests should be suspended
		var suspended = true;
		// Flag inidicating that document ready has occured
		var ready = false;
		
		/* Private Methods */
		
		/**
		 * Generates an ISO8601 "basic" string from a UNIX timestamp
		 */
		function formatVersionNumber( timestamp ) {
			function pad( a, b, c ) {
				return [a < 10 ? '0' + a : a, b < 10 ? '0' + b : b, c < 10 ? '0' + c : c].join( '' );
			}
			var d = new Date()
			d.setTime( timestamp * 1000 );
			return [
				pad( d.getUTCFullYear(), d.getUTCMonth() + 1, d.getUTCDate() ), 'T',
				pad( d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds() ), 'Z'
			].join( '' );
		}
		
		/**
		 * Recursively resolves dependencies and detects circular references
		 */
		function recurse( module, resolved, unresolved ) {
			unresolved[unresolved.length] = module;
			// Resolves dynamic loader function and replaces it with it's own results
			if ( typeof registry[module].dependencies === 'function' ) {
				registry[module].dependencies = registry[module].dependencies();
				// Gaurantees the module's dependencies are always in an array 
				if ( typeof registry[module].dependencies !== 'object' ) {
					registry[module].dependencies = [registry[module].dependencies];
				}
			}
			// Tracks down dependencies
			for ( var n = 0; n < registry[module].dependencies.length; n++ ) {
				if ( resolved.indexOf( registry[module].dependencies[n] ) === -1 ) {
					if ( unresolved.indexOf( registry[module].dependencies[n] ) !== -1 ) {
						throw new Error(
							'Circular reference detected: ' + module + ' -> ' + registry[module].dependencies[n]
						);
					}
					recurse( registry[module].dependencies[n], resolved, unresolved );
				}
			}
			resolved[resolved.length] = module;
			unresolved.splice( unresolved.indexOf( module ), 1 );
		}
		
		/**
		 * Gets a list of modules names that a module dependencies in their proper dependency order
		 * 
		 * @param mixed string module name or array of string module names
		 * @return list of dependencies
		 * @throws Error if circular reference is detected
		 */
		function resolve( module, resolved, unresolved ) {
			// Allow calling with an array of module names
			if ( typeof module === 'object' ) {
				var modules = [];
				for ( var m = 0; m < module.length; m++ ) {
					var dependencies = resolve( module[m] );
					for ( var n = 0; n < dependencies.length; n++ ) {
						modules[modules.length] = dependencies[n];
					}
				}
				return modules;
			} else if ( typeof module === 'string' ) {
				// Undefined modules have no dependencies
				if ( !( module in registry ) ) {
					return [];
				}
				var resolved = [];
				recurse( module, resolved, [] );
				return resolved;
			}
			throw new Error( 'Invalid module argument: ' + module );
		};
		
		/**
		 * Narrows a list of module names down to those matching a specific state. Possible states are 'undefined',
		 * 'registered', 'loading', 'loaded', or 'ready'
		 * 
		 * @param mixed string or array of strings of module states to filter by
		 * @param array list of module names to filter (optional, all modules will be used by default)
		 * @return array list of filtered module names
		 */
		function filter( states, modules ) {
			// Allow states to be given as a string
			if ( typeof states === 'string' ) {
				states = [states];
			}
			// If called without a list of modules, build and use a list of all modules
			var list = [];
			if ( typeof modules === 'undefined' ) {
				modules = [];
				for ( module in registry ) {
					modules[modules.length] = module;
				}
			}
			// Build a list of modules which are in one of the specified states
			for ( var s = 0; s < states.length; s++ ) {
				for ( var m = 0; m < modules.length; m++ ) {
					if (
						( states[s] == 'undefined' && typeof registry[modules[m]] === 'undefined' ) ||
						( typeof registry[modules[m]] === 'object' && registry[modules[m]].state === states[s] )
					) {
						list[list.length] = modules[m];
					}
				}
			}
			return list;
		}
		
		/**
		 * Executes a loaded module, making it ready to use
		 * 
		 * @param string module name to execute
		 */
		function execute( module ) {
			if ( typeof registry[module] === 'undefined' ) {
				throw new Error( 'Module has not been registered yet: ' + module );
			} else if ( registry[module].state === 'registered' ) {
				throw new Error( 'Module has not been requested from the server yet: ' + module );
			} else if ( registry[module].state === 'loading' ) {
				throw new Error( 'Module has not completed loading yet: ' + module );
			} else if ( registry[module].state === 'ready' ) {
				throw new Error( 'Module has already been loaded: ' + module );
			}
			// Add style sheet to document
			if ( typeof registry[module].style === 'string' && registry[module].style.length ) {
				$( 'head' ).append( '<style type="text/css">' + registry[module].style + '</style>' );
			} else if ( typeof registry[module].style === 'object' && !( registry[module].style instanceof Array ) ) {
				for ( var media in registry[module].style ) {
					$( 'head' ).append(
						'<style type="text/css" media="' + media + '">' + registry[module].style[media] + '</style>'
					);
				}
			}
			// Add localizations to message system
			if ( typeof registry[module].messages === 'object' ) {
				mediaWiki.msg.set( registry[module].messages );
			}
			// Execute script
			try {
				registry[module].script();
				registry[module].state = 'ready';
				// Run jobs who's dependencies have just been met
				for ( var j = 0; j < jobs.length; j++ ) {
					if ( filter( 'ready', jobs[j].dependencies ).compare( jobs[j].dependencies ) ) {
						if ( typeof jobs[j].ready === 'function' ) {
							jobs[j].ready();
						}
						jobs.splice( j, 1 );
						j--;
					}
				}
				// Execute modules who's dependencies have just been met
				for ( r in registry ) {
					if ( registry[r].state == 'loaded' ) {
						if ( filter( ['ready'], registry[r].dependencies ).compare( registry[r].dependencies ) ) {
							execute( r );
						}
					}
				}
			} catch ( e ) {
				mediaWiki.log( 'Exception thrown by ' + module + ': ' + e.message );
				mediaWiki.log( e );
				registry[module].state = 'error';				
				// Run error callbacks of jobs affected by this condition
				for ( var j = 0; j < jobs.length; j++ ) {
					if ( jobs[j].dependencies.indexOf( module ) !== -1 ) {
						if ( typeof jobs[j].error === 'function' ) {
							jobs[j].error();
						}
						jobs.splice( j, 1 );
						j--;
					}
				}
			}
		}
		
		/**
		 * Adds a dependencies to the queue with optional callbacks to be run when the dependencies are ready or fail
		 * 
		 * @param mixed string moulde name or array of string module names
		 * @param function ready callback to execute when all dependencies are ready
		 * @param function error callback to execute when any dependency fails
		 */
		function request( dependencies, ready, error ) {
			// Allow calling by single module name
			if ( typeof dependencies === 'string' ) {
				dependencies = [dependencies];
				if ( dependencies[0] in registry ) {
					for ( var n = 0; n < registry[dependencies[0]].dependencies.length; n++ ) {
						dependencies[dependencies.length] = registry[dependencies[0]].dependencies[n];
					}
				}
			}
			// Add ready and error callbacks if they were given
			if ( arguments.length > 1 ) {
				jobs[jobs.length] = {
					'dependencies': filter( ['undefined', 'registered', 'loading', 'loaded'], dependencies ),
					'ready': ready,
					'error': error
				};
			}
			// Queue up any dependencies that are undefined or registered
			dependencies = filter( ['undefined', 'registered'], dependencies );
			for ( var n = 0; n < dependencies.length; n++ ) {
				if ( queue.indexOf( dependencies[n] ) === -1 ) {
					queue[queue.length] = dependencies[n];
				}
			}
			// Work the queue
			that.work();
		}
		
		function sortQuery(o) {
			var sorted = {}, key, a = [];
			for ( key in o ) {
				if ( o.hasOwnProperty( key ) ) {
					a.push( key );
				}
			}
			a.sort();
			for ( key = 0; key < a.length; key++ ) {
				sorted[a[key]] = o[a[key]];
			}
			return sorted;
		}
		
		/* Public Methods */
		
		/**
		 * Requests dependencies from server, loading and executing when things when ready.
		 */
		this.work = function() {
			// Appends a list of modules to the batch
			for ( var q = 0; q < queue.length; q++ ) {
				// Only request modules which are undefined or registered
				if ( !( queue[q] in registry ) || registry[queue[q]].state == 'registered' ) {
					// Prevent duplicate entries
					if ( batch.indexOf( queue[q] ) === -1 ) {
						batch[batch.length] = queue[q];
						// Mark registered modules as loading
						if ( queue[q] in registry ) {
							registry[queue[q]].state = 'loading';
						}
					}
				}
			}
			// Clean up the queue
			queue = [];
			// After document ready, handle the batch
			if ( !suspended && batch.length ) {
				// Always order modules alphabetically to help reduce cache misses for otherwise identical content
				batch.sort();
				// Build a list of request parameters
				var base = {
					'skin': mediaWiki.config.get( 'skin' ),
					'lang': mediaWiki.config.get( 'wgUserLanguage' ),
					'debug': mediaWiki.config.get( 'debug' )
				};
				// Extend request parameters with a list of modules in the batch
				var requests = [];
				if ( base.debug == '1' ) {
					for ( var b = 0; b < batch.length; b++ ) {
						requests[requests.length] = $.extend(
							{ 'modules': batch[b], 'version': registry[batch[b]].version }, base
						);
					}
				} else {
					// Split into groups
					var groups = {};
					for ( var b = 0; b < batch.length; b++ ) {
						var group = registry[batch[b]].group;
						if ( !( group in groups ) ) {
							groups[group] = [];
						}
						groups[group][groups[group].length] = batch[b];
					}
					for ( var group in groups ) {
						// Calculate the highest timestamp
						var version = 0;
						for ( var g = 0; g < groups[group].length; g++ ) {
							if ( registry[groups[group][g]].version > version ) {
								version = registry[groups[group][g]].version;
							}
						}
						requests[requests.length] = $.extend(
							{ 'modules': groups[group].join( '|' ), 'version': formatVersionNumber( version ) }, base
						);
					}
				}
				// Clear the batch - this MUST happen before we append the script element to the body or it's
				// possible that the script will be locally cached, instantly load, and work the batch again,
				// all before we've cleared it causing each request to include modules which are already loaded
				batch = [];
				// Asynchronously append a script tag to the end of the body
				function request() {
					var html = '';
					for ( var r = 0; r < requests.length; r++ ) {
						requests[r] = sortQuery( requests[r] );
						// Build out the HTML
						var src = mediaWiki.config.get( 'wgLoadScript' ) + '?' + $.param( requests[r] );
						html += '<script type="text/javascript" src="' + src + '"></script>';
					}
					return html;
				}
				// Load asynchronously after doumument ready
				if ( ready ) {
					setTimeout(  function() { $( 'body' ).append( request() ); }, 0 )
				} else {
					document.write( request() );
				}
			}
		};
		
		/**
		 * Registers a module, letting the system know about it and it's dependencies. loader.js files contain calls
		 * to this function.
		 */
		this.register = function( module, version, dependencies, group ) {
			// Allow multiple registration
			if ( typeof module === 'object' ) {
				for ( var m = 0; m < module.length; m++ ) {
					if ( typeof module[m] === 'string' ) {
						that.register( module[m] );
					} else if ( typeof module[m] === 'object' ) {
						that.register.apply( that, module[m] );
					}
				}
				return;
			}
			// Validate input
			if ( typeof module !== 'string' ) {
				throw new Error( 'module must be a string, not a ' + typeof module );
			}
			if ( typeof registry[module] !== 'undefined' ) {
				throw new Error( 'module already implemeneted: ' + module );
			}
			// List the module as registered
			registry[module] = {
				'state': 'registered',
				'group': typeof group === 'string' ? group : null,
				'dependencies': [],
				'version': typeof version !== 'undefined' ? parseInt( version ) : 0
			};
			if ( typeof dependencies === 'string' ) {
				// Allow dependencies to be given as a single module name
				registry[module].dependencies = [dependencies];
			} else if ( typeof dependencies === 'object' || typeof dependencies === 'function' ) {
				// Allow dependencies to be given as an array of module names or a function which returns an array
				registry[module].dependencies = dependencies;
			}
		};
		
		/**
		 * Implements a module, giving the system a course of action to take upon loading. Results of a request for
		 * one or more modules contain calls to this function.
		 */
		this.implement = function( module, script, style, localization ) {
			// Automaically register module
			if ( typeof registry[module] === 'undefined' ) {
				that.register( module );
			}
			// Validate input
			if ( typeof script !== 'function' ) {
				throw new Error( 'script must be a function, not a ' + typeof script );
			}
			if ( typeof style !== 'undefined' && typeof style !== 'string' && typeof style !== 'object' ) {
				throw new Error( 'style must be a string or object, not a ' + typeof style );
			}
			if ( typeof localization !== 'undefined' && typeof localization !== 'object' ) {
				throw new Error( 'localization must be an object, not a ' + typeof localization );
			}
			if ( typeof registry[module] !== 'undefined' && typeof registry[module].script !== 'undefined' ) {
				throw new Error( 'module already implemeneted: ' + module );
			}
			// Mark module as loaded
			registry[module].state = 'loaded';
			// Attach components
			registry[module].script = script;
			if ( typeof style === 'string' || typeof style === 'object' && !( style instanceof Array ) ) {
				registry[module].style = style;
			}
			if ( typeof localization === 'object' ) {
				registry[module].messages = localization;
			}
			// Execute or queue callback
			if ( filter( ['ready'], registry[module].dependencies ).compare( registry[module].dependencies ) ) {
				execute( module );
			} else {
				request( module );
			}
		};
		
		/**
		 * Executes a function as soon as one or more required modules are ready
		 * 
		 * @param mixed string or array of strings of modules names the callback dependencies to be ready before
		 * executing
		 * @param function callback to execute when all dependencies are ready (optional)
		 * @param function callback to execute when if dependencies have a errors (optional)
		 */
		this.using = function( dependencies, ready, error ) {
			// Validate input
			if ( typeof dependencies !== 'object' && typeof dependencies !== 'string' ) {
				throw new Error( 'dependencies must be a string or an array, not a ' + typeof dependencies )
			}
			// Allow calling with a single dependency as a string
			if ( typeof dependencies === 'string' ) {
				dependencies = [dependencies];
			}
			// Resolve entire dependency map
			dependencies = resolve( dependencies );
			// If all dependencies are met, execute ready immediately
			if ( filter( ['ready'], dependencies ).compare( dependencies ) ) {
				if ( typeof ready === 'function' ) {
					ready();
				}
			}
			// If any dependencies have errors execute error immediately
			else if ( filter( ['error'], dependencies ).length ) {
				if ( typeof error === 'function' ) {
					error();
				}
			}
			// Since some dependencies are not yet ready, queue up a request
			else {
				request( dependencies, ready, error );
			}
		};
		
		/**
		 * Loads an external script or one or more modules for future use
		 * 
		 * @param {mixed} modules either the name of a module, array of modules, or a URL of an external script or style
		 * @param {string} type mime-type to use if calling with a URL of an external script or style; acceptable values
		 * are "text/css" and "text/javascript"; if no type is provided, text/javascript is assumed
		 */
		this.load = function( modules, type ) {
			// Validate input
			if ( typeof modules !== 'object' && typeof modules !== 'string' ) {
				throw new Error( 'dependencies must be a string or an array, not a ' + typeof dependencies )
			}
			// Allow calling with an external script or single dependency as a string
			if ( typeof modules === 'string' ) {
				// Support adding arbitrary external scripts
				if ( modules.substr( 0, 7 ) == 'http://' || modules.substr( 0, 8 ) == 'https://' ) {
					if ( type === 'text/css' ) {
						setTimeout(  function() {
							$( 'head' ).append( '<link rel="stylesheet" type="text/css" />' ).attr( 'href', modules );
						}, 0 );
						return true;
					} else if ( type === 'text/javascript' || typeof type === 'undefined' ) {
						setTimeout(  function() {
							$( 'body' ).append( '<script type="text/javascript"></script>'  ).attr( 'src', modules )
						}, 0 );
						return true;
					}
					// Unknown type
					return false;
				}
				// Called with single module
				modules = [modules];
			}
			// Resolve entire dependency map
			modules = resolve( modules );
			// If all modules are ready, nothing dependency be done
			if ( filter( ['ready'], modules ).compare( modules ) ) {
				return true;
			}
			// If any modules have errors return false
			else if ( filter( ['error'], modules ).length ) {
				return false;
			}
			// Since some modules are not yet ready, queue up a request
			else {
				request( modules );
				return true;
			}
		};
		
		/**
		 * Flushes the request queue and begin executing load requests on demand
		 */
		this.go = function() {
			suspended = false;
			that.work();
		};
		
		/**
		 * Changes the state of a module
		 * 
		 * @param mixed module string module name or object of module name/state pairs
		 * @param string state string state name
		 */
		this.state = function( module, state ) {
			if ( typeof module === 'object' ) {
				for ( var m in module ) {
					that.state( m, module[m] );
				}
				return;
			}
			if ( !( module in registry ) ) {
				that.register( module );
			}
			registry[module].state = state;
		};
		
		/**
		 * Gets the version of a module
		 * 
		 * @param string module name of module to get version for
		 */
		this.version = function( module ) {
			if ( module in registry && 'version' in registry[module] ) {
				return formatVersionNumber( registry[module].version );
			}
			return null;
		}
		
		/* Cache document ready status */
		
		$(document).ready( function() { ready = true; } );
	} )();
	
	/* Extension points */
	
	this.util = {};
	this.legacy = {};
	
} )( jQuery );

/* Auto-register from pre-loaded startup scripts */

if ( typeof startUp === 'function' ) {
	startUp();
	delete startUp;
}

// Alias $j to jQuery for backwards compatibility
window.$j = jQuery;
