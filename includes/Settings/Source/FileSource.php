<?php

namespace MediaWiki\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\Format\JsonFormat;
use MediaWiki\Settings\Source\Format\SettingsFormat;
use MediaWiki\Settings\Source\Format\YamlFormat;
use UnexpectedValueException;
use Wikimedia\AtEase\AtEase;

/**
 * Settings loaded from a local file path.
 *
 * @since 1.38
 */
class FileSource implements SettingsSource {

	private const BUILT_IN_FORMATS = [
		JsonFormat::class,
		YamlFormat::class,
	];

	/**
	 * Format to use for reading the file, if given.
	 *
	 * @var ?SettingsFormat
	 */
	private $format;

	/**
	 * Path to local file.
	 * @var string
	 */
	private $path;

	/**
	 * Constructs a new FileSource for the given path and possibly a custom format
	 * to decode the contents. If no format is given, the built-in formats will be
	 * tried and the first one that supports the file extension will be used.
	 *
	 * Built-in formats:
	 *  - JsonFormat
	 *  - YamlFormat
	 *
	 * <code>
	 * <?php
	 * $source = new FileSource( 'my/settings.json' );
	 * $source->load();
	 * </code>
	 *
	 * While a specialized caller may want to pass a specialized format
	 *
	 * <code>
	 * <?php
	 * $source = new FileSource(
	 *     'my/settings.toml',
	 *     new TomlFormat()
	 * );
	 * $source->load();
	 * </code>
	 *
	 * @param string $path
	 * @param SettingsFormat|null $format
	 */
	public function __construct( string $path, SettingsFormat $format = null ) {
		$this->path = $path;
		$this->format = $format;
	}

	/**
	 * Loads contents from the file and decodes them using the first format
	 * to claim support for the file's extension.
	 *
	 * @throws SettingsBuilderException
	 * @return array
	 */
	public function load(): array {
		$ext = pathinfo( $this->path, PATHINFO_EXTENSION );

		// If there's only one format, don't bother to match the file
		// extension.
		if ( $this->format ) {
			return $this->readAndDecode( $this->format );
		}

		foreach ( self::BUILT_IN_FORMATS as $format ) {
			if ( call_user_func( [ $format, 'supportsFileExtension' ], $ext ) ) {
				return $this->readAndDecode( new $format() );
			}
		}

		throw new SettingsBuilderException(
			"None of the built-in formats are suitable for '{path}'",
			[
				'path' => $this->path,
			]
		);
	}

	/**
	 * Returns this file source as a string.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->path;
	}

	/**
	 * Reads and decodes the file contents using the given format.
	 *
	 * @param SettingsFormat $format
	 *
	 * @return array
	 * @throws SettingsBuilderException
	 */
	private function readAndDecode( SettingsFormat $format ): array {
		$contents = AtEase::quietCall( 'file_get_contents', $this->path );

		if ( $contents === false ) {
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

			throw new SettingsBuilderException(
				"Failed to read file '{path}'",
				[ 'path' => $this->path ]
			);
		}

		try {
			return $format->decode( $contents );
		} catch ( UnexpectedValueException $e ) {
			throw new SettingsBuilderException(
				"Failed to decode file '{path}': {message}",
				[
					'path' => $this->path,
					'message' => $e->getMessage()
				]
			);
		}
	}
}
