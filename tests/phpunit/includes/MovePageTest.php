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
		return array(
			// for MovePage::isValidMove
			array( 'Test', 'Test', 'selfmove' ),
			array( 'Special:FooBar', 'Test', 'immobile-source-namespace' ),
			array( 'Test', 'Special:FooBar', 'immobile-target-namespace' ),
			array( 'MediaWiki:Common.js', 'Help:Some wikitext page', 'bad-target-model' ),
			array( 'Page', 'File:Test.jpg', 'nonfile-cannot-move-to-file' ),
			// for MovePage::isValidFileMove
			array( 'File:Test.jpg', 'Page', 'imagenocrossnamespace' ),
		);
	}
}
