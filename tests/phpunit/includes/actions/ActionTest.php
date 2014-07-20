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

		$this->setMwGlobals( 'wgActions', array(
			'null' => null,
			'dummy' => true,
			'string' => 'NamedDummyAction',
			'declared' => 'NonExistingClassName',
			'callable' => array( $this, 'dummyActionCallback' ),
			'object' => new InstantiatedDummyAction( $this->getPage(), $this->getContext() ),
		) );
	}

	private function getPage() {
		return WikiPage::factory( Title::makeTitle( 0, 'Title' ) );
	}

	private function getContext() {
		return new DerivativeContext( RequestContext::getMain() );
	}

	public function actionProvider() {
		return array(
			array( 'dummy', 'DummyAction' ),
			array( 'string', 'NamedDummyAction' ),
			array( 'callable', 'CalledDummyAction' ),
			array( 'object', 'InstantiatedDummyAction' ),

			// Capitalization is ignored
			array( 'STRING', 'NamedDummyAction' ),

			// Null and non-existing values
			array( 'null', null ),
			array( 'undeclared', null ),
			array( '', null ),
			array( null, null ),
		);
	}

	/**
	 * @dataProvider actionProvider
	 * @param string $requestedAction
	 * @param string|null $expected
	 */
	public function testActionExists( $requestedAction, $expected ) {
		$exists = Action::exists( $requestedAction );

		$this->assertEquals( isset( $expected ), $exists );
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
		$context = $this->getContext();
		$context->setWikiPage( $this->getPage() );
		$context->setRequest( new FauxRequest( array( 'action' => $requestedAction ) ) );

		$actionName = Action::getActionName( $context );

		$this->assertEquals( isset( $expected ) ? $expected : 'nosuchaction', $actionName );
	}

	/**
	 * @dataProvider actionProvider
	 * @param string $requestedAction
	 * @param string|null $expected
	 */
	public function testActionFactory( $requestedAction, $expected ) {
		$action = Action::factory( $requestedAction, $this->getPage(), $this->getContext() );

		$this->assertType( isset( $expected ) ? $expected : 'null', $action );
	}

	public function dummyActionCallback() {
		return new CalledDummyAction( $this->getPage(), $this->getContext() );
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
