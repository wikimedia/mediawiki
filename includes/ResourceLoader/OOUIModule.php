<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ResourceLoader;

use InvalidArgumentException;
use MediaWiki\Registration\ExtensionRegistry;

/**
 * Convenience methods for dealing with OOUI themes and their relations to MW skins.
 *
 * @ingroup ResourceLoader
 * @internal
 */
trait OOUIModule {

	/** @var string[] */
	private const array KNOWN_SCRIPTS_MODULES = [
		'core',
	];

	/** @var string[] */
	private const array KNOWN_STYLES_MODULES = [
		'core',
		'toolbars',
		'widgets',
		'windows',
	];

	/** @var string[] */
	protected const array KNOWN_IMAGES_MODULES = [
		'indicators',
		// Extra icons
		'icons-accessibility',
		'icons-alerts',
		'icons-content',
		'icons-editing-advanced',
		'icons-editing-citation',
		'icons-editing-core',
		'icons-editing-functions',
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

	/** @var array<string,string> Note that keys must be lowercase, values TitleCase. */
	private const array BUILTIN_SKIN_THEME_MAP = [
		'default' => 'WikimediaUI',
	];

	/** @var array<string,array<string,string>> Note that keys must be TitleCase. */
	private const array BUILTIN_THEME_PATHS = [
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
	 * @return string[]
	 */
	public static function getSkinThemeMap() {
		$themeMap = self::BUILTIN_SKIN_THEME_MAP;
		$themeMap += ExtensionRegistry::getInstance()->getAttribute( 'SkinOOUIThemes' );
		return $themeMap;
	}

	/**
	 * Return a map of theme names to lists of paths from which a given theme should be loaded.
	 *
	 * Keys are theme names, values are associative arrays. Keys of the inner array are 'scripts',
	 * 'styles', or 'images', and values are paths. Paths may be strings or FilePaths.
	 *
	 * Additionally, the string '{module}' in paths represents the name of the module to load.
	 *
	 * @return array<string,array<string,string|FilePath>>
	 */
	protected static function getThemePaths() {
		$themePaths = self::BUILTIN_THEME_PATHS;
		$themePaths += ExtensionRegistry::getInstance()->getAttribute( 'OOUIThemePaths' );

		[ $defaultLocalBasePath, $defaultRemoteBasePath ] =
			FileModule::extractBasePaths();

		// Allow custom themes' paths to be relative to the skin/extension that defines them,
		// like with ResourceModuleSkinStyles
		foreach ( $themePaths as &$paths ) {
			[ $localBasePath, $remoteBasePath ] =
				FileModule::extractBasePaths( $paths );
			if ( $localBasePath !== $defaultLocalBasePath || $remoteBasePath !== $defaultRemoteBasePath ) {
				foreach ( $paths as &$path ) {
					$path = new FilePath( $path, $localBasePath, $remoteBasePath );
				}
			}
		}

		return $themePaths;
	}

	/**
	 * Return a path to load given module of given theme from.
	 *
	 * The file at this path may not exist. This should be handled by the caller (throwing an error or
	 * falling back to default theme).
	 *
	 * @param string $theme OOUI theme name, for example 'WikimediaUI' or 'Apex'
	 * @param string $kind Kind of the module: 'scripts', 'styles', or 'images'
	 * @param string $module Module name, for valid values see {@link KNOWN_SCRIPTS_MODULES},
	 *     {@link KNOWN_STYLES_MODULES}, {@link KNOWN_IMAGES_MODULES}
	 * @return string|FilePath
	 */
	protected function getThemePath( $theme, $kind, $module ) {
		$path = self::getThemePaths()[$theme][$kind];

		if ( $path instanceof FilePath ) {
			return new FilePath(
				str_replace( '{module}', $module, $path->getPath() ),
				$path->getLocalBasePath(),
				$path->getRemoteBasePath()
			);
		}

		return str_replace( '{module}', $module, $path );
	}

	/**
	 * @param string $theme See getThemePath()
	 * @param string $module See getThemePath()
	 * @return string|FilePath
	 */
	protected function getThemeScriptsPath( $theme, $module ) {
		if ( !in_array( $module, self::KNOWN_SCRIPTS_MODULES ) ) {
			throw new InvalidArgumentException( "Invalid OOUI scripts module '$module'" );
		}
		return $this->getThemePath( $theme, 'scripts', $module );
	}

	/**
	 * @param string $theme See getThemePath()
	 * @param string $module See getThemePath()
	 * @return string|FilePath
	 */
	protected function getThemeStylesPath( $theme, $module ) {
		if ( !in_array( $module, self::KNOWN_STYLES_MODULES ) ) {
			throw new InvalidArgumentException( "Invalid OOUI styles module '$module'" );
		}
		return $this->getThemePath( $theme, 'styles', $module );
	}

	/**
	 * @param string $theme See getThemePath()
	 * @param string $module See getThemePath()
	 * @return string|FilePath
	 */
	protected function getThemeImagesPath( $theme, $module ) {
		if ( !in_array( $module, self::KNOWN_IMAGES_MODULES ) ) {
			throw new InvalidArgumentException( "Invalid OOUI images module '$module'" );
		}
		return $this->getThemePath( $theme, 'images', $module );
	}
}
