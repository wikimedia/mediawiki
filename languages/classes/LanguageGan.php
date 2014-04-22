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

require_once __DIR__ . '/../LanguageConverter.php';
require_once __DIR__ . '/LanguageZh.php';

/**
 * @ingroup Language
 */
class GanConverter extends LanguageConverter {
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
			'gan' => '原文',
			'gan-hans' => '简体',
			'gan-hant' => '繁體',
		);
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	function loadDefaultTables() {
		require __DIR__ . '/../../includes/ZhConversion.php';
		$this->mTables = array(
			'gan-hans' => new ReplacementArray( $zh2Hans ),
			'gan-hant' => new ReplacementArray( $zh2Hant ),
			'gan' => new ReplacementArray
		);
	}

	/**
	 * @param string $key
	 * @return string
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
			'gan' => array( 'gan-hans', 'gan-hant' ),
			'gan-hans' => array( 'gan' ),
			'gan-hant' => array( 'gan' ),
		);
		$ml = array(
			'gan' => 'disable',
		);

		$this->mConverter = new GanConverter( $this, 'gan',
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
	function normalizeForSearch( $string, $autoVariant = 'gan-hans' ) {
		// LanguageZh::normalizeForSearch
		return parent::normalizeForSearch( $string, $autoVariant );
	}

}
