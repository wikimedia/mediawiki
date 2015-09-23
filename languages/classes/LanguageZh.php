<?php
/**
 * Chinese specific code.
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
require_once __DIR__ . '/LanguageZh_hans.php';

/**
 * @ingroup Language
 */
class ZhConverter extends LanguageConverter {
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
			'zh' => '原文',
			'zh-hans' => '简体',
			'zh-hant' => '繁體',
			'zh-cn' => '大陆',
			'zh-tw' => '台灣',
			'zh-hk' => '香港',
			'zh-mo' => '澳門',
			'zh-sg' => '新加坡',
			'zh-my' => '大马',
		);
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	function loadDefaultTables() {
		require __DIR__ . "/../../includes/ZhConversion.php";
		$this->mTables = array(
			'zh-hans' => new ReplacementArray( $zh2Hans ),
			'zh-hant' => new ReplacementArray( $zh2Hant ),
			'zh-cn' => new ReplacementArray( $zh2CN ),
			'zh-hk' => new ReplacementArray( $zh2HK ),
			'zh-mo' => new ReplacementArray( $zh2HK ),
			'zh-my' => new ReplacementArray( $zh2CN ),
			'zh-sg' => new ReplacementArray( $zh2CN ),
			'zh-tw' => new ReplacementArray( $zh2TW ),
			'zh' => new ReplacementArray
		);
	}

	function postLoadTables() {
		$this->mTables['zh-cn']->setArray(
			$this->mTables['zh-cn']->getArray() + $this->mTables['zh-hans']->getArray()
		);
		$this->mTables['zh-hk']->setArray(
			$this->mTables['zh-hk']->getArray() + $this->mTables['zh-hant']->getArray()
		);
		$this->mTables['zh-mo']->setArray(
			$this->mTables['zh-mo']->getArray() + $this->mTables['zh-hant']->getArray()
		);
		$this->mTables['zh-my']->setArray(
			$this->mTables['zh-my']->getArray() + $this->mTables['zh-hans']->getArray()
		);
		$this->mTables['zh-sg']->setArray(
			$this->mTables['zh-sg']->getArray() + $this->mTables['zh-hans']->getArray()
		);
		$this->mTables['zh-tw']->setArray(
			$this->mTables['zh-tw']->getArray() + $this->mTables['zh-hant']->getArray()
		);
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'zh' );
	}
}

/**
 * class that handles both Traditional and Simplified Chinese
 * right now it only distinguish zh_hans, zh_hant, zh_cn, zh_tw, zh_sg and zh_hk.
 *
 * @ingroup Language
 */
class LanguageZh extends LanguageZh_hans {
	function __construct() {
		global $wgHooks;

		parent::__construct();

		$variants = array(
			'zh',
			'zh-hans',
			'zh-hant',
			'zh-cn',
			'zh-hk',
			'zh-mo',
			'zh-my',
			'zh-sg',
			'zh-tw'
		);

		$variantfallbacks = array(
			'zh' => array( 'zh-hans', 'zh-hant', 'zh-cn', 'zh-tw', 'zh-hk', 'zh-sg', 'zh-mo', 'zh-my' ),
			'zh-hans' => array( 'zh-cn', 'zh-sg', 'zh-my' ),
			'zh-hant' => array( 'zh-tw', 'zh-hk', 'zh-mo' ),
			'zh-cn' => array( 'zh-hans', 'zh-sg', 'zh-my' ),
			'zh-sg' => array( 'zh-hans', 'zh-cn', 'zh-my' ),
			'zh-my' => array( 'zh-hans', 'zh-sg', 'zh-cn' ),
			'zh-tw' => array( 'zh-hant', 'zh-hk', 'zh-mo' ),
			'zh-hk' => array( 'zh-hant', 'zh-mo', 'zh-tw' ),
			'zh-mo' => array( 'zh-hant', 'zh-hk', 'zh-tw' ),
		);
		$ml = array(
			'zh' => 'disable',
			'zh-hans' => 'unidirectional',
			'zh-hant' => 'unidirectional',
		);

		$this->mConverter = new ZhConverter( $this, 'zh',
								$variants, $variantfallbacks,
								array(),
								$ml );

		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}

	/**
	 * this should give much better diff info
	 *
	 * @param string $text
	 * @return string
	 */
	function segmentForDiff( $text ) {
		return preg_replace( '/[\xc0-\xff][\x80-\xbf]*/', ' $0', $text );
	}

	/**
	 * @param string $text
	 * @return string
	 */
	function unsegmentForDiff( $text ) {
		return preg_replace( '/ ([\xc0-\xff][\x80-\xbf]*)/', '$1', $text );
	}

	/**
	 * auto convert to zh-hans and normalize special characters.
	 *
	 * @param string $string
	 * @param string $autoVariant Defaults to 'zh-hans'
	 * @return string
	 */
	function normalizeForSearch( $string, $autoVariant = 'zh-hans' ) {

		// always convert to zh-hans before indexing. it should be
		// better to use zh-hans for search, since conversion from
		// Traditional to Simplified is less ambiguous than the
		// other way around
		$s = $this->mConverter->autoConvert( $string, $autoVariant );
		// LanguageZh_hans::normalizeForSearch
		$s = parent::normalizeForSearch( $s );
		return $s;

	}

	/**
	 * @param array $termsArray
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
