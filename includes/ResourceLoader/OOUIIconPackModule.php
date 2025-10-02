<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ResourceLoader;

use InvalidArgumentException;

/**
 * Allows loading arbitrary sets of OOUI icons.
 *
 * @ingroup ResourceLoader
 * @since 1.34
 */
class OOUIIconPackModule extends OOUIImageModule {
	public function __construct( array $options = [], ?string $localBasePath = null ) {
		parent::__construct( $options, $localBasePath );

		if ( !isset( $this->definition['icons'] ) || !$this->definition['icons'] ) {
			throw new InvalidArgumentException( "Parameter 'icons' must be given." );
		}

		// A few things check for the "icons" prefix on this value, so specify it even though
		// we don't use it for actually loading the data, like in the other modules.
		$this->definition['themeImages'] = 'icons';
	}

	private function getIcons(): array {
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable Checked in the constructor
		return $this->definition['icons'];
	}

	/** @inheritDoc */
	protected function loadOOUIDefinition( $theme, $unused ): array {
		// This is shared between instances of this class, so we only have to load the JSON files once
		static $data = [];

		if ( !isset( $data[$theme] ) ) {
			$data[$theme] = [];
			// Load and merge the JSON data for all "icons-foo" modules
			foreach ( self::$knownImagesModules as $module ) {
				if ( str_starts_with( $module, 'icons' ) ) {
					$moreData = $this->readJSONFile( $this->getThemeImagesPath( $theme, $module ) );
					if ( $moreData ) {
						$data[$theme] = array_replace_recursive( $data[$theme], $moreData );
					}
				}
			}
		}

		$definition = $data[$theme];

		// Filter out the data for all other icons, leaving only the ones we want for this module
		$iconsNames = $this->getIcons();
		foreach ( $definition['images'] as $iconName => $_ ) {
			if ( !in_array( $iconName, $iconsNames ) ) {
				unset( $definition['images'][$iconName] );
			}
		}

		return $definition;
	}

	/** @inheritDoc */
	public static function extractLocalBasePath( array $options, $localBasePath = null ) {
		global $IP;
		// Ignore any 'localBasePath' present in $options, this always refers to files in MediaWiki core
		return $localBasePath ?? $IP;
	}
}
