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

	/**
	 * Path to formal indexes JSON file.
	 */
	private const FORMALITY_INDEXES_PATH = __DIR__ . '/../data/formal-indexes.json';

	/**
	 * Language code for formality index lookup.
	 */
	private string $langCode;

	/**
	 * Logger instance used for logging errors.
	 */
	private LoggerInterface $logger;

	/**
	 * JsonLoader instance.
	 */
	private JsonLoader $jsonLoader;

	/**
	 * Cached formality indexes.
	 *
	 * @var array<string, int>|null
	 */
	private static ?array $indexesCache = null;

	/**
	 * Initializes the FormalityIndex.
	 *
	 * @param string $langCode The language code.
	 * @param LoggerInterface $logger The logger instance to use.
	 * @param JsonLoader $jsonLoader The json loader to load data
	 *
	 * @since 1.45
	 */
	public function __construct( string $langCode, LoggerInterface $logger, JsonLoader $jsonLoader ) {
		$this->langCode = $langCode;
		$this->logger = $logger;
		$this->jsonLoader = $jsonLoader;
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
