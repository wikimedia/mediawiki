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
 */

namespace MediaWiki\ResourceLoader;

use ExtensionRegistry;
use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\Html\Html;
use MediaWiki\Html\HtmlJsCode;
use MediaWiki\MainConfigNames;

/**
 * Module for codex that has direction-specific style files and a static helper
 * function for embedding icons in package modules. This module also contains
 * logic to support code-splitting (aka tree-shaking) of the Codex library to
 * return only a subset of component JS and/or CSS files.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class CodexModule extends FileModule {
	protected const CODEX_LIBRARY_DIR = 'resources/lib/codex/';
	private const CODEX_MODULE_DEPENDENCIES = [ 'vue' ];

	/** @var array<string,string>|null */
	private static ?array $themeMap = null;

	/**
	 * Cache for getCodexFiles(). Maps manifest file paths to outputs. For more information about
	 * the structure of the outputs, see the documentation comment for getCodexFiles().
	 *
	 * This array looks like:
	 *     [
	 *         '/path/to/manifest.json' => [ 'files' => [ ... ], 'components' => [ ... ] ],
	 *         '/path/to/manifest-rtl.json' => [ 'files' => [ ... ], 'components' => [ ... ] ],
	 *         ...
	 *     ]
	 *
	 * @var array<string,array{files:array<string,array{styles:string[],dependencies:string[]}>,components:array<string,string>}>
	 */
	private static array $codexFilesCache = [];

	/**
	 * Names of the requested components. Comes directly from the 'codexComponents' property in the
	 * module definition.
	 *
	 * @var string[]
	 */
	private array $codexComponents = [];

	private bool $isStyleOnly = false;
	private bool $isScriptOnly = false;
	private bool $codexFullLibrary = false;
	private bool $setupComplete = false;

	/**
	 * @param array $options [optional]
	 *  - codexComponents: array of Codex components to include
	 *  - codexFullLibrary: whether to load the entire Codex library
	 *  - codexStyleOnly: whether to include only style files
	 *  - codexScriptOnly: whether to include only script files
	 * @param string|null $localBasePath [optional]
	 * @param string|null $remoteBasePath [optional]
	 */
	public function __construct( array $options = [], $localBasePath = null, $remoteBasePath = null ) {
		if ( isset( $options[ 'codexComponents' ] ) ) {
			if ( !is_array( $options[ 'codexComponents' ] ) || count( $options[ 'codexComponents' ] ) === 0 ) {
				throw new InvalidArgumentException(
					"All 'codexComponents' properties in your module definition file " .
					'must either be omitted or be an array with at least one component name'
				);
			}

			$this->codexComponents = $options[ 'codexComponents' ];
		}

		if ( isset( $options['codexFullLibrary'] ) ) {
			if ( isset( $options[ 'codexComponents' ] ) ) {
				throw new InvalidArgumentException(
					'ResourceLoader modules using the CodexModule class cannot ' .
					"use both 'codexFullLibrary' and 'codexComponents' options. " .
					"Instead, use 'codexFullLibrary' to load the entire library" .
					"or 'codexComponents' to load a subset of components."
				);
			}

			$this->codexFullLibrary = $options[ 'codexFullLibrary' ];
		}

		if ( isset( $options[ 'codexStyleOnly' ] ) ) {
			$this->isStyleOnly = $options[ 'codexStyleOnly' ];
		}

		if ( isset( $options[ 'codexScriptOnly' ] ) ) {
			$this->isScriptOnly = $options[ 'codexScriptOnly' ];
		}

		// Normalize $options[ 'dependencies' ] to always make it an array. It could be unset, or
		// it could be a string.
		$options[ 'dependencies' ] = (array)( $options[ 'dependencies' ] ?? [] );

		// Unlike when the entire @wikimedia/codex module is depended on, when the codexComponents
		// option is used, Vue is not automatically included, though it is required. Add it and
		// other dependencies here.
		if ( !$this->isStyleOnly && count( $this->codexComponents ) > 0 ) {
			$options[ 'dependencies' ] = array_unique( array_merge(
				$options[ 'dependencies' ],
				self::CODEX_MODULE_DEPENDENCIES
			) );
		}

		if ( in_array( '@wikimedia/codex', $options[ 'dependencies' ] ) ) {
			throw new InvalidArgumentException(
				'ResourceLoader modules using the CodexModule class cannot ' .
				"list the '@wikimedia/codex' module as a dependency. " .
				"Instead, use 'codexComponents' to require a subset of components."
			);
		}

		parent::__construct( $options, $localBasePath, $remoteBasePath );
	}

	/**
	 * Retrieve the specified icon definitions from codex-icons.json. Intended as a convenience
	 * function to be used in packageFiles definitions.
	 *
	 * Example:
	 *     "packageFiles": [
	 *         {
	 *             "name": "icons.json",
	 *             "callback": "MediaWiki\\ResourceLoader\\CodexModule::getIcons",
	 *             "callbackParam": [
	 *                 "cdxIconClear",
	 *                 "cdxIconTrash"
	 *             ]
	 *         }
	 *     ]
	 *
	 * @param Context $context
	 * @param Config $config
	 * @param string[] $iconNames Names of icons to fetch
	 * @return array
	 */
	public static function getIcons( Context $context, Config $config, array $iconNames = [] ): array {
		global $IP;
		static $allIcons = null;
		if ( $allIcons === null ) {
			$allIcons = json_decode( file_get_contents( "$IP/resources/lib/codex-icons/codex-icons.json" ), true );
		}
		return array_intersect_key( $allIcons, array_flip( $iconNames ) );
	}

	// These 3 public methods are the entry points to this class; depending on the
	// circumstances any one of these might be called first.

	public function getPackageFiles( Context $context ) {
		$this->setupCodex( $context );
		return parent::getPackageFiles( $context );
	}

	public function getStyleFiles( Context $context ) {
		$this->setupCodex( $context );
		return parent::getStyleFiles( $context );
	}

	protected function processStyle( $style, $styleLang, $path, Context $context ) {
		$pathAsString = $path instanceof FilePath ? $path->getPath() : $path;
		if ( str_starts_with( $pathAsString, static::CODEX_LIBRARY_DIR ) ) {
			// This is a Codex style file, don't do any processing.
			// We need to avoid CSSJanus flipping in particular, because we're using RTL-specific
			// files instead. Note that we're bypassing all of processStyle() when we really just
			// care about bypassing flipping; that's fine for now, but could be a problem if
			// processStyle() is ever expanded to do more than Less compilation, RTL flipping and
			// image URL remapping.
			return $style;
		}

		return parent::processStyle( $style, $styleLang, $path, $context );
	}

	public function getDefinitionSummary( Context $context ) {
		$this->setupCodex( $context );
		return parent::getDefinitionSummary( $context );
	}

	public function supportsURLLoading() {
		// We need to override this explicitly. The parent method might return true if there are
		// no 'packageFiles' set in the module definition and they're all generated by us.
		// It's possible that this "should" return true in some circumstances (e.g. style-only use
		// of CodexModule combined with non-packageFiles scripts), but those are edge cases that
		// we're choosing not to support here.
		return false;
	}

	/**
	 * Get the theme to use based on the current skin.
	 *
	 * @param Context $context
	 * @return string Name of the current theme
	 */
	private function getTheme( Context $context ): string {
		if ( self::$themeMap === null ) {
			// Initialize self::$themeMap
			$skinCodexThemes = ExtensionRegistry::getInstance()->getAttribute( 'SkinCodexThemes' );
			self::$themeMap = [ 'default' => 'wikimedia-ui' ] + $skinCodexThemes;
		}
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
		return self::$themeMap[ $context->getSkin() ] ?? self::$themeMap[ 'default' ];
	}

	/**
	 * Decide which manifest file to use, based on the theme and the direction (LTR or RTL).
	 *
	 * @param Context $context
	 * @return string Name of the manifest file to use
	 */
	private function getManifestFilePath( Context $context ): string {
		$themeManifestNames = [
			'wikimedia-ui' => [
				'ltr' => 'manifest.json',
				'rtl' => 'manifest-rtl.json',
			],
			'wikimedia-ui-legacy' => [
				'ltr' => 'manifest.json',
				'rtl' => 'manifest-rtl.json',
			],
			'experimental' => [
				'ltr' => 'manifest.json',
				'rtl' => 'manifest-rtl.json',
			]
		];

		$theme = $this->getTheme( $context );
		$direction = $context->getDirection();
		if ( !isset( $themeManifestNames[ $theme ] ) ) {
			throw new InvalidArgumentException( "Unknown Codex theme $theme" );
		}
		$manifestFile = $themeManifestNames[ $theme ][ $direction ];
		$manifestFilePath = MW_INSTALL_PATH . '/' . static::CODEX_LIBRARY_DIR . 'modules/' . $manifestFile;
		return $manifestFilePath;
	}

	/**
	 * Get information about all available Codex files.
	 *
	 * This transforms the Codex manifest to a more useful format, and caches it so that different
	 * instances of this class don't each parse the manifest separately.
	 *
	 * The returned data structure contains a 'files' key with dependency information about every
	 * file (both entry point and non-entry point files), and a 'component' key that is an array
	 * mapping component names to file names. The full data structure looks like this:
	 *
	 *     [
	 *         'files' => [
	 *             'CdxButtonGroup.js' => [
	 *                 'styles' => [ 'CdxButtonGroup.css' ],
	 *                 'dependencies' => [ 'CdxButton.js', 'buttonHelpers.js' ]
	 *             ],
	 *             // all files are listed here, both entry point and non-entry point files
	 *         ],
	 *         'components' => [
	 *             'CdxButtonGroup' => 'CdxButtonGroup.js',
	 *             // only entry point files are listed here
	 *         ]
	 *     ]
	 *
	 * @param Context $context
	 * @return array{files:array<string,array{styles:string[],dependencies:string[]}>,components:array<string,string>}
	 */
	private function getCodexFiles( Context $context ): array {
		$manifestFilePath = $this->getManifestFilePath( $context );

		if ( isset( self::$codexFilesCache[ $manifestFilePath ] ) ) {
			return self::$codexFilesCache[ $manifestFilePath ];
		}

		$manifest = json_decode( file_get_contents( $manifestFilePath ), true );
		$files = [];
		$components = [];
		foreach ( $manifest as $key => $val ) {
			$files[ $val[ 'file' ] ] = [
				'styles' => $val[ 'css' ] ?? [],
				// $val['imports'] is expressed as manifest keys, transform those to file names
				'dependencies' => array_map( static function ( $manifestKey ) use ( $manifest ) {
					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					return $manifest[ $manifestKey ][ 'file' ];
				}, $val[ 'imports' ] ?? [] )
			];

			$isComponent = isset( $val[ 'isEntry' ] ) && $val[ 'isEntry' ];
			if ( $isComponent ) {
				$fileInfo = pathinfo( $val[ 'file' ] );
				// $fileInfo[ 'filename' ] is the file name without the extension.
				// This is the component name (e.g. CdxButton.cjs -> CdxButton).
				$components[ $fileInfo[ 'filename' ] ] = $val[ 'file' ];
			}
		}

		self::$codexFilesCache[ $manifestFilePath ] = [ 'files' => $files, 'components' => $components ];
		return self::$codexFilesCache[ $manifestFilePath ];
	}

	/**
	 * There are several different use-cases for CodexModule. We may be dealing
	 * with:
	 *
	 * - A CSS-only or JS & CSS module for the entire component library
	 * - An otherwise standard module that needs one or more Codex icons
	 * - A CSS-only or CSS-and-JS module that has opted-in to Codex's
	 *   tree-shaking feature by specifying the "codexComponents" option
	 *
	 * Regardless of the kind of CodexModule we are dealing with, some kind of
	 * one-time setup operation may need to be performed.
	 *
	 * In the case of a full-library module, we need to ensure that the correct
	 * theme- and direction-specific CSS file is used.
	 *
	 * In the case of a tree-shaking module, we need to ensure that the CSS
	 * and/or JS files for the specified components (as well as all
	 * dependencies) are added to the module's packageFiles.
	 *
	 * @param Context $context
	 */
	private function setupCodex( Context $context ) {
		if ( $this->setupComplete ) {
			return;
		}

		// If we are tree-shaking, add component-specific JS/CSS files
		if ( count( $this->codexComponents ) > 0 ) {
			$this->addComponentFiles( $context );
		}

		// If we want to load the entire Codex library (no tree shaking)
		if ( $this->codexFullLibrary ) {
			$this->loadFullCodexLibrary( $context );

		}

		$this->setupComplete = true;
	}

	/**
	 * Resolve the dependencies for a list of files, return flat arrays of both scripts and styles.
	 * The returned arrays include the files in $requestedFiles, plus their dependencies, their
	 * dependencies' dependencies, etc. The returned arrays are ordered in the order that the files
	 * should be loaded in; in other words, the files are ordered such that each file appears after
	 * all of its dependencies.
	 *
	 * @param string[] $requestedFiles File names whose dependencies to resolve
	 * @phpcs:ignore Generic.Files.LineLength, MediaWiki.Commenting.FunctionComment.MissingParamName
	 * @param array{files:array<string,array{styles:string[],dependencies:string[]}>,components:array<string,string>} $codexFiles
	 *   Data structure returned by getCodexFiles()
	 * @return array{scripts:string[],styles:string[]} Resolved dependencies, with script and style
	 *   files listed separately.
	 */
	private function resolveDependencies( array $requestedFiles, array $codexFiles ) {
		$scripts = [];
		$styles = [];

		$gatherDependencies = static function ( $file ) use ( &$scripts, &$styles, $codexFiles, &$gatherDependencies ) {
			foreach ( $codexFiles[ 'files' ][ $file ][ 'dependencies' ] as $dep ) {
				if ( !in_array( $dep, $scripts ) ) {
					$gatherDependencies( $dep );
				}
			}
			$scripts[] = $file;
			$styles = array_merge( $styles, $codexFiles[ 'files' ][ $file ][ 'styles' ] );
		};

		foreach ( $requestedFiles as $requestedFile ) {
			$gatherDependencies( $requestedFile );
		}

		return [ 'scripts' => $scripts, 'styles' => $styles ];
	}

	/**
	 * For Codex modules that rely on tree-shaking, this method determines
	 * which CSS and/or JS files need to be included by consulting the
	 * appropriate manifest file.
	 *
	 * @param Context $context
	 */
	private function addComponentFiles( Context $context ) {
		$remoteBasePath = $this->getConfig()->get( MainConfigNames::ResourceBasePath );
		$codexFiles = $this->getCodexFiles( $context );

		$requestedFiles = array_map( static function ( $component ) use ( $codexFiles ) {
			if ( !isset( $codexFiles[ 'components' ][ $component ] ) ) {
				throw new InvalidArgumentException(
					"\"$component\" is not an export of Codex and cannot be included in the \"codexComponents\" array."
				);
			}
			return $codexFiles[ 'components' ][ $component ];
		}, $this->codexComponents );

		[ 'scripts' => $scripts, 'styles' => $styles ] = $this->resolveDependencies( $requestedFiles, $codexFiles );

		// Add the CSS files to the module's package file (unless this is a script-only module)
		if ( !$this->isScriptOnly ) {
			foreach ( $styles as $fileName ) {
				$this->styles[] = new FilePath( static::CODEX_LIBRARY_DIR . 'modules/' .
					$fileName, MW_INSTALL_PATH, $remoteBasePath );
			}
		}

		// Add the JS files to the module's package file (unless this is a style-only module).
		if ( !$this->isStyleOnly ) {
			$exports = [];
			foreach ( $this->codexComponents as $component ) {
				$componentFile = $codexFiles[ 'components' ][ $component ];
				$exports[ $component ] = new HtmlJsCode(
					'require( ' . Html::encodeJsVar( "./_codex/$componentFile" ) . ' )'
				);
			}

			// Add a synthetic top-level "exports" file
			$syntheticExports = Html::encodeJsVar( HtmlJsCode::encodeObject( $exports ) );

			// Proxy the synthetic exports object so that we can throw a useful error if a component
			// is not defined in the module definition
			$proxiedSyntheticExports = <<<JAVASCRIPT
			module.exports = new Proxy( $syntheticExports, {
				get( target, prop ) {
					if ( !( prop in target ) ) {
						throw new Error(
							`Codex component "\${prop}" ` +
							'is not listed in the "codexComponents" array ' +
							'of the "{$this->getName()}" module in your module definition file'
						);
					}
					return target[ prop ];
				}
			} );
			JAVASCRIPT;

			$this->packageFiles[] = [
				'name' => 'codex.js',
				'content' => $proxiedSyntheticExports
			];

			// Add each of the referenced scripts to the package
			foreach ( $scripts as $fileName ) {
				$this->packageFiles[] = [
					'name' => "_codex/$fileName",
					'file' => new FilePath(
						static::CODEX_LIBRARY_DIR . 'modules/' . $fileName,
						MW_INSTALL_PATH, $remoteBasePath
					)
				];
			}
		}
	}

	/**
	 * For loading the entire Codex library, rather than a subset module of it.
	 *
	 * @param Context $context
	 */
	private function loadFullCodexLibrary( Context $context ) {
		$remoteBasePath = $this->getConfig()->get( MainConfigNames::ResourceBasePath );

		// Add all Codex JS files to the module's package
		if ( !$this->isStyleOnly ) {
			$this->packageFiles[] = [
				'name' => 'codex.js',
				'file' => new FilePath(
					static::CODEX_LIBRARY_DIR . 'codex.umd.cjs',
					MW_INSTALL_PATH, $remoteBasePath
				)
			];
		}

		// Add all Codex CSS files to the module's package
		if ( !$this->isScriptOnly ) {
			// Theme-specific + direction style files
			$themeStyles = [
				'wikimedia-ui' => [
					'ltr' => 'codex.style.css',
					'rtl' => 'codex.style-rtl.css'
				],
				'wikimedia-ui-legacy' => [
					'ltr' => 'codex.style.css',
					'rtl' => 'codex.style-rtl.css'
				],
				'experimental' => [
					'ltr' => 'codex.style.css',
					'rtl' => 'codex.style-rtl.css'
				]
			];

			$theme = $this->getTheme( $context );
			$direction = $context->getDirection();
			$styleFile = $themeStyles[ $theme ][ $direction ];
			$this->styles[] = new FilePath(
				static::CODEX_LIBRARY_DIR . $styleFile,
				MW_INSTALL_PATH,
				$remoteBasePath
			);
		}
	}
}
