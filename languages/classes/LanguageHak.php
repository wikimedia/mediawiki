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
 * Note: Based on LanguageZh and LanguageGan
 * @file
 * @ingroup Language
 */

/**
 * @ingroup Language
 */
class HakConverter extends LanguageConverter {

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
			'hak' => '漢字',
			'hak-hans' => '简体',
			'hak-hant' => '繁體',
			'hak-latn' => 'Pha̍k-fa-sṳ',
		];
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	function loadDefaultTables() {
		$json = file_get_contents( __DIR__ . "/../data/HakConversion.json" );
		$hak2Latn = FormatJson::decode( $json, true );
		$this->mTables = [
			'hak-latn' => new ReplacementArray( $hak2Latn ),
			'hak-hans' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hans ),
			'hak-hant' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hant ),
			'hak' => new ReplacementArray()
		];
	}

	/**
	 * Translate text into a variant.
	 *
	 * @param string $text
	 * @param bool $toVariant
	 *
	 * @return string
	 */
	function translate( $text, $toVariant ) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			// To Latin: first add space between Han characters
			if ( $toVariant == 'hak-latn' ) {
				$pattern = '/(\p{Han})/u';
				$text = preg_replace( $pattern, ' $1 ', $text );
				$text = preg_replace( '/ +/', ' ', $text );
			}
			$text = $this->mTables[$toVariant]->replace( $text );
			if ( $toVariant == 'hak-latn' ) {
				// Clean up spaces
				$text = preg_replace( '/ +/', ' ', $text );
				$text = preg_replace( '/ (\\000?[)\\].,!?:;])/', '$1', $text );
				$text = preg_replace( '/([[(]\\000?) +/', '$1', $text );
				$text = preg_replace( '/(^ | $)/', '', $text );
				$text = preg_replace( '/ ?\\000 \\000 ?/', "\x00 \x00", $text );
			}
		}
		return $text;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'hak' );
	}
}

/**
 * Class that handles Hakka.
 * Right now it distinguishes hak, hak-hans, hak-hant, hak-latn.
 *
 * @ingroup Language
 */
class LanguageHak extends LanguageZh {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = [ 'hak', 'hak-hans', 'hak-hant', 'hak-latn' ];

		$variantfallbacks = [
			'hak' => [ 'hak-hant', 'hak-hans' ],
			'hak-hans' => [ 'hak' ],
			'hak-hant' => [ 'hak' ],
			'hak-latn' => [ 'hak' ],
		];
		$ml = [
			'hak' => 'disable',
		];

		$this->mConverter = new HakConverter( $this, 'hak',
			$variants, $variantfallbacks,
			[],
			$ml
		);

		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}

}
