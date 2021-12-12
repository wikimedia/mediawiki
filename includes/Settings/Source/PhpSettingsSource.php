<?php

namespace MediaWiki\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use Wikimedia\AtEase\AtEase;

/**
 * Settings loaded from a PHP file path as an array structure.
 *
 * @since 1.38
 */
class PhpSettingsSource implements SettingsSource, SettingsIncludeLocator {
	/**
	 * Path to the PHP file.
	 * @var string
	 */
	private $path;

	/**
	 * @param string $path
	 */
	public function __construct( string $path ) {
		$this->path = $path;
	}

	/**
	 * Loads an array structure from a PHP file and return
	 * for using with merge strategies to apply on configs.
	 *
	 * @throws SettingsBuilderException
	 * @return array
	 */
	public function load(): array {
		// NOTE: try include first, for performance reasons. If all goes well, it will
		//       use the opcode cache, and will not touch the file system at all.
		//       So we should only go and look at the file system if the include fails.

		$source = AtEase::quietCall( static function ( $path ) {
			return include $path;
		}, $this->path );

		if ( $source === false ) {
			if ( !file_exists( $this->path ) ) {
				throw new SettingsBuilderException(
					"'File: {path}' does not exist",
					[ 'path' => $this->path ]
				);
			}

			if ( !is_readable( $this->path ) ) {
				throw new SettingsBuilderException(
					"File '{path}' is not readable",
					[ 'path' => $this->path ]
				);
			}

			if ( is_dir( $this->path ) ) {
				throw new SettingsBuilderException(
					"'{path}' is a directory, not a file",
					[ 'path' => $this->path ]
				);
			}
		}

		if ( !is_array( $source ) ) {
			throw new SettingsBuilderException(
				"File '{path}' did not return an array",
				[ 'path' => $this->path ]
			);
		}

		return $source;
	}

	/**
	 * Returns this file source as a string.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->path;
	}

	public function locateInclude( string $location ): string {
		return SettingsFileUtils::resolveRelativeLocation( $location, dirname( $this->path ) );
	}

}
