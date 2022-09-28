<?php

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
		$factory = new ContributeFactory();
		$cards = $factory->getCards();
		$this->assertIsArray( $cards );
		$this->assertNotEmpty( $cards );
	}

}
