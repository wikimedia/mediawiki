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

use MediaWiki\Language\LanguageConverter;
use MediaWiki\Languages\Data\ZhConversion;
use Wikimedia\ReplacementArray;

/**
 * Wu language specific code.
 *
 * @ingroup Languages
 */
class WuuConverter extends LanguageConverter {

	public function getMainCode(): string {
		return 'wuu';
	}

	public function getLanguageVariants(): array {
		return [ 'wuu', 'wuu-hans', 'wuu-hant' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'wuu' => [ 'wuu-hans', 'wuu-hant' ],
			'wuu-hans' => [ 'wuu' ],
			'wuu-hant' => [ 'wuu' ],
		];
	}

	protected function getAdditionalManualLevel(): array {
		return [ 'wuu' => 'disable' ];
	}

	public function getDescCodeSeparator(): string {
		return '：';
	}

	public function getDescVarSeparator(): string {
		return '；';
	}

	public function getVariantNames(): array {
		$names = [
			'wuu' => '原文',
			'wuu-hans' => '简体',
			'wuu-hant' => '正體',
		];
		return array_merge( parent::getVariantNames(), $names );
	}

	protected function loadDefaultTables(): array {
		return [
			'wuu-hans' => new ReplacementArray( ZhConversion::ZH_TO_HANS ),
			'wuu-hant' => new ReplacementArray( ZhConversion::ZH_TO_HANT ),
			'wuu' => new ReplacementArray,
		];
	}

}
