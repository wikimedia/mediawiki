<?php

use MediaWiki\Actions\ActionFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;

/**
 * @covers Action
 *
 * @group Action
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class ActionTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$context = $this->getContext();
		$this->overrideConfigValue(
			MainConfigNames::Actions,
			[
				'null' => null,
				'disabled' => false,
				'view' => true,
				'edit' => true,
				'revisiondelete' => [
					'class' => SpecialPageAction::class,
					'services' => [
						'SpecialPageFactory',
					],
					'args' => [
						// SpecialPageAction is used for both 'editchangetags' and
						// 'revisiondelete' actions, tell it which one this is
						'revisiondelete',
					],
				],
				'dummy' => true,
				'access' => 'ControlledAccessDummyAction',
				'unblock' => 'RequiresUnblockDummyAction',
				'string' => 'NamedDummyAction',
				'declared' => 'NonExistingClassName',
				'callable' => [ $this, 'dummyActionCallback' ],
				'object' => new InstantiatedDummyAction(
					$this->getArticle(),
					$context
				),
			]
		);
	}

	/**
	 * @param string $requestedAction
	 * @param WikiPage|null $wikiPage
	 * @return Action|bool|null
	 */
	private function getAction(
		string $requestedAction,
		WikiPage $wikiPage = null
	) {
		$context = $this->getContext( $requestedAction );

		return Action::factory(
			$requestedAction,
			$this->getArticle( $wikiPage, $context ),
			$context
		);
	}

	/**
	 * @param WikiPage|null $wikiPage
	 * @param IContextSource|null $context
	 * @return Article
	 */
	private function getArticle(
		WikiPage $wikiPage = null,
		IContextSource $context = null
	): Article {
		$context ??= $this->getContext();
		if ( $wikiPage !== null ) {
			$context->setWikiPage( $wikiPage );
			$context->setTitle( $wikiPage->getTitle() );
		} else {
			$wikiPage = $this->getPage();
		}

		return Article::newFromWikiPage( $wikiPage, $context );
	}

	private function getPage(): WikiPage {
		$title = Title::makeTitle( 0, 'Title' );
		return $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
	}

	/**
	 * @param string|null $requestedAction
	 * @return IContextSource
	 */
	private function getContext(
		string $requestedAction = null
	): IContextSource {
		$request = new FauxRequest( [ 'action' => $requestedAction ] );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$context->setWikiPage( $this->getPage() );

		return $context;
	}

	public function provideActions() {
		return [
			[ 'dummy', 'DummyAction' ],
			[ 'string', 'NamedDummyAction' ],
			[ 'callable', 'CalledDummyAction' ],
			[ 'object', 'InstantiatedDummyAction' ],

			// Capitalization is ignored
			[ 'DUMMY', 'DummyAction' ],
			[ 'STRING', 'NamedDummyAction' ],

			// non-existing values
			[ 'null', null ],
			[ 'undeclared', null ],
			[ '', null ],

			// disabled action exists but cannot be created
			[ 'disabled', false ],
		];
	}

	/**
	 * @dataProvider provideActions
	 * @param string $requestedAction
	 * @param string|false|null $expected
	 */
	public function testActionExists( string $requestedAction, $expected ) {
		$this->hideDeprecated( ActionFactory::class . '::actionExists' );
		$this->hideDeprecated( Action::class . '::exists' );
		$exists = Action::exists( $requestedAction );

		$this->assertSame( $expected !== null, $exists );
	}

	public function testActionExists_doesNotRequireInstantiation() {
		$this->hideDeprecated( ActionFactory::class . '::actionExists' );
		$this->hideDeprecated( Action::class . '::exists' );
		// The method is not supposed to check if the action can be instantiated.
		$exists = Action::exists( 'declared' );
		$this->assertTrue( $exists );
	}

	public function provideGetActionName() {
		return [
			'dummy' => [ 'dummy', 'DummyAction' ],
			'string' => [ 'string', 'NamedDummyAction' ],
			'callable' => [ 'callable', 'CalledDummyAction' ],
			'object' => [ 'object', 'InstantiatedDummyAction' ],

			// Capitalization is ignored
			'dummy (caps)' => [ 'DUMMY', 'DummyAction' ],
			'string (caps)' => [ 'STRING', 'NamedDummyAction' ],

			// non-existing values
			'null (string)' => [ 'null', 'nosuchaction' ],
			'undeclared' => [ 'undeclared', 'nosuchaction' ],
			'empty' => [ '', 'nosuchaction' ],

			// impossible
			'null (value)' => [ null, 'view' ],
			'false' => [ false, 'nosuchaction' ],

			// See https://phabricator.wikimedia.org/T22966
			'editredlink' => [ 'editredlink', 'edit' ],

			// See https://phabricator.wikimedia.org/T22966
			'historysubmit (no request params)' => [ 'historysubmit', 'view' ],

			'disabled not resolvable' => [ 'disabled', 'nosuchaction' ],
		];
	}

	/**
	 * @dataProvider provideGetActionName
	 * @param string $requestedAction
	 * @param string $expected
	 */
	public function testGetActionName( $requestedAction, $expected ) {
		$actionName = Action::getActionName(
			$this->getContext( $requestedAction )
		);
		$this->assertEquals( $expected, $actionName );
	}

	public function testGetActionName_revisiondeleteWorkaround() {
		// See https://phabricator.wikimedia.org/T22966
		$context = $this->getContext( 'historysubmit' );
		$context->getRequest()->setVal( 'revisiondelete', true );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'revisiondelete', $actionName );
	}

	public function testGetActionName_whenCanNotUseWikiPage_defaultsToView() {
		$request = new FauxRequest( [ 'action' => 'edit' ] );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'view', $actionName );
	}

	/**
	 * @covers \Action::factory
	 *
	 * @dataProvider provideActions
	 * @param string $requestedAction
	 * @param string|false|null $expected
	 */
	public function testActionFactory( string $requestedAction, $expected ) {
		$action = $this->getAction( $requestedAction );

		if ( is_string( $expected ) ) {
			$this->assertInstanceOf( $expected, $action );
		} else {
			$this->assertSame( $expected, $action );
		}
	}

	public function dummyActionCallback() {
		$article = $this->getArticle();
		return new CalledDummyAction(
			$article,
			$article->getContext()
		);
	}

	public function testCanExecute() {
		$user = $this->getTestUser()->getUser();
		$this->overrideUserPermissions( $user, 'access' );
		$action = $this->getAction( 'access' );
		$this->assertNull( $action->canExecute( $user ) );
	}

	public function testCanExecuteNoRight() {
		$user = $this->getTestUser()->getUser();
		$this->overrideUserPermissions( $user, [] );
		$action = $this->getAction( 'access' );
		$this->expectException( PermissionsError::class );
		$action->canExecute( $user );
	}

	public function testCanExecuteRequiresUnblock() {
		$page = $this->getExistingTestPage();
		$action = $this->getAction( 'unblock', $page );

		$user = $this->createMock( User::class );

		$user->method( 'getWikiId' )->willReturn( WikiAwareEntity::LOCAL );

		$block = new DatabaseBlock( [
			'address' => $user,
			'by' => $this->getTestSysop()->getUser(),
			'expiry' => 'infinity',
			'sitewide' => false,
		] );

		$user->expects( $this->once() )
			->method( 'getBlock' )
			->willReturn( $block );

		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->method( 'isBlockedFrom' )->willReturn( true );
		$this->setService( 'PermissionManager', $permissionManager );

		$this->expectException( UserBlockedError::class );
		$action->canExecute( $user );
	}

}

class DummyAction extends Action {

	public function getName() {
		return static::class;
	}

	public function show() {
	}

	public function execute() {
	}

	public function canExecute( User $user ) {
		return $this->checkCanExecute( $user );
	}
}

class NamedDummyAction extends DummyAction {
}

class CalledDummyAction extends DummyAction {
}

class InstantiatedDummyAction extends DummyAction {
}

class ControlledAccessDummyAction extends DummyAction {
	public function getRestriction() {
		return 'access';
	}
}

class RequiresUnblockDummyAction extends DummyAction {
	public function requiresUnblock() {
		return true;
	}
}
