<?php

/**
 * @todo covers tags
 */
class XMPTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		if ( !extension_loaded( 'xml' ) ) {
			$this->markTestSkipped( 'Requires libxml to do XMP parsing' );
		}
	}

	/**
	 * Put XMP in, compare what comes out...
	 *
	 * @param $xmp String the actual xml data.
	 * @param $expected Array expected result of parsing the xmp.
	 * @param $info String Short sentence on what's being tested.
	 *
	 * @throws Exception
	 * @dataProvider provideXMPParse
	 */
	public function testXMPParse( $xmp, $expected, $info ) {
		if ( !is_string( $xmp ) || !is_array( $expected ) ) {
			throw new Exception( "Invalid data provided to " . __METHOD__ );
		}
		$reader = new XMPReader;
		$reader->parse( $xmp );
		$this->assertEquals( $expected, $reader->getResults(), $info, 0.0000000001 );
	}

	public static function provideXMPParse() {
		$xmpPath = __DIR__ . '/../../data/xmp/';
		$data = array();

		// $xmpFiles format: array of arrays with first arg file base name,
		// with the actual file having .xmp on the end for the xmp
		// and .result.php on the end for a php file containing the result
		// array. Second argument is some info on what's being tested.
		$xmpFiles = array(
			array( '1', 'parseType=Resource test' ),
			array( '2', 'Structure with mixed attribute and element props' ),
			array( '3', 'Extra qualifiers (that should be ignored)' ),
			array( '3-invalid', 'Test ignoring qualifiers that look like normal props' ),
			array( '4', 'Flash as qualifier' ),
			array( '5', 'Flash as qualifier 2' ),
			array( '6', 'Multiple rdf:Description' ),
			array( '7', 'Generic test of several property types' ),
			array( 'flash', 'Test of Flash property' ),
			array( 'invalid-child-not-struct', 'Test child props not in struct or ignored' ),
			array( 'no-recognized-props', 'Test namespace and no recognized props' ),
			array( 'no-namespace', 'Test non-namespaced attributes are ignored' ),
			array( 'bag-for-seq', "Allow bag's instead of seq's. (bug 27105)" ),
			array( 'utf16BE', 'UTF-16BE encoding' ),
			array( 'utf16LE', 'UTF-16LE encoding' ),
			array( 'utf32BE', 'UTF-32BE encoding' ),
			array( 'utf32LE', 'UTF-32LE encoding' ),
			array( 'xmpExt', 'Extended XMP missing second part' ),
			array( 'gps', 'Handling of exif GPS parameters in XMP' ),
		);

		foreach ( $xmpFiles as $file ) {
			$xmp = file_get_contents( $xmpPath . $file[0] . '.xmp' );
			// I'm not sure if this is the best way to handle getting the
			// result array, but it seems kind of big to put directly in the test
			// file.
			$result = null;
			include $xmpPath . $file[0] . '.result.php';
			$data[] = array( $xmp, $result, '[' . $file[0] . '.xmp] ' . $file[1] );
		}

		return $data;
	}

	/** Test ExtendedXMP block support. (Used when the XMP has to be split
	 * over multiple jpeg segments, due to 64k size limit on jpeg segments.
	 *
	 * @todo This is based on what the standard says. Need to find a real
	 * world example file to double check the support for this is right.
	 */
	public function testExtendedXMP() {
		$xmpPath = __DIR__ . '/../../data/xmp/';
		$standardXMP = file_get_contents( $xmpPath . 'xmpExt.xmp' );
		$extendedXMP = file_get_contents( $xmpPath . 'xmpExt2.xmp' );

		$md5sum = '28C74E0AC2D796886759006FBE2E57B7'; // of xmpExt2.xmp
		$length = pack( 'N', strlen( $extendedXMP ) );
		$offset = pack( 'N', 0 );
		$extendedPacket = $md5sum . $length . $offset . $extendedXMP;

		$reader = new XMPReader();
		$reader->parse( $standardXMP );
		$reader->parseExtended( $extendedPacket );
		$actual = $reader->getResults();

		$expected = array(
			'xmp-exif' => array(
				'DigitalZoomRatio' => '0/10',
				'Flash' => 9,
				'FNumber' => '2/10',
			)
		);

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * This test has an extended XMP block with a wrong guid (md5sum)
	 * and thus should only return the StandardXMP, not the ExtendedXMP.
	 */
	public function testExtendedXMPWithWrongGUID() {
		$xmpPath = __DIR__ . '/../../data/xmp/';
		$standardXMP = file_get_contents( $xmpPath . 'xmpExt.xmp' );
		$extendedXMP = file_get_contents( $xmpPath . 'xmpExt2.xmp' );

		$md5sum = '28C74E0AC2D796886759006FBE2E57B9'; // Note last digit.
		$length = pack( 'N', strlen( $extendedXMP ) );
		$offset = pack( 'N', 0 );
		$extendedPacket = $md5sum . $length . $offset . $extendedXMP;

		$reader = new XMPReader();
		$reader->parse( $standardXMP );
		$reader->parseExtended( $extendedPacket );
		$actual = $reader->getResults();

		$expected = array(
			'xmp-exif' => array(
				'DigitalZoomRatio' => '0/10',
				'Flash' => 9,
			)
		);

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Have a high offset to simulate a missing packet,
	 * which should cause it to ignore the ExtendedXMP packet.
	 */
	public function testExtendedXMPMissingPacket() {
		$xmpPath = __DIR__ . '/../../data/xmp/';
		$standardXMP = file_get_contents( $xmpPath . 'xmpExt.xmp' );
		$extendedXMP = file_get_contents( $xmpPath . 'xmpExt2.xmp' );

		$md5sum = '28C74E0AC2D796886759006FBE2E57B7'; // of xmpExt2.xmp
		$length = pack( 'N', strlen( $extendedXMP ) );
		$offset = pack( 'N', 2048 );
		$extendedPacket = $md5sum . $length . $offset . $extendedXMP;

		$reader = new XMPReader();
		$reader->parse( $standardXMP );
		$reader->parseExtended( $extendedPacket );
		$actual = $reader->getResults();

		$expected = array(
			'xmp-exif' => array(
				'DigitalZoomRatio' => '0/10',
				'Flash' => 9,
			)
		);

		$this->assertEquals( $expected, $actual );
	}
}
