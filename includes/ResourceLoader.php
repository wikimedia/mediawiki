<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Roan Kattouw
 * @author Trevor Parscal
 */

/**
 * Dynamic JavaScript and CSS resource loading system
 *
 * @example
 * 	// Registers a module with the resource loading system
 * 	ResourceLoader::register( 'foo', array(
 * 		// Script or list of scripts to include when implementating the module (required)
 * 		'script' => 'resources/foo/foo.js',
 * 		// List of scripts or lists of scripts to include based on the current language
 * 		'locales' => array(
 * 			'en-gb' => 'resources/foo/locales/en-gb.js',
 * 		),
 * 		// Script or list of scripts to include only when in debug mode
 * 		'debug' => 'resources/foo/debug.js',
 * 		// If this module is going to be loaded before the mediawiki module is ready such as jquery or the mediawiki
 * 		// module itself, it can be included without special loader wrapping - this will also limit the module to not be
 * 		// able to specify needs, custom loaders, styles, themes or messages (any of the options below) - raw scripts
 * 		// get registered as 'ready' after the mediawiki module is ready, so they can be named as dependencies
 * 		'raw' => false,
 * 		// Modules or list of modules which are needed and should be used when generating loader code
 * 		'needs' => 'resources/foo/foo.js',
 * 		// Script or list of scripts which will cause loader code to not be generated - if you are doing something fancy
 * 		// with your dependencies this gives you a way to use custom registration code
 * 		'loader' => 'resources/foo/loader.js',
 * 		// Style-sheets or list of style-sheets to include
 * 		'style' => 'resources/foo/foo.css',
 * 		// List of style-sheets or lists of style-sheets to include based on the skin - if no match is found for current
 * 		// skin, 'default' is used - if default doesn't exist nothing is added
 * 		'themes' => array(
 * 			'default' => 'resources/foo/themes/default/foo.css',
 * 			'vector' => 'resources/foo/themes/vector.foo.css',
 * 		),
 * 		// List of keys of messages to include
 * 		'messages' => array( 'foo-hello', 'foo-goodbye' ),
 * 		// Subclass of ResourceLoaderModule to use for custom modules
 * 		'class' => 'ResourceLoaderSiteJSModule',
 * 	) );
 * @example
 * 	// Responds to a resource loading request
 * 	ResourceLoader::respond( $wgRequest, $wgServer . wfScript( 'load' ) );
 */
class ResourceLoader {
	/* Protected Static Members */

	// @var array list of module name/ResourceLoaderModule object pairs
	protected static $modules = array();

	/* Protected Static Methods */

	/**
	 * Runs text through a filter, caching the filtered result for future calls
	 *
	 * @param $filter String: name of filter to run
	 * @param $data String: text to filter, such as JavaScript or CSS text
	 * @param $file String: path to file being filtered, (optional: only required for CSS to resolve paths)
	 * @return String: filtered data
	 */
	protected static function filter( $filter, $data ) {
		global $wgMemc;

		// For empty or whitespace-only things, don't do any processing
		if ( trim( $data ) === '' ) {
			return $data;
		}

		// Try memcached
		$key = wfMemcKey( 'resourceloader', 'filter', $filter, md5( $data ) );
		$cached = $wgMemc->get( $key );

		if ( $cached !== false && $cached !== null ) {
			return $cached;
		}

		// Run the filter
		try {
			switch ( $filter ) {
				case 'minify-js':
					$result = JSMin::minify( $data );
					break;
				case 'minify-css':
					$result = CSSMin::minify( $data );
					break;
				case 'flip-css':
					$result = CSSJanus::transform( $data, true, false );
					break;
				default:
					// Don't cache anything, just pass right through
					return $data;
			}
		} catch ( Exception $exception ) {
			throw new MWException( 'Filter threw an exception: ' . $exception->getMessage() );
		}

		// Save to memcached
		$wgMemc->set( $key, $result );

		return $result;
	}

	/* Static Methods */

	/**
	 * Registers a module with the ResourceLoader system.
	 *
	 * Note that registering the same object under multiple names is not supported and may silently fail in all
	 * kinds of interesting ways.
	 *
	 * @param $name Mixed: string of name of module or array of name/object pairs
	 * @param $object ResourceLoaderModule: module object (optional when using multiple-registration calling style)
	 * @return Boolean: false if there were any errors, in which case one or more modules were not registered
	 *
	 * @todo We need much more clever error reporting, not just in detailing what happened, but in bringing errors to
	 * the client in a way that they can easily see them if they want to, such as by using FireBug
	 */
	public static function register( $name, ResourceLoaderModule $object = null ) {
		// Allow multiple modules to be registered in one call
		if ( is_array( $name ) && !isset( $object ) ) {
			foreach ( $name as $key => $value ) {
				self::register( $key, $value );
			}

			return;
		}

		// Disallow duplicate registrations
		if ( isset( self::$modules[$name] ) ) {
			// A module has already been registered by this name
			throw new MWException( 'Another module has already been registered as ' . $name );
		}
		// Attach module
		self::$modules[$name] = $object;
		$object->setName( $name );
	}

	/**
	 * Gets a map of all modules and their options
	 *
	 * @return Array: array( modulename => ResourceLoaderModule )
	 */
	public static function getModules() {
		return self::$modules;
	}

	/**
	 * Get the ResourceLoaderModule object for a given module name
	 *
	 * @param $name String: module name
	 * @return mixed ResourceLoaderModule or null if not registered
	 */
	public static function getModule( $name ) {
		return isset( self::$modules[$name] ) ? self::$modules[$name] : null;
	}

	/**
	 * Gets registration code for all modules, except pre-registered ones listed in self::$preRegisteredModules
	 *
	 * @param $context ResourceLoaderContext object
	 * @return String: JavaScript code for registering all modules with the client loader
	 */
	public static function getModuleRegistrations( ResourceLoaderContext $context ) {
		$scripts = '';
		$registrations = array();

		foreach ( self::$modules as $name => $module ) {
			// Support module loader scripts
			if ( ( $loader = $module->getLoaderScript() ) !== false ) {
				$scripts .= $loader;
			}
			// Automatically register module
			else {
				// Modules without dependencies pass two arguments (name, timestamp) to mediaWiki.loader.register()
				if ( !count( $module->getDependencies() ) ) {
					$registrations[] = array( $name, $module->getModifiedTime( $context ) );
				}
				// Modules with dependencies pass three arguments (name, timestamp, dependencies) to mediaWiki.loader.register()
				else {
					$registrations[] = array( $name, $module->getModifiedTime( $context ), $module->getDependencies() );
				}
			}
		}
		return $scripts . "mediaWiki.loader.register( " . FormatJson::encode( $registrations ) . " );";
	}

	/**
	 * Get the highest modification time of all modules, based on a given combination of language code,
	 * skin name and debug mode flag.
	 *
	 * @param $context ResourceLoaderContext object
	 * @return Integer: UNIX timestamp
	 */
	public static function getHighestModifiedTime( ResourceLoaderContext $context ) {
		$time = 1; // wfTimestamp() treats 0 as 'now', so that's not a suitable choice

		foreach ( self::$modules as $module ) {
			$time = max( $time, $module->getModifiedTime( $context ) );
		}

		return $time;
	}

	/**
	 * Outputs a response to a resource load-request, including a content-type header
	 *
	 * @param $context ResourceLoaderContext object
	 */
	public static function respond( ResourceLoaderContext $context ) {
		// Split requested modules into two groups, modules and missing
		$modules = array();
		$missing = array();

		foreach ( $context->getModules() as $name ) {
			if ( isset( self::$modules[$name] ) ) {
				$modules[] = $name;
			} else {
				$missing[] = $name;
			}
		}

		// Calculate the mtime and caching maxages for this request. We need this, 304 or no 304
		$mtime = 1;
		$maxage = PHP_INT_MAX;
		$smaxage = PHP_INT_MAX;

		foreach ( $modules as $name ) {
			$mtime = max( $mtime, self::$modules[$name]->getModifiedTime( $context ) );
			$maxage = min( $maxage, self::$modules[$name]->getClientMaxage() );
			$smaxage = min( $smaxage, self::$modules[$name]->getServerMaxage() );
		}

		// Output headers
		if ( $context->getOnly() === 'styles' ) {
			header( 'Content-Type: text/css' );
		} else {
			header( 'Content-Type: text/javascript' );
		}

		header( 'Last-Modified: ' . wfTimestamp( TS_RFC2822, $mtime ) );
		$expires = wfTimestamp( TS_RFC2822, min( $maxage, $smaxage ) + time() );
		header( "Cache-Control: public, max-age=$maxage, s-maxage=$smaxage" );
		header( "Expires: $expires" );

		// Check if there's an If-Modified-Since header and respond with a 304 Not Modified if possible
		$ims = $context->getRequest()->getHeader( 'If-Modified-Since' );

		if ( $ims !== false && wfTimestamp( TS_UNIX, $ims ) == $mtime ) {
			header( 'HTTP/1.0 304 Not Modified' );
			header( 'Status: 304 Not Modified' );
			return;
		}

		// Use output buffering
		ob_start();

		// Pre-fetch blobs
		$blobs = $context->shouldIncludeMessages() ?
		MessageBlobStore::get( $modules, $context->getLanguage() ) : array();

		// Generate output
		foreach ( $modules as $name ) {
			// Scripts
			$scripts = '';

			if ( $context->shouldIncludeScripts() ) {
				$scripts .= self::$modules[$name]->getScript( $context );
			}

			// Styles
			$styles = array();

			if (
				$context->shouldIncludeStyles() && ( count( $styles = self::$modules[$name]->getStyles( $context ) ) )
			) {
				foreach ( $styles as $media => $style ) {
					if ( self::$modules[$name]->getFlip( $context ) ) {
						$styles[$media] = self::filter( 'flip-css', $style );
					}
					if ( !$context->getDebug() ) {
						$styles[$media] = self::filter( 'minify-css', $style );
					}
				}
			}

			// Messages
			$messages = isset( $blobs[$name] ) ? $blobs[$name] : '{}';

			// Output
			if ( $context->getOnly() === 'styles' ) {
				if ( $context->getDebug() ) {
					echo "/* $name */\n";
					foreach ( $styles as $media => $style ) {
						echo "@media $media {\n" . str_replace( "\n", "\n\t", "\t" . $style ) . "\n}\n";
					}
				} else {
					foreach ( $styles as $media => $style ) {
						if ( strlen( $style ) ) {
							echo "@media $media{" . $style . "}";
						}
					}
				}
			} else if ( $context->getOnly() === 'scripts' ) {
				echo $scripts;
			} else if ( $context->getOnly() === 'messages' ) {
				echo "mediaWiki.msg.set( $messages );\n";
			} else {
				$styles = FormatJson::encode( $styles );
				echo "mediaWiki.loader.implement( '$name', function() {{$scripts}},\n$styles,\n$messages );\n";
			}
		}

		// Update the status of script-only modules
		if ( $context->getOnly() === 'scripts' && !in_array( 'startup', $modules ) ) {
			$statuses = array();

			foreach ( $modules as $name ) {
				$statuses[$name] = 'ready';
			}

			$statuses = FormatJson::encode( $statuses );
			echo "mediaWiki.loader.state( $statuses );\n";
		}

		// Register missing modules
		if ( $context->shouldIncludeScripts() ) {
			foreach ( $missing as $name ) {
				echo "mediaWiki.loader.register( '$name', null, 'missing' );\n";
			}
		}

		// Output the appropriate header
		if ( $context->getOnly() !== 'styles' ) {
			if ( $context->getDebug() ) {
				ob_end_flush();
			} else {
				echo self::filter( 'minify-js', ob_get_clean() );
			}
		}
	}
}

// FIXME: Temp hack
require_once "$IP/resources/Resources.php";
