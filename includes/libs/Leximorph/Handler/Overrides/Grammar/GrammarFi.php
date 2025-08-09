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

namespace Wikimedia\Leximorph\Handler\Overrides\Grammar;

use Wikimedia\Leximorph\Handler\Overrides\IGrammarTransformer;

/**
 * GrammarFi
 *
 * Implements grammar transformations for Finnish (fi).
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Niklas Laxström
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarFi implements IGrammarTransformer {
	/**
	 * Applies Finnish-specific grammatical transformations.
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
		# These rules don't cover the whole language.
		# They are used only for site names.

		# vowel harmony flag
		$aou = preg_match( '/[aou][^äöy]*$/i', $word );

		# The flag should be false for compounds where the last word has only neutral vowels (e/i).
		# The general case cannot be handled without a dictionary, but there's at least one notable
		# special case we should check for:

		if ( preg_match( '/wiki$/i', $word ) ) {
			$aou = false;
		}

		# append i after final consonant
		if ( preg_match( '/[bcdfghjklmnpqrstvwxz]$/i', $word ) ) {
			$word .= 'i';
		}

		switch ( $case ) {
			case 'genitive':
				$word .= 'n';
				break;
			case 'elative':
				$word .= ( $aou ? 'sta' : 'stä' );
				break;
			case 'partitive':
				$word .= ( $aou ? 'a' : 'ä' );
				break;
			case 'illative':
				# Double the last letter and add 'n'
				$word .= mb_substr( $word, -1 ) . 'n';
				break;
			case 'inessive':
				$word .= ( $aou ? 'ssa' : 'ssä' );
				break;
			default:
				break;
		}

		return $word;
	}
}
