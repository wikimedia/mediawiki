<?php

/**
 * @group GlobalFunctions
 * @group Database
 */
class GlobalWithDBTest extends MediaWikiTestCase {
	const FILE_BLACKLIST = <<<WIKITEXT
Comment line, no effect [[File:Good.jpg]]
 * Indented list is also a comment [[File:Good.jpg]]
* [[File:Bad.jpg]] except [[Nasty page]]
*[[Image:Bad2.jpg]] also works
* So does [[Bad3.jpg]]
* [[User:Bad4.jpg]] works although it is silly
* [[File:Redirect to good.jpg]] doesn't do anything if RepoGroup is working, because we only look at
  the final name, but will work if RepoGroup returns null
* List line with no link
* [[Malformed title<>]] doesn't break anything, the line is ignored [[File:Good.jpg]]
* [[File:Bad5.jpg]] before [[malformed title<>]] doesn't ignore the line
WIKITEXT;

	public static function badImageHook( $name, &$bad ) {
		switch ( $name ) {
		case 'Hook_bad.jpg':
		case 'Redirect_to_hook_good.jpg':
			$bad = true;
			return false;

		case 'Hook_good.jpg':
		case 'Redirect_to_hook_bad.jpg':
			$bad = false;
			return false;
		}

		return true;
	}

	private function setUpBadImageTests( $name ) {
		if ( in_array( $name, [
			'Hook bad.jpg',
			'Redirect to bad.jpg',
			'Redirect_to_good.jpg',
			'Redirect to hook bad.jpg',
			'Redirect to hook good.jpg',
		] ) ) {
			$this->markTestSkipped( "Didn't get RepoGroup working properly yet" );
		}

		// Don't try to fetch the files from Commons or anything, please
		$this->setMwGlobals( 'wgForeignFileRepos', [] );
		// We need to reset services immediately so that editPage() doesn't use the old RepoGroup
		// and hit the network
		$this->resetServices();

		// XXX How do we get file redirects to work?
		$this->editPage( 'File:Redirect to bad.jpg', '#REDIRECT [[Bad.jpg]]' );
		$this->editPage( 'File:Redirect to good.jpg', '#REDIRECT [[Good.jpg]]' );
		$this->editPage( 'File:Redirect to hook bad.jpg', '#REDIRECT [[Hook bad.jpg]]' );
		$this->editPage( 'File:Redirect to hook good.jpg', '#REDIRECT [[Hook good.jpg]]' );

		$this->setTemporaryHook( 'BadImage', __CLASS__ . '::badImageHook' );
	}

	/**
	 * @dataProvider provideIsBadFile
	 * @covers ::wfIsBadImage
	 */
	public function testWfIsBadImage( $name, $title, $expected ) {
		$this->setUpBadImageTests( $name );

		$this->editPage( 'MediaWiki:Bad image list', self::FILE_BLACKLIST );
		$this->resetServices();
		// Enable messages from MediaWiki namespace
		MessageCache::singleton()->enable();

		$this->assertEquals( $expected, wfIsBadImage( $name, $title ) );
	}

	/**
	 * @dataProvider provideIsBadFile
	 * @covers ::wfIsBadImage
	 */
	public function testWfIsBadImage_blacklistParam( $name, $title, $expected ) {
		$this->setUpBadImageTests( $name );

		$this->assertSame( $expected, wfIsBadImage( $name, $title, self::FILE_BLACKLIST ) );
	}

	public static function provideIsBadFile() {
		return [
			'No context page' => [ 'Bad.jpg', null, true ],
			'Context page not whitelisted' =>
				[ 'Bad.jpg', Title::makeTitleSafe( NS_MAIN, 'A page' ), true ],
			'Good image' => [ 'Good.jpg', null, false ],
			'Whitelisted context page' =>
				[ 'Bad.jpg', Title::makeTitleSafe( NS_MAIN, 'Nasty page' ), false ],
			'Bad image with Image:' => [ 'Image:Bad.jpg', null, false ],
			'Bad image with File:' => [ 'File:Bad.jpg', null, false ],
			'Bad image with Image: in blacklist' => [ 'Bad2.jpg', null, true ],
			'Bad image without prefix in blacklist' => [ 'Bad3.jpg', null, true ],
			'Bad image with different namespace in blacklist' => [ 'Bad4.jpg', null, true ],
			'Redirect to bad image' => [ 'Redirect to bad.jpg', null, true ],
			'Redirect to good image' => [ 'Redirect_to_good.jpg', null, false ],
			'Hook says bad (with space)' => [ 'Hook bad.jpg', null, true ],
			'Hook says bad (with underscore)' => [ 'Hook_bad.jpg', null, true ],
			'Hook says good' => [ 'Hook good.jpg', null, false ],
			'Redirect to hook bad image' => [ 'Redirect to hook bad.jpg', null, true ],
			'Redirect to hook good image' => [ 'Redirect to hook good.jpg', null, false ],
			'Malformed title doesn\'t break the line' => [ 'Bad5.jpg', null, true ],
		];
	}
}
