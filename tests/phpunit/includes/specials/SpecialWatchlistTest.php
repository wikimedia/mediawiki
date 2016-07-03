<?php

/**
 * @author Addshore
 *
 * @group Database
 *
 * @covers SpecialWatchlist
 */
class SpecialWatchlistTest extends SpecialPageTestBase {

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		return new SpecialWatchlist();
	}

	public function testNotLoggedIn_throwsException() {
		$this->setExpectedException( 'UserNotLoggedIn' );
		$this->executeSpecialPage();
	}

	public function testUserWithNoWatchedItems_displaysNoWatchlistMessage() {
		$user = new TestUser( __METHOD__ );
		list( $html, ) = $this->executeSpecialPage( '', null, 'qqx', $user->getUser() );
		$this->assertContains( '(nowatchlist)', $html );
	}

}
