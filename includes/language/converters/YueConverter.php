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

use MediaWiki\Languages\Data\ZhConversion;

/**
 * Cantonese converter routine
 *
 * @ingroup Languages
 */
class YueConverter extends LanguageConverter {

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function getMainCode(): string {
		return 'yue';
	}

	/**
	 * @inheritDoc
	 *
	 * @return array
	 */
	public function getLanguageVariants(): array {
		return [
			'yue-hant',
			'yue-hans',
		];
	}

	/**
	 * @inheritDoc
	 *
	 * @return array
	 */
	public function getVariantsFallbacks(): array {
		return [
			'yue-hant' => [ 'yue-hans' ],
			'yue-hans' => [ 'yue-hant' ],
		];
	}

	/**
	 * @inheritDoc
	 *
	 * @return array
	 */
	protected function getAdditionalManualLevel(): array {
		return [ 'yue-hant' => 'disable' ];
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function getDescCodeSeparator(): string {
		return '：';
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function getDescVarSeparator(): string {
		return '；';
	}

	/**
	 * @inheritDoc
	 *
	 * @return array
	 */
	public function getVariantNames(): array {
		$names = [
			'yue-hant' => '原文',
			'yue-hans' => '简体',
		];
		return array_merge( parent::getVariantNames(), $names );
	}

	protected function loadDefaultTables() {
		$this->mTables = [
			'yue-hans' => new ReplacementArray( ZhConversion::$zh2Hans ),
			'yue-hant' => new ReplacementArray,
		];
	}

}
