<?php
namespace MediaWiki\Skins\Vector\Components;

use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use MessageLocalizer;
use Skin;
use User;

/**
 * VectorComponentMainMenu component
 */
class VectorComponentMainMenu implements VectorComponent {
	/** @var VectorComponent|null */
	private $optOut;
	/** @var VectorComponent|null */
	private $alert;
	/** @var array */
	private $sidebarData;
	/** @var array */
	private $languageData;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var bool */
	private $isPinned;
	/** @var VectorComponentPinnableHeader|null */
	private $pinnableHeader;
	/** @var string */
	public const ID = 'vector-main-menu';

	/**
	 * @param array $sidebarData
	 * @param bool $shouldLanguageAlertBeInSidebar
	 * @param array $languageData
	 * @param MessageLocalizer $localizer
	 * @param User $user
	 * @param FeatureManager $featureManager
	 * @param Skin $skin
	 */
	public function __construct(
		array $sidebarData,
		bool $shouldLanguageAlertBeInSidebar,
		array $languageData,
		MessageLocalizer $localizer,
		User $user,
		FeatureManager $featureManager,
		Skin $skin
	) {
		$this->sidebarData = $sidebarData;
		$this->languageData = $languageData;
		$this->localizer = $localizer;
		$this->isPinned = $featureManager->isFeatureEnabled( Constants::FEATURE_MAIN_MENU_PINNED );

		$this->pinnableHeader = new VectorComponentPinnableHeader(
			$this->localizer,
			$this->isPinned,
			self::ID,
			'main-menu-pinned'
		);

		if ( $user->isRegistered() ) {
			$this->optOut = new VectorComponentMainMenuActionOptOut( $skin );
		}
		if ( $shouldLanguageAlertBeInSidebar ) {
			$this->alert = new VectorComponentMainMenuActionLanguageSwitchAlert( $skin );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$action = $this->optOut;
		$alert = $this->alert;
		$pinnableHeader = $this->pinnableHeader;

		$portletsRest = [];
		foreach ( $this->sidebarData[ 'array-portlets-rest' ] as $data ) {
			$portletsRest[] = ( new VectorComponentMenu( $data ) )->getTemplateData();
		}
		$firstPortlet = new VectorComponentMenu( $this->sidebarData['data-portlets-first'] );
		$languageMenu = new VectorComponentMenu( $this->languageData );

		$pinnableContainer = new VectorComponentPinnableContainer( self::ID, $this->isPinned );
		$pinnableElement = new VectorComponentPinnableElement( self::ID );

		return $pinnableElement->getTemplateData() + $pinnableContainer->getTemplateData() + [
			'data-portlets-first' => $firstPortlet->getTemplateData(),
			'array-portlets-rest' => $portletsRest,
			'data-main-menu-action' => $action ? $action->getTemplateData() : null,
			// T295555 Add language switch alert message temporarily (to be removed).
			'data-vector-language-switch-alert' => $alert ? $alert->getTemplateData() : null,
			'data-pinnable-header' => $pinnableHeader ? $pinnableHeader->getTemplateData() : null,
			'data-languages' => $languageMenu->getTemplateData(),
		];
	}
}
