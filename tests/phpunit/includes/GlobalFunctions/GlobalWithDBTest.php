<?php

use MediaWiki\MediaWikiServices;

/**
 * @group GlobalFunctions
 * @group Database
 */
class GlobalWithDBTest extends MediaWikiIntegrationTestCase {
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

		// XXX How do we get file redirects to work?
		$this->editPage( 'File:Redirect to bad.jpg', '#REDIRECT [[Bad.jpg]]' );
		$this->editPage( 'File:Redirect to good.jpg', '#REDIRECT [[Good.jpg]]' );
		$this->editPage( 'File:Redirect to hook bad.jpg', '#REDIRECT [[Hook bad.jpg]]' );
		$this->editPage( 'File:Redirect to hook good.jpg', '#REDIRECT [[Hook good.jpg]]' );

		$this->setTemporaryHook( 'BadImage', 'BadFileLookupTest::badImageHook' );
	}

	/**
	 * @dataProvider BadFileLookupTest::provideIsBadFile
	 * @covers ::wfIsBadImage
	 */
	public function testWfIsBadImage( $name, $title, $expected ) {
		$this->setUpBadImageTests( $name );

		$this->editPage( 'MediaWiki:Bad image list', BadFileLookupTest::BAD_FILE_LIST );
		$this->resetServices();
		// Enable messages from MediaWiki namespace
		MediaWikiServices::getInstance()->getMessageCache()->enable();

		$this->hideDeprecated( 'wfIsBadImage' );
		$this->assertEquals( $expected, wfIsBadImage( $name, $title ) );
	}
}
