<?php

class ChangeTagsCoreTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( [ 'wgUseAutoTagging' => true ] );
	}

	/**
	 * @dataProvider provideGetAutotagsForEditUpdate
	 * @covers ChangeTagsCore::getAutotagsForEditUpdate
	 */
	public function testGetAutotagsForEditUpdate( $oldText, $newText, $pageTitle, $expected ) {
		$title = Title::makeTitle( NS_MAIN, $pageTitle );
		$oldContent =  ContentHandler::makeContent( $oldText, null, CONTENT_MODEL_WIKITEXT );
		$newContent =  ContentHandler::makeContent( $newText, null, CONTENT_MODEL_WIKITEXT );
		$actual = ChangeTagsCore::getAutotagsForEditUpdate( $oldContent, $newContent, $title );

		sort( $expected );
		sort( $actual );
		$this->assertEquals( $expected, $actual );
	}

	public function provideGetAutotagsForEditUpdate() {
		return [
			[ 'Hi', '#REDIRECT [[Main Page]]', 'Main Page',
				[ 'core-redirect-self', 'core-redirect-new' ] ],
			[ '#REDIRECT [[Main Page]]', 'nothing', 'Some Page',
				[ 'core-redirect-removed' ] ],
			[ 'Lorem ipsum', '', 'Some Page',
				[ 'core-edit-blank' ] ],
			[ 'Lorem ipsum', 'Lorem ipsum dolor sit amet', 'Some Page',
				[] ],
		];
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
		$actual = ChangeTagsCore::getAutotagsForMove( $oldTitle, $newTitle, $user );

		sort( $expected );
		sort( $actual );
		$this->assertEquals( $expected, $actual );
	}

	public function provideGetAutotagsForMove() {
		return [
			[ NS_USER, 'John', NS_USER, 'James', 'John',
				[ 'core-move-rename' ] ],
			[ NS_MAIN, 'Foo', NS_PROJECT, 'Foo', 'Caesar',
				[ 'core-move-crossnamespace' ] ],
		];
	}
}
