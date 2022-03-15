<?php

use MediaWiki\Actions\ActionFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @coversDefaultClass \MediaWiki\Actions\ActionFactory
 *
 * @author DannyS712
 */
class ActionFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @param array $overrides
	 * @param array $hooks
	 * @return ActionFactory|MockObject
	 */
	private function getFactory( $overrides = [], $hooks = [] ) {
		// ContainerInterface needs to provide the services used in creating
		// SpecialPageAction because we create instances of that in testing
		// the 'revisiondelete' and 'editchangetags' actions
		$containerInterface = $this->getMockForAbstractClass( ContainerInterface::class );
		$containerInterface->method( 'get' )
			->with( 'SpecialPageFactory' )
			->willReturn( $this->createMock( SpecialPageFactory::class ) );
		$objectFactory = new ObjectFactory( $containerInterface );

		$mock = $this->getMockBuilder( ActionFactory::class )
			->setConstructorArgs( [
				$overrides['actions'] ?? [],
				$overrides['logger'] ?? new NullLogger(),
				$objectFactory,
				$this->createHookContainer( $hooks )
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
	public function testGetAction_overrideNonexistent() {
		$context = $this->createMock( IContextSource::class );
		$factory = $this->getFactory( [] );
		$theOverrideAction = $this->createMock( Action::class );
		$article = $this->getArticle( [
			'the-override' => $theOverrideAction,
		] );
		$this->assertSame(
			$theOverrideAction,
			$factory->getAction( 'the-override', $article, $context ),
			'Article::getActionOverrides can override non-existent actions'
		);
	}

	/**
	 * @covers ::getAction
	 */
	public function testGetAction_missingClass() {
		// Make sure nothing explodes from a class missing, instead its treated as
		// disabled, both for actions set to true and where the class comes from the
		// name, and actions that are configured as a string class name
		$logger = new TestLogger(
			true, // collect messages
			static function ( $message, $level, $context ) {
				// We only care about the ->info() log message generated from a
				// missing class, not the debug messages
				return $level === LogLevel::INFO ? $message : null;
			},
			true // collect context
		);
		$factory = $this->getFactory( [
			'actions' => [
				'actionnamewithnoclass' => true,
				'anothermissingaction' => 'MissingClassName',
			],
			'logger' => $logger,
		] );
		$context = $this->createMock( IContextSource::class );
		$article = $this->getArticle();
		$this->assertFalse(
			$factory->getAction( 'actionnamewithnoclass', $article, $context )
		);
		$this->assertFalse(
			$factory->getAction( 'anothermissingaction', $article, $context )
		);
		$this->assertSame( [
			[
				LogLevel::INFO,
				'Missing action class {actionClass}, treating as disabled',
				[ 'actionClass' => 'ActionnamewithnoclassAction' ]
			],
			[
				LogLevel::INFO,
				'Missing action class {actionClass}, treating as disabled',
				[ 'actionClass' => 'MissingClassName' ]
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();
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
				'info' => [ $this, 'getInfoAction' ],
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
	public function getInfoAction( Article $article, IContextSource $context ) {
		// Don't worry about all of the services that InfoAction really uses
		return $this->createMock( InfoAction::class );
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
		yield 'hook overriding action' => [
			'edit',
			false,
			false,
			'view',
			[
				'GetActionName' => static function ( $context, &$action ) {
					$action = 'view';
					return true;
				}
			]
		];
		yield 'hook overriding to an unrecognized action' => [
			'edit',
			false,
			false,
			'nosuchaction',
			[
				'GetActionName' => static function ( $context, &$action ) {
					$action = 'missing';
					return true;
				}
			]
		];
	}

	/**
	 * @dataProvider provideGetActionName
	 * @covers ::getActionName
	 * @param string $requestAction action requesting in &action= in the url
	 * @param bool $revDel whether &revisiondelete= is in the url
	 * @param bool $editTags whether $editchangetags= is in the url
	 * @param string $expectedActionName
	 * @param array $hooks hooks to register
	 */
	public function testGetActionName(
		string $requestAction,
		bool $revDel,
		bool $editTags,
		string $expectedActionName,
		array $hooks = []
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
		], $hooks );
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
		$this->hideDeprecated( ActionFactory::class . '::actionExists' );
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
