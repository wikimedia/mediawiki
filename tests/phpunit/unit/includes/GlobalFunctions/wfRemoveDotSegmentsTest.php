<?php

/**
 * @group GlobalFunctions
 * @covers ::wfRemoveDotSegments
 */
class WfRemoveDotSegmentsTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider providePaths
	 */
	public function testWfRemoveDotSegments( $inputPath, $outputPath ) {
		$this->assertEquals(
			$outputPath,
			wfRemoveDotSegments( $inputPath ),
			"Testing $inputPath expands to $outputPath"
		);
	}

	/**
	 * Provider of URL paths for testing wfRemoveDotSegments()
	 *
	 * @return array
	 */
	public static function providePaths() {
		return [
			[ '/a/b/c/./../../g', '/a/g' ],
			[ 'mid/content=5/../6', 'mid/6' ],
			[ '/a//../b', '/a/b' ],
			[ '/.../a', '/.../a' ],
			[ '.../a', '.../a' ],
			[ '', '' ],
			[ '/', '/' ],
			[ '//', '//' ],
			[ '.', '' ],
			[ '..', '' ],
			[ '...', '...' ],
			[ '/.', '/' ],
			[ '/..', '/' ],
			[ './', '' ],
			[ '../', '' ],
			[ './a', 'a' ],
			[ '../a', 'a' ],
			[ '../../a', 'a' ],
			[ '.././a', 'a' ],
			[ './../a', 'a' ],
			[ '././a', 'a' ],
			[ '../../', '' ],
			[ '.././', '' ],
			[ './../', '' ],
			[ '././', '' ],
			[ '../..', '' ],
			[ '../.', '' ],
			[ './..', '' ],
			[ './.', '' ],
			[ '/../../a', '/a' ],
			[ '/.././a', '/a' ],
			[ '/./../a', '/a' ],
			[ '/././a', '/a' ],
			[ '/../../', '/' ],
			[ '/.././', '/' ],
			[ '/./../', '/' ],
			[ '/././', '/' ],
			[ '/../..', '/' ],
			[ '/../.', '/' ],
			[ '/./..', '/' ],
			[ '/./.', '/' ],
			[ 'b/../../a', '/a' ],
			[ 'b/.././a', '/a' ],
			[ 'b/./../a', '/a' ],
			[ 'b/././a', 'b/a' ],
			[ 'b/../../', '/' ],
			[ 'b/.././', '/' ],
			[ 'b/./../', '/' ],
			[ 'b/././', 'b/' ],
			[ 'b/../..', '/' ],
			[ 'b/../.', '/' ],
			[ 'b/./..', '/' ],
			[ 'b/./.', 'b/' ],
			[ '/b/../../a', '/a' ],
			[ '/b/.././a', '/a' ],
			[ '/b/./../a', '/a' ],
			[ '/b/././a', '/b/a' ],
			[ '/b/../../', '/' ],
			[ '/b/.././', '/' ],
			[ '/b/./../', '/' ],
			[ '/b/././', '/b/' ],
			[ '/b/../..', '/' ],
			[ '/b/../.', '/' ],
			[ '/b/./..', '/' ],
			[ '/b/./.', '/b/' ],
		];
	}
}
