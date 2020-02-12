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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;

/**
 * A trivial language converter.
 *
 * For Languages which do not implement variant
 * conversion, for example, German, TrivialLanguageConverter is provided rather than a
 * LanguageConverter when asked for their converter. The TrivialLanguageConverter just
 * returns text unchanged, i.e. it doesn't do any conversion.
 *
 * See https://www.mediawiki.org/wiki/Writing_systems#LanguageConverter.
 *
 * @since 1.35
 * @ingroup Language
 */
class TrivialLanguageConverter implements ILanguageConverter {

	/**
	 * @var Language
	 */
	protected $language;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	public function __construct( $langobj ) {
		$this->language = $langobj;
		$this->titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
	}

	public function autoConvert( $text, $variant = false ) {
		return $text;
	}

	public function autoConvertToAllVariants( $text ) {
		return [ $this->language->getCode() => $text ];
	}

	public function convert( $t ) {
		return $t;
	}

	public function convertTo( $text, $variant ) {
		return $text;
	}

	/**
	 * @param LinkTarget $linkTarget
	 * @return mixed
	 */
	public function convertTitle( LinkTarget $linkTarget ) {
		return $this->titleFormatter->getPrefixedText( $linkTarget );
	}

	public function convertNamespace( $index, $variant = null ) {
		return $this->language->getFormattedNsText( $index );
	}

	/**
	 * @return string[]
	 */
	public function getVariants() {
		return [ $this->language->getCode() ];
	}

	public function getVariantFallbacks( $variant ) {
		return $this->language->getCode();
	}

	public function getPreferredVariant() {
		return $this->language->getCode();
	}

	public function getDefaultVariant() {
		return $this->language->getCode();
	}

	public function getURLVariant() {
		return '';
	}

	public function getConvRuleTitle() {
		return false;
	}

	public function findVariantLink( &$l, &$n, $ignoreOtherCond = false ) {
	}

	public function getExtraHashOptions() {
		return '';
	}

	public function guessVariant( $text, $variant ) {
		return false;
	}

	public function markNoConversion( $text, $noParse = false ) {
		return $text;
	}

	public function convertCategoryKey( $key ) {
		return $key;
	}

	public function validateVariant( $variant = null ) {
		if ( $variant === null ) {
			return null;
		}
		$variant = strtolower( $variant );
		return $variant === $this->language->getCode() ? $variant : null;
	}

	public function translate( $text, $variant ) {
		return $text;
	}

	public function updateConversionTable( LinkTarget $linkTarget ) {
	}

	/**
	 * Used by test suites which need to reset the converter state.
	 *
	 * @private
	 */
	private function reloadTables() {
	}

	/**
	 * Check if this is a language with variants
	 *
	 * @since 1.35
	 *
	 * @return bool
	 */
	public function hasVariants() {
		return count( $this->getVariants() ) > 1;
	}

	/**
	 * Strict check if the language has the specific variant.
	 *
	 * Compare to LanguageConverter::validateVariant() which does a more
	 * lenient check and attempts to coerce the given code to a valid one.
	 *
	 * @since 1.35
	 * @param string $variant
	 * @return bool
	 */
	public function hasVariant( $variant ) {
		return $variant && ( $variant === $this->validateVariant( $variant ) );
	}

	/**
	 * Perform output conversion on a string, and encode for safe HTML output.
	 *
	 * @since 1.35
	 *
	 * @param string $text Text to be converted
	 * @return string
	 */
	public function convertHtml( $text ) {
		return htmlspecialchars( $this->convert( $text ) );
	}
}

/**
 * @deprecated since 1.35 use TrivialLanguageConverter instead
 */
class_alias( TrivialLanguageConverter::class, 'FakeConverter' );
