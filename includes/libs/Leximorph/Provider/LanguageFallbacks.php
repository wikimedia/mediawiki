<?php
/**
 * @license GPL-2.0-or-later
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
	public function __construct(
		private readonly string $langCode,
		private readonly LoggerInterface $logger,
		private readonly JsonLoader $jsonLoader,
	) {
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
