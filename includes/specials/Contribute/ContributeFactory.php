<?php

namespace MediaWiki\Specials\Contribute;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\Contribute\Card\ContributeCard;
use MediaWiki\Specials\Contribute\Card\ContributeCardActionLink;
use MediaWiki\User\UserIdentity;
use MessageLocalizer;
use Skin;

class ContributeFactory {

	private MessageLocalizer $localizer;
	private HookRunner $hookRunner;

	/**
	 * @param MessageLocalizer $localizer
	 * @param HookRunner $hookRunner
	 */
	public function __construct( MessageLocalizer $localizer, HookRunner $hookRunner ) {
		$this->localizer = $localizer;
		$this->hookRunner = $hookRunner;
	}

	/**
	 * @return array
	 */
	public function getCards(): array {
		$cards = [];

		$this->hookRunner->onContributeCards( $cards );

		$cards[] = ( new ContributeCard(
			$this->localizer->msg( 'newpage' )->text(),
			$this->localizer->msg( 'newpage-desc' )->text(),
			'article',
			new ContributeCardActionLink(
				SpecialPage::getTitleFor( 'Wantedpages' )->getLocalURL(),
				$this->localizer->msg( 'view-missing-pages' )->text()
			)
		) )->toArray();

		return $cards;
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
