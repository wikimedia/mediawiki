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

namespace Wikimedia\Leximorph\Handler\Overrides;

/**
 * IGrammarTransformer
 *
 * Interface for implementing language-specific grammar fallback logic.
 * This contract is used by GrammarFallbackRegistry to apply procedural grammar
 * transformations in cases where declarative grammar rules may not be sufficient.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
interface IGrammarTransformer {
	/**
	 * Applies a procedural transformation to a word for a specific grammatical case.
	 *
	 * @param string $word The input word or phrase to transform.
	 * @param string $case The grammatical case to apply (e.g., "genitive", "dative").
	 *
	 * @since 1.45
	 * @return string The transformed word after applying grammar logic.
	 */
	public function process( string $word, string $case ): string;
}
