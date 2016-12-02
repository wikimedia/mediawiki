<?php

/**
 * @author Addshore
 *
 * @group Database
 *
 * @covers SpecialEditWatchlist
 */
class SpecialEditWatchlistTest extends SpecialPageTestBase {

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		return new SpecialEditWatchlist();
	}

	public function testNotLoggedIn_throwsException() {
		$this->setExpectedException( 'UserNotLoggedIn' );
		$this->executeSpecialPage();
	}

	public function testRootPage_displaysExplanationMessage() {
		$user = new TestUser( __METHOD__ );
		list( $html, ) = $this->executeSpecialPage( '', null, 'qqx', $user->getUser() );
		$this->assertContains( '(watchlistedit-normal-explain)', $html );
	}

	public function testClearPage_hasClearButtonForm() {
		$user = new TestUser( __METHOD__ );
		list( $html, ) = $this->executeSpecialPage( 'clear', null, 'qqx', $user->getUser() );
		$this->assertRegExp(
			'/<form class="mw-htmlform" action=".*?Special:EditWatchlist\/clear" method="post">/',
			$html
		);
	}

	public function testEditRawPage_hasTitlesBox() {
		$user = new TestUser( __METHOD__ );
		list( $html, ) = $this->executeSpecialPage( 'raw', null, 'qqx', $user->getUser() );
		$this->assertContains(
			'<textarea id="mw-input-wpTitles"',
			$html
		);
	}

}
