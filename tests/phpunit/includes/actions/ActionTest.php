<?php

/**
 * @covers Action
 *
 * @author Thiemo Mättig
 *
 * @group Action
 * @group Database
 */
class ActionTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$context = $this->getContext();
		$this->setMwGlobals( 'wgActions', array(
			'null' => null,
			'disabled' => false,
			'view' => true,
			'edit' => true,
			'revisiondelete' => 'SpecialPageAction',
			'dummy' => true,
			'string' => 'NamedDummyAction',
			'declared' => 'NonExistingClassName',
			'callable' => array( $this, 'dummyActionCallback' ),
			'object' => new InstantiatedDummyAction( $context->getWikiPage(), $context ),
		) );
	}

	private function getPage() {
		return WikiPage::factory( Title::makeTitle( 0, 'Title' ) );
	}

	private function getContext( $requestedAction = null ) {
		$request = new FauxRequest( array( 'action' => $requestedAction ) );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$context->setWikiPage( $this->getPage() );

		return $context;
	}

	public function actionProvider() {
		return array(
			array( 'dummy', 'DummyAction' ),
			array( 'string', 'NamedDummyAction' ),
			array( 'callable', 'CalledDummyAction' ),
			array( 'object', 'InstantiatedDummyAction' ),

			// Capitalization is ignored
			array( 'DUMMY', 'DummyAction' ),
			array( 'STRING', 'NamedDummyAction' ),

			// Null and non-existing values
			array( 'null', null ),
			array( 'undeclared', null ),
			array( '', null ),
			array( false, null ),
		);
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
		// See https://bugzilla.wikimedia.org/show_bug.cgi?id=20966
		$context = $this->getContext( 'editredlink' );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'edit', $actionName );
	}

	public function testGetActionName_historysubmitWorkaround() {
		// See https://bugzilla.wikimedia.org/show_bug.cgi?id=20966
		$context = $this->getContext( 'historysubmit' );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'view', $actionName );
	}

	public function testGetActionName_revisiondeleteWorkaround() {
		// See https://bugzilla.wikimedia.org/show_bug.cgi?id=20966
		$context = $this->getContext( 'historysubmit' );
		$context->getRequest()->setVal( 'revisiondelete', true );
		$actionName = Action::getActionName( $context );

		$this->assertEquals( 'revisiondelete', $actionName );
	}

	public function testGetActionName_whenCanNotUseWikiPage_defaultsToView() {
		$request = new FauxRequest( array( 'action' => 'edit' ) );
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

}

class DummyAction extends Action {

	public function getName() {
		return get_called_class();
	}

	public function show() {
	}

	public function execute() {
	}
}

class NamedDummyAction extends DummyAction {
}

class CalledDummyAction extends DummyAction {
}

class InstantiatedDummyAction extends DummyAction {
}
