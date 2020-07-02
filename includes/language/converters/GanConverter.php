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
 */

/**
 * @ingroup Language
 */
class GanConverter extends LanguageConverter {
	/**
	 * @param Language $langobj
	 */
	public function __construct( $langobj ) {
		$this->mDescCodeSep = '：';
		$this->mDescVarSep = '；';

		$variants = [ 'gan', 'gan-hans', 'gan-hant' ];
		$variantfallbacks = [
			'gan' => [ 'gan-hans', 'gan-hant' ],
			'gan-hans' => [ 'gan' ],
			'gan-hant' => [ 'gan' ],
		];
		$ml = [
			'gan' => 'disable',
		];

		parent::__construct( $langobj, 'gan',
			$variants,
			$variantfallbacks,
			[],
			$ml
		);

		$names = [
			'gan' => '原文',
			'gan-hans' => '简体',
			'gan-hant' => '繁體',
		];
		$this->mVariantNames = array_merge( $this->mVariantNames, $names );
	}

	protected function loadDefaultTables() {
		$this->mTables = [
			'gan-hans' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hans ),
			'gan-hant' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hant ),
			'gan' => new ReplacementArray
		];
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'gan' );
	}
}
