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
		return array(
			array( 'gif', 'image/gif', 'image/gif' ),
			array( 'gif', 'unknown/unknown', 'unknown/unknown' ),
			array( 'wrl', 'unknown/unknown', 'model/vrml' ),
			array( 'txt', 'text/plain', 'text/plain' ),
			array( 'csv', 'text/plain', 'text/csv' ),
			array( 'tsv', 'text/plain', 'text/tab-separated-values' ),
			array( 'json', 'text/plain', 'application/json' ),
			array( 'foo', 'application/x-opc+zip', 'application/zip' ),
			array( 'docx', 'application/x-opc+zip',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ),
			array( 'djvu', 'image/x-djvu', 'image/vnd.djvu' ),
			array( 'wav', 'audio/wav', 'audio/wav' ),
		);
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
