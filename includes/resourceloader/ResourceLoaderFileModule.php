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

	/** @var string Local base path, see __construct() */
	protected $localBasePath = '';

	/** @var string Remote base path, see __construct() */
	protected $remoteBasePath = '';

	/** @var array Saves a list of the templates named by the modules. */
	protected $templates = array();

	/**
	 * @var array List of paths to JavaScript files to always include
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $scripts = array();

	/**
	 * @var array List of JavaScript files to include when using a specific language
	 * @par Usage:
	 * @code
	 * array( [language-code] => array( [file-path], [file-path], ... ), ... )
	 * @endcode
	 */
	protected $languageScripts = array();

	/**
	 * @var array List of JavaScript files to include when using a specific skin
	 * @par Usage:
	 * @code
	 * array( [skin-name] => array( [file-path], [file-path], ... ), ... )
	 * @endcode
	 */
	protected $skinScripts = array();

	/**
	 * @var array List of paths to JavaScript files to include in debug mode
	 * @par Usage:
	 * @code
	 * array( [skin-name] => array( [file-path], [file-path], ... ), ... )
	 * @endcode
	 */
	protected $debugScripts = array();

	/**
	 * @var array List of paths to JavaScript files to include in the startup module
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $loaderScripts = array();

	/**
	 * @var array List of paths to CSS files to always include
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $styles = array();

	/**
	 * @var array List of paths to CSS files to include when using specific skins
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $skinStyles = array();

	/**
	 * @var array List of modules this module depends on
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $dependencies = array();

	/**
	 * @var string File name containing the body of the skip function
	 */
	protected $skipFunction = null;

	/**
	 * @var array List of message keys used by this module
	 * @par Usage:
	 * @code
	 * array( [message-key], [message-key], ... )
	 * @endcode
	 */
	protected $messages = array();

	/** @var string Name of group to load this module in */
	protected $group;

	/** @var string Position on the page to load this module at */
	protected $position = 'bottom';

	/** @var bool Link to raw files in debug mode */
	protected $debugRaw = true;

	/** @var bool Whether mw.loader.state() call should be omitted */
	protected $raw = false;

	protected $targets = array( 'desktop' );

	/**
	 * @var bool Whether getStyleURLsForDebug should return raw file paths,
	 * or return load.php urls
	 */
	protected $hasGeneratedStyles = false;

	/**
	 * @var array Place where readStyleFile() tracks file dependencies
	 * @par Usage:
	 * @code
	 * array( [file-path], [file-path], ... )
	 * @endcode
	 */
	protected $localFileRefs = array();

	/**
	 * @var array Place where readStyleFile() tracks file dependencies for non-existent files.
	 * Used in tests to detect missing dependencies.
	 */
	protected $missingLocalFileRefs = array();

	/* Methods */

	/**
	 * Constructs a new module from an options array.
	 *
	 * @param array $options List of options; if not given or empty, an empty module will be
	 *     constructed
	 * @param string $localBasePath Base path to prepend to all local paths in $options. Defaults
	 *     to $IP
	 * @param string $remoteBasePath Base path to prepend to all remote paths in $options. Defaults
	 *     to $wgResourceBasePath
	 *
	 * Below is a description for the $options array:
	 * @throws InvalidArgumentException
	 * @par Construction options:
	 * @code
	 *     array(
	 *         // Base path to prepend to all local paths in $options. Defaults to $IP
	 *         'localBasePath' => [base path],
	 *         // Base path to prepend to all remote paths in $options. Defaults to $wgResourceBasePath
	 *         'remoteBasePath' => [base path],
	 *         // Equivalent of remoteBasePath, but relative to $wgExtensionAssetsPath
	 *         'remoteExtPath' => [base path],
	 *         // Equivalent of remoteBasePath, but relative to $wgStylePath
	 *         'remoteSkinPath' => [base path],
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
	 *         'templates' => array(
	 *             [template alias with file.ext] => [file path to a template file],
	 *         ),
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
	 *         // Function that, if it returns true, makes the loader skip this module.
	 *         // The file must contain valid JavaScript for execution in a private function.
	 *         // The file must not contain the "function () {" and "}" wrapper though.
	 *         'skipFunction' => [file path]
	 *     )
	 * @endcode
	 */
	public function __construct(
		$options = array(),
		$localBasePath = null,
		$remoteBasePath = null
	) {
		// Flag to decide whether to automagically add the mediawiki.template module
		$hasTemplates = false;
		// localBasePath and remoteBasePath both have unbelievably long fallback chains
		// and need to be handled separately.
		list( $this->localBasePath, $this->remoteBasePath ) =
			self::extractBasePaths( $options, $localBasePath, $remoteBasePath );

		// Extract, validate and normalise remaining options
		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				// Lists of file paths
				case 'scripts':
				case 'debugScripts':
				case 'loaderScripts':
				case 'styles':
					$this->{$member} = (array)$option;
					break;
				case 'templates':
					$hasTemplates = true;
					$this->{$member} = (array)$option;
					break;
				// Collated lists of file paths
				case 'languageScripts':
				case 'skinScripts':
				case 'skinStyles':
					if ( !is_array( $option ) ) {
						throw new InvalidArgumentException(
							"Invalid collated file path list error. " .
							"'$option' given, array expected."
						);
					}
					foreach ( $option as $key => $value ) {
						if ( !is_string( $key ) ) {
							throw new InvalidArgumentException(
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
				case 'position':
					$this->isPositionDefined = true;
				case 'group':
				case 'skipFunction':
					$this->{$member} = (string)$option;
					break;
				// Single booleans
				case 'debugRaw':
				case 'raw':
					$this->{$member} = (bool)$option;
					break;
			}
		}
		if ( $hasTemplates ) {
			$this->dependencies[] = 'mediawiki.template';
			// Ensure relevant template compiler module gets loaded
			foreach ( $this->templates as $alias => $templatePath ) {
				if ( is_int( $alias ) ) {
					$alias = $templatePath;
				}
				$suffix = explode( '.', $alias );
				$suffix = end( $suffix );
				$compilerModule = 'mediawiki.template.' . $suffix;
				if ( $suffix !== 'html' && !in_array( $compilerModule, $this->dependencies ) ) {
					$this->dependencies[] = $compilerModule;
				}
			}
		}
	}

	/**
	 * Extract a pair of local and remote base paths from module definition information.
	 * Implementation note: the amount of global state used in this function is staggering.
	 *
	 * @param array $options Module definition
	 * @param string $localBasePath Path to use if not provided in module definition. Defaults
	 *     to $IP
	 * @param string $remoteBasePath Path to use if not provided in module definition. Defaults
	 *     to $wgResourceBasePath
	 * @return array Array( localBasePath, remoteBasePath )
	 */
	public static function extractBasePaths(
		$options = array(),
		$localBasePath = null,
		$remoteBasePath = null
	) {
		global $IP, $wgResourceBasePath;

		// The different ways these checks are done, and their ordering, look very silly,
		// but were preserved for backwards-compatibility just in case. Tread lightly.

		if ( $localBasePath === null ) {
			$localBasePath = $IP;
		}
		if ( $remoteBasePath === null ) {
			$remoteBasePath = $wgResourceBasePath;
		}

		if ( isset( $options['remoteExtPath'] ) ) {
			global $wgExtensionAssetsPath;
			$remoteBasePath = $wgExtensionAssetsPath . '/' . $options['remoteExtPath'];
		}

		if ( isset( $options['remoteSkinPath'] ) ) {
			global $wgStylePath;
			$remoteBasePath = $wgStylePath . '/' . $options['remoteSkinPath'];
		}

		if ( array_key_exists( 'localBasePath', $options ) ) {
			$localBasePath = (string)$options['localBasePath'];
		}

		if ( array_key_exists( 'remoteBasePath', $options ) ) {
			$remoteBasePath = (string)$options['remoteBasePath'];
		}

		// Make sure the remote base path is a complete valid URL,
		// but possibly protocol-relative to avoid cache pollution
		$remoteBasePath = wfExpandUrl( $remoteBasePath, PROTO_RELATIVE );

		return array( $localBasePath, $remoteBasePath );
	}

	/**
	 * Gets all scripts for a given context concatenated together.
	 *
	 * @param ResourceLoaderContext $context Context in which to generate script
	 * @return string JavaScript code for $context
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
	 * Get loader script.
	 *
	 * @return string|bool JavaScript code to be added to startup module
	 */
	public function getLoaderScript() {
		if ( count( $this->loaderScripts ) === 0 ) {
			return false;
		}
		return $this->readScriptFiles( $this->loaderScripts );
	}

	/**
	 * Get all styles for a given context.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array CSS code for $context as an associative array mapping media type to CSS text.
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$styles = $this->readStyleFiles(
			$this->getStyleFiles( $context ),
			$this->getFlip( $context ),
			$context
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
	 * @return array List of message keys
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * Gets the name of the group this module should be loaded in.
	 *
	 * @return string Group name
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
	 * @param ResourceLoaderContext context
	 * @return array List of module names
	 */
	public function getDependencies( ResourceLoaderContext $context = null ) {
		return $this->dependencies;
	}

	/**
	 * Get the skip function.
	 * @return null|string
	 * @throws MWException
	 */
	public function getSkipFunction() {
		if ( !$this->skipFunction ) {
			return null;
		}

		$localPath = $this->getLocalPath( $this->skipFunction );
		if ( !file_exists( $localPath ) ) {
			throw new MWException( __METHOD__ . ": skip function file not found: \"$localPath\"" );
		}
		$contents = file_get_contents( $localPath );
		if ( $this->getConfig()->get( 'ResourceLoaderValidateStaticJS' ) ) {
			$contents = $this->validateScriptFile( $localPath, $contents );
		}
		return $contents;
	}

	/**
	 * @return bool
	 */
	public function isRaw() {
		return $this->raw;
	}

	/**
	 * Disable module content versioning.
	 *
	 * This class uses getDefinitionSummary() instead, to avoid filesystem overhead
	 * involved with building the full module content inside a startup request.
	 *
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return false;
	}

	/**
	 * Helper method to gather file hashes for getDefinitionSummary.
	 *
	 * This function is context-sensitive, only computing hashes of files relevant to the
	 * given language, skin, etc.
	 *
	 * @see ResourceLoaderModule::getFileDependencies
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getFileHashes( ResourceLoaderContext $context ) {
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
			$this->templates,
			$context->getDebug() ? $this->debugScripts : array(),
			$this->getLanguageScripts( $context->getLanguage() ),
			self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' ),
			$this->loaderScripts
		);
		if ( $this->skipFunction ) {
			$files[] = $this->skipFunction;
		}
		$files = array_map( array( $this, 'getLocalPath' ), $files );
		// File deps need to be treated separately because they're already prefixed
		$files = array_merge( $files, $this->getFileDependencies( $context->getSkin() ) );
		// Filter out any duplicates from getFileDependencies() and others.
		// Most commonly introduced by compileLessFile(), which always includes the
		// entry point Less file we already know about.
		$files = array_values( array_unique( $files ) );

		// Don't include keys or file paths here, only the hashes. Including that would needlessly
		// cause global cache invalidation when files move or if e.g. the MediaWiki path changes.
		// Any significant ordering is already detected by the definition summary.
		return array_map( array( __CLASS__, 'safeFileHash' ), $files );
	}

	/**
	 * Get the definition summary for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = parent::getDefinitionSummary( $context );

		$options = array();
		foreach ( array(
			// The following properties are omitted because they don't affect the module reponse:
			// - localBasePath (Per T104950; Changes when absolute directory name changes. If
			//    this affects 'scripts' and other file paths, getFileHashes accounts for that.)
			// - remoteBasePath (Per T104950)
			// - dependencies (provided via startup module)
			// - targets
			// - group (provided via startup module)
			// - position (only used by OutputPage)
			'scripts',
			'debugScripts',
			'loaderScripts',
			'styles',
			'languageScripts',
			'skinScripts',
			'skinStyles',
			'messages',
			'templates',
			'skipFunction',
			'debugRaw',
			'raw',
		) as $member ) {
			$options[$member] = $this->{$member};
		};

		$summary[] = array(
			'options' => $options,
			'fileHashes' => $this->getFileHashes( $context ),
			'msgBlobMtime' => $this->getMsgBlobMtime( $context->getLanguage() ),
		);
		return $summary;
	}

	/**
	 * @param string|ResourceLoaderFilePath $path
	 * @return string
	 */
	protected function getLocalPath( $path ) {
		if ( $path instanceof ResourceLoaderFilePath ) {
			return $path->getLocalPath();
		}

		return "{$this->localBasePath}/$path";
	}

	/**
	 * @param string|ResourceLoaderFilePath $path
	 * @return string
	 */
	protected function getRemotePath( $path ) {
		if ( $path instanceof ResourceLoaderFilePath ) {
			return $path->getRemotePath();
		}

		return "{$this->remoteBasePath}/$path";
	}

	/**
	 * Infer the stylesheet language from a stylesheet file path.
	 *
	 * @since 1.22
	 * @param string $path
	 * @return string The stylesheet language name
	 */
	public function getStyleSheetLang( $path ) {
		return preg_match( '/\.less$/i', $path ) ? 'less' : 'css';
	}

	/**
	 * Collates file paths by option (where provided).
	 *
	 * @param array $list List of file paths in any combination of index/path
	 *     or path/options pairs
	 * @param string $option Option name
	 * @param mixed $default Default value if the option isn't set
	 * @return array List of file paths, collated by $option
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
	 * Get a list of element that match a key, optionally using a fallback key.
	 *
	 * @param array $list List of lists to select from
	 * @param string $key Key to look for in $map
	 * @param string $fallback Key to look for in $list if $key doesn't exist
	 * @return array List of elements from $map which matched $key or $fallback,
	 *  or an empty list in case of no match
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
	 * Get a list of file paths for all scripts in this module, in order of proper execution.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array List of file paths
	 */
	protected function getScriptFiles( ResourceLoaderContext $context ) {
		$files = array_merge(
			$this->scripts,
			$this->getLanguageScripts( $context->getLanguage() ),
			self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' )
		);
		if ( $context->getDebug() ) {
			$files = array_merge( $files, $this->debugScripts );
		}

		return array_unique( $files, SORT_REGULAR );
	}

	/**
	 * Get the set of language scripts for the given language,
	 * possibly using a fallback language.
	 *
	 * @param string $lang
	 * @return array
	 */
	private function getLanguageScripts( $lang ) {
		$scripts = self::tryForKey( $this->languageScripts, $lang );
		if ( $scripts ) {
			return $scripts;
		}
		$fallbacks = Language::getFallbacksFor( $lang );
		foreach ( $fallbacks as $lang ) {
			$scripts = self::tryForKey( $this->languageScripts, $lang );
			if ( $scripts ) {
				return $scripts;
			}
		}

		return array();
	}

	/**
	 * Get a list of file paths for all styles in this module, in order of proper inclusion.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array List of file paths
	 */
	public function getStyleFiles( ResourceLoaderContext $context ) {
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
	 * Gets a list of file paths for all skin styles in the module used by
	 * the skin.
	 *
	 * @param string $skinName The name of the skin
	 * @return array A list of file paths collated by media type
	 */
	protected function getSkinStyleFiles( $skinName ) {
		return self::collateFilePathListByOption(
			self::tryForKey( $this->skinStyles, $skinName ),
			'media',
			'all'
		);
	}

	/**
	 * Gets a list of file paths for all skin style files in the module,
	 * for all available skins.
	 *
	 * @return array A list of file paths collated by media type
	 */
	protected function getAllSkinStyleFiles() {
		$styleFiles = array();
		$internalSkinNames = array_keys( Skin::getSkinNames() );
		$internalSkinNames[] = 'default';

		foreach ( $internalSkinNames as $internalSkinName ) {
			$styleFiles = array_merge_recursive(
				$styleFiles,
				$this->getSkinStyleFiles( $internalSkinName )
			);
		}

		return $styleFiles;
	}

	/**
	 * Returns all style files and all skin style files used by this module.
	 *
	 * @return array
	 */
	public function getAllStyleFiles() {
		$collatedStyleFiles = array_merge_recursive(
			self::collateFilePathListByOption( $this->styles, 'media', 'all' ),
			$this->getAllSkinStyleFiles()
		);

		$result = array();

		foreach ( $collatedStyleFiles as $media => $styleFiles ) {
			foreach ( $styleFiles as $styleFile ) {
				$result[] = $this->getLocalPath( $styleFile );
			}
		}

		return $result;
	}

	/**
	 * Gets the contents of a list of JavaScript files.
	 *
	 * @param array $scripts List of file paths to scripts to read, remap and concetenate
	 * @throws MWException
	 * @return string Concatenated and remapped JavaScript data from $scripts
	 */
	protected function readScriptFiles( array $scripts ) {
		if ( empty( $scripts ) ) {
			return '';
		}
		$js = '';
		foreach ( array_unique( $scripts, SORT_REGULAR ) as $fileName ) {
			$localPath = $this->getLocalPath( $fileName );
			if ( !file_exists( $localPath ) ) {
				throw new MWException( __METHOD__ . ": script file not found: \"$localPath\"" );
			}
			$contents = file_get_contents( $localPath );
			if ( $this->getConfig()->get( 'ResourceLoaderValidateStaticJS' ) ) {
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
	 * @param bool $flip
	 * @param ResourceLoaderContext $context (optional)
	 *
	 * @throws MWException
	 * @return array List of concatenated and remapped CSS data from $styles,
	 *     keyed by media type
	 */
	public function readStyleFiles( array $styles, $flip, $context = null ) {
		if ( empty( $styles ) ) {
			return array();
		}
		foreach ( $styles as $media => $files ) {
			$uniqueFiles = array_unique( $files, SORT_REGULAR );
			$styleFiles = array();
			foreach ( $uniqueFiles as $file ) {
				$styleFiles[] = $this->readStyleFile( $file, $flip, $context );
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
	 * @param ResourceLoaderContext $context (optional)
	 *
	 * @return string CSS data in script file
	 * @throws MWException If the file doesn't exist
	 */
	protected function readStyleFile( $path, $flip, $context = null ) {
		$localPath = $this->getLocalPath( $path );
		$remotePath = $this->getRemotePath( $path );
		if ( !file_exists( $localPath ) ) {
			$msg = __METHOD__ . ": style file not found: \"$localPath\"";
			wfDebugLog( 'resourceloader', $msg );
			throw new MWException( $msg );
		}

		if ( $this->getStyleSheetLang( $localPath ) === 'less' ) {
			$compiler = $this->getLessCompiler( $context );
			$style = $this->compileLessFile( $localPath, $compiler );
			$this->hasGeneratedStyles = true;
		} else {
			$style = file_get_contents( $localPath );
		}

		if ( $flip ) {
			$style = CSSJanus::transform( $style, true, false );
		}
		$localDir = dirname( $localPath );
		$remoteDir = dirname( $remotePath );
		// Get and register local file references
		$localFileRefs = CSSMin::getAllLocalFileReferences( $style, $localDir );
		foreach ( $localFileRefs as $file ) {
			if ( file_exists( $file ) ) {
				$this->localFileRefs[] = $file;
			} else {
				$this->missingLocalFileRefs[] = $file;
			}
		}
		return CSSMin::remap(
			$style, $localDir, $remoteDir, true
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
	 * @return array Array of strings
	 */
	public function getTargets() {
		return $this->targets;
	}

	/**
	 * Compile a LESS file into CSS.
	 *
	 * Keeps track of all used files and adds them to localFileRefs.
	 *
	 * @since 1.22
	 * @throws Exception If less.php encounters a parse error
	 * @param string $fileName File path of LESS source
	 * @param Less_Parser $parser Compiler to use, if not default
	 * @return string CSS source
	 */
	protected function compileLessFile( $fileName, $compiler = null ) {
		if ( !$compiler ) {
			$compiler = $this->getLessCompiler();
		}
		$result = $compiler->parseFile( $fileName )->getCss();
		$this->localFileRefs += array_keys( $compiler->AllParsedFiles() );
		return $result;
	}

	/**
	 * Get a LESS compiler instance for this module in given context.
	 *
	 * Just calls ResourceLoader::getLessCompiler() by default to get a global compiler.
	 *
	 * @param ResourceLoaderContext $context
	 * @throws MWException
	 * @since 1.24
	 * @return Less_Parser
	 */
	protected function getLessCompiler( ResourceLoaderContext $context = null ) {
		return ResourceLoader::getLessCompiler( $this->getConfig() );
	}

	/**
	 * Takes named templates by the module and returns an array mapping.
	 * @return array of templates mapping template alias to content
	 * @throws MWException
	 */
	public function getTemplates() {
		$templates = array();

		foreach ( $this->templates as $alias => $templatePath ) {
			// Alias is optional
			if ( is_int( $alias ) ) {
				$alias = $templatePath;
			}
			$localPath = $this->getLocalPath( $templatePath );
			if ( file_exists( $localPath ) ) {
				$content = file_get_contents( $localPath );
				$templates[$alias] = $content;
			} else {
				$msg = __METHOD__ . ": template file not found: \"$localPath\"";
				wfDebugLog( 'resourceloader', $msg );
				throw new MWException( $msg );
			}
		}
		return $templates;
	}
}
