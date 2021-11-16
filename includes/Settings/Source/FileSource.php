<?php

namespace MediaWiki\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\Format\JsonFormat;
use MediaWiki\Settings\Source\Format\SettingsFormat;
use UnexpectedValueException;
use Wikimedia\AtEase\AtEase;

/**
 * Settings loaded from a local file path.
 *
 * @since 1.38
 */
class FileSource implements SettingsSource {
	/**
	 * Default format with which to attempt decoding if none are given to the
	 * constructor.
	 */
	private const DEFAULT_FORMAT = JsonFormat::class;

	/**
	 * Possible formats.
	 * @var array
	 */
	private $formats;

	/**
	 * Path to local file.
	 * @var string
	 */
	private $path;

	/**
	 * Constructs a new FileSource for the given path and possible matching
	 * formats. The first format to match the path's file extension will be
	 * used to decode the content.
	 *
	 * An end-user caller may be explicit about the given path's format by
	 * providing only one format.
	 *
	 * <code>
	 * <?php
	 * $source = new FileSource( 'my/settings.json', new JsonFormat() );
	 * $source->load();
	 * </code>
	 *
	 * While a generalized caller may want to pass a number of supported
	 * formats.
	 *
	 * <code>
	 * <?php
	 * function loadAllPossibleFormats( string $path ) {
	 *     $source = new FileSource(
	 *         $path,
	 *         new JsonFormat(),
	 *         new YamlFormat(),
	 *         new TomlFormat()
	 *     )
	 * }
	 * </code>
	 *
	 * @param string $path
	 * @param SettingsFormat ...$formats
	 */
	public function __construct( string $path, SettingsFormat ...$formats ) {
		$this->path = $path;
		$this->formats = $formats;

		if ( empty( $this->formats ) ) {
			$class = self::DEFAULT_FORMAT;
			$this->formats = [ new $class() ];
		}
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
		if ( count( $this->formats ) == 1 ) {
			return $this->readAndDecode( $this->formats[0] );
		}

		foreach ( $this->formats as $format ) {
			if ( $format->supportsFileExtension( $ext ) ) {
				return $this->readAndDecode( $format );
			}
		}

		throw new SettingsBuilderException(
			"None of the given formats ({formats}) are suitable for '{path}'",
			[
				'formats' => implode( ', ', $this->formats ),
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
