<?php
/**
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
 * Chinese converter routine.
 *
 * @ingroup Languages
 */
class ZhConverter extends LanguageConverter {

	public function getMainCode(): string {
		return 'zh';
	}

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
			'zh-sg' => [ 'zh-my', 'zh-hans', 'zh-cn' ],
			'zh-my' => [ 'zh-sg', 'zh-hans', 'zh-cn' ],
			'zh-tw' => [ 'zh-hant', 'zh-hk', 'zh-mo' ],
			'zh-hk' => [ 'zh-mo', 'zh-hant', 'zh-tw' ],
			'zh-mo' => [ 'zh-hk', 'zh-hant', 'zh-tw' ],
		];
	}

	public function getAdditionalManualLevel(): array {
		return [
			'zh' => 'disable',
			'zh-hans' => 'unidirectional',
			'zh-hant' => 'unidirectional',
		];
	}

	public function getDescCodeSeparator(): string {
		return '：';
	}

	public function getDescVarSeparator(): string {
		return '；';
	}

	public function getVariantNames(): array {
		$names = [
			'zh' => '原文',
			'zh-hans' => '简体',
			'zh-hant' => '繁體',
			'zh-cn' => '大陆',
			'zh-tw' => '臺灣',
			'zh-hk' => '香港',
			'zh-mo' => '澳門',
			'zh-sg' => '新加坡',
			'zh-my' => '大马',
		];
		return array_merge( parent::getVariantNames(), $names );
	}

	protected function loadDefaultTables(): array {
		return [
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

	protected function postLoadTables( &$tables ) {
		$tables['zh-cn']->setArray(
			$tables['zh-cn']->getArray() + $tables['zh-hans']->getArray()
		);
		$tables['zh-hk']->setArray(
			$tables['zh-hk']->getArray() + $tables['zh-hant']->getArray()
		);
		$tables['zh-mo']->setArray(
			$tables['zh-mo']->getArray() + $tables['zh-hant']->getArray()
		);
		$tables['zh-my']->setArray(
			$tables['zh-my']->getArray() + $tables['zh-hans']->getArray()
		);
		$tables['zh-sg']->setArray(
			$tables['zh-sg']->getArray() + $tables['zh-hans']->getArray()
		);
		$tables['zh-tw']->setArray(
			$tables['zh-tw']->getArray() + $tables['zh-hant']->getArray()
		);
	}

	public function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'zh' );
	}
}
