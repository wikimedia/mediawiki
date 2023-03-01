<?php

use MediaWiki\Actions\ActionFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

/**
 * Test that runs against all core actions to make sure that
 * creating an instance of the action works
 *
 * @coversNothing
 */
class ActionFactoryIntegrationTest extends MediaWikiIntegrationTestCase {

	public function testActionFactoryServiceWiring() {
		$services = MediaWikiServices::getInstance();
		$actionFactory = $services->getActionFactory();
		$context = RequestContext::getMain();
		$article = Article::newFromTitle( Title::makeTitle( NS_MAIN, 'ActionFactoryServiceWiringTest' ), $context );

		$actionSpecs = ( new ReflectionClassConstant( ActionFactory::class, 'CORE_ACTIONS' ) )->getValue();
		foreach ( $actionSpecs as $action => $_ ) {
			$this->assertInstanceOf( Action::class, $actionFactory->getAction( $action, $article, $context ) );
		}
	}

}
