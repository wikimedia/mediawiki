<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Handler\Overrides\Grammar;

use Wikimedia\Leximorph\Handler\Overrides\IGrammarTransformer;

/**
 * GrammarCu
 *
 * Implements grammar transformations for Church Slavonic (cu).
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarCu implements IGrammarTransformer {
	/**
	 * Applies Church Slavonic-specific grammatical transformations.
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
		# These rules are not perfect, but they are currently only used for
		# site names, so it doesn't matter if they are wrong sometimes.
		# Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = [];
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/u", $word ) ) {
			switch ( $case ) {
				# родительный падеж
				case 'genitive':
					// if ( ( implode( '', array_slice( $ar[0], -4 ) ) == 'вики' )
					//	|| ( implode( '', array_slice( $ar[0], -4 ) ) == 'Вики' )
					// ) {
					// }

					if ( implode( '', array_slice( $ar[0], -2 ) ) == 'ї' ) {
						return implode( '', array_slice( $ar[0], 0, -2 ) ) . 'їѩ';
					}
					break;
				# винительный падеж
				case 'accusative':
					# stub
					break;

				default:
					break;
			}
		}

		return $word;
	}
}
