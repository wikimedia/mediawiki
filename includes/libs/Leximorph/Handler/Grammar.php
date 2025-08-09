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

namespace Wikimedia\Leximorph\Handler;

use Psr\Log\LoggerInterface;
use Wikimedia\Leximorph\Handler\Overrides\GrammarFallbackRegistry;
use Wikimedia\Leximorph\Provider;

/**
 * Grammar
 *
 * The Grammar class performs language-specific grammatical transformations on a given word.
 * It uses transformation rules (loaded from JSON files) to convert the input word into
 * the specified grammatical case.
 *
 * Usage Example:
 * <code>
 *            echo $grammar->process( 'Википедия', 'genitive' );
 * </code>
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class Grammar {

	/**
	 * Provider instance used to provide data.
	 */
	protected Provider $provider;

	/**
	 * PostProcessor registry instance.
	 */
	private GrammarFallbackRegistry $postProcessor;

	/**
	 * Logger instance used for logging errors.
	 */
	private LoggerInterface $logger;

	/**
	 * Initializes the Grammar handler with the given transformations provider and a logger.
	 *
	 * @param Provider $provider Provider instance.
	 * @param GrammarFallbackRegistry $postProcessor The post processor registry.
	 * @param LoggerInterface $logger The logger instance to use.
	 *
	 * @since 1.45
	 */
	public function __construct( Provider $provider, GrammarFallbackRegistry $postProcessor, LoggerInterface $logger ) {
		$this->provider = $provider;
		$this->postProcessor = $postProcessor;
		$this->logger = $logger;
	}

	/**
	 * Transforms the given word into the specified grammatical case.
	 *
	 * This method applies language-specific grammatical transformations by using transformation
	 * rules loaded from JSON configuration files. The input word is modified according to the first
	 * matching rule for the target grammatical case.
	 *
	 * @param string $word The word to transform.
	 * @param string $case The target grammatical case.
	 *
	 * @since 1.45
	 * @return string The transformed word in the specified case.
	 */
	public function process( string $word, string $case ): string {
		$grammarTransformations = $this->provider->getGrammarTransformationsProvider()->getTransformations();

		if ( array_key_exists( $case, $grammarTransformations ) ) {
			$forms = $grammarTransformations[$case];

			// Some names of grammar rules are aliases for other rules.
			// In such cases the value is a string rather than object,
			// so load the actual rules.
			if ( is_string( $forms ) ) {
				$alias = $forms;
				if ( isset( $grammarTransformations[$alias] ) && is_array( $grammarTransformations[$alias] ) ) {
					$forms = $grammarTransformations[$alias];
				} else {
					$this->logger->error(
						'Expected alias {alias} to resolve to an array in grammar transformations.',
						[ 'alias' => $alias ]
					);

					return $word;
				}
			}

			if ( !is_array( $forms ) ) {
				$this->logger->error(
					'Invalid type for grammar forms. Expected array, got {type}.',
					[ 'type' => gettype( $forms ) ]
				);

				return $word;
			}

			foreach ( $forms as $rule ) {
				if ( !is_array( $rule ) || !isset( $rule[0] ) || !isset( $rule[1] ) ) {
					$this->logger->warning(
						'Skipping malformed grammar rule. Expected [pattern, replacement]. Case: {case}, Rule: {rule}',
						[
							'case' => $case,
							'rule' => json_encode( $rule ),
						]
					);
					continue;
				}

				if ( !is_string( $rule[0] ) ) {
					$this->logger->warning(
						'Invalid grammar rule format: first element must be string. Case: {case}, Rule: {rule}',
						[
							'case' => $case,
							'rule' => json_encode( $rule ),
						]
					);
					continue;
				}

				$form = $rule[0];

				if ( $form === '@metadata' ) {
					continue;
				}

				$replacement = is_string( $rule[1] ) ? $rule[1] : '';

				$regex = '/' . addcslashes( $form, '/' ) . '/u';
				$patternMatches = preg_match( $regex, $word );

				if ( $patternMatches === false ) {
					$this->logger->error(
						'An error occurred while processing grammar: {error}. Word: {word}. Regex: /{form}/.',
						[
							'error' => preg_last_error_msg(),
							'word' => $word,
							'form' => $form,
						]
					);
				} elseif ( $patternMatches === 1 ) {
					$word = preg_replace( $regex, $replacement, $word ) ?? $word;
					break;
				}
			}
		} else {
			$word = $this->postProcessor->apply( $this->provider->getLanguageCode(), $word, $case );
		}

		return $word;
	}
}
