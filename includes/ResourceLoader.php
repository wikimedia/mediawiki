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
 */
class ResourceLoader {

	/* Protected Static Members */

	// @var array list of module name/ResourceLoaderModule object pairs
	protected static $modules = array();
	protected static $initialized = false;

	/* Protected Static Methods */

	/*
	 * Registers core modules and runs registration hooks
	 */
	protected static function initialize() {
		global $IP;
		
		// Safety check - this should never be called more than once
		if ( !self::$initialized ) {
			wfProfileIn( __METHOD__ );
			// This needs to be first, because hooks might call ResourceLoader 
			// public interfaces which will call this
			self::$initialized = true;
			self::register( include( "$IP/resources/Resources.php" ) );
			wfRunHooks( 'ResourceLoaderRegisterModules' );
			wfProfileOut( __METHOD__ );
		}
	}

	/**
	 * Runs text through a filter, caching the filtered result for future calls
	 *
	 * @param $filter String: name of filter to run
	 * @param $data String: text to filter, such as JavaScript or CSS text
	 * @param $file String: path to file being filtered, (optional: only required 
	 *     for CSS to resolve paths)
	 * @return String: filtered data
	 */
	protected static function filter( $filter, $data ) {
		global $wgMemc;
		wfProfileIn( __METHOD__ );

		// For empty or whitespace-only things, don't do any processing
		if ( trim( $data ) === '' ) {
			wfProfileOut( __METHOD__ );
			return $data;
		}

		// Try memcached
		$key = wfMemcKey( 'resourceloader', 'filter', $filter, md5( $data ) );
		$cached = $wgMemc->get( $key );

		if ( $cached !== false && $cached !== null ) {
			wfProfileOut( __METHOD__ );
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
					wfProfileOut( __METHOD__ );
					return $data;
			}
		} catch ( Exception $exception ) {
			throw new MWException( 'Filter threw an exception: ' . $exception->getMessage() );
		}

		// Save to memcached
		$wgMemc->set( $key, $result );

		wfProfileOut( __METHOD__ );
		return $result;
	}

	/* Static Methods */

	/**
	 * Registers a module with the ResourceLoader system.
	 *
	 * Note that registering the same object under multiple names is not supported 
	 * and may silently fail in all kinds of interesting ways.
	 *
	 * @param $name Mixed: string of name of module or array of name/object pairs
	 * @param $object ResourceLoaderModule: module object (optional when using 
	 *    multiple-registration calling style)
	 * @return Boolean: false if there were any errors, in which case one or more 
	 *    modules were not registered
	 *
	 * @todo We need much more clever error reporting, not just in detailing what 
	 *    happened, but in bringing errors to the client in a way that they can 
	 *    easily see them if they want to, such as by using FireBug
	 */
	public static function register( $name, ResourceLoaderModule $object = null ) {
		wfProfileIn( __METHOD__ );
		self::initialize();
		
		// Allow multiple modules to be registered in one call
		if ( is_array( $name ) && !isset( $object ) ) {
			foreach ( $name as $key => $value ) {
				self::register( $key, $value );
			}

			wfProfileOut( __METHOD__ );
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
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Gets a map of all modules and their options
	 *
	 * @return Array: array( modulename => ResourceLoaderModule )
	 */
	public static function getModules() {
		
		self::initialize();
		
		return self::$modules;
	}

	/**
	 * Get the ResourceLoaderModule object for a given module name
	 *
	 * @param $name String: module name
	 * @return mixed ResourceLoaderModule or null if not registered
	 */
	public static function getModule( $name ) {
		
		self::initialize();
		
		return isset( self::$modules[$name] ) ? self::$modules[$name] : null;
	}

	/**
	 * Gets registration code for all modules
	 *
	 * @param $context ResourceLoaderContext object
	 * @return String: JavaScript code for registering all modules with the client loader
	 */
	public static function getModuleRegistrations( ResourceLoaderContext $context ) {
		wfProfileIn( __METHOD__ );
		self::initialize();
		
		$scripts = '';
		$registrations = array();

		foreach ( self::$modules as $name => $module ) {
			// Support module loader scripts
			if ( ( $loader = $module->getLoaderScript() ) !== false ) {
				$deps = FormatJson::encode( $module->getDependencies() );
				$group = FormatJson::encode( $module->getGroup() );
				$version = wfTimestamp( TS_ISO_8601, round( $module->getModifiedTime( $context ), -2 ) );
				$scripts .= "( function( name, version, dependencies ) { $loader } )\n" . 
					"( '$name', '$version', $deps, $group );\n";
			}
			// Automatically register module
			else {
				// Modules without dependencies or a group pass two arguments (name, timestamp) to 
				// mediaWiki.loader.register()
				if ( !count( $module->getDependencies() && $module->getGroup() === null ) ) {
					$registrations[] = array( $name, $module->getModifiedTime( $context ) );
				}
				// Modules with dependencies but no group pass three arguments (name, timestamp, dependencies) 
				// to mediaWiki.loader.register()
				else if ( $module->getGroup() === null ) {
					$registrations[] = array(
						$name, $module->getModifiedTime( $context ),  $module->getDependencies() );
				}
				// Modules with dependencies pass four arguments (name, timestamp, dependencies, group) 
				// to mediaWiki.loader.register()
				else {
					$registrations[] = array(
						$name, $module->getModifiedTime( $context ),  $module->getDependencies(), $module->getGroup() );
				}
			}
		}
		$out = $scripts . "mediaWiki.loader.register( " . FormatJson::encode( $registrations ) . " );\n";
		wfProfileOut( __METHOD__ );
		return $out;
	}

	/**
	 * Get the highest modification time of all modules, based on a given 
	 * combination of language code, skin name and debug mode flag.
	 *
	 * @param $context ResourceLoaderContext object
	 * @return Integer: UNIX timestamp
	 */
	public static function getHighestModifiedTime( ResourceLoaderContext $context ) {
		
		self::initialize();
		
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
		global $wgResourceLoaderVersionedClientMaxage, $wgResourceLoaderVersionedServerMaxage;
		global $wgResourceLoaderUnversionedServerMaxage, $wgResourceLoaderUnversionedClientMaxage;

		wfProfileIn( __METHOD__ );
		self::initialize();
		
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

		// If a version wasn't specified we need a shorter expiry time for updates to 
		// propagate to clients quickly
		if ( is_null( $context->getVersion() ) ) {
			$maxage = $wgResourceLoaderUnversionedClientMaxage;
			$smaxage = $wgResourceLoaderUnversionedServerMaxage;
		}
		// If a version was specified we can use a longer expiry time since changing 
		// version numbers causes cache misses
		else {
			$maxage = $wgResourceLoaderVersionedClientMaxage;
			$smaxage = $wgResourceLoaderVersionedServerMaxage;
		}

		// To send Last-Modified and support If-Modified-Since, we need to detect 
		// the last modified time
		wfProfileIn( __METHOD__.'-getModifiedTime' );
		$mtime = 1;
		foreach ( $modules as $name ) {
			$mtime = max( $mtime, self::$modules[$name]->getModifiedTime( $context ) );
		}
		wfProfileOut( __METHOD__.'-getModifiedTime' );

		header( 'Content-Type: ' . ( $context->getOnly() === 'styles' ? 'text/css' : 'text/javascript' ) );
		header( 'Last-Modified: ' . wfTimestamp( TS_RFC2822, $mtime ) );
		header( "Cache-Control: public, max-age=$maxage, s-maxage=$smaxage" );
		header( 'Expires: ' . wfTimestamp( TS_RFC2822, min( $maxage, $smaxage ) + time() ) );

		// If there's an If-Modified-Since header, respond with a 304 appropriately
		$ims = $context->getRequest()->getHeader( 'If-Modified-Since' );
		if ( $ims !== false && $mtime >= wfTimestamp( TS_UNIX, $ims ) ) {
			header( 'HTTP/1.0 304 Not Modified' );
			header( 'Status: 304 Not Modified' );
			wfProfileOut( __METHOD__ );
			return;
		}

		// Use output buffering
		ob_start();

		// Pre-fetch blobs
		$blobs = $context->shouldIncludeMessages() ?
		MessageBlobStore::get( $modules, $context->getLanguage() ) : array();

		// Generate output
		foreach ( $modules as $name ) {
			wfProfileIn( __METHOD__ . '-' . $name );
			// Scripts
			$scripts = '';

			if ( $context->shouldIncludeScripts() ) {
				$scripts .= self::$modules[$name]->getScript( $context ) . "\n";
			}

			// Styles
			$styles = array();

			if (
				$context->shouldIncludeStyles() 
				&& ( count( $styles = self::$modules[$name]->getStyles( $context ) ) )
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
				if ( count( $styles ) ) {
					$styles = FormatJson::encode( $styles );
				} else {
					$styles = 'null';
				}
				echo "mediaWiki.loader.implement( '$name', function() {{$scripts}},\n$styles,\n$messages );\n";
			}
			wfProfileOut( __METHOD__ . '-' . $name );
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
		wfProfileOut( __METHOD__ );
	}
}
