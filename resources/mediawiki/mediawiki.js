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
	const LEGACY_GLOBALS = true;
	
	/* Members */
	
	this.legacy = LEGACY_GLOBALS ? window : {};
	
	/* Methods */
	
	/*
	 * Dummy function which in debug mode can be replaced with a function that does something clever
	 */
	this.log = function() { };
	/*
	 * An object which allows single and multiple existence, setting and getting on a list of key / value pairs
	 */
	this.config = new ( function() {
		
		/* Private Members */
		
		var that = this;
		// List of configuration values - in legacy mode these configurations were ALL in the global space
		var values = LEGACY_GLOBALS ? window : {};
		
		/* Public Methods */
		
		/**
		 * Sets one or multiple configuration values using a key and a value or an object of keys and values
		 */
		this.set = function( keys, value ) {
			if ( typeof keys === 'object' ) {
				for ( var k in keys ) {
					values[k] = keys[k];
				}
			} else if ( typeof keys === 'string' && typeof value !== 'undefined' ) {
				values[keys] = value;
			}
		};
		/**
		 * Gets one or multiple configuration values using a key and an optional fallback or an array of keys
		 */
		this.get = function( keys, fallback ) {
			if ( typeof keys === 'object' ) {
				var result = {};
				for ( var k = 0; k < keys.length; k++ ) {
					if ( typeof values[keys[k]] !== 'undefined' ) {
						result[keys[k]] = values[keys[k]];
					}
				}
				return result;
			} else if ( typeof keys === 'string' ) {
				if ( typeof values[keys] === 'undefined' ) {
					return typeof fallback !== 'undefined' ? fallback : null;
				} else {
					return values[keys];
				}
			} else {
				return values;
			}
		};
		/**
		 * Checks if one or multiple configuration fields exist
		 */
		this.exists = function( keys ) {
			if ( typeof keys === 'object' ) {
				for ( var k = 0; k < keys.length; k++ ) {
					if ( !( keys[k] in values ) ) {
						return false;
					}
				}
				return true;
			} else {
				return keys in values;
			}
		};
	} )();
	/*
	 * Localization system
	 */
	this.msg = new ( function() {
		
		/* Private Members */
		
		var that = this;
		// List of localized messages
		var messages = {};
		
		/* Public Methods */
		
		this.set = function( keys, value ) {
			if ( typeof keys === 'object' ) {
				for ( var k in keys ) {
					messages[k] = keys[k];
				}
			} else if ( typeof keys === 'string' && typeof value !== 'undefined' ) {
				messages[keys] = value;
			}
		};
		this.get = function( key, args ) {
			if ( !( key in messages ) ) {
				return '<' + key + '>';
			}
			var msg = messages[key];
			if ( typeof args == 'object' || typeof args == 'array' ) {
				for ( var a = 0; a < args.length; a++ ) {
					msg = msg.replace( '\$' + ( parseInt( a ) + 1 ), args[a] );
				}
			} else if ( typeof args == 'string' || typeof args == 'number' ) {
				msg = msg.replace( '$1', args );
			}
			return msg;
		};
	} )();
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
		 * Generates an ISO8601 string from a UNIX timestamp
		 */
		function formatVersionNumber( timestamp ) {
			var date = new Date();
			date.setTime( timestamp * 1000 );
			function pad1( n ) {
				return n < 10 ? '0' + n : n
			}
			function pad2( n ) {
				return n < 10 ? '00' + n : ( n < 100 ? '0' + n : n );     
			}
			return date.getUTCFullYear() + '-' +
				pad1( date.getUTCMonth() + 1 ) + '-' +
				pad1( date.getUTCDate() ) + 'T' +
				pad1( date.getUTCHours() ) + ':' +
				pad1( date.getUTCMinutes() ) + ':' +
				pad1( date.getUTCSeconds() ) +
				'Z';
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
			} else if ( typeof registry[module].style === 'object' ) {
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
					// Calculate the highest timestamp
					var version = 0;
					for ( var b = 0; b < batch.length; b++ ) {
						if ( registry[batch[b]].version > version ) {
							version = registry[batch[b]].version;
						}
					}
					requests[requests.length] = $.extend(
						{ 'modules': batch.join( '|' ), 'version': formatVersionNumber( version ) }, base
					);
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
		this.register = function( module, version, dependencies, status ) {
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
			if ( typeof registry[module] !== 'undefined' && typeof status === 'undefined' ) {
				throw new Error( 'module already implemeneted: ' + module );
			}
			// List the module as registered
			registry[module] = {
				'state': typeof status === 'string' ? status : 'registered',
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
			if ( typeof style === 'string' || typeof style === 'object' ) {
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
				if ( typeof ready !== 'function' ) {
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
		 * Loads one or more modules for future use
		 */
		this.load = function( modules ) {
			// Validate input
			if ( typeof modules !== 'object' && typeof modules !== 'string' ) {
				throw new Error( 'dependencies must be a string or an array, not a ' + typeof dependencies )
			}
			// Allow calling with a single dependency as a string
			if ( typeof modules === 'string' ) {
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
			if ( module in registry ) {
				registry[module].state = state;
			}
		};
		
		/* Cache document ready status */
		
		$(document).ready( function() { ready = true; } );
	} )();
	
	/* Extension points */
	
	this.util = {};
	this.legacy = {};
	
} )( jQuery );


/* Auto-register from pre-loaded startup scripts */

if ( typeof window['startUp'] === 'function' ) {
	window['startUp']();
	delete window['startUp'];
}