<?php

namespace MediaWiki\Specials\Contribute;

use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Specials\Contribute\Card\ContributeCard;
use MediaWiki\Specials\Contribute\Card\ContributeCardActionLink;

class ContributeFactory {

	use ProtectedHookAccessorTrait;

	/**
	 * @return array
	 */
	public function getCards(): array {
		$cards = [];

		$this->getHookRunner()->onContributeCards( $cards );

		$cards[] = ( new ContributeCard(
			wfMessage( 'newpage' )->text(),
			wfMessage( 'newpage-desc' )->text(),
			'article',
			new ContributeCardActionLink(
				'/wiki/Special:WantedPages',
				wfMessage( 'view-missing-pages' )->text()
			)
		) )->toArray();

		return $cards;
	}
}
