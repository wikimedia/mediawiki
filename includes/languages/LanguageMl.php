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

/**
 * Malayalam (മലയാളം)
 *
 * @ingroup Languages
 */
class LanguageMl extends Language {
	/**
	 * Convert Unicode 5.0 style Malayalam input to Unicode 5.1, similar to T11413
	 * which is the same issue for Arabic.
	 *
	 * Also fixes miscellaneous problems due to mishandling of ZWJ (e.g. T13162).
	 *
	 * Originally introduced after "[wikitech-l] Unicode equivalence" (Dec 2009)
	 * <https://lists.wikimedia.org/hyperkitty/list/wikitech-l@lists.wikimedia.org/thread/LMMZ3M4757Z5DH42MT75K6GWLAKUBLRD>
	 *
	 * Optimization: This is language-specific to reduce negative performance impact.
	 *
	 * @param string $s
	 * @return string
	 */
	public function normalize( $s ) {
		$s = parent::normalize( $s );
		$s = $this->transformUsingPairFile( MediaWiki\Languages\Data\NormalizeMl::class, $s );
		return $s;
	}
}
