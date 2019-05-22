<?php

/** @covers ForeignDBFile */
class ForeignDBFileTest extends \PHPUnit\Framework\TestCase {

	use PHPUnit4And6Compat;

	public function testShouldConstructCorrectInstanceFromTitle() {
		$title = Title::makeTitle( NS_FILE, 'Awesome_file' );
		$repoMock = $this->createMock( LocalRepo::class );

		$file = ForeignDBFile::newFromTitle( $title, $repoMock );

		$this->assertInstanceOf( ForeignDBFile::class, $file );
	}
}
