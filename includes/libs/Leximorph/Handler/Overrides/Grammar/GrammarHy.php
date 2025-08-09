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
 * GrammarHy
 *
 * Implements grammar transformations for Armenian (hy).
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Ruben Vardanyan (Me@RubenVardanyan.com)
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarHy implements IGrammarTransformer {
	/**
	 * Applies Armenian-specific grammatical transformations.
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = [];
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/u", $word ) ) {
			switch ( $case ) {
				# սեռական հոլով
				case 'genitive':
					if ( implode( '', array_slice( $ar[0], -1 ) ) == 'ա' ) {
						$word = implode( '', array_slice( $ar[0], 0, -1 ) ) . 'այի';
					} elseif ( implode( '', array_slice( $ar[0], -1 ) ) == 'ո' ) {
						$word = implode( '', array_slice( $ar[0], 0, -1 ) ) . 'ոյի';
					} elseif ( implode( '', array_slice( $ar[0], -4 ) ) == 'գիրք' ) {
						$word = implode( '', array_slice( $ar[0], 0, -4 ) ) . 'գրքի';
					} else {
						$word .= 'ի';
					}
					break;
				# Տրական հոլով
				case 'dative':
					# stub
					break;
				# Հայցական հոլով
				case 'accusative':
					# stub
					break;
				case 'instrumental':
					# stub
					break;
				case 'prepositional':
					# stub
					break;
				default:
					break;
			}
		}

		return $word;
	}
}
