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

namespace MediaWiki\ResourceLoader;

use CSSJanus;
use Exception;
use FileContentsHasher;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Registration\ExtensionRegistry;
use RuntimeException;
use Wikimedia\Minify\CSSMin;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Module based on local JavaScript/CSS files.
 *
 * The following public methods can query the database:
 *
 * - getDefinitionSummary / … / Module::getFileDependencies.
 * - getVersionHash / getDefinitionSummary / … / Module::getFileDependencies.
 * - getStyles / Module::saveFileDependencies.
 *
 * @ingroup ResourceLoader
 * @see $wgResourceModules
 * @since 1.17
 */
class FileModule extends Module {
	/** @var string Local base path, see __construct() */
	protected $localBasePath = '';

	/** @var string Remote base path, see __construct() */
	protected $remoteBasePath = '';

	/**
	 * @var array<int,string|FilePath> List of JavaScript file paths to always include
	 */
	protected $scripts = [];

	/**
	 * @var array<string,array<int,string|FilePath>> Lists of JavaScript files by language code
	 */
	protected $languageScripts = [];

	/**
	 * @var array<string,array<int,string|FilePath>> Lists of JavaScript files by skin name
	 */
	protected $skinScripts = [];

	/**
	 * @var array<int,string|FilePath> List of paths to JavaScript files to include in debug mode
	 */
	protected $debugScripts = [];

	/**
	 * @var array<int,string|FilePath> List of CSS file files to always include
	 */
	protected $styles = [];

	/**
	 * @var array<string,array<int,string|FilePath>> Lists of CSS files by skin name
	 */
	protected $skinStyles = [];

	/**
	 * Packaged files definition, to bundle and make available client-side via `require()`.
	 *
	 * @see FileModule::expandPackageFiles()
	 * @var null|array
	 * @phan-var null|array<int,string|FilePath|array{main?:bool,name?:string,file?:string|FilePath,type?:string,content?:mixed,config?:array,callback?:callable,callbackParam?:mixed,versionCallback?:callable}>
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
	 * @var string[] List of modules this module depends on
	 */
	protected $dependencies = [];

	/**
	 * @var null|string File name containing the body of the skip function
	 */
	protected $skipFunction = null;

	/**
	 * @var string[] List of message keys used by this module
	 */
	protected $messages = [];

	/** @var array<int|string,string|FilePath> List of the named templates used by this module */
	protected $templates = [];

	/** @var null|string Name of group to load this module in */
	protected $group = null;

	/** @var bool Link to raw files in debug mode */
	protected $debugRaw = true;

	/** @var bool Whether CSSJanus flipping should be skipped for this module */
	protected $noflip = false;

	/** @var bool Whether to skip the structure test ResourcesTest::testRespond() */
	protected $skipStructureTest = false;

	/**
	 * @var bool Whether getStyleURLsForDebug should return raw file paths,
	 * or return load.php urls
	 */
	protected $hasGeneratedStyles = false;

	/**
	 * @var string[] Place where readStyleFile() tracks file dependencies
	 */
	protected $localFileRefs = [];

	/**
	 * @var string[] Place where readStyleFile() tracks file dependencies for non-existent files.
	 * Used in tests to detect missing dependencies.
	 */
	protected $missingLocalFileRefs = [];

	/**
	 * @var VueComponentParser|null Lazy-created by getVueComponentParser()
	 */
	protected $vueComponentParser = null;

	/**
	 * Construct a new module from an options array.
	 *
	 * @param array $options See $wgResourceModules for the available options.
	 * @param string|null $localBasePath Base path to prepend to all local paths in $options.
	 *     Defaults to MW_INSTALL_PATH
	 * @param string|null $remoteBasePath Base path to prepend to all remote paths in $options.
	 *     Defaults to $wgResourceBasePath
	 */
	public function __construct(
		array $options = [],
		?string $localBasePath = null,
		?string $remoteBasePath = null
	) {
		// Flag to decide whether to automagically add the mediawiki.template module
		$hasTemplates = false;
		// localBasePath and remoteBasePath both have unbelievably long fallback chains
		// and need to be handled separately.
		[ $this->localBasePath, $this->remoteBasePath ] =
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
				case 'skipStructureTest':
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
	 *     to MW_INSTALL_PATH
	 * @param string|null $remoteBasePath Path to use if not provided in module definition. Defaults
	 *     to $wgResourceBasePath
	 * @return string[] [ localBasePath, remoteBasePath ]
	 */
	public static function extractBasePaths(
		array $options = [],
		$localBasePath = null,
		$remoteBasePath = null
	) {
		// The different ways these checks are done, and their ordering, look very silly,
		// but were preserved for backwards-compatibility just in case. Tread lightly.

		$remoteBasePath ??= MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::ResourceBasePath );

		if ( isset( $options['remoteExtPath'] ) ) {
			$extensionAssetsPath = MediaWikiServices::getInstance()->getMainConfig()
				->get( MainConfigNames::ExtensionAssetsPath );
			$remoteBasePath = $extensionAssetsPath . '/' . $options['remoteExtPath'];
		}

		if ( isset( $options['remoteSkinPath'] ) ) {
			$stylePath = MediaWikiServices::getInstance()->getMainConfig()
				->get( MainConfigNames::StylePath );
			$remoteBasePath = $stylePath . '/' . $options['remoteSkinPath'];
		}

		if ( array_key_exists( 'localBasePath', $options ) ) {
			$localBasePath = (string)$options['localBasePath'];
		}

		if ( array_key_exists( 'remoteBasePath', $options ) ) {
			$remoteBasePath = (string)$options['remoteBasePath'];
		}

		if ( $localBasePath === null ) {
			$localBasePath = MW_INSTALL_PATH;
		}

		if ( $remoteBasePath === '' ) {
			// If MediaWiki is installed at the document root (not recommended),
			// then wgScriptPath is set to the empty string by the installer to
			// ensure safe concatenating of file paths (avoid "/" + "/foo" being "//foo").
			// However, this also means the path itself can be an invalid URI path,
			// as those must start with a slash. Within ResourceLoader, we will not
			// do such primitive/unsafe slash concatenation and use URI resolution
			// instead, so beyond this point, to avoid fatal errors in CSSMin::resolveUrl(),
			// do a best-effort support for docroot installs by casting this to a slash.
			$remoteBasePath = '/';
		}

		return [ $localBasePath, $remoteBasePath ];
	}

	public function getScript( Context $context ) {
		$packageFiles = $this->getPackageFiles( $context );
		if ( $packageFiles !== null ) {
			foreach ( $packageFiles['files'] as &$file ) {
				if ( $file['type'] === 'script+style' ) {
					$file['content'] = $file['content']['script'];
					$file['type'] = 'script';
				}
			}
			return $packageFiles;
		}

		$files = $this->getScriptFiles( $context );
		foreach ( $files as &$file ) {
			$this->readFileInfo( $context, $file );
		}
		return [ 'plainScripts' => $files ];
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		// phpcs:ignore Generic.WhiteSpace.LanguageConstructSpacing.IncorrectSingle
		return
			// Denied by options?
			$this->debugRaw
			// If package files are involved, don't support URL loading, because that breaks
			// scoped require() functions
			&& !$this->packageFiles
			// Can't link to scripts generated by callbacks
			&& !$this->hasGeneratedScripts();
	}

	public function shouldSkipStructureTest() {
		return $this->skipStructureTest || parent::shouldSkipStructureTest();
	}

	/**
	 * Determine whether the module may potentially have generated scripts.
	 *
	 * @return bool
	 */
	private function hasGeneratedScripts() {
		foreach (
			[ $this->scripts, $this->languageScripts, $this->skinScripts, $this->debugScripts ]
			as $scripts
		) {
			foreach ( $scripts as $script ) {
				if ( is_array( $script ) ) {
					if ( isset( $script['callback'] ) || isset( $script['versionCallback'] ) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Get all styles for a given context.
	 *
	 * @param Context $context
	 * @return string[] CSS code for $context as an associative array mapping media type to CSS text.
	 */
	public function getStyles( Context $context ) {
		$styles = $this->readStyleFiles(
			$this->getStyleFiles( $context ),
			$context
		);

		$packageFiles = $this->getPackageFiles( $context );
		if ( $packageFiles !== null ) {
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

		// Track indirect file dependencies so that StartUpModule can check for
		// on-disk file changes to any of this files without having to recompute the file list
		$this->saveFileDependencies( $context, $this->localFileRefs );

		return $styles;
	}

	/**
	 * @param Context $context
	 * @return string[][] Lists of URLs by media type
	 */
	public function getStyleURLsForDebug( Context $context ) {
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
	 * Get message keys used by this module.
	 *
	 * @return string[] List of message keys
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * Get the name of the group this module should be loaded in.
	 *
	 * @return null|string Group name
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * Get names of modules this module depends on.
	 *
	 * @param Context|null $context
	 * @return string[] List of module names
	 */
	public function getDependencies( ?Context $context = null ) {
		return $this->dependencies;
	}

	/**
	 * Helper method for getting a file.
	 *
	 * @param string $localPath The path to the resource to load
	 * @param string $type The type of resource being loaded (for error reporting only)
	 * @return string
	 */
	private function getFileContents( $localPath, $type ) {
		if ( !is_file( $localPath ) ) {
			throw new RuntimeException( "$type file not found or not a file: \"$localPath\"" );
		}
		return $this->stripBom( file_get_contents( $localPath ) );
	}

	/**
	 * @return null|string
	 */
	public function getSkipFunction() {
		if ( !$this->skipFunction ) {
			return null;
		}
		$localPath = $this->getLocalPath( $this->skipFunction );
		return $this->getFileContents( $localPath, 'skip function' );
	}

	public function requiresES6() {
		return true;
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
	 * @param Context $context
	 * @return string Hash
	 */
	private function getFileHashes( Context $context ) {
		$files = [];

		foreach ( $this->getStyleFiles( $context ) as $filePaths ) {
			foreach ( $filePaths as $filePath ) {
				$files[] = $this->getLocalPath( $filePath );
			}
		}

		// Extract file paths for package files
		// Optimisation: Use foreach() and isset() instead of array_map/array_filter.
		// This is a hot code path, called by StartupModule for thousands of modules.
		$expandedPackageFiles = $this->expandPackageFiles( $context );
		if ( $expandedPackageFiles ) {
			foreach ( $expandedPackageFiles['files'] as $fileInfo ) {
				$filePath = $fileInfo['filePath'] ?? $fileInfo['versionFilePath'] ?? null;
				if ( $filePath instanceof FilePath ) {
					$files[] = $filePath->getLocalPath();
				}
			}
		}

		// Add other configured paths
		$scriptFileInfos = $this->getScriptFiles( $context );
		foreach ( $scriptFileInfos as $fileInfo ) {
			$filePath = $fileInfo['filePath'] ?? $fileInfo['versionFilePath'] ?? null;
			if ( $filePath instanceof FilePath ) {
				$files[] = $filePath->getLocalPath();
			}
		}

		foreach ( $this->templates as $filePath ) {
			$files[] = $this->getLocalPath( $filePath );
		}

		if ( $this->skipFunction ) {
			$files[] = $this->getLocalPath( $this->skipFunction );
		}

		// Add any lazily discovered file dependencies from previous module builds.
		// These are saved as relatative paths.
		foreach ( Module::expandRelativePaths( $this->getFileDependencies( $context ) ) as $file ) {
			$files[] = $file;
		}

		// Filter out any duplicates. Typically introduced by getFileDependencies() which
		// may lazily re-discover a primary file.
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
	 * @param Context $context
	 * @return array
	 */
	public function getDefinitionSummary( Context $context ) {
		$summary = parent::getDefinitionSummary( $context );

		$options = [];
		foreach ( [
			// The following properties are omitted because they don't affect the module response:
			// - localBasePath (Per T104950; Changes when absolute directory name changes. If
			//    this affects 'scripts' and other file paths, getFileHashes accounts for that.)
			// - remoteBasePath (Per T104950)
			// - dependencies (provided via startup module)
			// - group (provided via startup module)
			'styles',
			'skinStyles',
			'messages',
			'templates',
			'skipFunction',
			'debugRaw',
		] as $member ) {
			$options[$member] = $this->{$member};
		}

		$packageFiles = $this->expandPackageFiles( $context );
		$packageSummaries = [];
		if ( $packageFiles ) {
			// Extract the minimum needed:
			// - The 'main' pointer (included as-is).
			// - The 'files' array, simplified to only which files exist (the keys of
			//   this array), and something that represents their non-file content.
			//   For packaged files that reflect files directly from disk, the
			//   'getFileHashes' method tracks their content already.
			//   It is important that the keys of the $packageFiles['files'] array
			//   are preserved, as they do affect the module output.
			foreach ( $packageFiles['files'] as $fileName => $fileInfo ) {
				$packageSummaries[$fileName] =
					$fileInfo['definitionSummary'] ?? $fileInfo['content'] ?? null;
			}
		}

		$scriptFiles = $this->getScriptFiles( $context );
		$scriptSummaries = [];
		foreach ( $scriptFiles as $fileName => $fileInfo ) {
			$scriptSummaries[$fileName] =
				$fileInfo['definitionSummary'] ?? $fileInfo['content'] ?? null;
		}

		$summary[] = [
			'options' => $options,
			'packageFiles' => $packageSummaries,
			'scripts' => $scriptSummaries,
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
	 * @param string|FilePath $path
	 * @return string
	 */
	protected function getPath( $path ) {
		if ( $path instanceof FilePath ) {
			return $path->getPath();
		}

		return $path;
	}

	/**
	 * @param string|FilePath $path
	 * @return string
	 */
	protected function getLocalPath( $path ) {
		if ( $path instanceof FilePath ) {
			if ( $path->getLocalBasePath() !== null ) {
				return $path->getLocalPath();
			}
			$path = $path->getPath();
		}

		return "{$this->localBasePath}/$path";
	}

	/**
	 * @param string|FilePath $path
	 * @return string
	 */
	protected function getRemotePath( $path ) {
		if ( $path instanceof FilePath ) {
			if ( $path->getRemoteBasePath() !== null ) {
				return $path->getRemotePath();
			}
			$path = $path->getPath();
		}

		if ( $this->remoteBasePath === '/' ) {
			return "/$path";
		} else {
			return "{$this->remoteBasePath}/$path";
		}
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
	 *
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
	 * Collate style file paths by 'media' option (or 'all' if 'media' is not set)
	 *
	 * @param array $list List of file paths in any combination of index/path
	 *     or path/options pairs
	 * @return string[][] List of collated file paths
	 */
	private static function collateStyleFilesByMedia( array $list ) {
		$collatedFiles = [];
		foreach ( $list as $key => $value ) {
			if ( is_int( $key ) ) {
				// File name as the value
				$collatedFiles['all'][] = $value;
			} elseif ( is_array( $value ) ) {
				// File name as the key, options array as the value
				$optionValue = $value['media'] ?? 'all';
				$collatedFiles[$optionValue][] = $key;
			}
		}
		return $collatedFiles;
	}

	/**
	 * Get a list of element that match a key, optionally using a fallback key.
	 *
	 * @param array[] $list List of lists to select from
	 * @param string $key Key to look for in $list
	 * @param string|null $fallback Key to look for in $list if $key doesn't exist
	 * @return array List of elements from $list which matched $key or $fallback,
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
	 * Get script file paths for this module, in order of proper execution.
	 *
	 * @param Context $context
	 * @return array An array of file info arrays as returned by expandFileInfo()
	 */
	private function getScriptFiles( Context $context ): array {
		// List in execution order: scripts, languageScripts, skinScripts, debugScripts.
		// Documented at MediaWiki\MainConfigSchema::ResourceModules.
		$filesByCategory = [
			'scripts' => $this->scripts,
			'languageScripts' => $this->getLanguageScripts( $context->getLanguage() ),
			'skinScripts' => self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' ),
		];
		if ( $context->getDebug() ) {
			$filesByCategory['debugScripts'] = $this->debugScripts;
		}

		$expandedFiles = [];
		foreach ( $filesByCategory as $category => $files ) {
			foreach ( $files as $key => $fileInfo ) {
				$expandedFileInfo = $this->expandFileInfo( $context, $fileInfo, "$category\[$key]" );
				$expandedFiles[$expandedFileInfo['name']] = $expandedFileInfo;
			}
		}

		return $expandedFiles;
	}

	/**
	 * Get the set of language scripts for the given language,
	 * possibly using a fallback language.
	 *
	 * @param string $lang
	 * @return array<int,string|FilePath> File paths
	 */
	private function getLanguageScripts( string $lang ): array {
		$scripts = self::tryForKey( $this->languageScripts, $lang );
		if ( $scripts ) {
			return $scripts;
		}

		// Optimization: Avoid initialising and calling into language services
		// for the majority of modules that don't use this option.
		if ( $this->languageScripts ) {
			$fallbacks = MediaWikiServices::getInstance()
				->getLanguageFallback()
				->getAll( $lang, LanguageFallback::MESSAGES );
			foreach ( $fallbacks as $lang ) {
				$scripts = self::tryForKey( $this->languageScripts, $lang );
				if ( $scripts ) {
					return $scripts;
				}
			}
		}

		return [];
	}

	public function setSkinStylesOverride( array $moduleSkinStyles ): void {
		$moduleName = $this->getName();
		foreach ( $moduleSkinStyles as $skinName => $overrides ) {
			// If a module provides overrides for a skin, and that skin also provides overrides
			// for the same module, then the module has precedence.
			if ( isset( $this->skinStyles[$skinName] ) ) {
				continue;
			}

			// If $moduleName in ResourceModuleSkinStyles is preceded with a '+', the defined style
			// files will be added to 'default' skinStyles, otherwise 'default' will be ignored.
			if ( isset( $overrides[$moduleName] ) ) {
				$paths = (array)$overrides[$moduleName];
				$styleFiles = [];
			} elseif ( isset( $overrides['+' . $moduleName] ) ) {
				$paths = (array)$overrides['+' . $moduleName];
				$styleFiles = isset( $this->skinStyles['default'] ) ?
					(array)$this->skinStyles['default'] :
					[];
			} else {
				continue;
			}

			// Add new file paths, remapping them to refer to our directories and not use settings
			// from the module we're modifying, which come from the base definition.
			[ $localBasePath, $remoteBasePath ] = self::extractBasePaths( $overrides );

			foreach ( $paths as $path ) {
				$styleFiles[] = new FilePath( $path, $localBasePath, $remoteBasePath );
			}

			$this->skinStyles[$skinName] = $styleFiles;
		}
	}

	/**
	 * Get a list of file paths for all styles in this module, in order of proper inclusion.
	 *
	 * @internal Exposed only for use by structure phpunit tests.
	 * @param Context $context
	 * @return array<string,array<int,string|FilePath>> Map from media type to list of file paths
	 */
	public function getStyleFiles( Context $context ) {
		return array_merge_recursive(
			self::collateStyleFilesByMedia( $this->styles ),
			self::collateStyleFilesByMedia(
				self::tryForKey( $this->skinStyles, $context->getSkin(), 'default' )
			)
		);
	}

	/**
	 * Get a list of file paths for all skin styles in the module used by
	 * the skin.
	 *
	 * @param string $skinName The name of the skin
	 * @return array A list of file paths collated by media type
	 */
	protected function getSkinStyleFiles( $skinName ) {
		return self::collateStyleFilesByMedia(
			self::tryForKey( $this->skinStyles, $skinName )
		);
	}

	/**
	 * Get a list of file paths for all skin style files in the module,
	 * for all available skins.
	 *
	 * @return array A list of file paths collated by media type
	 */
	protected function getAllSkinStyleFiles() {
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
		$styleFiles = [];

		$internalSkinNames = array_keys( $skinFactory->getInstalledSkins() );
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
	 * Get all style files and all skin style files used by this module.
	 *
	 * @return array
	 */
	public function getAllStyleFiles() {
		$collatedStyleFiles = array_merge_recursive(
			self::collateStyleFilesByMedia( $this->styles ),
			$this->getAllSkinStyleFiles()
		);

		$result = [];

		foreach ( $collatedStyleFiles as $styleFiles ) {
			foreach ( $styleFiles as $styleFile ) {
				$result[] = $this->getLocalPath( $styleFile );
			}
		}

		return $result;
	}

	/**
	 * Read the contents of a list of CSS files and remap and concatenate these.
	 *
	 * @internal This is considered a private method. Exposed for internal use by WebInstallerOutput.
	 * @param array<string,array<int,string|FilePath>> $styles Map of media type to file paths
	 * @param Context $context
	 * @return array<string,string> Map of combined CSS code, keyed by media type
	 */
	public function readStyleFiles( array $styles, Context $context ) {
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
	 * @param string|FilePath $path Path of style file to read
	 * @param Context $context
	 * @return string CSS code
	 */
	protected function readStyleFile( $path, Context $context ) {
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
	 * @param string $style CSS or LESS code
	 * @param string $styleLang Language of $style code ('css' or 'less')
	 * @param string|FilePath $path Path to code file, used for resolving relative file paths
	 * @param Context $context
	 * @return string Processed CSS code
	 */
	protected function processStyle( $style, $styleLang, $path, Context $context ) {
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
			$this->hasGeneratedStyles = true;
		}

		$localDir = dirname( $localPath );
		$remoteDir = dirname( $remotePath );
		// Get and register local file references
		$localFileRefs = CSSMin::getLocalFileReferences( $style, $localDir );
		foreach ( $localFileRefs as $file ) {
			if ( is_file( $file ) ) {
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
	 * @param Context $context
	 * @return bool
	 */
	public function getFlip( Context $context ) {
		return $context->getDirection() === 'rtl' && !$this->noflip;
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
	 * Compile a LESS string into CSS.
	 *
	 * Keeps track of all used files and adds them to localFileRefs.
	 *
	 * @since 1.35
	 * @param string $style LESS source to compile
	 * @param string $stylePath File path of LESS source, used for resolving relative file paths
	 * @param Context $context Context in which to generate script
	 * @return string CSS source
	 */
	protected function compileLessString( $style, $stylePath, Context $context ) {
		static $cache;
		// @TODO: dependency injection
		if ( !$cache ) {
			$cache = MediaWikiServices::getInstance()->getObjectCacheFactory()
				->getLocalServerInstance( CACHE_HASH );
		}

		$skinName = $context->getSkin();
		$skinImportPaths = ExtensionRegistry::getInstance()->getAttribute( 'SkinLessImportPaths' );
		$importDirs = [];
		if ( isset( $skinImportPaths[ $skinName ] ) ) {
			$importDirs[] = $skinImportPaths[ $skinName ];
		}

		$vars = $this->getLessVars( $context );
		// Construct a cache key from a hash of the LESS source, and a hash digest
		// of the LESS variables and import dirs used for compilation.
		ksort( $vars );
		$compilerParams = [
			'vars' => $vars,
			'importDirs' => $importDirs,
			// CodexDevelopmentDir affects import path mapping in ResourceLoader::getLessCompiler(),
			// so take that into account too
			'codexDevDir' => $this->getConfig()->get( MainConfigNames::CodexDevelopmentDir )
		];
		$key = $cache->makeGlobalKey(
			'resourceloader-less',
			'v1',
			hash( 'md4', $style ),
			hash( 'md4', serialize( $compilerParams ) )
		);

		// If we got a cached value, we have to validate it by getting a checksum of all the
		// files that were loaded by the parser and ensuring it matches the cached entry's.
		$data = $cache->get( $key );
		if (
			!$data ||
			$data['hash'] !== FileContentsHasher::getFileContentsHash( $data['files'] )
		) {
			$compiler = $context->getResourceLoader()->getLessCompiler( $vars, $importDirs );

			$css = $compiler->parse( $style, $stylePath )->getCss();
			// T253055: store the implicit dependency paths in a form relative to any install
			// path so that multiple version of the application can share the cache for identical
			// less stylesheets. This also avoids churn during application updates.
			$files = $compiler->getParsedFiles();
			$data = [
				'css'   => $css,
				'files' => Module::getRelativePaths( $files ),
				'hash'  => FileContentsHasher::getFileContentsHash( $files )
			];
			$cache->set( $key, $data, $cache::TTL_DAY );
		}

		foreach ( Module::expandRelativePaths( $data['files'] ) as $path ) {
			$this->localFileRefs[] = $path;
		}

		return $data['css'];
	}

	/**
	 * Get content of named templates for this module.
	 *
	 * @return array<string,string> Templates mapping template alias to content
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
	 * @param Context $context
	 * @return array|null Array of arrays as returned by expandFileInfo(), with the key being
	 *   the file name, or null if this is not a package file module.
	 * @phan-return array{main:?string,files:array[]}|null
	 */
	private function expandPackageFiles( Context $context ) {
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
			$expanded = $this->expandFileInfo( $context, $fileInfo, "packageFiles[$key]" );
			$fileName = $expanded['name'];
			if ( !empty( $expanded['main'] ) ) {
				unset( $expanded['main'] );
				$type = $expanded['type'];
				$mainFile = $fileName;
				if ( $type !== 'script' && $type !== 'script-vue' ) {
					$msg = "Main file in package must be of type 'script', module " .
						"'{$this->getName()}', main file '{$mainFile}' is '{$type}'.";
					$this->getLogger()->error( $msg );
					throw new LogicException( $msg );
				}
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
	 * Process a file info array as specified in configuration or extension.json,
	 * expanding shortcuts and callbacks.
	 *
	 * @see MainConfigSchema::ResourceModules
	 *
	 * @param Context $context
	 * @param array|string|FilePath $fileInfo
	 * @param string $debugKey
	 * @return array An associative array with the following keys:
	 *   - name: (string) The filename relative to the module base. This is unique only within
	 *     the context of the current module. It may be a virtual name.
	 *   - type: (string) May be 'script', 'script-vue', 'data' or 'text'
	 *   - filePath: (FilePath) The FilePath object which should be used to load the content.
	 *     This will be absent if the content was loaded another way.
	 *   - virtualFilePath: (FilePath) A FilePath object for a virtual path which doesn't actually
	 *     exist. This is used for source map generation. Optional.
	 *   - versionFilePath: (FilePath) A FilePath object which is the ultimate source of a
	 *     generated file. The timestamp and contents will be used for version generation.
	 *     Generated by the callback specified in versionCallback. Optional.
	 *   - content: (string|mixed) If the 'type' element is 'script', this is a string containing
	 *     JS code, being the contents of the script file. For any other type, this contains data
	 *     which will be JSON serialized. Optional, if not set, it will be set in readFileInfo().
	 *   - callback: (callable) A callback to call to obtain the contents. This will be set if the
	 *     version callback was present in the input, indicating that the callback is expensive.
	 *   - callbackParam: (array) The parameters to be passed to the callback.
	 *   - definitionSummary: (array) The data returned by the version callback.
	 *   - main: (bool) Whether the file is the main file of the package.
	 */
	private function expandFileInfo( Context $context, $fileInfo, $debugKey ) {
		if ( is_string( $fileInfo ) ) {
			// Inline common case
			return [
				'name' => $fileInfo,
				'type' => self::getPackageFileType( $fileInfo ),
				'filePath' => new FilePath( $fileInfo, $this->localBasePath, $this->remoteBasePath )
			];
		} elseif ( $fileInfo instanceof FilePath ) {
			$fileInfo = [
				'name' => $fileInfo->getPath(),
				'file' => $fileInfo
			];
		} elseif ( !is_array( $fileInfo ) ) {
			$msg = "Invalid type in $debugKey for module '{$this->getName()}', " .
				"must be array, string or FilePath";
			$this->getLogger()->error( $msg );
			throw new LogicException( $msg );
		}
		if ( !isset( $fileInfo['name'] ) ) {
			$msg = "Missing 'name' key in $debugKey for module '{$this->getName()}'";
			$this->getLogger()->error( $msg );
			throw new LogicException( $msg );
		}
		$fileName = $this->getPath( $fileInfo['name'] );

		// Infer type from alias if needed
		$type = $fileInfo['type'] ?? self::getPackageFileType( $fileName );
		$expanded = [
			'name' => $fileName,
			'type' => $type
		];
		if ( !empty( $fileInfo['main'] ) ) {
			$expanded['main'] = true;
		}

		// Perform expansions (except 'file' and 'callback'), creating one of these keys:
		// - 'content': literal value.
		// - 'filePath': content to be read from a file.
		// - 'callback': content computed by a callable.
		if ( isset( $fileInfo['content'] ) ) {
			$expanded['content'] = $fileInfo['content'];
		} elseif ( isset( $fileInfo['file'] ) ) {
			$expanded['filePath'] = $this->makeFilePath( $fileInfo['file'] );
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
				if ( $callbackResult instanceof FilePath ) {
					$callbackResult->initBasePaths( $this->localBasePath, $this->remoteBasePath );
					$expanded['versionFilePath'] = $callbackResult;
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
				if ( $callbackResult instanceof FilePath ) {
					$callbackResult->initBasePaths( $this->localBasePath, $this->remoteBasePath );
					$expanded['filePath'] = $callbackResult;
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
			foreach ( $fileInfo['config'] as $configKey => $var ) {
				$expandedConfig[ is_numeric( $configKey ) ? $var : $configKey ] = $this->getConfig()->get( $var );
			}
			$expanded['content'] = $expandedConfig;
		} elseif ( !empty( $fileInfo['main'] ) ) {
			// [ 'name' => 'foo.js', 'main' => true ] is shorthand
			$expanded['filePath'] = $this->makeFilePath( $fileName );
		} else {
			$msg = "Incomplete definition for module '{$this->getName()}', file '{$fileName}'. "
				. "One of 'file', 'content', 'callback', or 'config' must be set.";
			$this->getLogger()->error( $msg );
			throw new LogicException( $msg );
		}
		if ( !isset( $expanded['filePath'] ) ) {
			$expanded['virtualFilePath'] = $this->makeFilePath( $fileName );
		}
		return $expanded;
	}

	/**
	 * Cast a FilePath or string to a FilePath
	 *
	 * @param FilePath|string $path
	 * @return FilePath
	 */
	private function makeFilePath( $path ): FilePath {
		if ( $path instanceof FilePath ) {
			return $path;
		} elseif ( is_string( $path ) ) {
			return new FilePath( $path, $this->localBasePath, $this->remoteBasePath );
		} else {
			throw new InvalidArgumentException( '$path must be either FilePath or string' );
		}
	}

	/**
	 * Resolve the package files definition and generate the content of each package file.
	 *
	 * @param Context $context
	 * @return array|null Package files data structure, see Module::getScript()
	 */
	public function getPackageFiles( Context $context ) {
		if ( $this->packageFiles === null ) {
			return null;
		}
		$hash = $context->getHash();
		if ( isset( $this->fullyExpandedPackageFiles[ $hash ] ) ) {
			return $this->fullyExpandedPackageFiles[ $hash ];
		}
		$expandedPackageFiles = $this->expandPackageFiles( $context ) ?? [];

		foreach ( $expandedPackageFiles['files'] as &$fileInfo ) {
			$this->readFileInfo( $context, $fileInfo );
		}

		$this->fullyExpandedPackageFiles[ $hash ] = $expandedPackageFiles;
		return $expandedPackageFiles;
	}

	/**
	 * Given a file info array as returned by expandFileInfo(), expand the file paths and
	 * remaining callbacks, ensuring that the 'content' element is populated. Modify
	 * the array by reference, removing intermediate data such as callback parameters.
	 *
	 * @param Context $context
	 * @param array &$fileInfo
	 */
	private function readFileInfo( Context $context, array &$fileInfo ) {
		// Turn any 'filePath' or 'callback' key into actual 'content',
		// and remove the key after that. The callback could return a
		// FilePath object; if that happens, fall through to the 'filePath'
		// handling.
		if ( !isset( $fileInfo['content'] ) && isset( $fileInfo['callback'] ) ) {
			$callbackResult = ( $fileInfo['callback'] )(
				$context,
				$this->getConfig(),
				$fileInfo['callbackParam']
			);
			if ( $callbackResult instanceof FilePath ) {
				// Fall through to the filePath handling code below
				$fileInfo['filePath'] = $callbackResult;
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
				$content = json_decode( $content, false, 512, JSON_THROW_ON_ERROR );
			}
			$fileInfo['content'] = $content;
		}
		if ( $fileInfo['type'] === 'script-vue' ) {
			try {
				$parsedComponent = $this->getVueComponentParser()->parse(
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
					$fileInfo['content'],
					[ 'minifyTemplate' => !$context->getDebug() ]
				);
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception $e ) {
				$msg = "Error parsing file '{$fileInfo['name']}' in module '{$this->getName()}': " .
					$e->getMessage();
				$this->getLogger()->error( $msg );
				throw new RuntimeException( $msg );
			}
			$encodedTemplate = json_encode( $parsedComponent['template'] );
			if ( $context->getDebug() ) {
				// Replace \n (backslash-n) with space + backslash-n + backslash-newline in debug mode
				// The \n has to be preserved to prevent Vue parser issues (T351771)
				// We only replace \n if not preceded by a backslash, to avoid breaking '\\n'
				$encodedTemplate = preg_replace( '/(?<!\\\\)\\\\n/', " \\n\\\n", $encodedTemplate );
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
		if ( !isset( $fileInfo['content'] ) ) {
			// This should not be possible due to validation in expandFileInfo()
			$msg = "Unable to resolve contents for file {$fileInfo['name']}";
			$this->getLogger()->error( $msg );
			throw new RuntimeException( $msg );
		}

		// Not needed for client response, exists for use by getDefinitionSummary().
		unset( $fileInfo['definitionSummary'] );
		// Not needed for client response, used by callbacks only.
		unset( $fileInfo['callbackParam'] );
	}

	/**
	 * Take an input string and remove the UTF-8 BOM character if present
	 *
	 * We need to remove these after reading a file, because we concatenate our files and
	 * the BOM character is not valid in the middle of a string.
	 * We already assume UTF-8 everywhere, so this should be safe.
	 *
	 * @param string $input
	 * @return string Input minus the initial BOM char
	 */
	protected function stripBom( $input ) {
		if ( str_starts_with( $input, "\xef\xbb\xbf" ) ) {
			return substr( $input, 3 );
		}
		return $input;
	}
}
