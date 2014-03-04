<?php
/**
 * Hakka specific code.
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
 * Note: Based on LanguageZh
 * @file
 * @ingroup Language
 */

require_once __DIR__ . '/../LanguageConverter.php';

/**
 * @ingroup Language
 */
class HakConverter extends LanguageConverter {

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
			'hak' => '漢字',
			'hak-lat' => 'Pha̍k-fa-sṳ',
		);
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	function loadDefaultTables() {
		require __DIR__ . "/../../includes/ZhConversion.php";
		$this->mTables = array(
			'hak-lat' => new ReplacementArray( $zh2HAK ),
			'hak' => new ReplacementArray
		);
	}

	/**
	 * @param $key string
	 * @return String
	 */
	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'hak' );
	}
}

/**
 * class that handles Hakka
 * right now it only distinguish hak, hat_lat
 *
 * @ingroup Language
 */
class LanguageHak extends Language {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'hak', 'hak-lat' );

		$variantfallbacks = array(
			'hak' => array( 'zh-hant' ),
			'hak-lat' => array( 'hak' ),
		);
		$ml = array(
			'hak' => 'disable',
			'hak-lat' => 'disable',
		);

		$this->mConverter = new HakConverter( $this, 'hak',
								$variants, $variantfallbacks,
								array(),
								$ml );

		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}

	/**
	 * this should give much better diff info
	 *
	 * @param $text string
	 * @return string
	 */
	function segmentForDiff( $text ) {
		return preg_replace( '/[\xc0-\xff][\x80-\xbf]*/', ' $0', $text );
	}

	/**
	 * @param $text string
	 * @return string
	 */
	function unsegmentForDiff( $text ) {
		return preg_replace( '/ ([\xc0-\xff][\x80-\xbf]*)/', '$1', $text );
	}

	/**
	 * @return bool
	 */
	function hasWordBreaks() {
		return false;
	}

	/**
	 * Eventually this should be a word segmentation;
	 * for now just treat each character as a word.
	 * @todo FIXME: Only do this for Han characters...
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	function segmentByWord( $string ) {
		$reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
		$s = self::insertSpace( $string, $reg );
		return $s;
	}

}

