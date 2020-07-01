<?php

use MediaWiki\Block\DatabaseBlock;
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
class ActionTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
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
	) : Article {
		$context = $context ?? $this->getContext();
		if ( $wikiPage !== null ) {
			$context->setWikiPage( $wikiPage );
			$context->setTitle( $wikiPage->getTitle() );
		} else {
			$wikiPage = $this->getPage();
		}

		return Article::newFromWikiPage( $wikiPage, $context );
	}

	private function getPage() : WikiPage {
		return WikiPage::factory( $this->getTitle() );
	}

	private function getTitle() : Title {
		return Title::makeTitle( 0, 'Title' );
	}

	/**
	 * @param string|null $requestedAction
	 * @return IContextSource
	 */
	private function getContext(
		string $requestedAction = null
	) : IContextSource {
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

	public function provideGetActionNameNotPossible() {
		return [
			'null' => [ null, 'view' ],
			'false' => [ false, 'nosuchaction' ],
		];
	}

	/**
	 * @dataProvider provideGetActionNameNotPossible
	 * @param mixed $requestedAction
	 * @param string $expected
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

	/**
	 * @covers \Action::factory
	 */
	public function testActionFactory_withNull_expectNull() {
		$this->hideDeprecated( 'Action::factory with null $action' );
		$result = Action::factory( null, $this->getPage() );
		$this->assertNull( $result );
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
		try {
			$action->canExecute( $user );
		} catch ( Exception $e ) {
			$this->assertInstanceOf( PermissionsError::class, $e );
		}
	}

	public function testCanExecuteRequiresUnblock() {
		$user = $this->getTestUser()->getUser();
		$this->overrideUserPermissions( $user, [] );

		$page = $this->getExistingTestPage();
		$action = $this->getAction( 'unblock', $page );

		$block = new DatabaseBlock( [
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
			$this->assertFalse( true );
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
