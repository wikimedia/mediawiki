<?php

use MediaWiki\Block\Restriction\PageRestriction;

/**
 * @covers Action
 *
 * @group Action
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class ActionTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$context = $this->getContext();
		$this->setMwGlobals( 'wgActions', [
			'null' => null,
			'disabled' => false,
			'view' => true,
			'edit' => true,
			'revisiondelete' => SpecialPageAction::class,
			'dummy' => true,
			'access' => 'ControlledAccessDummyAction',
			'unblock' => 'RequiresUnblockDummyAction',
			'string' => 'NamedDummyAction',
			'declared' => 'NonExistingClassName',
			'callable' => [ $this, 'dummyActionCallback' ],
			'object' => new InstantiatedDummyAction( $context->getWikiPage(), $context ),
		] );
	}

	private function getPage() {
		return WikiPage::factory( Title::makeTitle( 0, 'Title' ) );
	}

	private function getContext( $requestedAction = null ) {
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

			// Null and non-existing values
			[ 'null', null ],
			[ 'undeclared', null ],
			[ '', null ],
			[ false, null ],
		];
	}

	/**
	 * @dataProvider actionProvider
	 * @param string $requestedAction
	 * @param string|null $expected
	 */
	public function testActionExists( $requestedAction, $expected ) {
		$exists = Action::exists( $requestedAction );

		$this->assertSame( $expected !== null, $exists );
	}

	public function testActionExists_doesNotRequireInstantiation() {
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
	 * @dataProvider actionProvider
	 * @param string $requestedAction
	 * @param string|null $expected
	 */
	public function testActionFactory( $requestedAction, $expected ) {
		$context = $this->getContext();
		$action = Action::factory( $requestedAction, $context->getWikiPage(), $context );

		$this->assertType( $expected ?: 'null', $action );
	}

	public function testNull_doesNotExist() {
		$exists = Action::exists( null );

		$this->assertFalse( $exists );
	}

	public function testNull_defaultsToView() {
		$context = $this->getContext( null );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'view', $actionName );
	}

	public function testNull_canNotBeInstantiated() {
		$page = $this->getPage();
		$action = Action::factory( null, $page );

		$this->assertNull( $action );
	}

	public function testDisabledAction_exists() {
		$exists = Action::exists( 'disabled' );

		$this->assertTrue( $exists );
	}

	public function testDisabledAction_isNotResolved() {
		$context = $this->getContext( 'disabled' );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'nosuchaction', $actionName );
	}

	public function testDisabledAction_factoryReturnsFalse() {
		$page = $this->getPage();
		$action = Action::factory( 'disabled', $page );

		$this->assertFalse( $action );
	}

	public function dummyActionCallback() {
		$context = $this->getContext();
		return new CalledDummyAction( $context->getWikiPage(), $context );
	}

	public function testCanExecute() {
		$user = $this->getTestUser()->getUser();
		$user->mRights = [ 'access' ];
		$action = Action::factory( 'access', $this->getPage(), $this->getContext() );
		$this->assertNull( $action->canExecute( $user ) );
	}

	public function testCanExecuteNoRight() {
		$user = $this->getTestUser()->getUser();
		$user->mRights = [];
		$action = Action::factory( 'access', $this->getPage(), $this->getContext() );

		try {
			$action->canExecute( $user );
		} catch ( Exception $e ) {
			$this->assertInstanceOf( PermissionsError::class, $e );
		}
	}

	public function testCanExecuteRequiresUnblock() {
		$user = $this->getTestUser()->getUser();
		$user->mRights = [];

		$page = $this->getExistingTestPage();
		$action = Action::factory( 'unblock', $page, $this->getContext() );

		$block = new Block( [
			'address' => $user,
			'by' => $this->getTestSysop()->getUser()->getId(),
			'expiry' => 'infinity',
			'sitewide' => false,
		] );
		$block->setRestrictions( [
			new PageRestriction( 0, $page->getTitle()->getArticleID() ),
		] );

		$block->insert();

		try {
			$action->canExecute( $user );
		} catch ( Exception $e ) {
			$this->assertInstanceOf( UserBlockedError::class, $e );
		}

		$block->delete();
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
