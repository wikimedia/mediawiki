<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Contribute;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\Contribute\Card\ContributeCard;
use MediaWiki\Specials\Contribute\Card\ContributeCardActionLink;
use Mediawiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MessageLocalizer;

class ContributeFactory {

	private MessageLocalizer $localizer;
	private HookRunner $hookRunner;
	public const CONSTRUCTOR_OPTIONS = [ MainConfigNames::SpecialContributeNewPageTarget ];
	private ServiceOptions $serviceOptions;

	public function __construct( MessageLocalizer $localizer, HookRunner $hookRunner, ServiceOptions $serviceOptions ) {
		$this->localizer = $localizer;
		$this->hookRunner = $hookRunner;
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $serviceOptions;
	}

	public function getCards(): array {
		$cards = [];
		$this->hookRunner->onContributeCards( $cards );

		$url = $this->getNewPageTitle()->getLocalURL();
		$cards[] = ( new ContributeCard(
			$this->localizer->msg( 'newpage' )->text(),
			$this->localizer->msg( 'newpage-desc' )->text(),
			'article',
			new ContributeCardActionLink(
				$url,
				$this->localizer->msg( 'view-missing-pages' )->text()
			)
		) )->toArray();

		return $cards;
	}

	private function getNewPageTitle(): Title {
		$configValue = $this->serviceOptions->get( MainConfigNames::SpecialContributeNewPageTarget );
		$configuredPageTitle = Title::newFromText( $configValue );

		if ( $configuredPageTitle && $configuredPageTitle->isKnown() ) {
			return $configuredPageTitle;
		}

		return SpecialPage::getTitleFor( 'Wantedpages' );
	}

	/**
	 * Check if the Special:Contribute page is enabled for the current skin
	 * This can be removed when T323083 is resolved ie. the Special:Contribute feature
	 * has been shipped by the WMF Language Team.
	 *
	 * @param Skin $skin
	 * @param array $specialContributeSkinsEnabled
	 *
	 * @return bool
	 */
	public static function isEnabledOnCurrentSkin(
		Skin $skin,
		array $specialContributeSkinsEnabled = []
	): bool {
		return in_array(
				$skin->getSkinName(),
				$specialContributeSkinsEnabled
			);
	}

	/**
	 * @param UserIdentity $viewingUser
	 * @param ?UserIdentity $targetUser
	 *
	 * @return array
	 */
	public static function getAssociatedNavigationLinks(
		UserIdentity $viewingUser,
		?UserIdentity $targetUser
	): array {
		if ( $targetUser === null || !$viewingUser->equals( $targetUser ) ) {
			return [];
		}

		return [
			SpecialPage::getTitleFor( 'Contribute' )->getFullText(),
			SpecialPage::getTitleFor( 'Contributions', $targetUser->getName() )->getFullText(),
		];
	}
}
