<?php

/**
 * @group Database
 */
class MovePageTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideIsValidMove
	 * @covers MovePage::isValidMove
	 * @covers MovePage::isValidFileMove
	 */
	public function testIsValidMove( $old, $new, $error ) {
		$this->setMwGlobals( 'wgContentHandlerUseDB', false );
		$mp = new MovePage(
			Title::newFromText( $old ),
			Title::newFromText( $new )
		);
		$status = $mp->isValidMove();
		if ( $error === true ) {
			$this->assertTrue( $status->isGood() );
		} else {
			$this->assertTrue( $status->hasMessage( $error ) );
		}
	}

	/**
	 * This should be kept in sync with TitleTest::provideTestIsValidMoveOperation
	 */
	public static function provideIsValidMove() {
		return [
			// for MovePage::isValidMove
			[ 'Test', 'Test', 'selfmove' ],
			[ 'Special:FooBar', 'Test', 'immobile-source-namespace' ],
			[ 'Test', 'Special:FooBar', 'immobile-target-namespace' ],
			[ 'MediaWiki:Common.js', 'Help:Some wikitext page', 'bad-target-model' ],
			[ 'Page', 'File:Test.jpg', 'nonfile-cannot-move-to-file' ],
			// for MovePage::isValidFileMove
			[ 'File:Test.jpg', 'Page', 'imagenocrossnamespace' ],
		];
	}

	/**
	 * Integration test to catch regressions like T74870. Taken and modified
	 * from SemanticMediaWiki
	 *
	 * @covers Title::moveTo
	 */
	public function testTitleMoveCompleteIntegrationTest() {
		$oldTitle = Title::newFromText( 'Help:Some title' );
		WikiPage::factory( $oldTitle )->doEditContent( new WikitextContent( 'foo' ), 'bar' );
		$newTitle = Title::newFromText( 'Help:Some other title' );
		$this->assertNull(
			WikiPage::factory( $newTitle )->getRevision()
		);

		$this->assertTrue( $oldTitle->moveTo( $newTitle, false, 'test1', true ) );
		$this->assertNotNull(
			WikiPage::factory( $oldTitle )->getRevision()
		);
		$this->assertNotNull(
			WikiPage::factory( $newTitle )->getRevision()
		);
	}

	/**
	 * Test for the move operation being aborted via the TitleMove hook
	 * @covers MovePage::move
	 */
	public function testMoveAbortedByTitleMoveHook() {
		$error = 'Preventing move operation with TitleMove hook.';
		$this->setTemporaryHook( 'TitleMove',
			function ( $old, $new, $user, $reason, $status ) use ( $error ) {
				$status->fatal( $error );
			}
		);

		$oldTitle = Title::newFromText( 'Some old title' );
		WikiPage::factory( $oldTitle )->doEditContent( new WikitextContent( 'foo' ), 'bar' );
		$newTitle = Title::newFromText( 'A brand new title' );
		$mp = new MovePage( $oldTitle, $newTitle );
		$user = User::newFromName( 'TitleMove tester' );
		$status = $mp->move( $user, 'Reason', true );
		$this->assertTrue( $status->hasMessage( $error ) );
	}
}
