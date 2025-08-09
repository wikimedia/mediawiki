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
 * LanguageFallbacks
 *
 * Provides functionality to retrieve fallback language codes for a given language.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class LanguageFallbacks {

	/**
	 * Path to the fallback language mappings JSON file.
	 */
	private const FALLBACKS_PATH = __DIR__ . '/../data/language-fallback-mappings.json';

	/**
	 * The language code for which fallback mappings should be returned.
	 */
	private string $langCode;

	/**
	 * Logger instance used for logging errors.
	 */
	private LoggerInterface $logger;

	/**
	 * JsonLoader instance used for loading json files.
	 */
	private JsonLoader $jsonLoader;

	/**
	 * The fallback language codes for this instance's language.
	 *
	 * @var string[]|null
	 */
	private ?array $fallbacks = null;

	/**
	 * Initializes the LanguageFallbacks.
	 *
	 * @param string $langCode The language code.
	 * @param LoggerInterface $logger The logger instance to use.
	 * @param JsonLoader $jsonLoader The json loader to load data.
	 *
	 * @since 1.45
	 */
	public function __construct( string $langCode, LoggerInterface $logger, JsonLoader $jsonLoader ) {
		$this->langCode = $langCode;
		$this->logger = $logger;
		$this->jsonLoader = $jsonLoader;
	}

	/**
	 * Loads the fallback mappings from the JSON file.
	 *
	 * @since 1.45
	 * @return array<int|string, mixed> The entire fallback mapping.
	 */
	private function loadFallbacks(): array {
		return $this->jsonLoader->load( self::FALLBACKS_PATH, 'language fallback mappings' );
	}

	/**
	 * Returns fallback language codes for the instance's language code.
	 *
	 * @since 1.45
	 * @return string[] Fallback language codes.
	 */
	public function getFallbacks(): array {
		if ( $this->fallbacks === null ) {
			$allFallbacks = $this->loadFallbacks();
			$fallbackValues = $allFallbacks[$this->langCode]['values'] ?? [];

			$this->fallbacks = [];

			foreach ( $fallbackValues as $index => $value ) {
				if ( is_string( $value ) ) {
					$this->fallbacks[] = $value;
				} else {
					$this->logger->warning(
						'Invalid fallback value. Expected string, got {type}.',
						[
							'type' => gettype( $value ),
							'langCode' => $this->langCode,
							'index' => $index,
						]
					);
				}
			}
		}

		return $this->fallbacks;
	}
}
