<?php

use MediaWiki\Actions\ActionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ObjectFactory;

/**
 * @coversDefaultClass \MediaWiki\Actions\ActionFactory
 *
 * @author DannyS712
 */
class ActionFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @param array $overrides
	 * @return ActionFactory|MockObject
	 */
	private function getFactory( $overrides = [] ) {
		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);

		$mock = $this->getMockBuilder( ActionFactory::class )
			->setConstructorArgs( [
				$overrides['actions'] ?? [],
				$overrides['logger'] ?? new NullLogger(),
				$objectFactory
			] )
			->onlyMethods( [ 'getArticle' ] )
			->getMock();
		// Partial mock to override use of static Article::newFromWikiPage
		// the typehint. By default has no action overrides
		$mock->method( 'getArticle' )
			->willReturn(
				$overrides['article'] ?? $this->getArticle()
			);
		return $mock;
	}

	/**
	 * @param array $overrides
	 * @return Article|MockObject
	 */
	private function getArticle( $overrides = [] ) {
		$article = $this->createMock( Article::class );
		$article->method( 'getActionOverrides' )
			->willReturn( $overrides );
		return $article;
	}

	/**
	 * @covers ::getAction
	 */
	public function testGetAction_simple() {
		// Cases for undefined and disabled
		$context = $this->createMock( IContextSource::class );
		$article = $this->getArticle();
		$factory = $this->getFactory( [
			'actions' => [
				'disabled' => false,
			]
		] );
		$this->assertNull(
			$factory->getAction( 'missing', $article, $context ),
			'The `missing` action is not defined'
		);
		$this->assertFalse(
			$factory->getAction( 'disabled', $article, $context ),
			'The `disabled` action is disabled'
		);
	}

	/**
	 * @covers ::getAction
	 */
	public function testGetAction_override() {
		$context = $this->createMock( IContextSource::class );
		$factory = $this->getFactory( [
			'actions' => [
				'the-override' => [
					'class' => Action::class,
				],
			]
		] );

		$theOverrideAction = $this->createMock( Action::class );
		$article = $this->getArticle( [
			'the-override' => $theOverrideAction,
		] );
		$this->assertSame(
			$theOverrideAction,
			$factory->getAction( 'the-override', $article, $context ),
			'Article::getActionOverrides can override configured actions'
		);
	}

	/**
	 * @covers ::getAction
	 */
	public function testGetAction_spec() {
		$context = $this->createMock( IContextSource::class );

		// Test actually getting with the object factory. Core EditAction is used
		// for the {true -> class name -> spec with class} logic, and we replace
		// the default logic for InfoAction with a custom callback for the
		// {callable -> spec with factory} logic. Not testing the fact that ObjectFactory
		// can provide services
		$factory = $this->getFactory( [
			'actions' => [
				'edit' => true,
				'info' => [ __CLASS__, 'getInfoAction' ],
			]
		] );
		$article = $this->getArticle();
		$editAction = $factory->getAction( 'edit', $article, $context );
		$this->assertInstanceOf(
			EditAction::class,
			$editAction,
			'Setting an action name to `true` and getting the class from the name'
		);
		$infoAction = $factory->getAction( 'info', $article, $context );
		$this->assertInstanceOf(
			InfoAction::class,
			$infoAction,
			'Callable used as a factory'
		);
	}

	/**
	 * Callback for ObjectFactory
	 *
	 * @param Article $article
	 * @param IContextSource $context
	 * @return InfoAction
	 */
	public static function getInfoAction( Article $article, IContextSource $context ) {
		return new InfoAction( $article, $context );
	}

	public function provideGetActionName() {
		yield 'Disabled action' => [
			'disabled',
			true,
			true,
			'nosuchaction',
		];
		yield 'historysubmit workaround - revision deletion' => [
			'historysubmit',
			true,
			false,
			'revisiondelete',
		];
		yield 'historysubmit workaround - change tags' => [
			'historysubmit',
			false,
			true,
			'editchangetags',
		];
		yield 'historysubmit falls back to view' => [
			'historysubmit',
			false,
			false,
			'view',
		];
		yield 'editredlink maps to edit' => [
			'editredlink',
			false,
			false,
			'edit',
		];
		yield 'unrecognized action' => [
			'missing',
			false,
			false,
			'nosuchaction',
		];
	}

	/**
	 * @dataProvider provideGetActionName
	 * @covers ::getActionName
	 * @param string $requestAction action requesting in &action= in the url
	 * @param bool $revDel whether &revisiondelete= is in the url
	 * @param bool $editTags whether $editchangetags= is in the url
	 * @param string $expectedActionName
	 */
	public function testGetActionName(
		string $requestAction,
		bool $revDel,
		bool $editTags,
		string $expectedActionName
	) {
		$context = $this->createMock( IContextSource::class );
		$context->method( 'canUseWikiPage' )->willReturn( true );

		$request = new FauxRequest( [
			'action' => $requestAction,
			'revisiondelete' => $revDel,
			'editchangetags' => $editTags,
		] );
		$context->method( 'getRequest' )->willReturn( $request );

		$factory = $this->getFactory( [
			'actions' => [
				'disabled' => false,
			]
		] );
		$actionName = $factory->getActionName( $context );
		$this->assertSame( $expectedActionName, $actionName );
	}

	/**
	 * @covers ::getActionName
	 */
	public function testGetActionName_noWikiPage() {
		$context = $this->createMock( IContextSource::class );
		$context->method( 'canUseWikiPage' )->willReturn( false );

		$factory = $this->getFactory();
		$this->assertSame(
			'view',
			$factory->getActionName( $context ),
			'For contexts where a wiki page cannot be used, the action is always `view`'
		);
	}

	/**
	 * @covers ::actionExists
	 */
	public function testActionExists() {
		$factory = $this->getFactory( [
			'actions' => [
				'extra' => true
			]
		] );
		$this->assertTrue(
			$factory->actionExists( 'VIEW' ),
			'`view` is built into core, action name is normalized to lowercase'
		);
		$this->assertTrue(
			$factory->actionExists( 'extra' ),
			'`extra` is added via configuration'
		);
		$this->assertFalse(
			$factory->actionExists( 'missing' ),
			'`missing` action is not defined'
		);
	}

}
