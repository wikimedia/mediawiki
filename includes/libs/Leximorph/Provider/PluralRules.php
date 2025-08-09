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

use CLDRPluralRuleParser\Error as CLDRPluralRuleError;
use CLDRPluralRuleParser\Evaluator;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\Leximorph\Provider;
use Wikimedia\Leximorph\Util\XmlLoader;

/**
 * PluralRules
 *
 * Provides functionality to load, cache, and compile pluralization rules from XML files.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class PluralRules {

	/**
	 * The paths to the plural rules XML files.
	 */
	public const PLURAL_FILES = [
		// Load CLDR plural rules
		__DIR__ . '/../data/plurals.xml',
		// Override or extend with MW-specific rules
		__DIR__ . '/../data/plurals-mediawiki.xml',
	];

	/**
	 * Language code used for evaluating plural rules.
	 */
	private string $langCode;

	/**
	 * Evaluator instance used to compile and evaluate plural rules.
	 */
	private Evaluator $evaluator;

	/**
	 * Provider instance used to access language-specific services such as fallbacks.
	 */
	private Provider $provider;

	/**
	 * Logger instance used for logging errors.
	 */
	private LoggerInterface $logger;

	/**
	 * XmlLoader instance used for loading xml files.
	 */
	private XmlLoader $xmlLoader;

	/**
	 * Associative array of cached plural data.
	 *
	 * The key is the language code, and the value is an array of plural rules.
	 * Each plural rule is an associative array with the keys:
	 *  - 'type': the plural rule type (e.g., 'one', 'few', etc.)
	 *  - 'rule': the plural rule as a string.
	 *
	 * @var array<string, list<array{type: string, rule: string}>>|null
	 */
	private static ?array $pluralData = null;

	/**
	 * Initializes the PluralRules.
	 *
	 * @param string $langCode The language code for which plural rules will be evaluated.
	 * @param Evaluator $evaluator The evaluator used for compiling and evaluating plural rules.
	 * @param Provider $provider The provider used to access fallback and related resources.
	 * @param LoggerInterface $logger The logger instance used for reporting errors.
	 * @param XmlLoader $xmlLoader The xml loader to load data
	 *
	 * @since 1.45
	 */
	public function __construct(
		string $langCode,
		Evaluator $evaluator,
		Provider $provider,
		LoggerInterface $logger,
		XmlLoader $xmlLoader
	) {
		$this->langCode = $langCode;
		$this->logger = $logger;
		$this->evaluator = $evaluator;
		$this->provider = $provider;
		$this->xmlLoader = $xmlLoader;
	}

	/**
	 * Finds the index number of the plural rule appropriate for the given number.
	 *
	 * @param float $number
	 *
	 * @since 1.45
	 * @return int The index number of the plural rule.
	 */
	public function getPluralRuleIndexNumber( float $number ): int {
		$compiledRules = $this->getCompiledPluralRules();
		$evaluatedNumber = ( $number == (int)$number ) ? (int)$number : (string)$number;

		return $this->evaluator->evaluateCompiled( $evaluatedNumber, $compiledRules );
	}

	/**
	 * Returns the compiled plural rules for the current language.
	 *
	 * It uses the cached raw rules from the XML files and compiles them.
	 *
	 * @since 1.45
	 * @return array<int, string> The compiled plural rules.
	 */
	public function getCompiledPluralRules(): array {
		$rules = $this->compileRulesFor( $this->langCode );
		if ( count( $rules ) === 0 ) {
			foreach ( $this->provider->getLanguageFallbacksProvider()->getFallbacks() as $fallbackCode ) {
				$rules = $this->compileRulesFor( $fallbackCode );
				if ( count( $rules ) > 0 ) {
					break;
				}
			}
		}

		return $rules;
	}

	/**
	 * Compiles the plural rules for the specified language code.
	 *
	 * It uses the cached data and returns a compiled version via the CLDR Evaluator.
	 * Returns an empty array if the rules are unavailable or if a compilation error occurs.
	 *
	 * @param string $code The language code.
	 *
	 * @since 1.45
	 * @return array<int, string> The compiled plural rules.
	 */
	public function compileRulesFor( string $code ): array {
		if ( self::$pluralData === null ) {
			self::$pluralData = self::loadPluralFiles();
		}
		$data = self::$pluralData[$code] ?? null;
		$rules = $data ? array_column( $data, 'rule' ) : null;

		return $this->compileRulesFromArray( $rules );
	}

	/**
	 * Loads the plural XML files.
	 *
	 * @since 1.45
	 * @return array<string, list<array{type: string, rule: string}>>
	 */
	private function loadPluralFiles(): array {
		$pluralData = [];
		foreach ( self::PLURAL_FILES as $fileName ) {
			$pluralData = array_merge( $pluralData, $this->loadPluralFile( $fileName ) );
		}

		return $pluralData;
	}

	/**
	 * Loads a plural XML file and extracts the plural data.
	 *
	 * @param string $fileName The path to the XML file.
	 *
	 * @since 1.45
	 * @return array<string, list<array{type: string, rule: string}>>
	 * @throws RuntimeException if the file cannot be read.
	 */
	private function loadPluralFile( string $fileName ): array {
		$data = [];

		$doc = $this->xmlLoader->load( $fileName, 'PluralRules' );
		if ( $doc === null ) {
			return $data;
		}

		$rulesets = $doc->getElementsByTagName( "pluralRules" );
		foreach ( $rulesets as $ruleset ) {
			$codes = $ruleset->getAttribute( 'locales' );
			$rules = [];
			$ruleElements = $ruleset->getElementsByTagName( "pluralRule" );
			foreach ( $ruleElements as $elt ) {
				$ruleType = $elt->getAttribute( 'count' );
				if ( $ruleType === 'other' ) {
					// Skip "other" rules, which have an empty condition.
					continue;
				}
				$rules[] = [
					'type' => $ruleType,
					'rule' => (string)$elt->nodeValue,
				];
			}
			foreach ( explode( ' ', $codes ) as $code ) {
				$data[$code] = $rules;
			}
		}

		return $data;
	}

	/**
	 * Helper method that compiles an array of rules.
	 *
	 * If the rules array is empty, returns an empty array.
	 * Otherwise, attempts to compile using the CLDR Evaluator.
	 * On error, logs the message and returns an empty array.
	 *
	 * @param array<int, string>|null $rules The raw plural rules.
	 *
	 * @since 1.45
	 * @return array<int, string> The compiled plural rules.
	 */
	private function compileRulesFromArray( ?array $rules ): array {
		if ( $rules === null ) {
			return [];
		}
		try {
			/** @var array<int|string, string> $compiled */
			$compiled = $this->evaluator->compile( $rules );

			return array_values( $compiled );
		} catch ( CLDRPluralRuleError $e ) {
			$this->logger->debug( 'Unable to compile rules', [ 'exception' => $e ] );

			return [];
		}
	}

	/**
	 * Returns the raw plural rules for the current language from the XML files.
	 *
	 * The data is loaded from the cache if available; otherwise, the XML files are read.
	 *
	 * @since 1.45
	 * @return array<int, string>|null The plural rules, or null if they are not available.
	 */
	public function getPluralRules(): ?array {
		self::$pluralData ??= self::loadPluralFiles();
		$data = self::$pluralData[$this->langCode] ?? null;

		return $data ? array_column( $data, 'rule' ) : null;
	}

	/**
	 * Finds the plural rule type corresponding to the given number.
	 * For example, if the language is set to Arabic, getPluralRuleType(5) should return 'few'.
	 *
	 * @param float $number
	 *
	 * @since 1.45
	 * @return string The name of the plural rule type (e.g., one, two, few, many).
	 */
	public function getPluralRuleType( float $number ): string {
		$index = $this->getPluralRuleIndexNumber( $number );
		$types = $this->getPluralRuleTypes();

		return $types[$index] ?? 'other';
	}

	/**
	 * Returns the plural rule types for the current language from the XML files.
	 *
	 * The data is loaded from the cache if available; otherwise, the XML files are read.
	 *
	 * @since 1.45
	 * @return array<int, string> The plural rule types.
	 */
	public function getPluralRuleTypes(): array {
		if ( self::$pluralData === null ) {
			self::$pluralData = self::loadPluralFiles();
		}
		$data = self::$pluralData[$this->langCode] ?? [];
		if ( count( $data ) === 0 ) {
			foreach ( $this->provider->getLanguageFallbacksProvider()->getFallbacks() as $fallbackCode ) {
				$data = self::$pluralData[$fallbackCode] ?? [];
				if ( count( $data ) > 0 ) {
					break;
				}
			}
		}

		return array_column( $data, 'type' );
	}
}
