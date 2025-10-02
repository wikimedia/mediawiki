<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ResourceLoader;

use LogicException;

/**
 * Loads the module definition from JSON files in the format that OOUI uses, converting it to the
 * format we use. (Previously known as secret special sauce.)
 *
 * @since 1.26
 */
class OOUIImageModule extends ImageModule {
	use OOUIModule;

	protected function loadFromDefinition() {
		if ( $this->definition === null ) {
			// Do nothing if definition was already processed
			return;
		}

		$themes = self::getSkinThemeMap();

		// For backwards-compatibility, allow missing 'themeImages'
		$module = $this->definition['themeImages'] ?? '';

		$definition = [];
		foreach ( $themes as $skin => $theme ) {
			$data = $this->loadOOUIDefinition( $theme, $module );

			if ( !$data ) {
				// If there's no file for this module of this theme, that's okay, it will just use the defaults
				continue;
			}

			// Convert into a definition compatible with the parent vanilla ImageModule
			foreach ( $data as $key => $value ) {
				switch ( $key ) {
					// Images and color variants are defined per-theme, here converted to per-skin
					case 'images':
					case 'variants':
						$definition[$key][$skin] = $value;
						break;

					// Other options must be identical for each theme (or only defined in the default one)
					default:
						if ( !isset( $definition[$key] ) ) {
							$definition[$key] = $value;
						} elseif ( $definition[$key] !== $value ) {
							throw new LogicException(
								"Mismatched OOUI theme images definition: " .
									"key '$key' of theme '$theme' for module '$module' " .
									"does not match other themes"
							);
						}
						break;
				}
			}
		}

		// Extra selectors to allow using the same icons for old-style MediaWiki UI code
		if ( str_starts_with( $module, 'icons' ) ) {
			$definition['selectorWithoutVariant'] = '.oo-ui-icon-{name}, .mw-ui-icon-{name}:before';
			$definition['selectorWithVariant'] = '.oo-ui-image-{variant}.oo-ui-icon-{name}, ' .
				'.mw-ui-icon-{name}-{variant}:before';
		}

		// Fields from module definition silently override keys from JSON files
		$this->definition += $definition;

		parent::loadFromDefinition();
	}

	/**
	 * Load the module definition from the JSON file(s) for the given theme and module.
	 *
	 * @since 1.34
	 * @param string $theme
	 * @param string $module
	 * @return array|false
	 * @suppress PhanTypeArraySuspiciousNullable
	 */
	protected function loadOOUIDefinition( $theme, $module ) {
		// Find the path to the JSON file which contains the actual image definitions for this theme
		if ( $module ) {
			$dataPath = $this->getThemeImagesPath( $theme, $module );
		} else {
			// Backwards-compatibility for things that probably shouldn't have used this class...
			$dataPath =
				$this->definition['rootPath'] . '/' .
				strtolower( $theme ) . '/' .
				$this->definition['name'] . '.json';
		}

		return $this->readJSONFile( $dataPath );
	}

	/**
	 * Read JSON from a file, and transform all paths in it to be relative to the module's base path.
	 *
	 * @since 1.34
	 * @param string $dataPath Path relative to the module's base bath
	 * @return array|false
	 */
	protected function readJSONFile( $dataPath ) {
		$localDataPath = $this->getLocalPath( $dataPath );

		if ( !file_exists( $localDataPath ) ) {
			return false;
		}

		$data = json_decode( file_get_contents( $localDataPath ), true );

		// Expand the paths to images (since they are relative to the JSON file that defines them, not
		// our base directory)
		$fixPath = static function ( &$path ) use ( $dataPath ) {
			if ( $dataPath instanceof FilePath ) {
				$path = new FilePath(
					dirname( $dataPath->getPath() ) . '/' . $path,
					$dataPath->getLocalBasePath(),
					$dataPath->getRemoteBasePath()
				);
			} else {
				$path = dirname( $dataPath ) . '/' . $path;
			}
		};
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
		array_walk( $data['images'], static function ( &$value ) use ( $fixPath ) {
			if ( is_string( $value['file'] ) ) {
				$fixPath( $value['file'] );
			} elseif ( is_array( $value['file'] ) ) {
				array_walk_recursive( $value['file'], $fixPath );
			}
		} );

		return $data;
	}
}
