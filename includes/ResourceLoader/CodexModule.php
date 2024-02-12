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
	protected const CODEX_MODULE_DIR = 'resources/lib/codex/modules/';
	private const CODEX_MODULE_DEPENDENCIES = [ 'vue' ];

	/** @var array<string,string> */
	private array $themeMap = [];

	/** @var array<string,array> */
	private array $themeStyles = [];

	/** @var array<string> */
	private array $codexComponents = [];

	private bool $hasThemeStyles = false;
	private bool $isStyleOnly = false;
	private bool $isScriptOnly = false;
	private bool $setupComplete = false;

	/**
	 * @param array $options [optional]
	 *  - themeStyles: array of skin- or theme-specific files
	 *  - codexComponents: array of Codex components to include
	 *  - codexStyleOnly: whether to include only style files
	 *  - codexScriptOnly: whether to include only script files
	 * @param string|null $localBasePath [optional]
	 * @param string|null $remoteBasePath [optional]
	 */
	public function __construct( array $options = [], $localBasePath = null, $remoteBasePath = null ) {
		$skinCodexThemes = ExtensionRegistry::getInstance()->getAttribute( 'SkinCodexThemes' );
		$this->themeMap = [ 'default' => 'wikimedia-ui' ] + $skinCodexThemes;

		if ( isset( $options['themeStyles'] ) ) {
			$this->hasThemeStyles = true;
			$this->themeStyles = $options[ 'themeStyles' ];
		}

		if ( isset( $options[ 'codexComponents' ] ) ) {
			if ( !is_array( $options[ 'codexComponents' ] ) || count( $options[ 'codexComponents' ] ) === 0 ) {
				throw new InvalidArgumentException(
					"All 'codexComponents' properties in your module definition file " .
					'must either be omitted or be an array with at least one component name'
				);
			}

			$this->codexComponents = $options[ 'codexComponents' ];
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

	public function getDefinitionSummary( Context $context ) {
		$this->setupCodex( $context );
		return parent::getDefinitionSummary( $context );
	}

	public function getFlip( Context $context ) {
		// Never flip styles for Codex modules, because we already provide separate style files
		// for LTR vs RTL
		return false;
	}

	/**
	 * @param Context $context
	 * @return string Name of the manifest file to use
	 */
	private function getManifestFile( Context $context ): string {
		$isRtl = $context->getDirection() === 'rtl';
		$isLegacy = $this->getTheme( $context ) === 'wikimedia-ui-legacy';
		$manifestFile = null;

		if ( $isRtl && $isLegacy ) {
			$manifestFile = 'manifest-legacy-rtl.json';
		} elseif ( $isRtl ) {
			$manifestFile = 'manifest-rtl.json';
		} elseif ( $isLegacy ) {
			$manifestFile = 'manifest-legacy.json';
		} else {
			$manifestFile = 'manifest.json';
		}

		return $manifestFile;
	}

	/**
	 * @param Context $context
	 * @return string Name of the current theme
	 */
	private function getTheme( Context $context ): string {
		return $this->themeMap[ $context->getSkin() ] ?? $this->themeMap[ 'default' ];
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

		// If themestyles are present, add them to the module styles
		if ( $this->hasThemeStyles ) {
			$theme = $this->getTheme( $context );
			$dir = $context->getDirection();
			$styles = $this->themeStyles[ $theme ][ $dir ];
			$this->styles = array_merge( $this->styles, (array)$styles );
		}

		$this->setupComplete = true;
	}

	/**
	 * Resolve the dependencies for a list of keys in a given manifest,
	 * and return flat arrays of both scripts and styles. Dependencies
	 * are ordered.
	 *
	 * @param array<string> $keys
	 * @param array<string,array> $manifest
	 * @return array<string,array>
	 */
	private function resolveDependencies( $keys, $manifest ): array {
		$resolvedKeys = [];
		$scripts = [];
		$styles = [];

		$gatherDependencies = static function ( $key ) use ( &$resolvedKeys, $manifest, &$gatherDependencies ) {
			foreach ( $manifest[ $key ][ 'imports' ] ?? [] as $dep ) {
				if ( !in_array( $dep, $resolvedKeys ) ) {
					$gatherDependencies( $dep );
				}
			}
			$resolvedKeys[] = $key;
		};

		foreach ( $keys as $key ) {
			$gatherDependencies( $key );
		}

		foreach ( $resolvedKeys as $key ) {
			$scripts[] = $manifest[ $key ][ 'file' ];
			foreach ( $manifest[ $key ][ 'css'] ?? [] as $css ) {
				$styles[] = $css;
			}
		}

		return [
			'scripts' => $scripts,
			'styles' => $styles
		];
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

		// Manifest data structure representing all Codex components in the library
		$manifest = json_decode(
			file_get_contents( MW_INSTALL_PATH . '/' . static::CODEX_MODULE_DIR . $this->getManifestFile( $context ) ),
			true
		);

		// Generate an array of manifest keys that meet the following conditions:
		// * The "file" property has a ".js", ".mjs", or ".cjs" file extension
		// * Entry has a "file" property that matches one of the specified codexComponents (sans extension)
		// * The manifest item is an intentional entry point and not a generated chunk
		$manifestKeys = array_keys( array_filter( $manifest, function ( $val ) {
			$file = pathinfo( $val[ 'file' ] );
			$pattern = '/^(js|mjs|cjs)$/';

			if (
				!array_key_exists( 'extension', $file ) ||
				!preg_match( $pattern, $file[ 'extension' ] ) ||
				!in_array( $file[ 'filename' ], $this->codexComponents )
			) {
				return false;
			}

			if ( !isset( $val[ 'isEntry' ] ) || !$val[ 'isEntry' ] ) {
				throw new InvalidArgumentException(
					'"' . $file[ 'filename' ] . '"' .
					' is not an export of Codex and cannot be included in the "codexComponents" array.'
				);
			}

			return true;
		} ) );

		[ 'scripts' => $scripts, 'styles' => $styles ] = $this->resolveDependencies( $manifestKeys, $manifest );

		// Add the CSS files to the module's package file (unless this is a script-only module)
		if ( !( $this->isScriptOnly ) ) {
			foreach ( $styles as $fileName ) {
				$this->styles[] = new FilePath( static::CODEX_MODULE_DIR .
					$fileName, MW_INSTALL_PATH, $remoteBasePath );
			}
		}

		// Add the JS files to the module's package file (unless this is a style-only module).
		if ( !( $this->isStyleOnly ) ) {
			$exports = [];
			foreach ( $this->codexComponents as $component ) {
				// Don't make assumptions about the component filename or extension; instead
				// consult the array of scripts for the exact name and extension of the file
				// we care about
				$componentFileName = current( array_filter( $scripts, static function ( $el ) use ( $component ) {
					return str_contains( $el, $component );
				} ) );

				$exports[ $component ] = new HtmlJsCode(
					'require( ' . Html::encodeJsVar( "./_codex/$componentFileName" ) . ' )'
				);
			}

			// Add a synthetic top-level "exports" file
			$syntheticExports = Html::encodeJsVar( HtmlJsCode::encodeObject( $exports ) );

			// Proxy the synthetic exports object so that we can throw a useful error if a component
			// is not defined in the module definition
			$proxiedSyntheticExports = <<<JAVASCRIPT
module.exports = new Proxy( $syntheticExports, {
	get( target, prop ) {
		if ( !(prop in target) ) {
			throw new Error(
				`Codex component "\${prop}" ` +
				'is not listed in the "codexComponents" array ' +
				'of the "{$this->getName()}" module in your module definition file'
			);
		}

		return target [ prop ];
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
					'file' => new FilePath( static::CODEX_MODULE_DIR . $fileName, MW_INSTALL_PATH, $remoteBasePath )
				];
			}
		}
	}
}
