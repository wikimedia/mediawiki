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
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\StubObject\StubUserLang;
use MediaWiki\Title\TitleFormatter;

/**
 * A trivial language converter.
 *
 * For Languages which do not implement variant
 * conversion, for example, German, TrivialLanguageConverter is provided rather than a
 * LanguageConverter when asked for their converter. The TrivialLanguageConverter just
 * returns text unchanged, i.e., it doesn't do any conversion.
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

	/**
	 * Creates a converter for languages that don't have variants. This method is internal
	 * and should be called for LanguageConverterFactory only
	 *
	 * @param Language|StubUserLang $langobj
	 * @param TitleFormatter|null $titleFormatter
	 *
	 * @internal
	 */
	public function __construct(
		$langobj,
		?TitleFormatter $titleFormatter = null
	) {
		$this->language = $langobj;
		$this->titleFormatter = $titleFormatter ?? MediaWikiServices::getInstance()->getTitleFormatter();
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

	public function convertTo( $text, $variant, bool $clearState = true ) {
		return $text;
	}

	public function convertSplitTitle( $title ) {
		$mainText = $this->titleFormatter->getText( $title );

		$index = $title->getNamespace();
		try {
			$nsWithUnderscores = $this->titleFormatter->getNamespaceName( $index, $mainText );
		} catch ( InvalidArgumentException ) {
			// T165149: see TitleFormatter::formatTitle()
			$nsWithUnderscores = $this->language->getNsText( NS_SPECIAL );
			$mainText = "Badtitle/NS$index:$mainText";
		}
		$nsText = str_replace( '_', ' ', $nsWithUnderscores );

		return [ $nsText, ':', $mainText ];
	}

	public function convertTitle( $title ) {
		return $this->titleFormatter->getPrefixedText( $title );
	}

	public function convertNamespace( $index, $variant = null ) {
		return $this->language->getFormattedNsText( $index );
	}

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

	public function updateConversionTable( PageIdentity $page ) {
	}

	/**
	 * Used by test suites which need to reset the converter state.
	 *
	 * Called by ParserTestRunner with the help of TestingAccessWrapper
	 */
	private function reloadTables() {
	}

	public function hasVariants() {
		return count( $this->getVariants() ) > 1;
	}

	public function hasVariant( $variant ) {
		return $variant && ( $variant === $this->validateVariant( $variant ) );
	}

	public function convertHtml( $text ) {
		return htmlspecialchars( $this->convert( $text ) );
	}
}
