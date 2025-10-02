<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Handler;

use Wikimedia\Leximorph\Provider;

/**
 * Formal
 *
 * The Formal class selects the appropriate text form (formal or informal)
 * based on a language-specific formality index. It loads the index from a JSON file
 * and returns either the formal or informal variant provided in the options.
 *
 * Usage Example:
 * <code>
 *            echo $formal->process( [ 'Du hast', 'Sie haben' ] );
 * </code>
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class Formal {

	/**
	 * Initializes the Formal handler with the provider.
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
	 * Selects the appropriate text form based on formality.
	 *
	 * Given an array of options containing a formal form and an informal form (with the informal form
	 * defaulting to the formal form if not provided), this method returns the form that matches the
	 * language-specific formality index loaded from configuration.
	 *
	 * @param string[] $options An array with the formal and informal text variants.
	 *
	 * @since 1.45
	 * @return string The text variant corresponding to the determined formality.
	 */
	public function process( array $options ): string {
		$informal = $options[0];
		$formal = $options[1] ?? $informal;

		$index = $this->provider->getFormalityIndexProvider()->getFormalityIndex();

		return ( $index === 1 ) ? $formal : $informal;
	}
}
