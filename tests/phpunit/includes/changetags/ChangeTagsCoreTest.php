<?php

class ChangeTagsCoreTest extends ChangeTagsTest {

	/**
	 * @dataProvider provideGetAutotagsForEditUpdate
	 * @covers ChangeTagsCore::getAutotagsForEditUpdate
	 */
	public function testGetAutotagsForEditUpdate( $oldText, $newText, $pageTitle, $expected ) {
		$title = Title::makeTitle( NS_MAIN, $pageTitle );
		$oldContent =  ContentHandler::makeContent( $oldText, $title );
		$newContent =  ContentHandler::makeContent( $newText, $title );
		$result = ChangeTagsCore::getAutotagsForEditUpdate( $oldContent, $newContent, $title );

		sort( $result );
		sort( $expected );
		$this->assertEquals( $returned, $expected );
	}

	public function provideGetAutotagsForEditUpdate() {
		return array(
			array( 'Hi', '#REDIRECT [[Main Page]]', 'Main Page',
				array( 'core-redirect-self', 'core-redirect-new' ) ),
			array( '#REDIRECT [[Main Page]]', 'nothing', 'Some Page',
				array( 'core-redirect-removed' ) ),
			array( 'Lorem ipsum', '', 'Some Page',
				array( 'core-edit-blank' ) ),
			array( 'Lorem ipsum', 'Lorem ipsum dolor sit amet', 'Some Page',
				array() ),
		);
	}

	/**
	 * @dataProvider provideGetAutotagsForMove
	 * @covers ChangeTagsCore::getAutotagsForMove
	 */
	public function testGetAutotagsForMove(
		$oldNs, $oldPage, $newNs, $newPage, $userName, $expected
	) {
		$oldTitle = Title::makeTitle( $oldNs, $oldPage );
		$newTitle = Title::makeTitle( $newNs, $newPage );
		$user = User::newFromName( $userName );
		$result = ChangeTagsCore::getAutotagsForMove( $oldTitle, $newTitle, $user );

		sort( $result );
		sort( $expected );
		$this->assertEquals( $returned, $expected );
	}

	public function provideGetAutotagsForMove() {
		return array(
			array( NS_USER, 'John', NS_USER, 'James', 'John',
				array( 'core-move-rename' ) ),
			array( NS_Main, 'Foo', NS_PROJECT, 'Foo', 'Caesar',
				array( 'core-move-crossnamespace' ) ),
		);
	}
}
