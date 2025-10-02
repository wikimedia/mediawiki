<?php
/**
 * @license GPL-2.0-or-later
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
