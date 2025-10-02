<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Handler\Overrides\Grammar;

use Wikimedia\Leximorph\Handler\Overrides\IGrammarTransformer;

/**
 * GrammarGa
 *
 * Implements grammar transformations for Irish (ga).
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarGa implements IGrammarTransformer {
	/**
	 * Applies Irish-specific grammatical transformations.
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
		switch ( $case ) {
			case 'ainmlae':
				switch ( $word ) {
					case 'an Domhnach':
						$word = 'Dé Domhnaigh';
						break;
					case 'an Luan':
						$word = 'Dé Luain';
						break;
					case 'an Mháirt':
						$word = 'Dé Mháirt';
						break;
					case 'an Chéadaoin':
						$word = 'Dé Chéadaoin';
						break;
					case 'an Déardaoin':
						$word = 'Déardaoin';
						break;
					case 'an Aoine':
						$word = 'Dé hAoine';
						break;
					case 'an Satharn':
						$word = 'Dé Sathairn';
						break;
					default:
						break;
				}
				break;

			default:
				break;
		}

		return $word;
	}
}
