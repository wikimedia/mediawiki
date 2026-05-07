<?php

namespace MediaWiki\Tests\Utils;

use MediaWiki\Utils\FileContentsHasher;
use MediaWikiCoversValidator;

/**
 * @covers \MediaWiki\Utils\FileContentsHasher
 */
class FileContentsHasherTest extends \PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	private static function getFixtureFiles() {
		return [
			'hash.svg' => [
				__DIR__ . '/../../data/filecontentshasher/hash.svg',
				'bb270c58a73de5c9a6071f89288debc8',
			],
			'primes.txt' => [
				__DIR__ . '/../../data/filecontentshasher/primes.txt',
				'96c9ed44903f19477f6a6f7a0667c314',
			]
		];
	}

	public static function provideSingleFile() {
		return self::getFixtureFiles();
	}

	/**
	 * @dataProvider provideSingleFile
	 */
	public function testSingleFile( $filePath, $expected ) {
		$this->assertEquals( $expected, FileContentsHasher::getFileContentsHash( $filePath ) );
		$this->assertEquals( $expected, FileContentsHasher::getFileContentsHash( $filePath ), 'Repeat to exercise caching' );
	}

	public function testMultipleFiles() {
		$fixture = self::getFixtureFiles();
		$filePaths = [];
		foreach ( $fixture as [ $filePath ] ) {
			$filePaths[] = $filePath;
		}

		$expected = '53a10dc4c6d758651418ce63f37b4434';
		$this->assertEquals(
			$expected,
			// Implementation sorts by hash (96c..., bb2...) and re-hashes
			hash( 'md4', $fixture['primes.txt'][1] . $fixture['hash.svg'][1] ),
			'manual'
		);
		$this->assertEquals( $expected, FileContentsHasher::getFileContentsHash( $filePaths ) );
		$this->assertEquals( $expected, FileContentsHasher::getFileContentsHash( $filePaths ), 'Repeat to exercise caching' );
	}
}
