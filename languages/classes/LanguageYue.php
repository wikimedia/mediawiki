<?php
/**
 * Cantonese specific code.
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
 * @ingroup Language
 */
class YueConverter extends LanguageConverter {

	/**
	 * @param Language $langobj
	 * @param string $maincode
	 * @param array $variants
	 * @param array $variantfallbacks
	 * @param array $flags
	 * @param array $manualLevel
	 */
	function __construct( $langobj, $maincode,
		$variants = [],
		$variantfallbacks = [],
		$flags = [],
		$manualLevel = []
	) {
		$this->mDescCodeSep = '：';
		$this->mDescVarSep = '；';
		parent::__construct( $langobj, $maincode,
			$variants,
			$variantfallbacks,
			$flags,
			$manualLevel
		);
		$names = [
			'yue' => '原文',
			'yue-hans' => '简体',
			'yue-hant' => '繁體',
		];
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	/**
	 * Eventually this should be a word segmentation;
	 * for now just treat each character as a word.
	 * @todo FIXME: Only do this for Han characters...
	 *
	 * @param string $string
	 * @return string
	 */
	function segmentByWord( $string ) {
		$reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
		$s = self::insertSpace( $string, $reg );
		return $s;
	}

	function loadDefaultTables() {
		$this->mTables = [
			'yue-hans' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hans ),
			'yue-hant' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hant ),
			'yue' => new ReplacementArray
		];
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'yue' );
	}
}

/**
 * class that handles both Traditional and Simplified Chinese
 * right now it only distinguish yue_hans, yue_hant.
 *
 * @ingroup Language
 */
class LanguageYue extends LanguageZh {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = [ 'yue', 'yue-hans', 'yue-hant' ];
		$variantfallbacks = [
			'yue' => [ 'yue-hans', 'yue-hant' ],
			'yue-hans' => [ 'yue' ],
			'yue-hant' => [ 'yue' ],
		];
		$ml = [
			'yue' => 'disable',
		];

		$this->mConverter = new YueConverter( $this, 'yue',
			$variants, $variantfallbacks,
			[],
			$ml
		);

		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}

	/**
	 * word segmentation
	 *
	 * @param string $string
	 * @param string $autoVariant
	 * @return string
	 */
	function normalizeForSearch( $string, $autoVariant = 'yue-hans' ) {
		// LanguageZh::normalizeForSearch
		return parent::normalizeForSearch( $string, $autoVariant );
	}

}
