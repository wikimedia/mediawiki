<?php
/**
 * @license GPL-2.0-or-later
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

	/** @inheritDoc */
	public function autoConvert( $text, $variant = false ) {
		return $text;
	}

	/** @inheritDoc */
	public function autoConvertToAllVariants( $text ) {
		return [ $this->language->getCode() => $text ];
	}

	/** @inheritDoc */
	public function convert( $t ) {
		return $t;
	}

	/** @inheritDoc */
	public function convertTo( $text, $variant, bool $clearState = true ) {
		return $text;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function convertTitle( $title ) {
		return $this->titleFormatter->getPrefixedText( $title );
	}

	/** @inheritDoc */
	public function convertNamespace( $index, $variant = null ) {
		return $this->language->getFormattedNsText( $index );
	}

	/** @inheritDoc */
	public function getVariants() {
		return [ $this->language->getCode() ];
	}

	/** @inheritDoc */
	public function getVariantFallbacks( $variant ) {
		return $this->language->getCode();
	}

	/** @inheritDoc */
	public function getPreferredVariant() {
		return $this->language->getCode();
	}

	/** @inheritDoc */
	public function getDefaultVariant() {
		return $this->language->getCode();
	}

	/** @inheritDoc */
	public function getURLVariant() {
		return '';
	}

	/** @inheritDoc */
	public function getConvRuleTitle() {
		return false;
	}

	/** @inheritDoc */
	public function findVariantLink( &$l, &$n, $ignoreOtherCond = false ) {
	}

	/** @inheritDoc */
	public function getExtraHashOptions() {
		return '';
	}

	/** @inheritDoc */
	public function guessVariant( $text, $variant ) {
		return false;
	}

	/** @inheritDoc */
	public function markNoConversion( $text, $noParse = false ) {
		return $text;
	}

	/** @inheritDoc */
	public function convertCategoryKey( $key ) {
		return $key;
	}

	/** @inheritDoc */
	public function validateVariant( $variant = null ) {
		if ( $variant === null ) {
			return null;
		}
		$variant = strtolower( $variant );
		return $variant === $this->language->getCode() ? $variant : null;
	}

	/** @inheritDoc */
	public function translate( $text, $variant ) {
		return $text;
	}

	/** @inheritDoc */
	public function updateConversionTable( PageIdentity $page ) {
	}

	/**
	 * Used by test suites which need to reset the converter state.
	 *
	 * Called by ParserTestRunner with the help of TestingAccessWrapper
	 */
	private function reloadTables() {
	}

	/** @inheritDoc */
	public function hasVariants() {
		return count( $this->getVariants() ) > 1;
	}

	/** @inheritDoc */
	public function hasVariant( $variant ) {
		return $variant && ( $variant === $this->validateVariant( $variant ) );
	}

	/** @inheritDoc */
	public function convertHtml( $text ) {
		return htmlspecialchars( $this->convert( $text ) );
	}
}
