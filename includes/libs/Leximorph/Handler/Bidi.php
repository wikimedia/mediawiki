<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Handler;

use Wikimedia\Leximorph\Provider;

/**
 * Bidi
 *
 * The Bidi class applies bidirectional formatting to text by wrapping the input
 * with directional isolation control characters. It detects the primary directionality
 * (left-to-right or right-to-left) of the text and ensures that mixed-direction content
 * is rendered correctly.
 *
 * Usage Example:
 * <code>
 *            echo $bidi->process( 'Hello World' );
 * </code>
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class Bidi {

	/**
	 * Unicode LEFT-TO-RIGHT EMBEDDING character (U+202A).
	 */
	public const LRE = "\xE2\x80\xAA";

	/**
	 * Unicode RIGHT-TO-LEFT EMBEDDING character (U+202B).
	 */
	public const RLE = "\xE2\x80\xAB";

	/**
	 * Unicode POP DIRECTIONAL FORMATTING character (U+202C).
	 */
	public const PDF = "\xE2\x80\xAC";

	/**
	 * Initializes the Bidi handler with the provider.
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
	 * Applies bidirectional formatting to the provided text.
	 *
	 * This method determines the primary text direction (LTR or RTL) by
	 * examining the first strongly directional codepoint in the string.
	 * It then wraps the text with the appropriate directional control
	 * characters: LRE and PDF for left-to-right, RLE and PDF for right-to-left.
	 * If no strong directionality is detected, the text is returned
	 * unmodified.
	 *
	 * @param string $value The text to format.
	 *
	 * @since 1.45
	 * @return string The text with directional controls, or the original text.
	 */
	public function process( string $value ): string {
		$dir = $this->provider->getBidiProvider()->getDirection( $value );
		if ( $dir === 'ltr' ) {
			# Wrap in LEFT-TO-RIGHT EMBEDDING ... POP DIRECTIONAL FORMATTING
			return self::LRE . $value . self::PDF;
		}
		if ( $dir === 'rtl' ) {
			# Wrap in RIGHT-TO-LEFT EMBEDDING ... POP DIRECTIONAL FORMATTING
			return self::RLE . $value . self::PDF;
		}

		# No strong directionality: return the text unmodified
		return $value;
	}
}
