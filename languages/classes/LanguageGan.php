<?php

require_once( dirname( __FILE__ ) . '/../LanguageConverter.php' );
require_once( dirname( __FILE__ ) . '/LanguageZh.php' );

/**
 * @ingroup Language
 */
class GanConverter extends LanguageConverter {

	function __construct( $langobj, $maincode,
								$variants = array(),
								$variantfallbacks = array(),
								$flags = array(),
								$manualLevel = array() ) {
		$this->mDescCodeSep = '：';
		$this->mDescVarSep = '；';
		parent::__construct( $langobj, $maincode,
									$variants,
									$variantfallbacks,
									$flags,
									$manualLevel );
		$names = array(
			'gan'      => '原文',
			'gan-hans' => '简体',
			'gan-hant' => '繁體',
		);
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	function loadDefaultTables() {
		require( dirname( __FILE__ ) . "/../../includes/ZhConversion.php" );
		$this->mTables = array(
			'gan-hans' => new ReplacementArray( $zh2Hans ),
			'gan-hant' => new ReplacementArray( $zh2Hant ),
			'gan'      => new ReplacementArray
		);
	}

	/* there shouldn't be any latin text in Chinese conversion, so no need
	   to mark anything.
	   $noParse is there for compatibility with LanguageConvert::markNoConversion
	 */
	function markNoConversion( $text, $noParse = false ) {
		return $text;
	}

	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'gan' );
	}
}

/**
 * class that handles both Traditional and Simplified Chinese
 * right now it only distinguish gan_hans, gan_hant.
 *
 * @ingroup Language
 */
class LanguageGan extends LanguageZh {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'gan', 'gan-hans', 'gan-hant' );
		$variantfallbacks = array(
			'gan'      => array( 'gan-hans', 'gan-hant' ),
			'gan-hans' => array( 'gan' ),
			'gan-hant' => array( 'gan' ),
		);
		$ml = array(
			'gan'      => 'disable',
		);

		$this->mConverter = new GanConverter( $this, 'gan',
								$variants, $variantfallbacks,
								array(),
								$ml );

		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}

	# this should give much better diff info
	function segmentForDiff( $text ) {
		return preg_replace(
			"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
			"' ' .\"$1\"", $text );
	}

	function unsegmentForDiff( $text ) {
		return preg_replace(
			"/ ([\\xc0-\\xff][\\x80-\\xbf]*)/e",
			"\"$1\"", $text );
	}

	// word segmentation
	function normalizeForSearch( $string, $autoVariant = 'gan-hans' ) {
		// LanguageZh::normalizeForSearch
		return parent::normalizeForSearch( $string, $autoVariant );
	}

	function convertForSearchResult( $termsArray ) {
		$terms = implode( '|', $termsArray );
		$terms = self::convertDoubleWidth( $terms );
		$terms = implode( '|', $this->mConverter->autoConvertToAllVariants( $terms ) );
		$ret = array_unique( explode( '|', $terms ) );
		return $ret;
	}
}
