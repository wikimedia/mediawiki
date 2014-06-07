<?php
/**
 * Resource loader module based on local JavaScript/CSS files.
 *
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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * ResourceLoader module based on local JavaScript/CSS files.
 */
class ResourceLoaderFileModule extends ResourceLoaderModule {

	/* Protected Members */

	/** String: Local base path, see __construct() */
	protected $localBasePath = '';
	/** String: Remote base path, see __construct() */
	protected $remoteBasePath = '';
	/**
	 * Array: List of paths to JavaScript files to always include
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $scripts = array();
	/**
	 * Array: List of JavaScript files to include when using a specific language
	 * @par Usage:
	 * @code
	 * array( [language-code] => array( [file-path], [file-path], ... ), ... )
	 * @endcode
	 */
	protected $languageScripts = array();
	/**
	 * Array: List of JavaScript files to include when using a specific skin
	 * @par Usage:
	 * @code
	 * array( [skin-name] => array( [file-path], [file-path], ... ), ... )
	 * @endcode
	 */
	protected $skinScripts = array();
	/**
	 * Array: List of paths to JavaScript files to include in debug mode
	 * @par Usage:
	 * @code
	 * array( [skin-name] => array( [file-path], [file-path], ... ), ... )
	 * @endcode
	 */
	protected $debugScripts = array();
	/**
	 * Array: List of paths to JavaScript files to include in the startup module
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $loaderScripts = array();
	/**
	 * Array: List of paths to CSS files to always include
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $styles = array();
	/**
	 * Array: List of paths to CSS files to include when using specific skins
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $skinStyles = array();
	/**
	 * Array: List of modules this module depends on
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $dependencies = array();
	/**
	 * Array: List of message keys used by this module
	 * @par Usage:
	 * @code
	 * array( [message-key], [message-key], ... )
	 * @endcode
	 */
	protected $messages = array();
	/** String: Name of group to load this module in */
	protected $group;
	/** String: Position on the page to load this module at */
	protected $position = 'bottom';
	/** Boolean: Link to raw files in debug mode */
	protected $debugRaw = true;
	/** Boolean: Whether mw.loader.state() call should be omitted */
	protected $raw = false;
	protected $targets = array( 'desktop' );

	/**
	 * Boolean: Whether getStyleURLsForDebug should return raw file paths,
	 * or return load.php urls
	 */
	protected $hasGeneratedStyles = false;

	/**
	 * Array: Cache for mtime
	 * @par Usage:
	 * @code
	 * array( [hash] => [mtime], [hash] => [mtime], ... )
	 * @endcode
	 */
	protected $modifiedTime = array();
	/**
	 * Array: Place where readStyleFile() tracks file dependencies
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $localFileRefs = array();

	/* Methods */

	/**
	 * Constructs a new module from an options array.
	 *
	 * @param array $options List of options; if not given or empty, an empty module will be
	 *     constructed
	 * @param string $localBasePath Base path to prepend to all local paths in $options. Defaults
	 *     to $IP
	 * @param string $remoteBasePath Base path to prepend to all remote paths in $options. Defaults
	 *     to $wgScriptPath
	 *
	 * Below is a description for the $options array:
	 * @throws MWException
	 * @par Construction options:
	 * @code
	 *     array(
	 *         // Base path to prepend to all local paths in $options. Defaults to $IP
	 *         'localBasePath' => [base path],
	 *         // Base path to prepend to all remote paths in $options. Defaults to $wgScriptPath
	 *         'remoteBasePath' => [base path],
	 *         // Equivalent of remoteBasePath, but relative to $wgExtensionAssetsPath
	 *         'remoteExtPath' => [base path],
	 *         // Scripts to always include
	 *         'scripts' => [file path string or array of file path strings],
	 *         // Scripts to include in specific language contexts
	 *         'languageScripts' => array(
	 *             [language code] => [file path string or array of file path strings],
	 *         ),
	 *         // Scripts to include in specific skin contexts
	 *         'skinScripts' => array(
	 *             [skin name] => [file path string or array of file path strings],
	 *         ),
	 *         // Scripts to include in debug contexts
	 *         'debugScripts' => [file path string or array of file path strings],
	 *         // Scripts to include in the startup module
	 *         'loaderScripts' => [file path string or array of file path strings],
	 *         // Modules which must be loaded before this module
	 *         'dependencies' => [module name string or array of module name strings],
	 *         // Styles to always load
	 *         'styles' => [file path string or array of file path strings],
	 *         // Styles to include in specific skin contexts
	 *         'skinStyles' => array(
	 *             [skin name] => [file path string or array of file path strings],
	 *         ),
	 *         // Messages to always load
	 *         'messages' => [array of message key strings],
	 *         // Group which this module should be loaded together with
	 *         'group' => [group name string],
	 *         // Position on the page to load this module at
	 *         'position' => ['bottom' (default) or 'top']
	 *     )
	 * @endcode
	 */
	public function __construct( $options = array(), $localBasePath = null,
		$remoteBasePath = null
	) {
		global $IP, $wgScriptPath, $wgResourceBasePath;
		$this->localBasePath = $localBasePath === null ? $IP : $localBasePath;
		if ( $remoteBasePath !== null ) {
			$this->remoteBasePath = $remoteBasePath;
		} else {
			$this->remoteBasePath = $wgResourceBasePath === null ? $wgScriptPath : $wgResourceBasePath;
		}

		if ( isset( $options['remoteExtPath'] ) ) {
			global $wgExtensionAssetsPath;
			$this->remoteBasePath = $wgExtensionAssetsPath . '/' . $options['remoteExtPath'];
		}

		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				// Lists of file paths
				case 'scripts':
				case 'debugScripts':
				case 'loaderScripts':
				case 'styles':
					$this->{$member} = (array)$option;
					break;
				// Collated lists of file paths
				case 'languageScripts':
				case 'skinScripts':
				case 'skinStyles':
					if ( !is_array( $option ) ) {
						throw new MWException(
							"Invalid collated file path list error. " .
							"'$option' given, array expected."
						);
					}
					foreach ( $option as $key => $value ) {
						if ( !is_string( $key ) ) {
							throw new MWException(
								"Invalid collated file path list key error. " .
								"'$key' given, string expected."
							);
						}
						$this->{$member}[$key] = (array)$value;
					}
					break;
				// Lists of strings
				case 'dependencies':
				case 'messages':
				case 'targets':
					// Normalise
					$option = array_values( array_unique( (array)$option ) );
					sort( $option );

					$this->{$member} = $option;
					break;
				// Single strings
				case 'group':
				case 'position':
				case 'localBasePath':
				case 'remoteBasePath':
					$this->{$member} = (string)$option;
					break;
				// Single booleans
				case 'debugRaw':
				case 'raw':
					$this->{$member} = (bool)$option;
					break;
			}
		}
		// Make sure the remote base path is a complete valid URL,
		// but possibly protocol-relative to avoid cache pollution
		$this->remoteBasePath = wfExpandUrl( $this->remoteBasePath, PROTO_RELATIVE );
	}

	/**
	 * Gets all scripts for a given context concatenated together.
	 *
	 * @param ResourceLoaderContext $context Context in which to generate script
	 * @return string: JavaScript code for $context
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$files = $this->getScriptFiles( $context );
		return $this->readScriptFiles( $files );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getScriptURLsForDebug( ResourceLoaderContext $context ) {
		$urls = array();
		foreach ( $this->getScriptFiles( $context ) as $file ) {
			$urls[] = $this->getRemotePath( $file );
		}
		return $urls;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return $this->debugRaw;
	}

	/**
	 * Gets loader script.
	 *
	 * @return string: JavaScript code to be added to startup module
	 */
	public function getLoaderScript() {
		if ( count( $this->loaderScripts ) == 0 ) {
			return false;
		}
		return $this->readScriptFiles( $this->loaderScripts );
	}

	/**
	 * Gets all styles for a given context concatenated together.
	 *
	 * @param ResourceLoaderContext $context Context in which to generate styles
	 * @return string: CSS code for $context
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$styles = $this->readStyleFiles(
			$this->getStyleFiles( $context ),
			$this->getFlip( $context )
		);
		// Collect referenced files
		$this->localFileRefs = array_unique( $this->localFileRefs );
		// If the list has been modified since last time we cached it, update the cache
		try {
			if ( $this->localFileRefs !== $this->getFileDependencies( $context->getSkin() ) ) {
				$dbw = wfGetDB( DB_MASTER );
				$dbw->replace( 'module_deps',
					array( array( 'md_module', 'md_skin' ) ), array(
						'md_module' => $this->getName(),
						'md_skin' => $context->getSkin(),
						'md_deps' => FormatJson::encode( $this->localFileRefs ),
					)
				);
			}
		} catch ( Exception $e ) {
			wfDebugLog( 'resourceloader', __METHOD__ . ": failed to update DB: $e" );
		}
		return $styles;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyleURLsForDebug( ResourceLoaderContext $context ) {
		if ( $this->hasGeneratedStyles ) {
			// Do the default behaviour of returning a url back to load.php
			// but with only=styles.
			return parent::getStyleURLsForDebug( $context );
		}
		// Our module consists entirely of real css files,
		// in debug mode we can load those directly.
		$urls = array();
		foreach ( $this->getStyleFiles( $context ) as $mediaType => $list ) {
			$urls[$mediaType] = array();
			foreach ( $list as $file ) {
				$urls[$mediaType][] = $this->getRemotePath( $file );
			}
		}
		return $urls;
	}

	/**
	 * Gets list of message keys used by this module.
	 *
	 * @return array: List of message keys
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * Gets the name of the group this module should be loaded in.
	 *
	 * @return string: Group name
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @return string
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * Gets list of names of modules this module depends on.
	 *
	 * @return array: List of module names
	 */
	public function getDependencies() {
		return $this->dependencies;
	}

	/**
	 * @return bool
	 */
	public function isRaw() {
		return $this->raw;
	}

	/**
	 * Get the last modified timestamp of this module.
	 *
	 * Last modified timestamps are calculated from the highest last modified
	 * timestamp of this module's constituent files as well as the files it
	 * depends on. This function is context-sensitive, only performing
	 * calculations on files relevant to the given language, skin and debug
	 * mode.
	 *
	 * @param ResourceLoaderContext $context Context in which to calculate
	 *     the modified time
	 * @return int: UNIX timestamp
	 * @see ResourceLoaderModule::getFileDependencies
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		if ( isset( $this->modifiedTime[$context->getHash()] ) ) {
			return $this->modifiedTime[$context->getHash()];
		}
		wfProfileIn( __METHOD__ );

		$files = array();

		// Flatten style files into $files
		$styles = self::collateFilePathListByOption( $this->styles, 'media', 'all' );
		foreach ( $styles as $styleFiles ) {
			$files = array_merge( $files, $styleFiles );
		}

		$skinFiles = self::collateFilePathListByOption(
			self::tryForKey( $this->skinStyles, $context->getSkin(), 'default' ),
			'media',
			'all'
		);
		foreach ( $skinFiles as $styleFiles ) {
			$files = array_merge( $files, $styleFiles );
		}

		// Final merge, this should result in a master list of dependent files
		$files = array_merge(
			$files,
			$this->scripts,
			$context->getDebug() ? $this->debugScripts : array(),
			self::tryForKey( $this->languageScripts, $context->getLanguage() ),
			self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' ),
			$this->loaderScripts
		);
		$files = array_map( array( $this, 'getLocalPath' ), $files );
		// File deps need to be treated separately because they're already prefixed
		$files = array_merge( $files, $this->getFileDependencies( $context->getSkin() ) );

		// If a module is nothing but a list of dependencies, we need to avoid
		// giving max() an empty array
		if ( count( $files ) === 0 ) {
			$this->modifiedTime[$context->getHash()] = 1;
			wfProfileOut( __METHOD__ );
			return $this->modifiedTime[$context->getHash()];
		}

		wfProfileIn( __METHOD__ . '-filemtime' );
		$filesMtime = max( array_map( array( __CLASS__, 'safeFilemtime' ), $files ) );
		wfProfileOut( __METHOD__ . '-filemtime' );

		$this->modifiedTime[$context->getHash()] = max(
			$filesMtime,
			$this->getMsgBlobMtime( $context->getLanguage() ),
			$this->getDefinitionMtime( $context )
		);

		wfProfileOut( __METHOD__ );
		return $this->modifiedTime[$context->getHash()];
	}

	/**
	 * Get the definition summary for this module.
	 *
	 * @return Array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = array(
			'class' => get_class( $this ),
		);
		foreach ( array(
			'scripts',
			'debugScripts',
			'loaderScripts',
			'styles',
			'languageScripts',
			'skinScripts',
			'skinStyles',
			'dependencies',
			'messages',
			'targets',
			'group',
			'position',
			'localBasePath',
			'remoteBasePath',
			'debugRaw',
			'raw',
		) as $member ) {
			$summary[$member] = $this->{$member};
		};
		return $summary;
	}

	/* Protected Methods */

	/**
	 * @param string $path
	 * @return string
	 */
	protected function getLocalPath( $path ) {
		return "{$this->localBasePath}/$path";
	}

	/**
	 * @param string $path
	 * @return string
	 */
	protected function getRemotePath( $path ) {
		return "{$this->remoteBasePath}/$path";
	}

	/**
	 * Infer the stylesheet language from a stylesheet file path.
	 *
	 * @since 1.22
	 * @param string $path
	 * @return string: the stylesheet language name
	 */
	public function getStyleSheetLang( $path ) {
		return preg_match( '/\.less$/i', $path ) ? 'less' : 'css';
	}

	/**
	 * Collates file paths by option (where provided).
	 *
	 * @param array $list List of file paths in any combination of index/path
	 *     or path/options pairs
	 * @param string $option option name
	 * @param mixed $default default value if the option isn't set
	 * @return array: List of file paths, collated by $option
	 */
	protected static function collateFilePathListByOption( array $list, $option, $default ) {
		$collatedFiles = array();
		foreach ( (array)$list as $key => $value ) {
			if ( is_int( $key ) ) {
				// File name as the value
				if ( !isset( $collatedFiles[$default] ) ) {
					$collatedFiles[$default] = array();
				}
				$collatedFiles[$default][] = $value;
			} elseif ( is_array( $value ) ) {
				// File name as the key, options array as the value
				$optionValue = isset( $value[$option] ) ? $value[$option] : $default;
				if ( !isset( $collatedFiles[$optionValue] ) ) {
					$collatedFiles[$optionValue] = array();
				}
				$collatedFiles[$optionValue][] = $key;
			}
		}
		return $collatedFiles;
	}

	/**
	 * Gets a list of element that match a key, optionally using a fallback key.
	 *
	 * @param array $list List of lists to select from
	 * @param string $key Key to look for in $map
	 * @param string $fallback Key to look for in $list if $key doesn't exist
	 * @return array: List of elements from $map which matched $key or $fallback,
	 *     or an empty list in case of no match
	 */
	protected static function tryForKey( array $list, $key, $fallback = null ) {
		if ( isset( $list[$key] ) && is_array( $list[$key] ) ) {
			return $list[$key];
		} elseif ( is_string( $fallback )
			&& isset( $list[$fallback] )
			&& is_array( $list[$fallback] )
		) {
			return $list[$fallback];
		}
		return array();
	}

	/**
	 * Gets a list of file paths for all scripts in this module, in order of propper execution.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array: List of file paths
	 */
	protected function getScriptFiles( ResourceLoaderContext $context ) {
		$files = array_merge(
			$this->scripts,
			self::tryForKey( $this->languageScripts, $context->getLanguage() ),
			self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' )
		);
		if ( $context->getDebug() ) {
			$files = array_merge( $files, $this->debugScripts );
		}

		return array_unique( $files );
	}

	/**
	 * Gets a list of file paths for all styles in this module, in order of propper inclusion.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array: List of file paths
	 */
	protected function getStyleFiles( ResourceLoaderContext $context ) {
		return array_merge_recursive(
			self::collateFilePathListByOption( $this->styles, 'media', 'all' ),
			self::collateFilePathListByOption(
				self::tryForKey( $this->skinStyles, $context->getSkin(), 'default' ),
				'media',
				'all'
			)
		);
	}

	/**
	 * Returns all style files used by this module
	 * @return array
	 */
	public function getAllStyleFiles() {
		$files = array();
		foreach ( (array)$this->styles as $key => $value ) {
			if ( is_array( $value ) ) {
				$path = $key;
			} else {
				$path = $value;
			}
			$files[] = $this->getLocalPath( $path );
		}
		return $files;
	}

	/**
	 * Gets the contents of a list of JavaScript files.
	 *
	 * @param array $scripts List of file paths to scripts to read, remap and concetenate
	 * @throws MWException
	 * @return string: Concatenated and remapped JavaScript data from $scripts
	 */
	protected function readScriptFiles( array $scripts ) {
		global $wgResourceLoaderValidateStaticJS;
		if ( empty( $scripts ) ) {
			return '';
		}
		$js = '';
		foreach ( array_unique( $scripts ) as $fileName ) {
			$localPath = $this->getLocalPath( $fileName );
			if ( !file_exists( $localPath ) ) {
				throw new MWException( __METHOD__ . ": script file not found: \"$localPath\"" );
			}
			$contents = file_get_contents( $localPath );
			if ( $wgResourceLoaderValidateStaticJS ) {
				// Static files don't really need to be checked as often; unlike
				// on-wiki module they shouldn't change unexpectedly without
				// admin interference.
				$contents = $this->validateScriptFile( $fileName, $contents );
			}
			$js .= $contents . "\n";
		}
		return $js;
	}

	/**
	 * Gets the contents of a list of CSS files.
	 *
	 * @param array $styles List of media type/list of file paths pairs, to read, remap and
	 * concetenate
	 *
	 * @param bool $flip
	 *
	 * @throws MWException
	 * @return array: List of concatenated and remapped CSS data from $styles,
	 *     keyed by media type
	 */
	protected function readStyleFiles( array $styles, $flip ) {
		if ( empty( $styles ) ) {
			return array();
		}
		foreach ( $styles as $media => $files ) {
			$uniqueFiles = array_unique( $files );
			$styleFiles = array();
			foreach ( $uniqueFiles as $file ) {
				$styleFiles[] = $this->readStyleFile( $file, $flip );
			}
			$styles[$media] = implode( "\n", $styleFiles );
		}
		return $styles;
	}

	/**
	 * Reads a style file.
	 *
	 * This method can be used as a callback for array_map()
	 *
	 * @param string $path File path of style file to read
	 * @param bool $flip
	 *
	 * @return string: CSS data in script file
	 * @throws MWException if the file doesn't exist
	 */
	protected function readStyleFile( $path, $flip ) {
		$localPath = $this->getLocalPath( $path );
		if ( !file_exists( $localPath ) ) {
			$msg = __METHOD__ . ": style file not found: \"$localPath\"";
			wfDebugLog( 'resourceloader', $msg );
			throw new MWException( $msg );
		}

		if ( $this->getStyleSheetLang( $path ) === 'less' ) {
			$style = $this->compileLESSFile( $localPath );
			$this->hasGeneratedStyles = true;
		} else {
			$style = file_get_contents( $localPath );
		}

		if ( $flip ) {
			$style = CSSJanus::transform( $style, true, false );
		}
		$dirname = dirname( $path );
		if ( $dirname == '.' ) {
			// If $path doesn't have a directory component, don't prepend a dot
			$dirname = '';
		}
		$dir = $this->getLocalPath( $dirname );
		$remoteDir = $this->getRemotePath( $dirname );
		// Get and register local file references
		$this->localFileRefs = array_merge(
			$this->localFileRefs,
			CSSMin::getLocalFileReferences( $style, $dir )
		);
		return CSSMin::remap(
			$style, $dir, $remoteDir, true
		);
	}

	/**
	 * Get whether CSS for this module should be flipped
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function getFlip( $context ) {
		return $context->getDirection() === 'rtl';
	}

	/**
	 * Get target(s) for the module, eg ['desktop'] or ['desktop', 'mobile']
	 *
	 * @return array of strings
	 */
	public function getTargets() {
		return $this->targets;
	}

	/**
	 * Generate a cache key for a LESS file.
	 *
	 * The cache key varies on the file name and the names and values of global
	 * LESS variables.
	 *
	 * @since 1.22
	 * @param string $fileName File name of root LESS file.
	 * @return string: Cache key
	 */
	protected static function getLESSCacheKey( $fileName ) {
		$vars = json_encode( ResourceLoader::getLESSVars() );
		$hash = md5( $fileName . $vars );
		return wfMemcKey( 'resourceloader', 'less', $hash );
	}

	/**
	 * Compile a LESS file into CSS.
	 *
	 * If invalid, returns replacement CSS source consisting of the compilation
	 * error message encoded as a comment. To save work, we cache a result object
	 * which comprises the compiled CSS and the names & mtimes of the files
	 * that were processed. lessphp compares the cached & current mtimes and
	 * recompiles as necessary.
	 *
	 * @since 1.22
	 * @throws Exception If Less encounters a parse error
	 * @throws MWException If Less compilation returns unexpection result
	 * @param string $fileName File path of LESS source
	 * @return string: CSS source
	 */
	protected function compileLESSFile( $fileName ) {
		$key = self::getLESSCacheKey( $fileName );
		$cache = wfGetCache( CACHE_ANYTHING );

		// The input to lessc. Either an associative array representing the
		// cached results of a previous compilation, or the string file name if
		// no cache result exists.
		$source = $cache->get( $key );
		if ( !is_array( $source ) || !isset( $source['root'] ) ) {
			$source = $fileName;
		}

		$compiler = ResourceLoader::getLessCompiler();
		$result = null;

		$result = $compiler->cachedCompile( $source );

		if ( !is_array( $result ) ) {
			throw new MWException( 'LESS compiler result has type ' . gettype( $result ) . '; array expected.' );
		}

		$this->localFileRefs += array_keys( $result['files'] );
		$cache->set( $key, $result );
		return $result['compiled'];
	}
}
