<?php

/**
 * @covers Action
 *
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 *
 * @group Action
 */
class ActionTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$context = $this->getContext();
		$this->setMwGlobals( 'wgActions', array(
			'null' => null,
			'disabled' => false,
			'dummy' => true,
			'string' => 'NamedDummyAction',
			'declared' => 'NonExistingClassName',
			'callable' => array( $this, 'dummyActionCallback' ),
			'object' => new InstantiatedDummyAction( $context->getWikiPage(), $context ),
		) );
	}

	private function getContext( $requestedAction = null ) {
		$request = new FauxRequest( array( 'action' => $requestedAction ) );

		$page = WikiPage::factory( Title::makeTitle( 0, 'Title' ) );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$context->setWikiPage( $page );

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
			array( null, null ),
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
		$context = $this->getContext( 'disabled' );
		$action = Action::factory( 'disabled', $context->getWikiPage(), $context );

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
