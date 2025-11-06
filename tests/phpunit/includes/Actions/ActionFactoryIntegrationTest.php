<?php

namespace MediaWiki\Tests\Actions;

use MediaWiki\Actions\Action;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Article;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use ReflectionClassConstant;

/**
 * Test that runs against all core actions to make sure that
 * creating an instance of the action works
 *
 * @coversNothing
 * @group Database
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

	public function testGetActionInfo() {
		$article = $this->createMock( Article::class );
		$article->method( 'getActionOverrides' )
			->willReturn( [] );
		$theAction = $this->createMock( Action::class );
		$theAction->method( 'getName' )->willReturn( 'test' );
		$theAction->method( 'getRestriction' )->willReturn( 'testing' );
		$theAction->method( 'needsReadRights' )->willReturn( true );
		$theAction->method( 'requiresWrite' )->willReturn( true );
		$theAction->method( 'requiresUnblock' )->willReturn( true );

		$factory = $this->getMockBuilder( ActionFactory::class )
			->setConstructorArgs( [
				[
					'view' => $theAction,
					'disabled' => false,
				],
				new NullLogger(),
				$this->getServiceContainer()->getObjectFactory(),
				$this->createHookContainer(),
				$this->getServiceContainer()->getContentHandlerFactory()
			] )
			->onlyMethods( [ 'getArticle' ] )
			->getMock();

		$info = $factory->getActionInfo( 'view', $article );
		$this->assertIsObject( $info );

		$this->assertSame( 'test', $info->getName() );
		$this->assertSame( 'testing', $info->getRestriction() );
		$this->assertTrue( $info->needsReadRights() );
		$this->assertTrue( $info->requiresWrite() );
		$this->assertTrue( $info->requiresUnblock() );

		$this->assertNull(
			$factory->getActionInfo( 'missing', $article ),
			'No ActionInfo should be returned for an unknown action'
		);
		$this->assertNull(
			$factory->getActionInfo( 'disabled', $article ),
			'No ActionInfo should be returned for a disabled action'
		);
	}

	/**
	 * Regression test for T348451
	 */
	public function testActionForSpecialPage() {
		$context = $this->createMock( IContextSource::class );
		$factory = $this->getServiceContainer()->getActionFactory();

		$article = Title::makeTitle( NS_SPECIAL, 'Blankpage' );

		$this->assertNull(
			$factory->getActionInfo( 'edit', $article ),
			'Special pages do not support actions'
		);
		$this->assertNull(
			$factory->getAction( 'edit', $article, $context ),
			'Special pages do not support actions'
		);
	}

}
