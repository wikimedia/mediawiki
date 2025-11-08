<?php

use MediaWiki\FileRepo\File\ForeignDBFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Title\Title;

/** @covers \MediaWiki\FileRepo\File\ForeignDBFile */
class ForeignDBFileTest extends MediaWikiIntegrationTestCase {

	public function testShouldConstructCorrectInstanceFromTitle() {
		$title = Title::makeTitle( NS_FILE, 'Awesome_file' );
		$repoMock = $this->createMock( LocalRepo::class );

		$file = ForeignDBFile::newFromTitle( $title, $repoMock );

		$this->assertInstanceOf( ForeignDBFile::class, $file );
	}
}
