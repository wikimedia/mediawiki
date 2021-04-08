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

/**
 * @ingroup Language
 */
class ZhConverter extends LanguageConverter {

	/**
	 * Get Main language code.
	 * @since 1.36
	 *
	 * @return string
	 */
	public function getMainCode(): string {
		return 'zh';
	}

	/**
	 * Get supported variants of the language.
	 * @since 1.36
	 *
	 * @return array
	 */
	public function getLanguageVariants(): array {
		return [
			'zh',
			'zh-hans',
			'zh-hant',
			'zh-cn',
			'zh-hk',
			'zh-mo',
			'zh-my',
			'zh-sg',
			'zh-tw'
		];
	}

	/**
	 * Get language variants fallbacks.
	 * @since 1.36
	 *
	 * @return array
	 */
	public function getVariantsFallbacks(): array {
		return [
			'zh' => [
				'zh-hans',
				'zh-hant',
				'zh-cn',
				'zh-tw',
				'zh-hk',
				'zh-sg',
				'zh-mo',
				'zh-my'
			],
			'zh-hans' => [ 'zh-cn', 'zh-sg', 'zh-my' ],
			'zh-hant' => [ 'zh-tw', 'zh-hk', 'zh-mo' ],
			'zh-cn' => [ 'zh-hans', 'zh-sg', 'zh-my' ],
			'zh-sg' => [ 'zh-hans', 'zh-cn', 'zh-my' ],
			'zh-my' => [ 'zh-hans', 'zh-sg', 'zh-cn' ],
			'zh-tw' => [ 'zh-hant', 'zh-hk', 'zh-mo' ],
			'zh-hk' => [ 'zh-hant', 'zh-mo', 'zh-tw' ],
			'zh-mo' => [ 'zh-hant', 'zh-hk', 'zh-tw' ],
		];
	}

	/**
	 * Get manual level limits for variants supported by converter.
	 * @since 1.36
	 *
	 * @return array
	 */
	public function getAdditionalManualLevel(): array {
		return [
			'zh' => 'disable',
			'zh-hans' => 'unidirectional',
			'zh-hant' => 'unidirectional',
		];
	}

	/**
	 * Get desc. code separator.
	 * @since 1.36
	 *
	 * @return string
	 */
	public function getDescCodeSeparator(): string {
		return '：';
	}

	/**
	 * Get desc. var separator.
	 * @since 1.36
	 *
	 * @return string
	 */
	public function getDescVarSeparator(): string {
		return '；';
	}

	/**
	 * Get variant names.
	 * @since 1.36
	 *
	 * @return array
	 */
	public function getVariantNames(): array {
		$names = [
			'zh' => '原文',
			'zh-hans' => '简体',
			'zh-hant' => '繁體',
			'zh-cn' => '大陆',
			'zh-tw' => '台灣',
			'zh-hk' => '香港',
			'zh-mo' => '澳門',
			'zh-sg' => '新加坡',
			'zh-my' => '大马',
		];
		return array_merge( parent::getVariantNames(), $names );
	}

	protected function loadDefaultTables() {
		$this->mTables = [
			'zh-hans' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hans ),
			'zh-hant' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2Hant ),
			'zh-cn' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2CN ),
			'zh-hk' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2HK ),
			'zh-mo' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2HK ),
			'zh-my' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2CN ),
			'zh-sg' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2CN ),
			'zh-tw' => new ReplacementArray( MediaWiki\Languages\Data\ZhConversion::$zh2TW ),
			'zh' => new ReplacementArray
		];
	}

	protected function postLoadTables() {
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
	public function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'zh' );
	}
}
