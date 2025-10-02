<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Handler\Overrides\Grammar;

use Wikimedia\Leximorph\Handler\Overrides\IGrammarTransformer;

/**
 * GrammarKk
 *
 * Implements grammar transformations for Kazakh (kk).
 * Delegates processing to the Cyrillic-based implementation.
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarKk implements IGrammarTransformer {
	/**
	 * Applies Kazakh-specific grammatical transformations.
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
		// T277689: If there's no word, then there's nothing to convert.
		if ( $word === '' ) {
			return '';
		}

		return ( new GrammarKk_cyrl() )->process( $word, $case );
	}
}
