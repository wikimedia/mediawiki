<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Util;

use Psr\Log\LoggerInterface;

/**
 * JsonLoader
 *
 * Provides methods for loading and decoding JSON files with caching.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class JsonLoader {

	/**
	 * Initializes the JsonLoader with a logger.
	 *
	 * @param LoggerInterface $logger
	 *
	 * @since 1.45
	 */
	public function __construct(
		private readonly LoggerInterface $logger,
	) {
	}

	/**
	 * Loads and decodes a JSON file.
	 *
	 * @param string $filePath The path to the JSON file.
	 * @param string $context A short string to include in log messages.
	 * @param bool $allowMissing If true, suppress error logging for missing/unreadable files.
	 *
	 * @since 1.45
	 * @return array<int|string,mixed> The decoded JSON data, or an empty array on error.
	 */
	public function load( string $filePath, string $context = '', bool $allowMissing = false ): array {
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$data = @file_get_contents( $filePath );
		if ( $data === false ) {
			if ( !$allowMissing ) {
				$this->logger->error(
					'Failed to read file contents for {context} at {filePath}',
					[
						'filePath' => $filePath,
						'context' => $context,
					]
				);
			}

			return [];
		}

		$decoded = json_decode( $data, true );
		if ( !is_array( $decoded ) ) {
			$this->logger->error(
				'Expected an array from {filePath} for {context}, but received invalid data. JSON error: {error}',
				[
					'filePath' => $filePath,
					'context' => $context,
					'error' => json_last_error_msg(),
				]
			);

			return [];
		}

		return $decoded;
	}
}
