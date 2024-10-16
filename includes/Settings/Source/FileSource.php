<?php

namespace MediaWiki\Settings\Source;

use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\Format\JsonFormat;
use MediaWiki\Settings\Source\Format\SettingsFormat;
use MediaWiki\Settings\Source\Format\YamlFormat;
use Stringable;
use UnexpectedValueException;
use Wikimedia\AtEase\AtEase;

/**
 * Settings loaded from a local file path.
 *
 * @since 1.38
 */
class FileSource implements Stringable, CacheableSource, SettingsIncludeLocator {
	private const BUILT_IN_FORMATS = [
		JsonFormat::class,
		YamlFormat::class,
	];

	/**
	 * Cache expiry TTL for file sources (24 hours).
	 *
	 * @see getExpiryTtl()
	 * @see CacheableSource::getExpiryTtl()
	 */
	private const EXPIRY_TTL = 60 * 60 * 24;

	/**
	 * Early expiry weight. This value influences the margin by which
	 * processes are selected to expire cached local-file settings early to
	 * avoid cache stampedes. Changes to this value are not likely to be
	 * necessary as time spent loading from local files should not have much
	 * variation and should already be well served by the default early expiry
	 * calculation.
	 *
	 * @see getExpiryWeight()
	 * @see CacheableSource::getExpiryWeight()
	 */
	private const EXPIRY_WEIGHT = 1.0;

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
	public function __construct( string $path, ?SettingsFormat $format = null ) {
		$this->path = $path;
		$this->format = $format;
	}

	/**
	 * Disallow stale results from file sources in the case of load failure as
	 * failing to read from disk would be quite catastrophic and worthy of
	 * propagation.
	 *
	 * @return bool
	 */
	public function allowsStaleLoad(): bool {
		return false;
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
	 * The cache expiry TTL (in seconds) for this file source.
	 *
	 * @return int
	 */
	public function getExpiryTtl(): int {
		return self::EXPIRY_TTL;
	}

	/**
	 * Coefficient used in determining early expiration of cached settings to
	 * avoid stampedes.
	 *
	 * @return float
	 */
	public function getExpiryWeight(): float {
		return self::EXPIRY_WEIGHT;
	}

	/**
	 * Returns a hash key computed from the file's inode, size, and last
	 * modified timestamp.
	 *
	 * @return string
	 */
	public function getHashKey(): string {
		$stat = stat( $this->path );

		if ( $stat === false ) {
			throw new SettingsBuilderException(
				"Failed to stat file '{path}'",
				[ 'path' => $this->path ]
			);
		}

		return sprintf( '%x-%x-%x', $stat['ino'], $stat['size'], $stat['mtime'] );
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

	public function locateInclude( string $location ): string {
		return SettingsFileUtils::resolveRelativeLocation( $location, dirname( $this->path ) );
	}
}
