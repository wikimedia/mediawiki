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

/**
 * Module which magically loads the right skinScripts and skinStyles for every
 * skin, using the specified OOUI theme for each.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class OOUIFileModule extends FileModule {
	use OOUIModule;

	/** @var array<string,string|FilePath> */
	private $themeStyles = [];

	public function __construct( array $options = [] ) {
		if ( isset( $options['themeScripts'] ) ) {
			$skinScripts = $this->getSkinSpecific( $options['themeScripts'], 'scripts' );
			$options['skinScripts'] = $this->extendSkinSpecific( $options['skinScripts'] ?? [], $skinScripts );
		}
		if ( isset( $options['themeStyles'] ) ) {
			$this->themeStyles = $this->getSkinSpecific( $options['themeStyles'], 'styles' );
		}

		parent::__construct( $options );
	}

	public function setSkinStylesOverride( array $moduleSkinStyles ): void {
		parent::setSkinStylesOverride( $moduleSkinStyles );

		$this->skinStyles = $this->extendSkinSpecific( $this->skinStyles, $this->themeStyles );
	}

	/**
	 * Helper function to generate values for 'skinStyles' and 'skinScripts'.
	 *
	 * @param string $module Module to generate skinStyles/skinScripts for:
	 *   'core', 'widgets', 'toolbars', 'windows'
	 * @param string $which 'scripts' or 'styles'
	 * @return array<string,string|FilePath>
	 */
	private function getSkinSpecific( $module, $which ): array {
		$themes = self::getSkinThemeMap();

		return array_combine(
			array_keys( $themes ),
			array_map( function ( $theme ) use ( $module, $which ) {
				if ( $which === 'scripts' ) {
					return $this->getThemeScriptsPath( $theme, $module );
				} else {
					return $this->getThemeStylesPath( $theme, $module );
				}
			}, array_values( $themes ) )
		);
	}

	/**
	 * Prepend theme-specific resources on behalf of the skin.
	 *
	 * The expected order of styles and scripts in the output is:
	 *
	 * 1. Theme-specific resources for a given skin.
	 *
	 * 2. Module-defined resources for a specific skin,
	 *    falling back to module-defined "default" skin resources.
	 *
	 * 3. Skin-defined resources for a specific module, which can either
	 *    append to or replace the "default" (via ResourceModuleSkinStyles in skin.json)
	 *    Note that skins can only define resources for a module if that
	 *    module does not already explicitly provide resources for that skin.
	 *
	 * @param array $skinSpecific Module-defined 'skinScripts' or 'skinStyles'.
	 * @param array $themeSpecific
	 * @return array Modified $skinSpecific
	 */
	private function extendSkinSpecific( array $skinSpecific, array $themeSpecific ): array {
		// If the module or skin already set skinStyles/skinScripts, prepend ours
		foreach ( $skinSpecific as $skin => &$files ) {
			$prepend = $themeSpecific[$skin] ?? $themeSpecific['default'] ?? null;
			if ( $prepend !== null ) {
				if ( !is_array( $files ) ) {
					$files = [ $files ];
				}
				array_unshift( $files, $prepend );
			}
		}
		// If the module has no skinStyles/skinScripts for a skin, then set ours
		foreach ( $themeSpecific as $skin => $file ) {
			$skinSpecific[$skin] ??= [ $file ];
		}
		return $skinSpecific;
	}
}
