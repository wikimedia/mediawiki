<?php
namespace MediaWiki\Skins\Vector\Components;

use Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\StubObject\StubUserLang;

/**
 * VectorComponentMenuVariants component
 */
class VectorComponentMenuVariants extends VectorComponentMenu {

	/**
	 * @param array $data
	 * @param Language|StubUserLang $pageLang
	 * @param string $ariaLabel
	 */
	public function __construct( array $data, $pageLang, string $ariaLabel ) {
		parent::__construct( $this->updateVariantsMenuLabel( $data, $pageLang, $ariaLabel ) );
	}

	/**
	 * Change the portlets menu so the label is the selected variant
	 * @param array $portletData
	 * @param Language|StubUserLang $pageLang
	 * @param string $ariaLabel
	 * @return array
	 */
	private function updateVariantsMenuLabel( array $portletData, $pageLang, string $ariaLabel ): array {
		$languageConverterFactory = MediaWikiServices::getInstance()->getLanguageConverterFactory();
		$converter = $languageConverterFactory->getLanguageConverter( $pageLang );
		$portletData['label'] = $pageLang->getVariantname(
			$converter->getPreferredVariant()
		);
		// T289523 Add aria-label data to the language variant switcher.
		$portletData['aria-label'] = $ariaLabel;
		return $portletData;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return parent::getTemplateData();
	}
}
