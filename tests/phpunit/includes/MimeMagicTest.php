<?php
class MimeMagicTest extends PHPUnit_Framework_TestCase {

	/** @var MimeMagic */
	private $mimeMagic;

	function setUp() {
		$this->mimeMagic = MimeMagic::singleton();
		parent::setUp();
	}

	/**
	 * @dataProvider providerImproveTypeFromExtension
	 * @param string $ext File extension (no leading dot)
	 * @param string $oldMime Initially detected MIME
	 * @param string $expectedMime MIME type after taking extension into account
	 */
	function testImproveTypeFromExtension( $ext, $oldMime, $expectedMime ) {
		$actualMime = $this->mimeMagic->improveTypeFromExtension( $oldMime, $ext );
		$this->assertEquals( $expectedMime, $actualMime );
	}

	function providerImproveTypeFromExtension() {
		return [
			[ 'gif', 'image/gif', 'image/gif' ],
			[ 'gif', 'unknown/unknown', 'unknown/unknown' ],
			[ 'wrl', 'unknown/unknown', 'model/vrml' ],
			[ 'txt', 'text/plain', 'text/plain' ],
			[ 'csv', 'text/plain', 'text/csv' ],
			[ 'tsv', 'text/plain', 'text/tab-separated-values' ],
			[ 'js', 'text/javascript', 'application/javascript' ],
			[ 'js', 'application/x-javascript', 'application/javascript' ],
			[ 'json', 'text/plain', 'application/json' ],
			[ 'foo', 'application/x-opc+zip', 'application/zip' ],
			[ 'docx', 'application/x-opc+zip',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ],
			[ 'djvu', 'image/x-djvu', 'image/vnd.djvu' ],
			[ 'wav', 'audio/wav', 'audio/wav' ],
		];
	}

	/**
	 * Test to make sure that encoder=ffmpeg2theora doesn't trigger
	 * MEDIATYPE_VIDEO (bug 63584)
	 */
	function testOggRecognize() {
		$oggFile = __DIR__ . '/../data/media/say-test.ogg';
		$actualType = $this->mimeMagic->getMediaType( $oggFile, 'application/ogg' );
		$this->assertEquals( $actualType, MEDIATYPE_AUDIO );
	}
}
