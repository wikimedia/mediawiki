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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

use MediaWiki\Languages\LanguageFallback;
use MediaWiki\MediaWikiServices;

/**
 * Module based on local JavaScript/CSS files.
 *
 * The following public methods can query the database:
 *
 * - getDefinitionSummary / … / ResourceLoaderModule::getFileDependencies.
 * - getVersionHash / getDefinitionSummary / … / ResourceLoaderModule::getFileDependencies.
 * - getStyles / ResourceLoaderModule::saveFileDependencies.
 *
 * @ingroup ResourceLoader
 * @see $wgResourceModules
 * @since 1.17
 */
class ResourceLoaderFileModule extends ResourceLoaderModule {
	/** @var string Local base path, see __construct() */
	protected $localBasePath = '';

	/** @var string Remote base path, see __construct() */
	protected $remoteBasePath = '';

	/** @var array Saves a list of the templates named by the modules. */
	protected $templates = [];

	/**
	 * @var array List of paths to JavaScript files to always include
	 * @par Usage:
	 * @code
	 * [ [file-path], [file-path], ... ]
	 * @endcode
	 */
	protected $scripts = [];

	/**
	 * @var array List of JavaScript files to include when using a specific language
	 * @par Usage:
	 * @code
	 * [ [language-code] => [ [file-path], [file-path], ... ], ... ]
	 * @endcode
	 */
	protected $languageScripts = [];

	/**
	 * @var array List of JavaScript files to include when using a specific skin
	 * @par Usage:
	 * @code
	 * [ [skin-name] => [ [file-path], [file-path], ... ], ... ]
	 * @endcode
	 */
	protected $skinScripts = [];

	/**
	 * @var array List of paths to JavaScript files to include in debug mode
	 * @par Usage:
	 * @code
	 * [ [skin-name] => [ [file-path], [file-path], ... ], ... ]
	 * @endcode
	 */
	protected $debugScripts = [];

	/**
	 * @var array List of paths to CSS files to always include
	 * @par Usage:
	 * @code
	 * [ [file-path], [file-path], ... ]
	 * @endcode
	 */
	protected $styles = [];

	/**
	 * @var array List of paths to CSS files to include when using specific skins
	 * @par Usage:
	 * @code
	 * [ [file-path], [file-path], ... ]
	 * @endcode
	 */
	protected $skinStyles = [];

	/**
	 * @var array List of packaged files to make available through require()
	 * @par Usage:
	 * @code
	 * [ [file-path-or-object], [file-path-or-object], ... ]
	 * @endcode
	 */
	protected $packageFiles = null;

	/**
	 * @var array Expanded versions of $packageFiles, lazy-computed by expandPackageFiles();
	 *  keyed by context hash
	 */
	private $expandedPackageFiles = [];

	/**
	 * @var array Further expanded versions of $expandedPackageFiles, lazy-computed by
	 *   getPackageFiles(); keyed by context hash
	 */
	private $fullyExpandedPackageFiles = [];

	/**
	 * @var array List of modules this module depends on
	 * @par Usage:
	 * @code
	 * [ [file-path], [file-path], ... ]
	 * @endcode
	 */
	protected $dependencies = [];

	/**
	 * @var string File name containing the body of the skip function
	 */
	protected $skipFunction = null;

	/**
	 * @var array List of message keys used by this module
	 * @par Usage:
	 * @code
	 * [ [message-key], [message-key], ... ]
	 * @endcode
	 */
	protected $messages = [];

	/** @var string Name of group to load this module in */
	protected $group;

	/** @var bool Link to raw files in debug mode */
	protected $debugRaw = true;

	protected $targets = [ 'desktop' ];

	/** @var bool Whether CSSJanus flipping should be skipped for this module */
	protected $noflip = false;

	/**
	 * @var bool Whether getStyleURLsForDebug should return raw file paths,
	 * or return load.php urls
	 */
	protected $hasGeneratedStyles = false;

	/**
	 * @var array Place where readStyleFile() tracks file dependencies
	 * @par Usage:
	 * @code
	 * [ [file-path], [file-path], ... ]
	 * @endcode
	 */
	protected $localFileRefs = [];

	/**
	 * @var array Place where readStyleFile() tracks file dependencies for non-existent files.
	 * Used in tests to detect missing dependencies.
	 */
	protected $missingLocalFileRefs = [];

	/**
	 * @var VueComponentParser|null Lazy-created by getVueComponentParser()
	 */
	protected $vueComponentParser = null;

	/**
	 * Constructs a new module from an options array.
	 *
	 * @param array $options See $wgResourceModules for the available options.
	 * @param string|null $localBasePath Base path to prepend to all local paths in $options.
	 *     Defaults to $IP
	 * @param string|null $remoteBasePath Base path to prepend to all remote paths in $options.
	 *     Defaults to $wgResourceBasePath
	 * @throws InvalidArgumentException
	 * @see $wgResourceModules
	 */
	public function __construct(
		array $options = [],
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
				case 'styles':
				case 'packageFiles':
					$this->{$member} = is_array( $option ) ? $option : [ $option ];
					break;
				case 'templates':
					$hasTemplates = true;
					$this->{$member} = is_array( $option ) ? $option : [ $option ];
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
						$this->{$member}[$key] = is_array( $value ) ? $value : [ $value ];
					}
					break;
				case 'deprecated':
					$this->deprecated = $option;
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
				case 'skipFunction':
					$this->{$member} = (string)$option;
					break;
				// Single booleans
				case 'debugRaw':
				case 'noflip':
					$this->{$member} = (bool)$option;
					break;
			}
		}
		if ( isset( $options['scripts'] ) && isset( $options['packageFiles'] ) ) {
			throw new InvalidArgumentException( "A module may not set both 'scripts' and 'packageFiles'" );
		}
		if ( isset( $options['packageFiles'] ) && isset( $options['skinScripts'] ) ) {
			throw new InvalidArgumentException( "Options 'skinScripts' and 'packageFiles' cannot be used together." );
		}
		if ( $hasTemplates ) {
			$this->dependencies[] = 'mediawiki.template';
			// Ensure relevant template compiler module gets loaded
			foreach ( $this->templates as $alias => $templatePath ) {
				if ( is_int( $alias ) ) {
					$alias = $this->getPath( $templatePath );
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
	 * @param string|null $localBasePath Path to use if not provided in module definition. Defaults
	 *     to $IP
	 * @param string|null $remoteBasePath Path to use if not provided in module definition. Defaults
	 *     to $wgResourceBasePath
	 * @return array [ localBasePath, remoteBasePath ]
	 */
	public static function extractBasePaths(
		array $options = [],
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

		return [ $localBasePath, $remoteBasePath ];
	}

	/**
	 * Gets all scripts for a given context concatenated together.
	 *
	 * @param ResourceLoaderContext $context Context in which to generate script
	 * @return string|array JavaScript code for $context, or package files data structure
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$deprecationScript = $this->getDeprecationInformation( $context );
		if ( $this->packageFiles !== null ) {
			$packageFiles = $this->getPackageFiles( $context );
			foreach ( $packageFiles['files'] as &$file ) {
				if ( $file['type'] === 'script+style' ) {
					$file['content'] = $file['content']['script'];
					$file['type'] = 'script';
				}
			}
			if ( $deprecationScript ) {
				$mainFile =& $packageFiles['files'][$packageFiles['main']];
				$mainFile['content'] = $deprecationScript . $mainFile['content'];
			}
			return $packageFiles;
		}

		$files = $this->getScriptFiles( $context );
		return $deprecationScript . $this->readScriptFiles( $files );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getScriptURLsForDebug( ResourceLoaderContext $context ) {
		$urls = [];
		foreach ( $this->getScriptFiles( $context ) as $file ) {
			$urls[] = OutputPage::transformResourcePath(
				$this->getConfig(),
				$this->getRemotePath( $file )
			);
		}
		return $urls;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		// If package files are involved, don't support URL loading, because that breaks
		// scoped require() functions
		return $this->debugRaw && !$this->packageFiles;
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
			$context
		);

		if ( $this->packageFiles !== null ) {
			$packageFiles = $this->getPackageFiles( $context );
			foreach ( $packageFiles['files'] as $fileName => $file ) {
				if ( $file['type'] === 'script+style' ) {
					$style = $this->processStyle(
						$file['content']['style'],
						$file['content']['styleLang'],
						$fileName,
						$context
					);
					$styles['all'] = ( $styles['all'] ?? '' ) . "\n" . $style;
				}
			}
		}

		// Track indirect file dependencies so that ResourceLoaderStartUpModule can check for
		// on-disk file changes to any of this files without having to recompute the file list
		$this->saveFileDependencies( $context, $this->localFileRefs );

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
		$urls = [];
		foreach ( $this->getStyleFiles( $context ) as $mediaType => $list ) {
			$urls[$mediaType] = [];
			foreach ( $list as $file ) {
				$urls[$mediaType][] = OutputPage::transformResourcePath(
					$this->getConfig(),
					$this->getRemotePath( $file )
				);
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
	 * Gets list of names of modules this module depends on.
	 * @param ResourceLoaderContext|null $context
	 * @return array List of module names
	 */
	public function getDependencies( ResourceLoaderContext $context = null ) {
		return $this->dependencies;
	}

	/**
	 * Helper method for getting a file.
	 *
	 * @param string $localPath The path to the resource to load
	 * @param string $type The type of resource being loaded (for error reporting only)
	 * @throws RuntimeException If the supplied path is not found, or not a path
	 * @return string
	 */
	private function getFileContents( $localPath, $type ) {
		if ( !is_file( $localPath ) ) {
			throw new RuntimeException(
				__METHOD__ . ": $type file not found, or is not a file: \"$localPath\""
			);
		}
		return $this->stripBom( file_get_contents( $localPath ) );
	}

	/**
	 * Get the skip function.
	 * @return null|string
	 * @throws RuntimeException If the file doesn't exist
	 */
	public function getSkipFunction() {
		if ( !$this->skipFunction ) {
			return null;
		}
		$localPath = $this->getLocalPath( $this->skipFunction );
		return $this->getFileContents( $localPath, 'skip function' );
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
	 * Helper method for getDefinitionSummary.
	 *
	 * @param ResourceLoaderContext $context
	 * @return string
	 */
	private function getFileHashes( ResourceLoaderContext $context ) {
		$files = [];

		$styleFiles = $this->getStyleFiles( $context );
		foreach ( $styleFiles as $paths ) {
			$files = array_merge( $files, $paths );
		}

		// Extract file paths for package files
		// Optimisation: Use foreach() and isset() instead of array_map/array_filter.
		// This is a hot code path, called by StartupModule for thousands of modules.
		$expandedPackageFiles = $this->expandPackageFiles( $context );
		$packageFiles = [];
		if ( $expandedPackageFiles ) {
			foreach ( $expandedPackageFiles['files'] as $fileInfo ) {
				if ( isset( $fileInfo['filePath'] ) ) {
					$packageFiles[] = $fileInfo['filePath'];
				}
			}
		}

		// Merge all the file paths we were able discover directly from the module definition.
		// This is the master list of direct-dependent files for this module.
		$files = array_merge(
			$files,
			$packageFiles,
			$this->scripts,
			$this->templates,
			$context->getDebug() ? $this->debugScripts : [],
			$this->getLanguageScripts( $context->getLanguage() ),
			self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' )
		);
		if ( $this->skipFunction ) {
			$files[] = $this->skipFunction;
		}

		// Expand these local paths into absolute file paths
		$files = array_map( [ $this, 'getLocalPath' ], $files );

		// Add any lazily discovered file dependencies from previous module builds.
		// These are added last because they are already absolute file paths.
		$files = array_merge( $files, $this->getFileDependencies( $context ) );

		// Filter out any duplicates. Typically introduced by getFileDependencies() which
		// may lazily re-discover a master file.
		$files = array_unique( $files );

		// Don't return array keys or any other form of file path here, only the hashes.
		// Including file paths would needlessly cause global cache invalidation when files
		// move on disk or if e.g. the MediaWiki directory name changes.
		// Anything where order is significant is already detected by the definition summary.
		return FileContentsHasher::getFileContentsHash( $files );
	}

	/**
	 * Get the definition summary for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = parent::getDefinitionSummary( $context );

		$options = [];
		foreach ( [
			// The following properties are omitted because they don't affect the module reponse:
			// - localBasePath (Per T104950; Changes when absolute directory name changes. If
			//    this affects 'scripts' and other file paths, getFileHashes accounts for that.)
			// - remoteBasePath (Per T104950)
			// - dependencies (provided via startup module)
			// - targets
			// - group (provided via startup module)
			'scripts',
			'debugScripts',
			'styles',
			'languageScripts',
			'skinScripts',
			'skinStyles',
			'messages',
			'templates',
			'skipFunction',
			'debugRaw',
		] as $member ) {
			$options[$member] = $this->{$member};
		}

		$packageFiles = $this->expandPackageFiles( $context );
		if ( $packageFiles ) {
			// Extract the minimum needed:
			// - The 'main' pointer (included as-is).
			// - The 'files' array, simplified to only which files exist (the keys of
			//   this array), and something that represents their non-file content.
			//   For packaged files that reflect files directly from disk, the
			//   'getFileHashes' method tracks their content already.
			//   It is important that the keys of the $packageFiles['files'] array
			//   are preserved, as they do affect the module output.
			$packageFiles['files'] = array_map( function ( $fileInfo ) {
				return $fileInfo['definitionSummary'] ?? ( $fileInfo['content'] ?? null );
			}, $packageFiles['files'] );
		}

		$summary[] = [
			'options' => $options,
			'packageFiles' => $packageFiles,
			'fileHashes' => $this->getFileHashes( $context ),
			'messageBlob' => $this->getMessageBlob( $context ),
		];

		$lessVars = $this->getLessVars( $context );
		if ( $lessVars ) {
			$summary[] = [ 'lessVars' => $lessVars ];
		}

		return $summary;
	}

	/**
	 * @return VueComponentParser
	 */
	protected function getVueComponentParser() {
		if ( $this->vueComponentParser === null ) {
			$this->vueComponentParser = new VueComponentParser;
		}
		return $this->vueComponentParser;
	}

	/**
	 * @param string|ResourceLoaderFilePath $path
	 * @return string
	 */
	protected function getPath( $path ) {
		if ( $path instanceof ResourceLoaderFilePath ) {
			return $path->getPath();
		}

		return $path;
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
	 * Infer the file type from a package file path.
	 * @param string $path
	 * @return string 'script', 'script-vue', or 'data'
	 */
	public static function getPackageFileType( $path ) {
		if ( preg_match( '/\.json$/i', $path ) ) {
			return 'data';
		}
		if ( preg_match( '/\.vue$/i', $path ) ) {
			return 'script-vue';
		}
		return 'script';
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
		$collatedFiles = [];
		foreach ( $list as $key => $value ) {
			if ( is_int( $key ) ) {
				// File name as the value
				if ( !isset( $collatedFiles[$default] ) ) {
					$collatedFiles[$default] = [];
				}
				$collatedFiles[$default][] = $value;
			} elseif ( is_array( $value ) ) {
				// File name as the key, options array as the value
				$optionValue = $value[$option] ?? $default;
				if ( !isset( $collatedFiles[$optionValue] ) ) {
					$collatedFiles[$optionValue] = [];
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
	 * @param string|null $fallback Key to look for in $list if $key doesn't exist
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
		return [];
	}

	/**
	 * Get a list of script file paths for this module, in order of proper execution.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array List of file paths
	 */
	private function getScriptFiles( ResourceLoaderContext $context ) {
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
		$fallbacks = MediaWikiServices::getInstance()->getLanguageFallback()
			->getAll( $lang, LanguageFallback::MESSAGES );
		foreach ( $fallbacks as $lang ) {
			$scripts = self::tryForKey( $this->languageScripts, $lang );
			if ( $scripts ) {
				return $scripts;
			}
		}

		return [];
	}

	/**
	 * Get a list of file paths for all styles in this module, in order of proper inclusion.
	 *
	 * @internal Exposed only for use by WebInstallerOutput.
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
		$styleFiles = [];
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

		$result = [];

		foreach ( $collatedStyleFiles as $media => $styleFiles ) {
			foreach ( $styleFiles as $styleFile ) {
				$result[] = $this->getLocalPath( $styleFile );
			}
		}

		return $result;
	}

	/**
	 * Get the contents of a list of JavaScript files. Helper for getScript().
	 *
	 * @param array $scripts List of file paths to scripts to read, remap and concetenate
	 * @return string Concatenated JavaScript data from $scripts
	 * @throws RuntimeException
	 */
	private function readScriptFiles( array $scripts ) {
		if ( empty( $scripts ) ) {
			return '';
		}
		$js = '';
		foreach ( array_unique( $scripts, SORT_REGULAR ) as $fileName ) {
			$localPath = $this->getLocalPath( $fileName );
			$contents = $this->getFileContents( $localPath, 'script' );
			$js .= $contents . "\n";
		}
		return $js;
	}

	/**
	 * Get the contents of a list of CSS files.
	 *
	 * @internal This is considered a private method. Exposed for internal use by WebInstallerOutput.
	 * @param array $styles Map of media type to file paths to read, remap, and concatenate
	 * @param ResourceLoaderContext $context
	 * @return array List of concatenated and remapped CSS data from $styles,
	 *     keyed by media type
	 * @throws RuntimeException
	 */
	public function readStyleFiles( array $styles, ResourceLoaderContext $context ) {
		if ( !$styles ) {
			return [];
		}
		foreach ( $styles as $media => $files ) {
			$uniqueFiles = array_unique( $files, SORT_REGULAR );
			$styleFiles = [];
			foreach ( $uniqueFiles as $file ) {
				$styleFiles[] = $this->readStyleFile( $file, $context );
			}
			$styles[$media] = implode( "\n", $styleFiles );
		}
		return $styles;
	}

	/**
	 * Read and process a style file. Reads a file from disk and runs it through processStyle().
	 *
	 * This method can be used as a callback for array_map()
	 *
	 * @internal
	 * @param string $path File path of style file to read
	 * @param ResourceLoaderContext $context
	 * @return string CSS data in script file
	 * @throws RuntimeException If the file doesn't exist
	 */
	protected function readStyleFile( $path, ResourceLoaderContext $context ) {
		$localPath = $this->getLocalPath( $path );
		$style = $this->getFileContents( $localPath, 'style' );
		$styleLang = $this->getStyleSheetLang( $localPath );

		return $this->processStyle( $style, $styleLang, $path, $context );
	}

	/**
	 * Process a CSS/LESS string.
	 *
	 * This method performs the following processing steps:
	 * - LESS compilation (if $styleLang = 'less')
	 * - RTL flipping with CSSJanus (if getFlip() returns true)
	 * - Registration of references to local files in $localFileRefs and $missingLocalFileRefs
	 * - URL remapping and data URI embedding
	 *
	 * @internal
	 * @param string $style CSS/LESS string
	 * @param string $styleLang Language of $style ('css' or 'less')
	 * @param string $path File path where the CSS/LESS lives, used for resolving relative file paths
	 * @param ResourceLoaderContext $context
	 * @return string Processed CSS
	 */
	protected function processStyle( $style, $styleLang, $path, ResourceLoaderContext $context ) {
		$localPath = $this->getLocalPath( $path );
		$remotePath = $this->getRemotePath( $path );

		if ( $styleLang === 'less' ) {
			$style = $this->compileLessString( $style, $localPath, $context );
			$this->hasGeneratedStyles = true;
		}

		if ( $this->getFlip( $context ) ) {
			$style = CSSJanus::transform(
				$style,
				/* $swapLtrRtlInURL = */ true,
				/* $swapLeftRightInURL = */ false
			);
		}

		$localDir = dirname( $localPath );
		$remoteDir = dirname( $remotePath );
		// Get and register local file references
		$localFileRefs = CSSMin::getLocalFileReferences( $style, $localDir );
		foreach ( $localFileRefs as $file ) {
			if ( file_exists( $file ) ) {
				$this->localFileRefs[] = $file;
			} else {
				$this->missingLocalFileRefs[] = $file;
			}
		}
		// Don't cache this call. remap() ensures data URIs embeds are up to date,
		// and urls contain correct content hashes in their query string. (T128668)
		return CSSMin::remap( $style, $localDir, $remoteDir, true );
	}

	/**
	 * Get whether CSS for this module should be flipped
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function getFlip( ResourceLoaderContext $context ) {
		return $context->getDirection() === 'rtl' && !$this->noflip;
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
	 * Get the module's load type.
	 *
	 * @since 1.28
	 * @return string
	 */
	public function getType() {
		$canBeStylesOnly = !(
			// All options except 'styles', 'skinStyles' and 'debugRaw'
			$this->scripts
			|| $this->debugScripts
			|| $this->templates
			|| $this->languageScripts
			|| $this->skinScripts
			|| $this->dependencies
			|| $this->messages
			|| $this->skipFunction
			|| $this->packageFiles
		);
		return $canBeStylesOnly ? self::LOAD_STYLES : self::LOAD_GENERAL;
	}

	/**
	 * @deprecated since 1.35 Use compileLessString() instead
	 * @param string $fileName
	 * @param ResourceLoaderContext $context
	 * @return string
	 * @codeCoverageIgnore
	 */
	protected function compileLessFile( $fileName, ResourceLoaderContext $context ) {
		wfDeprecated( __METHOD__, '1.35' );

		$style = $this->getFileContents( $fileName, 'LESS' );
		return $this->compileLessString( $style, $fileName, $context );
	}

	/**
	 * Compile a LESS string into CSS.
	 *
	 * Keeps track of all used files and adds them to localFileRefs.
	 *
	 * @since 1.35
	 * @throws Exception If less.php encounters a parse error
	 * @param string $style LESS source to compile
	 * @param string $fileName File path of LESS source, used for resolving relative file paths
	 * @param ResourceLoaderContext $context Context in which to generate script
	 * @return string CSS source
	 */
	protected function compileLessString( $style, $fileName, ResourceLoaderContext $context ) {
		static $cache;

		if ( !$cache ) {
			$cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );
		}

		$vars = $this->getLessVars( $context );
		// Construct a cache key from a hash of the LESS source, and a hash digest
		// of the LESS variables used for compilation.
		ksort( $vars );
		$varsHash = hash( 'md4', serialize( $vars ) );
		$styleHash = hash( 'md4', $style );
		$cacheKey = $cache->makeGlobalKey( 'resourceloader-less', $styleHash, $varsHash );
		$cachedCompile = $cache->get( $cacheKey );

		// If we got a cached value, we have to validate it by getting a
		// checksum of all the files that were loaded by the parser and
		// ensuring it matches the cached entry's.
		if ( isset( $cachedCompile['hash'] ) ) {
			$contentHash = FileContentsHasher::getFileContentsHash( $cachedCompile['files'] );
			if ( $contentHash === $cachedCompile['hash'] ) {
				$this->localFileRefs = array_merge( $this->localFileRefs, $cachedCompile['files'] );
				return $cachedCompile['css'];
			}
		}

		$compiler = $context->getResourceLoader()->getLessCompiler( $vars );
		$css = $compiler->parse( $style, $fileName )->getCss();
		$files = $compiler->AllParsedFiles();
		$this->localFileRefs = array_merge( $this->localFileRefs, $files );

		$cache->set( $cacheKey, [
			'css'   => $css,
			'files' => $files,
			'hash'  => FileContentsHasher::getFileContentsHash( $files ),
		], $cache::TTL_DAY );

		return $css;
	}

	/**
	 * Takes named templates by the module and returns an array mapping.
	 * @return array Templates mapping template alias to content
	 * @throws RuntimeException If a file doesn't exist
	 */
	public function getTemplates() {
		$templates = [];

		foreach ( $this->templates as $alias => $templatePath ) {
			// Alias is optional
			if ( is_int( $alias ) ) {
				$alias = $this->getPath( $templatePath );
			}
			$localPath = $this->getLocalPath( $templatePath );
			$content = $this->getFileContents( $localPath, 'template' );

			$templates[$alias] = $this->stripBom( $content );
		}
		return $templates;
	}

	/**
	 * Internal helper for use by getPackageFiles(), getFileHashes() and getDefinitionSummary().
	 *
	 * This expands the 'packageFiles' definition into something that's (almost) the right format
	 * for getPackageFiles() to return. It expands shorthands, resolves config vars, and handles
	 * summarising any non-file data for getVersionHash(). For file-based data, getFileHashes()
	 * handles it instead, which also ends up in getDefinitionSummary().
	 *
	 * What it does not do is reading the actual contents of any specified files, nor invoking
	 * the computation callbacks. Those things are done by getPackageFiles() instead to improve
	 * backend performance by only doing this work when the module response is needed, and not
	 * when merely computing the version hash for StartupModule, or when checking
	 * If-None-Match headers for a HTTP 304 response.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array|null
	 * @phan-return array{main:string,files:string[][]}|null
	 * @throws LogicException If the 'packageFiles' definition is invalid.
	 */
	private function expandPackageFiles( ResourceLoaderContext $context ) {
		$hash = $context->getHash();
		if ( isset( $this->expandedPackageFiles[$hash] ) ) {
			return $this->expandedPackageFiles[$hash];
		}
		if ( $this->packageFiles === null ) {
			return null;
		}
		$expandedFiles = [];
		$mainFile = null;

		foreach ( $this->packageFiles as $key => $fileInfo ) {
			if ( is_string( $fileInfo ) ) {
				$fileInfo = [ 'name' => $fileInfo, 'file' => $fileInfo ];
			}
			if ( !isset( $fileInfo['name'] ) ) {
				$msg = "Missing 'name' key in package file info for module '{$this->getName()}'," .
					" offset '{$key}'.";
				$this->getLogger()->error( $msg );
				throw new LogicException( $msg );
			}
			$fileName = $fileInfo['name'];

			// Infer type from alias if needed
			$type = $fileInfo['type'] ?? self::getPackageFileType( $fileName );
			$expanded = [ 'type' => $type ];
			if ( !empty( $fileInfo['main'] ) ) {
				$mainFile = $fileName;
				if ( $type !== 'script' && $type !== 'script-vue' ) {
					$msg = "Main file in package must be of type 'script', module " .
						"'{$this->getName()}', main file '{$mainFile}' is '{$type}'.";
					$this->getLogger()->error( $msg );
					throw new LogicException( $msg );
				}
			}

			// Perform expansions (except 'file' and 'callback'), creating one of these keys:
			// - 'content': literal value.
			// - 'filePath': content to be read from a file.
			// - 'callback': content computed by a callable.
			if ( isset( $fileInfo['content'] ) ) {
				$expanded['content'] = $fileInfo['content'];
			} elseif ( isset( $fileInfo['file'] ) ) {
				$expanded['filePath'] = $fileInfo['file'];
			} elseif ( isset( $fileInfo['callback'] ) ) {
				// If no extra parameter for the callback is given, use null.
				$expanded['callbackParam'] = $fileInfo['callbackParam'] ?? null;

				if ( !is_callable( $fileInfo['callback'] ) ) {
					$msg = "Invalid 'callback' for module '{$this->getName()}', file '{$fileName}'.";
					$this->getLogger()->error( $msg );
					throw new LogicException( $msg );
				}
				if ( isset( $fileInfo['versionCallback'] ) ) {
					if ( !is_callable( $fileInfo['versionCallback'] ) ) {
						throw new LogicException( "Invalid 'versionCallback' for "
							. "module '{$this->getName()}', file '{$fileName}'."
						);
					}

					// Execute the versionCallback with the same arguments that
					// would be given to the callback
					$callbackResult = ( $fileInfo['versionCallback'] )(
						$context,
						$this->getConfig(),
						$expanded['callbackParam']
					);
					if ( $callbackResult instanceof ResourceLoaderFilePath ) {
						$expanded['filePath'] = $callbackResult->getPath();
					} else {
						$expanded['definitionSummary'] = $callbackResult;
					}
					// Don't invoke 'callback' here as it may be expensive (T223260).
					$expanded['callback'] = $fileInfo['callback'];
				} else {
					// Else go ahead invoke callback with its arguments.
					$callbackResult = ( $fileInfo['callback'] )(
						$context,
						$this->getConfig(),
						$expanded['callbackParam']
					);
					if ( $callbackResult instanceof ResourceLoaderFilePath ) {
						$expanded['filePath'] = $callbackResult->getPath();
					} else {
						$expanded['content'] = $callbackResult;
					}
				}
			} elseif ( isset( $fileInfo['config'] ) ) {
				if ( $type !== 'data' ) {
					$msg = "Key 'config' only valid for data files. "
						. " Module '{$this->getName()}', file '{$fileName}' is '{$type}'.";
					$this->getLogger()->error( $msg );
					throw new LogicException( $msg );
				}
				$expandedConfig = [];
				foreach ( $fileInfo['config'] as $key => $var ) {
					$expandedConfig[ is_numeric( $key ) ? $var : $key ] = $this->getConfig()->get( $var );
				}
				$expanded['content'] = $expandedConfig;
			} elseif ( !empty( $fileInfo['main'] ) ) {
				// [ 'name' => 'foo.js', 'main' => true ] is shorthand
				$expanded['filePath'] = $fileName;
			} else {
				$msg = "Incomplete definition for module '{$this->getName()}', file '{$fileName}'. "
					. "One of 'file', 'content', 'callback', or 'config' must be set.";
				$this->getLogger()->error( $msg );
				throw new LogicException( $msg );
			}

			$expandedFiles[$fileName] = $expanded;
		}

		if ( $expandedFiles && $mainFile === null ) {
			// The first package file that is a script is the main file
			foreach ( $expandedFiles as $path => $file ) {
				if ( $file['type'] === 'script' || $file['type'] === 'script-vue' ) {
					$mainFile = $path;
					break;
				}
			}
		}

		$result = [
			'main' => $mainFile,
			'files' => $expandedFiles
		];

		$this->expandedPackageFiles[$hash] = $result;
		return $result;
	}

	/**
	 * Resolves the package files defintion and generates the content of each package file.
	 * @param ResourceLoaderContext $context
	 * @return array Package files data structure, see ResourceLoaderModule::getScript()
	 * @throws RuntimeException If a file doesn't exist, or parsing a .vue file fails
	 */
	public function getPackageFiles( ResourceLoaderContext $context ) {
		if ( $this->packageFiles === null ) {
			return null;
		}
		$hash = $context->getHash();
		if ( isset( $this->fullyExpandedPackageFiles[ $hash ] ) ) {
			return $this->fullyExpandedPackageFiles[ $hash ];
		}
		$expandedPackageFiles = $this->expandPackageFiles( $context );

		// Expand file contents
		foreach ( $expandedPackageFiles['files'] as $fileName => &$fileInfo ) {
			// Turn any 'filePath' or 'callback' key into actual 'content',
			// and remove the key after that. The callback could return a
			// ResourceLoaderFilePath object; if that happens, fall through
			// to the 'filePath' handling.
			if ( isset( $fileInfo['callback'] ) ) {
				$callbackResult = ( $fileInfo['callback'] )(
					$context,
					$this->getConfig(),
					$fileInfo['callbackParam']
				);
				if ( $callbackResult instanceof ResourceLoaderFilePath ) {
					// Fall through to the filePath handling code below
					$fileInfo['filePath'] = $callbackResult->getPath();
				} else {
					$fileInfo['content'] = $callbackResult;
				}
				unset( $fileInfo['callback'] );
			}
			// Only interpret 'filePath' if 'content' hasn't been set already.
			// This can happen if 'versionCallback' provided 'filePath',
			// while 'callback' provides 'content'. In that case both are set
			// at this point. The 'filePath' from 'versionCallback' in that case is
			// only to inform getDefinitionSummary().
			if ( !isset( $fileInfo['content'] ) && isset( $fileInfo['filePath'] ) ) {
				$localPath = $this->getLocalPath( $fileInfo['filePath'] );
				$content = $this->getFileContents( $localPath, 'package' );
				if ( $fileInfo['type'] === 'data' ) {
					$content = json_decode( $content );
				}
				$fileInfo['content'] = $content;
				unset( $fileInfo['filePath'] );
			}
			if ( $fileInfo['type'] === 'script-vue' ) {
				try {
					$parsedComponent = $this->getVueComponentParser()->parse(
						$fileInfo['content'],
						[ 'minifyTemplate' => !$context->getDebug() ]
					);
				} catch ( Exception $e ) {
					$msg = "Error parsing file '$fileName' in module '{$this->getName()}': " .
						$e->getMessage();
					$this->getLogger()->error( $msg );
					throw new RuntimeException( $msg );
				}
				$encodedTemplate = json_encode( $parsedComponent['template'] );
				if ( $context->getDebug() ) {
					// Replace \n (backslash-n) with space + backslash-newline in debug mode
					// We only replace \n if not preceded by a backslash, to avoid breaking '\\n'
					$encodedTemplate = preg_replace( '/(?<!\\\\)\\\\n/', " \\\n", $encodedTemplate );
					// Expand \t to real tabs in debug mode
					$encodedTemplate = strtr( $encodedTemplate, [ "\\t" => "\t" ] );
				}
				$fileInfo['content'] = [
					'script' => $parsedComponent['script'] .
						";\nmodule.exports.template = $encodedTemplate;",
					'style' => $parsedComponent['style'] ?? '',
					'styleLang' => $parsedComponent['styleLang'] ?? 'css'
				];
				$fileInfo['type'] = 'script+style';
			}

			// Not needed for client response, exists for use by getDefinitionSummary().
			unset( $fileInfo['definitionSummary'] );
			// Not needed for client response, used by callbacks only.
			unset( $fileInfo['callbackParam'] );
		}

		$this->fullyExpandedPackageFiles[ $hash ] = $expandedPackageFiles;
		return $expandedPackageFiles;
	}

	/**
	 * Takes an input string and removes the UTF-8 BOM character if present
	 *
	 * We need to remove these after reading a file, because we concatenate our files and
	 * the BOM character is not valid in the middle of a string.
	 * We already assume UTF-8 everywhere, so this should be safe.
	 *
	 * @param string $input
	 * @return string Input minus the intial BOM char
	 */
	protected function stripBom( $input ) {
		if ( substr_compare( "\xef\xbb\xbf", $input, 0, 3 ) === 0 ) {
			return substr( $input, 3 );
		}
		return $input;
	}
}
