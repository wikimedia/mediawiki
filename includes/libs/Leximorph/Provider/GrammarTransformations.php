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
	 * Language code used for evaluating grammar rules.
	 */
	private string $langCode;

	/**
	 * JsonLoader instance for loading grammar transformations.
	 */
	private JsonLoader $jsonLoader;

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
	public function __construct( string $langCode, JsonLoader $jsonLoader ) {
		$this->langCode = $langCode;
		$this->jsonLoader = $jsonLoader;
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
