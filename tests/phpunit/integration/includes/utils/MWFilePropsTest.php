<?php

use Wikimedia\FileBackend\FileBackend;

/**
 * @covers \MWFileProps
 */
class MWFilePropsTest extends MediaWikiIntegrationTestCase {
	public static function provideGetPropsFromPath() {
		return [
			'nonexistent.png' => [ 'nonexistent.png', [
				'fileExists' => false,
				'size' => 0,
				'file-mime' => null,
				'major_mime' => null,
				'minor_mime' => null,
				'mime' => null,
				'sha1' => '',
				'metadata' => [],
				'width' => 0,
				'height' => 0,
				'bits' => 0,
				'media_type' => 'UNKNOWN',
			] ],
			'zip-kind-of-valid.png' => [ 'zip-kind-of-valid.png', [
				'fileExists' => true,
				'size' => 189,
				'file-mime' => 'application/zip',
				'major_mime' => 'application',
				'minor_mime' => 'zip',
				'mime' => 'application/zip',
				'sha1' => 'rt7k3bexfau9i8jd5z41oxi3fqz7psb',
				'metadata' => [],
				'width' => 0,
				'height' => 0,
				'bits' => 0,
				'media_type' => 'ARCHIVE',
			] ],
			'tinyrgb.jpg' => [ 'tinyrgb.jpg', [
				'width' => 120,
				'height' => 78,
				'bits' => 8,
				'fileExists' => true,
				'size' => 5118,
				'file-mime' => 'image/jpeg',
				'major_mime' => 'image',
				'minor_mime' => 'jpeg',
				'mime' => 'image/jpeg',
				'sha1' => 'iqrl77mbbzax718nogdpirzfodf7meh',
				'metadata' => [
					'JPEGFileComment' => [
						'File source: http://127.0.0.1:8080/wiki/File:Srgb_copy.jpg',
					],
					'MEDIAWIKI_EXIF_VERSION' => 2,
				],
				'media_type' => 'BITMAP',
			] ],
		];
	}

	/**
	 * @dataProvider provideGetPropsFromPath
	 * @param string $relPath
	 * @param array $expected
	 */
	public function testGetPropsFromPath( $relPath, $expected ) {
		$mwfp = new MWFileProps(
			$this->getServiceContainer()->getMimeAnalyzer()
		);
		$path = __DIR__ . '/../../../data/media/' . $relPath;

		$props = $mwfp->getPropsFromPath( $path, FileBackend::extensionFromPath( $path ) );
		$this->assertArrayEquals( $expected, $props, false, true );
	}
}
