<?php

/**
 * @covers \FileContentsHasher
 */
class FileContentsHasherTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public static function provideSingleFile() {
		return array_map( static function ( $file ) {
			return [ $file, file_get_contents( $file ) ];
		}, glob( __DIR__ . '/../../data/filecontentshasher/*.*' ) );
	}

	/**
	 * @dataProvider provideSingleFile
	 */
	public function testSingleFileHash( $fileName, $contents ) {
		$expected = hash( 'md4', $contents );
		$actualHash = FileContentsHasher::getFileContentsHash( $fileName );
		$this->assertEquals( $expected, $actualHash );

		$actualHashRepeat = FileContentsHasher::getFileContentsHash( $fileName );
		$this->assertEquals( $expected, $actualHashRepeat );
	}

	public static function provideMultipleFiles() {
		return [
			[ self::provideSingleFile() ]
		];
	}

	/**
	 * @dataProvider provideMultipleFiles
	 */
	public function testMultipleFileHash( $files ) {
		$fileNames = [];
		$hashes = [];
		foreach ( $files as [ $fileName, $contents ] ) {
			$fileNames[] = $fileName;
			$hashes[] = hash( 'md4', $contents );
		}

		$expectedHash = hash( 'md4', implode( '', $hashes ) );
		$actualHash = FileContentsHasher::getFileContentsHash( $fileNames );
		$this->assertEquals( $expectedHash, $actualHash );

		$actualHashRepeat = FileContentsHasher::getFileContentsHash( $fileNames );
		$this->assertEquals( $expectedHash, $actualHashRepeat );
	}
}
