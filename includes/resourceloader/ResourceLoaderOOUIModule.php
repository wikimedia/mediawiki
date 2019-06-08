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

/**
 * Convenience methods for dealing with OOUI themes and their relations to MW skins.
 *
 * @since 1.30
 */
trait ResourceLoaderOOUIModule {
	protected static $knownScriptsModules = [ 'core' ];
	protected static $knownStylesModules = [ 'core', 'widgets', 'toolbars', 'windows' ];
	protected static $knownImagesModules = [
		'indicators', 'textures',
		// Extra icons
		'icons-accessibility',
		'icons-alerts',
		'icons-content',
		'icons-editing-advanced',
		'icons-editing-citation',
		'icons-editing-core',
		'icons-editing-list',
		'icons-editing-styling',
		'icons-interactions',
		'icons-layout',
		'icons-location',
		'icons-media',
		'icons-moderation',
		'icons-movement',
		'icons-user',
		'icons-wikimedia',
	];

	// Note that keys must be lowercase, values TitleCase.
	protected static $builtinSkinThemeMap = [
		'default' => 'WikimediaUI',
	];

	// Note that keys must be TitleCase.
	protected static $builtinThemePaths = [
		'WikimediaUI' => [
			'scripts' => 'resources/lib/ooui/oojs-ui-wikimediaui.js',
			'styles' => 'resources/lib/ooui/oojs-ui-{module}-wikimediaui.css',
			'images' => 'resources/lib/ooui/themes/wikimediaui/{module}.json',
		],
		'Apex' => [
			'scripts' => 'resources/lib/ooui/oojs-ui-apex.js',
			'styles' => 'resources/lib/ooui/oojs-ui-{module}-apex.css',
			'images' => 'resources/lib/ooui/themes/apex/{module}.json',
		],
	];

	/**
	 * Return a map of skin names (in lowercase) to OOUI theme names, defining which theme a given
	 * skin should use.
	 *
	 * @return array
	 */
	public static function getSkinThemeMap() {
		$themeMap = self::$builtinSkinThemeMap;
		$themeMap += ExtensionRegistry::getInstance()->getAttribute( 'SkinOOUIThemes' );
		return $themeMap;
	}

	/**
	 * Return a map of theme names to lists of paths from which a given theme should be loaded.
	 *
	 * Keys are theme names, values are associative arrays. Keys of the inner array are 'scripts',
	 * 'styles', or 'images', and values are string paths.
	 *
	 * Additionally, the string '{module}' in paths represents the name of the module to load.
	 *
	 * @return array
	 */
	protected static function getThemePaths() {
		$themePaths = self::$builtinThemePaths;
		return $themePaths;
	}

	/**
	 * Return a path to load given module of given theme from.
	 *
	 * @param string $theme OOUI theme name, for example 'WikimediaUI' or 'Apex'
	 * @param string $kind Kind of the module: 'scripts', 'styles', or 'images'
	 * @param string $module Module name, for valid values see $knownScriptsModules,
	 *     $knownStylesModules, $knownImagesModules
	 * @return string
	 */
	protected function getThemePath( $theme, $kind, $module ) {
		$paths = self::getThemePaths();
		$path = $paths[$theme][$kind];
		$path = str_replace( '{module}', $module, $path );
		return $path;
	}

	/**
	 * @param string $theme See getThemePath()
	 * @param string $module See getThemePath()
	 * @return string
	 */
	protected function getThemeScriptsPath( $theme, $module ) {
		if ( !in_array( $module, self::$knownScriptsModules ) ) {
			throw new InvalidArgumentException( "Invalid OOUI scripts module '$module'" );
		}
		return $this->getThemePath( $theme, 'scripts', $module );
	}

	/**
	 * @param string $theme See getThemePath()
	 * @param string $module See getThemePath()
	 * @return string
	 */
	protected function getThemeStylesPath( $theme, $module ) {
		if ( !in_array( $module, self::$knownStylesModules ) ) {
			throw new InvalidArgumentException( "Invalid OOUI styles module '$module'" );
		}
		return $this->getThemePath( $theme, 'styles', $module );
	}

	/**
	 * @param string $theme See getThemePath()
	 * @param string $module See getThemePath()
	 * @return string
	 */
	protected function getThemeImagesPath( $theme, $module ) {
		if ( !in_array( $module, self::$knownImagesModules ) ) {
			throw new InvalidArgumentException( "Invalid OOUI images module '$module'" );
		}
		return $this->getThemePath( $theme, 'images', $module );
	}
}
