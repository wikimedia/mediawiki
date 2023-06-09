<?php
namespace MediaWiki\Skins\Vector\Components;

use Html;
use Skin;

/**
 * VectorComponentMainMenuActionLanguageSwitchAlert component
 */
class VectorComponentMainMenuActionLanguageSwitchAlert implements VectorComponent {
	/** @var Skin */
	private $skin;

	/**
	 * @param Skin $skin
	 */
	public function __construct( Skin $skin ) {
		$this->skin = $skin;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$skin = $this->skin;
		$languageSwitchAlert = [
			'html-content' => Html::noticeBox(
				$skin->msg( 'vector-language-redirect-to-top' )->parse(),
				'vector-language-sidebar-alert'
			),
		];
		$headingOptions = [
			'heading' => $skin->msg( 'vector-languages' )->plain(),
		];

		$component = new VectorComponentMainMenuAction(
			'lang-alert', $skin, $languageSwitchAlert, $headingOptions
		);
		return $component->getTemplateData();
	}
}
