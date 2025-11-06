<?php

namespace MediaWiki\Tests\Unit\Actions;

use MediaWiki\Actions\Action;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Actions\EditAction;
use MediaWiki\Actions\InfoAction;
use MediaWiki\Context\IContextSource;
use MediaWiki\Page\Article;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;

/**
 * @covers \MediaWiki\Actions\ActionFactory
 *
 * @author DannyS712
 */
class ActionFactoryTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	/**
	 * @param array $overrides
	 * @param array $hooks
	 * @return ActionFactory|MockObject
	 */
	private function getFactory( $overrides = [], $hooks = [] ) {
		$mock = $this->getMockBuilder( ActionFactory::class )
			->setConstructorArgs( [
				$overrides['actions'] ?? [],
				$overrides['logger'] ?? new NullLogger(),
				$this->getDummyObjectFactory(),
				$this->createHookContainer( $hooks ),
				$this->getDummyContentHandlerFactory()
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

	public function testGetAction_simple() {
		$context = $this->createMock( IContextSource::class );
		$article = $this->getArticle();
		$theAction = $this->createMock( Action::class );

		$factory = $this->getFactory( [
			'actions' => [
				'known' => $theAction,
				'disabled' => false,
			]
		] );
		$this->assertSame(
			$theAction,
			$factory->getAction( 'known', $article, $context ),
			'The `known` action is known'
		);
		$this->assertNull(
			$factory->getAction( 'missing', $article, $context ),
			'The `missing` action is not defined'
		);
		$this->assertFalse(
			$factory->getAction( 'disabled', $article, $context ),
			'The `disabled` action is disabled'
		);
	}

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
				'info' => function ( Article $article, IContextSource $context ) {
					return $this->createMock( InfoAction::class );
				},
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

	public static function provideGetActionName() {
		yield 'Disabled action' => [
			'disabled',
			'nosuchaction',
		];
		yield 'historysubmit falls back to view' => [
			'historysubmit',
			'view',
		];
		yield 'editredlink maps to edit' => [
			'editredlink',
			'edit',
		];
		yield 'unrecognized action' => [
			'missing',
			'nosuchaction',
		];
		yield 'hook overriding action' => [
			'edit',
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
	 * @param string $requestAction Action requested in &action= in the URL
	 * @param string $expectedActionName
	 * @param array $hooks hooks to register
	 */
	public function testGetActionName(
		string $requestAction,
		string $expectedActionName,
		array $hooks = []
	) {
		$context = $this->createMock( IContextSource::class );
		$context->method( 'canUseWikiPage' )->willReturn( true );

		$request = new FauxRequest( [
			'action' => $requestAction,
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

}
