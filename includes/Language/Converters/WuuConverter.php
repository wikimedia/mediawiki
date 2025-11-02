<?php
/**
 * @license GPL-2.0-or-later
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
