<?php

namespace MediaWiki\Specials\Contribute;

use IContextSource;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Specials\Contribute\Card\ContributeCard;
use MediaWiki\Specials\Contribute\Card\ContributeCardActionLink;
use SpecialPage;

class ContributeFactory {

	use ProtectedHookAccessorTrait;

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * @param IContextSource $context
	 */
	public function __construct( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * @return array
	 */
	public function getCards(): array {
		$cards = [];

		$this->getHookRunner()->onContributeCards( $cards );

		$cards[] = ( new ContributeCard(
			$this->context->msg( 'newpage' )->text(),
			$this->context->msg( 'newpage-desc' )->text(),
			'article',
			new ContributeCardActionLink(
				SpecialPage::getSafeTitleFor( 'Wantedpages' )->getLocalURL(),
				$this->context->msg( 'view-missing-pages' )->text()
			)
		) )->toArray();

		return $cards;
	}
}
