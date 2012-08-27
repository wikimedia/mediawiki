<?php
/**
 * Gan Chinese specific code.
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

require_once( __DIR__ . '/../LanguageConverter.php' );
require_once( __DIR__ . '/LanguageZh.php' );

/**
 * @ingroup Language
 */
class GanConverter extends LanguageConverter {

	/**
	 * @param $langobj Language
	 * @param $maincode string
	 * @param $variants array
	 * @param $variantfallbacks array
	 * @param $flags array
	 * @param $manualLevel array
	 */
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
		require( __DIR__ . "/../../includes/ZhConversion.php" );
		$this->mTables = array(
			'gan-hans' => new ReplacementArray( $zh2Hans ),
			'gan-hant' => new ReplacementArray( $zh2Hant ),
			'gan'      => new ReplacementArray
		);
	}

	/**
	 * there shouldn't be any latin text in Chinese conversion, so no need
	 * to mark anything.
	 * $noParse is there for compatibility with LanguageConvert::markNoConversion
	 *
	 * @param $text string
	 * @param $noParse bool
	 *
	 * @return string
	 */
	function markNoConversion( $text, $noParse = false ) {
		return $text;
	}

	/**
	 * @param $key string
	 * @return String
	 */
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

	/**
	 * this should give much better diff info
	 *
	 * @param $text string
	 * @return string
	 */
	function segmentForDiff( $text ) {
		return preg_replace(
			"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
			"' ' .\"$1\"", $text );
	}

	/**
	 * @param $text string
	 * @return string
	 */
	function unsegmentForDiff( $text ) {
		return preg_replace(
			"/ ([\\xc0-\\xff][\\x80-\\xbf]*)/e",
			"\"$1\"", $text );
	}

	/**
	 * word segmentation
	 *
	 * @param $string string
	 * @param $autoVariant string
	 * @return String
	 */
	function normalizeForSearch( $string, $autoVariant = 'gan-hans' ) {
		// LanguageZh::normalizeForSearch
		return parent::normalizeForSearch( $string, $autoVariant );
	}

	/**
	 * @param $termsArray array
	 * @return array
	 */
	function convertForSearchResult( $termsArray ) {
		$terms = implode( '|', $termsArray );
		$terms = self::convertDoubleWidth( $terms );
		$terms = implode( '|', $this->mConverter->autoConvertToAllVariants( $terms ) );
		$ret = array_unique( explode( '|', $terms ) );
		return $ret;
	}
}
