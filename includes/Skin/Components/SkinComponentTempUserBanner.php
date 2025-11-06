<?php

namespace MediaWiki\Skin;

use MediaWiki\Html\Html;
use MediaWiki\User\User;
use MessageLocalizer;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 *
 * NOTE: This class is currently *not registered* via the SkinComponentRegistry
 * and cannot be called via Skin::getComponent.
 * Because of it's unsuitability for rendering via mustache templates
 * (it renders its own HTML and returns no data),
 * it is appended directly to skin data in Skin::getTemplateData.
 * @unstable
 */
class SkinComponentTempUserBanner implements SkinComponent {
	/** @var string */
	private $loginUrl;
	/** @var string */
	private $createAccountUrl;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var bool */
	private $isTempUser;
	/** @var string */
	private $username;

	/**
	 * @param string|array $returnto
	 * @param MessageLocalizer $localizer
	 * @param User $user
	 */
	public function __construct( $returnto, $localizer, $user ) {
		$this->loginUrl = SkinComponentUtils::makeSpecialUrl( 'Userlogin', $returnto );
		$this->createAccountUrl = SkinComponentUtils::makeSpecialUrl( 'CreateAccount', $returnto );
		$this->localizer = $localizer;
		$this->isTempUser = $user->isTemp();
		$this->username = $user->getName(); // getUser
	}

	private function createLoginLink(): string {
		return Html::element( 'a',
		[
			'href' => $this->loginUrl,
			'id' => 'pt-login',
			'title' => $this->localizer->msg( 'tooltip-pt-login' )->text(),
			'class' => 'cdx-button cdx-button--fake-button cdx-button--fake-button--enabled'
		],
		$this->localizer->msg( 'pt-login' )->text() );
	}

	private function createAccountLink(): string {
		return Html::element( 'a',
			[
				'href' => $this->createAccountUrl,
				'id' => 'pt-createaccount',
				'title' => $this->localizer->msg( 'tooltip-pt-createaccount' )->text(),
				'class' => 'cdx-button cdx-button--fake-button cdx-button--fake-button--enabled'
			],
			$this->localizer->msg( 'pt-createaccount' )->text()
		);
	}

	private function renderBannerHTML(): string {
		return Html::rawElement( 'div', [ 'class' => 'mw-temp-user-banner' ],
			Html::rawElement( 'p', [],
				$this->localizer->msg( 'temp-user-banner-description' )->escaped() .
				$this->localizer->msg( 'colon-separator' )->escaped() .
				Html::element( 'span', [ 'class' => 'mw-temp-user-banner-username' ], $this->username )
			) .
			Html::rawElement( 'div', [ 'class' => 'mw-temp-user-banner-tooltip' ],
				Html::rawElement( 'button', [
					'id' => 'mw-temp-user-banner-tooltip-button',
					'class' => 'mw-temp-user-banner-tooltip-summary cdx-button '
						. 'cdx-button--icon-only cdx-button--weight-quiet',
					'aria-label' => $this->localizer->msg( 'temp-user-banner-tooltip-label' )->text()
					],
					Html::element( 'span', [ 'class' => 'mw-temp-user-banner-tooltip-icon ' ] )
				)

			) .
			Html::rawElement( 'div', [ 'class' => 'mw-temp-user-banner-buttons' ],
				$this->createLoginLink() .
				$this->createAccountLink()
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return [
			'html' => ( $this->isTempUser ) ? $this->renderBannerHTML() : ''
		];
	}
}
