<?php

/**
 * @covers \ViewAction
 *
 * @group Actions
 *
 * @author Derick N. Alangi
 */
class ViewActionTest extends MediaWikiIntegrationTestCase {
	/**
	 * @return ViewAction
	 */
	private function makeViewActionClassFactory() {
		$page = new Article( Title::newMainPage() );
		$context = RequestContext::getMain();
		$viewAction = new ViewAction( $page, $context );

		return $viewAction;
	}

	public function testGetName() {
		$viewAction = $this->makeViewActionClassFactory();
		$actual = $viewAction->getName();

		$this->assertSame( 'view', $actual );
	}

	public function testOnView() {
		$viewAction = $this->makeViewActionClassFactory();
		$actual = $viewAction->onView();

		$this->assertNull( $actual );
	}
}
