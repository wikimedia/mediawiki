<?php

use MediaWiki\Actions\ActionFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Permissions\PermissionManager;

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
		$this->setMwGlobals( 'wgActions', [
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
		] );
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
		$context = $context ?? $this->getContext();
		if ( $wikiPage !== null ) {
			$context->setWikiPage( $wikiPage );
			$context->setTitle( $wikiPage->getTitle() );
		} else {
			$wikiPage = $this->getPage();
		}

		return Article::newFromWikiPage( $wikiPage, $context );
	}

	private function getPage(): WikiPage {
		return WikiPage::factory( $this->getTitle() );
	}

	private function getTitle(): Title {
		return Title::makeTitle( 0, 'Title' );
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

	public function actionProvider() {
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
		];
	}

	/**
	 * @dataProvider actionProvider
	 * @param string $requestedAction
	 * @param string|null $expected
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

	/**
	 * @dataProvider actionProvider
	 * @param string $requestedAction
	 * @param string|null $expected
	 */
	public function testGetActionName( $requestedAction, $expected ) {
		$context = $this->getContext( $requestedAction );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( $expected ?: 'nosuchaction', $actionName );
	}

	public function provideGetActionNameNotPossible() {
		return [
			'null' => [ null, 'view' ],
			'false' => [ false, 'nosuchaction' ],
		];
	}

	/**
	 * @dataProvider provideGetActionNameNotPossible
	 */
	public function testGetActionNameNotPossible( $requestedAction, string $expected ) {
		$actionName = Action::getActionName(
			$this->getContext( $requestedAction )
		);
		$this->assertEquals( $expected, $actionName );
	}

	public function testGetActionName_editredlinkWorkaround() {
		// See https://phabricator.wikimedia.org/T22966
		$context = $this->getContext( 'editredlink' );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'edit', $actionName );
	}

	public function testGetActionName_historysubmitWorkaround() {
		// See https://phabricator.wikimedia.org/T22966
		$context = $this->getContext( 'historysubmit' );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'view', $actionName );
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
	 * @dataProvider actionProvider
	 * @param string $requestedAction
	 * @param string|null $expected
	 */
	public function testActionFactory( string $requestedAction, $expected ) {
		$action = $this->getAction( $requestedAction );

		if ( $expected === null ) {
			$this->assertNull( $action );
		} else {
			$this->assertInstanceOf( $expected, $action );
		}
	}

	public function testNull_defaultsToView() {
		$context = $this->getContext();
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'view', $actionName );
	}

	public function testDisabledAction_exists() {
		$this->hideDeprecated( ActionFactory::class . '::actionExists' );
		$this->hideDeprecated( Action::class . '::exists' );
		$exists = Action::exists( 'disabled' );

		$this->assertTrue( $exists );
	}

	public function testDisabledAction_isNotResolved() {
		$context = $this->getContext( 'disabled' );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'nosuchaction', $actionName );
	}

	public function testDisabledAction_factoryReturnsFalse() {
		$action = $this->getAction( 'disabled' );

		$this->assertFalse( $action );
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
