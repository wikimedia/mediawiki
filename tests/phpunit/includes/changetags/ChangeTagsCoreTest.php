<?php

class ChangeTagsCoreTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( [ 'wgCoreTags' => [
			'mw-contentmodelchange' => [ 'active' => true ],
			'mw-move-crossnamespace' => [ 'active' => true ],
			'mw-move-tomainspace' => [ 'active' => true ],
			'mw-move-rename' => [ 'active' => true ],
			'mw-redirect-new' => [ 'active' => true ],
			'mw-redirect-changed' => [ 'active' => true ],
			'mw-redirect-self' => [ 'active' => true ],
			'mw-redirect-nonexistent' => [ 'active' => true ],
			'mw-redirect-removed' => [ 'active' => true ],
			'mw-edit-blank' => [ 'active' => true ],
			'mw-edit-replace' => [ 'active' => true ],
			'mw-newpage-blank' => [ 'active' => true ],
		] ] );
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
				[ 'mw-redirect-self', 'mw-redirect-new' ] ],
			[ '#REDIRECT [[Main Page]]', 'nothing', 'Some Page',
				[ 'mw-redirect-removed' ] ],
			[ 'Lorem ipsum', '', 'Some Page',
				[ 'mw-edit-blank' ] ],
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
				[ 'mw-move-rename' ] ],
			[ NS_MAIN, 'Foo', NS_PROJECT, 'Foo', 'Caesar',
				[ 'mw-move-crossnamespace' ] ],
		];
	}
}
