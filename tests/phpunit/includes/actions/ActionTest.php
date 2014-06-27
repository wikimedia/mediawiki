<?php

/**
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 *
 * @group Action
 */
class ActionTest extends MediaWikiLangTestCase {

	protected function setUp() {
		global $wgActions;

		parent::setUp();

		$this->stashMwGlobals( 'wgActions' );

		$wgActions = array(
			'nullaction' => null,
			'booleanactiontest' => true,
			'stringaction' => 'StringActionTestAction',
			'undeclaredaction' => 'NonExistingClassName',
			'callableaction' => array( $this, 'callableActionDummyMethod' ),
			'objectaction' => new ObjectActionTestAction(),
		);
	}

	public function callableActionDummyMethod() {
		return new CallableActionTestAction();
	}

	private function getPage() {
		return WikiPage::factory( Title::makeTitle( 0, 'Title' ) );
	}

	public function actionNameProvider() {
		return array(
			array( 'booleanactiontest', true ),
			array( 'stringaction', true ),
			array( 'callableaction', true ),
			array( 'objectaction', true ),

			// Capitalization is ignored
			array( 'STRINGACTION', true ),

			// Null and non-existing values
			array( 'nullaction', false ),
			array( 'badaction', false ),
			array( '', false ),
			array( null, false ),
		);
	}

	/**
	 * @dataProvider actionNameProvider
	 * @param string $requestedAction
	 * @param bool $expected
	 * @param string $actionName Unused in this test.
	 */
	public function testExists( $requestedAction, $expected, $actionName = null ) {
		$exists = Action::exists( $requestedAction );

		$this->assertEquals( $expected, $exists );
	}

	public function testExists_() {
		$exists = Action::exists( 'undeclaredaction' );

		// An action may exist even if it can not be instantiated
		$this->assertTrue( $exists );
	}

	/**
	 * @dataProvider actionNameProvider
	 * @param string $requestedAction
	 * @param bool $exists Unused in this test.
	 * @param string $expected
	 */
	public function testGetActionName( $requestedAction, $exists, $expected = 'nosuchaction' ) {
		$request = new FauxRequest( array( 'action' => $requestedAction ) );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setWikiPage( $this->getPage() );
		$context->setRequest( $request );

		$actionName = Action::getActionName( $context );

		$this->assertEquals( $expected, $actionName );
	}

	public function actionTypeProvider() {
		return array(
			array( 'booleanactiontest', 'BooleanactiontestAction' ),
			array( 'stringaction', 'StringActionTestAction' ),
			array( 'callableaction', 'CallableActionTestAction' ),
			array( 'objectaction', 'ObjectActionTestAction' ),

			// Capitalization is ignored
			array( 'STRINGACTION', 'StringActionTestAction' ),

			// Null and non-existing values
			array( 'nullaction', 'null' ),
			array( 'badaction', 'null' ),
			array( '', 'null' ),
			array( null, 'null' ),
		);
	}

	/**
	 * @dataProvider actionTypeProvider
	 * @param string $requestedAction
	 * @param string $expectedType
	 */
	public function testFactory( $requestedAction, $expectedType ) {
		$action = Action::factory( $requestedAction, $this->getPage() );

		$this->assertType( $expectedType, $action );
	}

}

class BooleanactiontestAction {
}

class StringActionTestAction {
}

class CallableActionTestAction {
}

class ObjectActionTestAction {
}
