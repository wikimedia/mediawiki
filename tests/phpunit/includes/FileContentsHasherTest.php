<?php

/**
 * @covers FileContentsHasherTest
 */
class FileContentsHasherTest extends MediaWikiTestCase {

	public function singleFileProvider() {
		return array_map( function ( $file ) {
			return array( $file, file_get_contents( $file ) );
		}, glob( __DIR__ . '/../data/filecontentshasher/*.*' ) );
	}

	public function multipleFileProvider() {
		return array( array( $this->singleFileProvider() ) );
	}

	/**
	 * @covers FileContentsHasher::getFileContentHash
	 * @dataProvider singleFileProvider
	 */
	public function testSingleFileHash( $fileName, $contents ) {
		foreach ( array( 'md4', 'md5' ) as $algo ) {
			$expectedHash = hash( $algo, $contents );
			$actualHash = FileContentsHasher::getFileContentsHash( $fileName, $algo );
			$this->assertEquals( $expectedHash, $actualHash );
		}
	}

	/**
	 * @covers FileContentsHasher::getFileContentHash
	 * @dataProvider multipleFileProvider
	 */
	public function testMultipleFileHash( $files ) {
		$fileNames = array();
		$hashes = array();
		foreach ( $files as $fileInfo ) {
			list( $fileName, $contents ) = $fileInfo;
			$fileNames[] = $fileName;
			$hashes[] = md5( $contents );
		}

		$expectedHash = md5( implode( '', $hashes ) );
		$actualHash = FileContentsHasher::getFileContentsHash( $fileNames, 'md5' );
		$this->assertEquals( $expectedHash, $actualHash );
	}
}
