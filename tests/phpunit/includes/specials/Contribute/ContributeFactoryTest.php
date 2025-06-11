<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\Contribute\Card\ContributeCard;
use MediaWiki\Specials\Contribute\Card\ContributeCardActionLink;
use MediaWiki\Specials\Contribute\ContributeFactory;

/**
 * @author MAbualruz
 * @group Database
 * @covers \MediaWiki\Specials\Contribute\ContributeFactory
 */
class ContributeFactoryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Specials\Contribute\ContributeFactory::getCards
	 */
	public function testGetCards() {
		$context = RequestContext::getMain();
		$service = $this->getServiceContainer();
		$hookContainer = $service->getHookContainer();
		$services = MediaWikiServices::getInstance();
		$factory = new ContributeFactory(
			$context,
			new HookRunner( $hookContainer ),
			new ServiceOptions(
				ContributeFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			)
		);
		$cards = $factory->getCards();
		$this->assertIsArray( $cards );
		$this->assertNotEmpty( $cards );
		$defaltCard = $cards[ count( $cards ) - 1 ];
		$expectedCard = ( new ContributeCard(
			$context->msg( 'newpage' )->text(),
			$context->msg( 'newpage-desc' )->text(),
			'article',
			new ContributeCardActionLink(
				SpecialPage::getSafeTitleFor( 'Wantedpages' )->getLocalURL(),
				$context->msg( 'view-missing-pages' )->text()
			) ) )->toArray();
		$this->assertArrayEquals( [ 'title', 'icon', 'description', 'action' ], array_keys( $defaltCard ) );
		$this->assertArrayEquals( $expectedCard, $defaltCard );
	}

}
