<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Provider;

use Psr\Log\LoggerInterface;
use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * FormalityIndex
 *
 * Loads and caches formality indexes from a JSON file.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class FormalityIndex {

	private const string FORMALITY_INDEXES_PATH = __DIR__ . '/../data/formal-indexes.json';

	/**
	 * @var array<string, int>|null
	 */
	private static ?array $indexesCache = null;

	/**
	 * @since 1.45
	 */
	public function __construct(
		private readonly string $langCode,
		private readonly LoggerInterface $logger,
		private readonly JsonLoader $jsonLoader,
	) {
	}

	/**
	 * Returns the formality index number for the given language code.
	 *
	 * If no entry is found or the entry is not a simple integer,
	 * we default to 0 (informal).
	 *
	 * @since 1.45
	 * @return int The formality index (1 for formal, 0 for informal).
	 */
	public function getFormalityIndex(): int {
		self::$indexesCache ??= $this->loadIndexes();
		$indexesCache = self::$indexesCache;

		if ( array_key_exists( $this->langCode, $indexesCache ) ) {
			return $indexesCache[$this->langCode];
		}

		if ( str_contains( $this->langCode, '-' ) ) {
			$parts = explode( '-', $this->langCode );
			$suffix = array_pop( $parts );
			if ( $suffix === 'formal' ) {
				return 1;
			} elseif ( $suffix === 'informal' ) {
				return 0;
			}

			return $indexesCache[implode( '-', $parts )] ?? 0;
		}

		return 0;
	}

	/**
	 * Loads formal indexes from JSON.
	 *
	 * @since 1.45
	 * @return array<string, int> An associative array mapping language codes to formality indexes.
	 */
	private function loadIndexes(): array {
		$rawIndexes = $this->jsonLoader->load( self::FORMALITY_INDEXES_PATH, 'formality indexes' );
		$indexes = [];

		foreach ( $rawIndexes as $key => $value ) {
			if ( is_string( $key ) && is_int( $value ) ) {
				$indexes[$key] = $value;
			} else {
				$this->logger->error(
					'Invalid formality index entry. Expected string key and int value. ' .
					'Got key type {keyType}, value type {valueType}.',
					[
						'keyType' => gettype( $key ),
						'valueType' => gettype( $value ),
					]
				);
			}
		}

		return $indexes;
	}
}
