<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Provider;

use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * GrammarTransformations
 *
 * Provides functionality to load and cache grammar transformation rules from JSON files.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarTransformations {

	/**
	 * The paths to the grammar rules JSON files.
	 */
	private const TRANSFORMATIONS_PATH = __DIR__ . '/../data/grammarTransformations/';

	/**
	 * Cached grammar transformations indexed by language code.
	 *
	 * @var array<string, array<int|string, mixed>>
	 */
	private static array $transformationsCache = [];

	/**
	 * Initializes the GrammarTransformations.
	 *
	 * @param string $langCode The language code.
	 * @param JsonLoader $jsonLoader The json loader to load data
	 *
	 * @since 1.45
	 */
	public function __construct(
		private readonly string $langCode,
		private readonly JsonLoader $jsonLoader,
	) {
	}

	/**
	 * Returns grammar transformation rules for the current language.
	 *
	 * @since 1.45
	 * @return array<int|string, mixed> Grammar transformation rules.
	 */
	public function getTransformations(): array {
		self::$transformationsCache ??= [];

		if ( isset( self::$transformationsCache[$this->langCode] ) ) {
			return self::$transformationsCache[$this->langCode];
		}

		$filePath = self::TRANSFORMATIONS_PATH . $this->langCode . '.json';
		$transformations = $this->jsonLoader->load( $filePath, 'grammar transformations' );

		self::$transformationsCache[$this->langCode] = $transformations;

		return $transformations;
	}
}
