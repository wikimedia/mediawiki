<?php
namespace MediaWiki\Skins\Vector\Components;

use Skin;
use SpecialPage;

/**
 * VectorComponentMainMenuActionOptOut component
 */
class VectorComponentMainMenuActionOptOut implements VectorComponent {
	/** @var Skin */
	private $skin;

	/**
	 * T243281: Code used to track clicks to opt-out link.
	 *
	 * The "vct" substring is used to describe the newest "Vector" (non-legacy)
	 * feature. The "w" describes the web platform. The "1" describes the version
	 * of the feature.
	 *
	 * @see https://wikitech.wikimedia.org/wiki/Provenance
	 * @var string
	 */
	private const OPT_OUT_LINK_TRACKING_CODE = 'vctw1';

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
		// Note: This data is also passed to legacy template where it is unused.
		$optOutUrl = [
			'text' => $skin->msg( 'vector-opt-out' )->text(),
			'href' => SpecialPage::getTitleFor(
				'Preferences',
				false,
				'mw-prefsection-rendering-skin'
			)->getLinkURL( 'useskin=vector&wprov=' . self::OPT_OUT_LINK_TRACKING_CODE ),
			'title' => $skin->msg( 'vector-opt-out-tooltip' )->text(),
			'active' => false,
		];
		$htmlData = [
			'link' => $optOutUrl,
		];
		$component = new VectorComponentMainMenuAction( 'opt-out', $skin, $htmlData, [] );
		return $component->getTemplateData();
	}
}
