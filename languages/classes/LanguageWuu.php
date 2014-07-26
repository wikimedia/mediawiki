<?php
/**
 * Wuu Chinese specific code.
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

require_once __DIR__ . '/../LanguageConverter.php';
require_once __DIR__ . '/LanguageZh.php';

/**
 * @ingroup Language
 */
class WuuConverter extends LanguageConverter {
	/**
	 * @param Language $langobj
	 * @param string $maincode
	 * @param array $variants
	 * @param array $variantfallbacks
	 * @param array $flags
	 * @param array $manualLevel
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
			'wuu' => '原文',
			'wuu-hans' => '简体',
			'wuu-hant' => '正體',
		);
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	function loadDefaultTables() {
		require __DIR__ . '/../../includes/ZhConversion.php';
		$this->mTables = array(
			'wuu-hans' => new ReplacementArray( $zh2Hans ),
			'wuu-hant' => new ReplacementArray( $zh2Hant ),
			'wuu' => new ReplacementArray
		);
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'wuu' );
	}
}

/**
 * class that handles both Traditional and Simplified Chinese
 * right now it only distinguish wuu_hans, wuu_hant.
 *
 * @ingroup Language
 */
class LanguageWuu extends LanguageZh {
	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'wuu', 'wuu-hans', 'wuu-hant' );
		$variantfallbacks = array(
			'wuu' => array( 'wuu-hans', 'wuu-hant' ),
			'wuu-hans' => array( 'wuu' ),
			'wuu-hant' => array( 'wuu' ),
		);
		$ml = array(
			'wuu' => 'disable',
		);

		$this->mConverter = new WuuConverter( $this, 'wuu',
			$variants, $variantfallbacks,
			array(),
			$ml );

		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}

	/**
	 * word segmentation
	 *
	 * @param string $string
	 * @param string $autoVariant
	 * @return string
	 */
	function normalizeForSearch( $string, $autoVariant = 'wuu-hans' ) {
		// LanguageZh::normalizeForSearch
		return parent::normalizeForSearch( $string, $autoVariant );
	}

}
