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

defined( 'MEDIAWIKI' ) || die( 1 );

/**
 * Dynamic JavaScript and CSS resource loading system
 */
class ResourceLoader {

	/* Protected Static Members */

	// @var array list of module name/ResourceLoaderModule object pairs
	protected $modules = array();

	/* Protected Methods */
	
	/**
	 * Loads information stored in the database about modules
	 * 
	 * This is not inside the module code because it's so much more performant to request all of the information at once
	 * than it is to have each module requests it's own information.
	 * 
	 * @param $modules array list of module names to preload information for
	 * @param $context ResourceLoaderContext context to load the information within
	 */
	protected function preloadModuleInfo( array $modules, ResourceLoaderContext $context ) {
		if ( !count( $modules ) ) {
			return; # or Database*::select() will explode
		}
		$dbr = wfGetDb( DB_SLAVE );
		$skin = $context->getSkin();
		$lang = $context->getLanguage();
		
		// Get file dependency information
		$res = $dbr->select( 'module_deps', array( 'md_module', 'md_deps' ), array(
				'md_module' => $modules,
				'md_skin' => $context->getSkin()
			), __METHOD__
		);
		
		$modulesWithDeps = array();
		foreach ( $res as $row ) {
			$this->modules[$row->md_module]->setFileDependencies( $skin,
				FormatJson::decode( $row->md_deps, true )
			);
			$modulesWithDeps[] = $row->md_module;
		}
		// Register the absence of a dependencies row too
		foreach ( array_diff( $modules, $modulesWithDeps ) as $name ) {
			$this->modules[$name]->setFileDependencies( $skin, array() );
		}
		
		// Get message blob mtimes. Only do this for modules with messages
		$modulesWithMessages = array();
		$modulesWithoutMessages = array();
		foreach ( $modules as $name ) {
			if ( count( $this->modules[$name]->getMessages() ) ) {
				$modulesWithMessages[] = $name;
			} else {
				$modulesWithoutMessages[] = $name;
			}
		}
		if ( count( $modulesWithMessages ) ) {
			$res = $dbr->select( 'msg_resource', array( 'mr_resource', 'mr_timestamp' ), array(
					'mr_resource' => $modulesWithMessages,
					'mr_lang' => $lang
				), __METHOD__
			);
			foreach ( $res as $row ) {
				$this->modules[$row->mr_resource]->setMsgBlobMtime( $lang, $row->mr_timestamp );
			}
		}
		foreach ( $modulesWithoutMessages as $name ) {
			$this->modules[$name]->setMsgBlobMtime( $lang, 0 );
		}
	}

	/**
	 * Runs text through a filter, caching the filtered result for future calls
	 *
	 * @param $filter String: name of filter to run
	 * @param $data String: text to filter, such as JavaScript or CSS text
	 * @param $file String: path to file being filtered, (optional: only required for CSS to resolve paths)
	 * @return String: filtered data
	 */
	protected function filter( $filter, $data ) {
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

	/* Methods */

	/**
	 * Registers core modules and runs registration hooks
	 */
	public function __construct() {
		global $IP;
		
		wfProfileIn( __METHOD__ );
		
		// Register core modules
		$this->register( include( "$IP/resources/Resources.php" ) );
		// Register extension modules
		wfRunHooks( 'ResourceLoaderRegisterModules', array( &$this ) );
		
		wfProfileOut( __METHOD__ );
	}

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
	public function register( $name, ResourceLoaderModule $object = null ) {
		wfProfileIn( __METHOD__ );
		
		// Allow multiple modules to be registered in one call
		if ( is_array( $name ) && !isset( $object ) ) {
			foreach ( $name as $key => $value ) {
				$this->register( $key, $value );
			}

			wfProfileOut( __METHOD__ );
			return;
		}

		// Disallow duplicate registrations
		if ( isset( $this->modules[$name] ) ) {
			// A module has already been registered by this name
			throw new MWException( 'Another module has already been registered as ' . $name );
		}
		
		// Validate the input (type hinting lets null through)
		if ( !( $object instanceof ResourceLoaderModule ) ) {
			throw new MWException( 'Invalid ResourceLoader module error. Instances of ResourceLoaderModule expected.' );
		}
		
		// Attach module
		$this->modules[$name] = $object;
		$object->setName( $name );
		
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Gets a map of all modules and their options
	 *
	 * @return Array: array( modulename => ResourceLoaderModule )
	 */
	public function getModules() {
		return $this->modules;
	}

	/**
	 * Get the ResourceLoaderModule object for a given module name
	 *
	 * @param $name String: module name
	 * @return mixed ResourceLoaderModule or null if not registered
	 */
	public function getModule( $name ) {
		return isset( $this->modules[$name] ) ? $this->modules[$name] : null;
	}

	/**
	 * Outputs a response to a resource load-request, including a content-type header
	 *
	 * @param $context ResourceLoaderContext object
	 */
	public function respond( ResourceLoaderContext $context ) {
		global $wgResourceLoaderMaxage;

		wfProfileIn( __METHOD__ );
		
		// Split requested modules into two groups, modules and missing
		$modules = array();
		$missing = array();
		
		foreach ( $context->getModules() as $name ) {
			if ( isset( $this->modules[$name] ) ) {
				$modules[$name] = $this->modules[$name];
			} else {
				$missing[] = $name;
			}
		}

		// If a version wasn't specified we need a shorter expiry time for updates to 
		// propagate to clients quickly
		if ( is_null( $context->getVersion() ) ) {
			$maxage = $wgResourceLoaderMaxage['unversioned']['client'];
			$smaxage = $wgResourceLoaderMaxage['unversioned']['server'];
		}
		// If a version was specified we can use a longer expiry time since changing 
		// version numbers causes cache misses
		else {
			$maxage = $wgResourceLoaderMaxage['versioned']['client'];
			$smaxage = $wgResourceLoaderMaxage['versioned']['server'];
		}

		// Preload information needed to the mtime calculation below
		$this->preloadModuleInfo( array_keys( $modules ), $context );

		// To send Last-Modified and support If-Modified-Since, we need to detect 
		// the last modified time
		wfProfileIn( __METHOD__.'-getModifiedTime' );
		$mtime = 1;
		foreach ( $modules as $module ) {
			// Bypass squid cache if the request includes any private modules
			if ( $module->getGroup() === 'private' ) {
				$smaxage = 0;
			}
			// Calculate maximum modified time
			$mtime = max( $mtime, $module->getModifiedTime( $context ) );
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

		echo $this->makeModuleResponse( $context, $modules, $missing );

		wfProfileOut( __METHOD__ );
	}

	public function makeModuleResponse( ResourceLoaderContext $context, array $modules, $missing = null ) {
		// Pre-fetch blobs
		$blobs = $context->shouldIncludeMessages() ?
			MessageBlobStore::get( $this, $modules, $context->getLanguage() ) : array();

		// Generate output
		$out = '';
		foreach ( $modules as $name => $module ) {
			wfProfileIn( __METHOD__ . '-' . $name );

			// Scripts
			$scripts = '';
			if ( $context->shouldIncludeScripts() ) {
				$scripts .= $module->getScript( $context ) . "\n";
			}

			// Styles
			$styles = array();
			if (
				$context->shouldIncludeStyles() &&
				( count( $styles = $module->getStyles( $context ) ) )
			) {
				// Flip CSS on a per-module basis
				if ( $this->modules[$name]->getFlip( $context ) ) {
					foreach ( $styles as $media => $style ) {
						$styles[$media] = $this->filter( 'flip-css', $style );
					}
				}
			}

			// Messages
			$messages = isset( $blobs[$name] ) ? $blobs[$name] : '{}';

			// Append output
			switch ( $context->getOnly() ) {
				case 'scripts':
					$out .= $scripts;
					break;
				case 'styles':
					$out .= self::makeCombinedStyles( $styles );
					break;
				case 'messages':
					$out .= self::makeMessageSetScript( $messages );
					break;
				default:
					// Minify CSS before embedding in mediaWiki.loader.implement call (unless in debug mode)
					if ( !$context->getDebug() ) {
						foreach ( $styles as $media => $style ) {
							$styles[$media] = $this->filter( 'minify-css', $style );
						}
					}
					$out .= self::makeLoaderImplementScript( $name, $scripts, $styles, $messages );
					break;
			}
			
			wfProfileOut( __METHOD__ . '-' . $name );
		}

		// Update module states
		if ( $context->shouldIncludeScripts() ) {
			// Set the state of modules loaded as only scripts to ready
			if ( count( $modules ) && $context->getOnly() === 'scripts' && !isset( $modules['startup'] ) ) {
				$out .= self::makeLoaderStateScript( array_fill_keys( array_keys( $modules ), 'ready' ) );
			}
			// Set the state of modules which were requested but unavailable as missing
			if ( is_array( $missing ) && count( $missing ) ) {
				$out .= self::makeLoaderStateScript( array_fill_keys( $missing, 'missing' ) );
			}
		}

		if ( $context->getDebug() ) {
			return $out;
		} else {
			if ( $context->getOnly() === 'styles' ) {
				return $this->filter( 'minify-css', $out );
			} else {
				return $this->filter( 'minify-js', $out );
			}
		}
	}
	
	/* Static Methods */
	
	public static function makeLoaderImplementScript( $name, $scripts, $styles, $messages ) {
		if ( is_array( $scripts ) ) {
			$scripts = implode( $scripts, "\n" );
		}
		if ( is_array( $styles ) ) {
			$styles = count( $styles ) ? FormatJson::encode( $styles ) : 'null';
		}
		if ( is_array( $messages ) ) {
			$messages = count( $messages ) ? FormatJson::encode( $messages ) : 'null';
		}
		return "mediaWiki.loader.implement( '$name', function() {{$scripts}},\n$styles,\n$messages );\n";
	}

	public static function makeMessageSetScript( $messages ) {
		if ( is_array( $messages ) ) {
			$messages = count( $messages ) ? FormatJson::encode( $messages ) : 'null';
		}
		return "mediaWiki.msg.set( $messages );\n";
	}

	public static function makeCombinedStyles( array $styles ) {
		$out = '';
		foreach ( $styles as $media => $style ) {
			$out .= "@media $media {\n" . str_replace( "\n", "\n\t", "\t" . $style ) . "\n}\n";
		}
		return $out;
	}

	public static function makeLoaderStateScript( $name, $state = null ) {
		if ( is_array( $name ) ) {
			$statuses = FormatJson::encode( $name );
			return "mediaWiki.loader.state( $statuses );\n";
		} else {
			$name = Xml::escapeJsString( $name );
			$state = Xml::escapeJsString( $state );
			return "mediaWiki.loader.state( '$name', '$state' );\n";
		}
	}

	public static function makeCustomLoaderScript( $name, $version, $dependencies, $group, $script ) {
		$name = Xml::escapeJsString( $name );
		$version = (int) $version > 1 ? (int) $version : 1;
		if ( is_array( $dependencies ) ) {
			$dependencies = FormatJson::encode( $dependencies );
		} else if ( is_string( $dependencies ) ) {
			$dependencies = "'" . Xml::escapeJsString( $dependencies ) . "'";
		} else {
			$dependencies = 'null';
		}
		if ( is_string( $group ) ) {
			$group = "'" . Xml::escapeJsString( $group ) . "'";
		} else {
			$group = 'null';
		}
		$script = str_replace( "\n", "\n\t", trim( $script ) );
		return "( function( name, version, dependencies, group ) {\n\t$script\n} )" .
			"( '$name', $version, $dependencies, $group );\n";
	}

	public static function makeLoaderRegisterScript( $name, $version = null, $dependencies = null, $group = null ) {
		if ( is_array( $name ) ) {
			$registrations = FormatJson::encode( $name );
			return "mediaWiki.loader.register( $registrations );\n";
		} else {
			$name = Xml::escapeJsString( $name );
			$version = (int) $version > 1 ? (int) $version : 1;
			if ( is_array( $dependencies ) ) {
				$dependencies = FormatJson::encode( $dependencies );
			} else if ( is_string( $dependencies ) ) {
				$dependencies = "'" . Xml::escapeJsString( $dependencies ) . "'";
			} else {
				$dependencies = 'null';
			}
			if ( is_string( $group ) ) {
				$group = "'" . Xml::escapeJsString( $group ) . "'";
			} else {
				$group = 'null';
			}
			return "mediaWiki.loader.register( '$name', $version, $dependencies, $group );\n";
		}
	}

	public static function makeLoaderConditionalScript( $script ) {
		$script = str_replace( "\n", "\n\t", trim( $script ) );
		return "if ( window.mediaWiki ) {\n\t$script\n}\n";
	}

	public static function makeConfigSetScript( array $configuration ) {
		$configuration = FormatJson::encode( $configuration );
		return "mediaWiki.config.set( $configuration );\n";
	}
}
