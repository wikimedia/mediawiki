<?php
/**
 * Internationalisation code.
 *
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
 * @ingroup Language
 */

/**
 * A fake language converter
 *
 * @ingroup Language
 */
class FakeConverter {
	/**
	 * @var Language
	 */
	public $mLang;

	function __construct( $langobj ) {
		$this->mLang = $langobj;
	}

	function autoConvert( $text, $variant = false ) {
		return $text;
	}

	function autoConvertToAllVariants( $text ) {
		return [ $this->mLang->getCode() => $text ];
	}

	function convert( $t ) {
		return $t;
	}

	function convertTo( $text, $variant ) {
		return $text;
	}

	/**
	 * @param Title $t
	 * @return mixed
	 */
	function convertTitle( $t ) {
		return $t->getPrefixedText();
	}

	function convertNamespace( $ns ) {
		return $this->mLang->getFormattedNsText( $ns );
	}

	function getVariants() {
		return [ $this->mLang->getCode() ];
	}

	function getVariantFallbacks( $variant ) {
		return $this->mLang->getCode();
	}

	function getPreferredVariant() {
		return $this->mLang->getCode();
	}

	function getDefaultVariant() {
		return $this->mLang->getCode();
	}

	function getURLVariant() {
		return '';
	}

	function getConvRuleTitle() {
		return false;
	}

	function findVariantLink( &$l, &$n, $ignoreOtherCond = false ) {
	}

	function getExtraHashOptions() {
		return '';
	}

	function getParsedTitle() {
		return '';
	}

	function markNoConversion( $text, $noParse = false ) {
		return $text;
	}

	function convertCategoryKey( $key ) {
		return $key;
	}

	function validateVariant( $variant = null ) {
		return $variant === $this->mLang->getCode() ? $variant : null;
	}

	function translate( $text, $variant ) {
		return $text;
	}

	public function updateConversionTable( Title $title ) {
	}
}
