<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Handler;

use Wikimedia\Leximorph\Provider;

/**
 * Plural
 *
 * The Plural class selects the correct text form based on a numeric count and language-specific
 * pluralization rules from the Unicode CLDR. It processes a number along with an array of text options,
 * returning the appropriately pluralized text.
 *
 * Usage Example:
 * <code>
 *            echo $plural->process( 3, [ 'article', 'articles' ] );
 * </code>
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class Plural {

	/**
	 * Initializes the Plural handler with the given language code and provider.
	 *
	 * @param Provider $provider The provider instance to use.
	 *
	 * @since 1.45
	 */
	public function __construct(
		private readonly Provider $provider,
	) {
	}

	/**
	 * Selects and returns the pluralized text form based on a numeric count.
	 *
	 * This method evaluates the provided numeric count using language-specific pluralization rules
	 * derived from the Unicode CLDR. It then selects the appropriate text form from the provided
	 * array of alternatives, taking into account any explicit plural forms if specified.
	 *
	 * @param float $count The numeric count to evaluate.
	 * @param string[] $forms An array of text forms for pluralization.
	 *
	 * @since 1.45
	 * @return string The pluralized text corresponding to the count.
	 */
	public function process( float $count, array $forms ): string {
		// For "explicit" forms such as "0=No items", "1=One item", or "other=Items"
		// we’ll store them in an associative array if we parse them that way.
		$explicitForms = [];

		// For "default" (non-explicit) forms such as [ 'item', 'items' ],
		// we store them in a sequential array with integer keys.
		$defaultForms = [];

		// Separate explicit forms ("n=text") from default forms
		foreach ( $forms as $form ) {
			if ( str_contains( $form, '=' ) ) {
				[
					$key,
					$text,
				] = explode( '=', $form, 2 );
				// If key is purely numeric AND matches $count, return immediately:
				if ( is_numeric( $key ) && (float)$key === $count ) {
					return $text;
				}
				// Otherwise, treat it as an explicit string key
				$explicitForms[$key] = $text;
			} else {
				// Default form
				$defaultForms[] = $form;
			}
		}

		// Figure out the plural category: "one", "few", "other", etc.
		$pluralType = $this->provider->getPluralProvider()->getPluralRuleType( $count );

		// If we have an explicit form matching $pluralType` as a key, use it:
		// e.g., "one" => "Item", "other" => "Items"
		if ( array_key_exists( $pluralType, $explicitForms ) ) {
			return $explicitForms[$pluralType];
		}

		// Otherwise, fallback to the default forms (sequential)
		// If we find a default that exactly matches $pluralType as a string, use that:
		$foundKey = array_search( $pluralType, $defaultForms, true );
		if ( $foundKey !== false ) {
			return $defaultForms[$foundKey];
		}

		// Else, use the numeric index from the language’s plural rules
		// (e.g. 0 => singular form, 1 => plural form, etc.)
		if ( count( $defaultForms ) > 0 ) {
			$index = $this->provider->getPluralProvider()->getPluralRuleIndexNumber( $count );
			// Guard in case $index is out of range
			$index = min( $index, count( $defaultForms ) - 1 );

			return $defaultForms[$index];
		}

		// If no forms were provided at all, just return an empty string
		return '';
	}
}
